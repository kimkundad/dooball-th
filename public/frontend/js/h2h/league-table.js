function loadLeagueTable(leagueId, homeTeamName, awayTeamName) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/leagueTable',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (standings) {
            if (standings) {
                leagueTableRender(standings, homeTeamName, awayTeamName);
            } else {
                var tr = '<tr><td colspan="8" class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                $('.tbd-league-table').html(tr);
                $('.tbd-league-table-home').html(tr);
                $('.tbd-league-table-away').html(tr);
            }
        },
        error: function(response) {
            var tr = '<tr><td colspan="8" class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
            $('.tbd-league-table').html(tr);
            $('.tbd-league-table-home').html(tr);
            $('.tbd-league-table-away').html(tr);
        }
    });
}

function leagueTableRender(datas, homeTeamName, awayTeamName) {
    // console.log(datas);
    var tr = '';
    var trH = '';
    var trA = '';

    if (datas.length > 0) {
        if (datas[0]) {
            // var groupName = datas[0][0].group;

            // tr += '<tr class="league-name">';
            // tr +=   '<td colspan="8">' + groupName + '</td>';
            // tr += '</tr>';

            var activeTeam = '';
            var mp = 0, w = 0, d = 0, l = 0, g = 0;
            // var mpH = 0, wH = 0, dH = 0, lH = 0, gH = 0;
            // var mpA = 0, wA = 0, dA = 0, lA = 0, gA = 0;
    
            var point = 0;
            var rows = datas[0];
            var homeRows = datas[0];
            var awayRows = datas[0];

            for (var j = 0; j < rows.length; j++) {
                var row = rows[j];

                mp = row.all.matchsPlayed;
                w = row.all.win;
                d = row.all.draw;
                l = row.all.lose;
                g = row.all.goalsFor;
                
                // mpH = row.home.matchsPlayed;
                // wH = row.home.win;
                // dH = row.home.draw;
                // lH = row.home.lose;
                // gH = row.home.goalsFor;
                
                // mpA = row.away.matchsPlayed;
                // wA = row.away.win;
                // dA = row.away.draw;
                // lA = row.away.lose;
                // gA = row.away.goalsFor;

                point = row.points;

                activeTeam = (row.teamName == homeTeamName || row.teamName == awayTeamName) ? ' class="league-name"' : '';

                tr += '<tr ' + activeTeam + '>';
                tr +=   '<td>' + (j+1) + '.</td>';
                tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
                tr +=   '<td>' + mp + '</td>';
                tr +=   '<td>' + w + '</td>';
                tr +=   '<td>' + d + '</td>';
                tr +=   '<td>' + l + '</td>';
                tr +=   '<td>' + g + '</td>';
                tr +=   '<td>' + point + '</td>';
                tr += '</tr>';
                
                // trH += '<tr>';
                // trH +=   '<td>' + (j+1) + '.</td>';
                // trH +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
                // trH +=   '<td>' + mpH + '</td>';
                // trH +=   '<td>' + wH + '</td>';
                // trH +=   '<td>' + dH + '</td>';
                // trH +=   '<td>' + lH + '</td>';
                // trH +=   '<td>' + gH + '</td>';
                // trH +=   '<td>' + point + '</td>';
                // trH += '</tr>';

                // trA += '<tr>';
                // trA +=   '<td>' + (j+1) + '.</td>';
                // trA +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
                // trA +=   '<td>' + mpA + '</td>';
                // trA +=   '<td>' + wA + '</td>';
                // trA +=   '<td>' + dA + '</td>';
                // trA +=   '<td>' + lA + '</td>';
                // trA +=   '<td>' + gA + '</td>';
                // trA +=   '<td>' + point + '</td>';
                // trA += '</tr>';
            }

            var newHome = reOrderTeamSide(homeRows, 'home');
            newHome.sort(compareWin);
            var newAway = reOrderTeamSide(awayRows, 'away');
            newAway.sort(compareWin);

            renderSide(newHome, 'home', homeTeamName, awayTeamName);
            renderSide(newAway, 'away', homeTeamName, awayTeamName);
        }
    }

    $('.tbd-league-table').html(tr);
    // $('.tbd-league-table-home').html(trH);
    // $('.tbd-league-table-away').html(trA);
}

function renderSide(rows, side, homeTeamName, awayTeamName) {
    if (rows.length > 0) {
        var tr = '';
        var mp = 0, w = 0, d = 0, l = 0, g = 0;
        var activeTeam = '';

        for (var j = 0; j < rows.length; j++) {
            var row = rows[j];
    
            mp = row[side].matchsPlayed;
            w = row[side].win;
            d = row[side].draw;
            l = row[side].lose;
            g = row[side].goalsFor;
            
            activeTeam = (row.teamName == homeTeamName || row.teamName == awayTeamName) ? ' class="league-name"' : '';
    
            tr += '<tr ' + activeTeam + '>';
            tr +=   '<td>' + (j+1) + '.</td>';
            tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
            tr +=   '<td>' + mp + '</td>';
            tr +=   '<td>' + w + '</td>';
            tr +=   '<td>' + d + '</td>';
            tr +=   '<td>' + l + '</td>';
            tr +=   '<td>' + g + '</td>';
            tr +=   '<td>' + row.points + '</td>';
            tr += '</tr>';
        }

        $('.tbd-league-table-' + side).html(tr);
    }
}

function reOrderTeamSide(datas, side) {
    var newList = [];

    if (datas.length > 0) {
        for (var i = 0; i < datas.length; i++) {
            datas[i]['winRate'] = datas[i][side].win;
            newList.push(datas[i]);
        }
    }

    return newList;
}

function compareWin(a, b) {
    if ( a.winRate < b.winRate ){
        return 1;
    }
    if ( a.winRate > b.winRate ){
        return -1;
    }

    return 0;
}