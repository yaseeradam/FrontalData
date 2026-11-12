<footer class="relative z-20 py-5 space-y-5 md:space-y-16">
		<div class="grid md:grid-cols-4 gap-10 container">
			<section class="space-y-3 md:col-span-3 flex flex-col items-center md:items-start md:justify-end">
					<div class="text-primary text-4xl font-bold"> {{ jannah_opt('site_name') }}</div>
					
						<ul class="flex flex-wrap space-x-5 text-sm md:text-base">
							<li><a href="#">About Us</a></li>

							<li><a href="#">Features</a></li>

							<li><a href="#">Faqs</a></li>

							<li><a href="#">Help</a></li>

							<li><a href="#">Privacy Policy</a></li>
						</ul>
			</section>

			<section class="space-y-3 flex flex-col items-center md:items-start md:justify-end ">
				<h2>Get the App</h2>
				<div class="flex items-center justify-center md:justify-start space-x-3">
					<a href="">
						<img src="{{ asset('img/google-store.png') }}" alt="" class="h-[50px] w-auto" id="my-svg">
					</a>
					<a href="#">
						<img src="{{ asset('img/apple-store.png') }}" alt="" class="h-[50px] w-auto" id="my-svg">
					</a>
				</div>
			</section>
		</div>
	<section class="container flex flex-col md:flex-row text-center md:text-left justify-between items-center text-sm">
		<p class="opacity-40">© 2024 {{ jannah_opt('site_name') }}. All rights reserved</p>
		<ul class="flex items-center justify-center space-x-3 py-3">
                <li>
					<a href="">
						<img src="{{ asset('img/instagram.png') }}" class="w-[30px]" alt="" id="my-svg">
					</a>
				</li>

                <li>
					<a href="#">
						<img src="{{ asset('img/dribble.png') }}" class="w-[30px]" alt="" id="my-svg"> 
					</a>
				</li>

                <li>
					<a href="">
						<img src="{{ asset('img/twitter.png') }}" class="w-[30px]" alt="" id="my-svg">
					</a>
				</li>

                <li>
					<a href="">
						<img src="{{ asset('img/youtube.png') }}" class="w-[30px]" alt="" id="my-svg">
					</a>
				</li>
            </ul>
	</section>
</footer>