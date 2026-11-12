<div class="flex justify-between items-center p-2 sticky top-0 bg-gray-100 z-50">
    <button class="text-primary"><i onclick="back()" class="jannah-arrow-left-2 text-xl"></i></button>
    <h1 class="text-sm font-semibold"></h1>
    <button class="text-sm text-primary"></button>
  </div>
 <div class="bg-muted space-y-4 overflow-hidden">
     <div class="p-4 bg-gray-100">
            <div class="text-2xl font-semibold flex justify-center">
                <img src="{{ asset('img/app/logo.png') }}" class="h-[100px]">
            </div>
            <div class="text-xs mt-4">
               {{ $des }}
            </div>
            <div class="p-6">
                
            </div>
        </div>
     {{ $slot }}
 </div>