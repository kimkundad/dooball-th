@extends('backend.layouts.master')

@section('title', 'จัดการ API Football')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">เมนู</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs" id="api_football_tabs">
                    <li class="active">
                        <a href="javascript:void(0)" class="link-tab" link-to="livescore" data-id="1" data-page="Livescore">Livescore</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="link-tab" link-to="leagues" data-id="2" data-page="Leagues">Leagues</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="link-tab" link-to="teams" data-id="3" data-page="Teams">Teams</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="link-tab" link-to="players" data-id="4" data-page="Players">Players</a>
                    </li>
                </ul>
                <div class="tab-content bg-white theme-border" id="api_football_content">
                    <div class="tab-pane show active" id="livescore">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                                <h4 class="pl-2">หน้า<a href="{{ route('livescore') }}" target="_BLANK">ผลบอลสด</a></h4>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-right">
                                <a href="{{ URL::to('/') }}/admin/settings/api-football/create-api-page/1" class="btn btn-success btn-block add-btn" style="margin-top: 7px; margin-bottom: 3px; margin-right: 3px;">
                                    <i class="fa fa-plus"></i>&nbsp;เพิ่ม API
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="leagues">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                                <h4 class="pl-2">หน้า<a href="{{ url('premierleague') }}" target="_BLANK">ลีกต่างๆ</a></h4>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-right">
                                <a href="{{ URL::to('/') }}/admin/settings/api-football/create-api-page/2" class="btn btn-success btn-block add-btn" style="margin-top: 7px; margin-bottom: 3px; margin-right: 3px;">
                                    <i class="fa fa-plus"></i>&nbsp;เพิ่ม API
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="teams">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                                <h4 class="pl-2">หน้า<a href="{{ url('teams/Liverpool') }}" target="_BLANK">ทีม</a></h4>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-right">
                                <a href="{{ URL::to('/') }}/admin/settings/api-football/create-api-page/3" class="btn btn-success btn-block add-btn" style="margin-top: 7px; margin-bottom: 3px; margin-right: 3px;">
                                    <i class="fa fa-plus"></i>&nbsp;เพิ่ม API
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="players">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
                                <h4 class="pl-2">หน้า<a href="javascript:void(0)" target="_BLANK">นักเตะ</a></h4>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-right">
                                <a href="{{ URL::to('/') }}/admin/settings/api-football/create-api-page/4" class="btn btn-success btn-block add-btn" style="margin-top: 7px; margin-bottom: 3px; margin-right: 3px;">
                                    <i class="fa fa-plus"></i>&nbsp;เพิ่ม API
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="panel mb-0">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span>ทั้งหมด&nbsp;</span>
                                    <span class="text-primary text-bold" id="result_total"><i class="fa fa-spinner"></i></span>
                                    <span>&nbsp;รายการ</span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row no-marg">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pdd">
                                    <div class="table-responsive">
                                        <table id="table_api_items" class="table table-condensed table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center no-sort">&nbsp;&nbsp;ลำดับ&nbsp;&nbsp;</th>
                                                    <th class="text-left no-sort">API url</th>
                                                    <th class="text-left no-sort">รายละเอียด</th>
                                                    <th class="text-center no-sort">การรับค่าตัวแปร</th>
                                                    <th class="text-center no-sort"><i class="fa fa-cogs"></i></th>
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
        </div>
    </section>
    
    <section class="content container-fluid">
        <div class="row display-result hide-ele">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-primary mb-0 no-border">
                    {{-- <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex">
                                <h4 class="text-white">กลุ่ม&nbsp;<span class="use-in-page"></span></h4>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex">
                            </div>
                        </div>
                    </div> --}}
                    <div class="panel-body no-pdd">
                        <div class="row no-marg">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pdd">
                                <div class="response-loading">Loading..</div>
                                <pre id="pre_response"></pre>
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
        var apiPageId = 1;

        $(function() {
            $('#api_football_tabs li .link-tab').on('click', function() {
                if (! $('#api_football_content #' + $(this).attr('link-to')).hasClass('show')) {
                    $('#api_football_tabs li').removeClass('active');
                    $(this).parent().addClass('active');

                    $('#api_football_content .tab-pane').removeClass('show');
                    $('#api_football_content .tab-pane').removeClass('active');
                    $('#api_football_content #' + $(this).attr('link-to')).addClass('show');
                    $('#api_football_content #' + $(this).attr('link-to')).addClass('active');
                    
                    $('.display-result').addClass('hide-ele');
                    document.getElementById("pre_response").textContent = '';
                    $('#pre_response').addClass('hide-ele');

                    apiPageId = $(this).attr('data-id');
                    table.ajax.reload();
                }
            });

            dataTable();
            $('.tooltips').tooltip();
        });

        function dataTable() {
            table = $('#table_api_items').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10,15,20, 25, 30, 50, 100], [10,15,20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/api-football/list',
                    "type":"POST",
                    "beforeSend": function(response){
                        response.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        // d.req = (($('#req').is(':checked'))? 'T' : 'F');
                        d.api_page_id = apiPageId;
                        // d.created_at = $('#created_at').val();
                        // d.filter = $('#menu_filter').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'menu');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_total').html(oSettings.fnRecordsTotal());
                },
                "createdRow": function(row, data, index){
                    $('td', row).eq(8).addClass($('td', row).eq(8).find('span').attr('class'));
                    var colClass = $('td', row).eq(3).find('button').attr('class');

                    $('td', row).eq(3).find('button').on('click', function() {
                        var apiUrl = $(this).attr('data-url');
                        var apiId = $(this).attr('api-id');
                        var apiParams = $('#params-mask-'+ apiId).val();
                        var fullUrl = apiUrl;

                        if (apiParams) {
                            fullUrl = apiUrl + '' + apiParams;
                        }

                        $('.display-result').removeClass('hide-ele');

                        document.getElementById("pre_response").textContent = '';
                        $('#pre_response').addClass('hide-ele');

                        $('.response-loading').html('Loading..');
                        $('.response-loading').removeClass('hide-ele');

                        $('.use-in-page').html($('#api_football_tabs li.active a').attr('data-page'));
                        $('#table_api_items tbody tr').css('background', '#f9f9f9');
                        $(row).css('background', '#dedede');

                        showApiResponse(fullUrl);
                    });
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

        function deleteItem(id) {
            Swal.fire({
                title: 'ยืนยันการทำรายการ?',
                // text: "You won't be able to revert this!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    callDelete(id);
                }
            });
        }

        function callDelete(id) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    const formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('id', id);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/api-football/delete',
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
                                showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
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

        function showApiResponse(fullUrl) {
            var params = {full_path: fullUrl};

            $.ajax({
                url: $('#base_url').val() + '/api/api-football',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    if (response.status_code == 200) {
                        if (response.datas) {
                            $.each( response.datas, function( key, value ) {
                                if (key != 'results') {
                                    renderResponse(response[key]);
                                }
                            });
                        } else {
                            $('.response-loading').html('-- ไม่มีข้อมูล --');
                        }
                    } else {
                        $('.response-loading').html(response.status_message);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function renderResponse(resp) {
            var strList = JSON.stringify(resp, undefined, 2);

            $('.response-loading').addClass('hide-ele');
            $('#pre_response').removeClass('hide-ele');
            document.getElementById("pre_response").textContent = strList;

            /*
            if ($.isArray(resp)) {
                if (resp.length > 0) {
                    var strList = JSON.stringify(resp, undefined, 2);

                    $('.response-loading').addClass('hide-ele');
                    $('#pre_response').removeClass('hide-ele');
                    document.getElementById("pre_response").textContent = strList;
                }
            } else {
            }*/
        }
    </script>
@stop