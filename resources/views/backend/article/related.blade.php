@extends('backend/layouts.master')

@section('title', 'บทความที่เกี่ยวข้อง')

@section('custom-css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    @section('breadcrumb')
        <li><a href="{{ URL::to('/') }}/admin/article"><span class="icon icon-beaker"></span>บทความทั้งหมด</a></li>
        <li><a onclick="Javascript:void(0);"><span class="icon icon-double-angle-right"></span>บทความที่เกี่ยวข้อง</a></li>
    @endsection
    <form role="form" id="article_form">
        @csrf
        <input type="hidden" name="article_id" id="article_id" value="{{ $form->id }}">
        <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 pddt7">
                                <h3 class="back-title">จัดเรียง
                                {{--<span class="left-time">(เหลือเวลาในระบบอีก {{ $left_time }})</span></h3>--}}
                            </div>							
                            <div class="col-md-6 col-sm-6 text-right">
                                <button type="button" class="btn btn-success" onclick="saveSorting();"><i class="fa fa-save"></i>&nbsp;บันทึกการจัดเรียง</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row no-marg">
                            <div class="col-12 no-pdd">
                                <?php
                                    $related = array();

                                    if (trim($form->related)) {
                                        $related = explode(',', trim($form->related));
                                    }

                                    if (count($related)) {
                                ?>
                                    <ul id="sortable">
                                        <?php
                                                foreach($related as $id) {
                                        ?>
                                                    <li class="ui-state-default related-sortable" id="sort_<?php echo $id; ?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>ID #<?php echo $id; ?></li>
                                        <?php
                                                }
                                        ?>
                                    </ul>
                                <?php
                                    } else {
                                        echo '--- ยังไม่ได้เลือกบทความ ---';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9 col-sm-9 pddt7">
                                <h3 class="back-title">เลือกบทความที่เกี่ยวข้อง</h3>
                            </div>
                            <div class="col-md-3 col-sm-3 text-right pddt7">
                                <button type="button" class="btn btn-success" onclick="relateSelected();"><i class="fa fa-save"></i>&nbsp;บันทึกการเลือก</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="po_name" class="col-md-6 col-sm-6 col-xs-12 pddt5 form-label text-right">ชื่อบทความ&nbsp;:</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12 no-pdd">
                                        <input type="text" class="form-control" id="title" onkeyup="if (event.keyCode == 13) searchData();" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="search_btn"><i class="fa fa-search"></i>&nbsp;ค้นหา</button>
                                    <button type="button" class="btn btn-default" id="reset_btn"><i class="fa fa-th-list"></i>&nbsp;แสดงทั้งหมด</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cover-filter">
                                <select class="form-control filter" id="article_filter" onchange="filterTable($(this));">
                                    <option value="-1">--- แสดงตามสถานะ ---</option>
                                    <option value="0">รอผล</option>
                                    <option value="1">ผลบอลถูก</option>
                                    <option value="2">ผลบอลผิด</option>
                                    <option value="3">รายการที่ถูกลบ</option>
                                </select>
                            </div>
                        </div>

                        <div class="row no-marg">
                            <div class="col-12 no-pdd">
                                <div class="table-responsive">
                                    <table id="table_article" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">&nbsp;&nbsp;ID&nbsp;&nbsp;</th>
                                                <th class="text-center no-sort">ภาพหน้าปก</th>
                                                <th class="text-left">หัวข้อ</th>
                                                <th class="text-center">สถานะ</th>
                                                <th class="text-center no-sort"><i class="fa fa-plus"></i>&nbsp;เลือก</th>
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
    </form>
    <input type="hidden" id="related" value="{{ $form->related }}">
@endsection

@section('custom-scripts')
    <script>
        var tempIds = [];
        var table;

        $(function() {
            $( "#sortable" ).sortable({
                // update: function( event, ui ) {
                //	saveSorting();
                // }
            });

            getTempIds();

            $('#search_btn').click(function(){
                searchData();
            });
            $('#reset_btn').click(function(){
                $("#title").val('');
                $("#article_filter").val('-1');
                table.ajax.reload();
            });
        });
        
        function sortingRelatedIds() {
            const arrIds = [];
            $('.related-sortable').each(function(){
                var this_id = $(this).attr('id');
                var related_id = this_id.split('_')[1];
                arrIds.push(related_id);
            });
            return arrIds;
        }
        
        function getTempIds() {
            var relatedString = $('#related').val();
            if (relatedString) {
                var relatedList = relatedString.split(',');

                if (relatedList.length > 0) {
                    tempIds = relatedList;
                    tempIds = tempIds.map(function(item) {
                        return parseInt(item, 10);
                    });
                }
            }

            dataTable();
        }
        
        function checkRelated() {
            $('.chk-box').each(function() {
                var this_id = $(this).attr('id');
                var article_id = parseInt(this_id.split('_')[2]);
                if (tempIds.includes(article_id)) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });
        }
        
        function pushInTemp(article_id) {
            if ($('#article_id_'+ article_id).is(':checked')) {
                tempIds.push(article_id);
            } else {
                var index = tempIds.indexOf(article_id);
                if (index > -1) {
                    tempIds.splice(index, 1);
                }
            }
        }
        
        function searchData() {
            table.ajax.reload();
        }
        
        function filterTable(obj){
            table.ajax.reload();
        
            setTimeout(function(){
                $('.btn').blur();
            }, 1000);
        }
        
        function saveSorting() {
            var text = 'ต้องการบันทึกการจัดเรียงหรือไม่?';
            var conf = confirm(text);
        
            if (!conf) {
                return false;
            }

            const relatedIdsSorting = sortingRelatedIds();
            $.ajax({
                url: $('#base_url').val() +'/api/admin/article/related',
                type: 'POST',
                data: {'article_id': $('#article_id').val(), 'datas': relatedIdsSorting},
                beforeSend: function(){
                    // $('.action-loading').show();
                    // $('.action-loading').css('z-index',10050);
                    // $('.modal').css('z-index',10049);
                },
                dataType: 'json',
                success: function(response){
                    // console.log(response);
                    // $('.action-loading').hide();
        
                    if (response.total == 1) {
                        // saveSuccess();
                        // setTimeout(function(){
                            window.location.reload();
                        // }, 2000);
                    }
                }
            });
        }
        
        function relateSelected(){
            var text = 'ต้องการบันทึกรายการที่เลือกหรือไม่?';
            var conf = confirm(text);
        
            if (!conf) {
                return false;
            }
        
            // console.log(tempIds);
            if (tempIds.length>0) {
                $.ajax({
                    url: $('#base_url').val() +'/api/admin/article/related',
                    type: 'POST',
                    data: {'article_id': $('#article_id').val(), 'datas': tempIds},
                    beforeSend: function(){
                        // $('.action-loading').show();
                        // $('.action-loading').css('z-index',10050);
                        // $('.modal').css('z-index',10049);
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(response){
                        // console.log(response);
                        // $('.action-loading').hide();
        
                        if (response.total == 1) {
                            // saveSuccess();
                            // setTimeout(function(){
                                window.location.reload();
                            // }, 2000);
                        }
                    }
                });
            }
        }
        
        function dataTable() {
            table = $('#table_article').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10,15,20, 25, 30, 50, 100], [10,15,20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url":  $('#base_url').val() +'/api/admin/article/related-list',
                    "type":"POST",
                    "data": function(d){
                        d.title = $('#title').val();
                        d.article_id = $('#article_id').val();
                        d.search = $('#search').val();
                        // d.filter = $('#article_filter').val();
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('td').removeClass('sorting_1');
                    checkRelated();
                },
                "createdRow": function(row, data, index){
                    $('td', row).eq(8).addClass($('td', row).eq(8).find('span').attr('class'));
                },
                "pageLength": 10,
                "columns": [
                            { "className":'text-center'},
                            { "className":'text-center' },
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
                ,"order": [[0, 'desc']]
            });
        }        
    </script>
@endsection
