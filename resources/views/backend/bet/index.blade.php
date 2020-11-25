@extends('backend/layouts.master')

@section('title', 'จัดการเกมส์ทายผล')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">เกมส์ทายผลบอล</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                รายการเกมส์ทายผลทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_bet_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row mbt-10">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" name="chk_action" id="chk_action" onchange="doSomethingWithIt($(this), 'bet')">
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
                                    <table id="table_bet" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center pr-5 no-sort" id="th_chk_all"></th>
                                                <th class="text-center no-sort pr-5">#</th>
                                                <th class="text-left no-sort">ผู้เล่น</th>
                                                <th class="text-left no-sort">รหัสข้อมูลกราฟ</th>
                                                <th class="text-left no-sort">ชื่อลีก</th>
                                                <th class="text-left">เวลาแข่ง</th>
                                                <th class="text-left no-sort">ทีมเหย้า</th>
                                                <th class="text-left">ทีมเยือน</th>
                                                <th class="text-left">ราคาต่อรอง</th>
                                                <th class="text-left">ต่อ</th>
                                                <th class="text-left">ผลบอล</th>
                                                <th class="text-center">วันที่เล่น</th>
                                                <th class="text-left">IP Address</th>
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
        var tr_bet = [];
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
            table = $('#table_bet').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[5, 10, 15, 20, 25, 30, 50, 100], [5, 10, 15, 20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/bet/list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        chk_in_page = 0;
                        tr_bet = [];
                        chk = [];
                        d.search = $('#search').val();
                        // d.filter = $('#chk_action').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'bet');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_bet_total').html(oSettings.fnRecordsTotal());
                    $('table.dataTable thead .no-sort.sorting_desc').css('cursor', 'auto');
                    $('table.dataTable thead .no-sort.sorting_desc').css('position', 'unset');

                    $('#th_chk_all').html(chk_ele);
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(0).attr('id', 'tr_' + (index + 1));
                    $('td', row).eq(0).addClass('tr-bet');
                    // console.log(data[0]);
                    const id_name = $('td', row).eq(0).find('input').attr('id');
                    const id = id_name.split('_')[1];
                    chk.push(id);
                    tr_bet.push(data[0]);
                    chk_in_page++;
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'}, // checkbox
                    { "className":'text-center' }, // id
                    { "className":'text-left' }, // user_id
                    { "className":'text-left' }, // ffp_detail_id
                    { "className":'text-left' }, // match_time
                    { "className":'text-center' }, // league_name
                    { "className":'text-center' }, // home_team
                    { "className":'text-center' }, // away_team
                    { "className":'text-center' }, // bargain_price
                    { "className":'text-center' }, // match_continue
                    { "className":'text-center' }, // match result
                    { "className":'text-center' }, // created_at
                    { "className":'text-center' } // ip
                ],
                "columnDefs": [
                    {
                        "targets"  : 'no-sort',
                        "orderable": false,
                        "order" : []
                    }
                ]
                ,"order": [[11, 'desc']]
            });
        }

        function tickAll(this_ele) {
            $('.tr-bet').each(function (idx, ele) {
                $(this).html(tr_bet[idx]);
            });

            if (this_ele.prop('checked')) {
                $('.chk-box:not(.chk-all)').each(function (idx, ele) {
                    $('#' + ele.id).attr('checked', true);
                });
            }
        }
    </script>
@stop