<x-admin-layout :drk="true" title="Edit Notification">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Edit Notification</x-admin.title>
            <x-admin.form id="edit-notification-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Title</x-label.main>
                        <x-input.default name="title" type="text" required placeholder="Enter title"
                            value="{{ $notification->title }}" />
                    </div>

                    <div>
                        <x-label.main>Message</x-label.main>
                        <x-input.default name="message" type="text" required placeholder="Enter message"
                            value="{{ $notification->message }}" />
                    </div>

                    <div>
                        <x-label.main>Action URL</x-label.main>
                        <x-input.default name="action_url" type="url" placeholder="Enter action URL (optional)"
                            value="{{ $notification->action_url }}" />
                    </div>

                    <div>
                        <x-label.main>Status</x-label.main>
                        <x-input.select name="status" required>
                            <option value="active" {{ $notification->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $notification->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-input.select>
                    </div>
                      <x-input.default name="admin_id" class="hidden" value="1" />
                     <div>
                        <x-label.main>Date</x-label.main>
                        <x-input.default name="date" type="datetime-local" required placeholder="Enter date"
                            value="{{ \Carbon\Carbon::parse($notification->date)->format('Y-m-d\TH:i') }}" />
                    </div>
                </div>
                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="edit-notification-button">
                        Save Settings
                    </x-btn.default>
                </div>
            </x-admin.form>
        </main>
        <script>
            submitForm('edit-notification-form', 'edit-notification-button', '{{ route('admin.update.notification', ['id' => $notification->id]) }}');
        </script>
    </x-admin.frame>
</x-admin-layout>