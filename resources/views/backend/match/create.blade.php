@extends('backend/layouts.master')

@section('title', 'เพิ่มแมทช์')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/match"><span class="icon icon-beaker"></span>แมทช์ทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>เพิ่มแมทช์</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="match_form">
                                    @csrf
                                    <div class="form-group">
                                        <label for="match_name" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">โปรแกรมการแข่งขัน&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="match_name" id="match_name" value="" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="password" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">วันเวลาที่เตะ&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="match_time" id="match_time" value="" placeholder="{{ Date('Y-m-d H:i') }}" maxlength="16" />
                                            <div class="format">รูปแบบ: {{ Date('Y-m-d H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="home_team" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">ทีมเหย้า&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="home_team" id="home_team" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="away_team" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">ทีมเยือน&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="away_team" id="away_team" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">ลิ้งค์ดูออนไลน์&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div id="online_link">
                                                <div class="row" id="link_1">
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control" name="links[1][name]" placeholder="ชื่อลิงค์" maxlength="200" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="links[1][url]" placeholder="url" maxlength="200" />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control" name="links[1][desc]" placeholder="คำอธิบาย" maxlength="200" />
                                                    </div>
                                                    <div class="col-md-1 no-pdd-lr">
                                                        <input type="number" class="form-control" name="links[1][link_seq]" placeholder="1" min="1" max="100" maxlength="3" />
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-success mt-5" onclick="addLink()"><i class="fa fa-plus-circle"></i></button>
                                                    </div>
                                                </div>
                                                <div class="clearfix" id="link_clear_1"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="channels" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">ช่องที่ถ่ายทอดสด&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="channels" id="channels" value="" maxlength="125" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="more_detail" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">คำอธิบายเพิ่มเติม&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <textarea class="form-control" rows="5" name="more_detail" id="more_detail"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/settings/user"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            jQuery.datetimepicker.setLocale('en');
            $("#match_time").datetimepicker({format:'Y-m-d H:i', step:30});

            $('#match_form').on('submit', (function (e) {
                let countWarning = 0;

                if (countWarning == 0) {
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
                }

                return false;
            }));
        });

        function addLink() {
            let num = $('#online_link .row').length;
            num++;

            let link = '';
            link += '<div class="row mt-5" id="link_' + num + '">';
            link +=     '<div class="col-md-3">';
            link +=         '<input type="text" class="form-control" name="links[' + num + '][name]" placeholder="ชื่อลิงค์" maxlength="200" />';
            link +=     '</div>';
            link +=     '<div class="col-md-4">';
            link +=         '<input type="text" class="form-control" name="links[' + num + '][url]" placeholder="url" maxlength="200" />';
            link +=     '</div>';
            link +=     '<div class="col-md-3">';
            link +=         '<input type="text" class="form-control" name="links[' + num + '][desc]" placeholder="คำอธิบาย" maxlength="200" />';
            link +=     '</div>';
            link +=     '<div class="col-md-1 no-pdd-lr">';
            link +=         '<input type="number" class="form-control" name="links[' + num + '][link_seq]" placeholder="1" min="1" max="100" maxlength="3" />';
            link +=     '</div>';
            link +=     '<div class="col-md-1">';
            link +=         '<button type="button" class="btn btn-sm btn-danger mt-5" onclick="delLink($(this))"><i class="fa fa-minus-circle"></i></button>';
            link +=     '</div>';
            link += '</div>';
            link += '<div class="clearfix" id="link_clear_' + num + '"></div>';

            $('#online_link').append(link);
        }

        function delLink(this_row) {
            const row_id = this_row.parent().parent().attr('id');
            const num = row_id.split('_')[1];
            $('#' + row_id).remove();
            $('#link_clear_' + num).remove();

            resetIndex();
        }

        function resetIndex() {
            let id_name = '';
            let old_idx = '';
            let idx = 0;
            $('#online_link .row').each(function(key){
		        id_name = $(this).attr('id');
		        old_idx = id_name.split('_')[1];
                idx = key + 1;

		        $(this).attr('id','link_'+ idx);
                $('#link_clear_'+ old_idx).attr('id','link_clear_'+ idx);
            });
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
                        url: $('#base_url').val() + '/api/admin/match/save-create',
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
                                    window.location = $('#base_url').val() + '/admin/match';
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
