@php
    $primary = jannah_opt('primary_color');
    $services = [
        ['icon' => 'ri-smartphone-line', 'label' => 'Airtime',     'route' => route('core.airtime'),     'enabled' => @jannah_opt('airtime_feature') === 'enabled'],
        ['icon' => 'ri-wifi-line',       'label' => 'Data',        'route' => route('core.data'),        'enabled' => @jannah_opt('data_feature') === 'enabled'],
        ['icon' => 'ri-lightbulb-line',  'label' => 'Electricity', 'route' => route('core.electricity'), 'enabled' => @jannah_opt('electricity_feature') === 'enabled'],
        ['icon' => 'ri-movie-line',      'label' => 'TV',          'route' => route('core.tv'),          'enabled' => @jannah_opt('tv_feature') === 'enabled'],
        ['icon' => 'ri-school-line',     'label' => 'Exams',       'route' => route('core.epin'),        'enabled' => @jannah_opt('epin_feature') === 'enabled'],
        ['icon' => 'ri-store-line',      'label' => 'Agent',       'route' => route('core.user.upgrade'),'enabled' => true],
        ['icon' => 'ri-more-fill',       'label' => 'See all',     'route' => '',                        'enabled' => true],
    ];
@endphp

<div class="p-2 md:col-span-2 relative bottom-6 mb-10">
    <div class="grid grid-cols-4 gap-6 text-center">
        @foreach ($services as $service)
            <a href="{{ $service['route'] }}" class="flex flex-col items-center">
                <div class="flex justify-center items-center mn-tile bg-[{{ $primary }}]/10 p-2 rounded-[17px] shadow-sm w-12 h-12 transition duration-500 transform hover:scale-110">
                    <i class="{{ $service['icon'] }} text-primary text-xl"></i>
                </div>
                <p class="mt-2 text-xs text-gray-700">{{ $service['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>
