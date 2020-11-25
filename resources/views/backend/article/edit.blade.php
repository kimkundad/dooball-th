@extends('backend/layouts.master')

@section('title', 'แก้ไขบทความ')

@section('custom-lib-css')
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('backend/css/bootstrap-tagsinput-typeahead.css')}}">
@endsection

@section('custom-css')
    <style>
        .select2 {
            width: 100% !important;
        }
        .nav.nav-tabs {
            margin-bottom: -1px;
        }
    </style>
@endsection

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/article"><span class="icon icon-beaker"></span>บทความทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>แก้ไขบทความ</a></li>
    @endsection
    <form role="form" id="article_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="article_id" id="article_id" value="{{ $form->article_id }}">
        <section class="content container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
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
                                        <label for="title_th" class="form-label">ชื่อบทความ&nbsp;:</label>
                                        <input type="text" class="form-control" name="title_th" id="title_th" value="{{ $form->title }}" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="slug" class="form-label">ชื่อลิงค์ (slug)&nbsp;:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">{{ URL::to('/') }}/article-detail/</span>
                                            <input type="text" class="form-control" name="slug" id="slug" value="{{ $form->slug }}" maxlength="200" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="description_th" class="form-label">หัวข้อของรายการแข่งขัน&nbsp;:</label>
                                        <input type="text" class="form-control" name="description_th" id="description_th" value="{{ $form->description }}" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="team_id" class="form-label">ทีม&nbsp;:</label>
                                                <select class="select-team" name="team_id" id="team_id">
                                                    <option value="0">N/A</option>
                                                    @if(count($teams) > 0)
                                                        @foreach($teams as $team)
                                                            <option value="{{ $team->id }}" {{ (($team->id == $form->team_id)? 'selected' : '') }}>{{ $team->team_name_en . ' : ' . $team->team_name_th }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="tournament_id" class="form-label">ทัวร์นาเม้นท์&nbsp;:</label>
                                                <select class="select-tournament" name="tournament_id" id="tournament_id">
                                                    <option value="0">N/A</option>
                                                    @if(count($tournaments) > 0)
                                                        @foreach($tournaments as $tournament)
                                                            <option value="{{ $tournament->id }}" {{ (($tournament->id == $form->tournament_id)? 'selected' : '') }}>{{ $tournament->tournament_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="channel_id" class="form-label">ช่อง&nbsp;:</label>
                                                <select class="select-channel" name="channel_id" id="channel_id">
                                                    <option value="0">N/A</option>
                                                    @if(count($channels) > 0)
                                                        @foreach($channels as $channel)
                                                            <option value="{{ $channel->id }}" {{ (($channel->id == $form->channel_id)? 'selected' : '') }}>{{ $channel->channel_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        {{-- <label for="detail" class="form-label">รายละเอียดข่าว&nbsp;:</label> --}}
                                        <textarea class="form-control" name="detail_th" id="detail_th" rows="10">{{ $form->detail }}</textarea>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_title_th" class="form-label">ป้ายชื่อ (Tag)&nbsp;:</label>
                                        <input type="text" class="form-control" name="tags" id="tags" value="{{ $tags }}" data-role="tagsinput" />
                                        <br />
                                        <span class="text-muted">*กรุณากดปุ่ม tab หลังจากพิมพ์ชื่อแท็ก</span>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <h4>ตั้งค่า SEO</h4>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_title_th" class="form-label">Title&nbsp;:</label>
                                        <input type="text" class="form-control" name="seo_title_th" id="seo_title_th" value="{{ $form->seo_title }}" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="seo_description_th" class="form-label">Description&nbsp;:</label>
                                        <input type="text" class="form-control" name="seo_description_th" id="seo_description_th" value="{{ $form->seo_description }}" maxlength="255" />
                                    </div>
                                </div>
                                <div id="english" class="tab-pane fade">
                                    <div class="form-group">
                                        <label for="title_en" class="form-label">ชื่อบทความ&nbsp;:</label>
                                        <input type="text" class="form-control" name="title[en]" id="title_en" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label for="description_en" class="form-label">หัวข้อของรายการแข่งขัน&nbsp;:</label>
                                        <input type="text" class="form-control" name="description[en]" id="description_en" value="" maxlength="200" />
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        {{-- <label for="detail" class="form-label">รายละเอียดข่าว&nbsp;:</label> --}}
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
                                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 form-label text-right"></div>
                                <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                    <a class="btn btn-md btn-default" href="{{ URL::to('/') }}/admin/article"><i class="fa fa-close"></i>&nbsp;ยกเลิก</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">ตัวเลือกการเผยแพร่</div>
                        <div class="panel-body">
                            <div class="rd-group">
                                <input type="radio" name="publishing_option" id="publish" value="1" {{ (((int) $form->article_status == 1)? 'checked' : '') }} />
                                <label class="rd text-success" for="publish">เผยแพร่</label>
                                <div class="check"></div>
                            </div>
                            {{-- <div class="rd-group">
                                <input type="radio" name="publishing_option" id="timing" value="2" />
                                <label class="rd text-primary" for="timing">ตั้งเวลา</label>
                                <div class="check"></div>
                            </div> --}}
                            <div class="rd-group">
                                <input type="radio" name="publishing_option" id="no_publish" value="0" {{ (((int) $form->article_status == 0)? 'checked' : '') }} />
                                <label class="rd text-danger" for="no_publish">บันทึกแบบไม่เผยแพร่</label>
                                <div class="check"></div>
                            </div>
                            {{-- <div class="admin-cbox mt-10">
                                <input type="checkbox" class="chkb" id="home_cont" value="3" />
                                <label class="cb" for="home_cont">ตั้งค่าเป็นบทความเด่น</label>
                            </div> --}}
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-md btn-primary" onclick="savePublish()">บันทึก</button>
                        </div>
                    </div>
                    @include('backend._partials.article.edit-cover')
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
        $(function() {
            $('.select-team').select2();
            $('.select-tournament').select2();
            $('.select-channel').select2();

            // $('.js-data-example-ajax').select2({
            //     ajax: {
            //         url: $('#base_url').val() + '/api/admin/team/list', // url: 'https://api.github.com/orgs/select2/repos',
            //         dataType: 'json'
            //     }
            // });

			loadDefault();

            CKEDITOR.replace('detail_th', {
                height: '350px',
                allowedContent: true
            });

            CKEDITOR.replace('detail_en', {
                height: '350px',
                allowedContent: true
            });
            
            $("#tags").tagsinput('items');

            $('#article_form').on('submit', (function (e) {
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
							const beforeSubmit = async function (a, b) {
								const result = await waitingForCKEDITOR();
								// console.log(result);
							}
							beforeSubmit();
							submitForm(this);
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

        function loadDefault() {
            $('.input-type-file').change(function (e) {
                fileChange(e);
            });
        }

        function fileChange(e) {
            var file = e.target.files[0];
            var type = (file) ? file.type : '';
            var size = (file) ? file.size : 0;
            var name = (file) ? file.name : '';
            var match = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
            if (type) {
                if ($.inArray(type.toLowerCase(), match) == -1) {
                    showWarning('Warning!', 'อนุญาติไฟล์ jpg, jpeg, png, gif เท่านั้น');
                    resetDefault();
                } else {
                    console.log(size);
                    if (size > 2097152) { // 2Mb
                        showWarning('Warning!', 'อนุญาติให้อัพโหลดไฟล์ขนาดไม่เกิน 2 MB');
                        resetDefault();
                    } else {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('.flex-card .box-preview').html('<img src="' + e.target.result + '" />');
                            $('.flex-card .clear-multi-file').css('visibility', 'visible');

                            const img = new Image;
                            img.src = e.target.result;

                            img.onload = function () {
                                $('#witdh').val(this.width);
                                $('#height').val(this.height);
                                // if (this.width == 120 && this.height == 120) {
                                //     $('.dimension-color').css('color', 'green');
                                // } else {
                                //     $('.dimension-color').css('color', 'red');
                                // }
                            };
                        };
                        reader.readAsDataURL(file);
                        const arrImg = name.split('.');
                        $('#img_name').val(arrImg[0]);
                        $('#img_ext').html('.' + arrImg[arrImg.length-1]);
                    }
                }
            }
        }

        function resetDefault() {
            $('.flex-card .clear-multi-file').css('visibility', 'hidden');
            $('.flex-card .box-preview').html('<i class="fa fa-camera fa-5x"></i>');
            $('.dimension-color').css('color', '#999999');
            $('#img_name').val('');
            $('#witdh').val('');
            $('#height').val('');
            $('.input-type-file').val('');
        }

        function submitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    formData.append('img_ext', $('#img_ext').html());

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/article/save-update',
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
                                    window.location = $('#base_url').val() + '/admin/article';
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

        function savePublish() {
            Swal.fire({
                title: 'ยืนยันการเผยแพร่บทความ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    callApiPublish();
                }
            });
        }

        function callApiPublish() {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var pOption = $('input[name=publishing_option]:checked').val();

                    var formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('article_id', $('#article_id').val());
                    formData.append('publishing_option', pOption);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/article/save-publish',
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
                                    window.location = $('#base_url').val() + '/admin/article';
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
        }
    </script>
@endsection
