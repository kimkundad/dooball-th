@extends('backend/layouts.master')

@section('title', 'เพิ่มบทความ')

@section('custom-css')
@endsection

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/prediction"><span class="icon icon-beaker"></span>เกมทายผลทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มเกมทายผล</a></li>
    @endsection
    <form role="form" id="prediction_form">
        @csrf
        <section class="content container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-9 col-sm-9 pddt7">
                                    <h3 class="back-title">เพิ่มเกมทายผล
                                    {{-- <span class="left-time">(เหลือเวลาในระบบอีก {{ $left_time }})</span></h3> --}}
                                </div>							
                                <div class="col-md-3 col-sm-3 text-right">
                                    <a href="{{ URL::to('/') }}/admin/prediction">
                                        <span class="btn btn-default back-to-main"><i class="fa fa-angle-double-left"></i>&nbsp;กลับหน้ารายการหลัก</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="league_name" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ชื่อลีก&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="league_name" id="league_name" value="" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="match_time" class="col-md-3 col-sm-12 col-xs-12 pddt5 form-label text-right">วันเวลาแข่ง&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="match_time" id="match_time" value="" placeholder="{{ Date('Y-m-d H:i:s') }}" />
                                            <div class="format">รูปแบบ: {{ Date('Y-m-d H:i:s') }}</div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="home_team" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ทีมเหย้า&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="home_team" id="home_team" value="" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="away_team" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ทีมเยือน&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="away_team" id="away_team" value="" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="bargain_price" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ราคาต่อรอง&nbsp;:</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="bargain_price" id="bargain_price" value="" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="match_continue" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ต่อ&nbsp;:</label>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="admin-cbox">
                                                <input type="checkbox" class="chkb" id="home_cont" value="T" checked />
                                                <label class="cb" for="home_cont">ทีมเหย้า</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <div class="admin-cbox">
                                                <input type="checkbox" class="chkb" id="away_cont" value="T" />
                                                <label class="cb" for="away_cont">ทีมเยือน</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="match_result" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ผลการแข่งขัน&nbsp;:</label>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <input type="text" class="form-control" name="match_result" id="match_result" value="" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="match_result" class="col-md-3 col-sm-3 col-xs-12 pddt5 form-label text-right">ผลการแข่งขัน&nbsp;:</label>
                                        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                            <select class="form-control" name="match_result_status" id="match_result_status">
                                                <option value="0"> --- ยังไม่กำหนด --- </option>
                                                <option value="1"> เจ้าบ้านชนะเต็ม </option>
                                                <option value="2"> เจ้าบ้านชนะครึ่ง </option>
                                                <option value="3"> เสมอ </option>
                                                <option value="4"> ทีมเยือนชนะเต็ม </option>
                                                <option value="5"> ทีมเยือนชนะครึ่ง </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-3 pddt5 form-label text-right"></div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ url('/') }}/admin/prediction/index.php"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection

@section('custom-scripts')
    <script>
        $(function() {
            // $('.js-data-example-ajax').select2({
            //     ajax: {
            //         url: $('#base_url').val() + '/api/admin/team/list', // url: 'https://api.github.com/orgs/select2/repos',
            //         dataType: 'json'
            //     }
            // });

            $('#prediction_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการทำรายการ?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        // submitForm(this);
						if (result.value) {
							submitForm(this);
						}
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
                    var match_continue = ($('#away_cont').is(':checked')) ? 2 : 1;
                    formData.append('match_continue', match_continue);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/prediction/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/prediction';
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
