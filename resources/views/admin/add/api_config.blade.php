<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="api-config-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Service</x-label.main>
                        <x-admin.input.select name="service_id" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name }} - @uppercase($service->category->name)
                                </option>
                            @endforeach
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>API Provider</x-label.main>
                        <x-admin.input.select name="provider_id" required>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">
                                    {{ $provider->name }} - @uppercase($provider->category->name) 
                                </option>
                            @endforeach
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>API Token</x-label.main>
                        <x-admin.input.default name="api_token" type="text" required placeholder="Enter API token" />
                    </div>
                    </div>
                    </div>
                     <div class="bg-white rounded shadow p-6 overflow-x-auto">
                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-admin.input.select name="status" required>
                            <option value="enabled">Enabled</option>
                            <option value="disabled">Disabled</option>
                        </x-admin.input.select>
                    </div>
           
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="api-config-save-button">
                        Save Configuration
                    </x-admin.btn.default>
                </div>
            </x-admin.form>
       
        <script>
            submitForm('api-config-form', 'api-config-save-button', '{{ route('admin.insert.api_config') }}');
        </script>
       
      </div>
  
    </div>
        </div>
</x-admin-layout> 