@extends('backend/layouts.master')

@section('title', 'เพิ่มเมนู')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/settings/menu"><span class="icon icon-beaker"></span>เมนูทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มเมนู</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="menu_form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="parent_id" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Parent menu&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="parent_id" id="parent_id">
                                                <option value="0">--- ไม่เลือก ---</option>
                                                @if ($parents)
                                                    @foreach ($parents as $parent)
                                                        <option value="{{$parent->id}}">{{$parent->menu_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="menu_name" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อเมนู&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="menu_name" id="menu_name" value="" maxlength="70" />
                                        </div>
                                    </div>
                                    <div class="clearfix no-parent"></div>
                                    <div class="form-group no-parent">
                                        <label for="menu_url" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">URL เมนู&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="menu_url" id="menu_url" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="menu_icon" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">icon&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="menu_icon" id="menu_icon" value="" maxlength="25" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="menu_seq" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ลำดับ&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="number" class="form-control" name="menu_seq" id="menu_seq" value="" min="0" max="20" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="menu_status" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">สถานะ&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="menu_status" id="menu_status">
                                                <option value="1">ใช้งาน</option>
                                                <option value="2">ไม่ใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ URL::to('/') }}/admin/settings/menu"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            checkShowInput($('#parent_id').val());

            $('#parent_id').change(function() {
                checkShowInput($(this).val());
            });

            $('#menu_form').on('submit', (function (e) {
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

        function checkShowInput(parentId) {
            if (parentId == 0) {
                    $('.no-parent').hide();
                } else {
                    $('.no-parent').show();
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
                        url: $('#base_url').val() + '/api/admin/menu/save-create',
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