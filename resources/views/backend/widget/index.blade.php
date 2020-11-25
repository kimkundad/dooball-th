@extends('backend/layouts.master')

@section('title', 'วิดเจ็ตและบล็อก')

@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/widget.css') }}">
@endsection

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">วิดเจ็ตและบล็อก</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            @include('backend._partials.widget.widget_left')
            @include('backend._partials.widget.widget_right')
        </div>
    </section>

    <div class="modal fade" id="widgetModal" tabindex="-1" role="dialog" aria-labelledby="widgetModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form role="form" id="widget_form">
                    @csrf
                    <input type="hidden" name="widget_order_id" id="widget_order_id" value="">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">หัวข้อ:</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                        <div class="form-group">
                            <div class="admin-cbox mt-10 mbt-10">
                                <input type="checkbox" class="chkb" name="show_title_on_widget" id="show_title_on_widget" value="1" onclick="tickWidgetCheckbox()" />
                                <label class="cb" for="show_title_on_widget">แสดงชื่อนี้บน widget</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Content:</label>
                            <div class="col-md-12" id="widget_content"></div>
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
@endsection

@section('custom-lib-scripts')
    <script type="text/javascript" src="{{asset('backend/ckeditor4-full/ckeditor.js')}}"></script>
@endsection

@section('custom-scripts')
    <script>
        var tkChk = false;

        $.ajaxSetup({
            cache: false
        });

        $(function() {
            widgetOrderList();

            $(".widget-item").draggable({
                connectToSortable: ".sortable",
                helper: "clone",
                revert: "invalid",
                start: function(e, ui) {
                    // ui.helper.addClass("dragging");
                },
                stop: function(e, ui) {
                    var name = $(this).attr('name');
                    var nameList = $('[name="' + name + '"]');

                    var widgetName = '';
                    var widgetPosition = '';
                    var widget_dom_id = '';
                    nameList.each(function(idx, ele) {
                        widgetName = $(ele).attr('name');
                        if (idx != 0 && widgetName) {
                            widgetPosition = $(ele).parent().attr('id');
                            widget_dom_id = $(ele).attr('data-widget');
                            $(ele).removeAttr('name');
                            addThisWidgetInPosition(widget_dom_id, widgetPosition, $(ele));
                        }
                    });
                }
            });

            $(".sortable").sortable({
                // revert: true
            });

            $('#widget_form').on('submit', (function (e) {
                Swal.fire({
                    title: 'ยืนยันการทำรายการ?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
						if (result.value) {
							submitWidgetForm(this);
						}
                    }
                });

                return false;
            }));
        });

        function tickWidgetCheckbox() {
            tkChk = !tkChk;
        }

        function modalClick(title, this_ele) {
            $('.modal-title').html(title);

            $('#title').val('');
            $('#show_title_on_widget').removeAttr('checked');
            $('#widget_content').html('');

            var order_id = this_ele.parent().parent().parent().attr('data-id');

            const params = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'widget_order_id': order_id
            };

            $.ajax({
                url: $('#base_url').val() + '/api/admin/widget/order-info',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);

                    var title = response.data.title;
                    var detail = '' + response.data.detail;
                    var show_title_name = response.data.show_title_name;

                    $('#title').val(title);
                    $('#widget_order_id').val(order_id);
                    $('#widget_content').html('<textarea class="form-control" name="widget_detail" id="widget_detail"></textarea>');
                    $('#widget_detail').val(detail);

                    CKEDITOR.replace('widget_detail', {
                        height: '350px',
                        allowedContent: true
                    });

                    if (parseInt(show_title_name) == 1) {
                        $('#show_title_on_widget').attr('checked', true);
                    } else {
                        $('#show_title_on_widget').removeAttr('checked');
                    }
                },
                error: function(response) {
                    showRequestWarning(response);
                }
            });
        }

        function showHideWidgetOrder(this_ele) {
            var order_id = this_ele.parent().parent().parent().attr('data-id');

            if (order_id) {
                var sh = (this_ele.html() == '<i class="fa fa-eye"></i>') ? 'ซ่อน' : 'แสดง' ;

                Swal.fire({
                    title: 'ต้องการ' + sh + 'วิดเจ็ตนี้หรือไม่?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
						if (result.value) {
                            callAPIShowHide(order_id, this_ele);
						}
                    }
                });
            }
        }

        function callAPIShowHide(order_id, this_ele) {
            const params = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'widget_order_id': order_id
            };

            $.ajax({
                url: $('#base_url').val() + '/api/admin/widget/order-show-hide',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);

                    if (response.total == 1) {
                        // saveSuccess();
                        if (response.active == 1) {
                            this_ele.html('<i class="fa fa-eye"></i>');
                        } else {
                            this_ele.html('<i class="fa fa-eye-slash"></i>');
                        }
                    } else {
                        showWarning('Warning!', response.message);
                    }
                },
                error: function(response) {
                    showRequestWarning(response);
                }
            });
        }

        function deleteWidgetOrder(this_ele) {
            var order_id = this_ele.parent().parent().parent().attr('data-id');

            if (order_id) {
                Swal.fire({
                    title: 'ต้องการลบวิดเจ็ตนี้หรือไม่?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {
                    if (result.value) {
						if (result.value) {
                            callAPIDelete(order_id);
						}
                    }
                });
            }
        }

        function callAPIDelete(order_id) {
            const params = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'widget_order_id': order_id
            };

            $.ajax({
                url: $('#base_url').val() + '/api/admin/widget/order-delete',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);

                    if (response.total == 1) {
                        // saveSuccess();
                        $('[data-id=' + order_id + ']').remove();
                    } else {
                        showWarning('Warning!', response.message);
                    }
                },
                error: function(response) {
                    showRequestWarning(response);
                }
            });
        }

        function widgetOrderList() {
            orderList('homepage_block_one'); // 1
            orderList('homepage_block_two'); // 2
            orderList('top_navigation_left'); // 3
            orderList('top_navigation_right'); // 4
            orderList('main_footer'); // 5
            orderList('sub_footer'); // 6
            orderList('top_banner'); // 7
            orderList('floating_left'); // 8
            orderList('floating_right'); // 9
            orderList('floating_bottom'); // 10
            orderList('footer_banner'); // 11
            orderList('home_top_banner'); // 12
            orderList('home_aside_banner'); // 13
            orderList('home_between_match'); // 14
            orderList('home_between_match_two'); // 15
            orderList('home_after_match'); // 16
            orderList('home_after_article'); // 17
            orderList('article_sidebar'); // 18
        }

        function orderList(position_dom_id) {
            const params = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'position_dom_id': position_dom_id
            };

            $.ajax({
                url: $('#base_url').val() + '/api/admin/widget/order-list',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);

                    if (response.total >= 1) {
                        var widgetOrderId = 0;
                        var widgetDomId = '';
                        var active = 0;
                        var widgetDomName = '';
                        var cloneWidget = '';

                        $.each(response.wPositionList, function(idx, ele) {
                            widgetOrderId = ele.widget_order_id;
                            widgetDomId = ele.widget_dom_id;
                            active = ele.active_status;
                            widgetDomName = ele.widget_name;

                            cloneWidget = '<div class="widget-item portlet solid blue" data-widget="' + widgetDomId + '" data-id="' + widgetOrderId + '">';
                            cloneWidget +=  '<div class="portlet-title">';
                            cloneWidget +=      '<div class="caption">' + widgetDomName + '</div>';
                            cloneWidget +=      '<div class="actions">';
                            cloneWidget +=          '<a href="javascript:void(0)" class="btn btn-sm btn-default" onclick="showHideWidgetOrder($(this))">';
                            cloneWidget +=              (active == 1) ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
                            cloneWidget +=          '</a>';
                            cloneWidget +=          '<a class="btn btn-sm btn-default" data-toggle="modal" data-target="#widgetModal" onclick="modalClick(\'' + widgetDomName + '\', $(this))">';
                            cloneWidget +=              '<i class="fa fa-cog"></i>';
                            cloneWidget +=          '</a>';
                            cloneWidget +=          '<a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="deleteWidgetOrder($(this))">';
                            cloneWidget +=              '<i class="fa fa-times"></i>';
                            cloneWidget +=          '</a>';
                            cloneWidget +=      '</div>';
                            cloneWidget +=  '</div>';
                            cloneWidget += '</div>';

                            $('#' + position_dom_id).append(cloneWidget);
                        });
                    }
                },
                error: function(response) {
                    showRequestWarning(response);
                }
            });

            return false;
        }

        function addThisWidgetInPosition(widget_dom_id, position_dom_id, this_ele) {
            const params = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'widget_dom_id': widget_dom_id,
                'position_dom_id': position_dom_id
            };

            $.ajax({
                url: $('#base_url').val() + '/api/admin/widget/order-create',
                type: 'POST',
                data: params,
                dataType: 'json',
                cache: false,
                success: function (response) {
                    // console.log(response);

                    if (response.total == 1) {
                        this_ele.attr('data-id', response.widget_order_id);
                    } else {
                        showWarning('Warning!', response.message);
                    }
                },
                error: function(response) {
                    showRequestWarning(response);
                }
            });

            return false;
        }

        function submitWidgetForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();

                    var content = CKEDITOR.instances.widget_detail.getData();
                    // console.log(content);
                    var params = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'widget_order_id': $('#widget_order_id').val(),
                        'title': $('#title').val(),
                        'show_title_name': ((tkChk) ? 1 : 0),
                        'detail': content
                    };

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/widget/order-update',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        cache: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total == 1) {
                                saveSuccess();
                                // $('#widgetModal').modal().hide();
                                $("#widgetModal .close").click()
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
    </script>
@stop