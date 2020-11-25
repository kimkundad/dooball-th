@extends('backend/layouts.master')

@section('title', 'จัดการแมทช์')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">แมทช์</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                รายการแมทช์ทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_match_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row mbt-10">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <select class="form-control" name="chk_action" id="chk_action" onchange="doSomethingWithIt($(this), 'match')">
                                    <option value="">ทำกับที่เลือก</option>
                                    <option value="delete">ไม่เปิดใช้งาน</option>
                                    <option value="restore">เปิดใช้งาน</option>
                                    {{-- <option value="real-delete">ลบทิ้ง</option> --}}
                                </select>
                            </div>
                            <div class="col-lg-9 col-md-8 col-sm-6 col-xs-12 text-right">
                                <a href="{{ URL::to('/') }}/admin/match/create">
                                    <span class="btn btn-success add-btn"><i class="fa fa-plus"></i>&nbsp;เพิ่มข้อมูล</span>
                                </a>
                                <button type="button" class="btn btn-primary mr-2" onclick="saveSeq()">
                                    <i class="fa fa-save"></i>&nbsp;บันทึกลำดับ
                                </button>
                                <button type="button" class="btn btn-default mr-2" onclick="resetSeq()">
                                    <i class="fa fa-refresh"></i>&nbsp;Reset ลำดับ
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="search-box">
                                    <span class="search-form-label">ค้นหา:&nbsp;</span>
                                    <input class="form-control search-form-input" type="text" id="search" onkeyup="searchDataTable()" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table_match" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center pr-5 no-sort" id="th_chk_all"></th>
                                                <th class="text-left">ชื่อแมทช์</th>
                                                <th class="text-left">เวลาแข่ง</th>
                                                <th class="text-left">ทีมเหย้า</th>
                                                <th class="text-left">ทีมเยือน</th>
                                                <th class="text-left">ลำดับ</th>
                                                <th class="text-center no-sort">ตัวจัดการ</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        var table;
        var chk_in_page = 0;
        var tr_team = [];
        var chk = [];
        var chk_ele = '<label class="ctainer">';
            chk_ele +=      '<input type="checkbox" class="chk-box chk-all" onclick="tickAll($(this))">';
            chk_ele +=      '<span class="checkmark"></span>';
            chk_ele += '</label>';

        $(function() {
            dataTable();
            $('.tooltips').tooltip();
        });

        function searchDataTable(){
            table.ajax.reload();
            // $('#table_match').DataTable().ajax.reload();
        }

        // function filterTable(obj){
        //     table.ajax.reload();

        //     setTimeout(function(){
        //         $('.btn').blur();
        //     }, 1000);
        // }

        function dataTable() {
            table = $('#table_match').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[5, 10, 15, 20, 25, 30, 50, 100], [5, 10, 15, 20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/match/list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        chk_in_page = 0;
                        tr_team = [];
                        chk = [];
                        d.search = $('#search').val();
                        // d.filter = $('#chk_action').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'match');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_match_total').html(oSettings.fnRecordsTotal());
                    $('table.dataTable thead .no-sort.sorting_desc').css('cursor', 'auto');
                    $('table.dataTable thead .no-sort.sorting_desc').css('position', 'unset');

                    $('#th_chk_all').html(chk_ele);
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(8).addClass($('td', row).eq(8).find('span').attr('class'));
                    // $('td', row).eq(0).attr('id', 'tr_' + (index + 1));
                    $('td', row).eq(0).addClass('tr-team');
                    // console.log(data[0]);
                    const id_name = $('td', row).eq(0).find('input').attr('id');
                    const id = id_name.split('_')[1];
                    chk.push(id);
                    tr_team.push(data[0]);
                    chk_in_page++;
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-center' },
                    { "className":'text-center' }
                ],
                "columnDefs": [
                    {
                        "targets"  : 'no-sort',
                        "orderable": false,
                        "order" : []
                    }
                ]
                ,"order": [[2, 'desc']]
            });
        }

        function tickAll(this_ele) {
            $('.tr-team').each(function (idx, ele) {
                $(this).html(tr_team[idx]);
            });

            if (this_ele.prop('checked')) {
                $('.chk-box:not(.chk-all)').each(function (idx, ele) {
                    $('#' + ele.id).attr('checked', true);
                });
            }
        }

        function saveSeq() {
            Swal.fire({
                title: 'ต้องการบันทึกลำดับหรือไม่?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    submitSeqForm('save');
                }
            });
        }

        function resetSeq() {
            Swal.fire({
                title: 'ต้องการ Reset ลำดับหรือไม่?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    submitSeqForm('reset');
                }
            });
        }
    
        function submitSeqForm(mode) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();

                    var arrSeq = [];
                    var seq = $('input[name="match_seq[]"]');
                    var ele_match_id = 0;

                    seq.each(function(idx, ele) {
                        ele_match_id = ele.id.split('_')[2];
                        arrSeq.push({id: ele_match_id, seq: ele.value});
                        // console.log(ele.id, ele_match_id, ele.value);
                    });

                    // console.log(arrSeq);

                    var params = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'arrSeq': arrSeq
                    };

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/match/' + mode + '-seq',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total != 0) {
                                saveSuccessReload();
                            } else {
                                showWarning('Warning!', response.message);
                            }
                        },
                        error: function (response) {
                            showRequestWarning(response);
                        }
                    });
                }
            });
        }
    </script>
@stop