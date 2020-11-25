function genResultProgram(fixtures) {
    var resultList = [];
    var programList = [];
    // var d = new Date();
    // var date = d.getFullYear() + '-' + padd(d.getMonth() + 1) + '-' + padd(d.getDate());
    // var cTimeStamp = new Date(date).getTime()/1000;
    var currentTimeStamp = Math.floor(Date.now()/1000);

    if (fixtures.length > 0) {
        var row = null;

        for (var i = 0; i < fixtures.length; i++) {
            row = fixtures[i];
            // console.log(row);
            if (row.event_timestamp < currentTimeStamp) {
                // console.log(row.event_timestamp, ' < ', currentTimeStamp, row.event_date);
                resultList.push(row);
            } else {
                // console.log(row.event_timestamp, ' > ', currentTimeStamp, row.event_date);
                programList.push(row);
            }
        }
    }

    var twoResults = [];
    var threePrograms = [];

    if (resultList.length > 0) {
        resultList.sort(compareResult);

        if (resultList.length > 2) {
            twoResults = resultList.slice(0, 2);
            twoResults.sort(compareProgram);
        } else {
            twoResults = resultList;
        }
    }

    if (programList.length > 0) {
        programList.sort(compareProgram);

        if (programList.length > 3) {
            threePrograms = programList.slice(0, 3);
        } else {
            threePrograms = programList;
        }
    }

    // console.log(twoResults.length);
    // console.log(threePrograms.length);
    $('.team-timeline-box .graph-loading').remove();

    if ((twoResults.length + threePrograms.length) > 0) {
        displayTimeline(twoResults, 'result');
        displayTimeline(threePrograms, 'program');
    } else {
        $('.team-timeline-box').html('-- ไม่มีข้อมูล --');
    }
}

function displayTimeline(twoResults, className) {
    var html = '';

    if (twoResults.length > 0) {
        var row = null;
        var fullDate = '';
        var cName = '';

        for (var i = 0; i < twoResults.length; i++) {
            row = twoResults[i];
            // console.log(row);

            var time = showDateTimeFromTimeStamp(row.event_timestamp, 1);
            var showTime = row.score.fulltime;

            if (row.statusShort == 'TBD' || row.statusShort == 'NS' || row.statusShort == 'FT') {
                showTime = '<span class="text-danger">' + time + '</span>';
            } else if (row.statusShort == '1H' || row.statusShort == '2H') {
                showTime = '<a href="javascript:void(0)" class="btn btn-live active mr-10">LIVE</a><span class="text-danger">' + row.statusShort + ' ' + row.elapsed + '<span class="live">\'</span></span>';
            } else {
                showTime = '<span>' + row.statusShort + '</span>';
            }

            // fullDate = row.event_date.substr(0, 4), row.event_date.substr(5, 2), row.event_date.substr(8, 2);
            // console.log(fullDate);

            fullDate = dateFullFormat(row.event_date.substr(8, 2), row.event_date.substr(5, 2), parseInt(row.event_date.substr(0, 4)));
            
            cName = (className == 'program' && i == 0) ? className : '';

            html += '<div class="ele-right-th pl-2 ' + cName + '">' + fullDate + '</div>';
            html += '<div class="pl-2 text-bold">' + row.league.name + ' ' +  row.league.country + '</div>';

            html += '<div class="d-flex df-col">';
            html += '<div class="right-hist d-flex p-2">';
            html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
            html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
            html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
            html +=         '<span class="t-score wide text-bold">' + showTime + '</span>';
            html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
            html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
            html +=     '</a>';
            html += '</div>';
            html += '</div>';
        }
    }

    $('.team-timeline-box').append(html);
}

function compareResult(a, b) {
    if ( a.event_timestamp < b.event_timestamp ){
        return 1;
    }
    if ( a.event_timestamp > b.event_timestamp ){
        return -1;
    }

    return 0;
}

function compareProgram(a, b) {
    if ( a.event_timestamp > b.event_timestamp ){
        return 1;
    }
    if ( a.event_timestamp < b.event_timestamp ){
        return -1;
    }

    return 0;
}