<div class="top-ten latest-blog mt-10px">
    <h2 class="sean-tt-title">บทความล่าสุด</h2>
    <div class="d-flex df-col text-white">
        @foreach($latest as $val)
            <div class="d-flex alit-center right-sb-ele" style="height:70px;margin-bottom: 5px;">
                <div class="d-flex alit-center just-center" style="width:35%;height:70px;overflow:hidden;">
                    <a href="{{ url('/' . $val->slug) }}">
                        <img src="{{ $val->showImage }}" alt="" class="img-fluid">
                    </a>
                </div>
                <div class="d-flex df-col" style="width:65%;padding-left:15px;">
                    <p>
                        <a href="{{ url('/' . $val->slug) }}" class="text-white">{{ $val->title }}</a>
                    </p>
                    <div class="right-sb-box d-flex df-row df-wrap alit-center">
                        <span class="info-ele d-flex alit-center">
                            <i class="fa fa-eye text-warning"></i><span class="text-white">{{ $val->count_view }}</span>
                        </span>
                        <span class="info-ele d-flex alit-center">
                            <i class="fa fa-star text-warning"></i><span class="text-white">{{ $val->score }}</span>
                        </span>
                        <span class="d-flex alit-center">
                            <i class="fa fa-edit text-warning"></i><span class="text-white">{{ $val->createdAt }}</span>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>