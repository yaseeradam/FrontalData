@php
$options = @get_all_opts();
@endphp

<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
   <x-admin.side></x-admin.side>

   <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
       <form id="save-settings-form">
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               
             
               <div class="bg-white rounded shadow p-6 w-full flex flex-col">
                   <div class="space-y-4">
                       @foreach ($options as $key => $value)
                           @if ($value !== 'enabled' && $value !== 'disabled')
                               <div>
                                   <x-label.main>{{ ucfirst(str_replace('_', ' ', $key)) }}</x-label.main>
                                   <x-admin.input.default 
                                       name="{{ $key }}" 
                                       type="text" 
                                       value="{{ $value }}" 
                                       placeholder="{{ ucfirst(str_replace('_', ' ', $key)) }}" 
                                   />
                               </div>
                           @endif
                       @endforeach
                   </div>
               </div>

              
               <div class="bg-white rounded shadow p-6 w-full flex flex-col">
                   <div class="space-y-4">
                       @foreach ($options as $key => $value)
                           @if ($value === 'enabled' || $value === 'disabled')
                               <div>
                                   <x-label.main>{{ ucfirst(str_replace('_', ' ', $key)) }}</x-label.main>
                                   <x-admin.input.select name="{{ $key }}">
                                       <option value="enabled" {{ $value === 'enabled' ? 'selected' : '' }}>Enabled</option>
                                       <option value="disabled" {{ $value === 'disabled' ? 'selected' : '' }}>Disabled</option>
                                   </x-admin.input.select>
                               </div>
                           @endif
                       @endforeach
                   </div>
               </div>
        

         
           <div class="bg-white rounded shadow p-6 w-full flex flex-col">
           
               <x-admin.btn.default type="button" id="save-settings-button">
                   Save Settings
               </x-admin.btn.default>
           
           </div>
           
           
       </form>

       <script>
           submitForm('save-settings-form', 'save-settings-button', '{{ route('admin.save.settings') }}');
       </script>
   </div>
</x-admin-layout>
