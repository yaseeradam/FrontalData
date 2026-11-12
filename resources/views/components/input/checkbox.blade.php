@php
    $primary = jannah_opt('primary_color');
@endphp

<input type="checkbox" {{ $attributes->merge(['class' => "h-4 w-4 text-[$primary] focus:none border-gray-300 rounded"]) }} />