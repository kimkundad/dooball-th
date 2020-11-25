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
    <meta property="og:url"				content="https://dooball-th.com/ราคาบอล">
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
    <link href="https://dooball-th.com/ราคาบอล" rel="canonical">
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
                {!! $top_content !!}

                <div class="text-left text-white" id="title_data"></div>

				<div class="row prediction-table-area">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-condensed text-white">
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
                                <tbody id="tbody_ffp">
                                    <tr>
                                        <td colspan="7">
                                            <div class="table-loading">
                                                <div class="l-one"></div>
                                                <div class="l-two"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
					</div>
                </div>

                {!! $bottom_content !!}
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>
<input type="hidden" id="run_as" value="{{ env('RUN_AS') }}">
<input type="hidden" id="http_host" value="{{ $this_host }}" />
<input type="hidden" id="api_host" value="{{ env('SCRAP_PRICE') }}">
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script>
        var runAs = $('#run_as').val();
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

            ffp(fakeIp, apiHost);
        });

        function ffp(ip, apiHost) {
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
                    // console.log(JSON.parse(response));
                    if (response.latest_dir) {
                        // var text = 'ข้อมูล ณ เวลา: ' + response.latest_dir;
                        $('#title_data').html('');
                        if (response.final_list) {
                            arrangeContent(response.final_list);
                            saveToTemp(response.latest_dir, response.final_list);
                        }
                    } else {
                        $('#title_data').html('');
                        // console.log(response);
                        $('#tbody_ffp > td').html('- ไม่มีข้อมูลการแข่งขัน ในช่วงเวลานี้ -');
                    }
                },
                error: function(response) {
                    console.log(response);
                    $('#title_data').html('The system is currently unavailable.');
                    $('#tbody_ffp').remove();
                }
            });
        }

        function arrangeContent(finalList) {
            if (finalList.length > 0) {
                var html = '';
                for (var k = 0; k < finalList.length; k++) {
                    var leagueName = finalList[k].league_name;
                    var matchDatas = finalList[k].match_datas;

                    if (matchDatas.length > 0) {
                        html += '<tr class="league-name">';
                        html +=     '<td colspan="7">' + leagueName + '</td>';
                        html += '</tr>';

                        for (var l = 0; l < matchDatas.length; l++) {
                            var data = matchDatas[l];
                            var detailId = data.detail_id;
                            var teamClass = 'team-' + k +'-' + l;
                            var homeTeam = data.left[0];
                            var awayTeam = data.right[0];
                            var findHomeMinus = 0;
                            var homeRed = '';
                            var awayRed = '';
                            
                            if (data.left_list.length) {
                                for (var m = 0; m < data.left_list.length; m++) {
                                    var inner = data.left_list[m];

                                    if (inner[2]) {
                                        var strMid = inner[1];
                                        var n = strMid.indexOf('-');

                                        if (n != -1) {
                                            findHomeMinus++;
                                        }
                                    }
                                }
                            }

                            homeRed = (findHomeMinus > 0) ? 'text-danger' : '';
                            awayRed = (findHomeMinus == 0) ? 'text-danger' : '';

                            html += '<tr class="db-collapse">';
                            html +=         '<td>';
                            html +=             '<div class="match-time d-flex just-between">';
                            html +=                 '<span>' + ((data.date_time_before) ? data.date_time_before : '') + '</span>';
                            html +=             '</div>';
                            html +=         '</td>';
                            html +=         '<td>';
                            html +=             '<div class="ha-team ffp ' + teamClass + ' ' + homeRed + '">' + homeTeam + '</div>';
                            html +=         '</td>';
                            html +=         '<td>';

                            if (data.left_list.length) {
                                for (var m = 0; m < data.left_list.length; m++) {
                                    var inner = data.left_list[m];

                                    html +=             '<div class="d-flex alit-center just-between narrow-in-mb">';
                                    html +=                 '<div>';
                                    html +=                     ((inner[2]) ? inner[1] : '<span class="text-bold">(แพ้/ชนะ)</span>');
                                    html +=                 '</div>';
                                    html +=                 '<div>' + ((inner[2]) ? '@' + inner[2] : '@' + inner[1]) + '</div>';  
                                    html +=             '</div>';
                                }
                            }

                            var water = (data.datas.asian.water) ? data.datas.asian.water : 0;
                            var lastWater = (data.datas.asian.last_water) ? data.datas.asian.last_water : 0;

                            var diff = water - lastWater;
                            var sbdDiff = Math.abs(diff);
                            var divideWater = water - 1;
                            var percentDiff = 0;

                            if (divideWater != 0) {
                                percentDiff = (diff * 100 ) / divideWater;
                            }

                            console.log(percentDiff);

                            html +=         '</td>';
                            html +=        '<td class="not-mb">';
                            html +=             '<div class="vs d-flex just-center">Vs</div>';
                            html +=         '</td>';
                            html +=         '<td>';
                            html +=             '<div class="ha-team ffp ' + teamClass + ' ' + awayRed + '">' + awayTeam + '</div>';
                            html +=         '</td>';
                            html +=         '<td><span class="' + percentColor(parseInt(percentDiff)) + ' text-bold">' + percentFormat(percentDiff) + '%</span></td>';
                            // html +=         '<td>';
                            // html +=             (type == '1X2') ? '<span class="text-bold">(แพ้/ชนะ)</span>' : data.right[1];
                            // html +=         '</td>';
                            // html +=         '<td>';
                            // html +=             (type == '1X2') ? data.right[1] : data.right[2];
                            // html +=         '</td>';

                            html +=         '<td>'; //  class="row-span" rowspan="' + data.left_list.length + '"
                            html +=             '<div class="box-btn-link">';
                            if (detailId) {
                                var link = thisHost + '/ราคาบอลไหล?link=' + detailId;
                                html +=             '<a href="' + link + '" class="btn btn-block active-gd no-round text-white" target="_BLANK">ดูราคา<br>บอลไหล</a>';
                            }
                            html +=             '</div>';
                            html +=         '</td>';

                            html += '</tr>';
                        }
                    }
                }

                $('#tbody_ffp').html(html);
            }
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