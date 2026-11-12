<x-admin-layout :drk="true" title="Users">
  <x-admin.header />
  <x-admin.side></x-admin.side>
  <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
    <div class="bg-white rounded-xl shadow">
      {{-- Toolbar --}}
      <div class="py-3 px-4 flex flex-wrap gap-3 justify-between items-center border-b border-gray-100">
        <div class="relative max-w-xs flex-1 min-w-[180px]">
          <input type="text" id="searchInput"
                 class="py-2 px-3 ps-9 block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                 placeholder="Search users…">
          <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
            <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
          </div>
        </div>
        <form method="GET" action="{{ route('admin.users') }}" id="paginationForm" class="flex items-center gap-2">
          @foreach(request()->except('per_page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
          @endforeach
          <label for="per_page" class="text-sm text-gray-500">Show</label>
          <select name="per_page" id="per_page" class="border rounded px-3 py-1 text-sm">
            @foreach([50,100,200,500,1000,10000] as $pp)
              <option value="{{ $pp }}" {{ request('per_page', 50) == $pp ? 'selected' : '' }}>{{ $pp >= 1000 ? ($pp/1000).'k' : $pp }}</option>
            @endforeach
          </select>
        </form>
        <script>
          document.getElementById('per_page').addEventListener('change', function () {
            document.getElementById('paginationForm').submit();
          });
        </script>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm" id="usersTable">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">User</th>
              <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Phone</th>
              <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase">Level</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Txns</th>
              <th class="px-4 py-3 text-end text-xs font-medium text-gray-500 uppercase">Wallet</th>
              <th class="px-4 py-3 text-end text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach($users as $user)
              <tr class="hover:bg-gray-50 cursor-pointer transition-colors"
                  onclick="window.location='{{ route('admin.user', $user->id) }}'">
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <span class="flex-shrink-0 size-9 rounded-full bg-blue-600 text-white grid place-items-center text-xs font-semibold uppercase">{{ substr($user->username, 0, 1) }}</span>
                    <div class="min-w-0">
                      <div class="font-medium text-gray-800 truncate">{{ '@'.$user->username }}</div>
                      <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 text-gray-600 hidden lg:table-cell">{{ $user->phone ?: '—' }}</td>
                <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 capitalize">{{ $user->role }}</span></td>
                <td class="px-4 py-3 text-center text-gray-600 hidden sm:table-cell">{{ $user->transactions->count() }}</td>
                <td class="px-4 py-3 text-end font-semibold text-gray-800 whitespace-nowrap">₦{{ number_format($user->wallet_balance, 2) }}</td>
                <td class="px-4 py-3 text-end whitespace-nowrap" onclick="event.stopPropagation()">
                  <a href="{{ route('admin.user', $user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                  <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="py-3 px-4 border-t border-gray-100">
        {{ $users->appends(request()->query())->links() }}
      </div>
    </div>
  </div>

  <script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
      const filter = this.value.toLowerCase();
      document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
      });
    });
  </script>
</x-admin-layout>
