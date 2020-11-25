@extends('backend/layouts.master')

@section('title', 'ตั้งค่าทั่วไป')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">ตั้งค่าทั่วไป</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="general_form">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">โลโก้ของเว็บไซต์&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="flex-card">
                                                        <div class="box-picture web-logo">
                                                            <div class="box-preview">
                                                                @if($form->showImage)
                                                                    <img src="{{ $form->showImage }}" />
                                                                @else
                                                                    <i class="fa fa-camera fa-3x"></i>
                                                                @endif
                                                            </div>
                                                            <div class="box-btn">
                                                                <div class="btn btn-sm btn-file">
                                                                    <a class="file-input-wrapper">Browse
                                                                        <input type="file" accept="image/*" class="input-type-file" name="logo_file" />
                                                                    </a>
                                                                </div>
                                                                <button type="button" class="btn btn-sm clear-multi-file" onclick="resetDefault($(this));">&nbsp;<i class="fa fa-trash-o"></i>&nbsp;</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                                    <div class="box-image-name">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-folder-open"></i></span>
                                                            <input type="text" class="form-control" name="img_name" id="img_name" value="{{ $form->media_name }}" placeholder="ชื่อโลโก้" />
                                                            <span class="input-group-addon" id="img_ext"></span>
                                                        </div>
                                                    </div>
                                                    <div class="box-image-alt">
                                                        <div class="input-group">
                                                            <span class="input-group-addon" id="image_alt">alt</span>
                                                            <input type="text" class="form-control" name="alt" id="alt" value="{{ $form->alt }}" placeholder="alt ของโลโก้" />
                                                        </div>
                                                    </div>
                                                    <div class="mt-5">ขนาดภาพที่เลือก กว้าง x สูง (ขนาดภาพที่เหมาะสม: <span class="dimension-color">250 x 76</span>)</div>
                                                    <div class="box-set-dimension">
                                                        <div class="journey-detail">
                                                            <input type="text" class="form-control" name="witdh" id="witdh" value="{{ $form->witdh }}" placeholder="กว้าง" readonly />
                                                        </div>&nbsp;&nbsp;x&nbsp;&nbsp;
                                                        <div class="journey-detail">
                                                            <input type="text" class="form-control" name="height" id="height" value="{{ $form->height }}" placeholder="สูง" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5 bg-grey">
                                        <label for="website_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right"></label>
                                        <p class="col-lg-9 col-md-9 col-sm-6 col-xs-12 mt-10">ข้อมูล 3 ช่องด้านล่างไม่ได้ใช้แล้ว เปลี่ยนไปใช้หน้า Home แทน</p>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5 bg-grey">
                                        <label for="website_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Site title&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="website_name" id="website_name" value="{{ $form->website_name }}" readonly maxlength="255" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5 bg-grey">
                                        <label for="website_description" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Site description&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="website_description" id="website_description" value="{{ $form->website_description }}" readonly maxlength="255" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="website_robot" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">โรบอท&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="rd-group">
                                                <input type="radio" name="publishing_web_option" id="publish" value="1" {{ ($form->website_robot == 1)? 'checked' : '' }} />
                                                <label class="rd text-success" for="publish">อนุญาต</label>
                                                <div class="check"></div>
                                            </div>
                                            <div class="rd-group">
                                                <input type="radio" name="publishing_web_option" id="no_publish" value="0" {{ ($form->website_robot == 0)? 'checked' : '' }} />
                                                <label class="rd text-danger" for="no_publish">ไม่อนุญาต</label>
                                                <div class="check"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="website_gg_analytics" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">รหัส UA ของ Google Analytics&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="website_gg_analytics" id="website_gg_analytics" value="{{ $form->website_gg_ua }}" maxlength="255" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group mt-5">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
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

            $('#general_form').on('submit', (function (e) {
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

                                if (this.width == 250 && this.height == 76) {
                                    $('.dimension-color').css('color', 'green');
                                } else {
                                    $('.dimension-color').css('color', 'red');
                                } 
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
                        url: $('#base_url').val() + '/api/admin/general/save',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            // if (response.total == 1) {
                            //     saveSuccessReload();
                            // } else {
                            //     showWarning('Warning!', response.message);
                            // }
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