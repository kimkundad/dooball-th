var imageShoe = $('#image_shoe').val();
var imageYellowCard = $('#image_yellow_card').val();
var imageRedCard = $('#image_red_card').val();
var imageBox = $('#image_box').val();
// console.log(thisHost);

function displayMatchInfo(datas) {
    var checkMatch = 0;

    if (datas.length > 0) {
        var row = null;
        var foundObj = false;
        var timeStampNow = new Date().getTime();

        var date = new Date();
        var timeStampTenToday = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 10, 0, 0, 0).getTime();

        var yesterday = new Date(date);
        yesterday.setDate(date.getDate() - 1);
        var timeStampYesterday = new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate(), 10, 0, 0, 0).getTime();

        var tomorrow = new Date(date);
        tomorrow.setDate(date.getDate() + 1);
        var timeStampTenTomorrow = new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate(), 10, 0, 0, 0).getTime();

        // var nowDate = showDateTimeFromTimeStamp(timeStampNow/1000, 1);
        // var yesterdayTenDate = showDateTimeFromTimeStamp(timeStampYesterday/1000, 1);
        // var todayTenDate = showDateTimeFromTimeStamp(timeStampTenToday/1000, 1);
        // var tomorrowTenDate = showDateTimeFromTimeStamp(timeStampTenTomorrow/1000, 1);
        // console.log(nowDate, yesterdayTenDate, todayTenDate, tomorrowTenDate);

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
            foundObj = false;

            // event_timestamp from API is already /1000

            if (timeStampNow <= timeStampTenToday) {
                // compare with ten today
                if ((row.event_timestamp >= (timeStampYesterday/1000)) && row.event_timestamp <= (timeStampTenToday/1000)) {
                    foundObj = true;
                }
            } else {
                // compare with ten tomorrow
                if ((row.event_timestamp >= (timeStampTenToday/1000)) && row.event_timestamp <= (timeStampTenTomorrow/1000)) {
                    foundObj = true;
                }
            }

            if (foundObj) {
                checkMatch++;
                renderFixtureToday(row);
            }
        }
    }

    if (checkMatch == 0) {
        $('.livescore-today').remove();
        $('.hide-if-load-complete').html('<div class="d-flex text-muted">-- ไม่มีรายการแข่ง ' + thaiName + ' ในวันนี้ --</div>');
    } else {
        $('.hide-if-load-complete').remove();
    }
}

function renderFixtureToday(row) {
    // console.log(row);

    if (row) {
        var leagueAlias = row.league.name + ' (' + row.league.country + ')';

        $('.tm-time-box').html('<div>' + showDateYearFromTimeStamp(row.event_timestamp) + '</div><div>' + leagueAlias + '</div>');

        var hHtml = '<div class="d-flex df-col alit-center just-center">';
            hHtml +=    '<img src="' + row.homeTeam.logo + '" width="35" />';
            hHtml +=    '<div class="d-flex alit-center just-center">' + row.homeTeam.team_name + '</div>';
            hHtml += '</div>';
        $('.tm-team-box.home').html(hHtml);

        var hHtml = '<div class="d-flex df-col alit-center just-center">';
            hHtml +=    '<img src="' + row.awayTeam.logo + '" width="35" />';
            hHtml +=    '<div class="d-flex alit-center just-center">' + row.awayTeam.team_name + '</div>';
            hHtml += '</div>';
        $('.tm-team-box.away').html(hHtml);

        
        var time = showDateTimeFromTimeStamp(row.event_timestamp);
        var timeText = '';

        if (row.statusShort == '1H' || row.statusShort == '2H') {
            timeText = '<a href="javascript:void(0)" class="btn btn-live active mr-10">LIVE</a><span class="text-danger">' + row.statusShort + ' ' + row.elapsed + '<span class="live">\'</span></span>';
        } else if (row.statusShort == 'FT') {
            timeText = '<span>' + row.score.fulltime + '</span>';
        } else {
            timeText = '<span class="text-white text-so-big">' + time + '</span>';
        }

        var scoreHtml = '<div class="d-flex alit-center just-center text-white text-bold">';
            scoreHtml +=    timeText;
            scoreHtml += '</div>';
        $('.tm-score-box').html(scoreHtml);

        var a = '<a href="javascript:void(0)" class="active-gd link-nodec btn-block flex-center height-full text-white"><i class="fa fa-play-circle"></i></a>';
        $('.tm-link-box').html(a);
    }
}

function teamScoreResult(datas) {
    if (datas.length > 0) {
        var totalList = [];
        var homeList = [];
        var awayList = [];
        var row = null;
        var cTimeStamp = currentTimeStamp();

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];
            if ((row.homeTeam.team_name == teamName || row.awayTeam.team_name == teamName) && (row.event_timestamp < cTimeStamp) && (row.statusShort == 'FT')) {
                totalList.push(row);

                if (row.homeTeam.team_name == teamName) {
                    homeList.push(row);
                }

                if (row.awayTeam.team_name == teamName) {
                    awayList.push(row);
                }
            }
        }

        var topTotalList = totalList.sort(compareEventTimeStamp);
        var topHomeList = homeList.sort(compareEventTimeStamp);
        var topAwayList = awayList.sort(compareEventTimeStamp);

        var topTotal = [];
        var topHome = [];
        var topAway = [];

        if (topTotalList.length > 5) {
            topTotal = topTotalList.slice(0, 5);
        }

        if (topHomeList.length > 5) {
            topHome = topHomeList.slice(0, 5);
        }

        if (topAwayList.length > 5) {
            topAway = topAwayList.slice(0, 5);
        }

        renderResult(topTotal, 'total');
        renderResult(topHome, 'home');
        renderResult(topAway, 'away');
    }
}

function renderResult(datas, position) {
    var html = '';

    if (datas.length > 0) {
        html += '<table class="table table-condensed text-white">';
        var row = null;
        var lName = '';
        var y = 0;
        var m = 0;
        var d = 0;
        var dFormat = '';

        var leftClass = '';
        var rightClass = '';

        var rs = '';
        var rsClass = '';
        var rsIcon = '';

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];

            leftClass = '';
            rightClass = '';
            rs = '? : ?';
            rsClass = '';

            lName = (row.league.name) ? row.league.name : '-';
            lName = (lName == 'Primera Division') ? 'SPAIN LA LIGA' : lName;

            y = parseInt(row.event_date.substr(0, 4));
            m = parseInt(row.event_date.substr(5, 2));
            d = parseInt(row.event_date.substr(8, 2));
            dFormat = dateShortFormat(d, m, y);

            if (position == 'home') {
                leftClass = (row.homeTeam.team_name == teamName) ? 'text-danger' : '';
                rightClass = (row.awayTeam.team_name == teamName) ? 'text-danger' : '';
            } else {
                leftClass = (row.homeTeam.team_name == teamName) ? 'text-danger' : '';
                rightClass = (row.awayTeam.team_name == teamName) ? 'text-danger' : '';
            }

            html += '<tr>';
            html +=     '<td class="hd-format"><div class="team-show d-flex alit-center date-mb team-date-mb">' + dFormat; + '</div></td>';
            html +=     '<td class="text-right ht-format">';
            html +=             '<div class="team-show d-flex alit-center just-end">';
            html +=         '<span class="min-width-seven ' + leftClass + ' tud-bun-tud">' + row.homeTeam.team_name + '</span><img src="' + row.homeTeam.logo + '" width="35" class="ml-2">';
            html +=         '</div>';
            html +=     '</td>';

            if ((row.homeTeam.team_name == teamName) && (row.goalsHomeTeam > row.goalsAwayTeam)) {
                // win (home side)
                rsIcon = '<div class="match-cube win flex-center">W</div>';
            } else if ((row.homeTeam.team_name == teamName) && (row.goalsHomeTeam < row.goalsAwayTeam)) {
                // lose (home side)
                rsIcon = '<div class="match-cube lose flex-center">L</div>';
            } else if ((row.awayTeam.team_name == teamName) && (row.goalsHomeTeam < row.goalsAwayTeam)) {
                // win (away side)
                rsIcon = '<div class="match-cube win flex-center">W</div>';
            } else if ((row.awayTeam.team_name == teamName) && (row.goalsHomeTeam > row.goalsAwayTeam)) {
                // lose (away side)
                rsIcon = '<div class="match-cube lose flex-center">L</div>';
            } else {
                // draw
                rsIcon = '<div class="match-cube draw flex-center">D</div>';
            }

            if (row.statusShort == 'FT') {
                rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                rsClass = 'text-info';
            } else if (row.statusShort == '1H' || row.statusShort == '2H' || row.statusShort == 'HT') {
                rs = row.goalsHomeTeam + ' : ' + row.goalsAwayTeam;
                rsClass = 'text-info';
            }

            html +=     '<td class="hrs-format"><div class="team-show d-flex alit-center just-center ' + rsClass + '">' + rs + '</div></td>';
            html +=     '<td class="ht-format">';
            html +=         '<div class="team-show d-flex alit-center">';
            html +=             '<img src="' + row.awayTeam.logo + '" width="35" class="mr-2"><span class="min-width-seven ' + rightClass + ' tud-bun-tud">' + row.awayTeam.team_name + '</span>';
            html +=         '</div>';
            html +=     '</td>';
            html +=     '<td class="hrs-format">' + rsIcon + '</td>';
            html +=     '<td class="hl-format hide-if-mobile"><div class="team-show d-flex alit-center just-center tud-bun-tud">' + lName + '</div></td>';
            html += '</tr>';
        }

        html += '</table>';
    }

    $('.h2h-' + position).html(html);
}

function groupSevenSet(datas, year) {
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

    renderPlayerStat('star_content_' + year, topScore);
    renderPlayerStat('assist_content_' + year, topAssist);
    renderPlayerStat('chance_content_' + year, topChange);
    renderPlayerStat('frame_content_' + year, topFrame);
    renderPlayerStat('yellow_content_' + year, topYellow);
    renderPlayerStat('red_content_' + year, topRed);
    renderPlayerStat('time_content_' + year, topTime);
}

function renderPlayerStat(tab_id, datas) {
    var html = '';

    if (datas.length > 0) {
        var row = null;

        for (var i = 0; i < datas.length; i++) {
            row = datas[i];

            if (row.team_name == teamName) {
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
    } else {
        html + '-- ไม่มีข้อมูล --';
    }

    $('#' + tab_id).html(html);
}

function compareEventTimeStamp(a, b) {
    if (a.event_timestamp < b.event_timestamp) {
        return 1;
    }
    if (a.event_timestamp > b.event_timestamp) {
        return -1;
    }

    return 0;
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