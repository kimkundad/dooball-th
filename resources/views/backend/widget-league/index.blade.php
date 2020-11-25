@extends('backend.layouts.master')

@section('title', 'จัดการ Widget League')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">Widget League</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9 col-sm-9 pddt7">
                                รายการเมนูทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_wgl_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                            </div>
                            <div class="col-md-3 col-sm-3 text-right">
                                <a href="{{ URL::to('/') }}/admin/settings/league-decoration/create">
                                    <span class="btn btn-success add-btn"><i class="fa fa-plus"></i>&nbsp;เพิ่มข้อมูล</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row no-marg">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pdd">
                                <div class="table-responsive">
                                    <table id="table_wgl" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">&nbsp;&nbsp;ID&nbsp;&nbsp;</th>
                                                <th class="text-left">ชื่อหัวข้อ</th>
                                                <th class="text-left">ใช้ในลีก</th>
                                                <th class="text-left">ใช้ในหน้า</th>
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
        var table;

        $(function() {
            dataTable();
            $('.tooltips').tooltip();
        });

        function dataTable() {
            table = $('#table_wgl').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10,15,20, 25, 30, 50, 100], [10,15,20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/league-decoration/list',
                    "type":"POST",
                    "beforeSend": function(response){
                        response.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        // d.req = (($('#req').is(':checked'))? 'T' : 'F');
                        // d.title = $('#title').val();
                        // d.created_at = $('#created_at').val();
                        // d.filter = $('#menu_filter').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'league-decoration');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_wgl_total').html(oSettings.fnRecordsTotal());
                },
                "createdRow": function(row, data, index){
                    $('td', row).eq(8).addClass($('td', row).eq(8).find('span').attr('class'));
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-center' }
                ],
                "columnDefs": [
                    {
                        "targets"  : 'no-sort',
                        "orderable": false,
                        "order" : []
                    }
                ]
                ,"order": [[0, 'asc']]
            });
        }
    </script>
@stop