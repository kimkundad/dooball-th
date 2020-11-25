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
    <meta property="og:url"				content="https://dooball-th.com/livescore">
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
    <link href="https://dooball-th.com/livescore" rel="canonical">
@endsection

@section('custom-css')
<style>
    td.hd-format {
        width: 15% !important;
    }
    .ht-format, .hl-format {
        width: 30% !important;
    }
    .hrs-format {
        width: 10% !important;
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
        @include('frontend._partials.livescore.nav-date')

        <div class="row mt-5">
            <div class="col-12">
                {!! $top_content !!}
            </div>

            {{-- <div class="col-12">
                <h4 class="text-white">ข้อมูลผลบอลประจำวันที่ {{ $show_date }}</h4>
            </div> --}}
            {{-- <div class="col-lg-3">
                @if ($date)
                    <a class="c-theme" href="{{ route('livescore') }}">ดูตารางบอลวันนี้</a>
                @else
                    <a class="c-theme" href="{{ route('livescore', Date('Y-m-d', strtotime("-1 days"))) }}">ดูตารางบอลเมื่อวาน</a>
                @endif
            </div> --}}
        </div>
        <div class="row my-3 prediction-table-area">
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

        <br>
        {!! $bottom_content !!}
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
    <input type="hidden" id="date_search" value="{{ $input_date }}">
    <input type="hidden" id="this_host" value="{{ $this_host }}">
</div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script>
        var thisHost = $('#this_host').val();

        $(function() {
            clearSlide();

            livescore();

            // $('.sort-time').on('click', function() {
            //     // ...
            // });
        });

        function livescore() {
            var params = {date: $('#date_search').val()};

            $.ajax({
                url: $('#base_url').val() + '/api/livescore',
                type: 'GET',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    arrangeFixturesBackend(response.small_league, response.big_league, response.mode);
                },
                error: function(response) {
                    // console.log(response);
                    var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                    $('.livescore-table').html(tr);
                }
            });
        }

        function arrangeFixturesBackend(smallLeagues, bigLeagues, mode) {
            var bigHtml = genTableBackend(bigLeagues, mode);
            $('.livescore-table').html(bigHtml);

            var html = genTableBackend(smallLeagues, mode);
            $('.livescore-table').append(html);

            if (bigHtml == '' && html == '') {
                var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                $('.livescore-table').html(tr);
            }

            setTimeout(function(){
                var d = new Date();
                var t = d.toLocaleTimeString();
                console.log(t);

                livescore();
            }, 60000); // 60 seconds => 1 minute
        }

        function genTableBackend(datas, mode) {
            var html = '';

            if (! datas) {
                return '';
            }

            if (Object.keys(datas).length > 0) {
                var count = 0;
                var showTime = '';
                var rows = null;
                var lName = '';

                // for (var i = 0; i < datas.length; i++) {
                for (var i in datas) {
                    rows = datas[i];
                    lName = (rows.league_name == 'Primera Division') ? 'SPAIN LA LIGA' : rows.league_name;

                    html += '<thead>';
                    html += '<tr class="card-prediction">';
                    html +=     '<th colspan="4">';
                    html +=         '<div class="d-flex alit-center">';
                    html +=             '<img src="' + rows.league_logo + '" width="25">&nbsp;' + lName;
                    html +=         '</div>';
                    html +=     '</th>';
                    html += '</tr>';
                    html += '</thead>';

                    // console.log(rows);

                    if (rows.matches.length > 0) {
                        var row = null;

                        for (var j = 0; j < rows.matches.length; j++) {
                            row = rows.matches[j];
                            // console.log(row);

                            var time = showDateTimeFromTimeStamp(row.event_timestamp);
                            var sttShort = (mode == 'new-call') ? row.statusShort : row.status_short;

                            showTime = sttShort;
                            if (sttShort == 'TBD' || sttShort == 'NS') {
                                showTime = '<span class="text-danger">' + time + '</span>';
                            } else if (sttShort == '1H' || sttShort == '2H') {
                                showTime = '<span class="btn btn-live active">LIVE</span><span class="ml-2 text-danger">' + sttShort + ' ' + row.elapsed + '<span class="live">\'</span></span>';
                            }

                            html += '<tbody>';
                            html +=     '<tr>';
                            html +=         '<td class="hd-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center">';
                            html +=                 showTime;
                            html +=             '</a>';
                            html +=         '</td>';
                            html +=         '<td class="text-right ht-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center just-end">';
                            html +=                 '<span class="mr-2 tud-bun-tud">' + ((mode == 'new-call') ? row.homeTeam.team_name : row.home_name) + '</span><img src="' + ((mode == 'new-call') ? row.homeTeam.logo : row.home_logo_path) + '" width="35">';
                            html +=             '</a>';
                            html +=         '</td>';

                            var rs = '? : ?';
                            var rsClass = '';
                            var goalsHomeTeam = (mode == 'new-call') ? row.goalsHomeTeam : row.goals_home_team;
                            var goalsAwayTeam = (mode == 'new-call') ? row.goalsAwayTeam : row.goals_away_team;

                            if (sttShort == 'FT') {
                                rs = goalsHomeTeam + ' : ' + goalsAwayTeam;
                                rsClass = 'text-info';
                            } else if (sttShort == '1H' || sttShort == '2H' || sttShort == 'HT') {
                                rs = goalsHomeTeam + ' : ' + goalsAwayTeam;
                                rsClass = 'text-info';
                            }

                            html +=         '<td class="hd-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center just-center '+ rsClass +'"">';
                            html +=                 rs;
                            html +=             '</a>';
                            html+=          '</td>';
                            html +=         '<td class="ht-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center">';
                            html +=                 '<img src="' + ((mode == 'new-call') ? row.awayTeam.logo : row.away_logo_path) + '" width="35"><span class="ml-2 tud-bun-tud">' + ((mode == 'new-call') ? row.awayTeam.team_name : row.away_name) + '</span>';
                            html +=             '</a>';
                            html +=         '</td>';
                            // html +=         '<td><div class="team-show d-flex alit-center just-center">' + row.status + '</div></td>';
                            html +=     '</tr>';
                            html += '</tbody>';
                        }
                    }
                }
            }

            return html;
        }

        /*
        function groupLeague(datas) {
            var groups = [];
            var bigGroups = [];

            if (datas.length > 0) {
                var names = [];
                var logos = [];

                for (var i = 0; i < datas.length; i++) {
                    var lCtry = (datas[i].league.name == 'Premier League') ? datas[i].league.name + ': ' + datas[i].league.country : datas[i].league.name;
                    var found = $.inArray(lCtry, names);
                    if (found == -1) {
                        names.push(lCtry);
                        logos.push(datas[i].league.logo);
                    }
                }

                // console.log(names);
                // console.log(logos);

                for (var n = 0; n < names.length; n++) {
                    var matches = [];

                    for (var v = 0; v < datas.length; v++) {
                        var slCtry = (datas[v].league.name == 'Premier League') ? datas[v].league.name + ': ' + datas[v].league.country : datas[v].league.name;

                        if (slCtry == names[n]) {
                            matches.push(datas[v]);
                        }
                    }

                    var obj = {league: names[n], logo: logos[n], matches: matches};

                    if (names[n] == 'Premier League: England' || names[n] == 'Primera Division') {
                        bigGroups.push(obj);
                    } else {
                        groups.push(obj);
                    }
                    
                }
            }

            arrangeFixtures(groups, bigGroups);
        }

        function arrangeFixtures(datas, bigGroups) {
            var bigHtml = genTable(bigGroups);
            $('.livescore-table').html(bigHtml);

            var html = genTable(datas);
            $('.livescore-table').append(html);

            if (bigHtml == '' && html == '') {
                var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                $('.livescore-table').html(tr);
            }

            setTimeout(function(){
                var d = new Date();
                var t = d.toLocaleTimeString();
                console.log(t);

                livescoreNew();
            }, 60000); // 60 seconds => 1 minute
        }

        function genTable(datas) {
            var html = '';

            if (datas.length > 0) {
                var count = 0;
                var showTime = '';

                for (var i = 0; i < datas.length; i++) {
                    var rows = datas[i];
                    var lName = (rows.league == 'Primera Division') ? 'SPAIN LA LIGA' : rows.league;

                    html += '<thead>';
                    html += '<tr class="card-prediction">';
                    html +=     '<th colspan="4">';
                    html +=         '<div class="d-flex alit-center">';
                    html +=             '<img src="' + rows.logo + '" width="25">&nbsp;' + lName;
                    html +=         '</div>';
                    html +=     '</th>';
                    html += '</tr>';
                    html += '</thead>';

                    if (rows.matches.length > 0) {
                        for (var j = 0; j < rows.matches.length; j++) {
                            var row = rows.matches[j];

                            showTime = checkShowTime(row);

                            html += '<tbody>';
                            html +=     '<tr>';
                            html +=         '<td class="hd-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center">';
                            html +=                 showTime;
                            html +=             '</a>';
                            html +=         '</td>';
                            html +=         '<td class="text-right ht-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center just-end">';
                            html +=                 '<span class="mr-2 tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35">';
                            html +=             '</a>';
                            html +=         '</td>';

                            var rs = '? : ?';
                            var rsClass = '';

                            if (row.statusShort == 'FT') {
                                rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                                rsClass = 'text-info';
                            } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                                rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                                rsClass = 'text-info';
                            }

                            html +=         '<td class="hd-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center just-center '+ rsClass +'"">';
                            html +=                 rs;
                            html +=             '</a>';
                            html+=          '</td>';
                            html +=         '<td class="ht-format">';
                            html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center">';
                            html +=                 '<img src="' + row.awayTeam.logo + '" width="35"><span class="ml-2 tud-bun-tud">' + row.awayTeam.team_name + '</span>';
                            html +=             '</a>';
                            html +=         '</td>';
                            // html +=         '<td><div class="team-show d-flex alit-center just-center">' + row.status + '</div></td>';
                            html +=     '</tr>';
                            html += '</tbody>';
                        }
                    }
                }
            }

            return html;
        }*/
    </script>
@stop