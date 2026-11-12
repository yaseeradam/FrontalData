<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="edit-gateway-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Name</x-label.main>
                        <x-admin.input.default name="name" type="text" required value="{{ $gateway->name }}" />
                    </div>

                    <div>
                        <x-label.main>API Token</x-label.main>
                        <x-admin.input.default name="api_token" type="text" required value="{{ $gateway->api_token }}" />
                    </div>

                    <div>
                        <x-label.main>Secret Token</x-label.main>
                        <x-admin.input.default name="secret_token" type="text" required value="{{ $gateway->secret_token }}" />
                    </div>

                    <div>
                        <x-label.main>Charge Method</x-label.main>
                        <x-admin.input.select name="charge_method" required>
                            <option value="fixed" {{ $gateway->charge_method === 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="percentage" {{ $gateway->charge_method === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>Business ID</x-label.main>
                        <x-admin.input.default name="business_id" type="text" class="optional" value="{{ $gateway->business_id }}" />
                    </div>

                    <div>
                        <x-label.main>Charges</x-label.main>
                        <x-admin.input.default name="charges" type="text" required value="{{ $gateway->charges }}" />
                    </div>
                    </div>
                    </div>
                    
                    
                     <div class="bg-white rounded shadow p-6 overflow-x-auto">
                          <div class="space-y-4">
                    <div>
                        <x-label.main>Identifier</x-label.main>
                        <x-admin.input.default name="identifier" type="text" required value="{{ $gateway->identifier }}" disabled />
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-admin.input.select name="status" required>
                            <option value="enabled" {{ $gateway->status === 'enabled' ? 'selected' : '' }}>Enabled</option>
                            <option value="disabled" {{ $gateway->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>Optional</x-label.main>
                        <x-admin.input.default name="optional" type="text" class="optional" value="{{ $gateway->optional }}" />
                    </div>
                
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="submit" id="edit-gateway-button">
                        Save Changes
                    </x-admin.btn.default>
                </div>
                </div>
            </x-admin.form>
       
        <script>
            submitForm('edit-gateway-form', 'edit-gateway-button', '{{ route('admin.gateway.update', ['id' => $gateway->id]) }}');
        </script>
     
      </div>
  
    </div>
        </div>
</x-admin-layout> 