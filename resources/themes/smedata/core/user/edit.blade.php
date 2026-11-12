<x-app-layout :user="$user">
    <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Edit Profile" fallback="{{ route('profile.edit') }}" />
        <x-form.auth id="edit-profile-form">
            @csrf
            <div class="space-y-4">
                <div>
                 
                    <x-input.default name="first_name" value="{{ $user->first_name }}" type="text" required label="First Name" />
                </div>
                <div>
                    
                    <x-input.default name="last_name" value="{{ $user->last_name }}" type="text" required label="Last Name" />
                </div>
                
                
                <div>
                 
                    <x-input.default name="phone" value="{{ $user->phone }}" type="text" required label="Phone" />
                </div>
            </div>
            <x-btn.default class="mt-4 mb-10" id="edit-profile-button">Save Changes</x-btn.default>
        </x-form.auth>
        </div>
        </div>

    <script>
        submitForm('edit-profile-form', 'edit-profile-button', '{{ route('core.user.update') }}');
    </script>
</x-app-layout>