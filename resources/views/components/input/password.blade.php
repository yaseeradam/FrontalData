@php
    $primary = jannah_opt('primary_color');
@endphp
 
<div class="relative">
    <input type="password" {{ $attributes->merge(['class' => "bg-gray-100 w-full text-sm text-gray-800 border-ash px-4 py-3.5 rounded-lg outline-[$primary] focus:bg-transparent h-11" ]) }} />
    <span id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-4 cursor-pointer">
        <i class="jannah-eye-slash"></i>
    </span>
</div>