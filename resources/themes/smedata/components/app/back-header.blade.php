@props(['title' => '', 'fallback' => route('dashboard')])
<div class="my-4 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <button type="button" 
                onclick="if(window.history.length > 1){ history.back(); } else { window.location.href='{{ $fallback }}'; }" 
                class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50/50 active:scale-95 transition-all flex items-center justify-center shadow-xs cursor-pointer group shrink-0" 
                title="Go Back"
                aria-label="Go Back">
            <i class="ri-arrow-left-line text-lg font-bold group-hover:-translate-x-0.5 transition-transform"></i>
        </button>
        @if($title)
            <h1 class="font-bold text-lg text-slate-900 tracking-tight">{{ $title }}</h1>
        @endif
    </div>
    @if(isset($slot) && !empty(trim($slot)))
        <div>
            {{ $slot }}
        </div>
    @endif
</div>
