function predictionTeamDatas(ip, apiHost, filterName, teamName = null) {
    const params = {
        'ip': ip,
        'filter_name': filterName,
        'team_name': teamName
    };

    $.ajax({
        url: apiHost + '/api/ffp-custom',
        type: 'POST',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (response) {
            if (response.final_list) {
                arrangeContent(response.final_list);
            }
        },
        error: function(response) {
            console.log(response);
            $('.ffp-box').html('<span class="text-muted text-center">--- ไม่มีข้อมูลในช่วงเวลานี้ --</span>');
        }
    });
}

function arrangeContent(finalList) {
    if (finalList.length > 0) {
        var html = '';
        var diffList = [];

        for (var k = 0; k < finalList.length; k++) {
            var matchDatas = finalList[k].match_datas;

            for (var l = 0; l < matchDatas.length; l++) {
                var data = matchDatas[l];
                var detailId = data.detail_id;
                var homeTeam = data.left[0];
                var awayTeam = data.right[0];

                var water = (data.datas.asian.water) ? data.datas.asian.water : 0;
                var lastWater = (data.datas.asian.last_water) ? data.datas.asian.last_water : 0;

                var diff = water - lastWater;
                var color = (diff >= 0) ? 'text-success' : 'text-danger';
                var sbdDiff = Math.abs(diff);
                var divideWater = water - 1;
                var percentDiff = 0;

                if (divideWater != 0 && 1 == 1) {
                    percentDiff = (diff * 100 ) / divideWater;
                }

                var diffScorePercent = percentFormat(percentDiff);

                var obj = {
                    diffScore: Math.abs(diffScorePercent),
                    diffScorePercent: diffScorePercent,
                    home_team: homeTeam,
                    away_team: awayTeam,
                    link: detailId,
                    score: ((data.score) ? data.score : '-')
                };

                diffList.push(obj);
            }
        }

        diffList.sort(compare);
        // console.log(diffList);

        var topFive = diffList.slice(0, 5);
        // console.log(topFive);

        if (topFive.length > 0) {
            var ffp = '';

            for (var i = 0; i < topFive.length; i++) {
                ffp += '<div class="ele-ffp item-' + i + ' d-flex alit-center">';
                ffp +=      '<div class="lt-name">' + topFive[i].home_team + '</div>';
                ffp +=      '<div class="ffp-vs">' + topFive[i].score + '</div>';
                ffp +=      '<div class="rt-name">' + topFive[i].away_team + '</div>';
                ffp +=      '<div class="ffp-g-icon text-right">';
                ffp +=          '<span class="' + percentColor(parseInt(topFive[i].diffScorePercent)) + ' text-bold">' + topFive[i].diffScorePercent + '%</span>';
                // ffp +=          '<a href="' + thisHost + '/ราคาบอลไหล?link=' + topFive[i].link + '" target="_BLANK"><i class="fa fa-chart-line text-white"></i></a>';
                ffp +=      '</div>';
                ffp +=  '</div>';
            }

            $('.ffp-box .graph-loading').remove();
            $('.ffp-box').append(ffp);
        } else {
            $('.ffp-box').html('<span class="text-muted">--- ไม่มีข้อมูลในช่วงเวลานี้ --</span>');
        }
    } else {
        $('.ffp-box').html('<span class="text-muted">--- ไม่มีข้อมูลในช่วงเวลานี้ --</span>');
    }
}

function compare( a, b ) {
    if ( a.diffScore < b.diffScore ){
        return 1;
    }
    if ( a.diffScore > b.diffScore ){
        return -1;
    }

    return 0;
}