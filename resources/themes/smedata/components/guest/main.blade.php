<section class="relative py-10 ">
	<div class="container relative z-20">
		<section class="grid md:grid-cols-2 gap-10 md:gap-7">

			<section class="space-y-5 text-center md:text-left">

				<div class="short-caption">
					<i class="las la-arrow-right"></i>
					<span>Elevating Bills Payment Experience</span>
				</div>
				<h1 class="text-3xl md:text-4xl lg:text-6xl font-medium" style="line-height: 1.2;">Pay Bills.</h1>
				<p>Choose {{ jannah_opt('site_name') }} for a seamless Bills Payment experience with detailed and simplified transactions. We are committed to your maximum satisfaction.</p>	
				
				<div class="flex items-center justify-center md:justify-start space-x-3">
					<a href="{{ route('register') }}">
         <div class=" w-auto border font-bold text-sm rounded-full border-gray-300 p-4"
         > 
        Register
          </div>
         </a>
        <a href="{{ route('login') }}">
         <div class=" w-auto border font-bold text-sm rounded-full border-gray-300 p-4"> 
      Login Now
          </div>
         </a>
				</div>
			

			</section>

			<section class="relative">
				<img src="{{ asset('img/app.png') }}" id"my-svg" alt="">
			</section>


		</section>
	</div>
	<img src="{{ asset('img/half-moon.svg') }}" alt="" id="my-svg" class="absolute top-0 -left-5">
</section>