<div class="ffp-outmost d-flex">
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th class="tb-time">Time</th>
                <th class="tb-team">Home team</th>
                <th class="tb-result">Result</th>
                <th class="tb-team">Away team</th>
                <th class="tb-action text-center"><i class="fa fa-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            {{-- start live market --}}
            @if(count($live_datas) > 0)
                @foreach($live_datas as $ball)
                    <tr class="live-market">
                        <td colspan="5">{{ $ball['top_head'] }}</td>
                    </tr>
                    @if(count($ball['league_list']) > 0)
                        @foreach($ball['league_list'] as $matches)
                            @if(count($matches) > 0)
                                @foreach($matches as $match)
                                    <tr class="market-league">
                                        <td colspan="5">{{ $match['name'] }}</td>
                                    </tr>
                                    @if(count($match['league_row']) > 0)
                                        @foreach($match['league_row'] as $row)
                                            @if(array_key_exists('match_result', $row))
                                                <tr>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span>{{ $row['match_result'] }}</span>
                                                            <span>{!! $row['date_time'] !!}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span>{{ $row['left_team_name'] }}</span>
                                                            <span>{{ $row['left_team_score'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span>{{ $row['draw_text'] }}</span>
                                                            <span>{{ $row['draw_score'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span class="w-70-pc">{{ $row['right_team_name'] }}</span>
                                                            <span class="w-30-pc d-flex just-between">
                                                                <span>{{ $row['right_team_score'] }}</span>
                                                                <span>{{ $row['right_last_num'] }}</span>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $row['num'] }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
            {{-- end live market --}}

            
            {{-- start non live market --}}
            @if(count($non_live_datas) > 0)
                @foreach($non_live_datas as $ball)
                    <tr class="non-live-market">
                        <td colspan="5">{{ $ball['top_head'] }}</td>
                    </tr>
                    @if(count($ball['league_list']) > 0)
                        @foreach($ball['league_list'] as $matches)
                            @if(count($matches) > 0)
                                @foreach($matches as $match)
                                    <tr class="nl-market-league">
                                        <td colspan="5">{{ $match['name'] }}</td>
                                    </tr>
                                    @if(count($match['league_row']) > 0)
                                        @foreach($match['league_row'] as $row)
                                            @if(array_key_exists('match_result', $row))
                                                <tr>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span>{{ $row['match_result'] }}</span>
                                                            <span>{!! $row['date_time'] !!}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span>{{ $row['left_team_name'] }}</span>
                                                            <span>{{ $row['left_team_score'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span>{{ $row['draw_text'] }}</span>
                                                            <span>{{ $row['draw_score'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex just-between">
                                                            <span class="w-70-pc">{{ $row['right_team_name'] }}</span>
                                                            <span class="w-30-pc d-flex just-between">
                                                                <span>{{ $row['right_team_score'] }}</span>
                                                                <span>{{ $row['right_last_num'] }}</span>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ $row['num'] }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
            {{-- end non live market --}}

        </tbody>
    </table>
</div>