<x-admin-layout :drk="true" title="VTUNAIJA Price Sync" class="bg-[#f1f1f1]">
  <x-admin.header />
  <x-admin.side></x-admin.side>

  <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">VTUNAIJA Price Sync</h1>
        <p class="text-gray-500">Fetch provider catalogs, review changes, then apply selected product updates.</p>
      </div>
      <div class="flex flex-col gap-2 sm:flex-row">
        <a href="{{ route('admin.vtunaija-price-sync.setup') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 font-semibold text-gray-800 hover:bg-gray-50">
          API Setup
        </a>
        <form method="POST" action="{{ route('admin.vtunaija-price-sync.preview') }}" class="flex flex-col gap-2 sm:flex-row">
          @csrf
          <select name="product_type" class="rounded-lg border-gray-300 text-sm">
            @foreach($adapters as $key => $adapter)
              <option value="{{ $key }}" @selected($productType === $key)>{{ $adapter->label() }}</option>
            @endforeach
          </select>
          <button class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white disabled:opacity-50" @disabled(!$apiReady)>
            Fetch Latest
          </button>
        </form>
      </div>
    </div>

    @if(session('success'))
      <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{{ $errors->first() }}</div>
    @endif
    @unless($apiReady)
      <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-yellow-700">
        No enabled VTUNAIJA API config was found. Add or enable a VTUNAIJA provider config before fetching prices.
        <a href="{{ route('admin.vtunaija-price-sync.setup') }}" class="font-semibold underline">Set up VTUNAIJA</a>.
      </div>
    @endunless

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
      <section class="xl:col-span-2">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
          <div class="flex items-center justify-between border-b border-gray-100 p-4">
            <h2 class="font-bold text-gray-900">Preview</h2>
            @if(!empty($previewRunId))
              <span class="text-xs text-gray-500">Preview run #{{ $previewRunId }}</span>
            @endif
          </div>

          @if(count($rows))
            <form method="POST" action="{{ route('admin.vtunaija-price-sync.apply') }}">
              @csrf
              <input type="hidden" name="product_type" value="{{ $productType }}">
              <textarea name="payload" class="hidden">{{ json_encode($payload) }}</textarea>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                  <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                    <tr>
                      <th class="px-4 py-3">Apply</th>
                      <th class="px-4 py-3">Provider Plan</th>
                      <th class="px-4 py-3">Current</th>
                      <th class="px-4 py-3">Proposed</th>
                      <th class="px-4 py-3">Action</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">
                    @foreach($rows as $i => $row)
                      @php
                        $canApply = in_array($row['action'], ['create', 'update']);
                        $badge = match($row['action']) {
                          'create' => 'bg-green-100 text-green-700',
                          'update' => 'bg-blue-100 text-blue-700',
                          'unchanged' => 'bg-gray-100 text-gray-600',
                          'needs_review' => 'bg-yellow-100 text-yellow-700',
                          default => 'bg-red-100 text-red-700',
                        };
                      @endphp
                      <tr>
                        <td class="px-4 py-3 align-top">
                          <input type="checkbox" name="selected[]" value="{{ $i }}" class="rounded border-gray-300 text-blue-600" @checked($canApply) @disabled(!$canApply)>
                        </td>
                        <td class="px-4 py-3 align-top">
                          <div class="font-semibold text-gray-900">{{ $row['item']['product_name'] }}</div>
                          <div class="text-xs text-gray-500">{{ $row['item']['service_name'] }} · ID {{ $row['item']['external_id'] }}</div>
                          @if($row['item']['subcategory'])
                            <div class="mt-1 text-xs text-gray-500">{{ $row['item']['subcategory'] }}</div>
                          @endif
                          @if($row['message'])
                            <div class="mt-1 text-xs text-yellow-700">{{ $row['message'] }}</div>
                          @endif
                        </td>
                        <td class="px-4 py-3 align-top text-xs text-gray-600">
                          @if($row['product'])
                            <div>{{ $row['product']['name'] }}</div>
                            <div>Buy: {{ number_format($row['product']['buying_price'], 2) }}</div>
                            <div>Customer: {{ number_format($row['product']['customer_price'], 2) }}</div>
                          @else
                            <span class="text-gray-400">Not linked</span>
                          @endif
                        </td>
                        <td class="px-4 py-3 align-top text-xs text-gray-600">
                          <div>{{ $row['proposed']['name'] }}</div>
                          <div>Buy: {{ number_format($row['proposed']['buying_price'], 2) }}</div>
                          <div>Customer: {{ number_format($row['proposed']['customer_price'], 2) }}</div>
                          <div>Reseller: {{ number_format($row['proposed']['reseller_price'], 2) }}</div>
                          <div>Agent: {{ number_format($row['proposed']['agent_price'], 2) }}</div>
                          @if($row['proposed']['markup_rule'])
                            @php
                              $rule = $row['proposed']['markup_rule'];
                              $range = match (true) {
                                $rule['price_min'] !== null && $rule['price_max'] !== null => number_format($rule['price_min'], 2) . ' - ' . number_format($rule['price_max'], 2),
                                $rule['price_min'] !== null => 'From ' . number_format($rule['price_min'], 2),
                                $rule['price_max'] !== null => 'Up to ' . number_format($rule['price_max'], 2),
                                default => 'Any price',
                              };
                            @endphp
                            <div class="mt-1 text-gray-400">{{ $rule['scope_type'] }} markup · {{ $range }}</div>
                          @endif
                        </td>
                        <td class="px-4 py-3 align-top">
                          <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badge }}">{{ str_replace('_', ' ', $row['action']) }}</span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <div class="flex justify-end border-t border-gray-100 p-4">
                <button class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white">Apply Selected</button>
              </div>
            </form>
          @else
            <div class="p-8 text-center text-gray-500">Choose a product type and fetch the latest VTUNAIJA catalog.</div>
          @endif
        </div>
      </section>

      <aside class="space-y-6">
        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
          <h2 class="mb-4 font-bold text-gray-900">Provider Setup</h2>
          <div class="grid grid-cols-2 gap-2 text-center text-xs">
            <div class="rounded-lg bg-gray-50 p-3">
              <div class="text-base font-bold text-gray-900">{{ number_format($setupStatus['providers'] ?? 0) }}</div>
              <div class="text-gray-500">Providers</div>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <div class="text-base font-bold text-gray-900">{{ number_format($setupStatus['configs'] ?? 0) }}</div>
              <div class="text-gray-500">Configs</div>
            </div>
          </div>
          <a href="{{ route('admin.vtunaija-price-sync.setup') }}" class="mt-4 inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2 font-semibold text-white">
            Configure API
          </a>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
          <h2 class="mb-4 font-bold text-gray-900">Markup Rules</h2>
          <form method="POST" action="{{ route('admin.vtunaija-price-sync.rules.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="return_product_type" value="{{ $productType }}">
            <select name="product_type" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              <option value="">Global fallback</option>
              @foreach($adapters as $key => $adapter)
                <option value="{{ $key }}">{{ $adapter->label() }}</option>
              @endforeach
            </select>
            <select name="scope_type" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              <option value="global">Global</option>
              <option value="product_type">Product type</option>
              <option value="network">Network / Provider</option>
              <option value="type">Data / Cable type</option>
              <option value="product">External plan ID</option>
            </select>
            <input name="scope_key" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Scope key, e.g. mtn, corporate, 121">
            <div class="grid grid-cols-2 gap-2">
              <input name="price_min" type="number" step="0.0001" min="0" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Min price">
              <input name="price_max" type="number" step="0.0001" min="0" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Max price">
            </div>
            <div class="grid grid-cols-2 gap-2">
              <select name="markup_type" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="flat">Flat</option>
                <option value="percent">Percent</option>
              </select>
              <input name="markup_value" type="number" step="0.0001" min="0" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Fallback value" required>
            </div>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
              <input name="customer_markup_value" type="number" step="0.0001" min="0" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Customer">
              <input name="reseller_markup_value" type="number" step="0.0001" min="0" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Reseller">
              <input name="agent_markup_value" type="number" step="0.0001" min="0" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Agent">
            </div>
            <button class="w-full rounded-lg bg-gray-900 px-4 py-2 font-semibold text-white">Add Rule</button>
          </form>

          <div class="mt-5 divide-y divide-gray-100">
            @forelse($rules as $rule)
              <div class="flex items-center justify-between gap-3 py-3 text-xs">
                @php
                  $range = match (true) {
                    $rule->price_min !== null && $rule->price_max !== null => number_format($rule->price_min, 2) . ' - ' . number_format($rule->price_max, 2),
                    $rule->price_min !== null => 'From ' . number_format($rule->price_min, 2),
                    $rule->price_max !== null => 'Up to ' . number_format($rule->price_max, 2),
                    default => 'Any price',
                  };
                @endphp
                <div>
                  <div class="font-semibold text-gray-800">{{ $rule->product_type ?? 'global' }} · {{ $rule->scope_type }}</div>
                  <div class="text-gray-500">{{ $rule->scope_key ?? 'all' }} · {{ $range }} · fallback {{ $rule->markup_value }} {{ $rule->markup_type }}</div>
                  <div class="text-gray-500">
                    C: {{ $rule->customer_markup_value ?? 'fallback' }} ·
                    R: {{ $rule->reseller_markup_value ?? 'fallback' }} ·
                    A: {{ $rule->agent_markup_value ?? 'fallback' }}
                  </div>
                </div>
                <form id="delete-markup-rule-{{ $rule->id }}" method="POST" action="{{ route('admin.vtunaija-price-sync.rules.delete', $rule->id) }}">
                  @csrf
                  @method('DELETE')
                  <input type="hidden" name="return_product_type" value="{{ $productType }}">
                </form>
                <button type="submit" form="delete-markup-rule-{{ $rule->id }}" class="text-red-600">Delete</button>
              </div>
            @empty
              <div class="py-3 text-xs text-gray-500">No markup rules yet. Prices will sync at VTUNAIJA cost until a rule is added.</div>
            @endforelse
          </div>
        </section>

        <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
          <h2 class="mb-4 font-bold text-gray-900">Recent Runs</h2>
          <div class="divide-y divide-gray-100">
            @forelse($runs as $run)
              <a href="{{ route('admin.vtunaija-price-sync.runs.show', $run->id) }}" class="block py-3 text-xs hover:bg-gray-50">
                <div class="font-semibold text-gray-800">#{{ $run->id }} · {{ $run->product_type }} · {{ $run->status }}</div>
                <div class="text-gray-500">{{ $run->created_count }} created, {{ $run->updated_count }} updated, {{ $run->skipped_count }} skipped</div>
              </a>
            @empty
              <div class="py-3 text-xs text-gray-500">No sync runs yet.</div>
            @endforelse
          </div>
        </section>
      </aside>
    </div>
  </div>
</x-admin-layout>
