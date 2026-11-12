<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow p-6 moverflow-x-auto">
            <x-form.auth id="migrate-data-form" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Select Software</x-label.main>
                        <x-admin.input.select name="software" required>
                            <option disabled>Select Software</option>
                            <option value="adex" disabled>Adex</option>
                            <option value="msorg" disabled>Msorg</option>
                            <option value="topupmate" selected>Topupmate</option>
                            <option value="legacy-script" disabled>Legacy VTU Import</option>
                        </x-admin.input.select>
                    </div>

                    <div>
                        <x-label.main>Select Tables to Migrate</x-label.main>
                        <x-admin.input.select name="tables" required>
                            <option value="configuration" disabled>Configuration</option>
                            <option value="site-details" disabled>Site Details</option>
                            <option value="users" selected>Users</option>
                            <option value="transactions">Transactions</option>
                        </x-admin.input.select>
                    </div>
                </div>
                </div>
                 <div class="bg-white rounded shadow p-6 overflow-x-auto">
                    <div>
                        <x-label.main>Upload SQL File</x-label.main>
                        <x-admin.input.file name="sql_file" id="sql_file" type="file" required />
                    </div>
               
                
                <div class="!mt-8 mb-12">
                    <x-admin.btn.default type="button" id="migrate-data-button">
                        Migrate Data
                    </x-admin.btn.default>
                </div>
            </x-form.auth>
            </div>
        <script>
            submitForm('migrate-data-form', 'migrate-data-button', '{{ route('admin.switch.data') }}');
        </script>
   
      </div>
  
    </div>
        </div>
</x-admin-layout> 