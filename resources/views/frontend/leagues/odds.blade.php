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
    <meta property="og:url"				content="https://dooball-th.com/{{ $league_url }}/odds">
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
    <link href="https://dooball-th.com/{{ $league_url }}/odds" rel="canonical">
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

                <div class="clearfix"></div>

                <div class="card no-round no-border bg-trans">
                    <div class="card-body">
                        @if (count($tab_list) > 0)
                            <ul class="nav nav-tabs tsc-tab mt-1" id="myTab" role="tablist">
                                @foreach($tab_list as $k => $tab)
                                    <li class="nav-item">
                                        <a class="nav-link d-flex alit-center {{ (($k == 0)? 'active' : '') }}" id="odds-{{ $tab['date'] }}" data-index="0" data-toggle="tab" href="#odds_{{ $tab['date'] }}" role="tab" aria-controls="odds_{{ $tab['date'] }}" aria-selected="true">
                                            <span class="text-bold">{{ $tab['date_format'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach($tab_list as $ktct => $tab)
                                    <div class="tab-pane fade {{ (($ktct == 0)? 'show active' : '') }}" id="odds_{{ $tab['date'] }}" role="tabpanel" aria-labelledby="odds-{{ $tab['date'] }}">
                                        <div class="table-responsive">
                                            <table class="table table-condensed odds-table text-white">
                                                <thead>
                                                    <tr class="card-prediction">
                                                        <th>เวลาแข่ง</th>
                                                        <th>ทีมเหย้า</th>
                                                        <th>ราคา@ค่าน้ำ</th>
                                                        <th class="not-mb">VS</th>
                                                        <th>ทีมเยือน</th>
                                                        <th>% ราคาไหล</th>
                                                        <th>ราคาบอลไหล</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="league-name">
                                                        <td colspan="7">{{ $league_scraper_name }}</td>
                                                    </tr>

                                                    @if (count($tab['detail']['final_list']) > 0)
                                                        @foreach($tab['detail']['final_list'] as $match)
                                                            @php
                                                                $findHomeMinus = 0;
                                                                $homeRed = '';
                                                                $awayRed = '';
                                                                
                                                                if (count($match['left_list']) > 0) {
                                                                    foreach ($match['left_list'] as $inner) {
                                                                        if (array_key_exists(2, $inner)) {
                                                                            $strMid = $inner[1];

                                                                            if ((float) $strMid < 0) {
                                                                                $findHomeMinus++;
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                $homeRed = ($findHomeMinus > 0) ? 'text-danger' : '';
                                                                $awayRed = ($findHomeMinus == 0) ? 'text-danger' : '';
                                                            @endphp
                                                            <tr>
                                                                <td>{!! $match['date_time_before'] !!}</td>
                                                                <td>
                                                                    <div class="ha-team {{ $homeRed }}">{{ $match['left'][0] }}</div>
                                                                </td>
                                                                <td>
                                                                    @if (count($match['left_list']) > 0)
                                                                        @foreach($match['left_list'] as $inner)
                                                                            <div class="d-flex alit-center just-between narrow-in-mb">
                                                                                <div>
                                                                                    {!! ((array_key_exists(2, $inner)) ? $inner[1] : '<span class="text-bold">(แพ้/ชนะ)</span>') !!}
                                                                                </div>
                                                                                <div>
                                                                                    {!!  ((array_key_exists(2, $inner)) ? '@' . $inner[2] : '@' . $inner[1]) !!}
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>Vs</td>
                                                                <td>
                                                                    <div class="ha-team {{ $awayRed }}">{{ $match['right'][0] }}</div>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $water = ($match['datas']['asian']['water']) ? $match['datas']['asian']['water'] : 0;
                                                                        $lastWater = ($match['datas']['asian']['last_water']) ? $match['datas']['asian']['last_water'] : 0;
                                            
                                                                        $diff = $water - $lastWater;
                                                                        $sbdDiff = abs($diff);
                                                                        $divideWater = ($water - 1);
                                                                        $percentDiff = 0;
                                            
                                                                        if ($divideWater != 0) {
                                                                            $percentDiff = ($diff * 100 ) / $divideWater;
                                                                        }
                                                                        
                                                                        $color = 'text-white';
                                                                        
                                                                        if ($percentDiff > 0) {
                                                                            $color = 'text-success';
                                                                        } else if ($percentDiff < 0) {
                                                                            $color = 'text-danger';
                                                                        }
                                                                    @endphp
                                                                    <span class="{{ $color }} text-bold">{{ round($percentDiff) }}%</span>
                                                                    {{-- <div class="text-muted">{{ $percentDiff }}</div> --}}
                                                                </td>
                                                                <td>
                                                                    <div class="box-btn-link">
                                                                        @if ($match['detail_id'])
                                                                            <a href="{{ route('football-price-detail', 'link=' . $match['detail_id']) }}" class="btn btn-block active-gd no-round text-white" target="_BLANK">ดูราคา<br>บอลไหล</a>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="7" class="text-muted text-center">-- ไม่มีข้อมูล --</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="clearfix"></div>

                <br>
                {!! $bottom_content !!}
            </div>
            <div class="col-lg-4 col-md-4 col-12 d-flex df-col r-side">
                @include('frontend._partials.league.right-ffp', ['league_name' => $league_short_name, 'url' => $league_url])
                @include('frontend._partials.league.widget-league')
                @include('frontend._partials.article-sidebar.latest')
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>

<input type="hidden" id="league_scraper_name" value="{{ $league_scraper_name }}">
<input type="hidden" id="image_shoe" value="{{ asset('images/stud-shoe.png') }}">
<input type="hidden" id="image_yellow_card" value="{{ asset('images/yellow-card.png') }}">
<input type="hidden" id="image_red_card" value="{{ asset('images/red-card.png') }}">
<input type="hidden" id="image_box" value="{{ asset('images/box-square.png') }}">

<input type="hidden" id="http_host" value="{{ $this_host }}" />
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-topscore/player-stats.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/prediction-datas.js?' . time()) }}"></script>
    <script>
        var thisHost = $('#http_host').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var fakeIp = '58.18.145.72';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            clearSlide();

            loadPlayerStats(524);

            predictionDatas(fakeIp, apiHost, $('#league_scraper_name').val());
        });
    </script>
@stop