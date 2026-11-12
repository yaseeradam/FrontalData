<x-admin-layout :drk="true" title="Edit Payment Gateway">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Edit Payment Gateway</x-admin.title>
            <x-admin.form id="edit-gateway-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Name</x-label.main>
                        <x-input.default name="name" type="text" required value="{{ $gateway->name }}" />
                    </div>

                    <div>
                        <x-label.main>API Token</x-label.main>
                        <x-input.default name="api_token" type="text" required value="{{ $gateway->api_token }}" />
                    </div>

                    <div>
                        <x-label.main>Secret Token</x-label.main>
                        <x-input.default name="secret_token" type="text" required value="{{ $gateway->secret_token }}" />
                    </div>

                    <div>
                        <x-label.main>Charge Method</x-label.main>
                        <x-input.select name="charge_method" required>
                            <option value="fixed" {{ $gateway->charge_method === 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="percentage" {{ $gateway->charge_method === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Business ID</x-label.main>
                        <x-input.default name="business_id" type="text" class="optional" value="{{ $gateway->business_id }}" />
                    </div>

                    <div>
                        <x-label.main>Charges</x-label.main>
                        <x-input.default name="charges" type="text" required value="{{ $gateway->charges }}" />
                    </div>

                    <div>
                        <x-label.main>Identifier</x-label.main>
                        <x-input.default name="identifier" type="text" required value="{{ $gateway->identifier }}" disabled />
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-input.select name="status" required>
                            <option value="enabled" {{ $gateway->status === 'enabled' ? 'selected' : '' }}>Enabled</option>
                            <option value="disabled" {{ $gateway->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Optional</x-label.main>
                        <x-input.default name="optional" type="text" class="optional" value="{{ $gateway->optional }}" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="submit" id="edit-gateway-button">
                        Save Changes
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('edit-gateway-form', 'edit-gateway-button', '{{ route('admin.gateway.update', ['id' => $gateway->id]) }}');
        </script>
    </x-admin.frame>
</x-admin-layout>
