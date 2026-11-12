<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="add-service-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Name</x-label.main>
                        <x-admin.input.default name="name" type="text" required placeholder="Enter service name" />
                    </div>
                   <div>
          <x-label.main>Category</x-label.main>
          <x-admin.input.select name="category_id" required>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" >{{ $category->name }}</option>
            @endforeach
          </x-admin.input.select>
        </div>
                    <div>
                        <x-label.main>Network ID</x-label.main>
                        <x-admin.input.default name="network_id" type="text" placeholder="Enter network ID (optional)" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="add-service-button">
                        Save Service
                    </x-admin.btn.default>
                </div>
            </x-admin.form>
   
        <script>
            submitForm('add-service-form', 'add-service-button', '{{ route('admin.insert.service') }}');
        </script>
    
      </div>
  
    </div>
        </div>
</x-admin-layout> 