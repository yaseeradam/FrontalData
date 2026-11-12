@php
    $wa = jannah_opt('support_whatsapp');
@endphp
@props(['txt' => '', 'txt2' => '', 'txt3' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-headx />
    <body class="min-h-screen bg-slate-50/50 flex items-center justify-center p-3 sm:p-6 relative">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl border border-slate-100 grid md:grid-cols-[11fr_13fr] overflow-hidden min-h-[640px]">
           
            <!-- Left Branding Column (Desktop) -->
            <div class="hidden md:flex flex-col justify-between p-10 lg:p-12 bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-950 text-white relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 rounded-full bg-blue-600/15 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 space-y-6">
                    <a href="{{ url('/') }}" class="inline-block">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ jannah_opt('site_name', 'FrontalData') }}" class="h-10 w-auto brightness-0 invert">
                    </a>

                    <div class="space-y-3 pt-6">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-cyan-400/20 text-cyan-300">
                            Enterprise Telecom Infrastructure
                        </span>
                        <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                            {{ $txt ?: 'Superfast Data, Airtime & Automated Bills.' }}
                        </h2>
                        <p class="text-slate-300 text-sm leading-relaxed">
                            Access wholesale SME data bundles, automated virtual funding accounts, electricity tokens, and cable renewals in seconds.
                        </p>
                    </div>

                    <div class="space-y-3 pt-4">
                        <div class="flex items-center gap-3 text-sm text-slate-200">
                            <i class="ri-checkbox-circle-fill text-cyan-400 text-lg"></i>
                            <span>Sub-5 second automated delivery across all networks</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-200">
                            <i class="ri-checkbox-circle-fill text-cyan-400 text-lg"></i>
                            <span>Automated virtual bank accounts with 0% downtime</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-200">
                            <i class="ri-checkbox-circle-fill text-cyan-400 text-lg"></i>
                            <span>Developer-friendly REST APIs for custom reseller apps</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-8 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                    <span>© 2026 FrontalData</span>
                    <span>256-Bit SSL Encrypted</span>
                </div>
            </div>

            <!-- Right Form Column -->
            <div class="w-full flex flex-col justify-center p-6 sm:p-10 lg:p-12">
                <div class="w-full max-w-md mx-auto space-y-6">
                    <div class="text-center sm:text-left">
                        <a href="{{ url('/') }}" class="inline-block mb-4 md:hidden">
                            <img src="{{ asset('images/logo.png') }}" alt="FrontalData" class="h-9 w-auto mx-auto sm:mx-0">
                        </a>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                            {{ $txt2 }} 
                        </h2>
                        @if(!empty($txt3))
                            <p class="text-slate-500 text-sm mt-1.5">
                                {{ $txt3 }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-2">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($wa) && $wa !== 'false')
        <a href="{{ $wa }}"
           class="fixed bottom-6 right-6 bg-emerald-500 text-white p-3 rounded-full shadow-2xl hover:bg-emerald-600 transition-all hover:scale-110 flex justify-center items-center z-50" title="Chat on WhatsApp">
            <i class="ri-whatsapp-line text-2xl"></i>
        </a>
        @endif

        <script>
            const toggleBtn = document.getElementById('togglePassword');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    const passwordField = document.getElementById('password');
                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        this.classList.replace('ri-eye-off-line', 'ri-eye-line');
                    } else {
                        passwordField.type = 'password';
                        this.classList.replace('ri-eye-line', 'ri-eye-off-line');
                    }
                });
            }
        </script>
        <x-app.loader />
    </body>
</html>
