<section class="py-16 relative" id="faqs">
    <section class="container relative z-10 space-y-10">
		<header class="space-y-3 md:w-3/4">
				<h2 class="section-title">Have a question?</h2>
				<p>Have a question about our platform or services? We've got you covered. Browse our FAQs to find the information you need, or get in touch with us for personalized support.</p>
		</header>


		
            <section class="grid md:grid-cols-4 gap-10" x-data="{ faqType : 'General' }">
                    <div class="overflow-x-auto text-sm md:text-base flex space-x-2 md:space-x-0 flex-row md:flex-col justify-start items-start md:space-y-7">
                        <button x-on:click="faqType = 'General'" :class="faqType == 'General' ? 'bg-primary text-white' : 'bg-light'" class="rounded-lg py-3 px-4 w-full">
                            <span>General</span>
                        </button>


                        <button x-on:click="faqType = 'Transactions'" :class="faqType == 'Transactions' ? 'bg-primary text-white' : 'bg-light'" class="rounded-lg py-3 px-4 w-full">
                            <span>Transactions</span>
                        </button>

						<button x-on:click="faqType = 'Payments'" :class="faqType == 'Payments' ? 'bg-primary text-white' : 'bg-light'" class="rounded-lg py-3 px-4 w-full">
                            <span>Payments</span>
                        </button>

						<button x-on:click="faqType = 'Returns'" :class="faqType == 'Returns' ? 'bg-primary text-white' : 'bg-light'" class="rounded-lg py-3 px-4 w-full">
                            <span>Returns</span>
                        </button>

						<button x-on:click="faqType = 'Careeers'" :class="faqType == 'Careeers' ? 'bg-primary text-white' : 'bg-light'" class="rounded-lg py-3 px-4 w-full">
                            <span>Careeers</span>
                        </button>
						
                    </div>
            <section class="space-y-5 md:col-span-3">
                
               <section x-show="faqType == 'General">

                  <div class="space-y-5 border-b pb-5 md:pb-7" x-data="{ show : true}" x-on:click.away="show = false">
    <header class="flex items-center space-x-5 justify-between cursor-pointer">
      <h2 x-on:click="show = !show" class="font-semibold">How do I get started with {{ jannah_opt('site_name') }}?</h2>
      <button x-on:click="show = !show"></button>
    </header>
    <p x-show="show" x-transition:enter.duration.1000ms="" x-transition:leave.duration.1000ms="" class="text-sm opacity-90">
      To get started with {{ jannah_opt('site_name') }}, simply download the app or via the website, create an account, get your unique bank account number, and start Paying your favorite Bills.</p>
  </div>
  <div class="space-y-5 border-b py-5 md:py-7" x-data="{ show : false}" x-on:click.away="show = false">
    <header class="flex items-center space-x-5 justify-between cursor-pointer">
      <h2 x-on:click="show = !show" class="font-semibold">How secure is {{ jannah_opt('site_name') }}?</h2>
      <button x-on:click="show = !show"></button>
    </header>
    <p x-show="show" x-transition:enter.duration.1000ms="" x-transition:leave.duration.1000ms="" class="text-sm opacity-90">
      {{ jannah_opt('site_name') }} Service is really fast our app is powered by NIBSS open banking API which makes our service fast and easy.    </p>
  </div>
  <div class="space-y-5 border-b py-5 md:py-7" x-data="{ show : false}" x-on:click.away="show = false">
    <header class="flex items-center space-x-5 justify-between cursor-pointer">
      <h2 x-on:click="show = !show" class="font-semibold">What types of Bills can I Pay on {{ jannah_opt('site_name') }}?</h2>
      <button x-on:click="show = !show"></button>
    </header>
    <p x-show="show" x-transition:enter.duration.1000ms="" x-transition:leave.duration.1000ms="" class="text-sm opacity-90">
      You can pay bills such as Data, Airtime, Cable TV, Electricity & more.    </p>
  </div>
  <div class="space-y-5 border-b py-5 md:py-7" x-data="{ show : false}" x-on:click.away="show = false">
    <header class="flex items-center space-x-5 justify-between cursor-pointer">
      <h2 x-on:click="show = !show" class="font-semibold">How long does it take to process a transaction on {{ jannah_opt('site_name') }}?</h2>
      <button x-on:click="show = !show"></button>
    </header>
    <p x-show="show" x-transition:enter.duration.1000ms="" x-transition:leave.duration.1000ms="" class="text-sm opacity-90">
      {{ jannah_opt('site_name') }} Payments are processed instantly we have a 24/7 active service.</p>
  </div>
  <div class="space-y-5 border-b py-5 md:py-7" x-data="{ show : false}" x-on:click.away="show = false">
    <header class="flex items-center space-x-5 justify-between cursor-pointer">
      <h2 x-on:click="show = !show" class="font-semibold">What if I have issues with my {{ jannah_opt('site_name') }} account or transactions?</h2>
      <button x-on:click="show = !show"></button>
    </header>
    <p x-show="show" x-transition:enter.duration.1000ms="" x-transition:leave.duration.1000ms="" class="text-sm opacity-90">
      If you face any issues with transacting with {{ jannah_opt('site_name') }} we have a LiveChat Team who will attend to your case or you can email us via {{ jannah_opt('support_email') }}.    </p>
  </div>
               </section>
            </section>
		</section>

	</section>
</section>