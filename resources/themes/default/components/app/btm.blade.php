<nav id="mobileNav" class="rounded-2xl bg-white/95 backdrop-blur-md shadow-2xl border border-slate-200/90 sm:hidden flex items-center justify-around py-1.5 px-2 text-sm animate-slideUp">
    <a href="{{ route('dashboard') }}" class="nav-item relative flex items-center justify-center min-h-[44px] px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-slate-500 hover:text-blue-600' }}">
        <i class="ri-home-5-line text-xl"></i>
        <span class="ml-1.5 text-xs {{ request()->routeIs('dashboard') ? 'inline-block' : 'hidden' }}">
            Home
        </span>
    </a>
    <a href="{{ route('core.earn') }}" class="nav-item relative flex items-center justify-center min-h-[44px] px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('core.earn') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-slate-500 hover:text-blue-600' }}">
        <i class="ri-gift-line text-xl"></i>
        <span class="ml-1.5 text-xs {{ request()->routeIs('core.earn') ? 'inline-block' : 'hidden' }}">
            Earn
        </span>
    </a>
    <a href="{{ route('core.transactions') }}" class="nav-item relative flex items-center justify-center min-h-[44px] px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('core.transactions') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-slate-500 hover:text-blue-600' }}">
        <i class="ri-file-list-3-line text-xl"></i>
        <span class="ml-1.5 text-xs {{ request()->routeIs('core.transactions') ? 'inline-block' : 'hidden' }}">
            History
        </span>
    </a>
    <a href="{{ route('profile.edit') }}" class="nav-item relative flex items-center justify-center min-h-[44px] px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('profile.edit') ? 'text-blue-600 bg-blue-50 font-bold' : 'text-slate-500 hover:text-blue-600' }}">
        <i class="ri-user-3-line text-xl"></i>
        <span class="ml-1.5 text-xs {{ request()->routeIs('profile.edit') ? 'inline-block' : 'hidden' }}">
            Profile
        </span>
    </a>
</nav>
