<x-admin-layout :drk="true" title="Products">
   <x-admin.header />
    <x-admin.side>
        
    </x-admin.side>
     <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
          
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
   
      <div class="bg-white rounded shadow md:col-span-2 overflow-x-auto">
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
                <a href="{{ route('admin.add.api_config') }}" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">
                  Add API Setting
                </a>
              </div>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="py-3 px-4 pe-0">
                        <div class="flex items-center h-5">
                          <input id="hs-table-pagination-checkbox-all" type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                          <label for="hs-table-pagination-checkbox-all" class="sr-only">Checkbox</label>
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Category</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Service</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Provider</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">API Token</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Status</th>
                      <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach($apiConfigs as $config)
                      <tr>
                        <td class="py-3 ps-4">
                          <div class="flex items-center h-5">
                            <input type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                            <label class="sr-only">Checkbox</label>
                          </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $config->service->category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $config->service->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $config->apiProvider->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $config->api_token }}</td> 
                        <td class="px-6 py-4 text-sm text-gray-800">{{ ucfirst($config->status) }}</td>
                        <td class="px-6 py-4 text-end text-sm font-medium">
                          <a href="{{ route('admin.edit.api_config', $config->id) }}" class="inline-flex items-center gap-x-2 text-blue-600 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none">Edit</a>
                          
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="py-1 px-4">
                <nav class="flex items-center space-x-1" aria-label="Pagination">
                  {{ $apiConfigs->links() }}
                </nav>
              </div>
            
               
      </div>
  
    </div>
        </div>
</x-admin-layout> 