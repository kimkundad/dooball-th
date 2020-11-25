<html xmlns="http://www.w3.org/1999/xhtml" lang="th">
    <head>
		<meta charset="utf-8">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		{{-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> --}}
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link rel="home" href="https://dooball-th.com/" />
		<link rel="icon" type="image/png" href="{{ asset('frontend/images/favicon.png') }}" />
		{{-- <link rel="icon" type="image/png" href="http://dooball-th.com/frontend/images/favicon.png" /> --}}

		{{-- rel="stylesheet" ต่างๆ ของเวบใส่ตรงนี้ --}}
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/fontawesome-free-5.9.0/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/nvs-flex.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/new-theme.css?' . time()) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/dooball-th-responsive.css?' . time()) }}">

		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://dooball-th.com/sitemap.xml" />
		<meta name="theme-color" content="#fc4612">

		@section('title')
			<title>Dooball online</title>
		@show

		@section('description')
			<meta name="description" content="">
		@show

		@section('robots')
			<meta name="robots" content="noindex" />
		@show

		<meta property="fb:profile_id" 			content="https://www.facebook.com/pg/flowball168/about">
		<meta property="og:locale" 				content="th">
		<meta property="og:site_name" 			content="Dooball">
		<meta property="og:type" 				content="website">

		@section('fb_url')
			<meta property="og:url"				content="https://dooball-th.com">
		@show
		@section('fb_title')
			<meta property="og:title"			content="ดูบอลสด 7m 4k ดูบอลออนไลน์ วันนี้ คมชัด HD สำรองคู่ละ 30 ลิ้ง">
		@show
		@section('fb_description')
			<meta property="og:description"		content="Dooball เว็บรวมลิ้งดูบอลออนไลน์วันนี้ ครบทุกคู่ทั่วโลกฟรี ดูบอลสด 7m 4k HD อัพเดตลิ้งดูบอล youtube facebook ระหว่างการแข่งขันสด รองรับ ดูบอลผ่านเน็ตมือถือ">
		@show
		{{-- index + blog --}}
		@section('fb_image')
			<meta property="og:image" 			content="https://dooball-th.com/images/social/dooball_1200x600.jpg">
		@show

		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="600">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@DooballTh">
		<meta name="twitter:creator" content="@DooballTh">

		@section('tw_title')
			<meta name="twitter:title"			content="ดูบอลสด 7m 4k ดูบอลออนไลน์ วันนี้ คมชัด HD สำรองคู่ละ 30 ลิ้ง">
		@show
		@section('tw_description')
			<meta name="twitter:description"	content="Dooball เว็บรวมลิ้งดูบอลออนไลน์วันนี้ ครบทุกคู่ทั่วโลกฟรี ดูบอลสด 7m 4k HD อัพเดตลิ้งดูบอล youtube facebook ระหว่างการแข่งขันสด รองรับ ดูบอลผ่านเน็ตมือถือ">
		@show
		@section('tw_image')
			<meta name="twitter:image" content="https://dooball-th.com/images/dooball_800x418.jpg">
		@show

		<meta name="twitter:image:width" content="800">
		<meta name="twitter:image:height" content="418">

		@section('global_url')
			<link href="https://dooball-th.com/" rel="canonical">
		@show

		<link href="https://dooball-th.com/" rel="home">
		<link type="text/plain" href="https://dooball-th.com/about.txt" rel="author">

		@section('custom-css')
		@show
	</head>
    <body>
		@section('content')
		@show

		@include('frontend.layouts.footer')

		<script src="{{asset('frontend/js/jquery-min.js')}}"></script>
		<script src="{{asset('js/bootstrap.min.js')}}"></script>
		<script src="{{asset('js/sweetalert2.min.js')}}"></script>

		@section('custom-scripts')
		@show
    </body>
</html>