<div class="col-span-full md:col-span-3">
    <div class="flex items-center gap-3 mb-4">
        <button type="button" onclick="if(window.history.length > 1){ history.back(); } else { window.location.href='{{ route('dashboard') }}'; }" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:bg-blue-50 active:scale-95 transition-all flex items-center justify-center cursor-pointer shadow-xs shrink-0" title="Go Back">
            <i class="ri-arrow-left-line text-lg font-bold"></i>
        </button>
        <div>
            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ $title }}</h1>
            @if(isset($des) && $des)
                <p class="text-xs text-slate-500 mt-0.5">{{ $des }}</p>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 sm:p-6 overflow-hidden">
        {{ $slot }}
    </div>
</div>
