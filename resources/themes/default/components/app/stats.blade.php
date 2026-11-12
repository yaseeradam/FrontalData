@php 
    $primary = jannah_opt('primary_color', '#0066FF'); 
@endphp

<div class="relative flex flex-col w-full md:col-span-3 space-y-4"> 
    <!-- Neo-Fintech Electric Gradient Wallet Card -->
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-7 text-white shadow-xl shadow-blue-500/15 bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-500/20">
        <!-- Background light waves & watermarks -->
        <div class="absolute -right-8 -top-8 w-44 h-44 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-44 h-44 rounded-full bg-cyan-400/20 blur-xl pointer-events-none"></div>
        <div class="absolute right-6 top-6 opacity-20 pointer-events-none">
            <img src="{{ asset('images/smfavicon.png') }}" class="w-14 h-14" alt="FrontalData">
        </div>

        <div class="relative z-10 flex flex-col justify-between h-full space-y-5">
            <!-- Header row: Title & Eye Toggle -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-white/80">
                    <span class="w-2 h-2 rounded-full bg-cyan-300 animate-pulse"></span>
                    <span>FrontalData Wallet Balance</span>
                </div>
                <button type="button" id="eye" onclick="toggleBalance()" class="p-1.5 rounded-full bg-white/10 hover:bg-white/20 text-white/90 transition-colors" title="Toggle visibility">
                    <i class="ri-eye-line text-lg"></i>
                </button>
            </div>

            <!-- Big Balance Display -->
            <div class="space-y-1">
                <div class="text-3xl sm:text-4xl font-black tracking-tight text-white flex items-baseline gap-1">
                    <span class="text-2xl text-cyan-200">₦</span>
                    <span id="balance" class="transition-all duration-300">@numberformat($balance)</span>
                </div>
                <p class="text-xs text-white/70">Instant 24/7 Automated Settlement</p>
            </div>

            <!-- 3 Quick Action Buttons -->
            <div class="grid grid-cols-3 gap-3 pt-2">
                <button onclick="load('{{ route('core.deposit') }}')" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-2xl bg-white text-blue-700 font-bold text-xs shadow-md hover:bg-blue-50 hover:scale-105 transition-all">
                    <i class="ri-add-circle-fill text-base text-blue-600"></i>
                    <span>Add Money</span>
                </button>

                <a href="{{ route('core.transfer') }}" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-2xl bg-white/15 backdrop-blur-md text-white font-semibold text-xs border border-white/20 hover:bg-white/25 hover:scale-105 transition-all">
                    <i class="ri-arrow-left-right-line text-base text-cyan-300"></i>
                    <span>Transfer</span>
                </a>

                <a href="{{ route('core.transactions') }}" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-2xl bg-white/15 backdrop-blur-md text-white font-semibold text-xs border border-white/20 hover:bg-white/25 hover:scale-105 transition-all">
                    <i class="ri-history-line text-base text-cyan-200"></i>
                    <span>History</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Feed Card -->
    @if(count($transactions) > 0)
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Recent Activity</h4>
            <a href="{{ route('core.transactions') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">View All</a>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach ($transactions as $transaction)
                <x-block.transaction :transaction="$transaction" />
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function toggleBalance() {
        let balance = document.getElementById("balance");
        let eye = document.getElementById("eye").querySelector("i");
        
        if (balance.style.filter === "blur(6px)") {
            balance.style.filter = "none";
            eye.classList.remove("ri-eye-off-line");
            eye.classList.add("ri-eye-line");
        } else {
            balance.style.filter = "blur(6px)";
            eye.classList.remove("ri-eye-line");
            eye.classList.add("ri-eye-off-line");
        }
    }
</script>