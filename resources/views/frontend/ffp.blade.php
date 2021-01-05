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


                                <tbody id="tbody-ffp">
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
                        if (response.raw_group) {
                            arrangeContent(response.raw_group);
                          //  saveToTemp(response.latest_dir, response.final_list);
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

        function arrangeContent(rawGroup, domain) {
            if (rawGroup) {
                console.log(rawGroup);
                if (rawGroup.length > 0) {
                    var firstLink = '';
                    var html = '';
                    var c = 0;
                    c = rawGroup.length;
                    //console.log(c);
                    var arr = [];
                    for(var i = 0; i < c; i++) {
                        var row = rawGroup[i];
                        var rowDatas = row;
                        //arr.push(row.datas);
                       // console.log(row.datas[i].match_datas);
                       // var link = row.link;

                        var d = 0;
                        var d = row.datas.length;

                       // console.log(row.datas.length);
                       var f = 0;
                        for (var j = 0; j < d; j++) {

                            var data = row.datas[j];
                            var link = data.match_datas[0].link;

                            if(i == 0){

                           // console.log(data.match_datas.length);

                            if( j == 0){
                                html += '<tr class="db-collapse">';
                                html += '<td colspan=5>';
                                html += '<span><b>' + row.top_head + '</b></span>';
                                html += '</td>';
                                html += '</tr>';
                            }

                            var k = 0;
                            kk = data.match_datas.length;

                            html += '<tr class="db-collapse">';
                                html += '<td colspan=5>';
                                html += '<span><b>' + data.league_name + '</b></span>';
                                html += '</td>';
                                html += '</tr>';

                            for (var k = 0; k < kk; k++) {

                            var data2 = data.match_datas[k];


                            html += '<tr class="db-collapse">'; // db-match
                            html +=         '<td>';
                            html +=             '<div class="match-time d-flex just-between">';
                            html +=                 '<span>' + data2.time + '</span>';
                            html +=             '</div>';
                            html +=         '</td>';
                            html +=         '<td>';
                            html +=             '<div class="match-time d-flex just-between">';
                            html +=                 '<span>' + data2.left[0] +' <b>(' + data2.left[1] +')</b></span>';
                            html +=             '</div>';
                            html +=         '</td>';
                            html +=         '<td>';
                            html +=             'เสมอ <b> ' + data2.mid[1] + '</b>';
                            html +=         '</td>';
                            html +=         '<td>';
                            html +=             '<div class="match-time d-flex just-between">';
                            html +=                 '<span>' + data2.right[0] +' <b>(' + data2.right[1] +')</b></span>';
                            html +=             '</div>';
                            html +=         '</td>';
                                html +=         '<td class="row-span" ';
                                html +=             '<div class="league-name">';
                                if (link) {
                                    html +=             '<a href="{{ url('/ราคาบอลไหล?link=') }}'+ link +'" target="_BLANK">ดูราคา<br>บอลไหล</a>';
                                }
                                html +=             '</div>';
                                html +=         '</td>';
                            html += '</tr>';
                            }

                            }else{

                                var kk = 0;
                                kk = data.match_datas.length;

                                if(f === 0){
                                    html += '<tr class="db-collapse">';
                                    html += '<td colspan=5>';
                                    html += '<span><b>' + row.top_head + '</b></span>';
                                    html += '</td>';
                                    html += '</tr>';
                                }

                            ////////////////////////////////

                                html += '<tr>';
                                html += '<td colspan=5>';
                                html += '<span><b>' + data.league_name + '</b></span>';
                                html += '</td>';
                                html += '</tr>';


                                for (var k = 0; k < kk; k++) {
                                var data2 = data.match_datas[k];


                                console.log(data2.left_list.length);
                                ll = data2.left_list.length;
                                //console.log(ll)
                                for (var l = 0; l < ll; l++) {
                                    var data3 = data2.left_list[l];
                                    var data4 = data2.right_list[l];

                                html += '<tr class="db-collapse">'; // db-match
                                html +=         '<td>';
                                html +=             '<div class="match-time d-flex just-between">';
                                html +=                 '<span>' + data2.time + '</span>';
                                html +=             '</div>';
                                html +=         '</td>';

                                html +=         '<td>';
                                html +=             '<div class="match-time d-flex just-between">';
                                html +=                 '<span>' + data3[0] + ' <span style="padding-left:5px; color: #46a; padding-right:5px;">' + data3[1] + '</span> <b>(' + data3[2] + ')</b></span>';
                                html +=             '</div>';
                                html +=         '</td>';

                                html +=         '<td>';
                                html +=             '<div class="match-time d-flex just-between">';
                                html +=                 '<span>' + data4[0] + ' <span style="padding-left:5px; color: #46a; padding-right:5px;">' + data4[1] + '</span> <b>(' + data4[2] + ')</b></span>';
                                html +=             '</div>';
                                html +=         '</td>';

                                html +=         '<td class="row-span" colspan="2" >';
                                html +=             '<div class="league-name">';
                                    if (link) {
                                    html +=             '<a href="{{ url('/ราคาบอลไหล?link=') }}'+ link +'" target="_BLANK">ดูราคา<br>บอลไหล</a>';
                                    }
                                html +=             '</div>';
                                html +=         '</td>';
                                html += '</tr>';

                                }

                                }




                            }
                            f++
                        }
                    }
                    //console.log(arr)
                    $('#tr_loading').remove();
                    $('#tbody-ffp').append(html);
                }
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
