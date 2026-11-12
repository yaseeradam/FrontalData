<div class="fixed bottom-0 bg-white left-0 right-0 border-t border-border flex justify-around py-2 lg:relative lg:w-16 lg:h-screen lg:border-l lg:border-t-0 lg:flex-col lg:space-y-4 lg:space-x-0 lg:items-center lg:top-0 lg:left-0 lg:space-y-0">
    <a href="{{ route('admin.dashboard') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('admin.dashboard')) active @endif" data-text="Home">
        <i class="jannah-home bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:hidden">Home</span>
    </a>
    <a href="{{ route('admin.apiconfigs') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('admin.apiconfigs')) active @endif" data-text="ApiConfigs">
        <i class="jannah-code bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:hidden">API Config</span>
    </a>
    <a href="{{ route('admin.transactions') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('admin.transactions')) active @endif" data-text="Sales">
        <i class="jannah-document-filter bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:hidden">Sales</span>
    </a>
    <a href="{{ route('admin.products') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('admin.products')) active @endif" data-text="Products">
        <i class="jannah-shop bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:hidden">Products</span>
    </a>
    <a href="{{ route('admin.more') }}" class="flex flex-row items-center icon-text @if(request()->routeIs('admin.more')) active @endif" data-text="More">
        <i class="jannah-category-2 bmi text-2xl"></i>
        <span class="text-2xs ml-2 hidden text-primary lg:hidden">More</span>
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