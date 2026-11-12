@props(['user' => null])
@php
    $primary = jannah_opt('primary_color');
    $wa = jannah_opt('support_whatsapp');
    $user = $user ?? auth()->user();
    $username = $user ? $user->username : '';
    $firstname = $user ? $user->first_name : '';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-head></x-head>
</head>
<body class="bg-slate-50/60 text-slate-900 min-h-screen overflow-x-hidden antialiased">
    <div class="flex min-h-screen" id="contentWrapper"> 
        <x-app.aside :username="$username" :firstname="$firstname" />
     
        <div class="flex-1 sm:ml-64 transition-all w-full max-w-full overflow-x-hidden">
           <x-app.header :username="$username" /> 

            <main class="p-3.5 sm:p-6 mt-16 max-w-7xl mx-auto grid md:grid-cols-5 gap-4 sm:gap-5 pb-28 sm:pb-10">
                 @yield('content')
                 {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @if(!empty($wa) && $wa !== 'false')
    <a href="{{ $wa }}" class="fixed bottom-24 right-5 sm:bottom-6 sm:right-6 bg-emerald-500 text-white p-3 rounded-full shadow-xl hover:bg-emerald-600 transition-all hover:scale-110 flex justify-center items-center z-40" title="Chat on WhatsApp">
        <i class="ri-whatsapp-line text-2xl"></i> 
    </a>
    @endif

    <div class="px-3 sm:px-4 fixed bottom-3 left-0 w-full z-40 sm:hidden pb-[env(safe-area-inset-bottom,0px)]">
        <x-app.btm />
    </div>

    @if($user)
        <x-block.pin :user="$user" />
    @endif
    <x-app.loader />
</body>
</html>

 