<div class="z-10 bg-white fixed bottom-0 left-0 right-0 border-t border-border flex justify-around py-2 lg:relative lg:w-16 lg:h-screen lg:border-l lg:border-t-0 lg:flex-col lg:space-y-4 lg:space-x-0 lg:items-center lg:top-0 lg:left-0 lg:space-y-0 lg:sticky hidden lg:flex">
    <a href="{{ route('dashboard') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('dashboard')) active @endif" data-text="Home">
        <i class="jannah-home-1 bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:block"></span>
    </a>
    <a href="{{ route('core.earn') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('rewards')) active @endif" data-text="Rewards">
        <i class="jannah-hashtag-up bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:block"></span>
    </a>
    <a href="{{ route('core.transactions') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('transactions')) active @endif" data-text="Transactions">
        <i class="jannah-receipt-2-1 bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:block"></span>
    </a>
    <a href="{{ route('profile.edit') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('profile')) active @endif" data-text="Profile">
        <i class="jannah-happyemoji bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:block"></span>
    </a>
</div>

<script>
  document.querySelectorAll('.icon-text').forEach(item => {
    item.addEventListener('click', function() {
      document.querySelectorAll('.icon-text').forEach(el => {
        el.querySelector('i').classList.remove('text-primary');
        el.querySelector('span').classList.add('hidden');
        el.classList.remove('active');
      });
      item.querySelector('i').classList.add('text-primary');
      item.querySelector('span').classList.remove('hidden');
      item.classList.add('active');
    });
  });

  document.querySelectorAll('.icon-text').forEach(item => {
    if (item.href === window.location.href) {
      item.classList.add('active');
      item.querySelector('i').classList.add('text-primary');
      item.querySelector('span').classList.remove('hidden');
    }
  });
</script>