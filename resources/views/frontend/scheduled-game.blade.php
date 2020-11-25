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

@section('fb_url')
    <meta property="og:url"				content="https://dooball-th.com/game">
@endsection
@section('fb_title')
    <meta property="og:title"			content="{{ $seo_title }}">
@endsection
@section('fb_description')
    <meta property="og:description"		content="{{ $seo_description }}">
@endsection
@section('fb_image')
    <meta property="og:image" 			content="https://dooball-th.com/images/social/game_1200x600.jpg">
@endsection
@section('tw_title')
    <meta name="twitter:title"			content="{{ $seo_title }}">
@endsection
@section('tw_description')
    <meta name="twitter:description"	content="{{ $seo_description }}">
@endsection
@section('tw_image')
    <meta name="twitter:image" content="https://dooball-th.com/images/game_800x418.jpg">
@endsection
@section('global_url')
    <link href="https://dooball-th.com/game" rel="canonical">
@endsection

@section('content')
<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')
    @include('frontend.layouts.new-theme-navbar')
    @include('frontend._partials.new-theme.league-slider')
    @include('frontend.layouts.new-theme-navbar-second')

    <div class="content-box">
        <div class="row">
            <div class="col-12">
                <div class="box-segment d-flex df-wrap">
                    <div class="seg-ele">
                        <a href="" class="btn btn-live">LIVE</a>
                    </div>
                    <div class="seg-ele">
                        <div>FRI</div><div>17 ARP</div>
                    </div>
                    <div class="seg-ele">
                        <div>FRI</div><div>17 ARP</div>
                    </div>
                    <div class="seg-ele">
                        <div>FRI</div><div>17 ARP</div>
                    </div>
                    <div class="seg-ele">
                        <div>FRI</div><div>17 ARP</div>
                    </div>
                    <div class="seg-ele">
                        <div>FRI</div><div>17 ARP</div>
                    </div>
                    <div class="seg-ele d-flex alit-center just-center"><i class="far fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row my-4 prediction-table-area">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-condensed text-white livescore-table">
                        <tr>
                            <td>
                                <div class="table-loading">
                                    <div class="l-one"></div>
                                    <div class="l-two"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script>
        var baseURL = $('#base_url').val();

        $(function() {
            clearSlide();

            scheduledGameAPI();
        });

        function scheduledGameAPI() {
            $.ajax({
                url: baseURL + '/api/scheduled-game',
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function (response) {

                    arrangeData(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function arrangeData(response) {
            // console.log(response);

            var html = '';

            if (response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                    var row = response[i];

                    if (row['league_name']) {
                        html += '<thead>';
                        html +=     '<tr class="card-prediction">';
                        // html +=         '<th></th>'; // <img src="asset('images/league/icon-premier.jpg') " alt="" width="25">
                        html +=         '<th colspan="7">' + row['league_name'] + '</th>';
                        html +=     '</tr>';
                        html += '</thead>';

                        html += '<tbody>';

                        if (row['match_datas'].length > 0) {
                            for(var j = 0; j < row['match_datas'].length; j++) {
                                var match = row['match_datas'][j];

                                html += '<tr>';
                                html +=     '<td>' + match.time + '</td>';
                                html +=     '<td></td>';
                                html +=     '<td>' + match.home_name + '</td>';
                                html +=     '<td><span id="score_' + match.id + '"></span></td>';
                                html +=     '<td>' + match.away_name + '</td>';
                                html +=     '<td></td>';
                                html +=     '<td></td>';
                                html += '</tr>';
                            }
                        }

                        html += '</tbody>';
                    }
                }
            } else {
                html += '<tbody>';
                html +=     '<tr>';
                html +=         '<td>--- ไม่พบข้อมูล ---</td>';
                html +=     '</tr>';
                html += '</tbody>';
            }

            $('.livescore-table').html(html);

            // setInterval(function() {
            //     scheduledGameAPI();
            // }, 5000); // 5 seconds
        }
    </script>
@stop