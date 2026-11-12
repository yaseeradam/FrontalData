@php
    $primary = jannah_opt('primary_color');
@endphp
 
<input {{ $attributes->merge([
    'class' => "w-full text-sm text-gray-800 border border-lg border-gray-100 px-4 py-4 rounded-lg outline-[{$primary}] focus:bg-transparent h-12 "
]) }} />