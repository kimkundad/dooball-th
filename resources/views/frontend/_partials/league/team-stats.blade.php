<div class="card no-round no-border bg-trans">
    <div class="card-header card-prediction no-round no-border text-white">
        <h2>สถิตินักเตะ สโมสร{{ $team_name }}</h2>
    </div>
    <div class="card-body">
        @if (count($tab_list) > 0)
            <ul class="nav nav-tabs team-player-stats mt-1" id="myTab" role="tablist">
                @foreach($tab_list as $k => $year)
                    <li class="nav-item">
                        <a class="nav-link {{ ($k == 0) ? 'active' : '' }}" id="y{{ $year }}-tab" data-year="{{ $year }}" league-id="{{ $league_ids[$year] }}" data-toggle="tab" href="#y{{ $year }}" role="tab" aria-controls="y{{ $year }}" aria-selected="true">
                            {{ $year }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($tab_list as $m => $year)
                    <div class="tab-pane fade {{ ($m == 0) ? 'show active' : '' }}" id="y{{ $year }}" role="tabpanel" aria-labelledby="y{{ $year }}-tab">
                        @include('frontend._partials.league.teams', ['year' => $year])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-footer no-round no-border text-center">
        <a href="{{ url($url . '/topscore') }}" class="btn active-gd mt-3 text-white no-round text-ok">สถิตินักเตะ {{ $league_name }}</a>
    </div>
</div>