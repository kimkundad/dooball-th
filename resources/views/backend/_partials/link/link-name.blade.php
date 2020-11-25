<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row mbt-10">
                    <div class="col-md-9 col-sm-9 mt-5">
                        รายการเมนูทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_link_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                    </div>
                    <div class="col-md-3 col-sm-3 text-right">
                        <a href="{{ URL::to('/') }}/admin/link/create">
                            <span class="btn btn-success add-btn"><i class="fa fa-plus"></i>&nbsp;เพิ่มข้อมูล</span>
                        </a>
                    </div>
                </div>
                <div class="row no-marg">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pdd">
                        <div class="table-responsive">
                            <table id="table_link" class="table table-striped table-condensed table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">&nbsp;&nbsp;ID&nbsp;&nbsp;</th>
                                        <th class="text-left">ลิงค์</th>
                                        <th class="text-left">คำที่แสดงในหน้าเว็บ</th>
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