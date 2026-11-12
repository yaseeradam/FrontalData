@php
    $primary = jannah_opt('primary_color');
@endphp
 
<input {{ $attributes->merge([
    'class' => "bg-gray-100 w-full text-sm text-gray-800 border-ash px-4 py-3.5 rounded-lg outline-[{$primary}] focus:bg-transparent h-11"
]) }} />