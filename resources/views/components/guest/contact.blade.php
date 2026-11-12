<section class="py-16 relative" id="contact">
	<img src="{{ asset('img/cone.svg') }}" class="absolute -right-72 md:right-0 top-0 z-0" alt="" id="my-svg">
	<img src="{{ asset('img/half-moon.svg') }}" class="absolute bottom-0 left-0 z-0" alt="" id="my-svg">

    <section class="container grid md:grid-cols-2 gap-10 relative z-10">
		<section class="space-y-16">
			<header class="space-y-1">
				<h2 class="section-title">Get in touch</h2>
				<p>Have an enquiry? Fill out the form to contact our team</p>
			</header>


			<ul class="space-y-3">
				<li>
					<a href="tel:{{ jannah_opt('support_phone') }}" class=" flex items-center" space-x-3"="">
						<img src="images/call.svg" alt="">
						<span>{{ jannah_opt('support_phone') }}</span>
					</a>
				</li>
				<li>
					<a href="mailto:{{ jannah_opt('support_email') }}" class="flex items-center space-x-3">
						<img src="images/direct-inbox.svg" alt="">
						<span>{{ jannah_opt('support_email') }}</span>
					</a>
				</li>
					<li>
					<a href="" class=" flex="" items-center="" space-x-3"="">
						<br>
						<span class="font-bold">Address:</span><br>
						<span>{{ jannah_opt('adrress') }}</span>
					</a>
				</li>
			</ul>

			<div class="space-y-1">
				<h3>Connect with us on our social media accounts</h3>
				<ul class="flex items-center space-x-3 py-3">
					<li>
						<a href="">
							<img src="{{ asset('img/instagram.png') }}" class="w-[40px]" alt="" id="my-svg">
						</a>
					</li>

					<li>
						<a href="#">
							<img src="{{ asset('img/dribble.png') }}" class="w-[40px]" alt="" id="my-svg"> 
						</a>
					</li>

					<li>
						<a href="">
							<img src="{{ asset('img/twitter.png') }}" class="w-[40px]" alt="" id="my-svg">
						</a>
					</li>

					<li>
						<a href="">
							<img src="{{ asset('img/youtube.png') }}" class="w-[40px]" alt="" id="my-svg">
						</a>
					</li>
				</ul>
			</div>
		</section>

		<section class="bg-light p-7 rounded-2xl border">
			<form action="#" class="grid gap-3">
				<div class="form-group">
					<label>Full Name</label>
					<input type="text" class="form-control" placeholder="Enter your full name">
				</div>

				<div class="form-group">
					<label>Email Address</label>
					<input type="text" class="form-control" placeholder="Enter your email address">
				</div>

				<div class="form-group">
					<label>Phone Number</label>
					<input type="text" class="form-control" placeholder="Enter your phone number">
				</div>

				<div class="form-group">
					<label>Message</label>
					<textarea rows="5" class="form-control" placeholder="Enter your message here..."></textarea>
				</div>

				<div class="form-group">
					<button type="submit" class="rounded-xl btn btn-lg  btn-primary text-white w-full">Send Message</button>
				</div>
			</form>
		</section>
	</section>
</section>




