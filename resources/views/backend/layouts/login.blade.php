<html>
    <head>
		<meta charset="utf-8">
        <title>@yield('title')</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" type="text/css" href="{{asset('backend/css/jquery-ui.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('backend/css/bootstrap.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('css/sweetalert2.min.css')}}">
		<link rel="stylesheet" type="text/css" href="{{asset('backend/font-awesome/css/font-awesome.min.css')}}">
		<style>
			@font-face {
				font-family: Poppins-Regular;
				src: url('{{ asset('backend/fonts/poppins/Poppins-Regular.ttf') }}'); 
			}
			@font-face {
				font-family: Poppins-Medium;
				src: url('{{ asset('backend/fonts/poppins/Poppins-Medium.ttf') }}'); 
			}
			@font-face {
				font-family: Poppins-Bold;
				src: url('{{ asset('backend/fonts/poppins/Poppins-Bold.ttf') }}'); 
			}
			@font-face {
				font-family: Poppins-SemiBold;
				src: url('{{ asset('backend/fonts/poppins/Poppins-SemiBold.ttf') }}');
			}
		</style>
		<link rel="stylesheet" type="text/css" href="{{asset('backend/css/login.css')}}">
	</head>
    <body>
        @yield('content')

	    <script type="text/javascript" src="{{asset('backend/js/jquery.min.js')}}"></script>
	    <script type="text/javascript" src="{{asset('backend/js/jquery-ui.min.js')}}"></script>
	    <script type="text/javascript" src="{{asset('backend/js/bootstrap.min.js')}}"></script>
	    <script type="text/javascript" src="{{asset('js/sweetalert2.min.js')}}"></script>

	    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>
	    <script type="text/javascript" src="{{asset('backend/js/login.js')}}"></script>

		@section('scripts')
		@show
    </body>
</html>