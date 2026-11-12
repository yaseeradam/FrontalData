<x-admin-layout :drk="true" title="API Providers">
  <x-admin.frame>
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
      <x-admin.title>API Providers</x-admin.title>
      <div class="flex flex-col">
        <div class="overflow-x-auto">
          <div class="min-w-full inline-block align-middle">
            <div class="border rounded-lg divide-y divide-gray-200">
              <div class="py-3 px-4 flex justify-between items-center">
                <div class="relative max-w-xs">
                  <label class="sr-only">Search</label>
                  <input type="text" name="hs-table-with-pagination-search" id="hs-table-with-pagination-search" class="py-2 px-3 ps-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="Search for items">
                  <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
                    <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="11" cy="11" r="8"></circle>
                      <path d="m21 21-4.3-4.3"></path>
                    </svg>
                  </div>
                </div>
                <a href="{{ route('admin.add.api_provider') }}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                  Add API Provider
                </a> 
              </div>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Identifier</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Category</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">API Endpoint</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach($apiProviders as $apiProvider)
                      <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $apiProvider->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $apiProvider->identifier }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $apiProvider->category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $apiProvider->api_endpoint }}</td>
                        <td class="px-6 py-4 text-end text-sm font-medium">
                          <a href="{{ route('admin.edit.api_provider', $apiProvider->id) }}" class="inline-flex items-center gap-x-2 text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">Edit</a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="py-1 px-4">
                <nav class="flex items-center space-x-1" aria-label="Pagination">
                  {{ $apiProviders->links() }}
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </x-admin.frame>
</x-admin-layout>