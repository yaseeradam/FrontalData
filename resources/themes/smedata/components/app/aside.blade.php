@props(['username' => '', 'firstname' => ''])
@php
    $primary = jannah_opt('primary_color');
    $activeClass = "bg-[$primary]/5 text-primary";
    $inactiveClass = "hover:bg-gray-100 text-gray-500";
    $sidebarItems = \App\Plugins\Hooks::getUserSidebarItems();
    $sidebarItems = collect($sidebarItems)->unique('title')->values()->all();
@endphp
<aside class="sidebar bg-white w-64 h-screen fixed left-0 border-r border-dashed p-5 hidden sm:block show overflow-y-auto"> 
    <div class="p-4">
        <!-- OPP -->
    </div>
    <div class="bg-gray-100 p-4 rounded-[40px] flex transition duration-700 hover:shadow-lg">
        <a href="{{ route('profile.edit') }}" class="bg-primary p-2 rounded-full w-10 h-10 flex justify-center items-center  transition-all hover:scale-110">
            {{ strtoupper(substr($username, 0, 1)) }}
        </a>
        <div class="text-xs ml-4">
            <div class="">{{ '@' . strtoupper($username) }}</div>
            <div class="relative top-[4px]">{{ strtoupper($firstname) }}</div> 
        </div>
    </div>
    <div class="mt-5 text-xs  ml-3">GENERAL</div> 
    <nav class="mt-2 space-y-2 text-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            <i class="jannah-home-1 mr-2 "></i> Dashboard
        </a>
        <a href="{{ route('core.data') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('core.data') ? $activeClass : $inactiveClass }}">
            <i class="ri-wifi-line mr-2 "></i> Buy Data
        </a>
        <a href="{{ route('core.airtime') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('core.airtime') ? $activeClass : $inactiveClass }}">
            <i class="ri-smartphone-line mr-2 "></i> Buy Airtime
        </a>
        <a href="{{ route('core.tv') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('core.tv') ? $activeClass : $inactiveClass }}">
            <i class="ri-movie-line mr-2 "></i> Cable Sub
        </a> 
        <a href="{{ route('core.electricity') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('core.electricity') ? $activeClass : $inactiveClass }}">
            <i class="ri-lightbulb-line mr-2 "></i> Electricity
        </a>
        <a href="{{ route('core.epin') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('core.epin') ? $activeClass : $inactiveClass }}">
            <i class="ri-school-line mr-2 "></i> Exams
        </a>
    </nav>
    <div class="mt-5 text-xs  ml-3">MANAGEMENT</div>
    <nav class="mt-2 space-y-2 text-sm">
        <a href="{{ route('profile.edit') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('profile.edit') ? $activeClass : $inactiveClass }}">
            <i class="ri-settings-3-line mr-2 "></i> Settings
        </a>
        <a href="{{ route('core.transactions') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('core.transactions') ? $activeClass : $inactiveClass }}">
            <i class="ri-file-paper-2-line mr-2 "></i> Transactions
        </a>
    </nav>
    
    @if(count($sidebarItems) > 0)
    <div class="mt-5 text-xs ml-3">MORE</div>
    <nav class="mt-2 space-y-2 text-sm">
        @foreach($sidebarItems as $menuItem)
            
                    <a href="{{ $menuItem['route'] }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ $inactiveClass }}">
                        <i class="{{ $menuItem['icon'] }} mr-2 "></i>
                       {{ $menuItem['title'] }}
                    </a>
               
        @endforeach
    </nav>
    @endif
    
</aside>
