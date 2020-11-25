<div class="top-ten">
    <h2 class="sean-tt-title mt-2">10 อันดับสุดแม่น เกมฟรีเครดิต</h2>

    @if(count($bets) > 0)
        @foreach($bets as $idx => $val)
            <div class="row-tt-ele cute-small d-flex item-{{ $idx }}">
                <div class="player">{{ $val['screen_name'] }}</div>
                {!! $val['bet_hist'] !!}
            </div>
        @endforeach
    @endif

    <button type="button" class="btn active-gd btn-tded-all mt-4 text-white">ดูทีเด็ดเกมทั้งหมด</button>
</div>