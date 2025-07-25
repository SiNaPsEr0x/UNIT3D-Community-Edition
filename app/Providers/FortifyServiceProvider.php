<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\FailedLoginAttempt;
use App\Models\Group;
use App\Models\User;
use App\Notifications\FailedLogin;
use App\Services\Unit3dAnnounce;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Responses\FailedPasswordResetLinkRequestResponse;
use Laravel\Fortify\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Handle redirects after successful login
        $this->app->instance(LoginResponse::class, new class () implements LoginResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse
            {
                $user = $request->user()->load('group:id,slug');

                // Check if user is disabled
                if ($user->group->slug === 'disabled') {
                    $user->group_id = Group::query()->where('slug', '=', 'user')->soleValue('id');
                    $user->can_download = true;
                    $user->disabled_at = null;
                    $user->save();

                    cache()->forget('user:'.$user->passkey);

                    Unit3dAnnounce::addUser($user);

                    return to_route('home.index')
                        ->with('success', trans('auth.welcome-restore'));
                }

                // Check if user has read the rules
                if ($request->user()->read_rules == 0) {
                    return redirect()->to(config('other.rules_url'))
                        ->with('warning', trans('auth.require-rules'));
                }

                // Fix for issue described in https://github.com/laravel/framework/pull/46133
                if ($rootUrlOverride = config('unit3d.root_url_override')) {
                    $url = redirect()->getIntendedUrl();

                    return $url === null ? $rootUrlOverride : redirect(
                        rtrim(
                            rtrim($rootUrlOverride, '/')
                            .parse_url($url, PHP_URL_PATH)
                            .'?'.parse_url($url, PHP_URL_QUERY),
                        )
                    );
                }

                return redirect()->intended()
                    ->with('success', trans('auth.welcome'));
            }
        });

        // Handle redirects before the registration form is shown
        $this->app->instance(RegisterViewResponse::class, new class () implements RegisterViewResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
            {
                if ($request->missing('code')) {
                    return view('auth.register');
                }

                return view('auth.register', ['code' => $request->query('code')]);
            }
        });

        $this->app->instance(VerifyEmailResponse::class, new class () implements VerifyEmailResponse {
            public function toResponse($request): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
            {
                $user = $request->user()->load('group:id,slug');

                if ($user->group->slug !== 'banned') {
                    if ($user->group->slug === 'validating') {
                        $user->can_download = true;
                        $user->group_id = Group::query()->where('slug', '=', 'user')->soleValue('id');
                        $user->save();

                        cache()->forget('user:'.$user->passkey);

                        Unit3dAnnounce::addUser($user);
                    }

                    // Check if user has read the rules
                    if ($user->read_rules == 0) {
                        return redirect()->to(config('other.rules_url'))
                            ->with('success', trans('auth.activation-success'))
                            ->with('warning', trans('auth.require-rules'));
                    }

                    return to_route('login')
                        ->with('success', trans('auth.activation-success'));
                }

                return to_route('login')
                    ->withErrors(trans('auth.activation-error'));
            }
        });

        $this->app->extend(FailedPasswordResetLinkRequestResponse::class, fn () => new SuccessfulPasswordResetLinkRequestResponse(Password::RESET_LINK_SENT));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by('fortify-login'.$request->ip()));
        RateLimiter::for('fortify-login-get', fn (Request $request) => Limit::perMinute(5)->by('fortify-login'.$request->ip()));
        RateLimiter::for('fortify-register-get', fn (Request $request) => Limit::perMinute(5)->by('fortify-register'.$request->ip()));
        RateLimiter::for('fortify-register-post', fn (Request $request) => Limit::perMinute(5)->by('fortify-register'.$request->ip()));
        RateLimiter::for('fortify-forgot-password-get', fn (Request $request) => Limit::perMinute(5)->by('fortify-forgot-password'.$request->ip()));
        RateLimiter::for('fortify-forgot-password-post', fn (Request $request) => Limit::perMinute(5)->by('fortify-forgot-password'.$request->ip()));
        RateLimiter::for('fortify-reset-password-get', fn (Request $request) => Limit::perMinute(5)->by('fortify-reset-password'.$request->ip()));
        RateLimiter::for('fortify-reset-password-post', fn (Request $request) => Limit::perMinute(5)->by('fortify-reset-password'.$request->ip()));
        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by('fortify-two-factor'.$request->session()->get('login.id')));

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.passwords.email'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.passwords.reset', ['request' => $request]));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request): \Illuminate\Database\Eloquent\Model {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'captcha'  => Rule::when(config('captcha.enabled'), 'hiddencaptcha')
            ]);

            $user = User::query()->where('username', $request->username)->first();

            if ($user === null) {
                throw ValidationException::withMessages([
                    Fortify::username() => __('auth.failed'),
                ]);
            }

            $password = Hash::check($request->password, $user->password);

            if ($password === false) {
                FailedLoginAttempt::create([
                    'user_id'    => $user->id,
                    'username'   => $request->username,
                    'ip_address' => $request->ip(),
                ]);

                $user->notify(new FailedLogin(
                    $request->ip() ?? 'Invalid IP'
                ));

                throw ValidationException::withMessages([
                    Fortify::username() => __('auth.failed'),
                ]);
            }

            if ($password === true) {
                $user->load('group:id,slug');

                // Check if user is activated
                if ($user->email_verified_at === null || $user->group->slug === 'validating') {
                    $request->session()->invalidate();

                    throw ValidationException::withMessages([
                        Fortify::username() => __('auth.not-activated'),
                    ]);
                }

                // Check if user is banned
                if ($user->group->slug === 'banned') {
                    $request->session()->invalidate();

                    throw ValidationException::withMessages([
                        Fortify::username() => __('auth.banned'),
                    ]);
                }

                return $user;
            }
        });
    }
}
