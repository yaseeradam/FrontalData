<x-admin-layout :drk="true" title="Edit API Configuration">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Edit API Configuration</x-admin.title>
            <x-admin.form id="api-config-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Service</x-label.main>
                        <x-input.select name="service_id" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $config->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - @uppercase($service->category->name)
                                </option>
                            @endforeach
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>API Provider</x-label.main>
                        <x-input.select name="provider_id" required>
                            @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{ $config->provider_id == $provider->id ? 'selected' : '' }}>
                                    {{ $provider->name }} - @uppercase($provider->category->name) 
                                </option>
                            @endforeach
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>API Token</x-label.main>
                        <x-input.default name="api_token" type="text" required placeholder="Enter API token" value="{{ $config->api_token }}" />
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-input.select name="status" required>
                            <option value="enabled" {{ $config->status === 'enabled' ? 'selected' : '' }}>Enabled</option>
                            <option value="disabled" {{ $config->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </x-input.select>
                    </div>

                    <input type="hidden" name="id" value="{{ $config->id }}" />
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="api-config-save-button">
                        Save Configuration
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('api-config-form', 'api-config-save-button', '{{ route('admin.update.api_config', ['id' => $config->id]) }}');
        </script>
    </x-admin.frame>
</x-admin-layout>