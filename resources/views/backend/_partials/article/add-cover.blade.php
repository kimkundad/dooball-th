<div class="panel panel-primary">
    <div class="panel-heading">ภาพหน้าปก</div>
    <div class="panel-body">
        <div class="flex-card">
            <div class="box-picture">
                <div class="box-preview"><i class="fa fa-camera fa-5x"></i></div>
                <div class="box-btn">
                    <div class="btn btn-sm btn-file">
                        <a class="file-input-wrapper">Browse
                            <input type="file" accept="image/*" class="input-type-file" name="media_file" />
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm clear-multi-file" onclick="resetDefault($(this));">&nbsp;<i class="fa fa-trash-o"></i>&nbsp;</button>
                </div>
            </div>
        </div>
        <div class="box-image-name mt-10">
            <div class="input-group">
                <input type="text" class="form-control" name="img_name" id="img_name" value="" placeholder="ชื่อภาพ" />
                <span class="input-group-addon" id="img_ext"></span>
            </div>
        </div>
        <div class="box-image-alt">
            <div class="input-group">
                <span class="input-group-addon" id="image_alt">alt</span>
                <input type="text" class="form-control" name="alt" id="alt" value="" placeholder="alt ของภาพ" />
            </div>
        </div>
        <div><br>ขนาดภาพที่เลือก กว้าง x สูง</div>
        <div class="box-set-dimension">
            <div class="dimension-detail">
                <input type="text" class="form-control" name="witdh" id="witdh" value="" placeholder="กว้าง" readonly />
            </div>&nbsp;&nbsp;x&nbsp;&nbsp;
            <div class="dimension-detail">
                <input type="text" class="form-control" name="height" id="height" value="" placeholder="สูง" readonly />
            </div>
        </div>
        {{-- <div class="box-dimension">ขนาดภาพที่เหมาะสม: <span class="dimension-color">120 x 120</span></div> --}}
    </div>
</div>