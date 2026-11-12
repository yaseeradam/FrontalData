@props(['drk' => false, 'user' => null])
@php
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
    @if($user)
    <div class="flex min-h-screen" id="contentWrapper">
        <x-app.aside :username="$username" :firstname="$firstname" />
        <div class="flex-1 sm:ml-64 transition-all w-full max-w-full overflow-x-hidden">
            <x-app.header :username="$username" />
            <main class="p-3.5 sm:p-6 mt-16 max-w-7xl mx-auto pb-28 sm:pb-10">
                {{ $slot }}
            </main>
        </div>
    </div>
    <div class="px-3 sm:px-4 fixed bottom-3 left-0 w-full z-40 sm:hidden pb-[env(safe-area-inset-bottom,0px)]">
        <x-app.btm />
    </div>
    <x-block.pin :user="$user" />
    @else
    <div class="flex min-h-screen" id="contentWrapper">
        <main class="flex-1 p-4 max-w-2xl mx-auto overflow-x-hidden">
            {{ $slot }}
        </main>
    </div>
    @endif
    <x-app.loader />
</body>
</html>

 