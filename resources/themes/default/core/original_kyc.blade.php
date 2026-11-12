<x-form-layout :drk="false" title="Update Password">
    <x-app.frame2 title="Finish KYC Verification" des="Complete your KYC process for enhanced account verification and security">
        <x-app.form>
        <x-form.auth id="verify-kyc-form">
            @csrf
            <div class="space-y-4">
                <div>
                    <x-label.main>Choose ID Type</x-label.main>
                    <x-input.select name="id_type" required>
                        <option value="nin">NIN - National Identification Number</option>
                        <option value="bvn">BVN - Bank Verification Number</option>
                    </x-input.select>
                </div>
                <div>
                    <x-label.main>Phone Number</x-label.main>
                    <x-input.default name="phone_number" required placeholder="Confirm Phone Number" />
                </div>
                <div>
                    <x-label.main>Date Of Birth</x-label.main>
                    <x-input.default type="date" name="date_of_birth" required placeholder="Confirm Date Of Birth" />
                </div>
                <div>
                    <x-label.main>ID Number</x-label.main>
                    <x-input.default name="id" required placeholder="Enter ID Number" />
                </div>
                
                
            </div>
            <x-btn.default class="mt-8" id="verify-kyc-button">Verify Details</x-btn.default>
            
            
        </x-form.auth>
        </x-app.form>
    </x-app.frame2>

    <script>
        submitForm('verify-kyc-form', 'verify-kyc-button', '{{ route('core.kyc.verify') }}');
    </script>
</x-form-layout>