@extends('frontend.layouts.public')

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

@section('custom-css')
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/welcome.css') }}">
@endsection

@section('content')
  <div class="body-wrap boxed-container">
    <header class="site-header">
        <div class="container">
            <div class="site-header-inner">
                <div class="brand header-brand">
                    <a href="{{ URL::to('/') }}">
                        <img src="{{ $web_image }}" width="250" alt="">
                    </a>
                </div>
            </div>
        </div>
    </header>

    @include('frontend.layouts.navbar')

    <div class="left-side-banner">
        @if($widget->floating_left)
            @foreach($widget->floating_left as $data)
                @if($data->active_status)
                    @if($data->show_title_name)
                        {{ $data->title }}
                    @endif
                    {!! $data->detail !!}
                @endif
            @endforeach
        @endif
    </div>
    <div class="right-side-banner">
        @if($widget->floating_right)
            @foreach($widget->floating_right as $data)
                @if($data->active_status)
                    @if($data->show_title_name)
                        {{ $data->title }}
                    @endif
                    {!! $data->detail !!}
                @endif
            @endforeach
        @endif
    </div>
    <main>
        <section class="text-center">
            <div class="container-sm">
                <div class="hero-inner">
                    <h1 class="hero-title h2-mobile mt-0 is-revealing">{{ $page_topic }}</h1>
                    <p class="hero-paragraph is-revealing">{{ $page_description }}</p>
                </div>

                <div class="hero-inner">
                    <div class="db-collapse">
                        <div class="db-match match-0">
                            <span class="match-time">วันเวลา</span>
                            <span class="home-team">ทีมเหย้า</span>
                            <span class="vs">Vs</span>
                            <span class="away-team">ทีมเยือน</span>
                            <span class="league-name">ชื่อลีก</span>
                        </div>
                    </div>
                    @if($matchDatas)
                        @if ($matchDatas['total'] > 0)
                            @foreach($matchDatas['records'] as $k => $val)
                                <div class="db-collapse">
                                    <div class="db-match match-{{ ($k+1) }}">
                                        <span class="match-time">{{ $val->match_time }}</span>
                                        <span class="home-team">{{ $val->home_team }}</span>
                                        <span class="vs">Vs</span>
                                        <span class="away-team">{{ $val->away_team }}</span>
                                        <span class="league-name">{{ $val->match_name }}</span>
                                    </div>
                                    <div class="db-content content-{{ ($k+1) }}">
                                        @if($val->sponsor_links)
                                            @foreach($val->sponsor_links as $ele)
                                                <p><a href="{{ $ele->url }}" target="_BLANK">{{ $ele->name }}</a></p>
                                            @endforeach
                                        @endif
                                        @if($val->normal_links)
                                            @foreach($val->normal_links as $e)
                                                <p><a href="{{ $e->url }}" target="_BLANK">{{ $e->name }}</a></p>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-16">-- วันนี้ไม่มีรายการ {{ $not_found_message }} แข่งค่ะ --</div>
                        @endif
                    @else
                        <div class="p-16">-- วันนี้ไม่มีแข่งค่ะ --</div>
                    @endif
                </div>
                <div class="hero-browser pdd-15 round bg-soft-grey welcome-detail">
                    {!! $page_detail !!}
                </div>
            </div>
        </section>
    </main>
    @include('frontend.layouts.footer')
  </div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{asset('frontend/js/jquery-min.js')}}"></script>
  <script>
    // $('#search_land').keyup(function(e){
    //   if (e.keyCode == 13) {
    //     $(this).trigger("enterKey");
    //   }
    // });

    $(function() {
        $('.db-collapse').each(function(index) {
            // console.log( index + ": " + $( this ).text() );
            $('.match-' + index).click(function(){
                $('.content-' + index).slideToggle('medium');
            });
        });
    });
  </script>
@stop