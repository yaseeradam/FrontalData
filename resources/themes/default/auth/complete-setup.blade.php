
@php
    $sitename = jannah_opt('site_name');
@endphp
<x-auth-layout :drk="false" txt="Hi, Complete Registration" txt2="Almost Done 🎉🎊!" txt3="Complete your registration to get started.">
    
            <x-form.auth id="setup-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-input.default name="first_name" type="text" autocomplete="first_name" required label="First Name" />
                    </div>
                    <div>
                        <x-input.default name="last_name" type="text" autocomplete="last_name" required label="Last Name" />
                    </div>
                    <div>
                        <x-input.default name="phone" type="number" autocomplete="phone" required label="Phone" />
                    </div>
                </div>
                <div> 
                    <x-btn.default type="button" id="setup-button">
                        Continue
                    </x-btn.default>
                </div>
            </x-form.auth>

    <script>
        submitForm('setup-form', 'setup-button', '{{ route('setup.save') }}');
    </script>
</x-auth-layout>