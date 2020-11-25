@extends('backend/layouts.master')

@section('title', 'แก้ไข Text')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/text"><span class="icon icon-beaker"></span>Text ทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไข Text</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="text_form">
                                    @csrf
                                    <input type="hidden" name="text_id" value="{{$form->id}}" />
                                    <div class="form-group">
                                        <label for="text" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Text&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="text" id="text" value="{{$form->text}}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group mt-5">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/text"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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

            $('#text_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการทำรายการ?',
                    // text: "You won't be able to revert this!",
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

            checkImageDimensions($('#witdh').val(), $('#height').val(), 120, 120);
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
                                checkImageDimensions(this.width, this.height, 120, 120);
                            };
                        };
                        reader.readAsDataURL(file);
                        const arrImg = name.split('.');
                        // console.log(arrImg[arrImg.length-1]);
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
                        url: $('#base_url').val() + '/api/admin/text/save-update',
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
                                    window.location = $('#base_url').val() + '/admin/text';
                                }, 2000);
                            } else {
                                showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
                            }
                        }
                    });
                }
            });
            return false;
        }
    </script>
@stop