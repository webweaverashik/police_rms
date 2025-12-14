<head>
    <title>@yield('title', 'ড্যাশবোর্ড') - পিআরএমএস</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="description" content="পুলিশ রিপোর্ট ম্যানেজমেন্ট সিস্টেম (PRMS) একটি ওয়েবভিত্তিক রিপোর্টিং অ্যাপ, যা রাজনৈতিক কর্মসূচি ও জনসমাগম সংক্রান্ত তথ্য ডিজিটালি এন্ট্রি, ম্যানেজ ও মনিটর করতে সহায়তা করে। Role-based access, zone-wise data filtering এবং structured reporting এর মাধ্যমে এটি দ্রুত, নির্ভুল ও নিরাপদ তথ্য ব্যবস্থাপনা নিশ্চিত করে।" />
    <meta name="keywords" content="Police Report Management System, PRMS, police reporting software, political program reporting, law enforcement reporting system, zone wise reporting, role based access control, police dashboard, political meeting report, election program monitoring, government reporting system, Bangladesh police software" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Police Report Management System (PRMS) | Zone-wise Political Program Reporting">
    <meta property="og:url" content="https://ashikur-rahman.com" />
    <meta property="og:site_name" content="PRMS by Ashikur Rahman" />
    <link rel="canonical" href="https://ashikur-rahman.com" />
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> --}}
    <!--end::Fonts-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    @stack('page-css')
    <!--end::Vendor Stylesheets-->

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
