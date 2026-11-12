<x-app-layout :user="$user">
    <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Update Password" fallback="{{ route('profile.edit') }}" />
        <x-form.auth id="update-password-form">
            @csrf
            <div class="space-y-4">
                <div>
                  
                    <x-input.password name="old_password" type="password" required label="Old Password" />
                </div>
                <div>
                   
                    <x-input.password name="password" type="password" required label="New Password" />
                </div>
                <div>
                  
                    <x-input.password name="password_confirmation" type="password" required label="Confirm New Password" />
                </div>
            </div>
            <x-btn.default class="mt-2" id="update-password-button">Update Password</x-btn.default>
            
        </x-form.auth>
       
    <script>
        submitForm('update-password-form', 'update-password-button', '{{ route('core.user.password.update') }}');
    </script>
</div>
</x-app-layout>