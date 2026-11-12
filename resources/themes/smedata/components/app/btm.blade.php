<nav id="mobileNav" class="rounded-full bg-white/10 shadow-md border-t border-gray-200 sm:hidden flex justify-around py-2 text-sm animate-slideUp bg-white/10 backdrop-blur-sm">
    <a href="{{ route('dashboard') }}" data-nav="home" class="nav-item relative flex items-center px-4 py-2 transition-all hover:scale-105 {{ request()->routeIs('dashboard') ? 'text-primary active-nav' : 'text-gray-500' }}">
        <i class="jannah-home-1 text-xl"></i>
        <span class="nav-label ml-2 text-sm font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('dashboard') ? 'max-w-xs opacity-100 translate-x-0' : 'max-w-0 opacity-0 translate-x-2' }}">
            Home
        </span>
    </a>
    <a href="{{ route('core.earn') }}" data-nav="hashtag" class="nav-item relative flex items-center px-4 py-2 transition-all hover:scale-105 {{ request()->routeIs('core.earn') ? 'text-primary active-nav' : 'text-gray-500' }}">
        <i class="jannah-hashtag-up text-xl"></i>
        <span class="nav-label ml-2 text-sm font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('core.earn') ? 'max-w-xs opacity-100 translate-x-0' : 'max-w-0 opacity-0 translate-x-2' }}">
            Earn
        </span>
    </a>
    <a href="{{ route('core.transactions') }}" data-nav="receipt" class="nav-item relative flex items-center px-4 py-2 transition-all hover:scale-105 {{ request()->routeIs('core.transactions') ? 'text-primary active-nav' : 'text-gray-500' }}">
        <i class="jannah-receipt-search text-xl"></i>
        <span class="nav-label ml-2 text-sm font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('core.transactions') ? 'max-w-xs opacity-100 translate-x-0' : 'max-w-0 opacity-0 translate-x-2' }}">
            History
        </span>
    </a>
    <a href="{{ route('profile.edit') }}" data-nav="happy" class="nav-item relative flex items-center px-4 py-2 transition-all hover:scale-105 {{ request()->routeIs('profile.edit') ? 'text-primary active-nav' : 'text-gray-500' }}">
        <i class="jannah-happyemoji text-xl"></i>
        <span class="nav-label ml-2 text-sm font-medium transition-all duration-300 overflow-hidden {{ request()->routeIs('profile.edit') ? 'max-w-xs opacity-100 translate-x-0' : 'max-w-0 opacity-0 translate-x-2' }}">
            Profile
        </span>
    </a>
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const navItems = document.querySelectorAll('#mobileNav .nav-item');

  navItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      navItems.forEach(nav => {
        nav.classList.remove('active-nav');
        nav.classList.remove('text-primary');
        nav.classList.add('text-gray-500');
        const label = nav.querySelector('.nav-label');
        label.classList.remove('max-w-xs', 'opacity-100', 'translate-x-0');
        label.classList.add('max-w-0', 'opacity-0', 'translate-x-2');
      });
      
      item.classList.add('active-nav');
      item.classList.remove('text-gray-500');
      item.classList.add('text-primary');
      const activeLabel = item.querySelector('.nav-label');
      activeLabel.classList.remove('max-w-0', 'opacity-0', 'translate-x-2');
      activeLabel.classList.add('max-w-xs', 'opacity-100', 'translate-x-0');
      
      setTimeout(() => {
        window.location.href = item.getAttribute('href');
      }, 300);
    });
  });
});
</script>
