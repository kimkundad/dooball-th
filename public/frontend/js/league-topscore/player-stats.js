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
            if (topscorers.length > 0) {
                groupSevenSet(topscorers);
                // renderPlayerStat(topscorers);
            } else {
                console.log('--- no data in league id: ' + leagueId);
            }
        },
        error: function(response) {
            console.log('--- failed to load data in league id: ' + leagueId);
        }
    });
}

function groupSevenSet(datas) {
    var topScore = [];
    var topAssist = [];
    var topChange = [];
    var topFrame = [];
    var topYellow = [];
    var topRed = [];
    var topTime = [];

    var row = null;

    if (datas.length > 0) {
        for (var i = 0; i < datas.length; i++) {
            row = datas[i];

            datas[i]['num_goal'] = (row.goals.total) ? row.goals.total : 0;
            datas[i]['num_assist'] = (row.goals.assists) ? row.goals.assists : 0;
            datas[i]['num_chance'] = (row.shots.total) ? row.shots.total : 0;
            datas[i]['num_frame'] = (row.shots.on) ? row.shots.on : 0;
            datas[i]['num_yellow'] = (row.cards.yellow) ? row.cards.yellow : 0;
            datas[i]['num_red'] = (row.cards.red) ? row.cards.red : 0;
            datas[i]['num_time'] = (row.games.minutes_played) ? row.games.minutes_played : 0;

            topScore.push(datas[i]);
            topAssist.push(datas[i]);
            topChange.push(datas[i]);
            topFrame.push(datas[i]);
            topYellow.push(datas[i]);
            topRed.push(datas[i]);
            topTime.push(datas[i]);
        }

        topScore = topScore.sort(compareGoal);
        topAssist = topAssist.sort(compareAssist);
        topChange = topChange.sort(compareChance);
        topFrame = topFrame.sort(compareFrame);
        topYellow = topYellow.sort(compareYellow);
        topRed = topRed.sort(compareRed);
        topTime = topTime.sort(compareTime);
    }

    renderPlayerStat('star_content', topScore);
    renderPlayerStat('assist_content', topAssist);
    renderPlayerStat('chance_content', topChange);
    renderPlayerStat('frame_content', topFrame);
    renderPlayerStat('yellow_content', topYellow);
    renderPlayerStat('red_content', topRed);
    renderPlayerStat('time_content', topTime);
}

function renderPlayerStat(tab_id, datas) {
    var html = '';

    if (datas.length > 0) {
        var row = null;

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
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
    } else {
        html + '-- ไม่มีข้อมูล --';
    }

    $('#' + tab_id).html(html);
}

function compareGoal(a, b) {
    if (a.num_goal < b.num_goal) {
        return 1;
    }
    if (a.num_goal > b.num_goal) {
        return -1;
    }

    return 0;
}

function compareAssist(a, b) {
    if (a.num_assist < b.num_assist) {
        return 1;
    }
    if (a.num_assist > b.num_assist) {
        return -1;
    }

    return 0;
}

function compareChance(a, b) {
    if (a.num_chance < b.num_chance) {
        return 1;
    }
    if (a.num_chance > b.num_chance) {
        return -1;
    }

    return 0;
}

function compareFrame(a, b) {
    if (a.num_frame < b.num_frame) {
        return 1;
    }
    if (a.num_frame > b.num_frame) {
        return -1;
    }

    return 0;
}

function compareYellow(a, b) {
    if (a.num_yellow < b.num_yellow) {
        return 1;
    }
    if (a.num_yellow > b.num_yellow) {
        return -1;
    }

    return 0;
}

function compareRed(a, b) {
    if (a.num_red < b.num_red) {
        return 1;
    }
    if (a.num_red > b.num_red) {
        return -1;
    }

    return 0;
}

function compareTime(a, b) {
    if (a.num_time < b.num_time) {
        return 1;
    }
    if (a.num_time > b.num_time) {
        return -1;
    }

    return 0;
}