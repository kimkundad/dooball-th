<?php
    $request = request();
    $url = $request->path();
    $url = urldecode($url);

    $routeName = \Request::route()->getName();
    $currentHome = ($routeName == 'index')? 'current-nav' : '';
?>
{{-- start custom menu --}}
@if($pages)
    <div class="menu-wrap">
        <nav class="menu">
            <ul class="first-blood clearfix">
                <li class="{{ $currentHome }}"><a href="{{ URL('/') }}">Home</a></li>
                @if (count($pages) > 0)
                    @foreach($pages as $val)
                        <?php
                            $currentNav = (strpos($url, $val->slug) !== false) ? 'current-nav' : '';
                        ?>
                        <li class="{{ $currentNav }}"><a href="{{ URL('/') . '/' . $val->slug }}">{{ $val->page_name }}</a></li>
                        {{-- <li>
                            <a href="#">Movies <span class="arrow">&#9660;</span></a>
                            <ul class="sub-menu">
                                <li><a href="#">In Cinemas Now</a></li>
                                <li><a href="#">Coming Soon</a></li>
                                <li><a href="#">On DVD/Blu-ray</a></li>
                                <li><a href="#">Showtimes &amp; Tickets</a></li>
                            </ul>
                        </li> --}}
                    @endforeach
                @endif
            </ul>
        </nav>
    </div>

    <div class="menu-wrap-mobile">
        <button type="button" class="btn-fas" onclick="toggleNavMobile()">
            <i class="fas fa-bars"></i>
        </button>
        @if (count($pages) > 0)
            <ul>
                @foreach($pages as $val)
                    <li><a href="{{ URL('/') . '/' . $val->slug }}">{{ $val->page_name }}</a></li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
{{-- end custom menu --}}
<input type="hidden" id="base_url" value="{{ URL::to('/') }}" />