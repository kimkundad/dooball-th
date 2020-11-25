@if($matches)
    <div class="d-flex df-col complex-box">
        @if($matches['total'] != 0)
            @foreach($matches['records'] as $k => $val)
                <div class="row-live-ele">
                    <div class="row-main d-flex df-row df-wrap alit-center text-white">
                        <div class="match-live-time">{{ $val->match_time }}</div>
                        <div class="h-team-name">{{ $val->home_team }}</div>
                        <div class="the-vs">VS</div>
                        <div class="a-team-name">{{ $val->away_team }}</div>
                        <div class="other-text">{{ $val->match_name }}</div>
                        <div class="btn-link-box d-flex">
                            <a href="javascript:void(0)" class="active-gd text-center text-white"><i class="fa fa-play-circle"></i></a>
                        </div>
                    </div>
                    <div class="live-ball">
                        @if($val->sponsor_links)
                            @foreach($val->sponsor_links as $ele)
                                @php
                                    $link = preg_replace('/#[^\s]+/', '', $ele->url ); // remove => #https://www.ballzaa.com/linkdooball.php
                                @endphp
                                <div class="link">
                                    <a href="{{ $link }}" class="d-flex alit-center" target="_BLANK">{{ $ele->name }}</a>
                                </div>
                            @endforeach
                        @endif
                        @if($val->normal_links)
                            @foreach($val->normal_links as $e)
                                @php
                                    $lnk = preg_replace('/#[^\s]+/', '', $e->url );
                                @endphp
                                <div class="link">
                                    <a href="{{ $lnk }}" class="d-flex alit-center" target="_BLANK">{{ $e->name }}</a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <h4 class="text-muted text-center my-2">{{ $message }}</h4>
        @endif
    </div>
@else
    <h4 class="text-muted text-center my-2">{{ $message }}</h4>
@endif