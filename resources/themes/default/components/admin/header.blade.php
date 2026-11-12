<div class="flex justify-between items-center mb-4">
    <div class="flex items-center space-x-2">
      <div class="relative">
        <a href="{{ route('admin.profile') }}"><img src="@basecdn('images/profile.png')" alt="profile picture" class="rounded-full w-8" /></a>
        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1">2</span>
      </div>
      <span class="text-sm">Hi, Admin</span>
    </div>
    <div class="flex space-x-6">
      <a href="{{ route('admin.notifications') }}"><i class="jannah-notification text-2xl"></i></a>
      <a href="{{ route('admin.settings') }}"><i class="jannah-setting text-2xl"></i></a>
    </div>
  </div>