@php
    $primary = jannah_opt('primary_color');
    $label = $label ?? 'Data'; 
@endphp

<div class="relative mt-6">
    <input 
       
        placeholder=" " 
          {{ $attributes->merge(['class' => "peer w-full px-4 py-[15px] bg-transparent border border-gray-300 rounded-lg 
        focus:outline-none focus:ring-1 focus:ring-[$primary] focus:border-transparent"]) }}>

    <label 
        for="input-fieldd" 
        class="absolute left-3 top-0 -translate-y-1/2 peer-focus:bg-white px-1 text-gray-600 text-xs transition-all 
                            peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-gray-400 
                            peer-placeholder-shown:text-base peer-focus:top-0 peer-focus:-translate-y-1/2 peer-focus:text-[{{ $primary }}] 
                            peer-focus:text-xs peer-not-placeholder-shown:top-0 peer-not-placeholder-shown:-translate-y-1/2 
                            peer-not-placeholder-shown:text-[{{ $primary }}] peer-not-placeholder-shown:text-xs">
        {{ $label }}
    </label>
</div>
