<div class="card no-round no-border bg-trans">
    <div class="card-header card-prediction no-round no-border text-white">
        <h2>สถิติสโมสร{{ $team_name }}</h2>
    </div>
    <div class="card-body">
        @if (count($tab_list) > 0)
            <ul class="nav nav-tabs team-score mt-1" id="myTab" role="tablist">
                @foreach($tab_list as $k => $year)
                    <li class="nav-item">
                        <a class="nav-link {{ ($k == 0) ? 'active' : '' }}" id="y{{ $year }}-score-tab" data-year="{{ $year }}" league-id="{{ $league_ids[$year] }}" data-toggle="tab" href="#y{{ $year }}-score-content" role="tab" aria-controls="y{{ $year }}-score-content" aria-selected="true">
                            {{ $year }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($tab_list as $m => $year)
                    <div class="tab-pane fade {{ ($m == 0) ? 'show active' : '' }}" id="y{{ $year }}-score-content" role="tabpanel" aria-labelledby="y{{ $year }}-score-tab">
                        <div class="row mt-3">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-6">
                                <div class="text-center c-theme text-so-big num-of-match"><i class="fa fa-spinner text-loading-small fa-spin mb-2"></i></div>
                                <div class="text-center text-white text-so-big">แข่ง</div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-6">
                                <div class="text-center c-theme text-so-big num-of-win"><i class="fa fa-spinner text-loading-small fa-spin mb-2"></i></div>
                                <div class="text-center text-white text-so-big">ชนะ</div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-6">
                                <div class="text-center c-theme text-so-big num-of-draw"><i class="fa fa-spinner text-loading-small fa-spin mb-2"></i></div>
                                <div class="text-center text-white text-so-big">เสมอ</div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-6">
                                <div class="text-center c-theme text-so-big num-of-lose"><i class="fa fa-spinner text-loading-small fa-spin mb-2"></i></div>
                                <div class="text-center text-white text-so-big">แพ้</div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-6">
                                <div class="text-center c-theme text-so-big num-of-goal"><i class="fa fa-spinner text-loading-small fa-spin mb-2"></i></div>
                                <div class="text-center text-white text-bigger">การทำประตู</div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-6">
                                <div class="text-center c-theme text-so-big num-of-defeat"><i class="fa fa-spinner text-loading-small fa-spin mb-2"></i></div>
                                <div class="text-center text-white text-bigger">โดนยิงประตู</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-footer no-round no-border text-center">
        <a href="{{ url($url . '/program') }}" class="btn active-gd mt-3 text-white no-round text-ok">ตารางคะแนนย้อนหลัง</a>
    </div>
</div>