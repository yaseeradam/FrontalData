<div class="col-span-full md:col-span-3">
    <div class="flex items-center gap-3 p-3 sticky top-0 bg-white/90 backdrop-blur-md rounded-2xl border border-slate-100 shadow-xs z-20 mb-4">
        <button type="button" onclick="if(window.history.length > 1){ history.back(); } else { window.location.href='{{ route('dashboard') }}'; }" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 hover:text-blue-600 hover:bg-blue-50 active:scale-95 transition-all flex items-center justify-center cursor-pointer shadow-xs shrink-0" title="Go Back">
            <i class="ri-arrow-left-line text-lg font-bold"></i>
        </button>
        <h1 class="text-base font-bold text-slate-900">{{ $title }}</h1>
    </div>
    <div class="space-y-4 overflow-hidden">
        {{ $slot }}
    </div>
</div>
