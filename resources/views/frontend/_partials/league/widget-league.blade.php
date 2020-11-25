@if(count($widget_list) > 0)
    @foreach($widget_list as $widget)
        <div class="top-ten widget-league mt-10px">
            <h2 class="sean-tt-title">{{ $widget['name'] }}</h2>
            @if (count($widget['li']) > 0)
                <ul class="right-league-stats">
                    @foreach($widget['li'] as $li)
                        <li>
                            <a href="{{ url('/') . '/' . $li->slug }}">{{ $li->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach
@endif