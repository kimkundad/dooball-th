@extends('backend/layouts.master')

@section('title', 'แก้ไขผู้ใช้งาน')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/settings/user"><span class="icon icon-beaker"></span>ผู้ใช้งานทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขผู้ใช้งาน</a></li>
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
                                    <input type="hidden" name="user_id" value="{{$form->id}}" />
                                    <div class="form-group">
                                        <label for="role" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">บทบาท&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="role" id="role">
                                                <option value="Member" {{ ($form->role == 'Member')? 'selected' : '' }}>สมาชิก</option>
                                                <option value="Admin" {{ ($form->role == 'Admin')? 'selected' : '' }}>ผู้ดูแลระบบ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Username&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">{{$form->username}}</div>
                                    </div>
                                    {{-- <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="email" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">อีเมล์&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">{{$form->email}}</div>
                                    </div> --}}
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="first_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อ&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="first_name" id="first_name" value="{{$form->first_name}}" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="last_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">นามสกุล&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="last_name" id="last_name" value="{{$form->last_name}}" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="screen_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อในเกมส์&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="screen_name" id="screen_name" value="{{$form->screen_name}}" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="user_status" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">สถานะ&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="user_status" id="user_status">
                                                <option value="1" {{ ((int)$form->role == 1)? 'selected' : '' }}>ใช้งาน</option>
                                                <option value="2" {{ ((int)$form->role == 2)? 'selected' : '' }}>ไม่ใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ URL::to('/') }}/admin/settings/user"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#user_form').on('submit', (function (e) {
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
                    // formData.append('method_field', 'PUT');
                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/user/save-update',
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