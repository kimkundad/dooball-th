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
        <li><a href="{{ URL::to('/') }}/admin/on-page"><span class="icon icon-beaker"></span>ออนเพจทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มข้อมูล</a></li>
    @endsection
    <form role="form" id="onpage_form" enctype="multipart/form-data">
        @csrf
        <section class="content container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="code_name" class="col-md-3 col-sm-3 col-xs-12 form-label text-right">Code name&nbsp;:</label>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="code_name" id="code_name" placeholder="ตัวอย่าง: home, game, livescore" value="">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label for="seo_title" class="col-md-3 col-sm-3 col-xs-12 form-label text-right">SEO Title&nbsp;:</label>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="seo_title" id="seo_title" placeholder="SEO Title" value="" maxlength="200">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label for="seo_description" class="col-md-3 col-sm-3 col-xs-12 form-label text-right">SEO Description&nbsp;:</label>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <input type="text" class="form-control" name="seo_description" id="seo_description" placeholder="SEO Description" value="" maxlength="200">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label for="page_top" class="col-md-3 col-sm-3 col-xs-12 form-label text-right">เนื้อหาบน&nbsp;:</label>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <select class="form-control" name="page_top" id="page_top">
                                <option value="">--- เลือกหน้า ---</option>
                                @if ($page_list->count() > 0)
                                    @foreach($page_list->get() as $page)
                                        <option value="{{ $page->id }}">{{ $page->page_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label for="page_bottom" class="col-md-3 col-sm-3 col-xs-12 form-label text-right">เนื้อหาล่าง&nbsp;:</label>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <select class="form-control" name="page_bottom" id="page_bottom">
                                <option value="">--- เลือกหน้า ---</option>
                                @if ($page_list->count() > 0)
                                    @foreach($page_list->get() as $page)
                                        <option value="{{ $page->id }}">{{ $page->page_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <div class="col-md-3 col-sm-3 form-label text-right"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                            <a class="btn btn-lg btn-default" href="{{ url('/') }}/admin/on-page"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection

@section('custom-lib-scripts')
    <script type="text/javascript" src="{{ asset('backend/ckeditor4-full/ckeditor.js') }}"></script>
@endsection

@section('custom-scripts')
    <script>
        $(function() {
            $('#onpage_form').on('submit', (function (e) {
                if ($('#code_name').val().trim()) {
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
                } else {
                    showWarning('Warning!', 'กรุณากรอก Code name');
                }

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
                        url: $('#base_url').val() + '/api/admin/on-page/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/on-page';
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
