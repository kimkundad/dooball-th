@extends('backend/layouts.master')

@section('title', 'จัดการลิงค์')

@section('custom-css')
    <style>
        .panel {
            margin-top: 1px;
            border-top: 0px;
            -webkit-border-radius: 0px;
            -webkit-border-bottom-right-radius: 4px;
            -webkit-border-bottom-left-radius: 4px;
            -moz-border-radius: 0px;
            -moz-border-radius-bottomright: 4px;
            -moz-border-radius-bottomleft: 4px;
            border-radius: 0px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .panel-heading {
            padding-top: 3px;
            padding-bottom: 3px;
        }
    </style>
@endsection

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">จัดการลิงค์</a></li>
    @endsection

    <section class="content container-fluid">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#link_name">จัดการชื่อลิงค์</a>
            </li>
            <li>
                <a data-toggle="tab" href="#link_match" onclick="loadLinkMatch()">จัดการลิงค์ในแมทช์</a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="link_name" class="tab-pane fade in active">
                @include('backend._partials.link.link-name')
            </div>
            <div id="link_match" class="tab-pane fade in active">
                @include('backend._partials.link.link-match')
            </div>
        </div>
    </section>
@endsection

@section('custom-scripts')
    <script>
        var firstLoad = 0;
        var tableLinkName;
        var tableLinkMatch;

        $(function() {
            dataTableLinkName();
            $('.tooltips').tooltip();
        });

        // function searchDataTableLinkName(){
        //     tableLinkName.ajax.reload();
        // }

        function dataTableLinkName() {
            tableLinkName = $('#table_link').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[5, 10, 15, 20, 25, 30, 50, 100], [5, 10, 15, 20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/link/list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        d.search = $('#search').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'linkname');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_link_total').html(oSettings.fnRecordsTotal());
                    $('table.dataTable thead .no-sort.sorting_desc').css('cursor', 'auto');
                    $('table.dataTable thead .no-sort.sorting_desc').css('position', 'unset');
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(0).addClass('tr-link');
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
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
                ,"order": [[1, 'desc']]
            });
        }

        function loadLinkMatch() {
            if (firstLoad == 0) {
                dataTableLinkMatch();
            }
        }

        // function searchDataTableLinkMatch(){
        //     tableLinkMatch.ajax.reload();
        // }

        function dataTableLinkMatch() {
            firstLoad = 1;
            tableLinkMatch = $('#table_link_match').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[5, 10, 15, 20, 25, 30, 50, 100], [5, 10, 15, 20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/link/match-list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        d.search = $('#search').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'linkmatch');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_link_match_total').html(oSettings.fnRecordsTotal());
                    $('table.dataTable thead .no-sort.sorting_desc').css('cursor', 'auto');
                    $('table.dataTable thead .no-sort.sorting_desc').css('position', 'unset');
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(0).addClass('tr-link');
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-center' },
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
                ,"order": [[1, 'desc']]
            });
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
                    submitSeqForm();
                }
            });
        }

        function submitSeqForm() {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();

                    var arrSeq = [];
                    var seq = $('input[name="link_seq[]"]');
                    var ele_link_id = 0;

                    seq.each(function(idx, ele) {
                        ele_link_id = ele.id.split('_')[2];
                        arrSeq.push({id: ele_link_id, seq: ele.value});
                        // console.log(ele.id, ele_link_id, ele.value);
                    });

                    // console.log(arrSeq);

                    var params = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'arrSeq': arrSeq
                    };

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/link-match/save-seq',
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