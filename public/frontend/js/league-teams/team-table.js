function teamTable(leagueId) {
   var params = {league_id: leagueId};

   $.ajax({
       url: $('#base_url').val() + '/api/leagueTable',
       type: 'GET',
       data: params,
       dataType: 'json',
       cache: false,
       success: function (standings) {
            if (standings.length > 0) {
                genTeamTable(standings, teamName);
            } else {
                var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                $('.tbd-league-table').html(tr);
            }
       },
       error: function(response) {
            var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
            $('.tbd-league-table').html(tr);
       }
   });
}

function genTeamTable(datas, teamName) {
    var tr = '';

    if (datas.length > 0) {
        if (datas[0]) {
            var mp = 0, w = 0, d = 0, l = 0, g = 0;
    
            var point = 0;
            var rows = datas[0];
            var homeRows = datas[0];
            var awayRows = datas[0];
            var row = null;

            var topFiveTeam = fiveKeys(rows, teamName);

            for (var j = 0; j < topFiveTeam.length; j++) {
                row = topFiveTeam[j];

                mp = row.all.matchsPlayed;
                w = row.all.win;
                d = row.all.draw;
                l = row.all.lose;
                g = row.all.goalsFor;

                point = row.points;

                tr += '<tr class="' + row.rowClass + '">';
                tr +=   '<td>' + (row.no + 1) + '.</td>';
                tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
                tr +=   '<td>' + mp + '</td>';
                // tr +=   '<td>' + w + '</td>';
                // tr +=   '<td>' + d + '</td>';
                // tr +=   '<td>' + l + '</td>';
                tr +=   '<td>' + g + '</td>';
                tr +=   '<td>' + point + '</td>';
                tr += '</tr>';
            }

            var newHome = reOrderTeamSide(homeRows, 'home');
            newHome.sort(compareWin);
            var newAway = reOrderTeamSide(awayRows, 'away');
            newAway.sort(compareWin);

            renderSide(newHome, 'home', teamName);
            renderSide(newAway, 'away', teamName);
        }
    }

    $('.tbd-league-table').html(tr);
}

function renderSide(rows, side, teamName) {
    if (rows.length > 0) {
        var tr = '';
        var mp = 0, w = 0, d = 0, l = 0, g = 0;
        var row = null;

        var topFiveTeam = fiveKeys(rows, teamName);

        for (var j = 0; j < topFiveTeam.length; j++) {
            row = topFiveTeam[j];
    
            mp = row[side].matchsPlayed;
            w = row[side].win;
            d = row[side].draw;
            l = row[side].lose;
            g = row[side].goalsFor;
    
            tr += '<tr class="' + row.rowClass + '">';
            tr +=   '<td>' + (row.no + 1) + '.</td>';
            tr +=   '<td><img src="' + row.logo + '" width="30"><span class="ml-2">' + row.teamName + '</span></td>';
            tr +=   '<td>' + mp + '</td>';
            // tr +=   '<td>' + w + '</td>';
            // tr +=   '<td>' + d + '</td>';
            // tr +=   '<td>' + l + '</td>';
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

function fiveKeys(datas, teamName) {
    var nameKey = 0;
    var realTeamName = teamName.replace(/-/g, ' ');

    if (datas.length > 0) {
        for (var i = 0; i < datas.length; i++) {
            if (datas[i].teamName == realTeamName) {
                nameKey = i;
                break;
            }
        }
    }

    var fiveList = [];

    for (var n = (nameKey - 2); n <= (nameKey + 2); n++) {
        if (n >= 0) {
            if (datas[n]) {
                datas[n].rowClass = (datas[n].teamName == realTeamName) ? 'league-name' : '';
                datas[n].no = n;
                fiveList.push(datas[n]);
            }
        }
    }

    return fiveList;
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