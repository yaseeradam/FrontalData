@php
    $primary = jannah_opt('primary_color');
@endphp
<button {{ $attributes->merge(["class" => "ripple mt-8 w-full bg-primary text-white text-lg font-bold py-[14px] rounded-lg hover:opacity-90 transition shadow-xl hover:shadow-none text-sm relative overflow-hidden"]) }}>{{ $slot }}</button>