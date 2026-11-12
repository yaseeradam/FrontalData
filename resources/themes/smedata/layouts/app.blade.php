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
<body class="bg-white md:px-2 mn-skin">
    <div class="flex" id="contentWrapper"> 
        <x-app.aside :username="$username" :firstname="$firstname" />
            
     
        <div class="flex-1 sm:ml-64 transition-all">
           <x-app.header :username="$username" /> 

            <main class="p-5 mt-16 grid md:grid-cols-5 gap-4">
                 @yield('content')
                 {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <a href="{{ $wa }}" class="fixed bottom-16 right-6 bg-green-500 text-white p-2 rounded-full shadow-xl hover:bg-green-600 transition whatsapp-button h-[50px] w-[50px] flex justify-center items-center animate-bounce z-[999999]">
        <i class="ri-whatsapp-line text-3xl"></i> 
    </a>
    <div class="whatsapp-tooltip hidden">Need help? Chat with us on WhatsApp</div>
    <div class="px-4 fixed bottom-7 left-0 w-full">
        <x-app.btm />

    </div>
    @if($user)
        <x-block.pin :user="$user" />
    @endif
    <x-app.loader />
</body>
</html>

 