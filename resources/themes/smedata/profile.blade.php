<x-app-layout :user="$user">
    @php
        $primaryColor = jannah_opt('primary_color', '#0066FF');
        $siteName = jannah_opt('site_name', 'FrontalData');
        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->username;
        $userRole = ucfirst($user->role ?? 'Customer');
        $isAgent = strtolower($user->role ?? '') === 'agent';
        $supportWa = jannah_opt('support_whatsapp');
    @endphp

    <!-- Left Main Column (Profile Card & Settings Menu) -->
    <div class="col-span-full md:col-span-3">
        <x-app.back-header title="Profile & Settings" />

        <!-- 1. Hero User Identity Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden mb-5">
            <!-- Decorative Gradient Banner -->
            <div class="h-28 bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 relative p-4 flex items-start justify-between">
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-white text-xs font-bold shadow-xs">
                    <span class="w-2 h-2 rounded-full {{ $isAgent ? 'bg-amber-400' : 'bg-emerald-400' }} animate-pulse"></span>
                    {{ $isAgent ? 'VIP Agent' : 'Standard Member' }}
                </div>
                <div class="text-white/80 text-xs font-medium">
                    Joined {{ $user->created_at ? $user->created_at->format('M Y') : 'Active' }}
                </div>
            </div>

            <!-- Profile Info with Overlapping Avatar -->
            <div class="px-5 pb-5 pt-0 relative">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-10 mb-4">
                    <div class="flex items-end gap-3.5">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 border-4 border-white shadow-md flex items-center justify-center text-white text-2xl font-black shrink-0">
                            {{ strtoupper(substr($user->first_name ?: $user->username, 0, 1)) }}
                        </div>
                        <div class="mb-1">
                            <h2 class="text-xl font-extrabold text-slate-900 leading-tight">
                                {{ $fullName }}
                            </h2>
                            <p class="text-xs font-semibold text-slate-500">
                                {{ '@' . $user->username }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('core.user.edit') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 text-xs font-bold transition-all border border-slate-200/60 self-start sm:self-auto">
                        <i class="ri-edit-line text-sm"></i> Edit Profile
                    </a>
                </div>

                <!-- Contact Info Badges -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-3 border-t border-slate-100 text-xs">
                    <div class="flex items-center gap-2 text-slate-600 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                        <i class="ri-mail-line text-blue-600 text-sm shrink-0"></i>
                        <span class="truncate">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-600 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                        <i class="ri-phone-line text-emerald-600 text-sm shrink-0"></i>
                        <span class="truncate">{{ $user->phone ?: 'No phone provided' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Wallet Financial Balances -->
        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Main Wallet</div>
                <div class="text-base sm:text-lg font-black text-slate-900 mt-0.5">₦{{ number_format($user->wallet_balance ?? 0, 2) }}</div>
            </div>
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Referral Bonus</div>
                <div class="text-base sm:text-lg font-black text-emerald-600 mt-0.5">₦{{ number_format($user->referral_balance ?? 0, 2) }}</div>
            </div>
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs text-center">
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Commission</div>
                <div class="text-base sm:text-lg font-black text-blue-600 mt-0.5">₦{{ number_format($user->cashback_balance ?? 0, 2) }}</div>
            </div>
        </div>

        <!-- 3. Account Settings Menu List -->
        <div class="space-y-2.5 mb-10">
            <div class="text-xs font-extrabold text-slate-400 uppercase tracking-wider px-1">Security & Account Settings</div>

            <!-- Personal Info -->
            <a href="{{ route('core.user.edit') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:bg-blue-50/20 transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="ri-user-3-line"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800 group-hover:text-blue-600 transition-colors">Personal Information</div>
                        <div class="text-xs text-slate-400">Update your name, phone number, and contact info</div>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <!-- Password & Security -->
            <a href="{{ route('core.user.password') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:bg-blue-50/20 transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="ri-lock-password-line"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800 group-hover:text-indigo-600 transition-colors">Password & Security</div>
                        <div class="text-xs text-slate-400">Change your login password and manage access</div>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <!-- Payment PIN -->
            <a href="{{ route('core.user.pin') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:bg-blue-50/20 transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="ri-shield-keyhole-line"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800 group-hover:text-amber-600 transition-colors">Payment Security PIN</div>
                        <div class="text-xs text-slate-400">Set up or update your 4-digit transaction PIN</div>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-slate-400 group-hover:text-amber-600 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <!-- KYC Verification -->
            <a href="{{ route('core.kyc') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:bg-blue-50/20 transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="ri-id-card-line"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800 group-hover:text-emerald-600 transition-colors">KYC Verification</div>
                        <div class="text-xs text-slate-400">Verify your NIN / BVN to unlock higher daily limits</div>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <!-- Refer & Earn -->
            <a href="{{ route('core.earn') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:bg-blue-50/20 transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="ri-gift-line"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800 group-hover:text-purple-600 transition-colors">Refer & Earn Program</div>
                        <div class="text-xs text-slate-400">Share your referral code and earn instant bonuses</div>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-slate-400 group-hover:text-purple-600 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <!-- Agent Upgrade -->
            <a href="{{ route('core.user.upgrade') }}" class="group bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:bg-blue-50/20 transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                        <i class="ri-vip-crown-line"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-800 group-hover:text-cyan-600 transition-colors">Agent Tier Upgrade</div>
                        <div class="text-xs text-slate-400">Unlock wholesale reseller discounts on data & airtime</div>
                    </div>
                </div>
                <i class="ri-arrow-right-s-line text-xl text-slate-400 group-hover:text-cyan-600 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="w-full text-left group bg-rose-50/60 rounded-2xl p-4 border border-rose-100 hover:bg-rose-100/60 transition-all flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="ri-logout-box-r-line"></i>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-rose-700">Log Out</div>
                            <div class="text-xs text-rose-400">Safely terminate your current session</div>
                        </div>
                    </div>
                    <i class="ri-arrow-right-s-line text-xl text-rose-400 group-hover:translate-x-0.5 transition-all"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Sidebar Account Widgets (Replaces previous blank 250px gray boxes) -->
    <div class="col-span-full md:col-span-2 space-y-4 md:mt-14">
        <!-- 1. VIP Reseller Tier Status Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-5 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-blue-500/20 blur-2xl pointer-events-none"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Account Tier</span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $isAgent ? 'bg-amber-400/20 text-amber-300 border border-amber-400/30' : 'bg-blue-400/20 text-blue-300 border border-blue-400/30' }}">
                    {{ $isAgent ? '★ VIP AGENT' : 'STANDARD TIER' }}
                </span>
            </div>
            
            <h3 class="text-lg font-black leading-snug">
                {{ $isAgent ? 'Wholesale Reseller Active' : 'Save More with VIP Agent' }}
            </h3>
            
            <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                {{ $isAgent 
                    ? 'You are enjoying discounted wholesale prices on all SME, Corporate data bundles and airtime top-ups.' 
                    : 'Upgrade your account to VIP Agent status to purchase MTN data ₦3-₦6 cheaper per GB and earn on referrals!' }}
            </p>

            @if(!$isAgent)
                <a href="{{ route('core.user.upgrade') }}" class="mt-4 inline-flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-400 text-slate-950 font-extrabold text-xs shadow-md hover:opacity-95 transition-all">
                    <i class="ri-vip-crown-fill text-sm"></i> Upgrade Now
                </a>
            @endif
        </div>

        <!-- 2. Referral Code Quick Widget -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Your Referral Code</span>
                <i class="ri-share-line text-slate-400 text-sm"></i>
            </div>
            <div class="flex items-center gap-2 p-2.5 bg-slate-50 rounded-xl border border-slate-200/80">
                <span class="font-mono font-black text-slate-800 text-sm flex-1 tracking-wider" id="refCodeText">{{ $user->username }}</span>
                <button type="button" onclick="copyRefCode()" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                    <i class="ri-file-copy-line"></i> Copy
                </button>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">
                Earn ₦@jannah_opt('agent_upgrade_earning', '500') every time someone registers and upgrades using your code.
            </p>
        </div>

        <!-- 3. Dedicated Customer Support -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="ri-customer-service-2-line"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Need Assistance?</h4>
                    <p class="text-xs text-slate-400">Our customer care desk is active 24/7</p>
                </div>
            </div>

            @if(!empty($supportWa) && $supportWa !== 'false')
                <a href="{{ $supportWa }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs transition-all shadow-xs">
                    <i class="ri-whatsapp-line text-base"></i> Chat on WhatsApp
                </a>
            @else
                <div class="text-xs text-slate-500 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-center font-medium">
                    Email support: support@{{ request()->getHost() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyRefCode() {
            const code = document.getElementById('refCodeText').innerText;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(code).then(() => {
                    Toastify({
                        text: "Referral code copied to clipboard!",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #0066FF, #00D2FF)",
                            borderRadius: "12px",
                            fontWeight: "bold"
                        }
                    }).showToast();
                });
            }
        }
    </script>
</x-app-layout>
