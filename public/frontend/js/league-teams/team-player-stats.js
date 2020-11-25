function loadTeamPlayerStats(teamId, year) {
    var params = {team_id: teamId, year: (parseInt(year) - 1)};

    $.ajax({
        url: $('#base_url').val() + '/api/playersTeam',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (players) {
            if (players.length > 0) {
                var datas = players;
                var fiveStars = datas;

                if (datas.length > 5) {
                    fiveStars = datas.slice(0, 5);
                }

                groupSevenSet(fiveStars, year);
            } else {
                console.log('--- no player stats team id: ' + teamId, (parseInt(year) - 1));
                $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><h5 class="text-center text-muted">--- ไม่มีข้อมูล ---</h5></td>');
            }
        },
        error: function(response) {
            console.log('--- Failed to load team data: ' + leagueId + ' ---');
            $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><h5 class="text-center text-muted">--- ไม่มีข้อมูล ---</h5></td>');
        }
    });
}

function repeatLoadTeamPlayerStats(leagueId, year) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/leagueDatas',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (fixtures) {
            if (fixtures.length > 0) {
                var teamData = loadPlayerTransfers(fixtures, 'search', teamName);
                loadTeamPlayerStats(teamData.team_id, year); // topic 8
            } else {
                // var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                // $('.tb-transfer').html(tr);
                console.log('--- no data for team in league: ' + leagueId);
                $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><span class="text-muted">--- ไม่มีข้อมูล ---</span></td>');
            }
        },
        error: function(response) {
            console.log('--- Failed to load team in league: ' + leagueId + ' ---');
        }
    });
}

// --- start repeat topic 8 --- //
function loadPlayerStatsEachYear(leagueId, year) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/topscorers',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (topscorers) {
            if (topscorers.length > 0) {
                groupSevenSet(topscorers, year);
            } else {
                console.log('--- no team player stats for league id: ' + leagueId, year);
                $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><span class="text-muted">--- ไม่มีข้อมูล ---</span></td>');
            }
        },
        error: function(response) {
            console.log('--- no team player stats for league id: ' + leagueId, year);
            $('#star_content_' + year + ' tbody tr').html('<td class="text-muted text-center"><span class="text-muted">--- ไม่มีข้อมูล ---</span></td>');
        }
    });
}
// --- end repeat topic 8 --- //