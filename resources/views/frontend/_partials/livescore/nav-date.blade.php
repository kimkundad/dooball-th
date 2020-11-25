<div class="row">
    <div class="col-12">
        <div class="box-segment d-flex df-wrap">
            <div class="seg-ele">
                <a href="{{ route('live') }}" class="btn btn-live">Live</a>
            </div>
            @if(count($day_list) > 0)
                @foreach($day_list as $day)
                    <a href="{{ $day['link'] }}" class="seg-ele d-flex df-col just-center {{ $day['a'] }}">
                        <span>{{ $day['n'] }}</span>
                        <span>{{ $day['d'] }}</span>
                    </a>
                @endforeach
            @endif
            <div class="sort-time d-flex alit-center just-center text-muted"><i class="fa fa-sort-amount-up-alt"></i></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>