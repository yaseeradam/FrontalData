<x-admin-layout :drk="true" title="VTUNAIJA API Setup" class="bg-[#f1f1f1]">
  <x-admin.header />
  <x-admin.side></x-admin.side>

  <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">VTUNAIJA API Setup</h1>
        <p class="text-gray-500">Install the VTUNAIJA provider preset and connect it with your token.</p>
      </div>
      <a href="{{ route('admin.vtunaija-price-sync.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-800 hover:bg-gray-50">
        Back to sync
      </a>
    </div>

    @if($errors->any())
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
      <section class="xl:col-span-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <form method="POST" action="{{ route('admin.vtunaija-price-sync.setup.store') }}" class="space-y-5">
            @csrf

            <div>
              <label for="api_token" class="mb-2 block font-semibold text-gray-800">VTUNAIJA API token</label>
              <input
                id="api_token"
                name="api_token"
                type="password"
                value="{{ old('api_token') }}"
                placeholder="Paste your VTUNAIJA API token here"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                autocomplete="off"
                required
              >
            </div>

            <label class="flex items-start gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
              <input type="checkbox" name="replace_existing" value="1" class="mt-1 rounded border-gray-300 text-blue-600" checked>
              <span>
                <span class="block font-semibold text-gray-800">Make VTUNAIJA the default API for matching services</span>
                <span class="mt-1 block text-xs text-gray-500">Existing service configs are updated so purchases use the new token immediately.</span>
              </span>
            </label>

            <div class="flex justify-end">
              <button class="rounded-lg bg-green-600 px-5 py-2 font-semibold text-white hover:bg-green-700">
                Install preset
              </button>
            </div>
          </form>
        </div>
      </section>

      <aside class="space-y-6">
        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
          <h2 class="mb-4 font-bold text-gray-900">Current Setup</h2>
          <div class="grid grid-cols-2 gap-2 text-center text-xs">
            <div class="rounded-lg bg-gray-50 p-3">
              <div class="text-base font-bold text-gray-900">{{ number_format($setupStatus['categories'] ?? 0) }}</div>
              <div class="text-gray-500">Categories</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <div class="text-base font-bold text-gray-900">{{ number_format($setupStatus['services'] ?? 0) }}</div>
              <div class="text-gray-500">Services</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <div class="text-base font-bold text-gray-900">{{ number_format($setupStatus['providers'] ?? 0) }}</div>
              <div class="text-gray-500">Providers</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <div class="text-base font-bold text-gray-900">{{ number_format($setupStatus['configs'] ?? 0) }}</div>
              <div class="text-gray-500">Configs</div>
            </div>
          </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
          <h2 class="mb-3 font-bold text-gray-900">Preset Coverage</h2>
          <div class="space-y-2 text-xs text-gray-600">
            <div class="rounded-lg bg-gray-50 p-3">Wallet, airtime, data, cable TV, IUC validation, electricity, and meter validation.</div>
            <div class="rounded-lg bg-gray-50 p-3">Products are still fetched from VTUNAIJA on the sync screen so current provider prices and markup rules are used.</div>
          </div>
        </section>
      </aside>
    </div>
  </div>
</x-admin-layout>
