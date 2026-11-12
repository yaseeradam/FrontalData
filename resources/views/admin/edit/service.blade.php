<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="service-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Name</x-label.main>
                        <x-admin.input.default name="name" type="text" required placeholder="Enter name" value="{{ $service->name }}" />
                    </div>

                    <div>
                        <x-label.main>Network ID</x-label.main>
                        <x-admin.input.default name="network_id" type="text" required placeholder="Enter network ID" value="{{ $service->network_id }}" />
                    </div>

                    <div>
                        <x-label.main>Category</x-label.main>
                        <x-admin.input.select name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-admin.input.select name="status" required>
                            <option value="enabled" {{ $service->status === 'enabled' ? 'selected' : '' }}>Enabled</option>
                            <option value="disabled" {{ $service->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </x-admin.input.select>
                    </div>

                    <admin.input type="hidden" name="id" value="{{ $service->id }}" />
                </div>
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="service-save-button">
                        Save Service
                    </x-admin.btn.default>
                </div>
            </x-admin.form>
        
        <script>
            submitForm('service-form', 'service-save-button', '{{ route('admin.update.service', ['id' => $service->id]) }}');
        </script>
       
      </div>
  
    </div>
        </div>
</x-admin-layout> 