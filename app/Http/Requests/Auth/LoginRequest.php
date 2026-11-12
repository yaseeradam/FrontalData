<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string'],
            'email' => ['required_without:user_token', 'string', 'email'],
            'user_token' => ['required_without:email', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('password');
    
        $userIdentifier = $this->input('user_token') ?? $this->input('email');

        $user = $this->input('user_token') 
            ? Auth::getProvider()->retrieveByCredentials(['email' => $userIdentifier]) ??
              Auth::getProvider()->retrieveByCredentials(['username' => $userIdentifier]) ??
              Auth::getProvider()->retrieveByCredentials(['phone' => $userIdentifier])
            : Auth::getProvider()->retrieveByCredentials(['email' => $userIdentifier]);

        if (!$user || !Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            RateLimiter::hit($this->throttleKey());

            $message = $this->isApiRequest()
                ? ['user_token' => trans('auth.failed')]
                : ['email' => trans('auth.failed')];

            throw ValidationException::withMessages($message);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Check if the request is an API request.
     */
    protected function isApiRequest(): bool
    {
        return $this->wantsJson() || $this->is('api/*');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            $this->isApiRequest() ? 'user_token' : 'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $identifier = $this->input('user_token') ?? $this->input('email');
        return Str::transliterate(Str::lower($identifier).'|'.$this->ip());
    }
}
