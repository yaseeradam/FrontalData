@php
    $path = Request::path();
    $slugSegments = explode('/', $path);
    $pageTitle = implode(' ', array_map('ucfirst', $slugSegments));
@endphp
@props(['hasTheme' => false, 'theme' => false])
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Experience seamless and affordable bill payments with @jannah_opt('site_name'). Effortlessly buy airtime, data, subscribe to your favorite TV channels, and more—all in one place with ease and convenience.">
    <meta name="keywords" content="VTU services, airtime purchase, data bundles, TV subscription, fintech app, seamless payments, bill payments, affordable airtime solutions, mobile recharge platform, digital wallet integration, instant transactions, pay utility bills online, recharge platform for mobile devices, fintech solutions for everyday payments, mobile payment services, online utility payments, easy data recharge, quick airtime purchase, pay bills on the go, digital payment solutions, hassle-free bill payments, secure online transactions, fast mobile top-up, automated bill payments, all-in-one payment app, mobile wallet for bills, cheap data app, buy data earn reward, vtu, reward data app, data reseller app, @jannah_opt('site_name'), bill payments, utility payments, mobile top-up, fast recharge, affordable fintech solutions, airtime recharge, pay bills fast, TV subscriptions, digital recharge solutions, effortless mobile payments, convenient bill payments, fintech for mobile">
    <meta name="author" content="Jannah.org">
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
    @if($hasTheme && $theme)
    <meta name="theme-color" content="{{ $theme }}">
    <meta name="msapplication-navbutton-color" content="{{ $theme }}">
    <meta name="apple-mobile-web-app-status-bar-st" content="#000000">
    @else
    <meta name="theme-color" content="#ffffff">
    
    @endif
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <link href="https://s.w.org/wp-includes/css/dashicons.css" rel="stylesheet">
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
    <style>
    
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        html, div, body {
            font-family: 'Inter', sans-serif !important;
        }
        .bg-gradient {
             background: linear-gradient(to bottom, @jannah_opt('primary_color'), white);
         }
        .bg-primary {
            background: @jannah_opt('primary_color') !important;
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
    #sidebar {
      transition: width 0.3s ease-in-out;
    }
    #sidebar.collapsed {
      width: 4rem;
    }
    #sidebar.collapsed .menu-text {
      display: none;
    }
    #sidebar.collapsed .submenu-toggle-icon {
      transform: rotate(90deg);
    }
    .submenu ul {
      transition: max-height 0.3s ease-in-out;
      overflow: hidden;
    }
    .submenu ul.collapsed {
      max-height: 0;
    }
    .submenu ul.expanded {
      max-height: 500px;
    }
  
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #4CAF50;
        }
        input:checked + .slider:before {
            transform: translateX(20px);
        }
    </style>
    <script src="{{ asset('app.js') }}"></script>
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('composer.js') }}?v={{ time() }}"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.toggle-submenu').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
          var submenu = this.nextElementSibling;
          if (submenu) {
            if (submenu.classList.contains('hidden')) {
              submenu.classList.remove('hidden');
              submenu.classList.remove('collapsed');
              submenu.classList.add('expanded');
            } else {
              submenu.classList.add('hidden');
              submenu.classList.remove('expanded');
              submenu.classList.add('collapsed');
            }
          }
        });
      });
      var collapseBtn = document.getElementById('collapse-menu');
      var sidebar = document.getElementById('sidebar');
      var mainContent = document.getElementById('main-content');
      var isCollapsed = false;
      collapseBtn.addEventListener('click', function() {
        if (!isCollapsed) {
          sidebar.classList.add('collapsed');
          mainContent.classList.remove('lg:ml-50');
          mainContent.classList.add('lg:ml-16');
          isCollapsed = true;
        } else {
          sidebar.classList.remove('collapsed');
          mainContent.classList.remove('lg:ml-16');
          mainContent.classList.add('lg:ml-50');
          isCollapsed = false;
        }
      });
      var mobileToggle = document.getElementById('mobile-menu-toggle');
      mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('hidden');
      });
    });
  </script>
    {{ $slot }}
</head>