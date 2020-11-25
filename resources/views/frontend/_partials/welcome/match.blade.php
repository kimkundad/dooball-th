<div class="match-over">
    @if($matches)
        @if($matches['total'] != 0)
            <div class="db-collapse">
                <div class="db-match match-0">
                    <span class="match-time">วันเวลา</span>
                    <span class="home-team">ทีมเหย้า</span>
                    <span class="vs">Vs</span>
                    <span class="away-team">ทีมเยือน</span>
                    <span class="league-name">ชื่อลีก</span>
                </div>
            </div>

            @foreach($matches['records'] as $k => $val)
                <div class="db-collapse">
                    <div class="db-match match-{{ ($k+1) }}">
                        <span class="match-time">{{ $val->match_time }}</span>
                        <span class="home-team">{{ $val->home_team }}</span>
                        <span class="vs">Vs</span>
                        <span class="away-team">{{ $val->away_team }}</span>
                        <span class="league-name">{{ $val->match_name }}</span>
                    </div>
                    <div class="db-content content-{{ ($k+1) }}">
                        @if($val->sponsor_links)
                            @foreach($val->sponsor_links as $ele)
                                <p><a href="{{ $ele->url }}" target="_BLANK">{{ $ele->name }}</a></p>
                            @endforeach
                        @endif
                        @if($val->normal_links)
                            @foreach($val->normal_links as $e)
                                <p><a href="{{ $e->url }}" target="_BLANK">{{ $e->name }}</a></p>
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
    @else
        <div class="col-12 pt-24 pb-24">
            <h4>-- ไม่พบรายการแข่งขันในวันนี้ --</h4>
        </div>
    @endif
</div>