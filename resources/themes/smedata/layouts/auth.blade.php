@php
    $primary = jannah_opt('primary_color');
    $wa = jannah_opt('support_whatsapp');
    $sitename = jannah_opt('site_name');
@endphp
@props(['txt' => '', 'txt2' => '', 'txt3' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-headx />
    <body class="mn-auth min-h-screen relative">

        {{-- Al-Manarah auth styling — scoped to .mn-auth so it never touches the dashboard.
             Palette + fonts mirror the marketing site (emerald / gold). --}}
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..700&family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&display=swap');

            .mn-auth {
                --mn-emerald: #0d4f3c;
                --mn-emerald-2: #0a3d2e;
                --mn-gold: #c8a26b;
                --mn-gold-soft: #f1e6d0;
                --mn-ink: #0e1a14;
                --mn-cream: #f6f3eb;
                --mn-card: #fffdf6;
                --mn-line: rgba(14, 26, 20, 0.10);
                --mn-muted: #6b7a72;
                background: var(--mn-cream);
                color: var(--mn-ink);
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            }
            .mn-auth h1, .mn-auth h2, .mn-auth h3 {
                font-family: 'Bricolage Grotesque', ui-sans-serif, system-ui, sans-serif;
                letter-spacing: -0.025em;
            }
            .mn-serif { font-family: 'Instrument Serif', serif; font-style: italic; font-weight: 400; }

            /* Brand mark — the marketing-site beacon */
            .mn-mark {
                width: 38px; height: 38px; border-radius: 11px; background: var(--mn-emerald);
                position: relative; display: grid; place-items: center; flex-shrink: 0;
                box-shadow: inset 0 -8px 16px rgba(0,0,0,0.25), 0 4px 10px -4px var(--mn-emerald);
            }
            .mn-mark::before {
                content: ""; position: absolute; inset: 8px;
                background: radial-gradient(circle at 30% 30%, var(--mn-gold), transparent 60%);
                border-radius: 50%;
            }
            .mn-mark::after {
                content: ""; position: absolute; left: 50%; top: 50%; width: 12px; height: 12px;
                border: 2px solid var(--mn-cream); border-right: 0; border-bottom: 0;
                transform: translate(-50%, -55%) rotate(45deg); border-radius: 2px;
            }

            /* Left hero panel */
            .mn-hero {
                position: relative; overflow: hidden;
                background: linear-gradient(150deg, var(--mn-emerald), var(--mn-emerald-2) 70%, var(--mn-ink));
                color: var(--mn-cream);
            }
            .mn-hero::before {
                content: ""; position: absolute; inset: -30% -30% auto auto; width: 70%; aspect-ratio: 1;
                background: radial-gradient(circle, rgba(200,162,107,0.35), transparent 60%);
                filter: blur(10px); pointer-events: none;
            }
            .mn-hero-title { font-size: clamp(30px, 3vw, 44px); line-height: 1.02; font-weight: 600; }
            .mn-eyebrow {
                display: inline-flex; align-items: center; gap: 8px;
                font-size: 11px; text-transform: uppercase; letter-spacing: 0.14em;
                background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.16);
                padding: 6px 12px; border-radius: 999px; font-family: 'Inter', sans-serif;
            }
            .mn-check { display: flex; align-items: center; gap: 12px; font-size: 14.5px; color: rgba(246,243,235,0.85); }
            .mn-check i { color: var(--mn-gold); }

            /* Right form panel */
            .mn-form-title { font-size: clamp(24px, 2.4vw, 32px); font-weight: 600; }
            .mn-card {
                background: var(--mn-card); border: 1px solid var(--mn-line);
                border-radius: 22px; box-shadow: 0 24px 60px -20px rgba(14,26,20,0.18), 0 2px 6px rgba(14,26,20,0.05);
            }

            /* Restyle the shared Blade components, only inside auth */
            .mn-auth form button,
            .mn-auth button[type="submit"] {
                background: var(--mn-emerald) !important;
                border-radius: 999px !important;
                font-weight: 600 !important;
                box-shadow: 0 8px 22px -10px rgba(13,79,60,0.6), inset 0 1px 0 rgba(255,255,255,0.16) !important;
                transition: background .15s ease, transform .15s ease !important;
            }
            .mn-auth form button:hover,
            .mn-auth button[type="submit"]:hover { background: var(--mn-emerald-2) !important; }
            .mn-auth form button:active { transform: translateY(1px); }

            .mn-auth input:not([type="checkbox"]) { border-radius: 12px !important; background: var(--mn-card) !important; }
            .mn-auth input:not([type="checkbox"]):focus {
                border-color: var(--mn-emerald) !important;
                box-shadow: 0 0 0 1px var(--mn-emerald) !important;
                --tw-ring-color: var(--mn-emerald) !important;
            }
            .mn-auth input:focus ~ label,
            .mn-auth input:not(:placeholder-shown) ~ label { color: var(--mn-emerald) !important; }
            .mn-auth input[type="checkbox"] { accent-color: var(--mn-emerald) !important; }
            .mn-auth .text-primary { color: var(--mn-emerald) !important; }
            .mn-auth .text-primary:hover { color: var(--mn-emerald-2) !important; }
        </style>

        <div class="grid md:grid-cols-[5fr_6fr] min-h-screen w-full">

            {{-- Left: brand / welcome panel (hidden on mobile) --}}
            <div class="mn-hero hidden md:flex flex-col justify-between p-12">
                <div class="flex items-center gap-3">
                    <span class="mn-mark"></span>
                    <span class="text-xl font-semibold" style="font-family:'Bricolage Grotesque',sans-serif;letter-spacing:-0.02em;">{{ $sitename }}</span>
                </div>

                <div class="max-w-[420px]">
                    <span class="mn-eyebrow">Secure access</span>
                    <h1 class="mn-hero-title mt-5">{{ $txt }}</h1>
                    <p class="mt-4 text-[15px]" style="color:rgba(246,243,235,0.72);">
                        Airtime, data, electricity, cable TV and exam pins — delivered at the
                        <span class="mn-serif" style="color:var(--mn-gold);font-size:1.1em;">speed of light.</span>
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="mn-check"><i class="ri-flashlight-line text-lg"></i> 8-second delivery, every time</div>
                    <div class="mn-check"><i class="ri-shield-check-line text-lg"></i> Bank-grade, PCI-DSS secure</div>
                    <div class="mn-check"><i class="ri-customer-service-2-line text-lg"></i> 24/7 human support on WhatsApp</div>
                </div>
            </div>

            {{-- Right: form panel --}}
            <div class="w-full flex items-center justify-center p-5 md:p-10">
                <div class="w-full md:max-w-[460px]">

                    {{-- Mobile brand (left panel is hidden) --}}
                    <div class="flex items-center gap-3 mb-8 md:hidden">
                        <span class="mn-mark"></span>
                        <span class="text-lg font-semibold" style="font-family:'Bricolage Grotesque',sans-serif;letter-spacing:-0.02em;">{{ $sitename }}</span>
                    </div>

                    <div class="mn-card p-6 md:p-9">
                        <h2 class="mn-form-title">{{ $txt2 }}</h2>
                        @if(!empty($txt3))
                            <p class="mt-3 text-[15px]" style="color:var(--mn-muted);">{{ $txt3 }}</p>
                        @endif
                        <div class="mt-2">
                            {{ $slot }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <a href="{{ $wa }}"
           class="fixed bottom-6 right-6 text-white p-2 rounded-full shadow-xl transition h-[50px] w-[50px] flex justify-center items-center animate-bounce"
           style="background: var(--mn-emerald, #0d4f3c);" aria-label="Chat with us on WhatsApp">
            <i class="ri-whatsapp-line text-3xl"></i>
        </a>
        <div class="whatsapp-tooltip hidden">
            Need help? Chat with us on WhatsApp
        </div>

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
