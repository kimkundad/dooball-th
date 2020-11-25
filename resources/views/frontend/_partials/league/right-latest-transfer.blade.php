<div class="top-ten latest-transfer mt-10px">
    <h2 class="sean-tt-title">ซื้อขายนักเตะ ล่าสุด {{ $team_name }}</h2>

    <div class="slide-area widget flex-center">
        <div class="slide-prev flex-center">
            <button class="btn" type="button" onclick="prevWidgetSlide()">
                <i class="fa fa-chevron-left"></i>
            </button>
        </div>
        <div class="slide-outmost">
            <div class="slide-widget-cover">
                <div class="slide-box">
                    <a class="slide-ele text-center" href="#"><img src="{{ asset('images/user-avatar.png') }}" /></a>
                    <div class="row mt-3 text-white">
                        {{-- <div class="col-4"><img src="{{ asset('images/no-image.jpg') }}" width="35" /><br>ย้ายจาก</div>
                        <div class="col-4"><img src="{{ asset('images/no-image.jpg') }}" width="35" /><br>เข้าร่วม</div>
                        <div class="col-4">0.00<br>มูลค่า</div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="slide-next flex-center">
            <button class="btn" type="button" onclick="nextWidgetSlide()">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <a href="{{ url($url . '/transfer') }}" class="btn active-gd btn-center mt-4 text-white no-round text-ok">ซื้อขายนักเตะ {{ $league_name }}</a>
</div>

