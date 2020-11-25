function loadTopScore(leagueId) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/topscorers',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (topscorers) {
            if (topscorers.length > 0) {
                displayTopScore(topscorers);
            } else {
                $('.topscore-content').html('-- ไม่มีข้อมูล --');
            }
        },
        error: function(response) {
            $('.topscore-content').html('-- ไม่มีข้อมูล --');
        }
    });
}

function displayTopScore(listDatas) {
    // console.log(listDatas);
    // var fourStars = listDatas.slice(0, 4);

    if (listDatas.length > 0) {
        var html = '';

        for (var i = 0; i < listDatas.length; i++) {
            var data = listDatas[i];

            html = '<div class="d-flex bd-btt">';
            html +=     '<div class="row w-full">';
            html +=         '<div class="col-3 tsc-num text-success text-center text-bold">' + (i+1) + '</div>';
            html +=         '<div class="col-6">';
            html +=             '<div class="d-flex alit-center h-full">';
            html +=                 '<div class="tsc-img">';
            html +=                     '<img src="https://media.api-sports.io/football/players/' + data.player_id + '.png" class="img-fluid" alt="">';
            html +=                 '</div>';
            html +=                 '<div class="tsc-content d-flex df-col">';
            html +=                     '<h3 class="tsc-title">' + data.firstname + ' ' + data.lastname + '</h3>';
            html +=                     '<div class="tsc-lg-info c-content">';
            // html +=                         '<img src="' + mockImg + '" class="img-fluid mr-1" alt="">';
            html +=                         '<span>' + data.team_name + '</span>';
            html +=                     '</div>';
            html +=                 '</div>';
            html +=             '</div>';
            html +=         '</div>';
            html +=         '<div class="col-3 tsc-num text-muted text-center text-bold">' + data.goals.total + '</div>';
            html +=     '</div>';
            html += '</div>';
            $('.topscore-content').append(html);
        }
    }

}