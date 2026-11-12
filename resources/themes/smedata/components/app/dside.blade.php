@props(['username' => '', 'firstname' => ''])
@php
    $primary = jannah_opt('primary_color');
    $activeClass = "bg-[$primary]/5 text-primary";
    $inactiveClass = "hover:bg-gray-100 text-gray-500";
   
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
            <div class="relative top-[4px]">{{ $firstname }}</div> 
        </div>
    </div>
    <div class="mt-5 text-xs text-primary ml-3">DEVELOPED BY <a href="https://uiking.co" id="uiking">UIKING CO</a></div> 
    <nav class="mt-2 space-y-2 text-sm">
        <a href="{{ route('dev.user') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.user') ? $activeClass : $inactiveClass }}">
            <i class="jannah-home-1 mr-2 "></i> Home
        </a>
        <a href="{{ route('dev.data') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.data') ? $activeClass : $inactiveClass }}">
            <i class="ri-wifi-line mr-2 "></i> Data Vending
        </a>
        <a href="{{ route('dev.airtime') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.airtime') ? $activeClass : $inactiveClass }}">
            <i class="ri-smartphone-line mr-2 "></i> Airtime Vending
        </a>
        <a href="{{ route('dev.tv') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.tv') ? $activeClass : $inactiveClass }}">
            <i class="ri-movie-line mr-2 "></i> Cable Sub Vending
        </a> 
        <a href="{{ route('dev.electricity') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.electricity') ? $activeClass : $inactiveClass }}">
            <i class="ri-lightbulb-line mr-2 "></i> Electricity Vending
        </a>
        <a href="{{ route('dev.meter') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.meter') ? $activeClass : $inactiveClass }}">
            <i class="ri-lightbulb-line mr-2 "></i> Meter Validations
        </a>
        
        <a href="{{ route('dev.iuc') }}" class="flex items-center p-3 px-4 rounded-[40px]  text-sm transition-transform hover:scale-105 {{ request()->routeIs('dev.iuc') ? $activeClass : $inactiveClass }}">
            <i class="ri-movie-line mr-2 "></i> IUC validations
        </a>
    </nav>
    
    
</aside>
