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
    <meta property="og:url"				content="https://dooball-th.com/{{ $league_url }}/transfer">
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
    <link href="https://dooball-th.com/{{ $league_url }}/transfer" rel="canonical">
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
                    <div class="card-header card-prediction no-round no-border text-white">
                        <h2>ตลาดซื้อขายนักเตะพรีเมียร์ลีก ล่าสุด</h2>
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

<input type="hidden" id="league_scraper_name" value="{{ $league_scraper_name }}" />
<input type="hidden" id="years" value="{{ $years }}">
<input type="hidden" id="this_year" value="{{ Date('Y') }}" />
<input type="hidden" id="this_month" value="{{ Date('m') }}" />
<input type="hidden" id="http_host" value="{{ $this_host }}" />
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-transfer/player-transfer.js?' . time()) }}"></script>
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

            // $('.nav-item.month-list .nav-link').on('click', function() {
            //     loadTabContent($(this).attr('id'), $(this).attr('data-index'));
            // });

            loadPlayerTransfer(524);

            // loadTabContent($('.nav-item.month-list:first-child .nav-link').attr('id'), 0);

            predictionDatas(fakeIp, apiHost, $('#league_scraper_name').val());
        });
    </script>
@stop