<head>
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Experience seamless and affordable bill payments with @jannah_opt('site_name'). Effortlessly buy airtime, data, subscribe to your favorite TV channels, and more—all in one place with ease and convenience.">
    <meta name="keywords" content="VTU services, airtime purchase, data bundles, TV subscription, fintech app, seamless payments, bill payments, affordable airtime solutions, mobile recharge platform, digital wallet integration, instant transactions, pay utility bills online, recharge platform for mobile devices, fintech solutions for everyday payments, mobile payment services, online utility payments, easy data recharge, quick airtime purchase, pay bills on the go, digital payment solutions, hassle-free bill payments, secure online transactions, fast mobile top-up, automated bill payments, all-in-one payment app, mobile wallet for bills, cheap data app, buy data earn reward, vtu, reward data app, data reseller app, @jannah_opt('site_name'), bill payments, utility payments, mobile top-up, fast recharge, affordable fintech solutions, airtime recharge, pay bills fast, TV subscriptions, digital recharge solutions, effortless mobile payments, convenient bill payments, fintech for mobile">
    <meta name="author" content="Jannah.org">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta property="og:title" content="@jannah_opt('site_name') | Seamless Bill Payments & Airtime Topup">
    <meta property="og:description" content="@jannah_opt('site_name') offers affordable and seamless bill payments. Buy airtime, data, and subscribe to your favorite TV channels with ease.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="@jannah_opt('site_name')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@jannah_opt('site_name') | Seamless Bill Payments & Airtime Topup">
    <meta name="twitter:description" content="Pay bills, buy airtime, and subscribe to TV channels effortlessly with @jannah_opt('site_name').">
    <meta name="twitter:image" content="{{ asset('images/twitter-card.jpg') }}">
    <meta name="twitter:site" content="@jannahvtu">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "@jannah_opt('site_name')",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/') }}/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <link rel="canonical" href="{{ url('/') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="@jannah_opt('primary_color')">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Jannah VTU') }} - </title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
	<link rel="stylesheet" href="{{ asset('css/swipper.css') }}">
	<link rel="stylesheet" href="{{ asset('css/guest.css') }}">
	<script src="{{ asset('app.js') }}"></script>
	<script src="{{ asset('js/guest.js') }}"></script>
	<style type="text/tailwindcss">

	    @layer utilities {
	      .container {
	        @apply sm:w-[567px] md:w-[768px] lg:w-[1200px] mx-auto px-5;
	      }

	      html, body {
	      	@apply scroll-smooth;
	      }

	      .container-fluid {
	        @apply px-5 md:px-16;
	      }

			.btn {
				@apply text-white whitespace-nowrap;
			}

			.btn-xl {
				@apply py-4 px-8 text-sm;
			}

			.btn-lg {
				@apply py-3 px-5 text-sm;
			}

			.btn-sm {
				@apply py-1 text-sm px-5;
			}

			.btn-xs {
				@apply py-1 text-xs px-2;
			}

			.btn-primary {
				@apply bg-primary text-white hover:bg-opacity-70;
			}

			.form-group {
				@apply relative space-y-2;
			}

			.form-group label {
				@apply text-xs;
			}

			.form-control {
				@apply w-full z-10 rounded placeholder-gray-400 py-3 px-3 text-dark bg-white transition text-xs focus:outline-0;
			}

	      .logo {
	      	@apply w-[70px] h-auto;
	      }

	      .main-nav .nav-bar-lg {
	      	@apply w-full absolute z-50 top-[50px] left-0 min-h-[80vh] md:min-h-0 bg-white lg:static flex-col lg:flex-row justify-center lg:justify-start items-center shadow-xl lg:shadow-none space-x-2 space-y-3 md:space-y-0 transition;
	      }

	      ul.nav-bar-lg a.nav-link {
	      	@apply block px-7 py-4 text-xs rounded-lg hover:bg-primary hover:text-white transition;
	      }

	      ul.nav-bar-lg a.active {
	      	@apply bg-primary text-white;
	      }

		  .latest-update { 
    @apply px-3 bg-emerald-500 text-xs md:text-sm text-white flex flex-wrap text-center items-center justify-center py-3; 
} 

		  .section-title {
			@apply text-2xl md:text-4xl font-medium;
		  }

		  .title {
			@apply text-3xl md:text-5xl font-medium;
		  }

		  .short-caption {
			@apply text-primary bg-primary bg-opacity-10 rounded-lg py-2 px-5 inline-block text-sm space-x-2;
		  }
	    }
  </style>

	<script>
	    tailwind.config = {
	      theme: {
	        extend: {
	          colors: {
	            dark : '#2C2E2F',
				primary : '{{ jannah_opt('primary_color') }}',
	            light : '#fafafa',
	          }
	        }
	      }
	    }
  </script>
  <script>
    var swiper = new Swiper(".mySwiper", {
	  slidesPerView: 3,
	  spaceBetween: 30,
	  loop: true,
	  autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: ".testimonial-swiper-button-next",
        prevEl: ".testimonial-swiper-button-prev",
      },
	  breakpoints: {
		0: {
          slidesPerView: 1,
          spaceBetween: 30,
        },
        640: {
          slidesPerView: 1,
          spaceBetween: 30,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 30,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },
    });
  </script>
    
    {{ $slot }}
</head>