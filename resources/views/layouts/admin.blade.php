@props(['drk' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-admin.head/>
</head>
<body class="bg-[#f1f1f1]">
    @yield('content')
    {{ $slot ?? '' }}
</body>
</html>
