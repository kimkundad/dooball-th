<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row mbt-10">
                    <div class="col-lg-3 col-md-4 col-sm-6 mt-5">
                        รายการ Match link ทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_link_match_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                    </div>
                    {{-- <div class="col-md-3 col-sm-3 text-right">
                        <a href="{{ URL::to('/') }}/admin/link-match/create">
                            <span class="btn btn-success add-btn"><i class="fa fa-plus"></i>&nbsp;เพิ่มข้อมูล</span>
                        </a>
                    </div> --}}
                    <div class="col-lg-9 col-md-8 col-sm-6 col-xs-12 text-right">
                        {{-- <a href="{{ URL::to('/') }}/admin/match/create">
                            <span class="btn btn-success add-btn"><i class="fa fa-plus"></i>&nbsp;เพิ่มข้อมูล</span>
                        </a> --}}
                        <button type="button" class="btn btn-primary mr-2" onclick="saveSeq()">
                            <i class="fa fa-save"></i>&nbsp;บันทึกลำดับ
                        </button>
                        {{-- <button type="button" class="btn btn-default mr-2" onclick="resetSeq()">
                            <i class="fa fa-refresh"></i>&nbsp;Reset ลำดับ
                        </button> --}}
                    </div>
                </div>
                <div class="row no-marg">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pdd">
                        <div class="table-responsive">
                            <table id="table_link_match" class="table table-striped table-condensed table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">&nbsp;&nbsp;ID&nbsp;&nbsp;</th>
                                        <th class="text-left">ชื่อแมทช์</th>
                                        <th class="text-left">ประเภทลิ้งค์</th>
                                        <th class="text-left">ชื่อลิ้งค์</th>
                                        <th class="text-left">URL ลิ้งค์</th>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center"><i class="fa fa-cog"></i></th>
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