@props(['username' => '', 'firstname' => ''])
@php
    $activeClass = "bg-blue-50 text-blue-600 font-bold shadow-xs";
    $inactiveClass = "text-slate-600 hover:bg-slate-50 hover:text-blue-600";
    $sidebarItems = \App\Plugins\Hooks::getUserSidebarItems();
    $sidebarItems = collect($sidebarItems)->unique('title')->values()->all();
@endphp
<aside class="sidebar bg-white w-64 h-screen fixed left-0 top-0 border-r border-slate-100 p-5 hidden sm:flex flex-col show overflow-y-auto z-20 shadow-xs"> 
    <!-- Logo -->
    <div class="px-2 py-3 mb-4">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="{{ jannah_opt('site_name', 'FrontalData') }}" class="h-9 w-auto object-contain">
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-gradient-to-r from-blue-50/70 to-cyan-50/50 p-3 rounded-2xl flex items-center mb-6 border border-blue-100/60 shadow-xs">
        <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white font-bold flex justify-center items-center shadow-sm">
            {{ strtoupper(substr($username, 0, 1)) }}
        </a>
        <div class="ml-3 overflow-hidden">
            <div class="text-xs font-bold text-slate-800 truncate">{{ strtoupper($firstname ?: $username) }}</div> 
            <div class="text-[11px] text-blue-600 font-medium truncate">{{ '@' . strtolower($username) }}</div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">Services</div> 
    <nav class="space-y-1 text-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            <i class="ri-dashboard-3-line mr-2.5 text-lg"></i> Dashboard
        </a>
        <a href="{{ route('core.data') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('core.data') ? $activeClass : $inactiveClass }}">
            <i class="ri-wifi-line mr-2.5 text-lg"></i> Buy Data
        </a>
        <a href="{{ route('core.airtime') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('core.airtime') ? $activeClass : $inactiveClass }}">
            <i class="ri-smartphone-line mr-2.5 text-lg"></i> Buy Airtime
        </a>
        <a href="{{ route('core.tv') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('core.tv') ? $activeClass : $inactiveClass }}">
            <i class="ri-tv-2-line mr-2.5 text-lg"></i> Cable TV
        </a> 
        <a href="{{ route('core.electricity') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('core.electricity') ? $activeClass : $inactiveClass }}">
            <i class="ri-flashlight-line mr-2.5 text-lg"></i> Electricity
        </a>
        <a href="{{ route('core.epin') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('core.epin') ? $activeClass : $inactiveClass }}">
            <i class="ri-graduation-cap-line mr-2.5 text-lg"></i> Exam PINs
        </a>
    </nav>

    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mt-6 mb-2">Account</div>
    <nav class="space-y-1 text-sm">
        <a href="{{ route('core.transactions') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('core.transactions') ? $activeClass : $inactiveClass }}">
            <i class="ri-file-list-3-line mr-2.5 text-lg"></i> Transactions
        </a>
        <a href="{{ route('profile.edit') }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ request()->routeIs('profile.edit') ? $activeClass : $inactiveClass }}">
            <i class="ri-user-settings-line mr-2.5 text-lg"></i> Settings
        </a>
    </nav>
    
    @if(count($sidebarItems) > 0)
    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mt-6 mb-2">Plugins</div>
    <nav class="space-y-1 text-sm">
        @foreach($sidebarItems as $menuItem)
            <a href="{{ $menuItem['route'] }}" class="flex items-center p-2.5 px-3 rounded-xl transition-all {{ $inactiveClass }}">
                <i class="{{ $menuItem['icon'] }} mr-2.5 text-lg"></i>
                {{ $menuItem['title'] }}
            </a>
        @endforeach
    </nav>
    @endif
</aside>
