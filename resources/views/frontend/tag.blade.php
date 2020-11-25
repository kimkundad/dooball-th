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
    <meta property="og:url"				content="https://dooball-th.com/tags/{{ $tag_name }}">
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
    <link href="https://dooball-th.com/tags/{{ $tag_name }}" rel="canonical">
@endsection

@section('custom-css')
@endsection

@section('content')
<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')
    @include('frontend.layouts.new-theme-navbar')
    @include('frontend._partials.new-theme.league-slider')
    @include('frontend.layouts.new-theme-navbar-second')

    <div class="content-box">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-12">
                <p>{!! $tag_content !!}</p>

                @if(count($articles) > 0)
                    <div class="row-article-list d-flex df-row df-wrap just-between">
                        @foreach($articles as $val)
                            <div class="card">
                                <a href="{{ url('/' . $val->slug) }}" title="{{ $val->title }}" style="background-image: url('{{ $val->showImage }}')">
                                    <figure class="zoom">
                                        <img src="{{ $val->showImage }}" alt="{{ $val->alt }}">
                                    </figure>
                                </a>
                                <div class="card-body">
                                    <p class="article-title card-text">
                                        <a href="{{ url('/' . $val->slug) }}">{{ $val->title }}</a>
                                    </p>
                                    <div class="info-box d-flex df-row df-wrap alit-center just-between">
                                        <span class="info-ele d-flex alit-center">
                                            <i class="fa fa-eye text-warning"></i><span class="text-white">{{ $val->count_view }}</span>
                                        </span>
                                        <span class="info-ele d-flex alit-center">
                                            <i class="fa fa-star text-warning"></i><span class="text-white">{{ $val->score }}</span>
                                        </span>
                                        <span class="d-flex alit-center">
                                            <i class="fa fa-edit text-warning"></i><span class="text-white">{{ $val->created_at }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="clearfix"></div>
                <div class="col-12">
                    <div class="page-sps d-flex alit-center just-center">
                        {{ $articles->onEachSide(3)->links() }}
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12">
                @include('frontend._partials.article-sidebar.latest')
                @include('frontend._partials.article-sidebar.popular')
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        $(function() {
            clearSlide();
        });
    </script>
@stop