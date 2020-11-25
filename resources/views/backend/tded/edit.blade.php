@extends('backend/layouts.master')

@section('title', 'แก้ไขทีเด็ดบอล')

@section('custom-css')
<style>
    .pddt5 {
        padding-top: 5px;
    }
</style>
@endsection

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/tded"><span class="icon icon-beaker"></span>ทีเด็ดบอลทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขทีเด็ดบอล</a></li>
    @endsection
    <form role="form" id="prebet_form">
        @csrf
        <input type="hidden" name="tdedball_list_id" id="tdedball_list_id" value="{{ $form->tdedball_list_id }}">
        <section class="content container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-9 col-sm-9 pddt7">
                                    <h3 class="back-title">แก้ไขทีเด็ดบอล
                                    {{-- <span class="left-time">(เหลือเวลาในระบบอีก {{ $left_time }})</span></h3> --}}
                                </div>							
                                <div class="col-md-3 col-sm-3 text-right">
                                    <a href="{{ url('/') }}/admin/tded">
                                        <span class="btn btn-default back-to-main"><i class="fa fa-angle-double-left"></i>&nbsp;กลับหน้ารายการหลัก</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="type_id" class="col-lg-2 col-md-2 col-sm-12 col-xs-12 form-label text-right">ประเภททีเด็ดบอล&nbsp;:</label>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <select class="form-control" name="type_id" id="type_id">
                                                @if($tded_type->count() > 0)
                                                    @foreach($tded_type->get() as $val)
                                                        @php
                                                            $selected = ($val->tdedball_type_id == $form->type_id) ? 'selected' : '';
                                                        @endphp
                                                        <option value="{{ $val->tdedball_type_id }}" {{ $selected }}>{{ $val->type_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="" class="col-md-2 col-sm-2 col-xs-12 form-label text-right">เลือกทีม&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-condensed">
                                                    <thead>
                                                        <tr class="tr-head-title">
                                                            <th style="width:35%;">ทีมเหย้า</th>
                                                            <th style="width:20%;">อัตราต่อรอง</th>
                                                            <th style="width:35%;">ทีมเยือน</th>
                                                            <th class="text-center" style="width:10%;">ยกเลิก</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (count($datas) > 0)
                                                            @foreach($datas as $ktm => $v)
                                                                <tr class="league-name-th">
                                                                    <td colspan="4">{{ $v['name'] }}</td>
                                                                </tr>

                                                                @foreach($v['datas'] as $data)
                                                                    @php
                                                                        $home_team = ((int)$data['match_continue'] == 1) ? '<span class="text-danger">' . $data['home_team'] . '</span>' : $data['home_team'];
                                                                        $away_team = ((int)$data['match_continue'] == 2) ? '<span class="continue">' . $data['away_team'] . '</span>' : $data['away_team'];
                                                                        $home_checked = (in_array($data['prediction_id'] . '_1', $team_selected_list)) ? 'checked' : '';
                                                                        $away_checked = (in_array($data['prediction_id'] . '_2', $team_selected_list)) ? 'checked' : '';
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="rd-group">
                                                                                <input type="radio" class="use-this-page-only" name="select_team_{{ $data['prediction_id'] }}" id="team_{{ $data['prediction_id'] }}_1" value="{{ $data['prediction_id'] }}_1" {{ $home_checked }} />
                                                                                <label class="rd" for="team_{{ $data['prediction_id'] }}_1">{!! $home_team !!}</label>
                                                                                <div class="check"></div>
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $data['bargain_price'] }}</td>
                                                                        <td>
                                                                            <div class="rd-group">
                                                                                <input type="radio" class="use-this-page-only" name="select_team_{{ $data['prediction_id'] }}" id="team_{{ $data['prediction_id'] }}_2" value="{{ $data['prediction_id'] }}_2" {{ $away_checked }} />
                                                                                <label class="rd" for="team_{{ $data['prediction_id'] }}_2">{!! $away_team !!}</label>
                                                                                <div class="check"></div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn btn-danger" onclick="clearSelectedTeam('team_{{ $data['prediction_id'] }}');"><i class="fa fa-close"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="4" class="text-center">--- ไม่มีข้อมูล ---</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="form-group">
                                        <div class="col-md-3 col-sm-3 form-label text-right"></div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-lg btn-default" href="{{ url('/') }}/admin/tded"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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

@section('custom-lib-scripts')
    <script type="text/javascript" src="{{asset('backend/js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('backend/ckeditor4-full/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('backend/js/bootstrap-tagsinput.min.js')}}"></script>
@endsection

@section('custom-scripts')
    <script>
        var team_selected = [];

        $(function() {
            // $('.js-data-example-ajax').select2({
            //     ajax: {
            //         url: $('#base_url').val() + '/api/admin/team/list', // url: 'https://api.github.com/orgs/select2/repos',
            //         dataType: 'json'
            //     }
            // });

            $('#prebet_form').on('submit', (function (e) {
                var err = 0;

                team_selected = [];
                var count = 0;

                $(".use-this-page-only").each(function() {
                    if ($(this).is(':checked')) {
                        team_selected.push($(this).val());
                        count++;
                    }
                });

                // console.log(parseInt($('#type_id').val()), count);

                if ((parseInt($('#type_id').val()) == 1) && (count != 1)) {
                    err++;
                }
                if ((parseInt($('#type_id').val()) == 2) && (count != 1)) {
                    err++;
                }
                if ((parseInt($('#type_id').val()) == 3) && (count != 1)) {
                    err++;
                }
                if ((parseInt($('#type_id').val()) == 8) && (count != 1)) {
                    err++;
                }

                if ((parseInt($('#type_id').val()) == 4) && (count != 2)) {
                    err++;
                }
                if ((parseInt($('#type_id').val()) == 5) && (count != 3)) {
                    err++;
                }
                if ((parseInt($('#type_id').val()) == 6) && (count != 10)) {
                    err++;
                }

                if (err > 0) {
                    showWarning('Warning!', 'ท่านเลือกรายการไม่ถูกต้อง');
                } else {
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
                }

                return false;
            }));

        });

        function clearSelectedTeam(semi_id) {
            $('#' + semi_id + '_1').prop('checked', false);
            $('#' + semi_id + '_2').prop('checked', false);
        }

        function submitForm(this_form) {
            var formData = new FormData(this_form);
            if (team_selected.length > 0) {
                formData.append('team_selected', team_selected.join());
            }

            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/tded/save-update',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total > 0) {
                                // saveSuccess();
                                setTimeout(function () {
                                    window.history.back();
                                    window.location = $('#base_url').val() + '/admin/tded';
                                }, 2000);
                            } else {
                                console.log(response.message);
                                // showWarning('Warning!', response.message);
                            }
                        },
                        error: function(response) {
                            console.log(response);
                            // showRequestWarning(response);
                        }
                    });
                }
            });
            return false;
        }
    </script>
@endsection
