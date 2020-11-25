<div class="table-responsive">
    <table class="table table-condensed table-striped text-white table-prediction" id="table_prediction_list">
        @php
            $index = 0;
        @endphp

        @if(count($predictions) > 0)
            @foreach($predictions as $ktm => $v)
                @php
                    $topData = $predictions[$ktm];
                @endphp

                @if ($topData['league_name'])
                    @php
                        $lName = $topData['league_name'];
                        $rows = $topData['rows'];
                    @endphp

                    <thead>
                        <tr class="league-name">
                            <th colspan="5">{{ $lName }}</th>
                        </tr>
                        <tr class="league-name-th">
                            <th scope="col">วันเวลา</th>
                            <th scope="col">ทีมเหย้า</th>
                            <th scope="col">ราคาบอล</th>
                            <th scope="col">ทีมเยือน</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $rowData)
                            @php
                                $matchContinue = 0;
                                $score = ($rowData['datas']['asian']['score']) ? $rowData['datas']['asian']['score'] : 0;
                                $params = '\'' . $rowData['id'] . '\',\'' . $score . '\',\'' . $rowData['home_team'] . '\',\'' . $rowData['away_team'] . '\',\'' . $lName . '\',\'' . $rowData['event_time'] . '\',\'' . $matchContinue . '\'';
                            @endphp
                            <tr id="start_record_{{ $index }}">
                                <td>{{ $rowData['event_time'] }}</td>
                                <td>{{ $rowData['home_team'] }}</td>
                                <td id="pre_{{ $index }}">
                                    {{-- <i class="fa fa-circle-notch fa-spin text-muted"></i> --}}
                                    {{ $score }}
                                </td>
                                <td>{{ $rowData['away_team'] }}</td>
                                <td class="no-padd text-center" id="btn_{{ $index }}">
                                    {{-- <i class="fa fa-circle-notch fa-spin text-muted mt-2"></i> --}}
                                    <button type="button" class="btn active-gd text-white no-round no-border predict" onclick="setModalData({{ $params }})" {{ $rowData['disabled'] }} >ทายผลบอล</button>
                                </td>
                            </tr>
                            {{-- scoreInfo({{ $index }}, rowData, ip, lName); --}}
                        @endforeach
                    </tbody>
                @endif
                {{-- <tbody>
                    @foreach($v['datas'] as $data)
                        @php
                            $home_team = $data['home_team'];
                            $away_team = $data['away_team'];
                            if ((int)$data['match_continue'] == 1) {
                                $home_team = '<span class="continue">' . $data['home_team'] . '</span>';
                            } else {
                                $away_team = '<span class="continue">' . $data['away_team'] . '</span>';
                            }
                            $params = $data['id'] . ',\'' . $data['bargain_price'] . '\',\'' . $data['home_team'] . '\',\'' . $data['away_team'] . '\',' . (int)$data['match_continue'];
                            $disabled = ((int)$data['left'] > 30) ? '' : 'disabled';
                        @endphp
                        <tr>
                            <td>{{ $data['match_time'] }}</td>
                            <td>
                                {{ $home_team }}
                            </td>
                            <td>{{ $data['bargain_price'] }}</td>
                            <td>
                                {{ $away_team }}
                            </td>
                            <td class="no-padd">
                                <button type="button" class="btn active-gd text-white no-round no-border predict" onclick="setModalData({{ $params }})" {{ $disabled }}>ทายผลบอล</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody> --}}
            @endforeach
        @else
            <tbody>
                <tr>
                    <td colspan="5" class="text-center">--- ไม่มีข้อมูล ---</td>
                </tr>
            </tbody>
        @endif
        {{-- <tbody id="tbody_ffp">
            <tr>
                <td colspan="6">
                    <div class="table-loading">
                        <div class="l-one"></div>
                        <div class="l-two"></div>
                    </div>
                </td>
            </tr>
        </tbody> --}}
    </table>
</div>
  