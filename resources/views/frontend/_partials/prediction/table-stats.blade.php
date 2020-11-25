<div class="table-responsive">
    <table class="table table-condensed table-striped text-white table-prediction">
        <thead>
            <tr class="league-name-th">
                <th scope="col">วันที่</th>
                <th scope="col">เจ้าบ้าน</th>
                <th scope="col">อัตตราต่อรอง</th>
                <th scope="col">ทีมเยือน</th>
                <th scope="col">ทีมที่เลือก</th>
                <th scope="col">ผลการทายผล</th>
            </tr>
        </thead>
        <tbody>
            @if(count($stats) > 0)
                @php
                    $teamName = '';
                @endphp

                @foreach($stats as $k => $v)
                    @php
                        $home_team = $v->home_team;
                        $away_team = $v->away_team;

                        if ((int)$v->match_continue == 1) {
                            $home_team = '<span class="continue">' . $v->home_team . '</span>';
                        } else {
                            $away_team = '<span class="continue">' . $v->away_team . '</span>';
                        }

				        $bet_team = ($v->match_continue == 1) ? $v->home_team : $v->away_team;
                    @endphp
                        <tr>
                            <td>{{ $v->betDate }}</td>
                            <td>{!! $home_team !!}</td>
                            <td>{{ $v->bargain_price }}</td>
                            <td>{!! $away_team !!}</td>
                            <td>{{ $bet_team }}</td>
                            <td>{!! $v->show !!}</td>
                        </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">--- ไม่มีข้อมูล ---</td>
                </tr>
            @endif
      </tbody>
  </table>
</div>