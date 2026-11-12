
            @php
    $supportChannels = [
        ['route' => @jannah_opt('support_whatsapp'), 'icon' => 'ri-whatsapp-line', 'label' => 'WhatsApp Desk', 'desc' => 'Instant chat & resolution', 'badge' => 'Fastest', 'color' => 'bg-emerald-500 text-white', 'border' => 'hover:border-emerald-500'],
        ['route' => @jannah_opt('support_phone') ? 'tel:'.@jannah_opt('support_phone') : null, 'icon' => 'ri-phone-line', 'label' => 'Direct Phone', 'desc' => @jannah_opt('support_phone', 'Call Customer Service'), 'badge' => 'Direct', 'color' => 'bg-blue-600 text-white', 'border' => 'hover:border-blue-500'],
        ['route' => @jannah_opt('support_gmail') ? 'mailto:'.@jannah_opt('support_gmail') : null, 'icon' => 'ri-mail-line', 'label' => 'Email Helpdesk', 'desc' => @jannah_opt('support_gmail', 'support@frontaldata.com'), 'badge' => 'Official', 'color' => 'bg-rose-500 text-white', 'border' => 'hover:border-rose-500'],
        ['route' => @jannah_opt('support_telegram'), 'icon' => 'ri-telegram-line', 'label' => 'Telegram Channel', 'desc' => 'Service updates & alerts', 'badge' => 'Alerts', 'color' => 'bg-sky-500 text-white', 'border' => 'hover:border-sky-500'],
        ['route' => @jannah_opt('support_group'), 'icon' => 'ri-group-line', 'label' => 'Community Group', 'desc' => 'Join fellow resellers', 'badge' => 'Community', 'color' => 'bg-indigo-600 text-white', 'border' => 'hover:border-indigo-500'],
        ['route' => @jannah_opt('support_facebook'), 'icon' => 'ri-facebook-fill', 'label' => 'Facebook', 'desc' => 'FrontalData on Facebook', 'badge' => 'Social', 'color' => 'bg-blue-700 text-white', 'border' => 'hover:border-blue-600'],
        ['route' => @jannah_opt('support_instagram'), 'icon' => 'ri-instagram-line', 'label' => 'Instagram', 'desc' => 'Updates, promos & tips', 'badge' => 'Social', 'color' => 'bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 text-white', 'border' => 'hover:border-pink-500'],
        ['route' => @jannah_opt('support_youtube'), 'icon' => 'ri-youtube-line', 'label' => 'YouTube', 'desc' => 'Video guides & tutorials', 'badge' => 'Tutorials', 'color' => 'bg-red-600 text-white', 'border' => 'hover:border-red-600'],
    ];
@endphp

<x-app-layout :user="$user" title="Help & Support">
    <div class="col-span-full md:col-span-3 space-y-4">
        <x-app.back-header title="Help & Support" fallback="{{ route('dashboard') }}" />

        <!-- Support Hero Card -->
        <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 text-white rounded-3xl p-6 sm:p-7 shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span> Support Online 24/7
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black mt-2">How can we help you today?</h2>
                <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-lg leading-relaxed">
                    Experiencing an issue with your data order, bill payment, or wallet funding? Our dedicated support team is on standby to assist you immediately.
                </p>
            </div>
            <i class="ri-customer-service-2-line absolute -right-6 -bottom-6 text-9xl text-white/10 pointer-events-none"></i>
        </div>

        <!-- Contact Channels List -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider px-1">Official Support Channels</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($supportChannels as $channel)
                    @if (!empty($channel['route']))
                        <a href="{{ $channel['route'] }}" target="_blank" rel="noopener noreferrer" class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center space-x-4 hover:shadow-md transition-all group {{ $channel['border'] }}">
                            <div class="w-12 h-12 rounded-2xl {{ $channel['color'] }} flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="{{ $channel['icon'] }} text-2xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-slate-800 truncate">{{ $channel['label'] }}</h4>
                                    <span class="text-[10px] font-semibold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full">{{ $channel['badge'] }}</span>
                                </div>
                                <p class="text-xs text-slate-500 truncate mt-0.5">{{ $channel['desc'] }}</p>
                            </div>
                            <i class="ri-arrow-right-s-line text-slate-300 group-hover:text-blue-600 text-lg transition-colors"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Side Troubleshooting & FAQs -->
    <div class="col-span-full md:col-span-2 space-y-4 md:mt-14">
        <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center">
                <i class="ri-questionnaire-line text-blue-600 text-base mr-2"></i>
                Instant Troubleshooting
            </h3>
            <div class="space-y-3.5 text-xs text-slate-600">
                <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1 flex items-center">
                        <i class="ri-time-line text-amber-500 mr-1.5"></i> Wallet Funding Delay?
                    </p>
                    <p class="text-slate-500 leading-relaxed text-[11px]">Direct automated bank transfers (Wema, Squad, Moniepoint) typically reflect within 60 seconds. If delayed beyond 5 minutes, send the transaction session ID to our WhatsApp desk.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1 flex items-center">
                        <i class="ri-wifi-line text-blue-500 mr-1.5"></i> Data Order Status?
                    </p>
                    <p class="text-slate-500 leading-relaxed text-[11px]">Check your Transaction Receipt in the Transactions tab to view the real-time telecom gateway response and API server log.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                    <p class="font-bold text-slate-800 mb-1 flex items-center">
                        <i class="ri-shield-keyhole-line text-emerald-500 mr-1.5"></i> Transaction PIN Forgotten?
                    </p>
                    <p class="text-slate-500 leading-relaxed text-[11px]">Go to Settings > Change PIN to securely reset your 4-digit transaction authorization code.</p>
                </div>
            </div>
        </div>

        <!-- Working Hours Badge -->
        <div class="bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-200/60 rounded-3xl p-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shrink-0 shadow-sm">
                    <i class="ri-headphone-line text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-emerald-900">5-Minute Response SLA</h4>
                    <p class="text-[11px] text-emerald-700 mt-0.5">Automated ticketing & live agents resolve 98% of inquiries on first contact.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>