<x-admin-layout :drk="true" title="Add Product">
  <x-admin.frame>
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
    
      <x-admin.title>Add Product</x-admin.title>
      <x-form.auth id="add-product-form">
        @csrf
        <div class="space-y-4">
          
          <div>
            <x-label.main>Product Name</x-label.main>
            <x-input.default name="name" type="text" required placeholder="Enter product name" />
          </div>

          <div>
            <x-label.main>Service</x-label.main>
            <x-input.select name="service_id" required>
              @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }} - @uppercase($service->category->name)</option>
              @endforeach
            </x-input.select>
          </div>

          <div>
            <x-label.main>SubCategory</x-label.main>
            <x-input.default class="optional" name="subcategory" type="text" placeholder="Enter Subcategory (optional)" />
          </div>
          
          <div>
            <x-label.main>Plan ID</x-label.main>
            <x-input.default class="optional" name="plan_id" type="number" placeholder="Enter plan ID (optional)" />
          </div>

          <div>
            <x-label.main>Buying Price</x-label.main>
            <x-input.default name="buying_price" type="number" step="0.01" required placeholder="Enter buying price" />
          </div>

          <div>
            <x-label.main>Reseller Price</x-label.main>
            <x-input.default name="reseller_price" type="number" step="0.01" required placeholder="Enter reseller price" />
          </div>

          <div>
            <x-label.main>Agent Price</x-label.main>
            <x-input.default name="agent_price" type="number" step="0.01" required placeholder="Enter agent price" />
          </div>

          <div>
            <x-label.main>Customer Price</x-label.main>
            <x-input.default name="customer_price" type="number" step="0.01" required placeholder="Enter customer price" />
          </div>

          <div>
            <x-label.main>Validity</x-label.main>
            <x-input.default name="validity" type="text" placeholder="Enter validity (optional)" />
          </div>

        </div>

        <div class="!mt-8 mb-12">
          <x-btn.default type="button" id="add-product-button">
            Save Product
          </x-btn.default>
        </div>
      </x-form.auth>
    </main>

    <script>
      submitForm('add-product-form', 'add-product-button', '{{ route('admin.insert.product') }}');
    </script>
  </x-admin.frame>
</x-admin-layout>