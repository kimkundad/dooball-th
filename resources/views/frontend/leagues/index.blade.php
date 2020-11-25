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
    <meta property="og:url"				content="https://dooball-th.com/{{ $league_url }}">
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
    <link href="https://dooball-th.com/{{ $league_url }}" rel="canonical">
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

                @include('frontend._partials.new-theme.match', ['message' => 'วันนี้ไม่มีการแข่งขัน' . $league_name . ' ค่ะ'])

                <div class="card my-4 no-round no-border bg-trans">
                    <div class="card-header card-prediction no-round no-border text-white">
                        <h2>สถิตินักเตะ {{ $league_name }}</h2>
                    </div>
                    <div class="card-body player-stats no-padd no-round no-border d-flex df-col">
                        <div class="graph-loading">
                            <div class="l-one"></div>
                            <div class="l-two"></div>
                        </div>
                    </div>
                    <div class="card-footer no-round no-border text-center">
                        <a href="{{ url($league_url . '/topscore') }}" class="btn active-gd mt-4 text-white no-round text-ok">สถิตินักเตะทั้งหมด ใน{{ $league_name }}</a>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="card no-round no-border bg-trans">
                    <div class="card-header card-prediction no-round no-border text-white">
                        <h2>ตลาดซื้อขายนักเตะ{{ $league_short_name }} ล่าสุด</h2>
                    </div>
                    <div class="card-body no-round no-border no-padd">
                        @if (count($tab_list) > 0)
                            <ul class="nav nav-tabs mt-1" id="myTab" role="tablist">
                                @foreach($tab_list as $k => $year)
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($k == 0) ? 'active' : '' }}" id="y{{ $year }}-tab" data-toggle="tab" href="#y{{ $year }}" role="tab" aria-controls="y{{ $year }}" aria-selected="true">
                                            {{ $year }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="pml_tab_content">
                                <div class="graph-loading">
                                    <div class="l-one"></div>
                                    <div class="l-two"></div>
                                </div>

                                @foreach($tab_list as $m => $year)
                                    <div class="tab-pane fade {{ ($m == 0) ? 'show active' : '' }}" id="y{{ $year }}" role="tabpanel" aria-labelledby="y{{ $year }}-tab">
                                        <div class="table-responsive">
                                            <table class="table table-condensed">
                                                <thead class="th-transfer">
                                                    <tr class="league-name text-white">
                                                        <th class="text-bold font-italic text-bigger">นักเตะ</th>
                                                        <th class="text-bold font-italic text-center text-bigger">ย้ายจาก</th>
                                                        <th class="text-bold font-italic text-center text-bigger">เข้าร่วม</th>
                                                        <th class="text-bold font-italic text-center text-bigger">มูลค่าซื้อขาย</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-y{{ $year }} tb-transfer"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="card-footer no-round no-border text-center">
                        <a href="{{ url($league_url . '/transfer') }}" class="btn active-gd mt-4 text-white no-round text-ok">การซื้อขายนักเตะทั้งหมด ใน{{ $league_short_name }}</a>
                    </div>
                </div>
                
                <div class="card no-round no-border bg-trans">
                    <div class="card-header card-prediction no-round no-border text-white">
                        <h2>สโมสรฟุตบอลใน{{ $league_name }}</h2>
                    </div>
                    <div class="card-body no-round no-border no-padd">
                        @include('frontend._partials.league.market-slider')
                    </div>
                </div>

                <div class="card no-round no-border bg-trans">
                    <div class="card-header card-prediction no-round no-border text-white">
                        <h2>ตารางคะแนน{{ $league_short_name }}</h2>
                    </div>
                    <div class="card-body no-round no-border no-padd">
                        @include('frontend._partials.h2h.league-table-tab')
                    </div>
                    <div class="card-footer no-round no-border text-center">
                        <a href="{{ url($league_url . '/table') }}" class="btn active-gd text-white no-round text-ok">ตารางคะแนน{{ $league_short_name }}ทั้งหมด</a>
                    </div>
                </div>

                <br>
                {!! $bottom_content !!}
            </div>
            <div class="col-lg-4 col-md-4 col-12 d-flex df-col r-side">
                @include('frontend._partials.league.right-ffp', ['league_name' => $league_short_name, 'url' => $league_url])
                @include('frontend._partials.league.right-result', ['league_name' => $league_name, 'league_short_name' => $league_short_name, 'url' => $league_url])
                @include('frontend._partials.league.right-adv', ['league_name' => $league_name, 'league_short_name' => $league_short_name, 'url' => $league_url])
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>
<input type="hidden" id="http_host" value="{{ $this_host }}" />

<input type="hidden" id="league_id" value="{{ $league_id }}" />
<input type="hidden" id="league_scraper_name" value="{{ $league_scraper_name }}" />
<input type="hidden" id="hist_one" value="{{ $hist_one }}" />
<input type="hidden" id="hist_one_text" value="{{ $hist_one_text }}" />
<input type="hidden" id="hist_two" value="{{ $hist_two }}" />
<input type="hidden" id="hist_two_text" value="{{ $hist_two_text }}" />
<input type="hidden" id="hist_three" value="{{ $hist_three }}" />
<input type="hidden" id="hist_three_text" value="{{ $hist_three_text }}" />
<input type="hidden" id="hist_four" value="{{ $hist_four }}" />
<input type="hidden" id="hist_four_text" value="{{ $hist_four_text }}" />
<input type="hidden" id="hist_five" value="{{ $hist_five }}" />
<input type="hidden" id="hist_five_text" value="{{ $hist_five_text }}" />

<input type="hidden" id="adv_one" value="{{ $adv_one }}" />
<input type="hidden" id="adv_one_text" value="{{ $adv_one_text }}" />
<input type="hidden" id="adv_two" value="{{ $adv_two }}" />
<input type="hidden" id="adv_two_text" value="{{ $adv_two_text }}" />
<input type="hidden" id="adv_three" value="{{ $adv_three }}" />
<input type="hidden" id="adv_three_text" value="{{ $adv_three_text }}" />
<input type="hidden" id="adv_four" value="{{ $adv_four }}" />
<input type="hidden" id="adv_four_text" value="{{ $adv_four_text }}" />
<input type="hidden" id="adv_five" value="{{ $adv_five }}" />
<input type="hidden" id="adv_five_text" value="{{ $adv_five_text }}" />

<input type="hidden" id="years" value="{{ $years }}">

<input type="hidden" id="image_shoe" value="{{ asset('images/stud-shoe.png') }}">
<input type="hidden" id="image_yellow_card" value="{{ asset('images/yellow-card.png') }}">
<input type="hidden" id="image_red_card" value="{{ asset('images/red-card.png') }}">
<input type="hidden" id="image_box" value="{{ asset('images/box-square.png') }}">
<input type="hidden" id="date_search" value="{{ Date('Y-m-d') }}">

@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/player-stats.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/market-slider.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/player-transfer.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/league-table.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/prediction-datas.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/history-league.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/advance-league.js?' . time()) }}"></script>
    <script>
        var thisHost = $('#http_host').val();
        var leagueId = $('#league_id').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var fakeIp = '58.18.145.72';

        var teamFullDatas = [];

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

            $('.nav-item .nav-link').on('click', function() {
                // console.log($(this).attr('id'));
            });

            loadPlayerStats(leagueId);
            oneCallLeague(leagueId);
            loadLeagueTable(leagueId, '', '');

            predictionDatas(fakeIp, apiHost, $('#league_scraper_name').val());
            histData(1, leagueId);
            advanceData(1, leagueId);

            // findLeagueId();
        });

        function oneCallLeague(leagueId) {
            var params = {league_id: leagueId};

            $.ajax({
                url: $('#base_url').val() + '/api/leagueDatas',
                type: 'GET',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (fixtures) {
                    if (fixtures.length > 0) {
                        loadMarketSlider(fixtures);
                        leagueYearGroup(fixtures);
                    } else {
                        $('.tb-transfer').css('visibility', 'visible');
                        $('.tb-transfer').html('<tr><td colspan="4"><h5 class="text-muted mt-2 text-center">--- ไม่มีข้อมูลการซื้อขาย ---</h5></td></tr>');
                    }
                },
                error: function(response) {
                    console.log('--- Failed to load league id: ' + leagueId + ' ---');
                    $('.tb-transfer').css('visibility', 'visible');
                    $('#pml_tab_content .graph-loading').remove();
                    $('.tb-transfer').html('<tr><td colspan="4"><h5 class="text-muted mt-2 text-center">--- ไม่มีข้อมูลการซื้อขาย ---</h5></td></tr>');
                }
            });
        }

        /*
        function findLeagueId() {
            var params = {date_search: $('#date_search').val()};

            $.ajax({
                url: $('#base_url').val() + '/api/find-league-id',
                type: 'GET',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (fixtures) {
                    if (fixtures.length > 0) {
                        var leagueId = 0;
                        var row = null;

                        for (var i = 0; i < fixtures.length; i++) {
                            row = fixtures[i];

                            // if (row.league.name == 'Primera Division'
                            //     || (row.league.name == 'Premier League' && row.league.country == 'England')
                            //     || row.league.name == 'Serie A'
                            //     || row.league.name == 'Primera Division'
                            //     || row.league.name == 'Bundesliga 2') {
                            //     console.log(row.league_id, row.league.name, row.league.country);
                            // }
                        }
                    } else {
                        var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                        $('.livescore-table').html(tr);
                    }
                },
                error: function(response) {
                    console.log('--- Failed to load league id by date: ' + $('#date_search').val());
                }
            });
        }*/
    </script>
@stop