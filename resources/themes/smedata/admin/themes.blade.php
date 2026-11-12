<x-admin-layout :drk="true" title="Themes">
    <x-admin.frame>
        <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
            <x-admin.title>Themes</x-admin.title>

            <div class="flex justify-between mb-4">
                <button class="bg-primary text-white px-4 py-2 rounded-lg text-xs" onclick="toggleUploadSection()">Add New Theme</button>
                <a href="https://marketplace.al-manarah.com.ng/themes" class="border border-lg border-[@jannah_opt('primary_color')] text-primary px-4 py-2 rounded-lg text-xs">Visit Theme Marketplace</a>
            </div>

            <div id="uploadSection" class="hidden mb-4 border border-gray-300 p-4 rounded-lg bg-white">
                <p class="text-sm mb-2">If you have a theme in a .zip format, you may install or update it by uploading it here.</p>
                <div class="flex items-center space-x-2">
                    <input type="file" id="uploadTheme" accept=".zip" onchange="enableInstallButton()" class="bg-gray-200 px-4 py-2 rounded-lg text-sm">
                    <button id="installButton" class="bg-primary text-white px-4 py-2 rounded-lg text-sm" disabled onclick="uploadTheme()">Install Now</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($themes as $theme)
                    <div class="bg-white border border-lg border-gray-500 relative">
                        <img src="{{ $theme['image'] }}" class="w-full h-40" alt="theme">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ $theme['name'] }}</h3>
                            <p class="text-xs">Version: {{ $theme['version'] }}</p>
                            <p class="text-xs">Developer: {{ $theme['developer'] }}</p>
                            <p class="mt-2 mb-4 text-xs">Description: {{ $theme['description'] }}</p>
                            <div class="flex justify-between space-x-3">
                                @if($theme['slug'] != @jannah_opt('preferred_theme'))
                                    <button class="border border-lg border-[@jannah_opt('primary_color')] text-primary px-4 py-2 rounded-lg mb-2 w-full text-xs" onclick="installTheme('{{ $theme['slug'] }}')">Activate</button>
                                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg mb-2 w-full text-xs" onclick="uninstallTheme('{{ $theme['slug'] }}')">Uninstall</button>
                                @else
                                    <button class="bg-green-500 text-white px-4 py-2 rounded-lg mb-2 w-full text-xs" disabled>Activated</button>
                                @endif
                            </div> 
                        </div>
                    </div> 
                @endforeach
            </div>
        </main>
    </x-admin.frame>

    <script>
        function toggleUploadSection() {
            const uploadSection = document.getElementById('uploadSection');
            uploadSection.classList.toggle('hidden');
        }

        function enableInstallButton() {
            document.getElementById('installButton').disabled = false;
        }

        function showToast(message, isError = false) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "center",
                background: isError ? "red" : "green",
            }).showToast();
        }

        function setProcessing(button, processing) {
            button.disabled = processing;
            button.innerText = processing ? 'Processing...' : button.dataset.originalText;
        }

        function uploadTheme() {
            const fileInput = document.getElementById('uploadTheme');
            const file = fileInput.files[0];
            const installButton = document.getElementById('installButton');
            if (file) {
                const formData = new FormData();
                formData.append('theme', file);
                installButton.dataset.originalText = installButton.innerText;
                setProcessing(installButton, true);
                fetch("{{ route('admin.theme.upload') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message, !data.success);
                    if (data.success) location.reload();
                    setProcessing(installButton, false);
                })
                .catch(() => {
                    showToast("Failed to upload theme.", true);
                    setProcessing(installButton, false);
                });
            }
        }

        function installTheme(theme) {
            const button = event.target;
            button.dataset.originalText = button.innerText;
            setProcessing(button, true);
            fetch("{{ route('admin.theme.install') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme })
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, !data.success);
                if (data.success) location.reload();
                setProcessing(button, false);
            })
            .catch(() => {
                showToast("Failed to activate theme.", true);
                setProcessing(button, false);
            });
        }

        function uninstallTheme(theme) {
            const button = event.target;
            button.dataset.originalText = button.innerText;
            setProcessing(button, true);
            fetch("{{ route('admin.theme.uninstall') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme })
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message, !data.success);
                if (data.success) location.reload();
                setProcessing(button, false);
            })
            .catch(() => {
                showToast("Failed to uninstall theme.", true);
                setProcessing(button, false);
            });
        }
    </script>
</x-admin-layout>
