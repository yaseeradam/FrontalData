
<div class="col-span-full grid md:grid-cols-5 gap-4">
    <!-- User Package & Upgrade Card -->
    <div class="bg-white p-5 rounded-3xl flex flex-col sm:flex-row justify-between items-start sm:items-center shadow-sm border border-slate-100 hover:shadow-md transition-all md:col-span-2">
        <div class="space-y-1">
            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 uppercase tracking-wider">Tier Status</span>
            <h4 class="text-sm font-bold text-slate-800">Standard User Package</h4>
            <p class="text-xs text-slate-500">Upgrade to Agent or Reseller to enjoy wholesale bulk discounts.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('core.user.upgrade') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-cyan-500 shadow-md hover:scale-105 transition-all">
                <i class="ri-vip-crown-fill text-amber-300"></i>
                Upgrade
            </a>
        </div>
    </div> 

    <!-- Today's Data Metric Card -->
    <div class="bg-white p-5 rounded-3xl flex flex-col justify-center items-center text-center shadow-sm border border-slate-100 hover:shadow-md transition-all md:col-span-1">
        <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl mb-2">
            <i class="ri-wifi-line"></i>
        </div>
        <div class="text-[11px] font-medium text-slate-500">Data Purchased Today</div>
        <div class="text-2xl font-black text-slate-900 mt-1">
            {{ $sales }} <span class="text-xs font-bold text-blue-600">GB</span>
        </div>
    </div>

    <!-- Promotional AI Banner Card -->
    <div class="rounded-3xl overflow-hidden shadow-sm border border-slate-100 md:col-span-2 group">
        <a href="{{ route('core.data') }}">
            <img src="{{ asset('images/banners/sme_data.jpg') }}" alt="Wholesale Data Promo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 max-h-[140px]">
        </a>
    </div>
</div>