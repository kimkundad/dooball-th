@extends('backend/layouts.master')

@section('title', 'เพิ่มทีม')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/team"><span class="icon icon-beaker"></span>ทีมทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มทีม</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="team_form" enctype="multipart/form-data">
                                    @csrf
                                    {{-- <div class="form-group">
                                        <label for="recommend_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Recommend name&nbsp;:</label>
                                        <div class="col-lg-4 col-md- col-sm-6 col-xs-12">
                                            <select class="form-control" id="recommend_name">
                                                <option value="-1">--- ชื่อทีมที่มีในตารางแข่งขัน ---</option>
                                                @if(count($match_list) > 0)
                                                    @foreach($match_list as $match)
                                                        <option value="{{ $match['id'] }}">{{ $match['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md- col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" id="recommend_name_th_en" value="" readonly />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div> --}}
                                    
                                    <div class="form-group">
                                        <label for="api_id" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">API ID&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="api_id" id="api_id" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="team_name_th" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อทีม (TH)&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="team_name_th" id="team_name_th" placeholder="ชื่อที่ตรงกับตาราง matches" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="team_name_en" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อทีม (EN)&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="team_name_en" id="team_name_en" placeholder="ชื่อที่ตรง API Football" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="short_name_th" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Short name&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="short_name_th" id="short_name_th" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="long_name_th" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Long name&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="long_name_th" id="long_name_th" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="search_dooball" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Search dooball&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="search_dooball" id="search_dooball" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="search_graph" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Search graph&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="search_graph" id="search_graph" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="league_url" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">League URL&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <select class="form-control" name="league_url" id="league_url">
                                                <option value="">--- เลือกลีก ---</option>
                                                @if ($leagues->count() > 0)
                                                    @foreach($leagues->get() as $league)
                                                        <option value="{{ $league->url }}">{{ $league->url }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="onpage_id" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Onpage&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <select class="form-control" name="onpage_id" id="onpage_id">
                                                <option value="-1">--- เลือกรายการ ---</option>
                                                @if ($onpages->count() > 0)
                                                    @foreach($onpages->get() as $row)
                                                        <option value="{{ $row->id }}">{{ $row->code_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">โลโก้&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                                                    <div class="flex-card">
                                                        <div class="box-picture logo">
                                                            <div class="box-preview"><i class="fa fa-camera fa-5x"></i></div>
                                                            <div class="box-btn">
                                                                <div class="btn btn-sm btn-file">
                                                                    <a class="file-input-wrapper">Browse
                                                                        <input type="file" accept="image/*" class="input-type-file" name="card_file" />
                                                                    </a>
                                                                </div>
                                                                <button type="button" class="btn btn-sm clear-multi-file" onclick="resetDefault();">&nbsp;<i class="fa fa-trash-o"></i>&nbsp;</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                                                    <div class="box-image-name">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-folder-open"></i>&nbsp;/teams/</span>
                                                            <input type="text" class="form-control" name="img_name" id="img_name" value="" placeholder="ชื่อภาพ" />
                                                            <span class="input-group-addon" id="img_ext"></span>
                                                        </div>
                                                    </div>
                                                    <div class="box-image-alt">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="image_alt">alt ของภาพ</span>
                                                            <input type="text" class="form-control" name="alt" id="alt" value="" placeholder="alt ของภาพ" />
                                                        </div>
                                                    </div>
                                                    <div><br>ขนาดภาพที่เลือก กว้าง x สูง</div>
                                                    <div class="box-set-dimension">
                                                        <div class="journey-detail">
                                                            <input type="text" class="form-control" name="witdh" id="witdh" value="" placeholder="กว้าง" readonly />
                                                        </div>&nbsp;&nbsp;x&nbsp;&nbsp;
                                                        <div class="journey-detail">
                                                            <input type="text" class="form-control" name="height" id="height" value="" placeholder="สูง" readonly />
                                                        </div>
                                                    </div>
                                                    <div class="box-dimension">ขนาดภาพที่เหมาะสม: <span class="dimension-color">122 x 122</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/team"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-scripts')
    <script>
        $(function() {
            loadDefault();

            // var name = $("#recommend_name option:selected").html();
            // $('#recommend_name_th_en').val(name);

            // $('#recommend_name').on('change', function() {
            //     if ($(this).val() != -1) {
            //         name = $("#recommend_name option:selected").html();
            //         $('#recommend_name_th_en').val(name);
            //     }
            // });

            $('#team_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการทำรายการ?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        submitForm(this);
                    }
                });

                return false;
            }));
        });

        function loadDefault() {
            $('.input-type-file').change(function (e) {
                fileChange(e);
            });
        }

        function fileChange(e) {
            var file = e.target.files[0];
            var type = (file) ? file.type : '';
            var size = (file) ? file.size : 0;
            var name = (file) ? file.name : '';
            var match = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
            if (type) {
                if ($.inArray(type.toLowerCase(), match) == -1) {
                    showWarning('Warning!', 'อนุญาติไฟล์ jpg, jpeg, png, gif เท่านั้น');
                    resetDefault();
                } else {
                    console.log(size);
                    if (size > 2097152) { // 2Mb
                        showWarning('Warning!', 'อนุญาติให้อัพโหลดไฟล์ขนาดไม่เกิน 2 MB');
                        resetDefault();
                    } else {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('.flex-card .box-preview').html('<img src="' + e.target.result + '" />');
                            $('.flex-card .clear-multi-file').css('visibility', 'visible');

                            const img = new Image;
                            img.src = e.target.result;

                            img.onload = function () {
                                $('#witdh').val(this.width);
                                $('#height').val(this.height);
                                if (this.width == 122 && this.height == 122)
                                    $('.dimension-color').css('color', 'green');
                                else
                                    $('.dimension-color').css('color', 'red');
                            };
                        };
                        reader.readAsDataURL(file);
                        const arrImg = name.split('.');
                        $('#img_name').val(arrImg[0]);
                        $('#img_ext').html('.' + arrImg[arrImg.length-1]);
                    }
                }
            }
        }

        function resetDefault() {
            $('.flex-card .clear-multi-file').css('visibility', 'hidden');
            $('.flex-card .box-preview').html('<i class="fa fa-camera fa-5x"></i>');
            $('.dimension-color').css('color', '#999999');
            $('#img_name').val('');
            $('#witdh').val('');
            $('#height').val('');
            $('.input-type-file').val('');
        }

        function submitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    formData.append('img_ext', $('#img_ext').html());

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/team/save-create',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total == 1) {
                                saveSuccess();
                                setTimeout(function () {
                                    window.history.back();
                                    window.location = $('#base_url').val() + '/admin/team';
                                }, 2000);
                            } else {
                                showWarning('Warning!', response.message);
                            }
                        },
                        error: function(response) {
                            showRequestWarning(response);
                        }
                    });
                }
            });
            return false;
        }
    </script>
@stop
