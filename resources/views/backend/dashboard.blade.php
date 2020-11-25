@extends('backend/layouts.master')

@section('title', 'Dashboard')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">Dashboard</a></li>
    @endsection

    <section class="content container-fluid">
        <!-- <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="page-header"><i class="fa fa-list"></i>&nbsp;เมนู</h1>
            </div>
        </div> -->

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                [Dashboard]
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
    </script>
@stop