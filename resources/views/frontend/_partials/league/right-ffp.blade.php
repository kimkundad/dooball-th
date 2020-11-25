<div class="top-ten ffp-pml mt-10px">
    <h2 class="sean-tt-title">ราคาบอลไหล{{ $league_name }} วันนี้</h2>

    <div class="ffp-box d-flex df-col text-white">
        <div class="ele-ffp ele-right-th d-flex alit-center p-1">
            <div class="lt-name">ทีมเหย้า</div>
            <div class="ffp-vs text-white">ราคาบอล</div>
            <div class="rt-name">ทีมเยือน</div>
            <div class="ffp-g-icon text-right">% ราคาไหล</div>
        </div>

        <div class="graph-loading">
            <div class="l-one"></div>
            <div class="l-two"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-7">
            <a href="{{ url($url . '/odds') }}" class="btn btn-block active-gd mt-4 text-white no-round text-ok">บอลไหล {{ $league_name }}</a>
        </div>
        <div class="col-5">
            <a href="{{ route('football-price') }}" class="btn btn-block active-gd mt-4 text-white no-round text-ok">ราคาบอลไหล</a>
        </div>
    </div>
</div>