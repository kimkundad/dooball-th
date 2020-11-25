@extends('frontend.layouts.new-theme')

@section('title')
    <title>Register with social</title>
@endsection

@section('custom-css')
@endsection

@section('content')

@php
    \App::setLocale('th');
@endphp

<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')

    <div class="content-box">
        <div class="row">
            <div class="col-12">
                <div class="card bg-trans">
                    <div class="card-header text-center">
                        <h4 class="c-theme">
                            {{-- <i class="fa fa-info-circle text-info mr-2"></i> --}}
                            {{ __('static.regis_info') }}
                        </h4>
                    </div>
                    <div class="card-body text-white">
                        <form id="register_with_social_form" method="POST" action="{{ url('api/register-after-social') }}">
                            @csrf

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right"></label>
                                <div class="col-md-6">
                                    <img src="{{ $avatar }}" alt="">

                                    @guest
                                    @else
                                        <div class="" style="color:transparent;">
                                            {{ Auth::user()->role }}
                                        </div>
                                    @endguest
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('static.username') }}</label>
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control no-round no-border" name="username" value="" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('static.password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control no-round no-border" name="password" value="" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="screen_name" class="col-md-4 col-form-label text-md-right">{{ __('static.display_name') }}</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control no-round no-border" id="screen_name" name="screen_name" value="{{ $screen_name }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="line_id" class="col-md-4 col-form-label text-md-right">Line ID</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control no-round no-border" id="line_id" name="line_id" value="" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tel" class="col-md-4 col-form-label text-md-right">เบอร์โทร</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control no-round no-border" id="tel" name="tel" value="" required>
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-lg active-gd btn-block no-round text-white">
                                        {{ __('Next') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
    {{-- <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script> --}}
    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        $(function() {
            // clearSlide();

            $('#register_with_social_form').on('submit', function() {
                if (!$('#username').val().trim() || !$('#password').val().trim() || !$('#line_id').val().trim() || !$('#tel').val().trim()) {
                    if (!$('#username').val().trim()) {
                        alert('กรุณาระบุ Username');
                        $('#username').focus();
                    } else if (!$('#password').val().trim()) {
                        alert('กรุณาระบุ Password');
                        $('#password').focus();
                    } else if (!$('#line_id').val().trim()) {
                        alert('กรุณาระบุ Line ID');
                        $('#line_id').focus();
                    } else if (!$('#tel').val().trim()) {
                        alert('กรุณาระบุหมายเลขโทรศัพท์');
                        $('#tel').focus();
                    }

                    return false;
                }
            });
        });
    </script>
@stop