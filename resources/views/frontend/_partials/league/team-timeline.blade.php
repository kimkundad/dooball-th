<div class="top-ten team-timeline mt-10px">
    <h2 class="sean-tt-title">ผลบอล โปรแกรมบอล {{ $team_name }}</h2>

    <div class="team-timeline-box d-flex df-col text-white">
        <div class="graph-loading">
            <div class="l-one"></div>
            <div class="l-two"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <a href="{{ url($url . '/result') }}" class="btn btn-block active-gd mt-4 text-white no-round text-ok">ผลบอลย้อนหลัง</a>
        </div>
        <div class="col-6">
            <a href="{{ url($url . '/program') }}" class="btn btn-block active-gd mt-4 text-white no-round text-ok">โปรแกรมบอล</a>
        </div>
    </div>
</div>