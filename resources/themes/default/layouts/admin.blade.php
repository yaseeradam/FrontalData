@props(['drk' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-head></x-head>
</head>
<body class="font-sans antialiased bg-[#f1f1f1] {{ $drk ? 'bg-gray-100' : '' }}">
    {{ $slot }}
</body>
</html>
