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
    <meta property="og:url"				content="https://dooball-th.com/{{ $league_url }}/table">
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
    <link href="https://dooball-th.com/{{ $league_url }}/table" rel="canonical">
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
                        <ul class="nav nav-tabs tsc-tab mt-1" id="myTab" role="tablist">
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center active" id="tab_2019-2020" league-id="524" data-toggle="tab" href="#content_2019-2020" role="tab" aria-controls="content_2019-2020" aria-selected="true">
                                    <span class="ml-1 text-bold">2019-20</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2018-2019" league-id="2" data-toggle="tab" href="#content_2018-2019" role="tab" aria-controls="content_2018-2019" aria-selected="true">
                                    <span class="ml-1 text-bold">2018-19</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2017-2018" league-id="37" data-toggle="tab" href="#content_2017-2018" role="tab" aria-controls="content_2017-2018" aria-selected="true">
                                    <span class="ml-1 text-bold">2017-18</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2016-2017" league-id="56" data-toggle="tab" href="#content_2016-2017" role="tab" aria-controls="content_2016-2017" aria-selected="true">
                                    <span class="ml-1 text-bold">2016-17</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2015-2016" league-id="696" data-toggle="tab" href="#content_2015-2016" role="tab" aria-controls="content_2015-2016" aria-selected="true">
                                    <span class="ml-1 text-bold">2015-16</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2014-2015" league-id="697" data-toggle="tab" href="#content_2014-2015" role="tab" aria-controls="content_2014-2015" aria-selected="true">
                                    <span class="ml-1 text-bold">2014-15</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2013-2014" league-id="698" data-toggle="tab" href="#content_2013-2014" role="tab" aria-controls="content_2013-2014" aria-selected="true">
                                    <span class="ml-1 text-bold">2013-14</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2012-2013" league-id="699" data-toggle="tab" href="#content_2012-2013" role="tab" aria-controls="content_2012-2013" aria-selected="true">
                                    <span class="ml-1 text-bold">2012-13</span>
                                </a>
                            </li>
                            <li class="nav-item table-list">
                                <a class="nav-link d-flex alit-center" id="tab_2011-2012" league-id="700" data-toggle="tab" href="#content_2011-2012" role="tab" aria-controls="content_2011-2012" aria-selected="true">
                                    <span class="ml-1 text-bold">2011-12</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="content_2019-2020" role="tabpanel" aria-labelledby="tab_2019-2020">
                                @include('frontend._partials.league.league-table', ['year' => '2019-2020'])
                            </div>
                            <div class="tab-pane fade" id="content_2018-2019" role="tabpanel" aria-labelledby="tab_2018-2019">
                                @include('frontend._partials.league.league-table', ['year' => '2018-2019'])
                            </div>
                            <div class="tab-pane fade" id="content_2017-2018" role="tabpanel" aria-labelledby="tab_2017-2018">
                                @include('frontend._partials.league.league-table', ['year' => '2017-2018'])
                            </div>
                            <div class="tab-pane fade" id="content_2016-2017" role="tabpanel" aria-labelledby="tab_2016-2017">
                                @include('frontend._partials.league.league-table', ['year' => '2016-2017'])
                            </div>
                            <div class="tab-pane fade" id="content_2015-2016" role="tabpanel" aria-labelledby="tab_2015-2016">
                                @include('frontend._partials.league.league-table', ['year' => '2015-2016'])
                            </div>
                            <div class="tab-pane fade" id="content_2014-2015" role="tabpanel" aria-labelledby="tab_2014-2015">
                                @include('frontend._partials.league.league-table', ['year' => '2014-2015'])
                            </div>
                            <div class="tab-pane fade" id="content_2013-2014" role="tabpanel" aria-labelledby="tab_2013-2014">
                                @include('frontend._partials.league.league-table', ['year' => '2013-2014'])
                            </div>
                            <div class="tab-pane fade" id="content_2012-2013" role="tabpanel" aria-labelledby="tab_2012-2013">
                                @include('frontend._partials.league.league-table', ['year' => '2012-2013'])
                            </div>
                            <div class="tab-pane fade" id="content_2011-2012" role="tabpanel" aria-labelledby="tab_2011-2012">
                                @include('frontend._partials.league.league-table', ['year' => '2011-2012'])
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
<input type="hidden" id="league_ids" value="{{ $league_ids }}" />
<input type="hidden" id="image_shoe" value="{{ asset('images/stud-shoe.png') }}">
<input type="hidden" id="image_yellow_card" value="{{ asset('images/yellow-card.png') }}">
<input type="hidden" id="image_red_card" value="{{ asset('images/red-card.png') }}">
<input type="hidden" id="image_box" value="{{ asset('images/box-square.png') }}">

<input type="hidden" id="http_host" value="{{ $this_host }}" />
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-table/table-list.js?' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/league-page/prediction-datas.js?' . time()) }}"></script>
    <script>
        var thisHost = $('#http_host').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var years = $('#league_ids').val();
        var fakeIp = '58.18.145.72';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            clearSlide();

            var leagueId = 0;
            var fullId = '';
            var duration = '';

            $('.nav-item.table-list .nav-link').on('click', function() {
                // fullId = $(this).attr('id');
                // duration = fullId.split('_')[1];
                // leagueId = $(this).attr('league-id');
                // loadLeagueTable(leagueId, duration);
            });

            var yearList = JSON.parse(years);

            for (const key in yearList) {
                // console.log(key, yearList[key], (key - 1) + '-' + key);
                loadLeagueTable(yearList[key], (key - 1) + '-' + key);
            }

            predictionDatas(fakeIp, apiHost, $('#league_scraper_name').val());
        });
    </script>
@stop