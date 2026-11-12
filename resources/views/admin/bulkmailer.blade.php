<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 overflow-x-auto">
   
   
    <form id="bulk-mailer-form">
        @csrf

            <div>
                <x-label.main>Subject</x-label.main>
                <x-admin.input.default name="subject" type="text" required placeholder="Enter email subject" />
            </div>
            
             <div class="!mt-8 mb-12">
            <x-admin.btn.default type="button" id="send-bulk-mail-button">
                Send to All Users
            </x-admin.btn.default>
        </div>
    </div>
    
 <div class="bg-white rounded shadow p-6 overflow-x-auto">
     
            <div>
                <x-label.main>Content</x-label.main>
                <textarea id="email-content" name="content" required placeholder="Enter email content"></textarea>
            </div>
        </div>
        
       
    </form>
    
    
    
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">

    <script>
        var easyMDE = new EasyMDE({ element: document.getElementById("email-content"), spellChecker: false });
        
        document.getElementById('send-bulk-mail-button').addEventListener('click', function() {
            document.getElementById('email-content').value = easyMDE.value();
            submitForm('bulk-mailer-form', 'send-bulk-mail-button', '{{ route('admin.send.bulkmail') }}');
        });
    </script>
    
    
      </div>
  
    </div>
        </div>
</x-admin-layout> 