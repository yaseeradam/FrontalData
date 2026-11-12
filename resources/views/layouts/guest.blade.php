<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-guest.head>
         
    </x-guest.head>
    <body>
       {{ $slot }}

    </body>
</html>
