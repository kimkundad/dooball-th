
@if(count($articles) > 0)
    <div class="row-article-box d-flex df-row df-wrap just-between">
        @foreach($articles as $val)
            <div class="card">
                <a href="{{ url('/' . $val->slug) }}" title="{{ $val->title }}" style="background-image: url('{{ $val->showImage }}')">
                    <figure class="zoom">
                        <img src="{{ $val->showImage }}" alt="{{ $val->alt }}">
                    </figure>
                </a>
                <div class="card-body">
                    <p class="article-title card-text">
                        <a href="{{ url('/' . $val->slug) }}">{{ $val->title }}</a>
                    </p>
                    <div class="info-box d-flex alit-end">
                        <span class="info-ele d-flex alit-end">
                            <i class="fa fa-eye text-warning"></i><span class="text-white">{{ $val->count_view }}</span>
                        </span>
                        <span class="d-flex alit-end">
                            <i class="fa fa-star text-warning"></i><span class="text-white">{{ $val->score }}</span>
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row-article-box d-flex just-center">
        <a href="/blog" class="btn active-gd read-all-article text-white">อ่านบทความทั้งหมด</a>
    </div>
@endif