<x-admin-layout :drk="true" title="Edit Service">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Edit Service</x-admin.title>
            <x-admin.form id="service-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Name</x-label.main>
                        <x-input.default name="name" type="text" required placeholder="Enter name" value="{{ $service->name }}" />
                    </div>

                    <div>
                        <x-label.main>Network ID</x-label.main>
                        <x-input.default name="network_id" type="text" required placeholder="Enter network ID" value="{{ $service->network_id }}" />
                    </div>

                    <div>
                        <x-label.main>Category</x-label.main>
                        <x-input.select name="category_id" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-input.select name="status" required>
                            <option value="enabled" {{ $service->status === 'enabled' ? 'selected' : '' }}>Enabled</option>
                            <option value="disabled" {{ $service->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </x-input.select>
                    </div>

                    <input type="hidden" name="id" value="{{ $service->id }}" />
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="service-save-button">
                        Save Service
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('service-form', 'service-save-button', '{{ route('admin.update.service', ['id' => $service->id]) }}');
        </script>
    </x-admin.frame>
</x-admin-layout>