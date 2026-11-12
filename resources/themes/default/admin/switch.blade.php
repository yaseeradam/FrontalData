<x-admin-layout :drk="true" title="Migrate Data">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Jannah Switch</x-admin.title>
            <x-form.auth id="migrate-data-form" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-label.main>Select Software</x-label.main>
                        <x-input.select name="software" id="software" required>
                            <option disabled>Select Software</option>
                            <option value="adex" disabled>Adex</option>
                            <option value="msorg" disabled>Msorg</option>
                            <option value="topupmate">Topupmate</option>
                            <option value="legacy">Legacy VTU Import</option>
                        </x-input.select>
                    </div>

                    <div>
                        <x-label.main>Select Tables to Migrate</x-label.main>
                        <x-input.select name="tables" required>
                            <option value="configuration" disabled>Configuration</option>
                            <option value="site-details" disabled>Site Details</option>
                            <option value="users" selected>Users</option>
                            <option value="transactions">Transactions</option>
                        </x-input.select>
                    </div>

                    <div id="sql-file-section">
                        <x-label.main>Upload SQL File</x-label.main>
                        <x-input.file name="sql_file" id="sql_file" type="file" required />
                    </div>

                    <div id="json-notice" class="hidden">
                        <p class="text-warning">JSON, not SQL, is required for Legacy VTU Import.</p>
                    </div>
                </div>

                <div class="!mt-8 mb-12">
                    <x-btn.default type="button" id="migrate-data-button">
                        Migrate Data
                    </x-btn.default>
                </div>
            </x-form.auth>
        </main>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const softwareSelect = document.getElementById('software');
                const sqlFileSection = document.getElementById('sql-file-section');
                const jsonNotice = document.getElementById('json-notice');
                const sqlFileInput = document.getElementById('sql_file');

                softwareSelect.addEventListener('change', function () {
                    if (this.value === 'legacy-script') {
                        sqlFileSection.classList.add('hidden');
                        sqlFileInput.removeAttribute('required');
                        jsonNotice.classList.remove('hidden');
                    } else {
                        sqlFileSection.classList.remove('hidden');
                        sqlFileInput.setAttribute('required', 'required');
                        jsonNotice.classList.add('hidden');
                    }
                });
            });

            submitForm('migrate-data-form', 'migrate-data-button', '{{ route('admin.switch.data') }}');
        </script>
    </x-admin.frame>
</x-admin-layout>
