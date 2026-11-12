@php
    $primary = jannah_opt('primary_color');
    $label = $label ?? ''; 
@endphp

<label class="flex items-center">
                            <span class="flex items-center justify-center transition-all duration-500 hover:bg-gray-100 size-10 rounded-full ripple">
                                 <input type="checkbox" {{ $attributes->merge(['class' => "form-checkbox text-[$primary] accent-[$primary] w-4 h-4 rounded-lg p-2"]) }} >
                            </span>
                            <span class="ml-2 text-gray-700 text-sm font-medium">{{ $label }}</span>
                        </label>