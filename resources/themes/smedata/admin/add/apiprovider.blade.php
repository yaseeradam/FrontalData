<x-admin-layout :drk="true" title="Add API Provider">
  <x-admin.frame>
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
      <x-admin.title>Add API Provider</x-admin.title>
      <x-admin.form id="create-api-provider-form">
        @csrf
        <div class="space-y-4">
          <div>
            <x-label.main>Name</x-label.main>
            <x-input.default name="name" type="text" required placeholder="Enter name" />
          </div>

          <div>
            <x-label.main>Identifier</x-label.main>
            <x-input.default name="identifier" type="text" required placeholder="Enter identifier" />
          </div>

          <div>
            <x-label.main>Category</x-label.main>
            <x-input.select name="category_id" required>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </x-input.select>
          </div>

          <div>
            <x-label.main>API Endpoint</x-label.main>
            <x-input.default name="api_endpoint" type="url" required placeholder="Enter API endpoint" />
          </div>

        </div>
        <div class="!mt-8 mb-12">
          <x-btn.default type="button" id="create-api-provider-button">
            Add API Provider
          </x-btn.default>
        </div>
      </x-admin.form>
    </main>
    <script>
      submitForm('create-api-provider-form', 'create-api-provider-button', '{{ route('admin.insert.api_provider') }}');
    </script>
  </x-admin.frame>
</x-admin-layout>