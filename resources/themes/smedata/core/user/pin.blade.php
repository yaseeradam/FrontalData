<x-app-layout :user="$user">
      <div class="col-span-full md:col-span-3">
        
        <x-form.auth id="update-pin-form">
            @csrf
            <div class="space-y-4">
                @if($user->pin == "1234")
                    <div>
                        <x-app.back-header title="Set Up Payment PIN" fallback="{{ route('profile.edit') }}" />
                
                        
                        <input style="display: none" name="old_pin" type="password" required value="1234" />
                    </div>
                @else
                <x-app.back-header title="Update PIN" fallback="{{ route('profile.edit') }}" />
                    <div>
                      
                        <x-input.default name="old_pin" type="password" required label="Old PIN" />
                    </div>
                @endif

                <div>
                  
                    <x-input.default name="pin" type="password" required label="New PIN" />
                </div>
                <div>
                   
                    <x-input.default name="pin_confirmation" type="password" required label="Confirm PIN" />
                </div>
            </div>
            <x-btn.default class="mt-8" id="update-pin-button">Update PIN</x-btn.default>
         </x-form.auth>
        </div>

    <script>
        submitForm('update-pin-form', 'update-pin-button', '{{ route('core.user.pin.update') }}');
    </script>
</x-app-layout>