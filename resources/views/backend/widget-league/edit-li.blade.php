@extends('backend/layouts.master')

@section('title', 'แก้ไขรายการย่อยใน' . $form->decoration_title)

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/settings/league-decoration"><span class="icon icon-beaker"></span>หัวข้อทั้งหมด</a></li>
        <li><a href="{{ URL::to('/') }}/admin/settings/league-decoration/{{ $form->decoration_id }}/items"><span class="icon icon-beaker"></span>รายการย่อยทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>{{ $form->decoration_title }}</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $form->decoration_title }}</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="widget_league_form">
                                    @csrf
                                    <input type="hidden" name="decoration_id" id="decoration_id" value="{{ $form->decoration_id }}" />
                                    <input type="hidden" name="wgl_id" id="wgl_id" value="{{ $form->id }}" />

                                    <div class="form-group">
                                        <label for="title" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">ชื่อหัวข้อ&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="title" id="title" value="{{ $form->title }}" placeholder="ตัวอย่าง: สถิตินักเตะพรีเมียร์ลีก" maxlength="125">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="slug" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">link&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="slug" id="slug" value="{{ $form->slug }}" placeholder="ตัวอย่าง: premierleague/topscore" maxlength="125">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ URL::to('/') }}/admin/settings/league-decoration/{{ $form->decoration_id }}/items"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
                        url: $('#base_url').val() + '/api/admin/league-decoration/save-li-update',
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
                                    window.location = $('#base_url').val() + '/admin/settings/league-decoration/' + $('#decoration_id').val() + '/items';
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