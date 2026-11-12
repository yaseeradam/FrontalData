<x-admin-layout :drk="true" title="Dashboard" class="bg-[#f1f1f1]">
    <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
    
      <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!--        <div class="p-4 bg-white mb-4 border-l-4 border-green-500 md:col-span-2 text-sm">-->
<!--    <p>Your changes have been saved successfully.</p>-->
<!--</div>-->

        <div class="p-4 md:col-span-2">
     
      </div>
      
      
        <div class="bg-white p-6 rounded shadow md:col-span-2">
        <h2 class="text-xl font-bold mb-4">Plugin Manger</h2>
       
    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 border">Name</th>
                <th class="py-2 border">Developer</th>
                <th class="py-2 border">Version</th>
                <th class="py-2 border">Description</th>
                <th class="py-2 border">Website</th>
                <th class="py-2 border">Status</th>
                <th class="py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plugins as $plugin)
            <tr>
                <td class="py-2 border px-4">{{ $plugin->name }}</td>
                <td class="py-2 border px-4">{{ $plugin->developer ?? 'N/A' }}</td>
                <td class="py-2 border px-4">{{ $plugin->version ?? 'N/A' }}</td>
                <td class="py-2 border px-4">{{ $plugin->description ?? 'No description' }}</td>
                <td class="py-2 border px-4"><a href="https://developer.al-manarah.com.ng/{{ $plugin->username ?? '' }}" class="text-blue-700"/> Visit</a></td>
                <td class="py-2 border px-4">{{ ucfirst($plugin->status) }}</td>
                
                <td class="py-2 border px-4 space-x-2">
                    @if($plugin->status == 'inactive')
                        <form action="{{ route('admin.plugins.activate', $plugin->slug) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Activate</button>
                        </form>
                    @else
                        <form action="{{ route('admin.plugins.deactivate', $plugin->slug) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded">Deactivate</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.plugins.uninstall', $plugin->slug) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to uninstall this plugin?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded">Uninstall</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 text-center">No plugins found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
      </div>
      
      
      <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4"></h2>
          <div class="mt-8">
        <h2 class="text-xl font-bold mb-2">Install New Plugin</h2>
        <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="plugin" class="block font-medium">Plugin ZIP File</label>
                <input type="file" name="plugin" id="plugin" class="border p-2 rounded" required>
                @error('plugin')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Upload & Install Plugin</button>
        </form>
    </div>
      </div>
      
      
      
        </div>
        </div>
</x-admin-layout>