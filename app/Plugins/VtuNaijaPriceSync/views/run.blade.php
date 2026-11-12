<x-admin-layout :drk="true" title="VTUNAIJA Sync Run" class="bg-[#f1f1f1]">
  <x-admin.header />
  <x-admin.side></x-admin.side>

  <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Sync Run #{{ $run->id }}</h1>
        <p class="text-gray-500">{{ ucfirst($run->product_type) }} · {{ ucfirst($run->status) }}</p>
      </div>
      <a href="{{ route('admin.vtunaija-price-sync.index', ['type' => $run->product_type]) }}" class="rounded-lg bg-gray-900 px-4 py-2 font-semibold text-white">Back</a>
    </div>

    @if(session('success'))
      <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif

    @php
      $plannedRun = in_array($run->status, ['previewed', 'changes_found', 'no_changes', 'failed'], true);
    @endphp

    <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4">
      <div class="rounded-xl bg-white p-4 shadow-sm"><div class="text-gray-500">Fetched</div><div class="text-xl font-bold">{{ $run->fetched_count }}</div></div>
      <div class="rounded-xl bg-white p-4 shadow-sm"><div class="text-gray-500">{{ $plannedRun ? 'To Create' : 'Created' }}</div><div class="text-xl font-bold">{{ $run->created_count }}</div></div>
      <div class="rounded-xl bg-white p-4 shadow-sm"><div class="text-gray-500">{{ $plannedRun ? 'To Update' : 'Updated' }}</div><div class="text-xl font-bold">{{ $run->updated_count }}</div></div>
      <div class="rounded-xl bg-white p-4 shadow-sm"><div class="text-gray-500">Skipped</div><div class="text-xl font-bold">{{ $run->skipped_count }}</div></div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
      <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
          <tr>
            <th class="px-4 py-3">External ID</th>
            <th class="px-4 py-3">Action</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Message</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($items as $item)
            <tr>
              <td class="px-4 py-3">{{ $item->external_id ?? '-' }}</td>
              <td class="px-4 py-3">{{ str_replace('_', ' ', $item->action) }}</td>
              <td class="px-4 py-3">{{ $item->status }}</td>
              <td class="px-4 py-3 text-gray-500">{{ $item->message }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</x-admin-layout>
