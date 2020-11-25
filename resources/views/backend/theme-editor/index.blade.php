@extends('backend/layouts.master')

@section('title', 'แก้ไขธีมหน้าบ้าน')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">แก้ไขธีมหน้าบ้าน</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <form role="form" id="general_form">
                                    @csrf
                                    <div class="form-group">
                                        
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group mt-5">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12 pddt5 form-label text-right"></div>
                                        <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-save"></i>&nbsp;บันทึก</button>
                                        </div>
                                    </div>
                                </form>
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
        $(function() {
            // ...
        });
    </script>
@stop