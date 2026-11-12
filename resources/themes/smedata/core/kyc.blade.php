<x-app-layout :user="$user" title="KYC Verification">
    <div class="col-span-full md:col-span-3 space-y-4">
        <x-app.back-header title="Identity Verification (KYC)" fallback="{{ route('profile.edit') }}" />

        <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-100">
            <!-- Header & Security Badge -->
            <div class="flex items-start justify-between pb-4 mb-5 border-b border-slate-100">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-800">Complete KYC Verification</h2>
                    <p class="text-xs text-slate-500 mt-1">Provide your verified BVN or NIN to upgrade your daily transaction limits and unlock automated virtual accounts.</p>
                </div>
                <div class="hidden sm:flex items-center space-x-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold shrink-0">
                    <i class="ri-shield-check-fill text-sm"></i>
                    <span>CBN / NDPR Compliant</span>
                </div>
            </div>

            <!-- Verification Notice Banner -->
            <div class="p-4 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50/60 border border-blue-100/80 mb-6 flex items-start space-x-3">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                    <i class="ri-information-fill text-lg"></i>
                </div>
                <div class="text-xs text-slate-600 leading-relaxed">
                    <span class="font-bold text-slate-800">Important:</span> Ensure that your Name and Date of Birth match the records registered with your Bank (BVN) or NIMC (NIN). Verification takes only a few seconds.
                </div>
            </div>

            <!-- KYC Form -->
            <form id="verify-kyc-form" class="space-y-4 sm:space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Choose ID Type</label>
                    <select name="id_type" required class="w-full text-sm font-semibold rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                        <option value="nin">NIN - National Identification Number (11 digits)</option>
                        <option value="bvn">BVN - Bank Verification Number (11 digits)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">ID Number (NIN / BVN)</label>
                    <input type="text" name="id" maxlength="11" minlength="11" required placeholder="Enter your 11-digit ID number" class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Registered Phone Number</label>
                    <input type="tel" name="phone_number" maxlength="11" minlength="11" required placeholder="e.g. 08012345678" value="{{ $user->phone ?? '' }}" class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Date Of Birth</label>
                    <input type="date" name="date_of_birth" required class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50/50 p-3.5 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" id="verify-kyc-button" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/35 transition-all transform active:scale-[0.99] flex items-center justify-center space-x-2">
                        <i class="ri-check-shield-line text-lg"></i>
                        <span>Verify & Upgrade Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side Benefits & Help Column -->
    <div class="col-span-full md:col-span-2 space-y-4 md:mt-14">
        <!-- Verification Benefits -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center">
                <i class="ri-award-line text-blue-600 text-base mr-2"></i>
                KYC Verification Benefits
            </h3>
            <ul class="space-y-3.5 text-xs text-slate-600">
                <li class="flex items-start space-x-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                        <i class="ri-check-line text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800">Tier 2/3 Limits:</span> Unlimited automated wallet funding without restrictions.
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                        <i class="ri-bank-card-line text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800">Dedicated Bank Accounts:</span> Instant automated reserved accounts (Wema / Moniepoint / Squad).
                    </div>
                </li>
                <li class="flex items-start space-x-3">
                    <div class="w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                        <i class="ri-shield-user-line text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-800">Fraud Protection:</span> 256-bit encrypted data handling strictly for identity validation.
                    </div>
                </li>
            </ul>
        </div>

        <!-- Support Card -->
        <div class="bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-3xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-xs font-bold uppercase tracking-wider text-blue-300 mb-2 flex items-center">
                    <i class="ri-customer-service-2-line mr-2 text-base"></i> Need Assistance?
                </div>
                <p class="text-xs text-slate-300 leading-relaxed mb-4">Having difficulties validating your BVN or NIN? Our verification desk is available 24/7 on WhatsApp.</p>
                <a href="{{ route('core.support') }}" class="inline-flex items-center space-x-2 text-xs font-bold px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white backdrop-blur transition-all border border-white/10">
                    <span>Contact Support Desk</span>
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        submitForm('verify-kyc-form', 'verify-kyc-button', '{{ route('core.kyc.verify') }}');
    </script>
</x-app-layout>