@extends('backend/layouts.master')

@section('title', 'แก้ไขป้ายกำกับ')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/tag"><span class="icon icon-beaker"></span>ป้ายกำกับทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขป้ายกำกับ</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="tag_form">
                                    @csrf
                                    <input type="hidden" name="tag_id" value="{{$form->id}}" />
                                    <div class="form-group">
                                        <label for="tag_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อแท็ก&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="tag_name" id="tag_name" value="{{$form->tag_name}}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="title" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">SEO Title&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="title" id="title" value="{{$form->title}}" maxlength="150" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="description" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">SEO Description&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="description" id="description" value="{{$form->description}}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="detail" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Content&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <textarea class="form-control" name="detail" id="detail" cols="30" rows="10">{{$form->detail}}</textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group mt-5">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/tag"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#tag_form').on('submit', (function (e) {
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

        function submitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/tag/save-update',
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
                                    window.location = $('#base_url').val() + '/admin/tag';
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