   @php
        $sitename = jannah_opt('site_name');
    @endphp
            <x-auth-layout :drk="false" txt="Hi, Welcome Back" txt2="Reset Your Password" txt3="Please enter your new password.">
            <x-form.auth id="password-reset-form">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}" />
                <div class="space-y-4">
                    <div>
                        <x-input.default name="email" type="email" autocomplete="username" required label="Email" :value="old('email', $request->email)" />
                    </div>
                    <div>
                        <x-input.password name="password" type="password" autocomplete="new-password" required labelr="New password" />
                    </div>
                    <div>
                        <x-input.password name="password_confirmation" type="password" autocomplete="new-password" required label="Confirm password" />
                    </div>
                </div>
                <div>
                    <x-btn.default type="button" id="password-reset-button">
                        Reset Password
                    </x-btn.default>
                </div>
            </x-form.auth>

    <script>
        submitForm('password-reset-form', 'password-reset-button', '{{ route('password.store') }}');
    </script>
</x-form-layout>