@props(['drk' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-admin.head :hasTheme="true" theme="#f3f4f6"></x-head>
</head>
<body class="bg-red-500">
   
       
            {{ $slot }}
       
   
</body>
</html>