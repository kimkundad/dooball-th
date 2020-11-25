@extends('backend/layouts.master')

@section('title', 'แก้ไขลีก')

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/leaguesubpage"><span class="icon icon-beaker"></span>ลีกทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขลีก</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="leaguesubpage_form">
                                    @csrf
                                    <input type="hidden" name="leaguesubpage_id" value="{{ $form->id }}" />

                                    <div class="form-group">
                                        <label for="page_url" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">Page URL&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <input type="text" class="form-control" name="page_url" id="page_url" value="{{ $form->page_url }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="league_url" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right">League URL&nbsp;:</label>
                                        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                            <select class="form-control" name="league_url" id="league_url">
                                                <option value="">--- เลือกลีก ---</option>
                                                @if ($leagues->count() > 0)
                                                    @foreach($leagues->get() as $league)
                                                        <option value="{{ $league->url }}" {{ (($league->url == $form->league_url) ? 'selected' : '')}}>{{ $league->url }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="onpage_id" class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right">Onpage&nbsp;:</label>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <select class="form-control" name="onpage_id" id="onpage_id">
                                                <option value="-1">--- เลือก Onpage ---</option>
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
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                            <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/leaguesubpage"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
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
            $('#leaguesubpage_form').on('submit', (function (e) {
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
                        url: $('#base_url').val() + '/api/admin/leaguesubpage/save-update',
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
                                    window.location = $('#base_url').val() + '/admin/leaguesubpage';
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