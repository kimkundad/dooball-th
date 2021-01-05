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
    <meta property="og:url"				content="https://dooball-th.com/ราคาบอลไหล?link={{ $link }}">
@endsection
@section('fb_title')
    <meta property="og:title"			content="{{ $seo_title }}">
@endsection
@section('fb_description')
    <meta property="og:description"		content="{{ $seo_description }}">
@endsection
@section('fb_image')
    <meta property="og:image" 			content="https://dooball-th.com/images/social/flowball_1200x600.jpg">
@endsection
@section('tw_title')
    <meta name="twitter:title"			content="{{ $seo_title }}">
@endsection
@section('tw_description')
    <meta name="twitter:description"	content="{{ $seo_description }}">
@endsection
@section('tw_image')
    <meta name="twitter:image" content="https://dooball-th.com/images/flowball_800x418.jpg">
@endsection
@section('global_url')
    <link href="https://dooball-th.com/ราคาบอลไหล?link={{ $link }}" rel="canonical">
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
                {{-- <h1 class="prediction-title">ราคาบอลไหล {{ $home_team }} {{ $away_team }} {{ $league_name }} แข่ง {{ $event_time }} คืนนี้</h1> --}}
                <h1 class="prediction-title">{{ $h_one }}</h1>
                <input type="hidden" id="detail_id" value="{{ $link }}">

                <div class="price-graph">
                    <div class="graph-loading">
                        <div class="l-one"></div>
                        <div class="l-two"></div>
                    </div>

                    <div class="d-flex just-center league-info">
                        <div class="team-logo hm d-flex df-col alit-center">
                            <img src="{{ asset('images/logo-team.jpg') }}" alt="" class="img-fluid">
                            <a href="javascript:void(0)" class="btn active-gd no-round no-border text-white mt-2">สถิติ {{ $home_team }}</a>
                        </div>
                        <div class="match-info d-flex df-col">
                            <div class="vs-info d-flex just-between">
                                <span class="h">{{ $home_team }}</span>
                                <span class="mvs text-center text-target"></span>
                                <span class="a">{{ $away_team }}</span>
                            </div>
                            <div class="l-info d-flex alit-center just-center">
                                <div class="l-name">{{ $league_name }} : </div>
                                <div class="ev-time">{{ $event_time }}</div>
                            </div>
                            <div class="score-info text-center"></div>
                            <div class="close-price text-center text-success">(ราคาปิด)</div>
                            <div class="link-to-game text-center">
                                <a href="{{ url('game') }}" class="btn active-gd no-round text-white">
                                    เล่นเกมทายผลบอลคู่นี้แจกฟรีเครดิต</a>
                            </div>
                        </div>
                        <div class="team-logo aw d-flex df-col alit-center">
                            <img src="{{ asset('images/logo-team.jpg') }}" alt="" class="img-fluid">
                            <a href="javascript:void(0)" class="btn active-gd no-round no-border text-white mt-2">สถิติ {{ $away_team }}</a>
                        </div>
                    </div>

                    <div class="card crd-content">
                        <div class="card-header">
                            <h4 class="top-head-graph">
                                <span id="asian_top_title">ราคาบอลไหล เอเชียแฮนดิแคป (Asia Handicap)</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            {{-- <div class="asian-content-box"></div> --}}
                            <div id="asian_graph" class="graph-layout"></div>
                        </div>
                    </div>

                    <div class="card crd-content">
                        <div class="card-header">
                            <h4 class="top-head-graph">
                                <span id="over_top_title">ราคาบอลไหล สูงต่ำ (Over/Under)</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            {{-- <div class="over-content-box"></div> --}}
                            <div id="over_graph" class="graph-layout"></div>
                        </div>
                    </div>

                    <div class="card crd-content">
                        <div class="card-header">
                            <h4 class="top-head-graph">
                                <span id="one_top_title">ราคาบอลไหล ฟิกอ๊อด ผบแพ้ชนะ (1x2) (Fixed Odds)</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            {{-- <div class="one-content-box"></div> --}}
                            <div id="one_graph" class="graph-layout"></div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <h2>{{ $bottom_htwo }}</h2>
                <p class="c-content">
                    {!! $bottom_content_first !!}
                </p>

                <h2>สอนวิธีดู ราคาไหล</h2>
                <p class="c-content">
                    {!! $bottom_content_second !!}
                </p>
                <ul class="c-content">
                    <li>
                        กราฟบอลไหล อัตราต่อรอง
                        กราฟนี้จะแสดงค่าของทีมต่อขึ้นมาเป็นหลัก
                        <span class="text-bold text-white">หากกราฟมีการกระดกขึ้น</span> หมายถึง ราคาต่อ
                        มีค่าน้ำราคาบอลที่มากขึ้น นั่นคือ ราคาไหลลง ทีมรอง
                        แสดงว่าทีมรองมีจำนวนผู้เดิมพันที่มากกว่า หรือมีการเปลี่ยนแปลง
                        ตัวผู้เล่นหรือปัจจัยอื่นๆที่ทำให้ฝั่งบอลรองได้เปรียบมากยิ่งขึ้น
                        จะเรียกว่า ราคาไหลรอง! ตรงกันข้าม
                        <span class="text-bold text-white">หากกราฟกระดกหกหัวลงมา</span> มีค่าน้ำราคาบอลที่น้อยลง นั่นคือ
                        ราคาบอลไหลต่อ อธิบายได้ก็คือ ราคาไหลลง ทีมต่อ
                        แสดงว่าทีมต่อมีจำนวนผู้เดิมพันที่มากกว่า หรือมีการเปลี่ยนแปลง
                        ตัวผู้เล่นหรือปัจจัยอื่นๆที่ทำให้ฝั่งบอลต่อได้เปรียบมากยิ่งขึ้น
                    </li>
                    <li>
                        กราฟ ราคาบอลไหลสูงต่ำ กราฟนี้แสดง ค่า Under
                        <span class="text-bold text-white">หากกราฟมีการกระดกขึ้น</span> หมายถึง ค่าน้ำราคา เดิมพันฝั่งต่ำ มีค่า
                        มากขึ้น แสดงว่า ราคาบอลไหลสูงต่ำ กำลังไหลไปทางฝั่ง
                        บอลสูงนั่นเอง
                        <span class="text-bold text-white">หากกราฟกระดกหกหัวลงมา</span> หมายถึง ค่าน้ำราคา เดิมพันฝั่งสูง มีค่า
                        น้อยลง แสดงว่า ราคาบอลไหลสูงต่ำ กำลังไหลไปทางฝั่ง
                        บอลต่ำนั่นเอง
                    </li>
                    <li>กราฟราคาบอล 1*2 ราคาบอลแพ้ชนะ</li>
                </ul>
                <p class="c-content">
                    {!! $last_content !!}
                </p>
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
    <input type="hidden" id="run_as" value="{{ env('RUN_AS') }}">
    <input type="hidden" id="http_host" value="{{ $this_host }}" />
    <input type="hidden" id="api_host" value="{{ env('SCRAP_PRICE') }}">
</div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('frontend/js/jquery-min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script>
        var runAs = $('#run_as').val();
        var thisHost = $('#http_host').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var fakeIp = '58.18.145.72';

        $(function() {
            clearSlide();

            if (runAs === 'local') {
                callGraphApi(fakeIp, 'http://localhost');
                callContentApi(fakeIp, 'http://localhost');
            } else {
                $.getJSON("https://api.ipify.org?format=json", function(data) {
                    // console.log(data.ip);
                    if (data.ip) {
                        callGraphApi(data.ip, apiHost);
                        callContentApi(data.ip, apiHost);
                    }
                });
            }
        });


    
    </script>
@stop
