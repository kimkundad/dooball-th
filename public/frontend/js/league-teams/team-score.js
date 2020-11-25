function teamScore(leagueId, teamId, year) {
    var params = {league_id: leagueId, team_id: teamId};

    $.ajax({
        url: $('#base_url').val() + '/api/statistics-alt',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (statistics) {
            if (statistics) {
                teamStatistics(statistics, year);
            } else {
                console.log('--- no data for team score in: ' + year);
                $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
                $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
                $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
                $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
                $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
                $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
            }
        },
        error: function(response) {
            // console.log(response);
            console.log('--- Failed to load team score: ' + leagueId + ' ---');
            $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
            $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
            $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
            $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
            $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
            $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
        }
    });
}

function teamScoreRepeat(leagueId, year) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/leagueDatas',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (fixtures) {
            if (fixtures.length > 0) {
                fixtureDatas = fixtures;

                var teamData = loadPlayerTransfers(fixtureDatas, 'search', teamName);
                teamScore(leagueId, teamData.team_id, year); // topic 7
            } else {
                console.log('--- no data for team score in: ' + year);
                $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
                $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
                $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
                $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
                $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
                $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
            }
        },
        error: function(response) {
            console.log('--- Failed to load team score: ' + leagueId + ' ---');
            $('#y' + year + '-score-content .num-of-match').html('-'); // แข่ง
            $('#y' + year + '-score-content .num-of-win').html('-'); // ชนะ
            $('#y' + year + '-score-content .num-of-draw').html('-'); // เสมอ
            $('#y' + year + '-score-content .num-of-lose').html('-'); // แพ้
            $('#y' + year + '-score-content .num-of-goal').html('-'); // การทำประตู
            $('#y' + year + '-score-content .num-of-defeat').html('-'); // โดนยิงประตู
        }
    });
}

function teamStatistics(row, year) {
    var matchTotal = 0;
    var winTotal = 0;
    var drawTotal = 0;
    var loseTotal = 0;
    var goalTotal = 0;
    var againstTotal = 0;

    if (row) {
        // console.log(row);
        if (row.matchs.matchsPlayed) {
            matchTotal = row.matchs.matchsPlayed.total;
        }
        winTotal = row.matchs.wins.total;
        drawTotal = row.matchs.draws.total;
        loseTotal = row.matchs.loses.total;
        goalTotal = row.goals.goalsFor.total;
        againstTotal = row.goals.goalsAgainst.total;
    }

    $('#y' + year + '-score-content .num-of-match').html(matchTotal); // แข่ง
    $('#y' + year + '-score-content .num-of-win').html(winTotal); // ชนะ
    $('#y' + year + '-score-content .num-of-draw').html(drawTotal); // เสมอ
    $('#y' + year + '-score-content .num-of-lose').html(loseTotal); // แพ้
    $('#y' + year + '-score-content .num-of-goal').html(goalTotal); // การทำประตู
    $('#y' + year + '-score-content .num-of-defeat').html(againstTotal); // โดนยิงประตู
}