@extends('backend/layouts.master')

@section('title', 'แก้ไขลีก')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/tournament"><span class="icon icon-beaker"></span>ลีกทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขลีก</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="tournament_form">
                                    @csrf
                                    <input type="hidden" name="league_id" value="{{ $form->id }}" />

                                    <div class="form-group">
                                        <label for="name_th" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">ชื่อ (TH)&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="name_th" id="name_th" value="{{ $form->name_th }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="name_en" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">ชื่อ (EN)&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="name_en" id="name_en" value="{{ $form->name_en }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="api_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">ชื่อ (API)&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="api_name" id="api_name" value="{{ $form->api_name }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="short_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">Short name&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="short_name" id="short_name" value="{{ $form->short_name }}" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="long_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">Long name&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="long_name" id="long_name" value="{{ $form->long_name }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="url" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">URL&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="url" id="url" value="{{ $form->url }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="years" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">Years&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="years" id="years" value="{{ $form->years }}" maxlength="255" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="onpage_id" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Onpage&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="onpage_id" id="onpage_id">
                                                <option value="-1">--- เลือกรายการ ---</option>
                                                @if ($onpages->count() > 0)
                                                    @foreach($onpages->get() as $row)
                                                        <option value="{{ $row->id }}" {{ (((int) $row->id == (int) $form->onpage_id) ? 'selected' : '')}}>{{ $row->code_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="active_status" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-label text-right">สถานะ&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="active_status" id="active_status">
                                                <option value="1" {{ ((int)$form->active_status == 1)? 'selected' : '' }}>ใช้งาน</option>
                                                <option value="2" {{ ((int)$form->active_status == 2)? 'selected' : '' }}>ไม่ใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/tournament"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#tournament_form').on('submit', (function (e) {
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
                        url: $('#base_url').val() + '/api/admin/tournament/save-update',
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
                                    window.location = $('#base_url').val() + '/admin/tournament';
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