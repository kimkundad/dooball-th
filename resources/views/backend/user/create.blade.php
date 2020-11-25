@extends('backend/layouts.master')

@section('title', 'เพิ่มผู้ใช้งาน')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/settings/user"><span class="icon icon-beaker"></span>ผู้ใช้งานทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มผู้ใช้งาน</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="user_form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="role" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">บทบาท&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <select class="form-control" name="role" id="role">
                                                <option value="Member">สมาชิก</option>
                                                <option value="Admin">ผู้ดูแลระบบ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="username" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อผู้ใช้งาน (Username)&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="username" id="username" value="" maxlength="50" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="password" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">รหัสผ่าน&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" id="password" value="" maxlength="50" aria-describedby="basic-addon1">
                                                <div class="input-group-addon c-pointer" id="basic-addon1" onclick="switchEyeOne();"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="password_confirmation" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ยืนยัน รหัสผ่าน&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="" maxlength="50" aria-describedby="basic-addon2">
                                                <div class="input-group-addon c-pointer" id="basic-addon2" onclick="switchEyeTwo();"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <span class="text-danger" id="warning_password_confirmation"></span>
                                        </div>
                                    </div>
                                    {{-- <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="email" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">อีเมล์&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="email" id="email" value="" maxlength="200" />
                                        </div>
                                    </div> --}}
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="first_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อ (First name)&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="first_name" id="first_name" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">นามสกุล&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="last_name" id="last_name" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="screen_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อในเกมส์&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="screen_name" id="screen_name" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="user_status" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">สถานะ&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="user_status" id="user_status">
                                                <option value="1">ใช้งาน</option>
                                                <option value="2">ไม่ใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-sm btn-default" href="{{ URL::to('/') }}/admin/settings/user"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
        var eye1 = true;
        var eye2 = true;
        $(function() {
            switchEyeOne();
            switchEyeTwo();

            $('#user_form').on('submit', (function (e) {
                let countWarning = 0;
                const pw = $('#password').val().trim();
                const rpw = $('#password_confirmation').val().trim();

                if (pw != rpw) {
                    $('#warning_password_confirmation').html('รหัสผ่านไม่ตรงกัน');
                    countWarning++;
                } else {
                    $('#warning_password_confirmation').html('');
                }

                if (countWarning == 0) {
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
                }

                return false;
            }));
        });

        function switchEyeOne() {
            eye1 = !eye1;
            if (eye1) {
                $('#basic-addon1').html('<i class="fa fa-eye"></i>');
                $('#password').prop('type', 'input');
            } else {
                $('#basic-addon1').html('<i class="fa fa-eye-slash"></i>');
                $('#password').prop('type', 'password');
            }
        }

        function switchEyeTwo() {
            eye2 = !eye2;
            if (eye2) {
                $('#basic-addon2').html('<i class="fa fa-eye"></i>');
                $('#password_confirmation').prop('type', 'input');
            } else {
                $('#basic-addon2').html('<i class="fa fa-eye-slash"></i>');
                $('#password_confirmation').prop('type', 'password');
            }
        }

        function submitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    // formData.append('method_field', 'PUT');
                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/user/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/settings/user';
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
