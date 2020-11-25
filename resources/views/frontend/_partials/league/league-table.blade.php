<ul class="nav nav-tabs league-table-tab" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="all_{{ $year }}_tab" data-toggle="tab" href="#all_{{ $year }}_content" role="tab" aria-controls="all_{{ $year }}_content" aria-selected="true">Total</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="home_{{ $year }}_tab" data-toggle="tab" href="#home_{{ $year }}_content" role="tab" aria-controls="home_{{ $year }}_content" aria-selected="false">Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="away_{{ $year }}_tab" data-toggle="tab" href="#away_{{ $year }}_content" role="tab" aria-controls="away_{{ $year }}_content" aria-selected="false">Away</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="all_{{ $year }}_content" role="tabpanel" aria-labelledby="all_{{ $year }}_tab">
        <div class="table-responsive">
            <table class="table table-condensed league-table-total">
                <thead>
                    <tr>
                        <th class="ltb-no">#</th>
                        <th class="ltb-team">Team</th>
                        <th class="ltb-slot">MP</th>
                        <th class="ltb-slot">W</th>
                        <th class="ltb-slot">D</th>
                        <th class="ltb-slot">L</th>
                        <th class="ltb-slot">G</th>
                        <th class="ltb-slot">Pts</th>
                    </tr>
                </thead>
                <tbody class="tbd-league-table-{{ $year }}"></tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="home_{{ $year }}_content" role="tabpanel" aria-labelledby="home_{{ $year }}_tab">
        <div class="table-responsive">
            <table class="table table-condensed league-table-total">
                <thead>
                    <tr>
                        <th class="ltb-no">#</th>
                        <th class="ltb-team">Team</th>
                        <th class="ltb-slot">MP</th>
                        <th class="ltb-slot">W</th>
                        <th class="ltb-slot">D</th>
                        <th class="ltb-slot">L</th>
                        <th class="ltb-slot">G</th>
                        <th class="ltb-slot">Pts</th>
                    </tr>
                </thead>
                <tbody class="tbd-league-table-{{ $year }}-home"></tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="away_{{ $year }}_content" role="tabpanel" aria-labelledby="away_{{ $year }}_tab">
        <div class="table-responsive">
            <table class="table table-condensed league-table-total">
                <thead>
                    <tr>
                        <th class="ltb-no">#</th>
                        <th class="ltb-team">Team</th>
                        <th class="ltb-slot">MP</th>
                        <th class="ltb-slot">W</th>
                        <th class="ltb-slot">D</th>
                        <th class="ltb-slot">L</th>
                        <th class="ltb-slot">G</th>
                        <th class="ltb-slot">Pts</th>
                    </tr>
                </thead>
                <tbody class="tbd-league-table-{{ $year }}-away"></tbody>
            </table>
        </div>
    </div>
  </div>