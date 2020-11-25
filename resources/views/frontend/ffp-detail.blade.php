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
    <meta property="og:url"				content="https://dooball-th.com/ราคาบอลไหล?link={{ $link }}">
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
    <link href="https://dooball-th.com/ราคาบอลไหล?link={{ $link }}" rel="canonical">
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
                {{-- <h1 class="prediction-title">ราคาบอลไหล {{ $home_team }} {{ $away_team }} {{ $league_name }} แข่ง {{ $event_time }} คืนนี้</h1> --}}
                <h1 class="prediction-title">{{ $h_one }}</h1>
                <input type="hidden" id="detail_id" value="{{ $link }}">

                <div class="price-graph">
                    <div class="graph-loading">
                        <div class="l-one"></div>
                        <div class="l-two"></div>
                    </div>

                    <div class="d-flex just-center league-info">
                        <div class="team-logo hm d-flex df-col alit-center">
                            <img src="{{ asset('images/logo-team.jpg') }}" alt="" class="img-fluid">
                            <a href="javascript:void(0)" class="btn active-gd no-round no-border text-white mt-2">สถิติ {{ $home_team }}</a>
                        </div>
                        <div class="match-info d-flex df-col">
                            <div class="vs-info d-flex just-between">
                                <span class="h">{{ $home_team }}</span>
                                <span class="mvs text-center text-target"></span>
                                <span class="a">{{ $away_team }}</span>
                            </div>
                            <div class="l-info d-flex alit-center just-center">
                                <div class="l-name">{{ $league_name }} : </div>
                                <div class="ev-time">{{ $event_time }}</div>
                            </div>
                            <div class="score-info text-center"></div>
                            <div class="close-price text-center text-success">(ราคาปิด)</div>
                            <div class="link-to-game text-center">
                                <a href="{{ url('game') }}" class="btn active-gd no-round text-white">
                                    เล่นเกมทายผลบอลคู่นี้แจกฟรีเครดิต</a>
                            </div>
                        </div>
                        <div class="team-logo aw d-flex df-col alit-center">
                            <img src="{{ asset('images/logo-team.jpg') }}" alt="" class="img-fluid">
                            <a href="javascript:void(0)" class="btn active-gd no-round no-border text-white mt-2">สถิติ {{ $away_team }}</a>
                        </div>
                    </div>

                    <div class="card crd-content">
                        <div class="card-header">
                            <h4 class="top-head-graph">
                                <span id="asian_top_title">ราคาบอลไหล เอเชียแฮนดิแคป (Asia Handicap)</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            {{-- <div class="asian-content-box"></div> --}}
                            <div id="asian_graph" class="graph-layout"></div>
                        </div>
                    </div>

                    <div class="card crd-content">
                        <div class="card-header">
                            <h4 class="top-head-graph">
                                <span id="over_top_title">ราคาบอลไหล สูงต่ำ (Over/Under)</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            {{-- <div class="over-content-box"></div> --}}
                            <div id="over_graph" class="graph-layout"></div>
                        </div>
                    </div>

                    <div class="card crd-content">
                        <div class="card-header">
                            <h4 class="top-head-graph">
                                <span id="one_top_title">ราคาบอลไหล ฟิกอ๊อด ผบแพ้ชนะ (1x2) (Fixed Odds)</span>
                            </h4>
                        </div>
                        <div class="card-body">
                            {{-- <div class="one-content-box"></div> --}}
                            <div id="one_graph" class="graph-layout"></div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <h2>{{ $bottom_htwo }}</h2>
                <p class="c-content">
                    {!! $bottom_content_first !!}
                </p>

                <h2>สอนวิธีดู ราคาไหล</h2>
                <p class="c-content">
                    {!! $bottom_content_second !!}
                </p>
                <ul class="c-content">
                    <li>
                        กราฟบอลไหล อัตราต่อรอง
                        กราฟนี้จะแสดงค่าของทีมต่อขึ้นมาเป็นหลัก
                        <span class="text-bold text-white">หากกราฟมีการกระดกขึ้น</span> หมายถึง ราคาต่อ
                        มีค่าน้ำราคาบอลที่มากขึ้น นั่นคือ ราคาไหลลง ทีมรอง
                        แสดงว่าทีมรองมีจำนวนผู้เดิมพันที่มากกว่า หรือมีการเปลี่ยนแปลง
                        ตัวผู้เล่นหรือปัจจัยอื่นๆที่ทำให้ฝั่งบอลรองได้เปรียบมากยิ่งขึ้น
                        จะเรียกว่า ราคาไหลรอง! ตรงกันข้าม
                        <span class="text-bold text-white">หากกราฟกระดกหกหัวลงมา</span> มีค่าน้ำราคาบอลที่น้อยลง นั่นคือ
                        ราคาบอลไหลต่อ อธิบายได้ก็คือ ราคาไหลลง ทีมต่อ
                        แสดงว่าทีมต่อมีจำนวนผู้เดิมพันที่มากกว่า หรือมีการเปลี่ยนแปลง
                        ตัวผู้เล่นหรือปัจจัยอื่นๆที่ทำให้ฝั่งบอลต่อได้เปรียบมากยิ่งขึ้น
                    </li>
                    <li>
                        กราฟ ราคาบอลไหลสูงต่ำ กราฟนี้แสดง ค่า Under
                        <span class="text-bold text-white">หากกราฟมีการกระดกขึ้น</span> หมายถึง ค่าน้ำราคา เดิมพันฝั่งต่ำ มีค่า
                        มากขึ้น แสดงว่า ราคาบอลไหลสูงต่ำ กำลังไหลไปทางฝั่ง
                        บอลสูงนั่นเอง
                        <span class="text-bold text-white">หากกราฟกระดกหกหัวลงมา</span> หมายถึง ค่าน้ำราคา เดิมพันฝั่งสูง มีค่า
                        น้อยลง แสดงว่า ราคาบอลไหลสูงต่ำ กำลังไหลไปทางฝั่ง
                        บอลต่ำนั่นเอง
                    </li>
                    <li>กราฟราคาบอล 1*2 ราคาบอลแพ้ชนะ</li>
                </ul>
                <p class="c-content">
                    {!! $last_content !!}
                </p>
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
    <input type="hidden" id="run_as" value="{{ env('RUN_AS') }}">
    <input type="hidden" id="http_host" value="{{ $this_host }}" />
    <input type="hidden" id="api_host" value="{{ env('SCRAP_PRICE') }}">
</div>
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('frontend/js/jquery-min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script>
        var runAs = $('#run_as').val();
        var thisHost = $('#http_host').val();
        var apiHost = $('#base_url').val(); // $('#api_host').val();
        var fakeIp = '58.18.145.72';

        $(function() {
            clearSlide();

            if (runAs === 'local') {
                callGraphApi(fakeIp, 'http://localhost');
                callContentApi(fakeIp, 'http://localhost');
            } else {
                $.getJSON("https://api.ipify.org?format=json", function(data) { 
                    // console.log(data.ip);
                    if (data.ip) {
                        callGraphApi(data.ip, apiHost);
                        callContentApi(data.ip, apiHost);
                    }
                });
            }
        });

        function callGraphApi(ip, apiHost) {
            const params = {
                'ip': ip,
                detail_id: $('#detail_id').val()
            };

            $.ajax({
                url: apiHost + '/api/data-to-graph',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);

                    $('.graph-loading').hide();
                    $('.crd-content').css('visibility', 'visible');

                    if (response.asian) {
                        if (response.asian.team_series.length > 0) {
                            arrangeAsianGraphData(response.asian);

                            if (response.over) {
                                arrangeOverGraphData(response.over);
                            }

                            if (response.one) {
                                arrangeOneGraphData(response.one);
                            }
                        } else {
                            window.location = thisHost + '/ราคาบอล';
                        }
                    } else {
                        window.location = thisHost + '/ราคาบอล';
                    }
                },
                error: function(response) {
                    console.log(response);
                    $('.graph-loading').hide();
                    $('.crd-content').css('visibility', 'visible');
                }
            });
        }

        function arrangeAsianGraphData(response) {
            const gTopTitle = response.name;
            const timeList = response.time_list;
            const teamSeries = response.team_series;
            // const theMin = response.min; // Math.floor(response.min);

            let graphTitle = ''; // 'Information at: ' + dateTime; // 20200201-1727

            // $('#asian_top_title').html(gTopTitle);

            const graphDatas = {
                title: {
                    text: graphTitle
                },
                subtitle: {
                    text: '' // Source: thesolarfoundation.com
                },
                chart: {
                    backgroundColor: '#fafafa'
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    // min: theMin
                    tickInterval: 0.5
                },
                xAxis: {
                    categories: timeList
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    },
                    series: {
                        label: {
                            connectorAllowed: false
                        }
                    }
                },

                series: teamSeries,

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }

            };

            plotGraph(graphDatas);
            scoreInfo(teamSeries, 'asian');
        }

        function plotGraph(graphDatas) {
            // document.addEventListener('DOMContentLoaded', function () {
                var myChart = Highcharts.chart('asian_graph', graphDatas);
            // });
        }

        function arrangeOverGraphData(response) {
            const gTopTitle = response.name;
            const timeList = response.time_list;
            const teamSeries = response.team_series;
            // const theMin = response.min; // Math.floor(response.min);

            let graphTitle = ''; // 'Information at: ' + dateTime; // 20200201-1727

            // $('#over_top_title').html(gTopTitle);

            const graphDatas = {
                title: {
                    text: graphTitle
                },
                subtitle: {
                    text: '' // Source: thesolarfoundation.com
                },
                chart: {
                    backgroundColor: '#fafafa'
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    // min: theMin
                    tickInterval: 0.5
                },
                xAxis: {
                    categories: timeList
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    },
                    series: {
                        label: {
                            connectorAllowed: false
                        }
                    }
                },

                series: teamSeries,

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }

            };

            plotOverGraph(graphDatas);
            scoreInfo(teamSeries, 'over');
        }

        function plotOverGraph(graphDatas) {
            // document.addEventListener('DOMContentLoaded', function () {
                var myChart = Highcharts.chart('over_graph', graphDatas);
            // });
        }

        function arrangeOneGraphData(response) {
            const gTopTitle = response.name;
            const timeList = response.time_list;
            const teamSeries = response.team_series;
            // const theMin = response.min; // Math.floor(response.min);

            let graphTitle = ''; // 'Information at: ' + dateTime; // 20200201-1727

            // $('#one_top_title').html(gTopTitle);

            const graphDatas = {
                title: {
                    text: graphTitle
                },
                subtitle: {
                    text: '' // Source: thesolarfoundation.com
                },
                chart: {
                    backgroundColor: '#fafafa'
                },
                yAxis: {
                    title: {
                        text: null
                    },
                    // min: theMin
                    tickInterval: 0.5
                },
                xAxis: {
                    categories: timeList
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    },
                    series: {
                        label: {
                            connectorAllowed: false
                        }
                    }
                },

                series: teamSeries,

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }

            };

            plotOneGraph(graphDatas);
        }

        function plotOneGraph(graphDatas) {
            // document.addEventListener('DOMContentLoaded', function () {
                var myChart = Highcharts.chart('one_graph', graphDatas);
            // });
        }

        function callContentApi(ip, apiHost) {
            const params = {
                'ip': ip,
                detail_id: $('#detail_id').val()
            };
 
            $.ajax({
                url: apiHost + '/api/current-detail-content', // => arrange-content
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    $('.league-info').css('visibility', 'visible');

                    if (response) {
                        showContent(response.matches, 'asian', ip);
                    }

                    /*
                    if (response.over_content) {
                        var over = response.over_content;
                        showContent(over.matches, 'over', ip);
                    }

                    if (response.one_content) {
                        var one = response.one_content;
                        showContent(one.matches, 'one', ip);
                    }*/
                },
                error: function(response) {
                    console.log(response);
                    $('.graph-loading').hide();
                    $('.crd-content').css('visibility', 'visible');
                }
            });
        }

        function showContent(response, mode, ip) {
            var minusTeam = '';
            var doCount = 0;
            var html = '';

            if (response.length > 0) {
                for(var i = 0; i < response.length; i++) {
                    var row = response[i];

                    if (row.score < 0) {
                        minusTeam = row.team_name;
                    }
                    
                    console.log(mode, doCount, minusTeam, (row.score < 0));

                    if ((mode == 'asian' && doCount == 0) || (mode == 'asian' && doCount == 0 && minusTeam == '')) {
                        var hName = $('.h').html();
                        var aName = $('.a').html();

                        if (minusTeam == hName) {
                            $('.h').css('color', 'red');
                            doCount++;
                        }

                        if (minusTeam == aName) {
                            $('.a').css('color', 'red');
                            doCount++;
                        }
                    }

                    // html += '<div class="db-collapse">';
                    // html +=     '<div class="db-match">';
                    // html +=         '<span class="home-team graph-content d-flex just-between">';
                    // html +=             '<span class="w-60-pc">' + row.team_left + '</span>';
                    
                    // var leftClass =  (row.score_right_mid) ? 'just-between' : 'just-end';

                    // html +=             '<span class="w-40-pc d-flex ' + leftClass + ' graph-score">';
                    // if (row.score_left_mid) {
                    //     html +=            '<span>' + row.score_left_mid + '</span>';
                    //     if (mode == 'asian' && (row.score_left_mid < 0)) {
                    //         minusTeam = row.team_left;
                    //     }
                    // }
                    // if (row.score_left_last) {
                    //     html +=            '<span>' + row.score_left_last + '</span>';
                    // }
                    // html +=            '</span>';
                    // html +=        '</span>';
                    

                    // if (row.draw_text) {
                    //     html +=         '<span class="draw-text d-flex just-between">';
                    //     html +=             '<span>' + row.draw_text + '</span>';
                    //     html +=             '<span>' + row.draw_score + '</span>';
                    //     html +=        '</span>';
                    // }

                    // var rightClass =  (row.score_right_mid) ? 'just-between' : 'just-end';

                    // html +=         '<span class="away-team graph-content d-flex just-between">';
                    // html +=             '<span class="w-60-pc">' + row.team_right + '</span>';
                    // html +=             '<span class="w-40-pc d-flex ' + rightClass + ' graph-score">';
                    // if (row.score_right_mid) {
                    //     html +=             '<span>' + row.score_right_mid + '</span>';
                    //     if (mode == 'asian' && (row.score_right_mid < 0)) {
                    //         minusTeam = row.team_right;
                    //     }
                    // }
                    // if (row.score_right_last) {
                    //     html +=             '<span>' + row.score_right_last + '</span>';
                    // }
                    // html +=             '</span>';
                    // html +=         '</span>';
                    // html +=     '</div>';
                    // html += '</div>';
                }

                // $('.' + mode + '-content-box').html(html);
            }
        }

        function scoreInfo(teamSeries, mode) {
            if (teamSeries.length > 0) {
                var findTheGoal = 0;
                var teamName = '';
                var num = 0;
                var text = '';

                for (var i = 0; i < teamSeries.length; i++) {
                    num = teamSeries[i].data[0];
                    if (num > findTheGoal && num < 2) {
                        findTheGoal = num;
                        teamName = teamSeries[i].name;
                    }
                }

                var showTheTarget = '';
                if (teamName) {
                    var tgList = teamName.split(':');
                    showTheTarget = tgList[tgList.length - 1];
                }

                if (mode == 'asian') {
                    $('.mvs').html(showTheTarget);
                    text = 'Asian Handicap: <span class="text-target">' + showTheTarget + '</span>';
                } else if (mode == 'over') {
                    text = ' | Over / Under: <span class="text-target">' + showTheTarget + '</span>';
                }

                $('.score-info').append(text);
            }
        }
    </script>
@stop
