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
    <meta property="og:url"				content="https://dooball-th.com">
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
    <link href="https://dooball-th.com" rel="canonical">
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
            <div class="col-lg-8 col-md-8 col-12 l-side">
                {!! $top_content !!}

                {{-- <div class="dooball-th">ดูบอลสด ดูบอลออนไลน์ รวมลิ้งวันนี้</div>
                <p>
                    ดูบอลสด วันนี้ อัพเดตลิ้งดูบอลจากทั่วทุกมุมโลก เว็บไซต์ถูกเขียนมาให้ รองรับมือถือ ดูบอลผ่านเน็ต อัพเดตลิ้งเร็ว พร้อมคำอธิบายแต่ละเว็บ เว็บไซต์ถ่ายทอดบอลสดแบบ ไม่มีโฆษณาบัง ระดับความคมชัด HD 4K ถ่ายบอลสดจอใหญ่ ไหลลื่น ดูง่าย ไม่กระตุก Update link facebook youtube ให้ระหว่างแข่ง แก้ไขปัญหา ลิ้งเสีย ดูบอลไม่ได้ ดูบอลสดออนไลน์ มือถือฟรีวันนี้ พร้อมทั้งมีลิ้งค์สำรองให้เลือกเข้าชม
                </p> --}}

                @include('frontend._partials.new-theme.match', ['message' => 'วันนี้ไม่มีบาส NBA แข่งค่ะ'])

                <br>
                {!! $bottom_content !!}

                {{-- <p class="mt-3">
                    Dooball TH ถ่ายทอดสดฟุตบอล ครบทุกช่อง pptv true sport hd ทรูไอดี บอลลีกเล็ก ลีกใหญ่ มีสตรีมผ่าน youtube facebook ครบเครื่อง ครบทุกคู่ สำหรับคนชอบดูบอลออนไลน์คืนนี้ เลือกชมได้จบที่เว็บเดียว มีลิ้งสำรองจากเว็บดูบอลชั้นนำ dooball , dooballsod , dooballhd , linkdooball , dooball football , doballfree.com , doball24.com , Doofootball.com , 7mscorethai.com , dooball.com , dooball online , duball.com , football online , dooball football คัดสรรและรวมไว้ที่นี่แล้ว
                </p> --}}
            </div>
            <div class="col-lg-4 col-md-4 col-12 r-side">
                @include('frontend._partials.new-theme.right-sean')
                @include('frontend._partials.new-theme.right-ffp')
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
    @include('frontend._partials.new-theme.article')
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>
<input type="hidden" id="http_host" value="{{ $this_host }}" />
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var thisHost = $('#http_host').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var fakeIp = '58.18.145.72';
        // console.log(thisHost);

        $(function() {
            clearSlide();

            // $('.row-live-ele').each(function() {
            //     var time = $(this).find('.match-live-time').text();
            //     console.log(time);
            // });

            $('.row-live-ele').on('click', function(ele){
                var content = $(this).find('.live-ball');
                // console.log(content);
                // content.css('visibility', 'visible');
                // content.css('height', 'auto');
                content.slideToggle('medium');
            });

            // predictionDatas(fakeIp, apiHost);
        });

        function predictionDatas(ip, apiHost) {
            const params = {
                'ip': ip
            };

            $.ajax({
                url: apiHost + '/api/ffp',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response.final_list) {
                        arrangeContent(response.final_list);
                        saveToTemp(response.latest_dir, response.final_list);
                    }
                },
                error: function(response) {
                    console.log(response);
                    $('.ffp-box').html('<span class="text-muted text-center">--- ไม่มีข้อมูลในช่วงเวลานี้ --</span>');
                }
            });
        }
        
        function arrangeContent(finalList) {
            if (finalList.length > 0) {
                var html = '';
                var diffList = [];

                for (var k = 0; k < finalList.length; k++) {
                    var matchDatas = finalList[k].match_datas;

                    for (var l = 0; l < matchDatas.length; l++) {
                        var data = matchDatas[l];
                        var detailId = data.detail_id;
                        var homeTeam = data.left[0];
                        var awayTeam = data.right[0];

                        var water = (data.datas.asian.water) ? data.datas.asian.water : 0;
                        var lastWater = (data.datas.asian.last_water) ? data.datas.asian.last_water : 0;

                        var diff = water - lastWater;
                        var color = (diff >= 0) ? 'text-success' : 'text-danger';
                        var sbdDiff = Math.abs(diff);
                        var divideWater = water - 1;
                        var percentDiff = 0;

                        if (divideWater != 0) {
                            percentDiff = (diff * 100 ) / divideWater;
                        }

                        var obj = {
                            diffScore: Math.abs(percentFormat(percentDiff)),
                            home_team: homeTeam,
                            away_team: awayTeam,
                            link: detailId
                        };

                        diffList.push(obj);
                    }
                }

                diffList.sort(compare);
                // console.log(diffList);

                var topFive = diffList.slice(0, 5);
                console.log(topFive);

                if (topFive.length > 0) {
                    var ffp = '';

                    for (var i = 0; i < topFive.length; i++) {
                        ffp += '<div class="ele-ffp item-' + i + ' d-flex alit-center">';
                        ffp +=      '<div class="lt-name">' + topFive[i].home_team + '</div>';
                        ffp +=      '<div class="ffp-vs">vs</div>';
                        ffp +=      '<div class="rt-name">' + topFive[i].away_team + '</div>';
                        ffp +=      '<div class="ffp-g-icon text-right">';
                        ffp +=          '<a href="' + thisHost + '/ราคาบอลไหล?link=' + topFive[i].link + '" target="_BLANK"><i class="fa fa-chart-line text-white"></i></a>';
                        ffp +=      '</div>';
                        ffp +=  '</div>';
                    }

                    $('.ffp-box').html(ffp);
                } else {
                    $('.ffp-box').html('<span class="text-muted">--- ไม่มีข้อมูลในช่วงเวลานี้ --</span>');
                }
            } else {
                $('.ffp-box').html('<span class="text-muted">--- ไม่มีข้อมูลในช่วงเวลานี้ --</span>');
            }
        }

        function compare( a, b ) {
            if ( a.diffScore < b.diffScore ){
                return 1;
            }
            if ( a.diffScore > b.diffScore ){
                return -1;
            }

            return 0;
        }

        function saveToTemp(latest_dir, final_list) {
            var params = {
                latest_dir: latest_dir,
                final_list: JSON.stringify(final_list)
            };

            $.ajax({
                url: apiHost + '/api/save-to-ffp-temp',
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