<x-admin-layout :drk="true" title="Dashboard">
  <x-admin.frame>
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
      <x-admin.header />

      @if(!empty($priceSyncNotice))
        <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <div class="font-bold">{{ $priceSyncNotice->title }}</div>
              <div class="text-sm text-amber-800">{{ $priceSyncNotice->message }}</div>
            </div>
            <a href="{{ $priceSyncNotice->action_url ?: url('/admin/vtunaija-price-sync') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white">
              Review prices
            </a>
          </div>
        </div>
      @endif

      @if(!empty($priceSyncHealth))
        @php
          $healthTone = match($priceSyncHealth['status']) {
            'healthy' => 'border-green-200 bg-green-50 text-green-900',
            'changes_found' => 'border-amber-200 bg-amber-50 text-amber-900',
            'failed' => 'border-red-200 bg-red-50 text-red-900',
            default => 'border-gray-200 bg-white text-gray-900',
          };
        @endphp
        <div class="mb-4 rounded-xl border p-4 {{ $healthTone }}">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <div class="font-bold">VTUNAIJA Cron Health · {{ $priceSyncHealth['label'] }}</div>
              <div class="text-sm opacity-80">
                Last check:
                {{ $priceSyncHealth['last_checked_at'] ? \Carbon\Carbon::parse($priceSyncHealth['last_checked_at'])->format('d M Y, H:i') : 'Not run yet' }}
                · Next: {{ $priceSyncHealth['next_run'] }}
              </div>
            </div>
            <a href="{{ url('/admin/vtunaija-price-sync') }}" class="inline-flex items-center justify-center rounded-lg bg-white/80 px-4 py-2 text-sm font-semibold">
              Open sync
            </a>
          </div>
        </div>
      @endif
      
      <div class="swiper-container">
          <div class="swiper-wrapper">
        @foreach($walletBalances as $wallet)
            <x-block.slider name="{{ $wallet['name'] }}" bal="{{ $wallet['balance'] }}" />
        @endforeach
        </div>
    </div>
      <div class="p-2 mt-3 font-bold text-lg">Users Analytics</div>
      <ul class="flex flex-col">
        <x-block.list name="Total Users" :tp="true" :amt="$user_no" />
        <x-block.list name="Total Agents" :tp="true" :amt="$t_agent" />
        <x-block.list name="Total Resellers" :tp="true" :amt="$t_reseller" />
        <x-block.list name="Total Customers" :tp="true" :amt="$t_customer" />
         <x-block.list name="Total User Balance" :tp="true" :amt="'₦' . $t_user_bal"/>
      </ul>

      <!-- Transactions Analytics -->
      <div class="p-2 mt-3 font-bold text-lg">Transactions Analytics</div>
      <ul class="flex flex-col">
        <x-block.list name="Total Transactions" :tp="true" :amt="$transaction_no" />
        <x-block.list name="Total Successful" :tp="true" :amt="$t_successful" status="success" />
        <x-block.list name="Total Pending" :tp="true" :amt="$t_pending" status="pending" />
        <x-block.list name="Total Failed" :tp="true" :amt="$t_failed" status="failed" />
        <x-block.list name="Total Processing" :tp="true" :amt="$t_processing" status="processing" />
      </ul>

      <script>
        var swiper = new Swiper('.swiper-container', {
          slidesPerView: 2,
          spaceBetween: 16,
          breakpoints: {
            768: {
              slidesPerView: 4,
            }
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        });
      </script>
    </main>
  </x-admin.frame>
</x-admin-layout>
