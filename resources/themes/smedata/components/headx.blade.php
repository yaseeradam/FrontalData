@php
    $path = Request::path();
    $slugSegments = explode('/', $path);
    $pageTitle = implode(' ', array_map('ucfirst', $slugSegments));
@endphp

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Experience seamless and affordable bill payments with @jannah_opt('site_name'). Effortlessly buy airtime, data, subscribe to your favorite TV channels, and more—all in one place with ease and convenience.">
    <meta name="keywords" content="VTU services, airtime purchase, data bundles, TV subscription, fintech app, seamless payments, bill payments, affordable airtime solutions, mobile recharge platform, digital wallet integration, instant transactions, pay utility bills online, recharge platform for mobile devices, fintech solutions for everyday payments, mobile payment services, online utility payments, easy data recharge, quick airtime purchase, pay bills on the go, digital payment solutions, hassle-free bill payments, secure online transactions, fast mobile top-up, automated bill payments, all-in-one payment app, mobile wallet for bills, cheap data app, buy data earn reward, vtu, reward data app, data reseller app, @jannah_opt('site_name'), bill payments, utility payments, mobile top-up, fast recharge, affordable fintech solutions, airtime recharge, pay bills fast, TV subscriptions, digital recharge solutions, effortless mobile payments, convenient bill payments, fintech for mobile">
    <meta name="author" content="uiking.co">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta property="og:title" content="@jannah_opt('site_name') | Seamless Bill Payments & Airtime Topup">
    <meta property="og:description" content="Jannah VTU offers affordable and seamless bill payments. Buy airtime, data, and subscribe to your favorite TV channels with ease.">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="@jannah_opt('site_name')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@jannah_opt('site_name') | Seamless Bill Payments & Airtime Topup">
    <meta name="twitter:description" content="Pay bills, buy airtime, and subscribe to TV channels effortlessly with @jannah_opt('site_name').">
    <meta name="twitter:image" content="{{ asset('images/twitter-card.jpg') }}">
    <meta name="twitter:site" content="@uikingco">
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

     <title>@jannah_opt('site_name') - {{ $pageTitle }}</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet"> 
    <link href="{{ asset('config.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"> 
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">
    <style>
    
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap');
        
        html, div, body {
            font-family: 'Public Sans', sans-serif;
        }
        .bg-gradient {
             background: linear-gradient(to bottom, @jannah_opt('primary_color'), white);
         }
        .bg-primary {
            background: @jannah_opt('primary_color') !important;
        }
       .bg-primary-100 {
          background: fade(@jannah_opt('primary_color'), 50%) !important;
        }

        .bg-alt {
            background: @jannah_opt('secondary_color') !important;
        }
        .text-primary {
            color: @jannah_opt('primary_color') !important;
        }
        .text-alt {
            color: @jannah_opt('secondary_color') !important;
        } 
        .text-2xs {
            font-size: 11px !important; 
        }
		.z-1 {
		       z-index: -1 !important;
		}
		.whatsapp-tooltip {
            position: absolute;
            bottom: 50px;
            right: 70px;
            background: white;
            color: black;
            padding: 8px 12px;
            margin: 0px 2px 0px 30px; 
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.2s, transform 0.2s;
        }
        .whatsapp-button:hover + .whatsapp-tooltip {
            opacity: 1;
            transform: translateY(0);
        }
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple::after {
            content: "";
            position: absolute;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transform: scale(0);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.4s ease-out, opacity 0.4s ease-out;
        }
        .ripple:active::after {
            transform: scale(8);
            opacity: 1; 
            transition: transform 0.4s ease-out, opacity 0.2s ease-out;
        }

    </style> 
     <style>
        
        #input-field:-webkit-autofill ~ label {
            top: 0;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: @jannah_opt('primary_color');
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('input-field');
            const checkValue = () => {
                input.classList.toggle('not-empty', input.value.length > 0);
            };
            
            checkValue();
            input.addEventListener('input', checkValue);
            setTimeout(checkValue, 100);
        });
    </script>
    @if (@jannah_opt('preloader_status') == 'enabled')
    <script>
   document.addEventListener('DOMContentLoaded', function() {
    var ld = document.querySelector('.ld');
    var blurOverlay = document.querySelector('.blur-overlay');

    function showLoader() {
        blurOverlay.style.visibility = 'visible';
        ld.style.visibility = 'visible';
        
    }

    function hideLoader() {
        setTimeout(function() {
            ld.style.visibility = 'hidden';
            blurOverlay.style.visibility = 'hidden';
        }, 1000);
    }

    function handleLoader() {
        document.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                showLoader();
            });
        });
        showLoader();
    }

    window.addEventListener('load', hideLoader);
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoader();
        } else {
            showLoader();
        }
    });

    handleLoader();
});
       
    </script>
    
    @endif
    <script src="{{ asset('app.js') }}"></script>
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('composer.js') }}?v={{ time() }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    {{ $slot }}
</head>