@extends('backend/layouts.master')

@section('title', 'เพิ่มเพจ')

@section('custom-css')
    <style>
        .nav.nav-tabs {
            margin-bottom: -1px;
        }
    </style>
@endsection

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/page"><span class="icon icon-beaker"></span>เพจทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มเพจ</a></li>
    @endsection
    <form role="form" id="page_form" enctype="multipart/form-data">
        @csrf
        <section class="content container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#thai"><img src="{{ asset('images/th.png') }}" >&nbsp;ภาษาไทย</a>
                        </li>
                        {{-- <li>
                            <a data-toggle="tab" href="#english"><img src="{{ asset('images/en.png') }}" >&nbsp;ภาษาอังกฤษ</a>
                        </li> --}}
                    </ul>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="thai" class="tab-pane fade in active">
                                    <div class="form-group">
                                        <label for="page_name" class="form-label">ชื่อเพจ&nbsp;:</label>
                                        <input type="text" class="form-control" name="page_name" id="page_name" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="slug" class="form-label">ชื่อลิงค์ (slug)&nbsp;:</label>
                                        <input type="text" class="form-control" name="slug" id="slug" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="page_condition" class="form-label">Page condition&nbsp;:</label>
                                        <select class="form-control" name="page_condition" id="page_condition" onchange="checkShowPageCondition()">
                                            <option value="L">ชื่อลีก</option>
                                            <option value="T">ชื่อทีม</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group league">
                                        <label for="league_name" class="form-label">ชื่อลีก&nbsp;:</label>
                                        <input type="text" class="form-control" name="league_name" id="league_name" value="" maxlength="125" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group team">
                                        <label for="team_name" class="form-label">ชื่อทีม&nbsp;:</label>
                                        <input type="text" class="form-control" name="team_name" id="team_name" value="" maxlength="125" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group key-box">
                                        <label class="form-label">ตัวแปรใน Page&nbsp;:</label>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr class="tr-key">
                                                                <th>ชื่อตัวแปร</th>
                                                                <th>ค่าในตัวแปร</th>
                                                                <th>
                                                                    <button type="button" class="btn btn-success" onclick="addKeyRow()"><i class="fa fa-plus"></i></button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody_page_key"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <p class="pp-view">Preview</p>
                                                <div class="page-key-preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="title_th" class="form-label">หัวข้อ&nbsp;(Title, h1)&nbsp;:</label>
                                        <input type="text" class="form-control" name="title_th" id="title_th" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="description_th" class="form-label">เนื้อหา&nbsp;(Description, p)&nbsp;:</label>
                                        <textarea class="form-control" name="description_th" id="description_th" rows="5"></textarea>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="admin-cbox mt-10">
                                            <input type="checkbox" class="chkb" name="show_on_menu" id="show_on_menu" value="1" />
                                            <label class="cb" for="show_on_menu">แสดงในเมนู</label>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="detail_th" class="form-label">รายละเอียดในส่วนอื่นๆ&nbsp;:</label>
                                        <textarea class="form-control" name="detail_th" id="detail_th" rows="10"></textarea>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <h4>ตั้งค่า SEO</h4>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_title_th" class="form-label">Title&nbsp;:</label>
                                        <input type="text" class="form-control" name="seo_title_th" id="seo_title_th" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_description_th" class="form-label">Description&nbsp;:</label>
                                        <input type="text" class="form-control" name="seo_description_th" id="seo_description_th" value="" maxlength="255" />
                                    </div>
                                </div>
                                <div id="english" class="tab-pane fade">
                                    <div class="form-group">
                                        <label for="title_en" class="form-label">ชื่อบทความ&nbsp;:</label>
                                        <input type="text" class="form-control" name="title[en]" id="title_en" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="description_en" class="form-label">เนื้อหา&nbsp;:</label>
                                        <textarea class="form-control" name="description[en]" id="description_en" rows="5"></textarea>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="detail" class="form-label">รายละเอียดในส่วนอื่ๆ&nbsp;:</label>
                                        <textarea class="form-control" name="detail[en]" id="detail_en" rows="10"></textarea>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <h4>ตั้งค่า SEO</h4>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_title_en" class="form-label">Title&nbsp;:</label>
                                        <input type="text" class="form-control" name="seo_title[en]" id="seo_title_en" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_description_en" class="form-label">Description&nbsp;:</label>
                                        <input type="text" class="form-control" name="seo_description[en]" id="seo_description_en" value="" maxlength="255" />
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                    <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/page"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
    <div class="key-option oppa-none"></div>
@endsection

@section('custom-lib-scripts')
    <script type="text/javascript" src="{{ asset('backend/ckeditor4-full/ckeditor.js') }}"></script>
@endsection

@section('custom-scripts')
    <script>
        $(function() {
            getKeyNameList();

            CKEDITOR.replace('detail_th', {
                height: '350px',
                allowedContent: true
            });

            CKEDITOR.replace('detail_en', {
                height: '350px',
                allowedContent: true
            });

            $('#page_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการทำรายการ?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        const validData = validateDateTime();

                        if (validData.error == 0) {
							const beforeSubmit = async function (a, b) {
								const result = await waitingForCKEDITOR();
								// console.log(result);
							}
							beforeSubmit();
                            submitForm(this);
                        } else {
                            showWarning('Warning!', 'กรุณาตรวจสอบรูปแบบของตัวแปร');
                        }
                    }
                });

                return false;
            }));
        });

        function waitingForCKEDITOR() {
            let count = 0;
            let valid = 1;
            if (valid == 1) {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                    count++;
                }
            }
            return count;
        }

        function submitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/page/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/page';
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
@endsection
