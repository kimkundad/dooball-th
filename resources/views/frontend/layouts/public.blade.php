<html>
    <head>
		<meta charset="utf-8">

		@section('title')
			<title>Dooball online</title>
		@show

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		@section('robots')
		<meta name="robots" content="noindex" />
		@show
		@section('description')
		<meta name="description" content="">
		@show

		<link rel="icon" type="image/png" href="{{ asset('frontend/images/favicon.png') }}" />
		{{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,400i|Roboto:500"> --}}
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/fontawesome-free-5.9.0/css/all.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css?' . time()) }}">
		{{-- <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script> --}}
		<script src="{{asset('frontend/js/scrollreveal.min.js')}}"></script>

		@section('custom-css')
		@show
	</head>
    <body class="is-boxed has-animations">
		{{-- @section('sidebar')
			@include('backend.layouts.header')
		@show --}}

		{{-- <div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<ul class="noi-breadcrumb">
					<li><a href="{{ URL::to('/') }}/admin/dashboard"><span class="fa fa-home"></span>&nbsp;หน้าหลัก</a></li>
					@section('breadcrumb')
					@show
				</ul>
			</div>
		</div> --}}

		@section('content')
		@show

		<script src="{{asset('frontend/js/main.min.js')}}"></script>
		<script src="{{asset('frontend/js/custom.js')}}"></script>
		@section('custom-scripts')
		@show
    </body>
</html>