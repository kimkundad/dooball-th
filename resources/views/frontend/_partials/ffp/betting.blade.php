@if(count($non_live_datas) > 0)
    @foreach($non_live_datas as $ball)
        {{-- <tr class="non-live-market">
            <td colspan="5">{{ $ball['top_head'] }}</td>
        </tr> --}}
        @if(count($ball['league_list']) > 0)
            @foreach($ball['league_list'] as $matches)
                @if(count($matches) > 0)
                    @foreach($matches as $match)
                        {{-- <tr class="nl-market-league">
                            <td colspan="5">{{ $match['name'] }}</td>
                        </tr> --}}
                        @if(count($match['league_row']) > 0)
                            @foreach($match['league_row'] as $row)
                                @if(array_key_exists('match_result', $row))
                                    <div class="db-collapse">
                                        <div class="db-match">
                                            <span class="match-time d-flex just-between">
                                                <span>{{ $row['match_result'] }}</span>
                                                <span>{!! $row['date_time'] !!}</span>    
                                            </span>
                                            <span class="home-team d-flex">
                                                <span class="w-60-pc">{{ $row['left_team_name'] }}</span>
                                                <span class="w-40-pc d-flex just-end ffp-score">
                                                    @if($row['left_team_score'])
                                                        <span>{{ $row['left_team_score'] }}</span>
                                                    @endif
                                                    @if($row['left_last_num'])
                                                        <span>{{ $row['left_last_num'] }}</span>
                                                    @endif
                                                </span>
                                            </span>
                                            <span class="vs">Vs</span>
                                            {{--
                                                <td>
                                                    <div class="d-flex just-between">
                                                        <span>{{ $row['draw_text'] }}</span>
                                                        <span>{{ $row['draw_score'] }}</span>
                                                    </div>
                                                </td>
                                            --}}
                                            <span class="away-team d-flex">
                                                <span class="w-60-pc">{{ $row['right_team_name'] }}</span>
                                                <span class="w-40-pc d-flex just-end ffp-score">
                                                    @if($row['right_team_score'])
                                                        <span>{{ $row['right_team_score'] }}</span>
                                                    @endif
                                                    @if($row['right_last_num'])
                                                        <span>{{ $row['right_last_num'] }}</span>
                                                    @endif
                                                </span>
                                            </span>
                                            <span class="league-name">
                                                <?php
                                                    $httpHost = '';
                                                    $domain = $row['domain']; // substr(Request::root(), 7);
                                                    if (env('APP_ENV') === 'production') {
                                                        $httpHost = 'https://' . $domain;
                                                    } else {
                                                        $httpHost = 'http://' . $domain;
                                                    }
                                                ?>
                                                @if($row['link'])
                                                    {{-- <a href="http://{{ $row['domain'] }}:8001/football-price?link={{ $row['link'] }}" target="_BLANK">ดูราคาบอลไหล</a> --}}
                                                    <a href="{{ $httpHost }}/ราคาบอลไหล?link={{ $row['link'] }}" target="_BLANK">ดูราคาบอลไหล</a>
                                                    {{-- <a href="{{ URL::route('football-price-detail',['link' => $row['link']]) }}">ดูราคาบอลไหล</a> --}}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
@endif