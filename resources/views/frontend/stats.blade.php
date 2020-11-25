@extends('frontend.layouts.new-theme')

@section('title')
    <title>{{ $seo_title }}</title>
@endsection

@if($website_robot == 1)
    @section('robots')
        <meta name="robots" content="index, follow">
    @endsection
@endif

@section('description')
    <meta name="description" content="{{ $seo_description }}">
@endsection

@section('custom-css')
    <style>
        .stats-monthly {
            width: 200px;
            font-size: 1.25rem;
        }
        .stats-monthly:focus {
            outline: none !important;
        }
    </style>
@endsection

@section('content')
<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')
    @include('frontend.layouts.new-theme-navbar')
    @include('frontend._partials.new-theme.league-slider')
    @include('frontend.layouts.new-theme-navbar-second')

    <div class="content-box">
        <div class="row prediction-table-area">
            <div class="col-12 l-side">
                <div class="card bg-trans text-white no-round no-border">
                    <div class="card-header d-flex just-between no-round card-prediction">
                        <h2>สถิติการเล่นเกมทายผล</h2>
                        <select class="form-control stats-monthly" name="stats_monthly" id="stats_monthly">
                            <option value="-1">--- แสดงทั้งหมด ---</option>
                            @if(count($monthly_list) > 0)
                                @foreach($monthly_list as $ym)
                                    @php
                                        $selected = ($month == $ym) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $ym }}" {{ $selected }}>{{ $ym }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="card-body">
                        @include('frontend._partials/prediction/table-stats')
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-4 col-md-4 col-12 r-side">
                @include('frontend._partials.new-theme.right-sean')
                @include('frontend._partials.new-theme.right-ffp')
            </div> --}}
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>

<input type="hidden" id="user_name" value="{{ $user_name }}">
<input type="hidden" id="user_id" value="{{ $user_id }}">
<input type="hidden" id="this_host" value="{{ $this_host }}">

@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        var userName = $('#user_name').val();
        var userId = $('#user_id').val();
        var thisHost = $('#this_host').val();

        $(function() {
            clearSlide();

            var userLink = '';

            if (parseInt(userId) == 0) {
                userLink = 'bet-stats/' + userName;
            } else {
                userLink = 'bet-stats-user/' + userId;
            }

            $('#stats_monthly').on('change', function() {
                // console.log($(this).val());

                if ($(this).val() != -1) {
                    window.location = thisHost + '/' + userLink + '/' + $(this).val();
                } else {
                    window.location = thisHost + '/' + userLink;
                }
            });
        });
    </script>
@stop