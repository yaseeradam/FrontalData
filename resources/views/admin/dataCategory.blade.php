<x-admin-layout :drk="true" title="Products">
    <x-admin.header />
    <x-admin.side></x-admin.side>

    <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded shadow p-6 overflow-x-auto">
                <h2 class="text-xl font-bold mb-4">Enable Category</h2>
                <x-form.auth id="enable-category-form" method="POST" action="{{ route('admin.data.category.update') }}">
                    @csrf
                    <input type="hidden" name="status" value="enabled">
                    <div>
                        <x-label.main>Select Category</x-label.main>
                        <x-admin.input.select name="category" required>
                            <option disabled selected>Select Category</option>
                            @foreach ($disabledCategories as $category)
                                <option value="{{ $category['subcategory'] }}" data-service="{{ $category['service_id'] }}">
                                    {{ ucfirst($category['subcategory']) }} ({{ $category['service_name'] }})
                                </option>
                            @endforeach
                        </x-admin.input.select>
                        <input type="hidden" name="service_id" id="enable-service-id">
                    </div>
                    <div class="mt-8">
                        <x-admin.btn.success type="submit">Enable Category</x-admin.btn.success>
                    </div>
                </x-form.auth>
            </div>

            <div class="bg-white rounded shadow p-6 overflow-x-auto">
                <h2 class="text-xl font-bold mb-4">Disable Category</h2>
                <x-form.auth id="disable-category-form" method="POST" action="{{ route('admin.data.category.update') }}">
                    @csrf
                    <input type="hidden" name="status" value="disabled">
                    <div>
                        <x-label.main>Select Category</x-label.main>
                        <x-admin.input.select name="category" required>
                            <option disabled selected>Select Category</option>
                            @foreach ($enabledCategories as $category)
                                <option value="{{ $category['subcategory'] }}" data-service="{{ $category['service_id'] }}">
                                    {{ ucfirst($category['subcategory']) }} ({{ $category['service_name'] }})
                                </option>
                            @endforeach
                        </x-admin.input.select>
                        <input type="hidden" name="service_id" id="disable-service-id">
                    </div>
                    <div class="mt-8">
                        <x-admin.btn.danger type="submit">Disable Category</x-admin.btn.danger>
                    </div>
                </x-form.auth>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateServiceId(select) {
                let selectedOption = select.options[select.selectedIndex];
                let serviceId = selectedOption.getAttribute('data-service');
                let formId = select.closest('form').id;

                if (formId === 'enable-category-form') {
                    document.getElementById('enable-service-id').value = serviceId || '';
                } else if (formId === 'disable-category-form') {
                    document.getElementById('disable-service-id').value = serviceId || '';
                }
            }

            document.querySelectorAll('select[name="category"]').forEach(select => {
                select.addEventListener('change', function () {
                    updateServiceId(this);
                });
                updateServiceId(select);
            });
        });
    </script>

</x-admin-layout>