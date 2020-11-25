<div class="match-over">
    @if(count($matches) > 0)
        <div class="db-collapse">
            <div class="db-match match-0">
                <span class="match-time">วันเวลา</span>
                <span class="home-team">ทีมเหย้า</span>
                <span class="vs">Vs</span>
                <span class="away-team">ทีมเยือน</span>
                <span class="league-name">ชื่อลีก</span>
            </div>
        </div>

        <?php
            $common = new App\Http\Controllers\API\CommonController();
        ?>

        @foreach($matches as $k => $val)
            <div class="db-collapse">
                <div class="db-match match-{{ ($k+1) }}">
                    <span class="match-time">{{ $common->showDayOnly(strtotime($val->kickoff_on)) }}</span>
                    <span class="home-team">{{ $val->team_home_name }}</span>
                    <span class="vs">Vs</span>
                    <span class="away-team">{{ $val->team_away_name }}</span>
                    <span class="league-name">{{ $val->program_name }}</span>
                </div>
                <div class="db-content content-{{ ($k+1) }}">
                    @if($val->links)
                        @foreach($val->links as $ele)
                            <p><a href="{{ $ele->url }}" target="_BLANK">{{ $ele->name }}</a></p>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12 pt-24 pb-24">
            <h4>-- ไม่พบรายการแข่งขันในวันนี้ --</h4>
        </div>
    @endif
</div>