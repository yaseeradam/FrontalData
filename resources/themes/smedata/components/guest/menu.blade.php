<section class="bg-white py-3 z-50" x-data="{ show : false }">
	<nav class="px-5 flex items-center justify-between main-nav relative z-50 bg-white">
		<div class="text-primary text-4xl font-bold"> {{ jannah_opt('site_name') }}</div>

		<section>
			<ul x-cloak="" class="nav-bar-lg" :class="show ? 'flex' : 'hidden lg:flex'" x-transition="">
				<li>
					<a class="nav-link active" href="#">Home</a>
				</li>
				<li>
					<a class="nav-link" href="#why">Why {{ jannah_opt('site_name') }}</a>
				</li>
				<li>
					<a class="nav-link" href="#faqs">Faqs</a>
				</li>
				<li>
					<a class="nav-link" href="#contact">Contact Us</a>
				</li>

				<li class="lg:hidden flex items-center justity-center space-x-3">
						<a href="{{ route('register') }}">
         <div class=" w-auto border font-bold text-sm rounded-full border-gray-300 p-4 text-primary"> 
        Register
          </div>
         </a> 
        <a href="{{ route('login') }}">
         <div class=" w-auto border font-bold text-sm rounded-full border-gray-300 p-4 text-primary"> 
      Login Now
          </div>
         </a>
				</li>

			</ul>
		</section>

		<section>
			<ul x-cloak="" class="hidden lg:block">
				<li class="flex items-center justity-start space-x-3">
				<a href="{{ route('register') }}">
         <div class=" w-auto border font-bold text-sm rounded-full border-gray-300 p-4 text-primary"> 
        Register
          </div>
         </a>
        <a href="{{ route('login') }}">
         <div class=" w-auto border font-bold text-sm rounded-full border-gray-300 p-4 text-primary"> 
      Login Now
          </div>
         </a>
				</li>
			</ul>

			<button class="lg:hidden text-primary text-2xl" x-on:click="show = !show">
				<i :class="show ? 'las la-times' : 'las la-bars'"></i>
			</button>
		</section>
	</nav>
</section>