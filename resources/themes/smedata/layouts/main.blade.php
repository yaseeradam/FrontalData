@props(['drk' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-head></x-head>
</head>
<body class="font-sans antialiased mn-skin {{ $drk ? 'bg-[#f8f8fb]' : '' }}">
    <div class="flex min-h-screen" id="contentWrapper">
        <x-app.btm />
        <main class="flex-1 lg:ml-1 lg:pl-2 lg:pr-2 lg:pt-2 lg:pb-4">
            {{ $slot }}
        </main>
    </div>
    <x-app.loader />
</body>
</html>

