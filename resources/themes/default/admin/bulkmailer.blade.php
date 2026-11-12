<x-admin-layout :drk="true" title="Dashboard">
  <x-admin.frame>
     
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
    
    <x-admin.title>Bulk Mailer</x-admin.title>
    <x-form.auth id="bulk-mailer-form">
        @csrf
        <div class="space-y-4">
            <div>
                <x-label.main>Subject</x-label.main>
                <x-input.default name="subject" type="text" required placeholder="Enter email subject" />
            </div>

            <div>
                <x-label.main>Content</x-label.main>
                <textarea id="email-content" name="content" required placeholder="Enter email content"></textarea>
            </div>
        </div>
        
        <div class="!mt-8 mb-12">
            <x-btn.default type="button" id="send-bulk-mail-button">
                Send to All Users
            </x-btn.default>
        </div>
    </x-form.auth>
    
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">

    <script>
        var easyMDE = new EasyMDE({ element: document.getElementById("email-content"), spellChecker: false });
        
        document.getElementById('send-bulk-mail-button').addEventListener('click', function() {
            document.getElementById('email-content').value = easyMDE.value();
            submitForm('bulk-mailer-form', 'send-bulk-mail-button', '{{ route('admin.send.bulkmail') }}');
        });
    </script>
    
  </x-admin.frame>
</x-admin-layout>