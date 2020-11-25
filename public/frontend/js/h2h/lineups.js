function loadLineUp(homeTeamName, awayTeamName) {
    var params = {fixture_id: $('#fixture_id').val()};

    $.ajax({
        url: $('#base_url').val() + '/api/lineups',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (lineUps) {
            if (lineUps) {
                displayLineUp(lineUps, homeTeamName, awayTeamName);
            } else {
                var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
                $('.tbody-line-up').html(tr);
            }
        },
        error: function(response) {
            var tr = '<tr><td class="text-center text-muted">-- ไม่มีข้อมูล --</td></tr>';
            $('.tbody-line-up').html(tr);
        }
    });
}

function displayLineUp(lineUps, homeTeamName, awayTeamName) {
    if (lineUps[homeTeamName]) {
        var homeLine = lineUps[homeTeamName];
        showLineUp(homeLine, 'home');
    }

    if (lineUps[awayTeamName]) {
        var awayLine = lineUps[awayTeamName];
        showLineUp(awayLine, 'away');
    }
}

function showLineUp(lineUpDatas, side) {
    $('.' + side + '-formation').html(lineUpDatas.formation);
    $('.' + side + '-coach').html(lineUpDatas.coach);

    var lineupFormat = findFormat(lineUpDatas.formation);
    
    if (lineupFormat == 'four-four-two' || lineupFormat == 'four-three-three'
        || lineupFormat == 'three-four-two-one' || lineupFormat == 'three-four-one-two'
        || lineupFormat == 'four-two-three-one' || lineupFormat == 'three-five-two'
        || lineupFormat == 'three-four-three' || lineupFormat == 'four-one-four-one'
        || lineupFormat == 'three-one-four-two' || lineupFormat == 'four-four-one-one'
        || lineupFormat == 'four-three-two-one' || lineupFormat == 'four-two-two-two'
        || lineupFormat == 'five-four-one' || lineupFormat == 'four-five-one'
        || lineupFormat == 'four-three-one-two' || lineupFormat == 'five-three-two') { // finished list
        $('.' + side + '-player.' + lineupFormat).show();
    } else {
        $('.' + side + '-player.row-formation').show();
    }

    if (lineUpDatas.startXI) {
        if (lineUpDatas.startXI.length > 0) {
            var lineUpData = null;
            var number = 0;
            var fullNamePlayer = '';
            var player = '';
            var row = '';
    
            for (var i = 0; i < lineUpDatas.startXI.length; i++) {
                lineUpData = lineUpDatas.startXI[i];
                number = lineUpData.number;
                fullNamePlayer = lineUpData.player;
                player = fullNamePlayer.split(' ')[0];
    
                if (lineupFormat == 'four-four-two' || lineupFormat == 'four-three-three'
                    || lineupFormat == 'three-four-two-one' || lineupFormat == 'three-four-one-two'
                    || lineupFormat == 'four-two-three-one' || lineupFormat == 'three-five-two'
                    || lineupFormat == 'three-four-three' || lineupFormat == 'four-one-four-one'
                    || lineupFormat == 'three-one-four-two' || lineupFormat == 'four-four-one-one'
                    || lineupFormat == 'four-three-two-one' || lineupFormat == 'four-two-two-two'
                    || lineupFormat == 'five-four-one' || lineupFormat == 'four-five-one'
                    || lineupFormat == 'four-three-one-two' || lineupFormat == 'five-three-two') { // finished list
                    if (lineUpData.pos == 'G') {
                        $('.' + side + '-player.' + lineupFormat + ' .goal .lu-num').html(number);
                        $('.' + side + '-player.' + lineupFormat + ' .goal .ply-name').html(player);
                    }
    
                    if (lineupFormat == 'four-four-two' || lineupFormat == 'four-four-one-one' || lineupFormat == 'four-three-one-two' || lineupFormat == 'four-two-two-two') {
                        fourFourTwo(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'four-three-three' || lineupFormat == 'four-three-two-one') {
                        fourThreeThree(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'three-four-two-one' || lineupFormat == 'three-four-one-two'|| lineupFormat == 'three-four-three') {
                        threeFourTwoOne(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'four-two-three-one') {
                        fourTwoThreeOne(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'three-five-two'|| lineupFormat == 'three-one-four-two') {
                        threeFiveTwo(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'four-one-four-one') {
                        fourOneFourOne(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'five-four-one') {
                        fiveFourOne(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'four-five-one') {
                        fourFiveOne(lineUpData.pos, lineupFormat, number, player, side);
                    } else if (lineupFormat == 'five-three-two') {
                        fiveThreeTwo(lineUpData.pos, lineupFormat, number, player, side);
                    }
                } else {
                    row = '<ul>';
                    row +=      '<li>';
                    row +=          '<span class="fm-rw-num mr-10">' + number + '</span>';
                    row +=          '<span class="fm-rw-pl">' + fullNamePlayer + '</span>';
                    row +=      '</li>';
                    row += '</ul>';
    
                    $('.' + side + '-player.row-formation .data-starting').append(row);
                }
            }
        }
    }

    if (lineUpDatas.substitutes) {
        if (lineUpDatas.substitutes.length > 0) {
            var row = '';
    
            for (var n = 0; n < lineUpDatas.substitutes.length; n++) {
                lineUpData = lineUpDatas.substitutes[n];
                number = lineUpData.number;
                fullNamePlayer = lineUpData.player;
                player = fullNamePlayer.split(' ')[0];
    
                row += '<ul>';
                row +=      '<li>';
                row +=          '<span class="fm-rw-num mr-10">' + number + '</span>';
                row +=          '<span class="fm-rw-pl">' + fullNamePlayer + '</span>';
                row +=      '</li>';
                row += '</ul>';
            }
    
            $('.' + side + '-row-substitutes .data-substitutes').html(row);
        }
    }
}

function findFormat(formation) {
    var fm = '';

    if (formation == '4-4-2') {
        fm = 'four-four-two'; // 1. 4-4-2
    } else if (formation == '4-3-3') {
        fm = 'four-three-three'; // 2. 4-3-3
    } else if (formation == '3-4-2-1') {
        fm = 'three-four-two-one'; // 3. 3-4-2-1
    } else if (formation == '4-2-3-1') {
        fm = 'four-two-three-one'; // 4. 4-2-3-1
    } else if (formation == '3-5-2') { // 3-5-2
        fm = 'three-five-two'; // 5. 3-5-2
    } else if (formation == '3-4-1-2') {
        fm = 'three-four-one-two'; // 6. 3-4-1-2
    } else if (formation == '3-4-3') {
        fm = 'three-four-three'; // 7. 3-4-3
    } else if (formation == '4-1-4-1') {
        fm = 'four-one-four-one'; // 8. 4-1-4-1
    } else if (formation == '3-1-4-2') {
        fm = 'three-one-four-two'; // 9. 3-1-4-2
    } else if (formation == '4-4-1-1') {
        fm = 'four-four-one-one'; // 10. 4-4-1-1
    } else if (formation == '4-3-1-2') {
        fm = 'four-three-one-two'; // 11. 4-3-1-2
    } else if (formation == '5-3-2') {
        fm = 'five-three-two'; // 12. 5-3-2
    } else if (formation == '5-4-1') {
        fm = 'five-four-one'; // 13. 5-4-1
    } else if (formation == '4-5-1') {
        fm = 'four-five-one'; // 14. 4-5-1
    } else if (formation == '4-3-2-1') {
        fm = 'four-three-two-one'; // 15. 4-3-2-1
    } else if (formation == '4-2-2-2') {
        fm = 'four-two-two-two'; // 16. 4-2-2-2
    }

    return fm;
}

function fourFourTwo(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html(player);
        }
    }
}

function fourThreeThree(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f03 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f03 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f03 .ply-name').html(player);
        }
    }
}

function threeFourTwoOne(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f03 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f03 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f03 .ply-name').html(player);
        }
    }
}

function fourTwoThreeOne(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        }
    }
}

function threeFiveTwo(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html(player);
        }
    }
}

function fourOneFourOne(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        }
    }
}

function fiveFourOne(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b05 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b05 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b05 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b05 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        }
    }
}

function fourFiveOne(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m04 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m05 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        }
    }
}

function fiveThreeTwo(position, lineupFormat, number, player, side) {
    if (position == 'D') {
        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b05 .lu-num').html() == 'D') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b05 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b03 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b04 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .back.b05 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .back.b05 .ply-name').html(player);
        }
    }

    if (position == 'M') {
        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html() == 'M') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m02 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .center.m03 .ply-name').html(player);
        }
    }

    if (position == 'F') {
        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .lu-num').html(number);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html() == 'F') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .lu-num').html(number);
        }

        if ($('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f01 .ply-name').html(player);
        } else if ($('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html() == '-') {
            $('.' + side + '-player.' + lineupFormat + ' .front.f02 .ply-name').html(player);
        }
    }
}