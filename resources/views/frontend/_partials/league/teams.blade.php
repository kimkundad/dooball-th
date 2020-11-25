<ul class="nav nav-tabs tsc-tab mt-1" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link d-flex alit-center active" id="star_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#star_content_{{ $year }}" role="tab" aria-controls="star_content_{{ $year }}" aria-selected="true">
            <i class="fa fa-futbol"></i>
            <span class="ml-1 text-bold">ดาวซัลโว</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex alit-center" id="assist_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#assist_content_{{ $year }}" role="tab" aria-controls="assist_content_{{ $year }}" aria-selected="true">
            <img src="{{ asset('images/stud-shoe.png') }}" alt="" width="25">
            <span class="ml-1 text-bold">แอสซิสต์</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex alit-center" id="chance_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#chance_content_{{ $year }}" role="tab" aria-controls="chance_content_{{ $year }}" aria-selected="true">
            <i class="fa fa-walking"></i>
            <span class="ml-1 text-bold">โอกาสยิง</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex alit-center" id="frame_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#frame_content_{{ $year }}" role="tab" aria-controls="frame_content_{{ $year }}" aria-selected="true">
            <img src="http://localhost/images/box-square.png">
            <span class="ml-1 text-bold">เข้ากรอบ</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex alit-center" id="yellow_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#yellow_content_{{ $year }}" role="tab" aria-controls="yellow_content_{{ $year }}" aria-selected="true">
            <img src="http://localhost/images/yellow-card.png" width="25">
            <span class="ml-1 text-bold">ใบเหลือง</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex alit-center" id="red_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#red_content_{{ $year }}" role="tab" aria-controls="red_content_{{ $year }}" aria-selected="true">
            <img src="http://localhost/images/red-card.png" width="25">
            <span class="ml-1 text-bold">ใบแดง</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex alit-center" id="time_tab_{{ $year }}" data-index="0" data-toggle="tab" href="#time_content_{{ $year }}" role="tab" aria-controls="time_content_{{ $year }}" aria-selected="true">
            <i class="far fa-clock"></i>
            <span class="ml-1 text-bold">เวลาลงเล่น</span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="star_content_{{ $year }}" role="tabpanel" aria-labelledby="star_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    @if ($year == 2020)
                        <td>
                            <div class="table-loading">
                                <div class="l-one"></div>
                                <div class="l-two"></div>
                            </div>
                        </td>
                    @else
                        <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="assist_content_{{ $year }}" role="tabpanel" aria-labelledby="assist_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="chance_content_{{ $year }}" role="tabpanel" aria-labelledby="chance_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="frame_content_{{ $year }}" role="tabpanel" aria-labelledby="frame_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="yellow_content_{{ $year }}" role="tabpanel" aria-labelledby="yellow_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="red_content_{{ $year }}" role="tabpanel" aria-labelledby="red_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="time_content_{{ $year }}" role="tabpanel" aria-labelledby="time_tab_{{ $year }}">
        <div class="table-responsive">
            <table class="table table-condensed topscore-table text-white">
                <tr>
                    <td class="text-muted text-center"><i class="fa fa-spinner text-loading-small fa-spin"></i></td>
                </tr>
            </table>
        </div>
    </div>
</div>