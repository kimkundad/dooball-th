
function loadStatistics() {
    var params = {fixture_id: $('#fixture_id').val()};

    $.ajax({
        url: $('#base_url').val() + '/api/statistics',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (statistics) {
            if (statistics) {
                displayStatistics(statistics);
            } else {
                $('.statistics-content').html('-- ไม่มีข้อมูล --');
            }
        },
        error: function(response) {
            // console.log(response);
            $('.statistics-content').html('-- ไม่มีข้อมูล --');
        }
    });
}

function displayStatistics(objDatas) {
    var html = '';
    var home = 0;
    var away = 0;
    var max = 0;
    var percentHome = 0.00;
    var percentAway = 0.00;

    if (objDatas) {
        for (let key in objDatas) {
            // console.log(key, objDatas[key]);

            if (objDatas[key].home && objDatas[key].away) {
                home = parseInt(objDatas[key].home);
                away = parseInt(objDatas[key].away);

                max = home + away;

                if (max != 0) {
                    percentHome = (home * 100) / max;
                    percentAway = (away * 100) / max;
                }

                html += '<div class="ele my-4">';
                html +=     '<div class="home-score">' + objDatas[key].home + '</div>';
                html +=     '<h4 class="text-center c-theme">' + key + '</h4>';
                html +=     '<div class="progress-bg d-flex">';
                html +=         '<div class="home-side d-flex just-end">';
                html +=             '<div class="fill" style="width: ' + percentHome + '%;"></div>';
                html +=         '</div>';
                html +=         '<div class="away-side d-flex">';
                html +=             '<div class="fill" style="width: ' + percentAway + '%;"></div>';
                html +=         '</div>';
                html +=     '</div>';
                html +=     '<div class="away-score">' + objDatas[key].away + '</div>';
                html += '</div>';
            }
        }
    }

    $('.statistics-content').html(html);
}