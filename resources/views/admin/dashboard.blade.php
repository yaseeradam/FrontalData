<x-admin-layout :drk="true" title="Dashboard" class="bg-[#f1f1f1]">
  <x-admin.header />
  <x-admin.side></x-admin.side>

  <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
    @if(!empty($priceSyncNotice))
      <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <div class="flex items-start gap-3">
            <span class="mt-0.5 grid size-9 place-items-center rounded-full bg-amber-100 text-amber-700">
              <i class="ri-price-tag-3-line text-lg"></i>
            </span>
            <div>
              <h2 class="font-bold text-amber-950">{{ $priceSyncNotice->title }}</h2>
              <p class="mt-1 text-sm text-amber-800">{{ $priceSyncNotice->message }}</p>
            </div>
          </div>
          <a href="{{ $priceSyncNotice->action_url ?: url('/admin/vtunaija-price-sync') }}" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
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
        $healthIconTone = match($priceSyncHealth['status']) {
          'healthy' => 'bg-green-100 text-green-700',
          'changes_found' => 'bg-amber-100 text-amber-700',
          'failed' => 'bg-red-100 text-red-700',
          default => 'bg-gray-100 text-gray-700',
        };
      @endphp
      <div class="mb-6 rounded-xl border p-4 shadow-sm {{ $healthTone }}">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
          <div class="flex items-start gap-3">
            <span class="grid size-10 place-items-center rounded-full {{ $healthIconTone }}">
              <i class="ri-pulse-line text-xl"></i>
            </span>
            <div>
              <h2 class="font-bold">VTUNAIJA Cron Health · {{ $priceSyncHealth['label'] }}</h2>
              <p class="mt-1 text-sm opacity-80">
                Last check:
                {{ $priceSyncHealth['last_checked_at'] ? \Carbon\Carbon::parse($priceSyncHealth['last_checked_at'])->format('d M Y, H:i') : 'Not run yet' }}
                · Next check: {{ $priceSyncHealth['next_run'] }}
              </p>
              @if(!empty($priceSyncHealth['last_message']))
                <p class="mt-1 text-sm opacity-80">{{ $priceSyncHealth['last_message'] }}</p>
              @endif
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2 text-center text-xs sm:min-w-80">
            <div class="rounded-lg bg-white/70 p-3">
              <div class="font-bold text-base">{{ number_format($priceSyncHealth['fetched_count']) }}</div>
              <div class="opacity-70">Fetched</div>
            </div>
            <div class="rounded-lg bg-white/70 p-3">
              <div class="font-bold text-base">{{ number_format($priceSyncHealth['pending_changes']) }}</div>
              <div class="opacity-70">Pending</div>
            </div>
            <a href="{{ url('/admin/vtunaija-price-sync') }}" class="rounded-lg bg-white/70 p-3 font-semibold hover:bg-white">
              <div class="text-base"><i class="ri-arrow-right-line"></i></div>
              <div>Open</div>
            </a>
          </div>
        </div>
      </div>
    @endif

    {{-- Wallet balances --}}
    <div class="mb-6">
      <div class="swiper-container">
        <div id="wallet-balances" class="swiper-wrapper" data-wallet-url="{{ route('admin.wallet-balances') }}">
          @for($i = 0; $i < 4; $i++)
            <div class="swiper-slide bg-white border border-gray-200 p-4 rounded-xl shadow-sm animate-pulse">
              <div class="h-3 w-24 bg-gray-200 rounded mb-4"></div>
              <div class="h-5 w-32 bg-gray-200 rounded"></div>
            </div>
          @endfor
        </div>
      </div>
    </div>

    {{-- Quick actions --}}
    <h2 class="text-lg font-bold mb-3">Quick Actions</h2>
    @php
      $actions = [
        ['Users', 'ri-group-line', route('admin.users')],
        ['Transactions', 'ri-exchange-funds-line', route('admin.transactions')],
        ['Products', 'ri-shopping-bag-3-line', route('admin.products')],
        ['Payments', 'ri-bank-card-line', route('admin.gateways')],
        ['Analytics', 'ri-bar-chart-box-line', route('admin.analytics')],
        ['Notifications', 'ri-notification-3-line', route('admin.notifications')],
        ['Services', 'ri-apps-2-line', route('admin.services')],
        ['Settings', 'ri-settings-3-line', route('admin.settings')],
      ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
      @foreach($actions as [$label, $icon, $url])
        <a href="{{ $url }}" class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col items-center justify-center gap-2 text-center shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
          <span class="size-10 rounded-full bg-blue-50 grid place-items-center">
            <i class="{{ $icon }} text-xl text-blue-600"></i>
          </span>
          <span class="text-xs font-medium text-gray-700">{{ $label }}</span>
        </a>
      @endforeach
    </div>

    {{-- Analytics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Users Analytics</h2>
        <ul class="flex flex-col">
          <x-block.list name="Total Users" :tp="true" :amt="$user_no" />
          <x-block.list name="Total Agents" :tp="true" :amt="$t_agent" />
          <x-block.list name="Total Resellers" :tp="true" :amt="$t_reseller" />
          <x-block.list name="Total Customers" :tp="true" :amt="$t_customer" />
          <x-block.list name="Total User Balance" :tp="true" :amt="'₦' . $t_user_bal"/>
        </ul>
      </div>

      <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Transactions Analytics</h2>
        <ul class="flex flex-col">
          <x-block.list name="Total Transactions" :tp="true" :amt="$transaction_no" />
          <x-block.list name="Total Successful" :tp="true" :amt="$t_successful" status="success" />
          <x-block.list name="Total Pending" :tp="true" :amt="$t_pending" status="pending" />
          <x-block.list name="Total Failed" :tp="true" :amt="$t_failed" status="failed" />
          <x-block.list name="Total Processing" :tp="true" :amt="$t_processing" status="processing" />
        </ul>
      </div>
    </div>

    {{-- Recent transactions --}}
    <div class="flex items-center justify-between mt-6 mb-3">
      <h2 class="text-lg font-bold">Recent Transactions</h2>
      <a href="{{ route('admin.transactions') }}" class="text-sm text-blue-600 hover:underline font-medium">View all</a>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
      @forelse($transactions->sortByDesc('created_at')->take(6) as $t)
        @php
          $sc = match($t->status) {
            'successful' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            'pending', 'processing' => 'bg-yellow-100 text-yellow-700',
            default => 'bg-gray-100 text-gray-700',
          };
        @endphp
        <a href="{{ route('admin.transaction', $t->id) }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors">
          <div class="min-w-0 flex-1">
            <div class="text-sm font-medium text-gray-800 capitalize truncate">{{ strtolower($t->biller) }} · {{ $t->product }}</div>
            <div class="text-xs text-gray-500 truncate">{{ '@'.($t->user->username ?? 'N/A') }} · {{ \Carbon\Carbon::parse($t->created_at)->format('d M, H:i') }}</div>
          </div>
          <div class="text-sm font-semibold text-gray-800 whitespace-nowrap">₦{{ number_format($t->amount, 2) }}</div>
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium capitalize {{ $sc }}">{{ $t->status }}</span>
        </a>
      @empty
        <div class="p-4 text-sm text-gray-500 text-center">No transactions yet.</div>
      @endforelse
    </div>
  </div>

  <script>
    var walletSwiper = new Swiper('.swiper-container', {
      slidesPerView: 2,
      spaceBetween: 16,
      breakpoints: { 768: { slidesPerView: 4 } },
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });

    (function () {
      const walletWrapper = document.getElementById('wallet-balances');

      if (!walletWrapper) {
        return;
      }

      const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
      }[char]));

      const walletSlide = (wallet) => `
        <div class="swiper-slide bg-primary p-4 rounded-xl">
          <h2 class="text-sm mb-2 text-white">${escapeHtml(wallet.name)}</h2>
          <p class="font-bold text-white">${escapeHtml(wallet.balance)}</p>
        </div>
      `;

      const emptySlide = (message) => `
        <div class="swiper-slide bg-white border border-gray-200 p-4 rounded-xl text-sm text-gray-500">
          ${escapeHtml(message)}
        </div>
      `;

      fetch(walletWrapper.dataset.walletUrl, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error('Unable to load wallets.');
          }

          return response.json();
        })
        .then((data) => {
          const wallets = Array.isArray(data.wallets) ? data.wallets : [];
          walletWrapper.innerHTML = wallets.length
            ? wallets.map(walletSlide).join('')
            : emptySlide('No wallets found.');
          walletSwiper.update();
        })
        .catch(() => {
          walletWrapper.innerHTML = emptySlide('Wallet balances could not be loaded.');
          walletSwiper.update();
        });
    })();
  </script>
</x-admin-layout>
