@extends('backend/layouts.master')

@section('title', 'เพิ่ม API')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/settings/api-football"><span class="icon icon-beaker"></span>API ทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่ม API</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="api_football_form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="api_page_id" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ใช้ในหน้า&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <select class="form-control" name="api_page_id" id="api_page_id">
                                                <option value="0">--- ไม่เลือก ---</option>
                                                @if (count($api_pages) > 0)
                                                    @foreach ($api_pages as $page)
                                                        <option value="{{$page->id}}" {{ ((int) $page->id == (int) $page_id) ? 'selected' : '' }}>{{$page->desc}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="api_url" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">API URL&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="api_url" id="api_url" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="api_desc" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">คำอธิบาย API&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="api_desc" id="api_desc" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="params_count" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">จำนวน Parameter&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <input type="number" class="form-control" name="params_count" id="params_count" value="" min="0" max="5" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mb-2">
                                        <label for="params_type" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">ข้อมูล Parameter&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <p class="mb-0">ประเภท</p>
                                            <div class="d-flex api-params-type mb-2"></div>

                                            <p class="mb-0">ตัวอย่าง</p>
                                            <div class="d-flex api-params-mask"></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ URL::to('/') }}/admin/settings/api-football"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#params_count').on('change', function() {
                // console.log($(this).val());
                genParams(parseInt($(this).val()));
            });

            $('#api_football_form').on('submit', (function (e) {
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

        function genParams(params_count) {
            if (! isNaN(params_count)) {
                var typeHtml = '';
                var maskHtml = '';

                for (var i = 0; i < params_count; i++) {
                    if (i > 0) {
                        typeHtml += '<span class="slash d-flex alit-end just-center">/</span>';
                        maskHtml += '<span class="slash d-flex alit-end just-center">/</span>';
                    }

                    typeHtml += '<input type="text" class="form-control params-data" name="params_types[]" id="params_type_' + i + '" value="" maxlength="125" />';
                    maskHtml += '<input type="text" class="form-control params-data" name="params_masks[]" id="params_mask_' + i + '" value="" maxlength="125" />';
                }

                $('.api-params-type').html(typeHtml);
                $('.api-params-mask').html(maskHtml);
            } else {
                $('#params_count').val(0);
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
                        url: $('#base_url').val() + '/api/admin/api-football/save-create',
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