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
                
                 <form method="GET" action="{{ route('admin.categories') }}" id="paginationForm">
                    @foreach(request()->except('per_page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                
                    <label for="per_page" class="text-sm">Show</label>
                    <select name="per_page" id="per_page" class="border rounded px-3 py-1 text-sm">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                
                <script>
                    document.getElementById('per_page').addEventListener('change', function() {
                        document.getElementById('paginationForm').submit();
                    });
                </script>
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
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                      <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">ID</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    @foreach($categories as $category)
                      <tr>
                        <td class="py-3 ps-4">
                          <div class="flex items-center h-5">
                            <input type="checkbox" class="border-gray-200 rounded text-blue-600 focus:ring-blue-500">
                            <label class="sr-only">Checkbox</label>
                          </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $category->id }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="py-1 px-4">
                <nav class="flex items-center space-x-1" aria-label="Pagination">
                  {{ $categories->appends(request()->query())->links() }}
                </nav>
              </div>
               
      </div>
  
    </div>
        </div>
</x-admin-layout> 