<div class="top-ten right-team-table mt-10px">
    <h2 class="sean-tt-title">อันดับตารางคะแนนทีม {{ $team_name }}</h2>
    {{-- <div class="result-box d-flex df-col text-white">
        <div class="graph-loading">
            <div class="l-one"></div>
            <div class="l-two"></div>
        </div>
    </div> --}}

    <ul class="nav nav-tabs league-table-tab" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Total</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Away</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive">
                <table class="table table-condensed league-table-widget">
                    <thead>
                        <tr>
                            <th class="ltb-no">อันดับ</th>
                            <th class="ltb-team">ทีม</th>
                            <th class="ltb-slot">แมทช์</th>
                            {{-- <th class="ltb-slot">W</th>
                            <th class="ltb-slot">D</th>
                            <th class="ltb-slot">L</th> --}}
                            <th class="ltb-slot">*/-</th> {{-- G --}}
                            <th class="ltb-slot">คะแนน</th>
                        </tr>
                    </thead>
                    <tbody class="tbd-league-table"></tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive">
                <table class="table table-condensed league-table-widget">
                    <thead>
                        <tr>
                            <th class="ltb-no">อันดับ</th>
                            <th class="ltb-team">ทีม</th>
                            <th class="ltb-slot">แมทช์</th>
                            {{-- <th class="ltb-slot">W</th>
                            <th class="ltb-slot">D</th>
                            <th class="ltb-slot">L</th> --}}
                            <th class="ltb-slot">*/-</th> {{-- G --}}
                            <th class="ltb-slot">คะแนน</th>
                        </tr>
                    </thead>
                    <tbody class="tbd-league-table-home"></tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="table-responsive">
                <table class="table table-condensed league-table-widget">
                    <thead>
                        <tr>
                            <th class="ltb-no">อันดับ</th>
                            <th class="ltb-team">ทีม</th>
                            <th class="ltb-slot">แมทช์</th>
                            {{-- <th class="ltb-slot">W</th>
                            <th class="ltb-slot">D</th>
                            <th class="ltb-slot">L</th> --}}
                            <th class="ltb-slot">*/-</th> {{-- G --}}
                            <th class="ltb-slot">คะแนน</th>
                        </tr>
                    </thead>
                    <tbody class="tbd-league-table-away"></tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="{{ url($url . '/table') }}" class="btn active-gd btn-center mt-4 text-white no-round text-ok">ดูตารางคะแนนรวม</a>
</div>