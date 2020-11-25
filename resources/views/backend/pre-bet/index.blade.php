@extends('backend/layouts.master')

@section('title', 'จัดการเกมทายผล')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">เกมทายผล</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                รายการเกมทายผลทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_prediction_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row mbt-10">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <select class="form-control" name="chk_action" id="chk_action" onchange="doSomethingWithIt($(this), 'prediction')">
                                    <option value="">ทำกับที่เลือก</option>
                                    <option value="delete">ไม่เปิดใช้งาน</option>
                                    <option value="restore">เปิดใช้งาน</option>
                                    {{-- <option value="real-delete">ลบทิ้ง</option> --}}
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="search-box">
                                    <span class="search-form-label">ค้นหา:&nbsp;</span>
                                    <input class="form-control search-form-input" type="text" id="search" placeholder="พิมพ์ชื่อลีก หรือ ชื่อทีม" onkeyup="searchDataTable()" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table_prediction" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center no-sort pr-5">#</th>
                                                <th class="text-left no-sort">รหัสข้อมูลกราฟ</th>
                                                <th class="text-left no-sort">ชื่อลีก</th>
                                                <th class="text-left">เวลาแข่ง</th>
                                                <th class="text-left no-sort">ทีมเหย้า</th>
                                                <th class="text-left">ทีมเยือน</th>
                                                <th class="text-left">ราคาต่อรอง</th>
                                                <th class="text-left">ต่อ</th>
                                                <th class="text-left">ผลบอล</th>
                                                <th class="text-center">วันที่เล่น</th>
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
        var tr_prediction = [];
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
        }

        function dataTable() {
            table = $('#table_prediction').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[5, 10, 15, 20, 25, 30, 50, 100], [5, 10, 15, 20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/pre-bet/list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        tr_prediction = [];
                        chk = [];
                        d.search = $('#search').val();
                        // d.filter = $('#chk_action').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'prediction');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_prediction_total').html(oSettings.fnRecordsTotal());
                    $('table.dataTable thead .no-sort.sorting_desc').css('cursor', 'auto');
                    $('table.dataTable thead .no-sort.sorting_desc').css('position', 'unset');

                    $('#th_chk_all').html(chk_ele);
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(0).attr('id', 'tr_' + (index + 1));
                    $('td', row).eq(0).addClass('tr-prediction');
                    // console.log(data[0]);
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
                    { "className":'text-center' },
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-center' },
                    { "className":'text-center' },
                    { "className":'text-center' },
                    { "className":'text-center' },
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
                ,"order": [[9, 'desc']]
            });
        }

        function tickAll(this_ele) {
            $('.tr-prediction').each(function (idx, ele) {
                $(this).html(tr_prediction[idx]);
            });

            if (this_ele.prop('checked')) {
                $('.chk-box:not(.chk-all)').each(function (idx, ele) {
                    $('#' + ele.id).attr('checked', true);
                });
            }
        }
    </script>
@stop