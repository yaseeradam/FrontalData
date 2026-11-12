<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">
        
    <div class="bg-red-100 text-red-500 rounded shadow p-6 overflow-x-auto md:col-span-2 mb-1 text-center">
        Always ensure to delete old prices before setting auto on a new one to prevent errors and potential loses, airtime has been diabled server side to prevent errors, you can use other services for now!
    </div>
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
        <h2 class="text-xl font-bold mb-4">Auto Add Bulk Products</h2>
             <x-admin.form id="auto-add-form">
                @csrf
                <div class="space-y-4">
                     <div>
                        <x-label.main>API Provider</x-label.main>
                        <x-admin.input.select name="provider_id" required>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}">
                                    {{ $provider->name }}  
                                </option>
                            @endforeach
                        </x-admin.input.select>
                    </div>
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
                        <x-label.main>User Profit Margin (percentage)</x-label.main>
                        <x-admin.input.default name="user_percentage" type="text" required placeholder="Enter from 1 - 100" />
                    </div>
                    
                    <div>
                        <x-label.main>Reseller Profit Margin (percentage)</x-label.main>
                        <x-admin.input.default name="reseller_percentage" type="text" required placeholder="Enter from 1 - 100" />
                    </div>
                    
                    <div>
                        <x-label.main>Agent Profit Margin (percentage)</x-label.main>
                        <x-admin.input.default name="agent_percentage" type="text" required placeholder="Enter from 1 - 100" />
                    </div>
                   
                     <div>
                        <x-label.main>Subcategory</x-label.main>
                        <x-admin.input.default name="subcategory" type="text" class="optional" placeholder="Subcategory if applicable" optional=""/>
                    </div>
                   
                    <div class="!mt-8 mb-12">
                        <x-admin.btn.default type="button" id="auto-add-save-button">
                            Auto Add products
                        </x-admin.btn.default>
                    </div>
                    </div>
            </x-admin.form>
       
        <script>
            submitForm('auto-add-form', 'auto-add-save-button', '{{ route('admin.automate.add') }}');
        </script>
       
        
    </div>
    
    <div class="bg-white rounded shadow p-6 overflow-x-auto">
        <h2 class="text-xl font-bold mb-4">Bulk Delete Products</h2>
    <x-admin.form id="auto-delete-form">
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
                        <x-label.main>Subcategory</x-label.main>
                        <x-admin.input.default name="subcategory" type="text" class="optional" placeholder="Subcategory if applicable" optional=""/>
                    </div>
                   
                    <div class="!mt-8 mb-12">
                        <x-admin.btn.default type="button" id="auto-delete-save-button">
                            Delete Products
                        </x-admin.btn.default>
                    </div>
                    </div>
            </x-admin.form>
       
        <script>
            submitForm('auto-delete-form', 'auto-delete-save-button', '{{ route('admin.automate.delete') }}');
        </script>
       
        
   
    </div>
      </div>
  
    
    </div>
        </div>
</x-admin-layout> 