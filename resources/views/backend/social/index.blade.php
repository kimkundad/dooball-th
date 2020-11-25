@extends('backend/layouts.master')

@section('title', 'ลิ้งค์โซเชียลเน็ตเวิร์ค')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">ลิ้งค์โซเชียลเน็ตเวิร์ค</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="social_form">
                                    @csrf
                                    <div class="form-group mt-5">
                                        <label for="social_facebook" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Facebook&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-facebook-square text-primary"></i>&nbsp;https://www.facebook.com/</span>
                                                <input type="text" class="form-control" name="social_facebook" id="social_facebook" value="{{ $form->social_facebook }}" maxlength="125" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="social_twitter" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Twitter&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-twitter-square text-info"></i>&nbsp;https://twitter.com/</span>
                                                <input type="text" class="form-control" name="social_twitter" id="social_twitter" value="{{ $form->social_twitter }}" maxlength="125" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="social_linkedin" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">LinkedIn&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-linkedin-square text-primary"></i>&nbsp;https://www.linkedin.com/</span>
                                                <input type="text" class="form-control" name="social_linkedin" id="social_linkedin" value="{{ $form->social_linkedin }}" maxlength="125" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="social_instagram" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Instagram&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-instagram text-danger"></i>&nbsp;https://www.instagram.com/</span>
                                                <input type="text" class="form-control" name="social_instagram" id="social_instagram" value="{{ $form->social_instagram }}" maxlength="125" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="social_youtube" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Youtube&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-youtube-square text-danger"></i>&nbsp;https://www.youtube.com/</span>
                                                <input type="text" class="form-control" name="social_youtube" id="social_youtube" value="{{ $form->social_youtube }}" maxlength="125" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group mt-5">
                                        <label for="social_pinterest" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 pddt5 form-label text-right">Pinterest&nbsp;:</label>
                                        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-pinterest-square text-danger"></i>&nbsp;https://www.pinterest.com/</span>
                                                <input type="text" class="form-control" name="social_pinterest" id="social_pinterest" value="{{ $form->social_pinterest }}" maxlength="125" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group mt-5">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
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
            $('#social_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการทำรายการ?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        submitSocialForm(this);
                    }
                });

                return false;
            }));
        });

        function submitSocialForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    formData.append('img_ext', $('#img_ext').html());

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/general/save-social',
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
                                saveSuccessReload();
                            } else {
                                showWarning('Warning!', response.message);
                            }
                        },
                        error: function(response) {
                            swal.close();
                            showRequestWarning(response);
                        }
                    });
                }
            });
            return false;
        }
    </script>
@stop