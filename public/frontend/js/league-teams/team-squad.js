function teamSquad(teamId, year) {
    var params = {team_id: teamId, year: (parseInt(year) - 1)};
    
    $.ajax({
        url: $('#base_url').val() + '/api/playersSquad',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (players) {
            if (players.length > 0) {
                teamPlayers(players, year);
            } else {
                console.log('--- no data for team squad in: ' + (parseInt(year) - 1));
                noSquadFound(year)
            }
        },
        error: function(response) {
            console.log('--- Failed to load team squad: ' + leagueId + ' ---');
            var li = '<li class="pt-2 text-center text-muted">-- ไม่มีข้อมูล --</li>';
            $('.team-attacker').html(li);
            $('.team-midfielder').html(li);
            $('.team-defender').html(li);
            $('.team-goalkeeper').html(li);
        }
    });
}

function coachInfo(teamId, year) {
    var params = {team_id: teamId};

    $.ajax({
        url: $('#base_url').val() + '/api/coachInfo',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (coachs) {
            if (coachs) {
                displayCoach(coachs, year);
            } else {
                console.log('--- no data for team coach in: ' + (parseInt(year) - 1));
            }
        },
        error: function(response) {
            console.log('--- no data for team coach in: ' + (parseInt(year) - 1));
        }
    });
}

function teamSquadRepeat(leagueId, year) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/leagueDatas',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (fixtures) {
            console.log('--- repeat done ---');
            if (fixtures.length > 0) {
                fixtureDatas = fixtures;

                var teamData = loadPlayerTransfers(fixtureDatas, 'search', teamName);
                teamSquad(teamData.team_id, year); // topic 10
                // coachInfo(teamData.team_id, year);
            } else {
                console.log('--- No data for league: ' + leagueId + ', year ' + (parseInt(year) - 1) + '---');
            }
        },
        error: function(response) {
            console.log('--- Failed to load league: ' + leagueId + ', year ' + (parseInt(year) - 1) + '---');
            noSquadFound(year);
        }
    });
}

function noSquadFound(year) {
    var li = '<li class="pt-2 text-center text-muted">-- ไม่มีข้อมูล --</li>';
    console.log('--- | ' + year + ' | ---');
    $('#m' + year + '-squad-content .team-attacker').html(li);
    $('#m' + year + '-squad-content .team-midfielder').html(li);
    $('#m' + year + '-squad-content .team-defender').html(li);
    $('#m' + year + '-squad-content .team-goalkeeper').html(li);
}

function teamPlayers(players, year) {
    var attackerList = [];
    var midfielderList = [];
    var defenderList = [];
    var goalkeeperList = [];

    if (players.length > 0) {
        var row = null;

        for (var i = 0; i < players.length; i++) {
            row = players[i];
            // console.log(row);

            if (row.position == 'Attacker') {
                attackerList.push(row);
            }

            if (row.position == 'Midfielder') {
                midfielderList.push(row);
            }

            if (row.position == 'Defender') {
                defenderList.push(row);
            }

            if (row.position == 'Goalkeeper') {
                goalkeeperList.push(row);
            }
        }
    }

    renderPlayer(attackerList, year, 'team-attacker');
    renderPlayer(midfielderList, year, 'team-midfielder');
    renderPlayer(defenderList, year, 'team-defender');
    renderPlayer(goalkeeperList, year, 'team-goalkeeper');
}

function renderPlayer(datas, year, positionClass) {
    var li = '';

    if (datas.length > 0) {
        var row = null;
        var name = '';

        li += '<li class="d-flex alit-center mb-1 ele-right-th">';
        li +=   '<div class="squad-img">รูป</div>';
        li +=   '<div class="squad-name text-white">ชื่อ</div>';
        li +=   '<div class="squad-position text-white">ตำแหน่ง</div>';
        li +=   '<div class="squad-nationality text-white">สัญชาติ</div>';
        li +=   '<div class="squad-age text-white">อายุ</div>';
        li += '</li>';

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
            name = row.firstname + '-' + row.lastname;
            // name = name.split(' ').join('-');
            name = name.replace(/ /g, '-');

            li += '<li class="d-flex alit-center mt-1 mb-1">';
            li +=   '<a href="' + thisHost + '/players/' + name + '" class="d-flex alit-center a-squad" target="_BLANK">';
            li +=       '<span class="squad-img"><img src="https://media.api-sports.io/football/players/' + row.player_id + '.png" class="img-round" width="35" /></span>';
            li +=       '<span class="squad-name text-white">' + row.player_name + '</span>';
            li +=       '<span class="squad-position text-white">' + row.position + '</span>';
            li +=       '<span class="squad-nationality text-white">' + row.nationality + '</span>';
            li +=       '<span class="squad-age text-white">' + row.age + '</span>';
            li +=   '</a>';
            li += '</li>';
        }
    } else {
        li += '<li class="pt-2 text-center text-muted">-- ไม่มีข้อมูล --</li>';
    }

    $('#m' + year + '-squad-content .' + positionClass).html(li);
}

function displayCoach(datas, year) {
    var li = '';

    if (datas.length > 0) {
        var row = null;

        li += '<li class="d-flex alit-center mb-1 ele-right-th">';
        li +=   '<div class="squad-name text-white pl-2">ชื่อ</div>';
        li +=   '<div class="squad-nationality text-white">สัญชาติ</div>';
        li +=   '<div class="squad-age text-white">อายุ</div>';
        li += '</li>';

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];

            li += '<li class="d-flex alit-center mb-1">';
            li +=   '<div class="squad-name text-white pl-2">' + row.firstname + ' ' + row.lastname + '</div>';
            li +=   '<div class="squad-nationality text-white">' + row.nationality + '</div>';
            li +=   '<div class="squad-age text-white">' + row.age + '</div>';
            li += '</li>';
        }
    } else {
        li += '<li class="pt-2 text-center text-muted">-- ไม่มีข้อมูล --</li>';
    }

    // $('#m' + year + '-squad-content .team-coach').html(li);
}