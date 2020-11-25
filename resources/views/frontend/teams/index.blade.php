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
    <meta property="og:url"				content="https://dooball-th.com/premierleague/teams/{{ $real_team_name }}">
@endsection
@section('fb_title')
    <meta property="og:title"			content="{{ $seo_title }}">
@endsection
@section('fb_description')
    <meta property="og:description"		content="{{ $seo_description }}">
@endsection
@section('fb_image')
    <meta property="og:image" 			content="https://dooball-th.com/images/social/dooball_1200x600.jpg">
@endsection
@section('tw_title')
    <meta name="twitter:title"			content="{{ $seo_title }}">
@endsection
@section('tw_description')
    <meta name="twitter:description"	content="{{ $seo_description }}">
@endsection
@section('tw_image')
    <meta name="twitter:image" content="https://dooball-th.com/images/dooball_800x418.jpg">
@endsection
@section('global_url')
    <link href="https://dooball-th.com/premierleague/teams/{{ $real_team_name }}" rel="canonical">
@endsection

@section('content')
<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')
    @include('frontend.layouts.new-theme-navbar')
    @include('frontend._partials.new-theme.league-slider')
    @include('frontend.layouts.new-theme-navbar-second')

    <div class="content-premierleague-box">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-12 l-side">
                {!! $top_content !!}

                <h1 class="text-uppercase">{{ $real_team_name }}</h1>
                <div class="d-flex alit-center just-center hide-if-load-complete">
                    <div class="graph-loading">
                        <div class="l-one"></div>
                        <div class="l-two"></div>
                    </div>
                </div>

                @php
                    $matchClass = '';
                    if($matches) {
                        if($matches['total'] != 0) {
                            $matchClass = 'complex-box no-border row-live-ele c-pointer';
                        }
                    }
                @endphp

                <div class="what-the-match {{ $matchClass }}">
                    <div class="livescore-today d-flex text-white">
                        <div class="tm-time-box"></div>
                        <div class="tm-team-box home"></div>
                        <div class="tm-score-box"></div>
                        <div class="tm-team-box away"></div>
                        <div class="tm-link-box flex-center"></div>
                    </div>

                    @if($matches)
                        @if($matches['total'] != 0)
                            @php
                                $val = $matches['records'][0];
                            @endphp
                            <div class="live-ball">
                                @if($val->sponsor_links)
                                    @foreach($val->sponsor_links as $ele)
                                        @php
                                            $link = preg_replace('/#[^\s]+/', '', $ele->url ); // remove => #https://www.ballzaa.com/linkdooball.php
                                        @endphp
                                        <div class="link">
                                            <a href="{{ $link }}" class="d-flex alit-center" target="_BLANK">{{ $ele->name }}</a>
                                        </div>
                                    @endforeach
                                @endif
                                @if($val->normal_links)
                                    @foreach($val->normal_links as $e)
                                        @php
                                            $lnk = preg_replace('/#[^\s]+/', '', $e->url );
                                        @endphp
                                        <div class="link">
                                            <a href="{{ $lnk }}" class="d-flex alit-center" target="_BLANK">{{ $e->name }}</a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            <h4 class="text-muted text-center my-2">{{ 'ลิ้งดูบอล' . $thai_short_name . ' มีอัพเดตก่อนบอลเตะ นะคะ' }}</h4>
                        @endif
                    @else
                        <h4 class="text-muted text-center my-2">{{ 'ลิ้งดูบอล' . $thai_short_name . ' มีอัพเดตก่อนบอลเตะ นะคะ' }}</h4>
                    @endif
                </div>
                <br>

                <div class="clearfix"></div>

                @include('frontend._partials.league.team-result', ['team_name' => $thai_short_name, 'tab_list' => $tab_list, 'league_name' => $league_thai_name, 'url' => $league_url])

                <div class="clearfix"></div>

                @include('frontend._partials.league.team-score', ['team_name' => $thai_long_name, 'tab_list' => $tab_list, 'league_name' => $league_thai_name, 'url' => $league_url])

                <div class="clearfix"></div>

                @include('frontend._partials.league.team-stats', ['team_name' => $thai_long_name, 'tab_list' => $tab_list, 'league_name' => $league_thai_name, 'url' => $league_url])

                <div class="clearfix"></div>

                @include('frontend._partials.league.team-transfer', ['team_name' => $thai_short_name, 'tab_list' => $tab_list, 'league_name' => $league_thai_name, 'url' => $league_url])

                <div class="clearfix"></div>

                @include('frontend._partials.league.team-members', ['team_name' => $thai_long_name, 'tab_list' => $tab_list, 'league_name' => $league_thai_name, 'url' => $league_url])

                <br>
                {!! $bottom_content !!}
            </div>
            <div class="col-lg-4 col-md-4 col-12 d-flex df-col r-side">
                @include('frontend._partials.league.right-ffp-team', ['league_name' => $league_this_web_short_name, 'team_name' => $thai_short_name, 'url' => $league_url])
                @include('frontend._partials.league.right-latest-transfer', ['league_name' => $league_this_web_short_name, 'team_name' => $thai_short_name, 'url' => $league_url])
                {{-- @include('frontend._partials.league.widget-league') --}}
                @include('frontend._partials.league.team-timeline', ['league_name' => $league_thai_name, 'team_name' => $thai_short_name, 'url' => $league_url])
                @include('frontend._partials.league.right-team-table', ['league_name' => $league_thai_name, 'team_name' => $thai_long_name, 'url' => $league_url])
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>

<input type="hidden" id="team_id" value="{{ $team_id }}">
<input type="hidden" id="league_id" value="{{ $league_id }}">
<input type="hidden" id="league_scraper_name" value="{{ $league_scraper_name }}">
<input type="hidden" id="league_alias_name" value="{{ ucwords(strtolower($league_scraper_name)) }}">
<input type="hidden" id="real_team_name" value="{{ $real_team_name }}">
<input type="hidden" id="thai_name" value="{{ $thai_name }}">
<input type="hidden" id="years" value="{{ $years }}">
<input type="hidden" id="this_year" value="{{ Date('Y') }}" />
<input type="hidden" id="image_shoe" value="{{ asset('images/stud-shoe.png') }}">
<input type="hidden" id="image_yellow_card" value="{{ asset('images/yellow-card.png') }}">
<input type="hidden" id="image_red_card" value="{{ asset('images/red-card.png') }}">
<input type="hidden" id="image_box" value="{{ asset('images/box-square.png') }}">

<input type="hidden" id="http_host" value="{{ $this_host }}" />
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/teams.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/team-score.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/team-player-stats.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/player-transfer.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/team-squad.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/team-ffp.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/result-program.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-teams/team-table.js?' . time()) }}"></script>

    <script>
        var thisHost = $('#http_host').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var teamId = $('#team_id').val();
        var leagueId = $('#league_id').val();
        var leagueAliasName = $('#league_alias_name').val();
        var teamName = $('#real_team_name').val();
        var thaiName = $('#thai_name').val();
        var noImage = "'images/no-image.jpg'";
        var fakeIp = '58.18.145.72';

        var teamAllYears = []; // topic 9 (frontend\js\league-teams\player-transfer.js)
        var alreadyLoad = [];
        var alreadyLoadTeamScore = [];
        var alreadyLoadTeamPlayerStats = [];
        var alreadyLoadSquad = [];

        var fixtureDatas = null;
        var fxYear = {};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            clearSlide();

            $('.row-live-ele').on('click', function(ele){
                var content = $(this).find('.live-ball');
                content.slideToggle('medium');
            });

            // topic 7
            $('.team-score .nav-link').on('click', function(){
                var year = $(this).attr('data-year');
                var lId = $(this).attr('league-id');
                console.log('team-score', year, lId);

                if ($.inArray(year, alreadyLoadTeamScore) === -1) {
                    alreadyLoadTeamScore.push(year);
                    teamScoreRepeat(lId, parseInt(year)); // finished
                }
            });

            // topic 8
            $('.team-player-stats .nav-link').on('click', function(){
                var year = $(this).attr('data-year');
                var lId = $(this).attr('league-id');
                console.log('team-player-stats', year, lId);

                if ($.inArray(year, alreadyLoadTeamPlayerStats) === -1) {
                    alreadyLoadTeamPlayerStats.push(year);
                    repeatLoadTeamPlayerStats(lId, year)
                }
            });

            // topic 9
            $('.player-tsf .nav-link').on('click', function(){
                var year = $(this).attr('data-year');

                if ($.inArray(year, alreadyLoad) === -1) {
                    alreadyLoad.push(year);
                    teamInYear(year, 'main'); // finished
                }
            });

            // topic 10
            $('.team-members .nav-link').on('click', function(){
                var year = $(this).attr('data-year');
                var lId = $(this).attr('league-id');
                console.log('team-members', year, lId);

                if ($.inArray(year, alreadyLoadSquad) === -1) {
                    alreadyLoadSquad.push(year);
                    teamSquadRepeat(lId, year); // finished
                }
            });

            alreadyLoadTeamScore.push('2020');
            alreadyLoadTeamPlayerStats.push('2020');
            alreadyLoad.push('2020');

            loadTeamDatas(teamId); // for topic 5, 6
            teamScore(leagueId, teamId, $('#this_year').val()); // topic 7
            loadTeamPlayerStats(teamId, $('#this_year').val()); // topic 8
            teamInfo(teamId, $('#this_year').val()); // topic 9, slider (league-teams/player-transfer.js)
            teamSquad(teamId, $('#this_year').val()); // topic 10
            // coachInfo(teamId, $('#this_year').val()); // topic 10

            predictionTeamDatas(fakeIp, apiHost, $('#league_scraper_name').val(), $('#real_team_name').val());
            teamTable(leagueId);
        });

        function loadTeamDatas(teamId) {
            var params = {team_id: teamId};

            $.ajax({
                url: $('#base_url').val() + '/api/fixtures-team',
                type: 'GET',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (fixtures) {
                    if (fixtures.length > 0) {
                        displayMatchInfo(fixtures); // topic 5
                        teamScoreResult(fixtures); // topic 6
                        genResultProgram(fixtures); // league-teams/result-program.js
                    } else {
                        console.log('--- no data for team id: ' + teamId);
                        $('.hide-if-load-complete').remove();
                        var noData = '<h5 class="text-center text-muted">--- ไม่มีข้อมูล ---</h5>';
                        $('.h2h-total').html(noData);
                        $('.h2h-home').html(noData);
                        $('.h2h-away').html(noData);

                        $('.team-timeline-box .graph-loading').remove();
                        $('.team-timeline-box').html(noData);
                    }
                },
                error: function(response) {
                    console.log('--- Failed to load team data: ' + leagueId + ' ---');
                    $('.hide-if-load-complete').remove();
                    var noData = '<h5 class="text-center text-muted">--- ไม่มีข้อมูล ---</h5>';
                    $('.h2h-total').html(noData);
                    $('.h2h-home').html(noData);
                    $('.h2h-away').html(noData);

                    $('.team-timeline-box .graph-loading').remove();
                    $('.team-timeline-box').html(noData);
                }
            });
        }

        /*
        function filterBigLeague(datas) {
            $.ajax({
                url: $('#base_url').val() + '/api/find-big-league',
                type: 'GET',
                dataType: 'json',
                cache: false,
                success: function (datas) {
                    if (datas.length > 0) {
                        var row = null;
                        var leagueName = '';
                        var bundesliga, championship, thai;

                        for (var i = 0; i < datas.length; i++) {
                            row = datas[i];

                            // console.log(row);
                            leagueName = row.name;
                            // laliga = str.includes('Primera Division');
                            bundesliga = leagueName.includes('Bundesliga');
                            championship = leagueName.includes('Championship');
                            thai = leagueName.includes('Thai'); // Thai Premier League

                            if (leagueName == 'Primera Division'
                                || (leagueName == 'Premier League' && row.country == 'England')
                                || leagueName == 'Serie A' // calcio
                                || leagueName == 'J.League'
                                || leagueName == 'Ligue 1'
                                || bundesliga || championship || thai) {
                                console.log(row);
                            }
                        }
                    } else {
                        console.log('--- no data found ---');
                    }
                },
                error: function(response) {
                    console.log('--- Failed to call ---');
                }
            });
        }*/
    </script>
@stop