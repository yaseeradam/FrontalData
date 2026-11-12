@props(['username' => ''])
<header class="bg-white/90 backdrop-blur-md border-b border-slate-100 px-4 sm:px-6 py-3.5 pt-[max(0.875rem,env(safe-area-inset-top))] flex justify-between items-center fixed top-0 right-0 left-0 sm:left-64 sm:w-[calc(100%-16rem)] z-30 shadow-xs">
    <div class="flex items-center gap-2 sm:gap-3">
        @if(!request()->routeIs('dashboard'))
            <button type="button" onclick="if(window.history.length > 1){ history.back(); } else { window.location.href='{{ route('dashboard') }}'; }" class="sm:hidden w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 active:scale-95 flex items-center justify-center -ml-1 transition-all cursor-pointer" title="Go Back">
                <i class="ri-arrow-left-s-line text-2xl font-bold"></i>
            </button>
        @endif
        <a href="{{ route('dashboard') }}" class="sm:hidden flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="{{ jannah_opt('site_name', 'FrontalData') }}" class="h-8 w-auto object-contain">
        </a>
        <div class="hidden sm:block">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Welcome back,</span>
            <span class="text-sm font-extrabold text-slate-900 ml-1">{{ $username }}</span>
        </div>
    </div>
    <div class="flex items-center gap-3"> 
        <a href="{{ route('core.notifications') }}" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-600 transition-all flex justify-center items-center text-lg" title="Notifications">
            <i class="ri-notification-3-line"></i>
        </a>
        <a href="{{ route('profile.edit') }}" class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white font-bold text-sm flex justify-center items-center shadow-sm hover:scale-105 transition-all">
            {{ strtoupper(substr($username, 0, 1)) }}
        </a>   
    </div>
</header>
