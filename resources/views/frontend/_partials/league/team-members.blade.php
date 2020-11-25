<div class="card no-round no-border bg-trans">
    <div class="card-header card-prediction no-round no-border text-white">
        <h2>รายชื่อนักเตะ {{ $team_name }}</h2>
    </div>
    <div class="card-body">
        @if (count($tab_list) > 0)
            <ul class="nav nav-tabs team-members mt-1" id="myTab" role="tablist">
                @foreach($tab_list as $k => $year)
                    <li class="nav-item">
                        <a class="nav-link {{ ($k == 0) ? 'active' : '' }}" id="m{{ $year }}-squad-tab" data-year="{{ $year }}" league-id="{{ $league_ids[$year] }}" data-toggle="tab" href="#m{{ $year }}-squad-content" role="tab" aria-controls="m{{ $year }}-squad-content" aria-selected="true">
                            {{ $year }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($tab_list as $m => $year)
                    <div class="tab-pane squad text-white fade {{ ($m == 0) ? 'show active' : '' }}" id="m{{ $year }}-squad-content" role="tabpanel" aria-labelledby="m{{ $year }}-squad-tab">
                        {{-- <div class="row mt-3">
                            <div class="col-12 league-name text-bold">ผู้จัดการทีม</div>
                            <ul class="team-coach">
                                <li class="mt-2 text-center">
                                    <i class="fa fa-spinner text-loading-small fa-spin"></i>
                                </li>
                            </ul>
                        </div> --}}
                        <div class="row mt-2 team-attacker-{{ $year }}">
                            <div class="col-12 league-name text-bold">กองหน้า</div>
                            <ul class="team-attacker">
                                <li class="mt-2 text-center">
                                    <i class="fa fa-spinner text-loading-small fa-spin"></i>
                                </li>
                            </ul>
                        </div>
                        <div class="row mt-2 team-midfielder-{{ $year }}">
                            <div class="col-12 league-name text-bold">กองกลาง</div>
                            <ul class="team-midfielder">
                                <li class="mt-2 text-center">
                                    <i class="fa fa-spinner text-loading-small fa-spin"></i>
                                </li>
                            </ul>
                        </div>
                        <div class="row mt-2 team-defender-{{ $year }}">
                            <div class="col-12 league-name text-bold">กองหลัง</div>
                            <ul class="team-defender">
                                <li class="mt-2 text-center">
                                    <i class="fa fa-spinner text-loading-small fa-spin"></i>
                                </li>
                            </ul>
                        </div>
                        <div class="row mt-2 team-goalkeeper-{{ $year }}">
                            <div class="col-12 league-name text-bold">ประตู</div>
                            <ul class="team-goalkeeper">
                                <li class="mt-2 text-center">
                                    <i class="fa fa-spinner text-loading-small fa-spin"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    {{-- <div class="card-footer no-round no-border text-center">
        <a href="#" class="btn active-gd mt-3 text-white no-round text-ok">รายชื่อนักเตะทั้งหมดใน{{ $team_name }}</a>
    </div> --}}
</div>