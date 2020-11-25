@extends('frontend.layouts.new-theme')

@section('title')
    <title>{{ $article->title }}</title>
@endsection

@if($website_robot == 1)
    @section('robots')
        <meta name="robots" content="index, follow">
    @endsection
@endif

@section('description')
    <meta name="description" content="{{ $article->seo_description }}">
@endsection

@section('fb_url')
    <meta property="og:url"				content="https://dooball-th.com/{{ $slug }}">
@endsection
@section('fb_title')
    <meta property="og:title"			content="{{ $article->title }}">
@endsection
@section('fb_description')
    <meta property="og:description"		content="{{ $article->seo_description }}">
@endsection
@section('fb_image')
    <meta property="og:image" 			content="{{ $web_image }}">
@endsection
@section('tw_title')
    <meta name="twitter:title"			content="{{ $article->title }}">
@endsection
@section('tw_description')
    <meta name="twitter:description"	content="{{ $article->seo_description }}">
@endsection
@section('tw_image')
    <meta name="twitter:image" content="{{ $web_image }}">
@endsection
@section('global_url')
    <link href="https://dooball-th.com/{{ $slug }}" rel="canonical">
@endsection

@section('custom-css')
@endsection

@section('content')
<div class="container main-content">
    @include('frontend.layouts.new-theme-top-nav')
    @include('frontend.layouts.new-theme-navbar')
    @include('frontend._partials.new-theme.league-slider')
    @include('frontend.layouts.new-theme-navbar-second')

    <div class="content-box atc-dt-page">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-6 col-12">
                <input type="hidden" id="atc_id" value="{{ $article->id }}">
                @if($article)
                    <img src="{{ $article->showImage }}" alt="" class="img-fluid">
                    <h1 class="atc-title mt-3">{{ $article->title }}</h1>
                    <div class="info-box d-flex alit-center">
                        <span class="info-ele d-flex alit-center">
                            <i class="fa fa-eye text-warning"></i><span class="text-white">{{ $article->count_view }}</span>
                        </span>
                        <span class="d-flex alit-center">
                            <i class="fa fa-clock text-warning"></i><span class="text-white">{{ $article->createdFormat }}</span>
                        </span>
                    </div>
                    <div class="info-box d-flex alit-center">
                        <span class="info-ele rate d-flex alit-center" data-toggle="modal" data-target="#rateModal">
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <i class="fa fa-star text-warning"></i>
                            <span class="text-white">({{ $article->vote }} votes, average: {{ $article->score }} out of 5)</span>
                        </span>
                    </div>

                    <p class="text-white mt-2">{{ $article->description }}</p>

                    {!! $article->detail !!}

                    <div class="d-flex alit-center my-3">
                        <span class="text-secondary mr-2"><i class="fa fa-tag"></i></span>&nbsp;{!! $article->tags !!}
                    </div>
                @endif
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                @include('frontend._partials.article-sidebar.latest')
                @include('frontend._partials.article-sidebar.popular')
            </div>
        </div>
    </div>
    @include('frontend._partials.new-theme.article-related')
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
    <input type="hidden" id="related" value="{{ $article->related }}">
</div>

<div id="rateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ให้คะแนนบทความ</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal rate-form" role="form" id="rate_form">
                    <div class="form-group row-star">
                        <div class="col-md-12 col-sm-12 score">
                            <i class="fa fa-star big-star one"></i>&nbsp;
                            <i class="fa fa-star big-star two"></i>&nbsp;
                            <i class="fa fa-star big-star three"></i>&nbsp;
                            <i class="fa fa-star big-star four"></i>&nbsp;
                            <i class="fa fa-star big-star five"></i>
                        </div>
                        <p class="score number"></p>
                        <input type="hidden" id="score" value="0" />
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-sm-4" for="username">Username&nbsp;<span class="text-warning">*</span>&nbsp;:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" id="username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-sm-4" for="code_name">ชื่อ&nbsp;<span class="text-warning">*</span>&nbsp;:</label>
                        <div class="col-md-8 col-sm-8"> 
                            <input type="text" class="form-control" id="code_name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-sm-4" for="code_secret">รหัสกิจกรรม&nbsp;<span class="text-warning">*</span>&nbsp;:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" id="secret_code" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-sm-4"></div>
                        <div class="col-md-8 col-sm-8">
                            <button type="submit" class="btn btn-success">ดำเนินการต่อ</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

        $(function(){
            clearSlide();
            findArticleRelated();
            updateView();

            $('.rate-form .one').click(function(){
                $('.rate-form .big-star').removeClass('text-warning');
                $('.rate-form .one').addClass('text-warning');
                $('#score').val(1);
            });
            $('.rate-form .two').click(function(){
                $('.rate-form .big-star').removeClass('text-warning');
                $('.rate-form .one').addClass('text-warning');
                $('.rate-form .two').addClass('text-warning');
                $('#score').val(2);
            });
            $('.rate-form .three').click(function(){
                $('.rate-form .big-star').removeClass('text-warning');
                $('.rate-form .one').addClass('text-warning');
                $('.rate-form .two').addClass('text-warning');
                $('.rate-form .three').addClass('text-warning');
                $('#score').val(3);
            });
            $('.rate-form .four').click(function(){
                $('.rate-form .big-star').removeClass('text-warning');
                $('.rate-form .one').addClass('text-warning');
                $('.rate-form .two').addClass('text-warning');
                $('.rate-form .three').addClass('text-warning');
                $('.rate-form .four').addClass('text-warning');
                $('#score').val(4);
            });
            $('.rate-form .five').click(function(){
                $('.rate-form .big-star').removeClass('text-warning');
                $('.rate-form .big-star').addClass('text-warning');
                $('#score').val(5);
            });

            $('#rate_form').submit(function(){
                var invalid = 0;
                $('.rate-form .score.number').html('');
                $('#username').removeClass('warning');
                $('#code_name').removeClass('warning');
                $('#secret_code').removeClass('warning');

                if($('#score').val() == '' || $('#score').val() == '0'){
                    invalid++;
                    $('.rate-form .score.number').html('กรุณาคลิกรูปดาว เพื่อให้คะแนน');
                }
                if($('#username').val().trim() == ''){
                    invalid++;
                    $('#username').addClass('warning');
                }
                if($('#code_name').val().trim() == ''){
                    invalid++;
                    $('#code_name').addClass('warning');
                }
                if($('#secret_code').val().trim() == ''){
                    invalid++;
                    $('#secret_code').addClass('warning');
                }

                if(invalid==0){
                    checkRating();
                }
                
                return false;
            });
        });

        function findArticleRelated() {
            var params = {
                            '_token': $('meta[name="csrf-token"]').attr('content')
                            ,'related': $('#related').val()
                        };

            $.ajax({
                url: $('#base_url').val() +'/api/article-related',
                type: 'POST',
                data: params,
                beforeSend: function(){
                    // $('.action-loading').show();
                    // $('.action-loading').css('z-index',10050);
                    // $('.modal').css('z-index',10049);
                },
                dataType: 'json',
                cache: false,
                success: function(response){
                    // console.log(response);
                    if (response.length > 0) {
                        var html = '';

                        for (var i = 0; i < response.length; i++) {
                            html += '<div class="card">';
                            html +=     '<a href="/' + response[i].slug + '">';
                            html +=         '<figure class="zoom">';
                            html +=             '<img src="' + response[i].showImage + '" class="img-fluid">';
                            html +=         '</figure>';
                            html +=     '</a>';

                            html +=     '<div class="card-body">';
                            html +=         '<p class="article-title card-text">';
                            html +=             '<a href="/' + response[i].slug + '">' + response[i].title + '</a>';
                            html +=         '</p>';
                            html +=         '<div class="info-box d-flex df-row df-wrap alit-center just-between">';
                            html +=             '<span class="info-ele d-flex alit-center">';
                            html +=                 '<i class="fa fa-eye text-warning"></i><span class="text-white">' + response[i].count_view + '</span>';
                            html +=             '</span>';
                            html +=             '<span class="info-ele d-flex alit-center">';
                            html +=                 '<i class="fa fa-star text-warning"></i><span class="text-white">' + response[i].score + '</span>';
                            html +=             '</span>';
                            // html +=             '<span class="d-flex alit-center">';
                            // html +=                 '<i class="fa fa-edit text-warning"></i><span class="text-white">' + response[i].createdFormat + '</span>';
                            // html +=             '</span>';
                            html +=         '</div>';
                            html +=     '</div>';
                            html += '</div>';
                        }

                        $('#related_area').html(html);
                    }
                }
            });
        }

        function checkRating() {
            var params = {
                            '_token': $('meta[name="csrf-token"]').attr('content')
                            ,'article_id': $('#atc_id').val()
                            ,'secret_code': $('#secret_code').val()
                            ,'score': $('#score').val()
                        };

            $.ajax({
                url: $('#base_url').val() +'/api/web/rate',
                type: 'POST',
                data: params,
                beforeSend: function(){
                    // $('.action-loading').show();
                    // $('.action-loading').css('z-index',10050);
                    // $('.modal').css('z-index',10049);
                },
                dataType: 'json',
                cache: false,
                success: function(response){
                    // console.log(response);
                    // $('.action-loading').hide();
                    alert(response.message);

                    if (response.total == 1) {
                        // saveSuccess();

                        setTimeout(function(){
                            window.location.reload();
                        }, 2000);
                    } else {
                        // showWarning(response.message,'Warning!');
                    }
                }
            });
        }

        function updateView() {
            const params = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                id: $('#atc_id').val()
            };

            $.ajax({
                url: $('#base_url').val() + '/api/web/count-view',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
@stop