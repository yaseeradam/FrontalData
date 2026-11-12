<footer class="bg-white border-t border-slate-100 py-12 relative z-20">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
		<div class="grid md:grid-cols-4 gap-8">
			<div class="space-y-4 md:col-span-2">
				<a href="{{ url('/') }}">
					<img src="{{ asset('images/logo.png') }}" alt="{{ jannah_opt('site_name') }}" class="h-10 w-auto object-contain">
				</a>
				<p class="text-sm text-slate-500 max-w-sm leading-relaxed">
					FrontalData is your premier automated telecom and utility payment platform. Instant data, cheap airtime, electricity tokens, and cable renewals 24/7.
				</p>
			</div>

			<div class="space-y-3">
				<h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Quick Links</h4>
				<ul class="space-y-2 text-sm text-slate-600">
					<li><a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a></li>
					<li><a href="#why" class="hover:text-blue-600 transition-colors">Why FrontalData</a></li>
					<li><a href="#services" class="hover:text-blue-600 transition-colors">Services</a></li>
					<li><a href="#faqs" class="hover:text-blue-600 transition-colors">Frequently Asked Questions</a></li>
				</ul>
			</div>

			<div class="space-y-3">
				<h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Access</h4>
				<ul class="space-y-2 text-sm text-slate-600">
					<li><a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors">Customer Portal Login</a></li>
					<li><a href="{{ route('register') }}" class="hover:text-blue-600 transition-colors">Create Free Account</a></li>
					<li><a href="{{ route('admin.login') }}" class="hover:text-blue-600 transition-colors">Admin Workspace</a></li>
				</ul>
			</div>
		</div>

		<div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
			<p>© 2026 FrontalData. All rights reserved. Encrypted & Automated Telecom Infrastructure.</p>
			<div class="flex items-center gap-4 text-slate-400">
				<a href="#" class="hover:text-blue-600"><i class="ri-shield-check-line text-lg"></i> SSL Secured</a>
				<a href="#" class="hover:text-blue-600"><i class="ri-flashlight-line text-lg"></i> Instant API</a>
			</div>
		</div>
	</div>
</footer>