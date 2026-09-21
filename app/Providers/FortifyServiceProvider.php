<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            $login = $request->input('email');
            $password = $request->input('password');

            $user = \App\Models\User::where('email', $login)
                ->orWhere('userid', $login)
                ->first();

            if (!$user) {
                return null;
            }

            $stored = $user->getAuthPassword();

            // rAthena plaintext (use_MD5_passwords: no — default)
            if ($stored === $password) {
                return $user;
            }

            // rAthena MD5 (use_MD5_passwords: yes)
            if ($stored === md5($password)) {
                return $user;
            }

            // Laravel bcrypt fallback (caso a senha tenha sido migrada)
            if (app('hash')->needsRehash($stored) === false && app('hash')->check($password, $stored)) {
                return $user;
            }

            return null;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $this->app->extend(\Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable::class, function ($repository, $app) {
            return app(RedirectIfTwoFactorAuthenticatable::class);
        });
    }
}
