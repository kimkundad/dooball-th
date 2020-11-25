var imageShoe = $('#image_shoe').val();
var imageYellowCard = $('#image_yellow_card').val();
var imageRedCard = $('#image_red_card').val();
var imageBox = $('#image_box').val();
// console.log(thisHost);

function loadPlayerStats(leagueId) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/topscorers',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (topscorers) {
            if (topscorers) {
                renderPlayerStat(topscorers);
            } else {
                console.log('--- no player stats in league id: ' + leagueId + ' ---');
                $('.card-body.player-stats').html('<h5 class="text-muted mt-2 text-center">--- ไม่มีข้อมูลสถิตินักเตะ ---</h5>');
                $('.card-body.player-stats').css('min-height', '45px');
            }
        },
        error: function(response) {
            console.log('--- Failed to load player stats in league id: ' + leagueId + ' ---');
            $('.card-body.player-stats').html('<h5 class="text-muted mt-2 text-center">--- ไม่มีข้อมูลสถิตินักเตะ ---</h5>');
            $('.card-body.player-stats').css('min-height', '45px');
        }
    });
}

function renderPlayerStat(datas) {
    var html = '';

    if (datas.length > 0) {
        var fourStars = datas;

        if (datas.length > 5) {
            fourStars = datas.slice(0, 5);
        }

        var row = null;
        for (var i = 0; i < fourStars.length; i++) {
            row = fourStars[i];
            name = row.firstname + ' ' + row.lastname;

            html += '<div class="pls-ele d-flex alit-center">';
            html +=     '<div class="pm-image pt-2 pb-2 mr-4">';
            html +=         '<img src="https://media.api-sports.io/football/players/' + row.player_id + '.png" class="img-fluid" alt="">';
            html +=     '</div>';
            html +=     '<div class="pm-content">';
            html +=         '<h4 class="text-white no-mbt">' + name + '</h4>';
            html +=         '<p class="text-white mb-2">' + row.team_name + '</p>';
            html +=         '<div class="d-flex alit-center just-between">';
            html +=             '<div class="d-flex df-col alit-center just-center db-tooltip">';
            html +=                 '<span class="tooltip-text">ประตู</span>';
            html +=                 '<span><i class="fa fa-futbol text-white"></i></span>';
            html +=                 '<span class="text-bigger text-danger text-bold">' + ((row.goals.total) ? row.goals.total : 0) + '</span>';
            html +=             '</div>';
            html +=             '<div class="d-flex df-col alit-center just-center db-tooltip">';
            html +=                 '<span class="tooltip-text">แอดซิส</span>';
            html +=                 '<span><img src="' + imageShoe + '" width="30"></span>';
            html +=                 '<span class="text-bigger text-danger text-bold">' + ((row.goals.assists) ? row.goals.assists : 0) + '</span>';
            html +=             '</div>';
            html +=             '<div class="d-flex df-col alit-center just-center db-tooltip">';
            html +=                 '<span class="tooltip-text">โอกาส</span>';
            html +=                 '<span><i class="fa fa-walking text-white"></i></span>';
            html +=                 '<span class="text-bigger text-danger text-bold">' + ((row.shots.total) ? row.shots.total : 0) + '</span>';
            html +=             '</div>';
            html +=             '<div class="d-flex df-col alit-center just-center db-tooltip">';
            html +=                 '<span class="tooltip-text">เข้ากรอบ</span>';
            html +=                 '<span><img src="' + imageBox + '"></span>';
            html +=                 '<span class="text-bigger text-danger text-bold">' + ((row.shots.on) ? row.shots.on : 0) + '</span>';
            html +=             '</div>';
            html +=             '<div class="d-flex df-col alit-center just-center db-tooltip">';
            html +=                 '<span class="tooltip-text">ใบเหลือง</span>';
            html +=                 '<span><img src="' + imageYellowCard + '" width="25"></span>';
            html +=                 '<span class="text-bigger text-danger text-bold">' + ((row.cards.yellow) ? row.cards.yellow : 0) + '</span>';
            html +=             '</div>';
            html +=             '<div class="d-flex df-col alit-center just-center db-tooltip">';
            html +=                 '<span class="tooltip-text">ใบแดง</span>';
            html +=                 '<span><img src="' + imageRedCard + '" width="25"></span>';
            html +=                 '<span class="text-bigger text-danger text-bold">' + ((row.cards.red) ? row.cards.red : 0) + '</span>';
            html +=             '</div>';
            html +=         '</div>';
            html +=     '</div>';
            html +=     '<div class="pm-image tsc-num ml-3 text-muted text-center text-bold">';
            html +=         (i+1);
            html +=     '</div>';
            html += '</div>';
        }
    }

    $('.player-stats').html(html);
}