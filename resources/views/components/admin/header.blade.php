<div class="fixed top-0 left-0 right-0 bg-[#1d2327] text-white h-12 z-50 flex items-center justify-between px-4">
    <div class="flex items-center">
      <button id="mobile-menu-toggle" class="md:hidden text-white focus:outline-none mr-4">
        <span class="jannah-menu"></span>
      </button>
      <a href="{{ route('admin.dashboard') }}" class="mr-4 text-sm font-bold">Jannah Admin</a>
      <nav class="hidden md:block">
        <ul class="flex space-x-4 text-sm">
          <li><a href="{{ route('admin.settings') }}" class="hover:text-gray-300"><span class="jannah-setting"></span></a></li>
          <li><a href="{{ route('admin.media.view') }}" class="hover:text-gray-300"><span class="jannah-gallery"></span></a></li>
        </ul>
      </nav>
    </div>
    <div class="flex items-center space-x-4">
      <div class="relative group">
        <button class="flex items-center space-x-2 hover:text-gray-300 focus:outline-none">
          <span class="menu-text text-[13px]">MrAdmin</span>
          <img src="@basecdn('images/profile.png')" alt="profile picture" class="rounded-full w-7" />
        </button>
        <ul class="absolute right-0 mt-2 w-40 bg-white text-black rounded shadow-lg hidden group-hover:block">
          <li><a href="#" class="block px-4 py-2 hover:bg-gray-200">Edit Profile</a></li>
          <li><a href="#" class="block px-4 py-2 hover:bg-gray-200">Logout</a></li>
        </ul>
      </div>
    </div>
</div>