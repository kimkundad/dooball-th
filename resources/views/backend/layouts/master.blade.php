<html>
    <head>
		<meta charset="utf-8">
        <title>@yield('title')</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/jquery-ui.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/font-awesome/css/font-awesome.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/dataTables.bootstrap.min.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/datatable-custom.css?' . time()) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/jquery.datetimepicker.css') }}">

		@section('custom-lib-css')
		@show

		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/admin.css?' . time()) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/skin-black.css?' . time()) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('backend/css/form.css?' . time()) }}">

		@section('custom-css')
		@show
	</head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            @section('sidebar')
                @include('backend.layouts.header')
                @include('backend.layouts.sidebar')
            @show

			<div class="content-wrapper">
				<section class="content-header">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<ul class="dooball-breadcrumb">
								<li><a href="{{ URL::to('/') }}/admin/dashboard"><span class="fa fa-home"></span>&nbsp;หน้าหลัก</a></li>
								@section('breadcrumb')
								@show
							</ul>
						</div>
					</div>
				</section>

				@section('content')
				@show
			</div>
			@include('backend.layouts.footer')
		</div>

	    <script type="text/javascript" src="{{ asset('backend/js/jquery.min.js') }}"></script>
	    <script type="text/javascript" src="{{ asset('backend/js/jquery-ui.min.js') }}"></script>
	    <script type="text/javascript" src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
	    <script type="text/javascript" src="{{ asset('js/sweetalert2.min.js') }}"></script>

	    <script type="text/javascript" src="{{ asset('backend/js/admin.min.js') }}"></script>
	    <script type="text/javascript" src="{{ asset('backend/js/custom-script.js') }}"></script>

	    <script type="text/javascript" src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
	    <script type="text/javascript" src="{{ asset('backend/js/dataTables.bootstrap.min.js') }}"></script>

		<script type="text/javascript" src="{{ asset('backend/js/php-date-formatter.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('backend/js/jquery.mousewheel.js') }}"></script>
		<script type="text/javascript" src="{{ asset('backend/js/jquery.datetimepicker.js') }}"></script>

		@section('custom-lib-scripts')
		@show

	    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>

		@section('custom-scripts')
		@show
    </body>
</html>