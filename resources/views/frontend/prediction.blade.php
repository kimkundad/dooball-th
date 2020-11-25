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
    <meta property="og:url"				content="https://dooball-th.com/game">
@endsection
@section('fb_title')
    <meta property="og:title"			content="{{ $seo_title }}">
@endsection
@section('fb_description')
    <meta property="og:description"		content="{{ $seo_description }}">
@endsection
@section('fb_image')
    <meta property="og:image" 			content="https://dooball-th.com/images/social/game_1200x600.jpg">
@endsection
@section('tw_title')
    <meta name="twitter:title"			content="{{ $seo_title }}">
@endsection
@section('tw_description')
    <meta name="twitter:description"	content="{{ $seo_description }}">
@endsection
@section('tw_image')
    <meta name="twitter:image" content="https://dooball-th.com/images/game_800x418.jpg">
@endsection
@section('global_url')
    <link href="https://dooball-th.com/game" rel="canonical">
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
                <div class="row">
                    <div class="col-lg-9">
                        {!! $top_content !!}

                        {{-- <h1 class="prediction-title">กติกา เกมทายผลบอลวันนี้ ฟรีเครดิต</h1>
                        <ul class="prediction-detail">
                            <li>เข้าทายผลบอลวันละ 1 คู่</li>
                            <li>เกมทาผลบอล เล่นฟรี ไม่มีเงื่อนไข</li>
                            <li>เครดิตฟรี ที่จะได้รับ เล่นได้ทุกเกมส์ สล็อต คาสิโน หวย ฟุตบอล</li>
                            <li>เกมทายผลบอล ถูกติดกัน 4 วัน = ได้รับ เครดิต 100 รับได้ไม่จำกัดรางวัล</li>
                            <li>เครดิตฟรีไม่ต้องฝาก ไม่ต้องแชร์ เติมให้ในรหัสเดิมพัน เท่านั่น</li>
                            <li>เครดิฟรี 100 ทำเทิร์น 1 ถอนได้</li>
                            <li>รับได้ไม่จำกัด ถูก4วัน 5รอบ = เครดิตฟรี 500</li>
                        </ul>

                        <h2 class="text-white">ขั้นตอนการขอรับเครดิตฟรี</h2>
                        <ul class="prediction-detail">
                            <li>Add line ไปที่ @dooball-th <a href="https://lin.ee/4oW26saLl" target="_BLANK">https://lin.ee/4oW26saLl</a></li>
                            <li>แจ้ง ชื่อ รอ Admin ตรวจสอบ</li>
                            <li>เครดิตฟรี เติมให้ช่วง 23.00 ของทุกวัน</li>
                        </ul>

                        <p class="text-white">** จำกัด 1 คน/ 1 สิทธิ์/ 1 ip หากตรวจพบ ทีมงานทำการแบน โดยไม่ต้องแจ้งให้ทราบ</p>
                        <p class="text-white">** ชื่อ และ ชื่อบัญชี ต้องเป็นชื่อเดียวกันถึงสามารถ ถอนได้</p> --}}

                    </div>
                    <div class="col-lg-3 pddr-30">
                        @guest
                            <form id="member_form" class="member-form" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="d-flex alit-center just-between c-theme">
                                    <div class="diy-tab-nav login-sec diy-active">เข้าสู่ระบบ</div>
                                    <div class="diy-tab-nav regis-sec">สมัครใหม่</div>
                                </div>
                                <div id="login_area">
                                    <input type="text" class="form-control no-round my-3" id="username" name="username" placeholder="Username" maxlength="125">
                                    <input type="password" class="form-control no-round my-3" id="password" name="password" placeholder="Password" maxlength="125">
                                    <button type="submit" class="btn btn-block active-gd no-round text-white">LOGIN</button>
                                </div>
                                <div id="register_area">
                                    <input type="text" class="form-control no-round my-3" id="regis_username" name="regis_username" placeholder="Username" maxlength="125">
                                    <input type="password" class="form-control no-round my-3" id="regis_password" name="regis_password" placeholder="Password" maxlength="125">
                                    <input type="password" class="form-control no-round my-3" id="confirm_password" name="confirm_password" placeholder="Password" maxlength="125">
                                    <input type="text" class="form-control no-round my-3" id="regis_display_name" name="regis_display_name" placeholder="ชื่อแสดงในเกมส์" maxlength="125">
                                    <input type="text" class="form-control no-round my-3" id="regis_line_id" name="regis_line_id" placeholder="Line ID" maxlength="125">
                                    <input type="number" class="form-control no-round my-3" id="regis_tel" name="regis_tel" placeholder="Tel" maxlength="10">

                                    <button type="button" class="btn btn-block active-gd no-round text-white" id="register_btn">REGISTER</button>
                                </div>
                                
                                <a href="https://lin.ee/4oW26saLl" class="forgot-password my-1 text-white">ลืมรหัสผ่าน</a>
                                <div class="clearfix"></div>

                                <div class="sepa-login my-1 d-flex alit-center just-center">
                                    <div class="line-or"></div>
                                    <div class="or-text text-white text-center">หรือ</div>
                                    <div class="line-or"></div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a href="{{ route('social.oauth', 'facebook') }}" class="no-border">
                                            <img src="{{ asset('images/loginwith-facebook.png') }}" class="img-fluid">
                                        </a>
                                    </div>
                                </div>
                                
                            </form>
                        @else
                            <div class="row predict-member-area">
                                <div class="col-12 mt-3 mb-2 d-flex alit-center just-center">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Auth::user()->avatar }}">
                                    @else
                                    <img src="{{ asset('images/user-avatar.png') }}">
                                    @endif
                                </div>
                                <div class="col-6 text-right">ชื่อผู้ใช้</div>
                                <div class="col-6">{{ Auth::user()->username }}</div>
                                <div class="col-6 text-right">ชื่อในเกม</div>
                                <div class="col-6">
                                    {{ (Auth::user()->screen_name) ? Auth::user()->screen_name : '-' }}
                                </div>
                                <div class="col-12 my-2 text-center">
                                    <a href="{{ url('bet-stats/' . Auth::user()->username) }}" class="text-white">สถิติ</a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://line.me/R/ti/p/%40899hibwr" class="text-white" target="_BLANK">ติดต่อรับรางวัล</a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('ราคาบอล') }}" class="text-white">เช็คราคาบอลไหล</a>
                                </div>
                                <div class="col-12 text-center">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn active-gd no-round text-white" href="{{ route('logout') }}">ออกจากระบบ</button>
                                    </form>
                                </div>
                            </div>
                            <input type="hidden" id="member_id" value="{{ Auth::user()->id }}">
                        @endguest
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row desktop">
                    <div class="col-12">
                        <div class="prediction-step d-flex alit-start">
                            <div class="step-one d-flex alit-start just-center">
                                <span class="c-theme text-num">1</span>
                                <div class="d-flex df-col alit-center text-fst">
                                    <img src="{{ asset('images/step-1.png') }}">
                                    <span class="text-sc text-white">สมัคร</span>
                                </div>
                            </div>
                            <div class="step-two d-flex alit-start just-center">
                                <span class="c-theme text-num">2</span>
                                <div class="d-flex df-col alit-center ml-2 text-snd">
                                    <img src="{{ asset('images/step-2.png') }}">
                                    <span class="text-sc text-white">เข้าทายผล วันละ <span class="c-theme">1 คู่</span></span>
                                </div>
                            </div>
                            <div class="step-three d-flex alit-start just-center">
                                <span class="c-theme text-num">3</span>
                                <div class="d-flex df-col alit-center text-thrd">
                                    <img src="{{ asset('images/step-3.png') }}">
                                    <div class="text-sc text-white">ถูก 4 วัน รับ</div>
                                    <div class="text-sc c-theme">เครดิต 100</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="row my-3 prediction-table-area">
					<div class="col-12">
                        <div class="d-flex alit-center text-white my-2 color-zone">
                            <div class="color-box d-flex">
                                <div class="match-cube win">W</div>
                                <div>ทายผลชนะเต็ม</div>
                            </div>
                            <div class="color-box d-flex">
                                <div class="match-cube win half">W</div>
                                <div>ทายผลชนะครึ่ง</div>
                            </div>
                            <div class="color-box d-flex">
                                <div class="match-cube lose">L</div>
                                <div>ทายผลเสียเต็ม</div>
                            </div>
                            <div class="color-box d-flex">
                                <div class="match-cube lose half">L</div>
                                <div>ทายผลเสียครึ่ง</div>
                            </div>
                            <div class="color-box d-flex">
                                <div class="match-cube draw">D</div>
                                <div>ทายผลเสมอ</div>
                            </div>
                        </div>
                        <div class="card bg-trans text-white no-round no-border">
                            <div class="card-header no-round card-prediction">
                                <h2>TOP10 ผู้เล่นเกมทายผลบอล</h2>
                            </div>
                            <div class="card-body">
                                @include('frontend._partials/prediction/table-user')
                            </div>
                        </div>
					</div>
                </div>
				<div class="row prediction-table-area">
                    <div class="col-12">
                        <div class="card bg-trans no-round no-border">
                            <div class="card-header no-round card-prediction">
                                <h2 class="text-white">ตารางเกมทายผลบอล วันนี้</h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-striped text-white table-prediction" id="table_prediction_list">
                                        <tbody id="tbody_prediction_table">
                                            <tr>
                                                <td>
                                                    <div class="table-loading">
                                                        <div class="l-one"></div>
                                                        <div class="l-two"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                {{-- @include('frontend._partials/prediction/table-prediction') --}}
                            </div>
                        </div>

                        <h3 class="text-white mt-2">ตารางอัพเดต รางวัล เครดิตฟรี เกมทายผลบอล</h3>

                        @if(count($texts) > 0)
                            <table class="table table-condensed table-bordered table-striped text-white">
                                @foreach($texts as $val)
                                    <tr>
                                        <td>{{ $val->text }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif

                        {!! $bottom_content !!}
					</div>
				</div>
            </div>
        </div>
    </div>
    <div class="d-flex alit-center just-center banner-content">BANNER</div>
</div>

<div id="betModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title chose-team">
                    <span class="ctm">เลือกทีม</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="rate-form">
                    <div class="row bg-soft-gray">
                        <div class="col-md-4 col-sm-4 col-xs-4">ทีมเหย้า</div>
                        <div class="col-md-4 col-sm-4 col-xs-4">ราคา</div>
                        <div class="col-md-4 col-sm-4 col-xs-4">ทีมเยือน</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4 rd-group">
                            <input type="radio" name="match_continue" id="rad_home_team" value="1" />
                            <label class="rd" for="rad_home_team"><span id="home_team_span"></span></label>
                            <div class="check"></div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <span class="bargain-price"></span>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 rd-group">
                            <input type="radio" name="match_continue" id="rad_away_team" value="2" />
                            <label class="rd" for="rad_away_team"><span id="away_team_span"></span></label>
                            <div class="check"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <input type="hidden" id="ffp_detail_id" value="" />
                            <input type="hidden" name="match_time" id="match_time">
                            <input type="hidden" id="league_name" value="" />
                            <input type="hidden" id="bargain_price" value="" />
                            <input type="hidden" id="home_team" value="" />
                            <input type="hidden" id="away_team" value="" />
                            <input type="hidden" id="match_continue_org" value="" />
                            <button type="button" class="btn btn-secondary no-round no-border choose-bet" data-dismiss="modal">ยกเลิก&nbsp;<i class="fa fa-times-circle"></i></button>
                            <button type="button" class="btn active-gd text-white no-round no-border choose-bet" id="choose_bet" onclick="confirmBet()" disabled>ดำเนินการต่อ&nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@php
    $httpHost = 'http://';

    if (env('APP_ENV') === 'production') {
        $httpHost = 'https://';
    } else {
        $httpHost = 'http://';
    }
@endphp
<input type="hidden" id="run_as" value="{{ env('RUN_AS') }}">
<input type="hidden" id="api_host" value="{{ env('API') }}">
<input type="hidden" id="http_host" value="{{ $httpHost . $domain }}" />
<input type="hidden" id="graph_icon" value="{{ asset('images/graph-icon.png') }}" />
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/league-slider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
    <script>
        var thisHost = $('#http_host').val();
        var baseURL = $('#base_url').val();
        var fakeIp = '58.18.145.72';

        // --- start test call main API --- //
        // var runAs = $('#run_as').val();

        // if (runAs == 'local') {
        //     baseURL = $('#api_host').val();
        // }
        // --- end test call main API --- //

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            clearSlide();

            $('#register_area').hide();

            $('.login-sec').on('click', function() {
                $('#login_area').show();
                $('#register_area').hide();
                $(this).addClass('diy-active');
                $('.regis-sec').removeClass('diy-active');
            });

            $('.regis-sec').on('click', function() {
                $('#login_area').hide();
                $('#register_area').show();
                $(this).addClass('diy-active');
                $('.login-sec').removeClass('diy-active');
            });

            $('#register_btn').on('click', function() {
                registerForm();
            });
            
            // const url = window.location.toString();

            $('input[name="match_continue"]').click(function () {
                $('#choose_bet').removeAttr('disabled');
            });

            predictionDatas(fakeIp, baseURL);
        });

        function registerForm() {
            var username = $('#regis_username').val();
            var password = $('#regis_password').val();
            var confirmPassword = $('#confirm_password').val();
            var displayName = $('#regis_display_name').val();
            var lineId = $('#regis_line_id').val();
            var tel = $('#regis_tel').val();
            var noError = 0;
            var message = '';

            if (! username.trim()) {
                noError++;
                message += '<li>กรุณากรอก Username</li>';
            }

            if (! password.trim()) {
                noError++;
                message += '<li>กรุณากรอก Password</li>';
            } else {
                if (password.trim() != confirmPassword.trim()) {
                    noError++;
                    message += '<li>Password ไม่ตรงกัน</li>';
                }
            }

            if (! displayName.trim()) {
                noError++;
                message += '<li>กรุณากรอก Display name</li>';
            }

            if (! lineId.trim()) {
                noError++;
                message += '<li>กรุณากรอก Line ID</li>';
            }

            if (! tel.trim()) {
                noError++;
                message += '<li>กรุณากรอก Tel</li>';
            }

            if (noError == 0) {
                var params = {
                    username: username,
                    password: password,
                    display_name: displayName,
                    line_id: lineId,
                    tel: tel
                };

                callApiRegister(params);
            } else {
                var ul = '<ul>' + message + '</ul>';
                showWarning('แจ้งเตือน', ul);
            }
        }

        function callApiRegister(params) {
            $.ajax({
                url: $('#base_url').val() + '/api/register-normal',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);
                    var total = response.total;
                    if (total != 0) {
                        saveSuccess();
                        setTimeout(function () {
                            location.reload();
                            // window.location = $('#base_url').val() + '/admin/article';
                        }, 2000);
                    } else {
                        showWarning('การสมัครสมาชิกผิดพลาด', response.message);
                    }
                },
                error: function(response) {
                    // console.log(response);
                    showWarning('การสมัครสมาชิกผิดพลาด', 'ระบบขัดข้อง กรุณาติดต่อผู้ดูแลระบบ!');
                }
            });
        }

        function setModalData(ffp_detail_id, bargain_price, home_team, away_team, league_name, match_time, match_continue) {
            $('#ffp_detail_id').val(ffp_detail_id);
            $('#league_name').val(league_name);
            $('#bargain_price').val(bargain_price);
            $('#home_team').val(home_team);
            $('#away_team').val(away_team);
            $('.bargain-price').html(bargain_price);
            $('#match_time').val(match_time);
            $('#match_continue_org').val(match_continue);

            var textHome = (match_continue == 1) ? '<span class="text-danger">' + home_team + '</span>' : home_team;
            var textAway = (match_continue == 2) ? '<span class="text-danger">' + away_team + '</span>' : away_team;
            $('#home_team_span').html(textHome);
            $('#away_team_span').html(textAway);

            $.ajax({
                url: $('#base_url').val() + '/check-login',
                type: 'POST',
                data: {},
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);
                    var stt = response.total;
                    afterCheckLogin(stt);
                },
                error: function(response) {
                    // console.log(response);
                    // showRequestWarning(response);
                    var stt = (response.status == 200) ? 1 : 0;
                    afterCheckLogin(stt);
                }
            });
        }

        function afterCheckLogin(stt) {
            if (stt == 1) {
                $('#betModal').modal();
            } else {
                $('#username').focus();
            }
        }

        function confirmBet() {
            const match_continue = $('input[name="match_continue"]:checked').val();
            if (match_continue != undefined) {
                Swal.fire({
                    title: 'ท่านต้องการดำเนินการต่อหรือไม่?',
                    text: "",
                    type: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {
                    if (result.value) {
                        bet(match_continue);
                    }
                });
            } else {
                Swal.fire({
                    title: 'เกิดความผิดพลาด',
                    text: "กรุณาเลือกทีม",
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'ตกลง'
                });
            }
        }

        function bet(match_continue) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();

                    const params = {
                        // _token: $('meta[name="csrf-token"]').attr('content'),
                        'member_id': $('#member_id').val(),
                        'ffp_detail_id': $('#ffp_detail_id').val(),
                        'match_continue_org': $('#match_continue_org').val(),
                        'match_continue': match_continue,
                        'match_time': $('#match_time').val(),
                        'league_name': $('#league_name').val(),
                        'home_team': $('#home_team').val(),
                        'away_team': $('#away_team').val(),
                        'bargain_price': $('#bargain_price').val()
                    };

                    $.ajax({
                        url: $('#base_url').val() + '/api/bet',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total == 1) {
                                Swal.fire({
                                    title: 'ดำเนินการสำเร็จ',
                                    text: "",
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('เกิดความผิดพลาด', response.message, 'warning');
                            }
                        }
                    });
                }
            });
        }

        function predictionDatas(ip, baseURL) {
            const params = {
                'ip': ip
            };
 
            $.ajax({
                url: baseURL + '/api/prediction',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    $('#tbody_prediction_table').remove();

                    arrangeTopContent(response.datas, ip);
                    saveToPredictionTemp(response.latest_dir, response.datas);
                },
                error: function(response) {
                    $('#tbody_prediction_table').remove();
                    console.log(response);
                    // $('.graph-loading').hide();
                    // $('.crd-content').css('visibility', 'visible');
                }
            });
        }

        function arrangeTopContent(datas, ip) {
			var predictionListBig = [];
			var predictionListSmall = [];

            if (datas.length > 0) {
                var tr = '';

                for (var n = 0; n < datas.length; n++) {
                    var topData = datas[n];

                    if (topData.league_name) {
                        var lName = topData.league_name;
                        var rows = topData.rows;

                        tr += '<thead>';
                        tr +=   '<tr class="league-name">';
                        tr +=       '<th colspan="5">' + lName + '</th>';
                        tr +=   '</tr>';
                        tr +=   '<tr class="league-name-th">';
                        tr +=       '<th scope="col">วันเวลา</th>';
                        tr +=       '<th scope="col">ทีมเหย้า</th>';
                        tr +=       '<th scope="col">ราคาบอล</th>';
                        tr +=       '<th scope="col">ทีมเยือน</th>';
                        tr +=       '<th scope="col"></th>';
                        tr +=   '</tr>';
                        tr += '</thead>';

                        tr += '<tbody>';

                        for (var i = 0; i < rows.length; i++) {
                            var rowData = rows[i];
                            var evTime = rowData.date_time_before;

                            var score = (rowData.datas.asian.score) ? rowData.datas.asian.score : 0;
                            var matchContinue = (rowData.datas.asian.team_cont) ? rowData.datas.asian.team_cont : 0;
                            var hRed = (matchContinue == 1) ? 'text-danger' : '';
                            var aRed = (matchContinue == 2) ? 'text-danger' : '';
                            var params = '\'' + rowData.id + '\',\'' + score + '\',\'' + rowData.home_team + '\',\'' + rowData.away_team + '\',\'' + lName + '\',\'' + evTime + '\',\'' + matchContinue + '\'';
    

                            tr  += '<tr>';
                            tr  +=  '<td>' + evTime + '</td>';
                            tr  +=  '<td><div class="ha-team ' + hRed + '">' + rowData.home_team + '</div></td>';
                            tr  +=  '<td>' + score + '</td>';
                            tr  +=  '<td><div class="ha-team ' + aRed + '">' + rowData.away_team + '</div></td>';
                            tr  +=  '<td class="no-padd text-center">';
                            tr  +=      '<div class="box-btn-link">';
                            tr  +=          '<button type="button" class="btn active-gd text-white no-round no-border predict" onclick="setModalData(' + params +')" ' + rowData.disabled +'>ทายผลบอล</button>';
                            tr  +=           '<a href="' + thisHost + '/ราคาบอลไหล?link=' + rowData.id + '" target="_BLANK"><img src="' + $('#graph_icon').val() + '" height="40"></a>';
                            tr  +=      '</div>';
                            tr  +=  '</td>';
                            tr  += '</tr>';

							var idxPosition = lName.search("e-");
                            var findStar = evTime.includes("*");

                            if (idxPosition == -1 && !findStar && score != 0 
                                // && (lName == 'SPAIN LA LIGA' || lName == 'GERMANY BUNDESLIGA' || lName == 'ENGLISH PREMIER LEAGUE')
                            ) {
                                var obj = {
                                    ffp_detail_id: rowData.id,
                                    league_name: lName,
                                    match_time: evTime,
                                    home_team: rowData.home_team,
                                    away_team: rowData.away_team,
                                    bargain_price: score,
                                    match_continue: matchContinue
                                };

                                if (lName == 'SPAIN LA LIGA' || lName == 'GERMANY BUNDESLIGA' || lName == 'ENGLISH PREMIER LEAGUE') {
                                    predictionListBig.push(obj);
                                } else {
                                    predictionListSmall.push(obj);
                                }
                            }
                        }

                        tr += '</tbody>';
                    }
                }

                $('#table_prediction_list').append(tr);
            }

            var today = new Date();
            // var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

            if (today.getHours() >= 13) {
                var predictionList = predictionListBig.concat(predictionListSmall);
                console.log(predictionListBig.length, predictionListSmall.length, predictionList.length);
                saveToTded(predictionList);
            }
        }

        function saveToPredictionTemp(latest_dir, final_list) {
            var params = {
                latest_dir: latest_dir,
                final_list: JSON.stringify(final_list)
            };

            $.ajax({
                url: baseURL + '/api/save-to-prediction-temp',
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

        function saveToTded(predictionList) {
            var params = {
                datas: predictionList
            };

            $.ajax({
                url: baseURL + '/api/save-to-tded',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
@stop