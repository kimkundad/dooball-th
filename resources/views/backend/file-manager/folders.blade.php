@extends('backend/layouts.master')

@section('title', 'รายการไดเร็คทอรี่')

@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/file-manager.css') }}">
@endsection

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">รายการไดเร็คทอรี่</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table class="table table-condensed table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ชื่อโฟลเดอร์</th>
                                            <th>แก้ไขล่าสุด</th>
                                            <th>Perms</th>
                                            <th class="text-center"><i class="fa fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="text-muted text-bold">
                                                <i class="fa fa-folder"></i>&nbsp;public / storage
                                            </td>
                                            <td class="text-center">
                                                <span class="c-pointer" data-toggle="modal" data-target="#addDirectoryModal">
                                                    <img src="{{ asset('images/add-folder.png') }}" alt="" width="40">
                                                </span>
                                            </td>
                                        </tr>
                                        @if(count($folders) > 0)
                                            <?php
                                                $all_files_size = 0;
                                                foreach ($folders as $f) {
                                                    $is_link = is_link($path . '/' . $f);
                                                    $modif = date('Y-m-d H:i', filemtime($path . '/' . $f));
                                                    $filesize_raw = filesize($path . '/' . $f);
                                                    $filesize = App\Http\Controllers\API\CommonController::fm_get_filesize($filesize_raw);
                                                    $filelink = $http_host . '/storage/teams/' . urlencode($f);
                                                    $all_files_size += $filesize_raw;
                                                    $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
            
                                                    if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                                                        $owner = posix_getpwuid(fileowner($path . '/' . $f));
                                                        $group = posix_getgrgid(filegroup($path . '/' . $f));
                                                    } else {
                                                        $owner = array('name' => '?');
                                                        $group = array('name' => '?');
                                                    }
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="filename">
                                                            <i class="fa fa-folder-open"></i>&nbsp;
                                                            <a href="{{ url('/admin/file-manager/' . $f) }}">{{ $f }}</a>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $modif ?></td>
                                                    <td><?php echo $perms ?></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-danger btn-xs" href="javascript:void(0)" onclick="deleteDirectory('{{ $f }}');" title="ลบไดเร็คทอรี่">
                                                            <i class="fa fa-trash-o" style="font-size: 1.2rem;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                            <tr>
                                                <td class="gray" colspan="4">
                                                    <?php /*
                                                    Full size: <span title="<?php printf('%s bytes', $all_files_size) ?>"><?php echo App\Http\Controllers\API\CommonController::fm_get_filesize($all_files_size) ?></span>,
                                                    */ ?>
                                                    Folders: {{ count($folders) }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="3">Folder is empty</td>
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

        <div class="modal fade" id="addDirectoryModal" tabindex="-1" role="dialog" aria-labelledby="addDirectoryModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form role="form" id="add_directory_form" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">เพิ่มไดเร็คทอรี่</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group mt-10">
                                    <div class="input-group-addon"><i class="fa fa-folder-open"></i>&nbsp;public/storage/</div>
                                    <input type="text" class="form-control" id="directory_name" name="directory_name" placeholder="ชื่อไดเร็คทอรี่">
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
            $('#add_directory_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการเพิ่มไดเร็คทอรี่?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        submitAddDirectoryForm(this);
                    }
                });

                return false;
            }));
        });

        function submitAddDirectoryForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/add-directory',
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
                            showWarning('Warning!', 'เกิดความผิดพลาด ชื่อไดเร็คทอรี่อาจซ้ำกัน');
                        }
                    });
                }
            });
            return false;
        }

        function deleteDirectory(d_name) {
            Swal.fire({
                    title: 'ยืนยันการลบไดเร็คทอรี่ ' + d_name + ' หรือไม่?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
                        submitDeleteDirectory(d_name);
                    }
                });
        }

        function submitDeleteDirectory(d_name) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var params = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'directory_name': d_name
                    };

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/delete-directory',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        cache: false,
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
                            showWarning('Warning!', 'เกิดความผิดพลาด ไม่สามารถลบไดเร็คทอรี่ ' + d_name);
                        }
                    });
                }
            });
            return false;
        }
    </script>
@stop