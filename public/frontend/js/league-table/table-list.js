function loadLeagueTable(leagueId, year) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/leagueTable',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (standings) {
            if (standings.length > 0) {
                leagueTableYearRender(standings, year);
             } else {
                $('#content_' + year).html('<span class="text-center text-muted">-- ไม่มีข้อมูล --</span>');
            }
        },
        error: function(response) {
            $('#content_' + year).html('<span class="text-center text-muted">-- ไม่มีข้อมูล --</span>');
        }
    });
}

function leagueTableYearRender(datas, year) {
    var tr = '';
    var trH = '';
    var trA = '';

    if (datas.length > 0) {
        if (datas[0]) {

            var mp = 0, w = 0, d = 0, l = 0, g = 0;
    
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

                point = row.points;

                tr += '<tr>';
                tr +=   '<td>' + (j+1) + '.</td>';
                tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
                tr +=   '<td>' + mp + '</td>';
                tr +=   '<td>' + w + '</td>';
                tr +=   '<td>' + d + '</td>';
                tr +=   '<td>' + l + '</td>';
                tr +=   '<td>' + g + '</td>';
                tr +=   '<td>' + point + '</td>';
                tr += '</tr>';
            }

            var newHome = reOrderTeamSide(homeRows, 'home');
            newHome.sort(compareWin);
            var newAway = reOrderTeamSide(awayRows, 'away');
            newAway.sort(compareWin);

            renderSide(newHome, 'home', year);
            renderSide(newAway, 'away', year);
        }
    }

    $('#content_' + year + ' .tbd-league-table-' + year).html(tr);
}

function renderSide(rows, side, year) {
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

        // console.log(year, side, rows);

        $('#content_' + year + ' .tbd-league-table-' + year + '-' + side).html(tr);
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