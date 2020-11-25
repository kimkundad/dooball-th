<div class="card no-round no-border bg-trans">
    <div class="card-header card-prediction no-round no-border text-white">
        <h2>ตลาดซื้อขายนักเตะ {{ $team_name }}</h2>
    </div>
    <div class="card-body no-round no-border no-padd">
        @if (count($tab_list) > 0)
            <ul class="nav nav-tabs player-tsf mt-1" id="myTab" role="tablist">
                @foreach($tab_list as $k => $year)
                    <li class="nav-item">
                        <a class="nav-link {{ ($k == 0) ? 'active' : '' }}" data-year="{{ $year }}" id="trans{{ $year }}-tab" data-toggle="tab" href="#trans{{ $year }}" role="tab" aria-controls="trans{{ $year }}" aria-selected="true">
                            {{ $year }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content" id="pml_tab_content">
                <div class="graph-loading">
                    <div class="l-one"></div>
                    <div class="l-two"></div>
                </div>

                @foreach($tab_list as $n => $year)
                    <div class="tab-pane fade {{ ($n == 0) ? 'show active' : '' }}" id="trans{{ $year }}" role="tabpanel" aria-labelledby="trans{{ $year }}-tab">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead class="th-transfer">
                                    <tr class="league-name text-white">
                                        <th class="text-bold font-italic text-bigger">นักเตะ</th>
                                        <th class="text-bold font-italic text-center text-bigger">ย้ายจาก</th>
                                        <th class="text-bold font-italic text-center text-bigger">เข้าร่วม</th>
                                        <th class="text-bold font-italic text-center text-bigger">มูลค่าซื้อขาย</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-trans{{ $year }} tb-transfer">
                                    <tr>
                                        <td class="text-center text-muted">-- ไม่พบข้อมูลปี {{ $year }} --</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="card-footer no-round no-border text-center">
        <a href="{{ url($url . '/transfer') }}" class="btn active-gd mt-3 text-white no-round text-ok">การซื้อขายนักเตะทั้งหมด ใน{{ $league_name }}</a>
    </div>
</div>