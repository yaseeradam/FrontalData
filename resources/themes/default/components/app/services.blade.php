@php
    $services = [
        ['img' => asset('images/services/airtime.jpg'),     'label' => 'Buy Airtime',     'badge' => 'Instant',    'route' => route('core.airtime'),     'enabled' => @jannah_opt('airtime_feature') === 'enabled'],
        ['img' => asset('images/services/data.jpg'),        'label' => 'Buy Data',        'badge' => 'Wholesale',  'route' => route('core.data'),        'enabled' => @jannah_opt('data_feature') === 'enabled'],
        ['img' => asset('images/services/electricity.jpg'), 'label' => 'Electricity',     'badge' => 'Token',      'route' => route('core.electricity'), 'enabled' => @jannah_opt('electricity_feature') === 'enabled'],
        ['img' => asset('images/services/tv.jpg'),          'label' => 'Cable TV',        'badge' => 'Renew',      'route' => route('core.tv'),          'enabled' => @jannah_opt('tv_feature') === 'enabled'],
        ['img' => asset('images/services/exam.jpg'),        'label' => 'Exam PINs',       'badge' => 'WAEC/NECO',  'route' => route('core.epin'),        'enabled' => @jannah_opt('epin_feature') === 'enabled'],
        ['img' => asset('images/services/betting.jpg'),     'label' => 'Betting Fund',    'badge' => 'Fast',       'route' => route('core.betting'),     'enabled' => @jannah_opt('betting_feature') === 'enabled'],
        ['img' => asset('images/services/agent.jpg'),       'label' => 'Agent Upgrade',   'badge' => 'VIP',        'route' => route('core.user.upgrade'),'enabled' => true],
        ['img' => asset('images/app_icon.png'),             'label' => 'FrontalData Hub', 'badge' => 'All',        'route' => route('dashboard'),        'enabled' => true],
    ];
@endphp

<div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 md:col-span-2">
    <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Quick Telecom & Utility Services</h3>
        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">● Live 24/7</span>
    </div>

    <div class="grid grid-cols-4 gap-3 sm:gap-4 text-center">
        @foreach ($services as $service)
            @if($service['enabled'])
            <a href="{{ $service['route'] }}" class="group flex flex-col items-center p-2 rounded-2xl hover:bg-blue-50/50 transition-all duration-300">
                <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-2xl p-1 bg-white shadow-md border border-slate-100 group-hover:scale-110 group-hover:shadow-lg group-hover:border-blue-200 transition-all duration-300 flex items-center justify-center overflow-hidden">
                    <img src="{{ $service['img'] }}" alt="{{ $service['label'] }}" class="w-full h-full object-cover rounded-xl">
                </div>
                <p class="mt-2 text-xs font-semibold text-slate-700 group-hover:text-blue-600 transition-colors leading-tight">{{ $service['label'] }}</p>
                <span class="text-[10px] text-slate-400 mt-0.5">{{ $service['badge'] }}</span>
            </a>
            @endif
        @endforeach
    </div>
</div>
