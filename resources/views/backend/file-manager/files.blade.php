@extends('backend/layouts.master')

@section('title', 'จัดการไฟล์')

@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/file-manager.css') }}">
@endsection

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/file-manager"><span class="icon icon-beaker"></span>public / storage</a></li>
        <li><a onclick="Javascript:void(0);">{{ $directory }}</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <input type="hidden" id="directory" value="{{ $directory }}">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-condensed table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>รูปภาพ</th>
                                            <th>ID</th>
                                            <th>ชื่อไฟล์</th>
                                            <th>ขนาด</th>
                                            <th>แก้ไขล่าสุด</th>
                                            <th>Perms</th>
                                            <th>ตัวจัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6">
                                                <a href="{{ URL::to('/') }}/admin/file-manager" class="text-bold">
                                                    <i class="fa fa-arrow-left"></i>&nbsp;public / storage
                                                </a>
                                            </td>
                                            <td>
                                                <span class="c-pointer" data-toggle="modal" data-target="#addFileModal">
                                                    <img src="{{ asset('images/add-image.png') }}" alt="" width="40">
                                                </span>
                                            </td>
                                        </tr>
                                        @if(count($files) > 0)
                                            <?php
                                                $all_files_size = 0;
                                                foreach ($files as $f) {
                                                    $is_link = is_link($path . '/' . $f);
                                                    $modif = date('Y-m-d H:i', filemtime($path . '/' . $f));
                                                    $filesize_raw = filesize($path . '/' . $f);
                                                    $filesize = App\Http\Controllers\API\CommonController::fm_get_filesize($filesize_raw);
                                                    
                                                    $all_files_size += $filesize_raw;
                                                    $perms = substr(decoct(fileperms($path . '/' . $f)), -4);

                                                    if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                                                        $owner = posix_getpwuid(fileowner($path . '/' . $f));
                                                        $group = posix_getgrgid(filegroup($path . '/' . $f));
                                                    } else {
                                                        $owner = array('name' => '?');
                                                        $group = array('name' => '?');
                                                    }
                                                    $f_name = App\Http\Controllers\API\CommonController::fm_enc($f);
                                                    $media_id = App\Http\Controllers\API\CommonController::findImageId($f_name);
                                                    $image = App\Http\Controllers\API\CommonController::getImageFile($media_id);

                                                    $web_image = '';
                                                    if ($image) {
                                                        $web_image = asset('storage' . $image);
                                                    } else {
                                                        $web_image = asset('storage/' . $directory . '/' . $f_name);
                                                    }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="filename">
                                                            <a href="{{ $web_image }}" target="_BLANK">
                                                                <img src="{{ $web_image }}" width="50" />
                                                            </a>
                                                            {{-- {{ App\Http\Controllers\API\CommonController::fm_convert_win($f) }} --}}
                                                        </div>
                                                    </td>
                                                    <td>{{ ($media_id == 0)? 'ไม่พบในฐานข้อมูล' : $media_id }}</td>
                                                    <td>{{ $f_name }}</td>
                                                    <td>
                                                        <span title="<?php printf('%s bytes', $filesize_raw) ?>"><?php echo $filesize ?></span>
                                                    </td>
                                                    <td><?php echo $modif ?></td>
                                                    <td><?php echo $perms ?></td>
                                                    <td>
                                                        <?php
                                                            $fnm = App\Http\Controllers\API\CommonController::fm_enc($f);
                                                        ?>
                                                        <a class="btn btn-warning btn-xs" href="javascript:void(0)" onclick="renameFile('<?php echo $fnm; ?>', '<?php echo $media_id; ?>');" title="เปลี่ยนชื่อ">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                        <a class="btn btn-danger btn-xs" href="javascript:void(0)" onclick="deleteFile('<?php echo $f ?>', '<?php echo $media_id; ?>');" title="ลบไฟล์">
                                                            <i class="fa fa-trash-o" style="font-size: 1.2rem;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            <tr>
                                                <td colspan="7">
                                                    Full size: <span title="<?php printf('%s bytes', $all_files_size) ?>"><?php echo App\Http\Controllers\API\CommonController::fm_get_filesize($all_files_size) ?></span>,
                                                    Folders: {{ count($files) }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="7">File is empty</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addFileModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form role="form" id="add_file_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="file_path" value="public/{{ $directory }}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">เพิ่มไฟล์</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="flex-card">
                                    <div class="box-picture web-logo">
                                        <div class="box-preview">
                                            <i class="fa fa-camera fa-3x"></i>
                                        </div>
                                        <div class="box-btn">
                                            <div class="noi-file">
                                                <span>Browse</span>
                                                <input type="file" accept="image/*" class="input-type-file" name="new_file" />
                                            </div>
                                            <button type="button" class="btn btn-sm clear-multi-file" onclick="resetDefault($(this));">&nbsp;<i class="fa fa-trash-o"></i>&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-10">
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-folder-open"></i>&nbsp;public/storage/{{ $directory }}</div>
                                    <input type="text" class="form-control" id="img_name" name="img_name" placeholder="ชื่อไฟล์">
                                    <div class="input-group-addon" id="img_ext"></div>
                                </div>
                            </div>
                            <div class="form-group mt-10">
                                <div class="rd-group">
                                    <input type="radio" name="need_db" id="need" value="1" checked />
                                    <label class="rd text-success" for="need">เก็บลงในฐานข้อมูล</label>
                                    <div class="check"></div>
                                </div>
                                <div class="rd-group">
                                    <input type="radio" name="need_db" id="not_need" value="0" />
                                    <label class="rd text-danger" for="not_need">ไม่เก็บลงฐานข้อมูล</label>
                                    <div class="check"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-scripts')
    <script>
        $(function() {
            loadDefault();

            $('#add_file_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการเพิ่มไฟล์?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        submitAddFileForm(this);
                    }
                });

                return false;
            }));
        });

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
                        showWarning('Warning!', 'ไม่อนุญาติให้อัพโหลดไฟล์เกิน 2 MB');
                        resetDefault();
                    } else {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('.flex-card .box-preview').html('<img src="' + e.target.result + '" />');
                            $('.flex-card .clear-multi-file').css('visibility', 'visible');
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
            $('#img_name').val('');
            $('#img_ext').html('');
            $('.input-type-file').val('');
        }

        function submitAddFileForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    formData.append('img_ext', $('#img_ext').html());

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/add-file',
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
                            console.log(response);
                            // showRequestWarning(response);
                            // if (response.status == 500) {

                            // }
                            showWarning('Warning!', 'เกิดความผิดพลาด ชื่อไฟล์อาจซ้ำกัน');
                        }
                    });
                }
            });
            return false;
        }

        async function renameFile(filename, media_id) {
            const {value: whatFilename} = await Swal.fire({
                title: 'เปลี่ยนชื่อไฟล์',
                input: 'text',
                inputValue: filename,
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'กรุณาใส่ชื่อไฟล์!';
                    } else if (value == filename) {
                        return 'ชื่อใหม่ต้องไม่ซ้ำชื่อเดิม!';
                    }
                }
            });

            if (whatFilename) {
                // Swal.fire(`ชื่อใหม่คือ ${whatFilename}`);
                submitRename(filename, whatFilename, media_id);
            }
        }

        function deleteFile(filename, media_id) {
            Swal.fire({
                title: 'ยืนยันการทำรายการ?',
                type: 'question',
                showCancelButton: true
            }).then((result) => {
                if (result.value) {
                    submitDelete(filename, media_id);
                }
            });
        }

        function submitRename(t, n, media_id) {
            const params = {'directory': $('#directory').val(),
                            'ren': t,
                            'to': n,
                            'id': media_id};

            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/rename-file',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        cache: false,
                        success: function(response){
                            swal.close();

                            if (response.transaction_status == 1) {
                                Swal.fire({
                                    title: 'เปลี่ยนชื่อไฟล์สำเร็จ',
                                    html: response.message,
                                    type: 'success',
                                    showCancelButton: false,
                                    timer: 5000
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดความผิดพลาด',
                                    html: response.message,
                                    type: 'warning',
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(response) {
                            swal.close();
                            console.log(response);
                            // showRequestWarning(response);
                            // if (response.status == 500) {

                            // }
                            showWarning('Warning!', 'เกิดความผิดพลาด ชื่อไฟล์อาจซ้ำกัน');
                        }
                    });
                }
            });

            return false;
        }

        function submitDelete(filename, media_id) {
            const params = {'directory': $('#directory').val(),
                            'filename': filename,
                            'id': media_id};

            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/delete-file',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        cache: false,
                        success: function(response){
                            swal.close();

                            if (response.transaction_status == 1) {
                                Swal.fire({
                                    title: 'ดำเนินการสำเร็จ',
                                    html: response.message,
                                    type: 'success',
                                    showCancelButton: false,
                                    timer: 5000
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'เกิดความผิดพลาด',
                                    html: response.message,
                                    type: 'warning',
                                    showConfirmButton: false
                                });
                            }
                        }
                    });
                }
            });

            return false;
        }
    </script>
@stop