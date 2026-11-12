<x-admin-layout :drk="true" title="Add Service">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Add Services</x-admin.title>
            <x-admin.form id="add-service-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Name</x-label.main>
                        <x-input.default name="name" type="text" required placeholder="Enter service name" />
                    </div>
                   <div>
          <x-label.main>Category</x-label.main>
          <x-input.select name="category_id" required>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" >{{ $category->name }}</option>
            @endforeach
          </x-input.select>
        </div>
                    <div>
                        <x-label.main>Network ID</x-label.main>
                        <x-input.default name="network_id" type="text" placeholder="Enter network ID (optional)" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="add-service-button">
                        Save Service
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('add-service-form', 'add-service-button', '{{ route('admin.insert.service') }}');
        </script>
    </x-admin.frame>
</x-admin-layout>