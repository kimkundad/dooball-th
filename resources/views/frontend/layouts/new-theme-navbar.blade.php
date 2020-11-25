<?php
    $request = request();
    $url = $request->path();
    $url = urldecode($url);

    $routeName = \Request::route()->getName();
    $currentHome = ($routeName == 'index')? 'current-nav' : '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-new-theme">
    <a class="navbar-brand active-gd" href="{{ url('/') }}">Dooball</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <!-- <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li> -->

            <li class="nav-item">
                <a class="nav-link" href="{{ route('game-free-credit') }}">เกมฟรีเครดิต</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('football-price') }}">ราคาบอล</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('livescore') }}">ผลบอลสด</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex alit-center" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ทีเด็ดบอล
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('tdedball') }}">ทีเด็ดบอล</a>
                    <a class="dropdown-item" href="{{ route('tdedball-cont') }}">ทีเด็ดบอลต่อ</a>
                    <a class="dropdown-item" href="{{ route('tdedball-not-cont') }}">ทีเด็ดบอลรอง</a>
                    <a class="dropdown-item" href="{{ route('tdedball-teng') }}">ทีเด็ดบอลเต็ง</a>
                    <a class="dropdown-item" href="{{ route('tdedball-step-two') }}">ทีเด็ดบอลสเต็ป2</a>
                    <a class="dropdown-item" href="{{ route('tdedball-step-three') }}">ทีเด็ดบอลสเต็ป3</a>
                </div>
            </li>

            {{-- @if($pages)
                @if (count($pages) > 0)
                    @foreach($pages as $val)
                        @php
                            $currentNav = (strpos($url, $val->slug) !== false) ? 'current-nav' : '';
                        @endphp
                        <li class="nav-item {{ $currentNav }}">
                            <a class="nav-link" href="{{ url('/' . $val->slug) }}">{{ $val->page_name }}</a>
                        </li>
                    @endforeach
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="#">ทีเด็ดล้มโต๊ะ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">กราฟบอลไหล</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('livescore') }}">ผลบอล</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">วิเคราะห์บอล</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">ไฮไลท์บอล</a>
                </li>
            @endif --}}
        </ul>
    </div>
</nav>
<input type="hidden" id="base_url" value="{{ URL::to('/') }}" />