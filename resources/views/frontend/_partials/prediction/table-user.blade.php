<div class="table-responsive">
    <table class="table table-condensed table-striped text-white table-predict-user">
        <thead>
            <tr>
                <th class="th-num">ลำดับ</th>
                <th class="th-name">รายชื่อสมาชิก</th>
                <th class="th-stats">สถิติ 10 นัดล่าสุด</th>
                <th class="th-today">วันนี้เล่นทีม</th>
                <th class="th-link">สถิติ</th>
            </tr>
        </thead>
        <tbody>
            @if(count($bets) > 0)
                @foreach($bets as $k => $val)
                    <tr>
                        <td scope="col">{{ ($current_page * $perpage) - $perpage + ($k+1) }}</td>
                        <td scope="col">{{ $val['screen_name'] }}</td>
                        <td scope="col">
                            <div class="d-flex bet-hist">
                                {!! $val['bet_hist'] !!}
                                {{-- ({{ $val['win_rate'] }}) ({{ $val['win_string'] }}) --}}
                            </div>
                        </td>
                        <td scope="col">{{ $val['team_name'] }}</td>
                        <td scope="col">
                            @if ($val['username'])
                                <a href="bet-stats/{{ $val['username'] }}" class="c-theme" target="_BLANK">สถิติ</a>
                            @else
                                <a href="bet-stats-user/{{ $val['user_id'] }}" class="c-theme" target="_BLANK">สถิติ</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">--- ไม่มีข้อมูล ---</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

@if(count($bets) > 0)
    <div class="clearfix"></div>
    <div class="col-12">
        <div class="page-sps d-flex alit-center just-center">
            {{ $bet_paging->onEachSide(3)->links() }}
        </div>
    </div>
@endif