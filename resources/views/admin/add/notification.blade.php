<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
    <form id="add-notification-form">
        @csrf
        <div class="space-y-4">
    <div>
        <x-label.main>Title</x-label.main>
        <x-admin.input.default name="title" type="text" required placeholder="Enter title" />
    </div>

    <div>
        <x-label.main>Message</x-label.main>
        <x-admin.input.default name="message" type="text" required placeholder="Enter message" />
    </div>

    <div>
        <x-label.main>Action URL</x-label.main>
        <x-admin.input.default name="action_url" type="url" placeholder="Enter action URL (optional)" />
    </div>

    <div>
        <x-label.main>Status</x-label.main>
        <x-admin.input.select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </x-admin.input.select>
    </div>

    <div>
        <x-label.main>Date</x-label.main>
        <x-admin.input.default name="date" type="datetime-local" required placeholder="Enter date" />
    </div>
</div>
</div>

 <div class="bg-white rounded shadow p-6 overflow-x-auto">
    <div class="!mt-8 mb-12">
          <x-admin.btn.default type="button" id="add-notification-button">
           Save Settings
          </x-admin.btn.default>
        </div>
      </form>
    
    <script>
    submitForm('add-notification-form', 'add-notification-button', '{{ route('admin.add.notification') }}');
  </script> 
      
      </div>
  
    </div>
        </div>
</x-admin-layout> 