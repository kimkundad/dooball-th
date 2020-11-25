var advOne = $('#adv_one').val();
var advTwo = $('#adv_two').val();
var advThree = $('#adv_three').val();
var advFour = $('#adv_four').val();
var advFive = $('#adv_five').val();
var advList = [advOne, advTwo, advThree, advFour, advFive];
var advListText = [$('#adv_one_text').val(), $('#adv_two_text').val(), $('#adv_three_text').val(), $('#adv_four_text').val(), $('#adv_five_text').val()];

function advanceData(index, leagueId) {
    var params = {date: advList[index-1]};

    $.ajax({
        url: $('#base_url').val() + '/api/programs',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (fixtures) {
            if (fixtures.length > 0) {
                filterLeagueAdvance(fixtures, index, leagueId);
            } else {
                $('.adv-box .graph-loading').remove();
                $('.adv-box').html('<h5 class="text-center text-muted">-- ไม่มีข้อมูล --</h5>');
            }

            if (index == 1) {
                $('.adv-box .graph-loading').remove();
            }

            setTimeout(function() {
                if (index < 5) {
                    advanceData(++index, leagueId);
                }
            }, 1000);
        },
        error: function(response) {
            console.log('--- Failed to load program: ' + leagueId + ' ---');
            $('.adv-box .graph-loading').remove();
            $('.adv-box').html('<h5 class="text-center text-muted">-- ไม่มีข้อมูล --</h5>');
        }
    });
}

function filterLeagueAdvance(datas, index, leagueId) {
    if (datas.length > 0) {
        var html = '';
        var row = null;

        html += '<div class="ele-right-th pl-2">' + advListText[index-1] + '</div>';
        html += '<div class="d-flex df-col">';

        var countPm = 0;
        var showTime = '';

        for (var i = 0; i < datas.length; i++) {
            row  = datas[i];

            if (leagueId == 524) {
                if (row.league.name == 'Premier League' && row.league.country == 'England') {
                    showTime = checkShowTime(row);
    
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score wide text-bold">' + showTime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 775) {
                if (row.league.name == 'Primera Division') {
                    showTime = checkShowTime(row);
    
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score wide text-bold">' + showTime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 891) {
                if (row.league.name == 'Serie A') {
                    showTime = checkShowTime(row);
    
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score wide text-bold">' + showTime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                }
            } else if (leagueId == 754) {
                if (row.league.name == 'Bundesliga 1') {
                    showTime = checkShowTime(row);
    
                    html += '<div class="right-hist d-flex p-2">';
                    html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                    html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-score wide text-bold">' + showTime + '</span>';
                    html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                    html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                    html +=     '</a>';
                    html += '</div>';
                    countPm++;
                } else if (leagueId == 525) {
                    if (row.league.name == 'Ligue 1') {
                        showTime = checkShowTime(row);
        
                        html += '<div class="right-hist d-flex p-2">';
                        html +=     '<a href="' + thisHost + '/h2h/' +  row.fixture_id + '" target="_BLANK" class="team-show d-flex alit-center">';
                        html +=         '<span class="t-name">' + row.homeTeam.team_name + '</span>';
                        html +=         '<span class="t-logo"><img src="' + row.homeTeam.logo + '" width="25"></span>';
                        html +=         '<span class="t-score wide text-bold">' + showTime + '</span>';
                        html +=         '<span class="t-logo"><img src="' + row.awayTeam.logo + '" width="25"></span>';
                        html +=         '<span class="t-name">' + row.awayTeam.team_name + '</span>';
                        html +=     '</a>';
                        html += '</div>';
                        countPm++;
                    }
                }
            }
        }

        html += '</div>';

        if (countPm > 0) {
            $('.adv-box').append(html);
        } else {
            $('.adv-box').html('<h5 class="text-center text-muted">-- ไม่มีข้อมูล --</h5>');
        }
    }
}