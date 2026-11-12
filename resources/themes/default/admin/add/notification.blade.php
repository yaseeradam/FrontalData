<x-admin-layout :drk="true" title="Dashboard">
  <x-admin.frame>
     
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
    
    <x-admin.title>Add Notification</x-admin.title>
    <x-form.auth id="add-notification-form">
        @csrf
        <div class="space-y-4">
    <div>
        <x-label.main>Title</x-label.main>
        <x-input.default name="title" type="text" required placeholder="Enter title" />
    </div>

    <div>
        <x-label.main>Message</x-label.main>
        <x-input.default name="message" type="text" required placeholder="Enter message" />
    </div>

    <div>
        <x-label.main>Action URL</x-label.main>
        <x-input.default name="action_url" type="url" placeholder="Enter action URL (optional)" />
    </div>

    <div>
        <x-label.main>Status</x-label.main>
        <x-input.select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </x-input.select>
    </div>

    <div>
        <x-label.main>Date</x-label.main>
        <x-input.default name="date" type="datetime-local" required placeholder="Enter date" />
    </div>
</div>
    <div class="!mt-8 mb-12">
          <x-btn.default type="button" id="add-notification-button">
           Save Settings
          </x-btn.default>
        </div>
      </x-form.auth>
    </main>
    <script>
    submitForm('add-notification-form', 'add-notification-button', '{{ route('admin.add.notification') }}');
  </script> 
    </main>
    </x-admin.frame>
</x-app-layout>