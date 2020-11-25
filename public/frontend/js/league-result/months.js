function leagueDatas(leagueId) {
    var params = {league_id: leagueId};

    $.ajax({
        url: $('#base_url').val() + '/api/leagueDatas',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (fixtures) {
            if (fixtures.length > 0) {
                groupByYear(fixtures);
            } else {
                console.log('--- Not found fixtures in league id: ' + leagueId + ' ---');
            }
        },
        error: function(response) {
            console.log('--- Failed to load fixtures in league id: ' + leagueId + ' ---');
        }
    });
}

function groupByYear(datas) {
    var thisYear = parseInt($('#this_year').val());
    var thisYearList = [];

    if (datas.length > 0) {
        var row = null;
        var evDate = null;
        var evYear = null;

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
            evDate = row.event_date;
            evYear = parseInt(evDate.substring(0, 4));

            if (evYear == thisYear) {
                thisYearList.push(row);
            }
        }

        groupByMonth(thisYearList);
    }
}

function groupByMonth(datas) {
    if (datas.length > 0) {
        var d = new Date();
        var thisMonth = parseInt(d.getMonth() + 1);
        var monthList = [];
        var monthDatas = {};

        for (var m = 1; m <= thisMonth; m++) {
            monthList.push(m);
            monthDatas[m] = [];
        }

        var row = null;
        var evDate = null;
        var evMonth = null;

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
            evDate = row.event_date;
            evMonth = parseInt(evDate.substring(5, 7));

            if ($.inArray(evMonth, monthList) !== -1) {
                monthDatas[evMonth].push(row);
            }
        }

        renderDataInTab(monthDatas);
    }
}

function renderDataInTab(monthDatas) {
    if (Object.keys(monthDatas).length > 0) {
        for (var monthNum in monthDatas) {
            if (monthDatas.hasOwnProperty(monthNum)) {
                if (monthDatas[monthNum].length > 0) {
                    groupDate(monthDatas[monthNum], monthNum);
                } else {
                    var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                    $('#m' + monthNum + ' .program-table').html(tr);
                }
            }
        }
    }
}

function groupDate(datas, monthNum) {
    var dateGroups = {};

    if (datas.length > 0) {
        var dateList = [];
        var row = null;
        var evDate = null;
        var dateNum = 0;

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
            evDate = row.event_date;
            dateNum = parseInt(evDate.substring(8, 10));

            if ($.inArray(dateNum, dateList) === -1) {
                dateList.push(dateNum);
                dateGroups[dateNum] = [];
            }
        }

        for (var n = 0; n < dateList.length; n++) {

            for (var i = 0; i < datas.length; i++) {
                row = datas[i];
                evDate = row.event_date;
                dateNum = parseInt(evDate.substring(8, 10));

                if (dateNum == dateList[n]) {
                    dateGroups[dateNum].push(row);
                }
            }
        }
    }

    // console.log(dateGroups);
    arrangeFixtures(dateGroups, monthNum);
}

function arrangeFixtures(dateGroups, monthNum) {
    var bigHtml = genTable(dateGroups);
    $('#m' + monthNum + ' .program-table').html(bigHtml);

    if (Object.keys(dateGroups).length == 0) {
        var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
        $('#m' + monthNum + ' .program-table').html(tr);
    }
}

function genTable(datas) {
    var html = '';

    // console.log(Object.keys(datas).length);
    if (Object.keys(datas).length > 0) {
        var row = null;
        var main = null;
        var d = new Date();
        var thisDay = parseInt(d.getDate());

        for (var obj in datas) {
            if (datas.hasOwnProperty(obj)) {
                rows = datas[obj];

                main = rows[0];
                var y = parseInt(main.event_date.substr(0, 4));
                var m = parseInt(main.event_date.substr(5, 2));
                var d = parseInt(main.event_date.substr(8, 2));

                if (d < thisDay) {
                    var dFormat = dateFullFormat(d, m, y);
                    html += '<thead>';
                    html +=     '<tr class="league-name">';
                    html +=         '<th colspan="4">';
                    html +=             '<div class="d-flex alit-center">';
                    html +=                 '<span class="text-bold">' + dFormat + '</span>';
                    html +=             '</div>';
                    html +=         '</th>';
                    html +=     '</tr>';
                    html +=     '<tr class="card-prediction">';
                    html +=         '<th colspan="4">';
                    html +=             '<div class="d-flex alit-center">';
                    html +=                 '<img src="' + main.league.logo + '" width="25"><span class="ml-2 text-bold">' + main.league.name + '</span>';
                    html +=             '</div>';
                    html +=         '</th>';
                    html +=     '</tr>';
                    html += '</thead>';

                    var row = null;
                    var showTime = '';

                    for (var i = 0; i < rows.length; i++) {
                        row = rows[i];
                        showTime = checkShowTime(row);

                        html += '<tbody>';
                        html +=     '<tr>';
                        html +=         '<td class="hd-format">';
                        html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center">';
                        html +=                  showTime;
                        html +=             '</a>';
                        html +=         '</td>';
                        html +=         '<td class="text-right ht-format">';
                        html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center just-end">';
                        html +=                 '<span class="mr-2 tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35">';
                        html +=             '</a>';
                        html +=         '</td>';

                        var rs = '? : ?';
                        var rsClass = '';

                        if (row.statusShort == 'FT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                            rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                            rsClass = 'text-info';
                        }

                        html +=         '<td class="hd-format">';
                        html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center just-center '+ rsClass +'">';
                        html +=                 rs;
                        html +=             '</a>';
                        html +=         '</td>';
                        html +=         '<td class="ht-format">';
                        html +=             '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" class="team-show d-flex alit-center">';
                        html +=                 '<img src="' + row.awayTeam.logo + '" width="35"><span class="ml-2 tud-bun-tud">' + row.awayTeam.team_name + '</span>';
                        html +=             '</a>';
                        html +=         '</td>';
                        // html +=         '<td><div class="team-show d-flex alit-center just-center">' + row.status + '</div></td>';
                        html +=     '</tr>';
                        html += '</tbody>';
                    }
                }
            }
        }
    }

    return html;
}