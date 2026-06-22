<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $source = (string) $request->string('source')->trim();

        if (! in_array($source, ['login', 'register'], true)) {
            $source = 'login';
        }

        $request->session()->put('google_auth_source', $source);

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        $source = $request->session()->pull('google_auth_source', 'login');

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $throwable) {
            report($throwable);

            return redirect()
                ->route($this->resolveFailureRoute($source))
                ->with('oauthError', __('We could not sign you in with Google. Please try again.'));
        }

        $email = $googleUser->getEmail();

        if ($email === null) {
            return redirect()
                ->route($this->resolveFailureRoute($source))
                ->with('oauthError', __('Google did not return an email address for this account.'));
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->first();

        if ($user === null) {
            $user = User::query()
                ->where('email', $email)
                ->first();
        }

        if ($user === null) {
            $user = User::create([
                'name' => $this->resolveName($googleUser),
                'email' => $email,
                'password' => Str::random(40),
                'google_id' => $googleUser->getId(),
            ]);

            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();

            event(new Registered($user));
        } else {
            $updates = [
                'google_id' => $googleUser->getId(),
            ];

            if ($user->email_verified_at === null) {
                $updates['email_verified_at'] = now();
            }

            if (blank($user->name)) {
                $updates['name'] = $this->resolveName($googleUser);
            }

            $user->forceFill($updates)->save();
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function resolveFailureRoute(string $source): string
    {
        return $source === 'register' ? 'register' : 'login';
    }

    private function resolveName(SocialiteUser $googleUser): string
    {
        $name = $googleUser->getName();

        if (is_string($name) && trim($name) !== '') {
            return trim($name);
        }

        $nickname = $googleUser->getNickname();

        if (is_string($nickname) && trim($nickname) !== '') {
            return trim($nickname);
        }

        $email = $googleUser->getEmail();

        if (is_string($email) && $email !== '') {
            return Str::before($email, '@');
        }

        return 'Google User';
    }
}
