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
    <meta property="og:url"				content="https://dooball-th.com/{{ $league_url }}/topscore">
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
    <link href="https://dooball-th.com/{{ $league_url }}/topscore" rel="canonical">
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
                        <h2>สถิตินักเตะ ฟุตบอลพรีเมียร์ลีก อังกฤษ</h2>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs tsc-tab mt-1" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center active" id="star_tab" data-index="0" data-toggle="tab" href="#star_content" role="tab" aria-controls="star_content" aria-selected="true">
                                    <i class="fa fa-futbol"></i>
                                    <span class="ml-1 text-bold">ดาวซัลโว</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center" id="assist_tab" data-index="0" data-toggle="tab" href="#assist_content" role="tab" aria-controls="assist_content" aria-selected="true">
                                    <img src="{{ asset('images/stud-shoe.png') }}" alt="" width="25">
                                    <span class="ml-1 text-bold">แอสซิสต์</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center" id="chance_tab" data-index="0" data-toggle="tab" href="#chance_content" role="tab" aria-controls="chance_content" aria-selected="true">
                                    <i class="fa fa-walking"></i>
                                    <span class="ml-1 text-bold">โอกาสยิง</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center" id="frame_tab" data-index="0" data-toggle="tab" href="#frame_content" role="tab" aria-controls="frame_content" aria-selected="true">
                                    <img src="http://localhost/images/box-square.png">
                                    <span class="ml-1 text-bold">เข้ากรอบ</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center" id="yellow_tab" data-index="0" data-toggle="tab" href="#yellow_content" role="tab" aria-controls="yellow_content" aria-selected="true">
                                    <img src="http://localhost/images/yellow-card.png" width="25">
                                    <span class="ml-1 text-bold">ใบเหลือง</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center" id="red_tab" data-index="0" data-toggle="tab" href="#red_content" role="tab" aria-controls="red_content" aria-selected="true">
                                    <img src="http://localhost/images/red-card.png" width="25">
                                    <span class="ml-1 text-bold">ใบแดง</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex alit-center" id="time_tab" data-index="0" data-toggle="tab" href="#time_content" role="tab" aria-controls="time_content" aria-selected="true">
                                    <i class="far fa-clock"></i>
                                    <span class="ml-1 text-bold">เวลาลงเล่น</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="star_content" role="tabpanel" aria-labelledby="star_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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
                            <div class="tab-pane fade" id="assist_content" role="tabpanel" aria-labelledby="assist_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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
                            <div class="tab-pane fade" id="chance_content" role="tabpanel" aria-labelledby="chance_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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
                            <div class="tab-pane fade" id="frame_content" role="tabpanel" aria-labelledby="frame_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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
                            <div class="tab-pane fade" id="yellow_content" role="tabpanel" aria-labelledby="yellow_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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
                            <div class="tab-pane fade" id="red_content" role="tabpanel" aria-labelledby="red_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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
                            <div class="tab-pane fade" id="time_content" role="tabpanel" aria-labelledby="time_tab">
                                <div class="table-responsive">
                                    <table class="table table-condensed topscore-table text-white">
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

            $('.row-live-ele').on('click', function(ele){
                var content = $(this).find('.live-ball');
                content.slideToggle('medium');
            });

            loadPlayerStats(524);

            predictionDatas(fakeIp, apiHost, $('#league_scraper_name').val());
        });
    </script>
@stop