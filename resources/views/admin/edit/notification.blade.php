<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
            <x-admin.form id="edit-notification-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Title</x-label.main>
                        <x-admin.input.default name="title" type="text" required placeholder="Enter title"
                            value="{{ $notification->title }}" />
                    </div>

                    <div>
                        <x-label.main>Message</x-label.main>
                        <x-admin.input.default name="message" type="text" required placeholder="Enter message"
                            value="{{ $notification->message }}" />
                    </div>

                    <div>
                        <x-label.main>Action URL</x-label.main>
                        <x-admin.input.default name="action_url" type="url" placeholder="Enter action URL (optional)"
                            value="{{ $notification->action_url }}" />
                    </div>
                    </div>
                    </div>
                    
                     <div class="bg-white rounded shadow p-6 overflow-x-auto">
                          <div class="space-y-4">
                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-admin.input.select name="status" required>
                            <option value="active" {{ $notification->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $notification->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-admin.input.select>
                    </div>
                      <x-admin.input.default name="admin_id" class="hidden" value="1" />
                     <div>
                        <x-label.main>Date</x-label.main>
                        <x-admin.input.default name="date" type="datetime-local" required placeholder="Enter date"
                            value="{{ \Carbon\Carbon::parse($notification->date)->format('Y-m-d\TH:i') }}" />
                   
                </div>
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="edit-notification-button">
                        Save Settings
                    </x-admin.btn.default>
                </div>
                </div>
                </div>
            </x-admin.form>
      
        <script>
            submitForm('edit-notification-form', 'edit-notification-button', '{{ route('admin.update.notification', ['id' => $notification->id]) }}');
        </script>
      
      </div>
  
    </div>
        </div>
</x-admin-layout> 