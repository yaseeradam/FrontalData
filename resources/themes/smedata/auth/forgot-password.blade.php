
    @php
        $sitename = jannah_opt('site_name');
    @endphp
            <x-auth-layout :drk="false" txt="Hi, Welcome Back" txt2="Reset Password" txt3="Enter your email associated with your account">
            <x-form.auth id="forgot-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input.default name="email" type="email" autocomplete="email" required label="Email" />
                    </div>
                </div>
                <div>
                    <x-btn.default type="button" id="forgot-button">
                        Verify Account
                    </x-btn.default>
                </div>
                
            </x-form.auth>
            <p class="text-center mt-6 text-sm md:fixed md:top-0 md:right-0 md:mr-6 ">Remembered Password?  <x-link.default href="{{ route('login') }}">
                          Login
                        </x-link.default> </p>
   

    <script>
        submitForm('forgot-form', 'forgot-button', '{{ route('password.email') }}');
    </script>
</x-auth-layout>