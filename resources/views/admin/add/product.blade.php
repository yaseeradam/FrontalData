<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
      <form id="add-product-form">
        @csrf
        <div class="space-y-4">
          
          <div>
            <x-label.main>Product Name</x-label.main>
            <x-admin.input.default name="name" type="text" required placeholder="Enter product name" />
          </div>

          <div>
            <x-label.main>Service</x-label.main>
            <x-admin.input.select name="service_id" required>
              @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }} - @uppercase($service->category->name)</option>
              @endforeach
            </x-admin.input.select>
          </div>

          

          <div>
            <x-label.main>Buying Price</x-label.main>
            <x-admin.input.default name="buying_price" type="number" step="0.01" required placeholder="Enter buying price" />
          </div>

          <div>
            <x-label.main>Reseller Price</x-label.main>
            <x-admin.input.default name="reseller_price" type="number" step="0.01" required placeholder="Enter reseller price" />
          </div>

          <div>
            <x-label.main>Agent Price</x-label.main>
            <x-admin.input.default name="agent_price" type="number" step="0.01" required placeholder="Enter agent price" />
          </div>

          <div>
            <x-label.main>Customer Price</x-label.main>
            <x-admin.input.default name="customer_price" type="number" step="0.01" required placeholder="Enter customer price" />
          </div>

         

        </div>
        </div>
        
        
         <div class="bg-white rounded shadow p-6 overflow-x-auto">
                <div class="space-y-4">
             <div>
            <x-label.main>SubCategory</x-label.main>
            <x-admin.input.default class="optional" name="subcategory" type="text" placeholder="Enter Subcategory (optional)" />
          </div>
          
          <div>
            <x-label.main>Plan ID</x-label.main>
            <x-admin.input.default class="optional" name="plan_id" type="number" placeholder="Enter plan ID (optional)" />
          </div>
          
           <div>
            <x-label.main>Validity</x-label.main>
            <x-admin.input.default name="validity" type="text" placeholder="Enter validity (optional)" />
          </div>
          
        <div class="!mt-8 mb-12">
          <x-admin.btn.default type="button" id="add-product-button">
            Save Product
          </x-admin.btn.default>
        </div>
      </form>
   
    <script>
      submitForm('add-product-form', 'add-product-button', '{{ route('admin.insert.product') }}');
    </script>
     </div>
      </div>
  
    </div>
        </div>
</x-admin-layout> 