@extends('backend/layouts.master')

@section('title', 'เพิ่มช่องรายการทีวี')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/channel"><span class="icon icon-beaker"></span>ช่องรายการทีวีทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขช่องรายการทีวี</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="channel_form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="channel_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ช่องรายการทีวี&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="channel_name" id="channel_name" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/channel"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#channel_form').on('submit', (function (e) {
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

        function submitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    // formData.append('method_field', 'PUT');
                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/channel/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/channel';
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
