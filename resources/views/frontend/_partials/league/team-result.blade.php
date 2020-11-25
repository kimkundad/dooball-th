<div class="card no-round no-border bg-trans">
    <div class="card-header card-prediction no-round no-border text-white">
        <h2>สถิติ ผลบอล {{ $team_name }}ล่าสุด</h2>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tm_total_tab" data-toggle="tab" href="#tm_total_content" role="tab" aria-controls="tm_total_content" aria-selected="true">Total</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tm_home_tab" data-toggle="tab" href="#tm_home_content" role="tab" aria-controls="tm_home_content" aria-selected="false">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tm_away_tab" data-toggle="tab" href="#tm_away_content" role="tab" aria-controls="tm_away_content" aria-selected="false">Away</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tm_total_content" role="tabpanel" aria-labelledby="tm_total_tab">
                <div class="table-responsive prediction-table-area">
                    <table class="table table-condensed text-white table-hist">
                        <thead>
                            <tr class="card-prediction">
                                <th>
                                    <span class="mr-10">ฟอร์ม 5 นัดหลังสุด {{ $team_name }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="h2h-total">
                                    <div class="table-loading">
                                        <div class="l-one"></div>
                                        <div class="l-two"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tm_home_content" role="tabpanel" aria-labelledby="tm_home_tab">
                <div class="table-responsive prediction-table-area">
                    <table class="table table-condensed text-white table-hist">
                        <thead>
                            <tr class="card-prediction">
                                <th>
                                    <span class="mr-10">ฟอร์มการพบกัน 5 นัดหลังสุดที่ {{ $team_name }} เป็นเจ้าบ้าน</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="h2h-home">
                                    <div class="table-loading">
                                        <div class="l-one"></div>
                                        <div class="l-two"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tm_away_content" role="tabpanel" aria-labelledby="tm_away_tab">
                <div class="table-responsive prediction-table-area">
                    <table class="table table-condensed text-white table-hist">
                        <thead>
                            <tr class="card-prediction">
                                <th>
                                    <span class="mr-10">ฟอร์มการพบกัน 5 นัดหลังสุดที่ {{ $team_name }} เป็นทีมเยือน</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="h2h-away">
                                    <div class="table-loading">
                                        <div class="l-one"></div>
                                        <div class="l-two"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>