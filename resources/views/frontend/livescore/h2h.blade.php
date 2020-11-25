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
    <meta property="og:url"				content="https://dooball-th.com/h2h/{{ $fixture_id }}">
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
    <link href="https://dooball-th.com/h2h/{{ $fixture_id }}" rel="canonical">
@endsection

@section('custom-css')
<style>
    @media only screen and (max-width: 575px) {
        .date-mb {
            width: 42px;
            overflow-wrap: break-word;
            word-wrap: break-word;
            -ms-word-break: break-word;
            word-break: break-word;
        }

        .tud-bun-tud {
            min-width: 50px;
            margin: 0px 5px;
        }

        .time-slot {
            width: 75px;
        }

        .home-h2h-top img,
        .away-h2h-top img {
            width: 35px;
        }

        .match-cube {
            width: 20px;
            height: 20px;
            margin-right: 2px;
        }
    }
    
    @media only screen and (min-width: 576px) {
        .home-h2h-top img,
        .away-h2h-top img {
            width: 75px;
        }
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

        <div class="row mt-5 prediction-table-area">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-condensed text-white">
                        <thead>
                            <tr class="card-prediction">
                                <th colspan="5">
                                    <div class="d-flex alit-center league-h2h-info">Loading..</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tbody-h2h">
                            <tr class="league-loading">
                                <td>
                                    <div class="table-loading">
                                        <div class="l-one"></div>
                                        <div class="l-two"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="league-show">
                                {{-- <td class="td-h2h-time">
                                    <div class="d-flex df-col just-center time-slot"></div>
                                </td> --}}
                                <td class="td-h2h-home">
                                    <div class="home-h2h-top d-flex df-col alit-center just-center"></div>
                                </td>
                                <td class="td-h2h-vs-top"></td>
                                <td class="td-h2h-away">
                                    <div class="away-h2h-top d-flex df-col alit-center just-center"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <h1 class="prediction-title h-one">{!! $h_one !!}</h1>

        <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="headtwohead-tab" data-toggle="tab" href="#headtwohead" role="tab" aria-controls="headtwohead" aria-selected="true">
                    สถิติวิเคราะห์
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab" aria-controls="timeline" aria-selected="false">
                    Timeline
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="lineup-tab" data-toggle="tab" href="#lineup" role="tab" aria-controls="lineup" aria-selected="false">
                    Line up
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="leaguetable-tab" data-toggle="tab" href="#leaguetable" role="tab" aria-controls="leaguetable" aria-selected="false">
                    ตารางลีก
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="statistics-tab" data-toggle="tab" href="#statistics" role="tab" aria-controls="statistics" aria-selected="false">
                    สถิติเกม
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="topscore-tab" data-toggle="tab" href="#topscore" role="tab" aria-controls="topscore" aria-selected="false">
                    ดาวซัลโว
                </a>
            </li>
        </ul>
        <div class="tab-content text-white" id="myTabContent">
            <div class="tab-pane fade show active" id="headtwohead" role="tabpanel" aria-labelledby="headtwohead-tab">
                @include('frontend._partials.h2h.first-tab')
            </div>
            <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                @include('frontend._partials.h2h.timeline-tab')
            </div>
            <div class="tab-pane fade" id="lineup" role="tabpanel" aria-labelledby="lineup-tab">
                @include('frontend._partials.h2h.lineups-tab')
            </div>
            <div class="tab-pane fade" id="leaguetable" role="tabpanel" aria-labelledby="leaguetable-tab">
                @include('frontend._partials.h2h.league-table-tab')
            </div>
            <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="statistics-tab">
                <div class="statistics-content"></div>
            </div>
            <div class="tab-pane fade" id="topscore" role="tabpanel" aria-labelledby="topscore-tab">
                @include('frontend._partials.h2h.topscore-tab')
            </div>
        </div>
    </div>

    <h2 class="text-white h-two">{!! $h_two !!}</h2>
    <p class="bottom-content c-content">{!! $bottom_content !!}</p>
    <ul class="ul-last c-content">{!! $ul_last !!}</ul>
    <p class="p-last c-content">{!! $p_last !!}</p>

    <div class="d-flex alit-center just-center banner-content">BANNER</div>
    <input type="hidden" id="this_host" value="{{ $this_host }}">
    <input type="hidden" id="fixture_id" value="{{ $fixture_id }}">
    <input type="hidden" id="image_icon" value="{{ asset('images/football-icon.png') }}">
</div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script>
        var baseURL = $('#base_url').val();
        var fixtureId = $('#fixture_id').val();

        var hProgress = false;
        var aProgress = false;

        var homeTeamId = 0;
        var awayTeamId = 0;
        var homeTeamName = '';
        var awayTeamName = '';
        var homeDatas = [];
        var awayDatas = [];

        $(function () {
            clearSlide();

            $('.home-player').hide();
            $('.away-player').hide();

            loadFixtures();
            loadStatistics();
        });

        function loadFixtures() {
            var params = {fixture_id: fixtureId};
            console.log(params);

            $.ajax({
                url: $('#base_url').val() + '/api/h2h',
                type: 'GET',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response) {
                        showFixture(response);
                    } else {
                        var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                        $('.tbody-h2h').html(tr);
                    }
                },
                error: function(response) {
                    // console.log(response);
                    var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                    $('.live-table').html(tr);
                }
            });
        }

        function showFixture(row) {
            var lName = (row.league_name) ? row.league_name : '-';
            lName = (lName == 'Primera Division') ? 'SPAIN LA LIGA' : lName;

            homeTeamId = row.home_id;
            awayTeamId = row.away_id;
            homeTeamName = row.home_name;
            awayTeamName = row.away_name;

            $('.league-h2h-info').html('<img src="' + row.league_logo_path + '" width="25" class="mr-10"><span>' + lName + '</span>');

            $('.home-h2h-top').html('<img src="' + row.home_logo_path + '"><div class="home-top-win d-flex">Loading..</div>');

            var rs = '<span class="ml-10 mr-10">VS</span>';
            var rsClass = '';

            if (row.status_short == 'FT') {
                rs = '<span class="ml-10 mr-10 w-35 font-weight-bold">' + row.goals_home_team + ' : ' + row.goals_away_team + '</span>';
                rsClass = 'text-info';
            } else if (row.status_short == '1H' || row.status_short == '2H' || row.status_short == 'HT') {
                rs = '<span class="ml-10 mr-10 w-35 font-weight-bold">' + row.goals_home_team + ' : ' + row.goals_away_team + '</span>';
                rsClass = 'text-info';
            }

            var rsFormat = '<span>' + homeTeamName + '</span>';
            rsFormat += rs;
            rsFormat += '<span>' + awayTeamName + '</span>';

            var time = showDateTimeFromTimeStamp(row.event_timestamp, 1);
            var timeText = ''; // row.statusShort + ' ' + time;

            if (row.status_short == 'TBD' || row.status_short == 'NS') {
                timeText = '<span class="text-danger">' + time + '</span>';
            } else if (row.status_short == '1H' || row.status_short == '2H') {
                timeText = '<a href="javascript:void(0)" class="btn btn-live active mr-10">LIVE</a><span class="text-danger">' + row.status_short + ' ' + row.elapsed + '<span class="live">\'</span></span>';
            } else {
                timeText = '<span>' + row.status_short + '</span>';
            }

            // var timeSlot = timeText + '' + '<span>' + row.round + '</span><span>' + row.venue + '</span>';
            // $('.time-slot').html('<span>' + row.round + '</span>');
            var tm = timeText.replace('<br>', ' ');
            $('.td-h2h-vs-top').html('<div class="d-flex alit-center just-center ' + rsClass + '">' + rsFormat + '</div><div class="flex-center mt-1">' + tm + '</div>');
            $('.away-h2h-top').html('<img src="' + row.away_logo_path + '"><div class="away-top-win d-flex">Loading..</div>');

            $('.league-loading').remove();
            $('.league-show').fadeIn();

            $('.timeline-home-name').html(homeTeamName);
            $('.timeline-away-name').html(awayTeamName);

            $('.five-home-team').html(homeTeamName);
            $('.five-away-team').html(awayTeamName);

            loadLatestFive(homeTeamId, 'home');
            loadLatestFive(awayTeamId, 'away');

            var count = 0;
            var chkDatas = setInterval(function () {
                if (hProgress && aProgress) {
                    showMatchHistFive(); // 4
                    count++;
                    clearInterval(chkDatas);
                }
            }, 1000);

            if (row.events) {
                showTimeline(JSON.parse(row.events), homeTeamName);
            }

            loadLineUp(homeTeamName, awayTeamName);
            loadLeagueTable(row.league_id, homeTeamName, awayTeamName);
            loadTopScore(row.league_id);
        }

        function loadLatestFive(teamId, side = '') {
            var params = {team_id: teamId};
            console.log('Team id: ' + teamId);

            $.ajax({
                url: $('#base_url').val() + '/api/fixtures-team',
                type: 'GET',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (datas) {
                    if (datas) {
                        var filter = filterDate(datas);
                        filter.sort(compare);

                        showTable(filter, side); // 2, 3

                        if (side == 'home') {
                            homeDatas = datas;
                            hProgress = true;
                            showSideHistHome(filter); // 5
                        }

                        if (side == 'away') {
                            awayDatas = datas;
                            aProgress = true;
                            showSideHistAway(filter); // 6
                        }
                    } else {
                        $('.h2h-' + side).html('<span class="text-muted">-- ไม่มีข้อมูล --</span>');
                    }
                },
                error: function(response) {
                    // console.log(response);
                    $('.h2h-' + side).html('<span class="text-muted">-- ไม่มีข้อมูล --</span>');
                }
            });
        }

        function showMatchHistFive() {
            var listBoth = homeDatas.concat(awayDatas);
            var filter = filterDate(listBoth);
            filter.sort(compare);

            if (filter.length > 0) {
                var uniqueList = [];
                var list = [];

                for (var i = 0; i < filter.length; i++) {
                    var row = filter[i];

                    if ((row.homeTeam.team_name == homeTeamName || row.homeTeam.team_name == awayTeamName) &&
                        (row.awayTeam.team_name == homeTeamName || row.awayTeam.team_name == awayTeamName)) {

                        var dName = row.homeTeam.team_name + '-' + row.awayTeam.team_name + '-' + row.event_timestamp;

                        if ($.inArray(dName, uniqueList) === -1) {
                            list.push(row);
                            uniqueList.push(dName);
                        }
                    }
                }

                genFour(list);
            }
        }

        function showTable(datas, side) {
            var html = '';
            var winRsText = '';

            if (datas.length > 0) {
                html += '<table class="table table-condensed text-white">';

                var row = null;
                var lName = '';
                var y = 0;
                var m = 0;
                var d = 0;
                var dFormat = '';

                var leftClass = '';
                var rightClass = '';

                var rs = '';
                var rsClass = '';

                var topFiveTeam = datas;

                if (datas.length > 5) {
                    topFiveTeam = datas.slice(0, 5);
                }

                for (var j = 0; j < topFiveTeam.length; j++) {
                    row = topFiveTeam[j];

                    leftClass = '';
                    rightClass = '';
                    rs = '? : ?';
                    rsClass = '';

                    lName = (row.league.name) ? row.league.name : '-';
                    lName = (lName == 'Primera Division') ? 'SPAIN LA LIGA' : lName;

                    y = parseInt(row.event_date.substr(0, 4));
                    m = parseInt(row.event_date.substr(5, 2));
                    d = parseInt(row.event_date.substr(8, 2));
                    dFormat = dateShortFormat(d, m, y);

                    if (side == 'home') {
                        leftClass = (row.homeTeam.team_name == homeTeamName) ? 'text-danger' : '';
                        rightClass = (row.awayTeam.team_name == homeTeamName) ? 'text-danger' : '';
                    } else {
                        leftClass = (row.homeTeam.team_name == awayTeamName) ? 'text-danger' : '';
                        rightClass = (row.awayTeam.team_name == awayTeamName) ? 'text-danger' : '';
                    }

                    html += '<tr>';
                    html +=     '<td class="hd-format"><div class="team-show d-flex alit-center date-mb">' + dFormat; + '</div></td>';
                    html +=     '<td class="text-right ht-format">';
                    html +=         '<div class="team-show d-flex alit-center just-end">';
                    html +=             '<span class="' + leftClass + ' tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35" class="ml-2">';
                    html +=         '</div>';
                    html +=     '</td>';

                    if (row.statusShort == 'FT') {
                        rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                        rsClass = 'text-info';

                        if (side == 'home') {
                            if (row.homeTeam.team_name == homeTeamName) { // home
                                winRsText += winStatusText(row.goalsHomeTeam, row.goalsAwayTeam);
                            } else { // away
                                winRsText += winStatusText(row.goalsAwayTeam, row.goalsHomeTeam);
                            }
                        }

                        if (side == 'away') {
                            if (row.homeTeam.team_name == awayTeamName) { // home
                                winRsText += winStatusText(row.goalsHomeTeam, row.goalsAwayTeam);
                            } else { // away
                                winRsText += winStatusText(row.goalsAwayTeam, row.goalsHomeTeam);
                            }
                        }
                    } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                        rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                        rsClass = 'text-info';
                    }

                    html +=     '<td class="hrs-format"><div class="team-show d-flex alit-center just-center ' + rsClass + '">' + rs + '</div></td>';
                    html +=     '<td class="ht-format">';
                    html +=         '<div class="team-show d-flex alit-center">';
                    html +=             '<img src="' + row.awayTeam.logo + '" width="35" class="mr-2"><span class="' + rightClass + ' tud-bun-tud">' + row.awayTeam.team_name + '</span>';
                    html +=         '</div>';
                    html +=     '</td>';
                    html +=     '<td class="hl-format"><div class="team-show d-flex alit-center just-center tud-bun-tud">' + lName + '</div></td>';
                    html += '</tr>';
                }

                html += '</table>';
            }

            $('.h2h-' + side).html(html);
            $('.' + side + '-top-win').html(winRsText);
        }

        function showSideHistHome(datas) {
            var html = '';

            if (datas.length > 0) {
                var count = 0;
                html += '<table class="table table-condensed text-white">';

                for (var j = 0; j < datas.length; j++) {
                    var row = datas[j];

                    if (row.homeTeam.team_name == homeTeamName && count < 5) {
                        var lName = (row.league.name) ? row.league.name : '-';
                        lName = (lName == 'Primera Division') ? 'SPAIN LA LIGA' : lName;

                        var y = parseInt(row.event_date.substr(0, 4));
                        var m = parseInt(row.event_date.substr(5, 2));
                        var d = parseInt(row.event_date.substr(8, 2));
                        var dFormat = dateShortFormat(d, m, y);

                        html += '<tr>';
                        html +=     '<td class="hd-format"><div class="team-show d-flex alit-center date-mb">' + dFormat; + '</div></td>';
                        html +=     '<td class="text-right ht-format">';
                        html +=         '<div class="team-show d-flex alit-center just-end">';
                        html +=             '<span class="text-danger tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35" class="ml-2">';
                        html +=         '</div>';
                        html +=     '</td>';

                        var rs = '? : ?';
                        var rsClass = '';

                        if (row.statusShort == 'FT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        }

                        html +=     '<td class="hrs-format"><div class="team-show d-flex alit-center just-center ' + rsClass + '">' + rs + '</div></td>';
                        html +=     '<td class="ht-format">';
                        html +=         '<div class="team-show d-flex alit-center">';
                        html +=             '<img src="' + row.awayTeam.logo + '" width="35" class="mr-2"><span class="tud-bun-tud">' + row.awayTeam.team_name + '</span>';
                        html +=         '</div>';
                        html +=     '</td>';
                        html +=     '<td class="hl-format"><div class="team-show d-flex alit-center just-center tud-bun-tud">' + lName + '</div></td>';
                        html += '</tr>';
                        count++;
                    }
                }

                html += '</table>';
            }

            $('.h2h-home-only').html(html);
        }

        function showSideHistAway(datas) {
            var html = '';

            if (datas.length > 0) {
                var count = 0;
                html += '<table class="table table-condensed text-white">';

                for (var j = 0; j < datas.length; j++) {
                    var row = datas[j];

                    if (row.awayTeam.team_name == awayTeamName && count < 5) {
                        var lName = (row.league.name) ? row.league.name : '-';
                        lName = (lName == 'Primera Division') ? 'SPAIN LA LIGA' : lName;

                        var y = parseInt(row.event_date.substr(0, 4));
                        var m = parseInt(row.event_date.substr(5, 2));
                        var d = parseInt(row.event_date.substr(8, 2));
                        var dFormat = dateShortFormat(d, m, y);

                        html += '<tr>';
                        html +=     '<td class="hd-format"><div class="team-show d-flex alit-center date-mb">' + dFormat; + '</div></td>';
                        html +=     '<td class="text-right ht-format">';
                        html +=         '<div class="team-show d-flex alit-center just-end">';
                        html +=             '<span class="tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35" class="ml-2">';
                        html +=         '</div>';
                        html +=     '</td>';

                        var rs = '? : ?';
                        var rsClass = '';

                        if (row.statusShort == 'FT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        }

                        html +=     '<td class="hrs-format"><div class="team-show d-flex alit-center just-center ' + rsClass + '">' + rs + '</div></td>';
                        html +=     '<td class="ht-format">';
                        html +=         '<div class="team-show d-flex alit-center">';
                        html +=             '<img src="' + row.awayTeam.logo + '" width="35" class="mr-2"><span class="text-danger tud-bun-tud">' + row.awayTeam.team_name + '</span>';
                        html +=         '</div>';
                        html +=     '</td>';
                        html +=     '<td class="hl-format"><div class="team-show d-flex alit-center just-center tud-bun-tud">' + lName + '</div></td>';
                        html += '</tr>';
                        count++;
                    }
                }

                html += '</table>';
            }

            $('.h2h-away-only').html(html);
        }

        function genFour(datas) {
            var html = '';

            if (datas.length > 0) {
                var count = 0;

                html += '<table class="table table-condensed text-white">';

                for (var j = 0; j < datas.length; j++) {
                    var row = datas[j];

                    if (count < 5) {
                        var lName = (row.league.name) ? row.league.name : '-';
                        lName = (lName == 'Primera Division') ? 'SPAIN LA LIGA' : lName;

                        var y = parseInt(row.event_date.substr(0, 4));
                        var m = parseInt(row.event_date.substr(5, 2));
                        var d = parseInt(row.event_date.substr(8, 2));
                        var dFormat = dateShortFormat(d, m, y);

                        html += '<tr>';
                        html +=     '<td class="hd-format"><div class="team-show d-flex alit-center date-mb">' + dFormat; + '</div></td>';
                        html +=     '<td class="text-right ht-format">';
                        html +=         '<div class="team-show d-flex alit-center just-end">';
                        html +=             '<span class="tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35" class="ml-2">';
                        html +=         '</div>';
                        html +=     '</td>';

                        var rs = '? : ?';
                        var rsClass = '';

                        if (row.statusShort == 'FT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        }

                        html +=     '<td class="hrs-format"><div class="team-show d-flex alit-center just-center ' + rsClass + '">' + rs + '</div></td>';
                        html +=     '<td class="ht-format">';
                        html +=         '<div class="team-show d-flex alit-center">';
                        html +=             '<img src="' + row.awayTeam.logo + '" width="35" class="mr-2"><span class="tud-bun-tud">' + row.awayTeam.team_name + '</span>';
                        html +=         '</div>';
                        html +=     '</td>';
                        html +=     '<td class="hl-format"><div class="team-show d-flex alit-center just-center tud-bun-tud">' + lName + '</div></td>';
                        html += '</tr>';
                        count++;
                    }
                }

                html += '</table>';
            }

            $('.h2h-match-hist').html(html);
        }

        function filterDate(datas) {
            var list = [];

            if (datas.length > 0) {
                var cTimeStamp = currentTimeStamp();

                for (var i = 0; i < datas.length; i++) {
                    var row = datas[i];

                    if ((row.event_timestamp < cTimeStamp) && (row.statusShort == 'FT')) {
                        list.push(row);
                    }
                }
            }

            return list;
        }

        function compare(a, b) {
            if (a.event_timestamp < b.event_timestamp) {
                return 1;
            }
            if (a.event_timestamp > b.event_timestamp) {
                return -1;
            }

            return 0;
        }
    </script>
    <script type="text/javascript" src="{{ asset('frontend/js/h2h/timeline.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/h2h/lineups.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/h2h/league-table.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/h2h/statistics.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/h2h/topscore.js?' . time()) }}"></script>
@stop