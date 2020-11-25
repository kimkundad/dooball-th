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
                        <img src="{{ $web_image }}" alt="">
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
                    <p class="hero-paragraph text-left is-revealing">{{ $page_description }}</p>
                  
                    @if($widget->top_banner)
                        @foreach($widget->top_banner as $data)
                            @if($data->active_status)
                                @if($data->show_title_name)
                                    {{ $data->title }}
                                @endif
                                {!! $data->detail !!}
                            @endif
                        @endforeach
                    @endif

                    @if($widget->home_top_banner)
                        @foreach($widget->home_top_banner as $data)
                            @if($data->active_status)
                                @if($data->show_title_name)
                                    {{ $data->title }}
                                @endif
                                {!! $data->detail !!}
                            @endif
                        @endforeach
                    @endif

                  
                  {{-- <div class="hero-form newsletter-form field field-grouped is-revealing">
                    <div class="control control-expanded">
                      <input class="input" type="email" name="email" placeholder="Your best email&hellip;">
                    </div>
                    <div class="control">
                      <a class="button button-primary button-block button-shadow" href="#">Get early access</a>
                    </div>
                  </div> --}}
                    <div class="hero-browser">
                        <div class="bubble-3 is-revealing">
                            <svg width="427" height="286" viewBox="0 0 427 286" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                    <path d="M213.5 286C331.413 286 427 190.413 427 72.5S304.221 16.45 186.309 16.45C68.396 16.45 0-45.414 0 72.5S95.587 286 213.5 286z" id="bubble-3-a"/>
                                </defs>
                                <g fill="none" fill-rule="evenodd">
                                    <mask id="bubble-3-b" fill="#fff">
                                        <use xlink:href="#bubble-3-a"/>
                                    </mask>
                                    <use fill="#4E8FF8" xlink:href="#bubble-3-a"/>
                                    <path d="M64.5 129.77c117.913 0 213.5-95.588 213.5-213.5 0-117.914-122.779-56.052-240.691-56.052C-80.604-139.782-149-201.644-149-83.73c0 117.913 95.587 213.5 213.5 213.5z" fill="#1274ED" mask="url(#bubble-3-b)"/>
                                    <path d="M381.5 501.77c117.913 0 213.5-95.588 213.5-213.5 0-117.914-122.779-56.052-240.691-56.052C236.396 232.218 168 170.356 168 288.27c0 117.913 95.587 213.5 213.5 213.5z" fill="#75ABF3" mask="url(#bubble-3-b)"/>
                                </g>
                            </svg>
                        </div>
                        <div class="bubble-4 is-revealing">
                            <svg width="230" height="235" viewBox="0 0 230 235" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                    <path d="M196.605 234.11C256.252 234.11 216 167.646 216 108 216 48.353 167.647 0 108 0S0 48.353 0 108s136.959 126.11 196.605 126.11z" id="bubble-4-a"/>
                                </defs>
                                <g fill="none" fill-rule="evenodd">
                                    <mask id="bubble-4-b" fill="#fff">
                                        <use xlink:href="#bubble-4-a"/>
                                    </mask>
                                    <use fill="#7CE8DD" xlink:href="#bubble-4-a"/>
                                    <circle fill="#3BDDCC" mask="url(#bubble-4-b)" cx="30" cy="108" r="108"/>
                                    <circle fill="#B1F1EA" opacity=".7" mask="url(#bubble-4-b)" cx="265" cy="88" r="108"/>
                                </g>
                            </svg>
                        </div>
                        <div class="hero-browser-inner is-revealing">
                            @if($connection_status == 1)
                                @include('frontend._partials.welcome.match')
                            @else
                                @include('frontend._partials.welcome.match-real')
                            @endif
                        </div>
                        <div class="bubble-2 is-revealing">
                            <svg width="179" height="126" viewBox="0 0 179 126" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                    <path d="M104.697 125.661c41.034 0 74.298-33.264 74.298-74.298s-43.231-7.425-84.265-7.425S0-28.44 0 12.593c0 41.034 63.663 113.068 104.697 113.068z" id="bubble-2-a"/>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <div class="hero-browser pdd-15 round bg-soft-grey welcome-detail">
                        {!! $page_detail !!}
                    </div>
                </div>
            </div>
        </section>

        <?php /*
        @if(count($articles) > 0)
            <section class="features section text-center">
                <div class="container-sm">
                    <div class="features-inner section-inner has-bottom-divider">
                        <div class="box-article-cover">
                            @foreach($articles as $val)
                                <div class="box-article">
                                    <div class="card-image rounded">
                                        <a href="{{ url('/article-detail/' . $val->slug .'/' . $val->title) }}" title="{{ $val->title }}" style="background-image: url('{{ $val->showImage }}')">
                                            <figure>
                                                <img src="{{ $val->showImage }}" alt="{{ $val->alt }}">
                                            </figure>
                                        </a>
                                    </div>
                                    <p class="article-title">
                                        <a href="{{ url('/article-detail/' . $val->slug .'/' . $val->title) }}">{{ $val->title }}</a>
                                    </p>
                                    {{-- <p>
                                        <span class="text-muted">
                                            <i class="fa fa-eye text-info"></i>&nbsp;&nbsp;<span class="view-vote">114</span>&nbsp;
                                        </span>&nbsp;
                                        <span class="text-muted">
                                            <i class="fa fa-star text-warning"></i>&nbsp;0.00
                                        </span>
                                    </p> --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
        */ ?>

        <section class="newsletter section">
            <div class="container-sm">
                {{-- <div class="newsletter-inner section-inner">
                    <div class="newsletter-header text-center is-revealing">
                        <h2 class="section-title mt-0">Stay in the know</h2>
                        <p class="section-paragraph">Lorem ipsum is common placeholder text used to demonstrate the graphic elements of a document or visual presentation</p>
                    </div>
                    <div class="footer-form newsletter-form field field-grouped is-revealing">
                        <div class="control control-expanded">
                            <input class="input" type="email" name="email" placeholder="Your best email&hellip;">
                        </div>
                        <div class="control">
                            <a class="button button-primary button-block button-shadow" href="#">Get early access</a>
                        </div>
                    </div>
                </div> --}}

                @if($widget->floating_bottom)
                    @foreach($widget->floating_bottom as $data)
                        @if($data->active_status)
                            @if($data->show_title_name)
                                {{ $data->title }}
                            @endif
                            {!! $data->detail !!}
                        @endif
                    @endforeach
                @endif
            </div>
        </section>
    </main>
    @include('frontend.layouts.footer')
  </div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{asset('frontend/js/jquery-min.js')}}"></script>
    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        $(function() {
            $('.db-collapse').each(function(index) {
                // console.log( index + ": " + $( this ).text() );
                if (index != 0) {
                    $('.match-' + index).click(function(){
                        $('.content-' + index).slideToggle('medium');
                    });
                }
            });

            // $('#search_land').keyup(function(e){
            //   if (e.keyCode == 13) {
            //     $(this).trigger("enterKey");
            //   }
            // });
        });
    </script>
@stop