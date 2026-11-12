<head>
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Experience seamless and affordable bill payments with @jannah_opt('site_name'). Effortlessly buy airtime, data, subscribe to your favorite TV channels, and more—all in one place with ease and convenience.">
    <meta name="keywords" content="VTU services, airtime purchase, data bundles, TV subscription, fintech app, seamless payments, bill payments, affordable airtime solutions, mobile recharge platform, digital wallet integration, instant transactions, pay utility bills online, recharge platform for mobile devices, fintech solutions for everyday payments, mobile payment services, online utility payments, easy data recharge, quick airtime purchase, pay bills on the go, digital payment solutions, hassle-free bill payments, secure online transactions, fast mobile top-up, automated bill payments, all-in-one payment app, mobile wallet for bills, cheap data app, buy data earn reward, vtu, reward data app, data reseller app, @jannah_opt('site_name'), bill payments, utility payments, mobile top-up, fast recharge, affordable fintech solutions, airtime recharge, pay bills fast, TV subscriptions, digital recharge solutions, effortless mobile payments, convenient bill payments, fintech for mobile">
    <meta name="author" content="FrontalData">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta charset="UTF-8">
    <meta name="theme-color" content="#0066FF">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="FrontalData">
    <meta name="application-name" content="FrontalData">

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <meta property="og:title" content="FrontalData | Superfast Automated Data, Airtime & Utility Bills">
    <meta property="og:description" content="FrontalData offers affordable and seamless bills payment, cheap SME data bundles, airtime top-up, and instant cable subscriptions.">
    <meta property="og:image" content="{{ asset('images/hero_showcase.jpg') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="FrontalData">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="FrontalData | Superfast Automated Data & Airtime Topup">
    <meta name="twitter:description" content="Pay bills, buy wholesale SME data, and top up airtime instantly with FrontalData.">
    <meta name="twitter:image" content="{{ asset('images/hero_showcase.jpg') }}">
    <link rel="canonical" href="{{ url('/') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#0066FF">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FrontalData - Superfast Data & Bills Payment Platform</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
	<link rel="stylesheet" href="{{ asset('css/swipper.css') }}">
	<link rel="stylesheet" href="{{ asset('css/guest.css') }}">
	<link rel="shortcut icon" href="{{ asset('images/smfavicon.png') }}" type="image/png">
	<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
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
  <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').then(function(reg) {
                console.log('FrontalData PWA ServiceWorker active');
            }).catch(function(err) {
                console.warn('FrontalData PWA ServiceWorker registration failed:', err);
            });
        });
    }
  </script>
    
    {{ $slot }}
</head>