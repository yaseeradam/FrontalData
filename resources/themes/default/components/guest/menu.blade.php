<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm" x-data="{ show : false }">
	<nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
		<a href="{{ url('/') }}" class="flex items-center gap-3">
			<img src="{{ asset('images/logo.png') }}" alt="{{ jannah_opt('site_name') }}" class="h-10 md:h-12 w-auto object-contain">
		</a>

		<div class="hidden lg:flex items-center gap-8">
			<a class="text-sm font-semibold text-slate-700 hover:text-blue-600 transition-colors" href="{{ url('/') }}">Home</a>
			<a class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors" href="#why">Why FrontalData</a>
			<a class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors" href="#services">Services</a>
			<a class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors" href="#faqs">FAQs</a>
			<a class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors" href="#contact">Contact</a>
		</div>

		<div class="hidden lg:flex items-center gap-3">
			<a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 px-5 py-2.5 rounded-full border border-slate-200 hover:border-blue-600 hover:text-blue-600 transition-all">
				Sign In
			</a>
			<a href="{{ route('register') }}" class="text-sm font-semibold text-white px-6 py-2.5 rounded-full bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 shadow-md shadow-blue-500/20 hover:shadow-cyan-500/30 hover:scale-105 transition-all">
				Create Free Account
			</a>
		</div>

		<button class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition-colors" x-on:click="show = !show" aria-label="Toggle navigation">
			<i class="ri-menu-3-line text-2xl" :class="show ? 'ri-close-line' : 'ri-menu-3-line'"></i>
		</button>
	</nav>

	<!-- Mobile Menu Drawer -->
	<div x-cloak x-show="show" x-transition.origin.top class="lg:hidden bg-white/95 backdrop-blur-lg border-b border-slate-100 px-5 py-6 space-y-4 shadow-xl">
		<a class="block text-base font-semibold text-slate-800 hover:text-blue-600" href="{{ url('/') }}" @click="show = false">Home</a>
		<a class="block text-base font-semibold text-slate-600 hover:text-blue-600" href="#why" @click="show = false">Why FrontalData</a>
		<a class="block text-base font-semibold text-slate-600 hover:text-blue-600" href="#services" @click="show = false">Services</a>
		<a class="block text-base font-semibold text-slate-600 hover:text-blue-600" href="#faqs" @click="show = false">FAQs</a>
		<a class="block text-base font-semibold text-slate-600 hover:text-blue-600" href="#contact" @click="show = false">Contact</a>
		<div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
			<a href="{{ route('login') }}" class="w-full text-center text-sm font-semibold text-slate-800 py-3 rounded-full border border-slate-200">
				Sign In
			</a>
			<a href="{{ route('register') }}" class="w-full text-center text-sm font-semibold text-white py-3 rounded-full bg-gradient-to-r from-blue-600 to-cyan-500 shadow-md shadow-blue-500/20">
				Create Free Account
			</a>
		</div>
	</div>
</header>