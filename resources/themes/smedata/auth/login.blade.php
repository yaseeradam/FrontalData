@php
    $sitename = jannah_opt('site_name');
@endphp
<x-auth-layout :drk="false" txt="Hi, Welcome Back" :txt2="'Sign in to ' . $sitename" txt3="Enter your details below.">
    
            <x-form.auth id="login-form">
                @csrf
                <div class="">
                    <div>
                        <x-input.default name="user_token" type="text" autocomplete="email" required label="Email or Phone" />
                    </div>
                    <div>
                        <x-input.password name="password" type="password" autocomplete="current-password" required placeholder="Password" />
                    </div>
                    <div class="flex items-center justify-between mt-6">
                        <x-input.checkbox id="remember-me" name="remember" label="Remember me"/>
                        <x-link.default class="text-sm font-medium" href="{{ route('password.request') }}">
                           Forgot password?
                     </x-link.default>
                    </div>
                </div>
                <div class="">
                    <x-btn.default type="submit" id="login-button">
                        Login
                    </x-btn.default>
                </div>
            
            </x-form.auth>
            <p class="text-center mt-6 text-sm md:fixed md:top-0 md:right-0 md:mr-6 ">Don’t have an account?  <x-link.default href="{{ route('register') }}">
                           Get Started
                        </x-link.default> </p>
            <script>
                submitForm('login-form', 'login-button', '{{ route('login') }}');
            </script>
</x-auth-layout>