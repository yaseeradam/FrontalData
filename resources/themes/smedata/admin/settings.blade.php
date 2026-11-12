@php
$options = @get_all_opts();
@endphp
<x-admin-layout :drk="true" title="Dashboard">
  <x-admin.frame>
     
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
    
    <x-admin.title>Global Settings</x-admin.title>
    <x-admin.form id="save-settings-form">
        @csrf
        <div class="space-y-4">
    @foreach ($options as $key => $value)
        <div>
            <x-label.main>{{ ucfirst(str_replace('_', ' ', $key)) }}</x-label.main>

            @if ($value === 'enabled' || $value === 'disabled')
                <x-input.select name="{{ $key }}">
                    <option value="enabled" {{ $value === 'enabled' ? 'selected' : '' }}>{{ $value === 'enabled' ? 'Enabled' : 'Enable' }}</option>
                    <option value="disabled" {{ $value === 'disabled' ? 'selected' : '' }}>{{ $value === 'disabled' ? 'Disabled' : 'Disable' }}</option>
                </x-input.select>
            @else
                <x-input.default 
                    name="{{ $key }}" 
                    type="text" 
                    value="{{ $value }}" 
                    placeholder="{{ ucfirst(str_replace('_', ' ', $key)) }}" 
                />
            @endif
        </div>
    @endforeach
</div>
        
        <div class="!mt-8 mb-12">
          <x-btn.default type="button" id="save-settings-button">
           Save Settings
          </x-btn.default>
        </div>
      </x-admin.form>
    </main>
    <script>
    submitForm('save-settings-form', 'save-settings-button', '{{ route('admin.save.settings') }}');
  </script>
    </x-admin.frame>
</x-admin-layout>