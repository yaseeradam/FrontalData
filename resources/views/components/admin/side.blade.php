<div id="sidebar" class="fixed top-12 left-0 bottom-0 w-[200px] bg-[#1d2327] text-white pt-6 overflow-y-auto text-[13px] hidden md:block z-[999999]">
    <ul class="space-y-1">
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
          <span class="jannah-home"></span>
          <span class="menu-text">Dashboard</span>
        </a>
      </li>
      <li class="px-4 py-2 hover:bg-[#32373c] submenu">
        <div class="flex items-center justify-between cursor-pointer toggle-submenu">
          <span class="flex items-center space-x-2">
            <span class="jannah-note-text"></span>
            <span class="menu-text">Products</span>
          </span>
          <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded menu-text">45</span>
        </div>
        <ul class="ml-6 mt-1 hidden">
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.products') }}">All Products</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.add.product') }}">Add New</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.services') }}">Services</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.categories') }}">Categories</a></li>
        </ul>
      </li>
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.users') }}" class="flex items-center space-x-2">
          <span class="jannah-user"></span>
          <span class="menu-text">Users</span>
        </a>
      </li>
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.transactions') }}" class="flex items-center space-x-2">
          <span class="jannah-wallet"></span>
          <span class="menu-text">Transactions</span>
        </a>
      </li>
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.gateways') }}" class="flex items-center space-x-2">
          <span class="jannah-card"></span>
          <span class="menu-text">Payments</span>
        </a>
      </li>
       <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.media.view') }}" class="flex items-center space-x-2">
          <span class="jannah-image"></span>
          <span class="menu-text">Media</span>
        </a>
      </li>

      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.automate') }}" class="flex items-center space-x-2">
          <span class="jannah-box-2"></span>
          <span class="menu-text">Automate & AI</span>
        </a>
      </li>
        <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.monetization') }}" class="flex items-center space-x-2">
          <span class="jannah-coin"></span>
          <span class="menu-text">Monetizations</span>
        </a>
      </li>
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.labs') }}" class="flex items-center space-x-2">
          <span class="jannah-bubble"></span>
          <span class="menu-text">Labs & Experiments</span>
        </a>
      </li>
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.mobile') }}" class="flex items-center space-x-2">
          <span class="jannah-mobile"></span>
          <span class="menu-text">Mobile App Manager</span>
        </a>
      </li>

       <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.notifications') }}" class="flex items-center space-x-2">
          <span class="jannah-notification-bing"></span>
          <span class="menu-text">Notifications</span>
        </a>
      </li>
       

      <li class="px-4 py-2 hover:bg-[#32373c] submenu">
        <div class="flex items-center justify-between cursor-pointer toggle-submenu">
          <span class="flex items-center space-x-2">
            <span class="jannah-brush"></span>
            <span class="menu-text">Themes</span>
          </span>
          <span class="jannah-arrow-down-1"></span>
        </div>
        <ul class="ml-6 mt-1 hidden">
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.themes') }}">Installed Themes</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.themes') }}">Add New</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="https://marketplace.al-manarah.com.ng/themes">Marketplace</a></li>
        </ul>
      </li>

      <li class="px-4 py-2 hover:bg-[#32373c] submenu">
        <div class="flex items-center justify-between cursor-pointer toggle-submenu">
          <span class="flex items-center space-x-2">
            <span class="jannah-folder-add"></span>
            <span class="menu-text">Plugins</span>
          </span>
          <span class="jannah-arrow-down-1"></span>
        </div>
        <ul class="ml-6 mt-1 hidden">
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.plugins') }}">Installed Plugins</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.plugins') }}">Add New</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="https://marketplace.al-manarah.com.ng/plugin">Marketplace</a></li>
        </ul>
      </li>

      <li class="px-4 py-2 hover:bg-[#32373c] submenu">
        <div class="flex items-center justify-between cursor-pointer toggle-submenu">
          <span class="flex items-center space-x-2">
            <span class="jannah-setting-2"></span>
            <span class="menu-text">Tools</span>
          </span>
          <span class="jannah-arrow-down-1"></span>
        </div>
        <ul class="ml-6 mt-1 hidden">
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.analytics') }}">Analytics</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.apiconfigs') }}">Api Config</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.providers') }}">Providers</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.bulkmailer') }}">Bulk Mailer</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.updates') }}">Updates</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.switch') }}">Jannah Switch</a></li>
          <li class="px-4 py-2 hover:bg-[#3c4042]"><a href="{{ route('admin.data.category') }}">Manage SubCategory</a></li>
          
        </ul>
      </li>
        
      <li class="px-4 py-2 hover:bg-[#32373c]">
        <a href="{{ route('admin.settings') }}" class="flex items-center space-x-2">
          <span class="jannah-setting"></span>
          <span class="menu-text">Settings</span>
        </a>
      </li>
   
     @foreach(\App\Plugins\Hooks::getSidebarItems() as $menuItem)
      <li class="px-4 py-2 hover:bg-[#32373c] {{ isset($menuItem['subitems']) ? 'submenu' : '' }}">
        <div class="flex items-center justify-between cursor-pointer toggle-submenu">
          <a href="{{ $menuItem['route'] }}" class="flex items-center space-x-2">
            <span class="{{ $menuItem['icon'] }}"></span>
            <span class="menu-text">{{ $menuItem['title'] }}</span>
          </a>
          @if(isset($menuItem['subitems']))
            <span class="jannah-arrow-down-1"></span>
          @endif
        </div>
        @if(isset($menuItem['subitems']))
          <ul class="ml-6 mt-1 hidden">
            @foreach($menuItem['subitems'] as $sub)
              <li class="px-4 py-2 hover:bg-[#3c4042]">
                <a href="{{ $sub['route'] }}">{{ $sub['title'] }}</a>
              </li>
            @endforeach
          </ul>
        @endif
      </li>
    @endforeach
   </ul>
    <div class="px-4 py-2 mt-4 border-t border-gray-600">
      <button id="collapse-menu" class="w-full flex items-center space-x-2 hover:bg-[#32373c] p-2 rounded focus:outline-none">
        <span class="jannah-arrow-left-2"></span>
        <span class="menu-text">Collapse Menu</span>
      </button>
    </div>
    
    <div class="text-green-500 flex justify-center align-center font-bold text-md mt-10">
        V{{ config('app.version') }}
    </div>
  </div>
