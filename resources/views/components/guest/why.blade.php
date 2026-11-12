<section class="py-5 relative" id="why">
	<img src="{{ asset('img/left-design.svg') }}" class="absolute -left-40 md:left-0 top-[100px] z-0" alt="" id="my-svg">
	<img src="{{ asset('img/right-design.svg') }}" class="absolute -right-40 md:right-0 top-[100px] z-0" alt="" id="my-svg">

    <section class="container space-y-10 relative z-10">
		<header class="mx-auto lg:w-3/5 text-center space-y-3">
			<h2 class="section-title"> Why Choose {{ jannah_opt('site_name') }}?</h2>
			<p>Choose {{ jannah_opt('site_name') }} for a seamless Bills Payment experience with detailed and simplified transactions. We are committed to your maximum satisfaction.</p>
		</header>

        <section class="grid md:grid-cols-2 mx-auto lg:w-3/4 gap-5 md:gap-10">
			  <section class="rounded-2xl space-y-3 p-5 md:p-7 text-center bg-primary text-white">
    <h2 class="font-medium text-md">Airtime top up</h2>
    <p class="text-sm">Effortlessly top up your phone with our user-friendly platform and experience seamless transactions like never before with {{ jannah_opt('site_name') }}.</p>
  </section>
    <section class="rounded-2xl space-y-3 p-5 md:p-7 text-center bg-light hover:bg-primary hover:text-white">
    <h2 class="font-medium text-md">Pay bills</h2>
    <p class="text-sm">Say goodbye to the hassle of paying bills. We offer a stress-free solution to pay your bills, making the process quick and easy.</p>
  </section>
    <section class="rounded-2xl space-y-3 p-5 md:p-7 text-center bg-light hover:bg-primary hover:text-white">
    <h2 class="font-medium text-md">Detailed and simplified trading experience</h2>
    <p class="text-sm">Experience trading like never before with our simplified and detailed platform. Our user-friendly interface provides a seamless trading experience with ease.</p>
  </section>
    <section class="rounded-2xl space-y-3 p-5 md:p-7 text-center bg-light hover:bg-primary hover:text-white">
    <h2 class="font-medium text-md">Maximum satisfaction in all your transactions</h2>
    <p class="text-sm">Enjoy a worry-free and secure trading experience with maximum satisfaction guaranteed in all your transactions.</p>
  </section>
  		</section>


		<section class="grid md:grid-cols-2">

			<section class="order-2 md:order-1 flex items-center md:items-start justify-center flex-col space-y-5">
				<h1 class="text-3xl md:text-4xl lg:text-6xl font-medium" style="line-height: 1.1;">Download <span class="text-primary">{{ jannah_opt('site_name') }}</span> Now!</h1>
				<div class="flex items-center justify-center md:justify-start space-x-3">
					<a href="{{ jannah_opt('app_url') }}">
						<img src="{{ asset('img/google-store.png') }}" alt="" class="h-[50px] w-auto" id="my-svg">
					</a>
					<a href="#">
						<img src="{{ asset('img/apple-store.png') }}" alt="" class="h-[50px] w-auto" id="my-svg">
					</a>
				</div>
			</section>

			<section class="order-1 md:order-2">
				<img src="{{ asset('img/dpage.png') }}" alt="" id="my-svgj" class="scale-70">
			</section>
		</section>
    </section>
</section>
