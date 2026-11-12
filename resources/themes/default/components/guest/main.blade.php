<section class="relative pt-12 pb-20 md:py-24 overflow-hidden bg-gradient-to-b from-blue-50/50 via-white to-slate-50/50">
	<!-- Background glow accents -->
	<div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-tr from-cyan-400/10 via-blue-500/10 to-indigo-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
	
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
		<div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">

			<!-- Left Column: Copy & CTAs -->
			<div class="space-y-6 text-center lg:text-left">
				<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-200/60 text-blue-700 text-xs font-semibold tracking-wide shadow-sm">
					<span class="flex h-2 w-2 rounded-full bg-cyan-400 animate-ping"></span>
					<span>⚡ Instant Automated VTU & Telecom Infrastructure</span>
				</div>

				<h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight" style="line-height: 1.15;">
					Superfast <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500">Data, Airtime</span> & Bills at Wholesale Rates.
				</h1>

				<p class="text-base sm:text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed">
					Experience lightning-fast SME data bundles, automated airtime top-up, electricity tokens, and cable subscriptions with 99.9% automated instant delivery and dedicated virtual bank accounts.
				</p>	
				
				<div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
					<a href="{{ route('register') }}" class="w-full sm:w-auto text-center px-8 py-4 rounded-full font-bold text-sm text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 shadow-lg shadow-blue-500/25 hover:shadow-cyan-500/40 hover:scale-105 transition-all">
						Get Started in 30 Seconds
					</a>
					<a href="{{ route('login') }}" class="w-full sm:w-auto text-center px-8 py-4 rounded-full font-bold text-sm text-slate-700 bg-white border border-slate-200 hover:border-blue-600 hover:text-blue-600 shadow-sm hover:scale-105 transition-all">
						Sign In to Portal
					</a>
				</div>

				<!-- Trust metrics badges -->
				<div class="pt-6 border-t border-slate-200/70 grid grid-cols-3 gap-4 text-center lg:text-left">
					<div>
						<div class="text-xl sm:text-2xl font-black text-slate-900">⚡ &lt;5s</div>
						<div class="text-xs text-slate-500 font-medium">Instant Delivery</div>
					</div>
					<div>
						<div class="text-xl sm:text-2xl font-black text-slate-900">99.9%</div>
						<div class="text-xs text-slate-500 font-medium">API Uptime</div>
					</div>
					<div>
						<div class="text-xl sm:text-2xl font-black text-slate-900">0% Risk</div>
						<div class="text-xs text-slate-500 font-medium">Bank-Grade Secure</div>
					</div>
				</div>
			</div>

			<!-- Right Column: 3D App Showcase Mockup -->
			<div class="relative flex justify-center lg:justify-end">
				<div class="relative w-full max-w-lg">
					<div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 opacity-30 blur-2xl"></div>
					<img src="{{ asset('images/hero_showcase.jpg') }}" alt="FrontalData App Showcase" class="relative rounded-3xl shadow-2xl border border-white/40 object-cover w-full hover:scale-105 transition-transform duration-700">
				</div>
			</div>

		</div>
	</div>
</section>