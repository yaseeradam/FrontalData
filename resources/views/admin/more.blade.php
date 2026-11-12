<x-admin-layout :drk="true" title="More Settings">
  <x-admin.frame>
     
    <main class="min-h-screen bg-background text-foreground p-4 lg:ml-1 lg:pl-4 lg:pr-2 lg:pt-2 lg:pb-4">
    
    <x-admin.title>More</x-admin.title>
    
    <div class="mt-4 bg-white p-4 rounded-lg shadow">
  <div class="space-y-4">
    <div onclick="load('{{ route('admin.analytics') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
       <i class="jannah-calendar-search text-2xl"></i>
        <div>
          <p class="text-sm">Analytics</p>
          <p class="text-xs text-zinc-500">View and Manage Sales</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.users') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-grammerly text-2xl"></i>
        <div>
          <p class="text-sm">Manage Users</p>
          <p class="text-xs text-zinc-500">Manage users and data</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
     <div onclick="load('{{ route('admin.providers') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-bag-timer text-2xl"></i>
        <div>
          <p class="text-sm">API Providers</p>
          <p class="text-xs text-zinc-500">Manage API Providers</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
     <div onclick="load('{{ route('admin.services') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-bill text-2xl"></i>
        <div>
          <p class="text-sm">Manage Services</p>
          <p class="text-xs text-zinc-500">View and Manage Services</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.apiconfigs') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-code text-2xl"></i>
        <div>
          <p class="text-sm">API Settings</p>
          <p class="text-xs text-zinc-500">Manage API settings</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
     <div onclick="load('{{ route('admin.products') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-shopping-cart text-2xl"></i>
        <div>
          <p class="text-sm">Products</p>
          <p class="text-xs text-zinc-500">Manage and Add Products</p> 
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
     <div onclick="load('{{ route('admin.categories') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-category-2 text-2xl"></i>
        <div>
          <p class="text-sm">View Categories</p>
          <p class="text-xs text-zinc-500">View product categories</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.settings') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
       <i class="jannah-lock-1 text-2xl"></i>
        <div>
          <p class="text-sm">Settings</p>
          <p class="text-xs text-zinc-500">Manage Global Configurations</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.gateways') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-finger-scan text-2xl"></i>
        <div>
          <p class="text-sm">Payment Gateway</p>
          <p class="text-xs text-zinc-500">Manage Payments Token</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.switch') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-additem text-2xl"></i>
        <div>
          <p class="text-sm">Jannah Switch</p>
          <p class="text-xs text-zinc-500">Import from old website</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.themes') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-autobrightness text-2xl"></i>
        <div>
          <p class="text-sm">Jannah Themes</p>
          <p class="text-xs text-zinc-500">Explore All Jannah Themes</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.bulkmailer') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-direct-send text-2xl"></i>
        <div>
          <p class="text-sm">Bulk Mailer</p>
          <p class="text-xs text-zinc-500">Send bulk email to your users</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
    <div onclick="load('{{ route('admin.updates') }}')" class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <i class="jannah-code text-2xl"></i>
        <div>
          <p class="text-sm">Software Update</p>
          <p class="text-xs text-zinc-500">Check for Software updates</p>
        </div>
      </div>
      <i class="jannah-arrow-right-3"></i>
    </div>
  </div>
  
</div> 
<div class="p-4 text-sm">Developed and Licensed by <x-link.default href="https://wa.me/+2348074195479">Ani Anthony</x-link.default></div>

    </main>
    </x-admin.frame>
</x-admin-layout>