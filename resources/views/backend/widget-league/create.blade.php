@extends('backend/layouts.master')

@section('title', 'เพิ่มหัวข้อ Widget League')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/settings/league-decoration"><span class="icon icon-beaker"></span>Widget League ทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มหัวข้อ</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="widget_league_form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="widget_title" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">ชื่อหัวข้อ&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="widget_title" id="widget_title" value="" placeholder="ตัวอย่าง: พรีเมียร์ลีก อังกฤษ" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="league_id" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">ลีก&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="league_id" id="league_id">
                                                <option value="0">--- ไม่เลือก ---</option>
                                                @if ($leagues->count() > 0)
                                                    @foreach ($leagues->get() as $league)
                                                        <option value="{{ $league->id }}">{{ $league->name_en }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <span class="text-muted font-italic">* ถ้าไม่ได้ระบุลีก สามารถนำไปใช้ได้ทุกลีก</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="page_url" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">ใช้ในหน้า&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="page_url" id="page_url" value="" placeholder="ตัวอย่าง: result" maxlength="100" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ URL::to('/') }}/admin/settings/league-decoration"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#widget_league_form').on('submit', (function (e) {
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
                        url: $('#base_url').val() + '/api/admin/league-decoration/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/settings/league-decoration';
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