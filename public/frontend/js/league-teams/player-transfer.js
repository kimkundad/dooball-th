var years = $('#years').val();
var yearList = years.split(',');
var yearStructure = {};

var slideLeft;
var touchSlide;
var marketListing = [];
var slidePoint = 215; // 215 + 215

function teamInfo(teamId, year) {
    var params = {team_id: teamId};

    $.ajax({
        url: $('#base_url').val() + '/api/transfers',
        type: 'GET',
        data: params,
        dataType: 'json',
        cache: false,
        success: function (transfers) {
            if (transfers.length > 0) {
                teamAllYears = transfers;
                teamInYear(year, 'main');
                teamInYear(year, 'widget');
            } else {
                console.log('--- Failed to load team transfer: ' + leagueId + ' ---');
                $('#pml_tab_content .graph-loading').remove();
                $('.tb-transfer').css('visibility', 'visible');
                $('.tb-transfer').html('<tr><td colspan="4"><h5 class="text-muted mt-2 text-center">--- ไม่มีข้อมูลการซื้อขาย ---</h5></td></tr>');
            }
        },
        error: function(response) {
            console.log('--- Failed to load team transfer: ' + leagueId + ' ---');
            $('#pml_tab_content .graph-loading').remove();
            $('.tb-transfer').css('visibility', 'visible');
            $('.tb-transfer').html('<tr><td colspan="4"><h5 class="text-muted mt-2 text-center">--- ไม่มีข้อมูลการซื้อขาย ---</h5></td></tr>');
        }
    });
}

function teamInYear(year, mode) {
    var thisYearList = [];

    if (teamAllYears.length > 0) {
        var date = '';
        var y = '';

        for (var i = 0; i < teamAllYears.length; i++) {
            date = teamAllYears[i].transfer_date;
            y = date.substr(0, 4);

            if (parseInt(y) == parseInt(year)) {
                thisYearList.push(teamAllYears[i]);
            }
        }

        if (mode == 'main') {
            genYearTab(year, thisYearList);
        } else {
            genWidgetSlider(thisYearList);
        }
    }
}

function genYearTab(year, yearDatas) {
    if (yearDatas.length > 0) {
        var tbHtml = '';
        var price = 0;
        var rawPrice = 0;
        var newPrice = null;

        for (var f = 0; f < yearDatas.length; f++) {
            price = 0;
            rawPrice = 0;

            if (yearDatas[f].type != 'Loan' && yearDatas[f].type != 'N/A' && yearDatas[f].type != 'Free' && yearDatas[f].type != '€ Free') {
                rawPrice = yearDatas[f].type;

                if (rawPrice) {
                    newPrice = rawPrice.replace('€', '');
                    
                    var positionOfEuro = rawPrice.indexOf('M');

                    if (positionOfEuro != -1) {
                        newPrice = newPrice.replace('M', '');
                        newPrice = newPrice.replace(',', '');
                        newPrice = parseFloat(newPrice.trim());
                        price = newPrice * 1000000;
                    }

                    var positionOfEuro = rawPrice.indexOf('K');

                    if (positionOfEuro != -1) {
                        newPrice = newPrice.replace('K', '');
                        newPrice = newPrice.replace(',', '');
                        newPrice = parseFloat(newPrice.trim());
                        price = newPrice * 1000;
                    }
                }
            }

            yearDatas[f]['price'] = price;
        }

        yearDatas.sort(comparePrice);

        var row = null;
        var outTeam = null;
        var inTeam = null;
        var urlOut = '';
        var urlIn = '';

        for (var i = 0; i < yearDatas.length; i++) {
            row = yearDatas[i];

            // console.log(row.team_out.team_name, row.team_in.team_name);

            if ((row.team_out.team_name == teamName || row.team_in.team_name == teamName) && i < 5) {
                // console.log(row);
                urlOut = 'https://media.api-sports.io/football/teams/' + row.team_out.team_id + '.png';
                urlIn = 'https://media.api-sports.io/football/teams/' + row.team_in.team_id + '.png';
    
                outTeam = '<img src="' + urlOut + '" title="' + row.team_out.team_name + '" class="img-round" height="35" onerror="javascript:this.src=' + noImage + '">';
                inTeam = '<img src="' + urlIn + '" title="' + row.team_out.team_name + '" title="' + row.team_in.team_name + '" class="img-round" height="35" onerror="javascript:this.src=' + noImage + '">';
    
                tbHtml += '<tr class="text-white">';
                tbHtml +=   '<td><img src="https://media.api-sports.io/football/players/' + row.player_id + '.png" class="img-round" width="50"><span class="ml-2">' + row.player_name + '</span></td>';
                tbHtml +=   '<td class="text-center">' + outTeam + '<br>' + row.team_out.team_name + '</td>';
                tbHtml +=   '<td class="text-center">' + inTeam + '<br>' + row.team_in.team_name + '</td>';
                tbHtml +=   '<td class="text-center">' + ((row.type) ? row.type : '-') + '</td>';
                tbHtml += '</tr>';
            }

        }

        yearStructure[year] = tbHtml;

        // console.log(n, max, year);
        if (yearStructure[year]) {
            $('.tbody-trans' + year).html(yearStructure[year]);
        } else {
            $('.tbody-trans' + year).html('<tr><td colspan="4" class="text-muted text-center mt-2" style="width:100%;">-- ไม่มีข้อมูล --</td></tr>');
        }

        $('.tb-transfer').css('visibility', 'visible');
        $('.tb-transfer').css('height', 'auto');

        $('#pml_tab_content .graph-loading').remove();
    }
}

function comparePrice(a, b) {
    if ( a.price < b.price ){
        return 1;
    }
    if ( a.price > b.price ){
        return -1;
    }

    return 0;
}


function genWidgetSlider(thisYearList) {
    if (thisYearList.length > 0) {
        var tbHtml = '';
        var noImage = "'images/no-image.jpg'";
        var price = 0;
        var rawPrice = 0;
        var newPrice = null;
        var row = null;
        var temp = [];

        for (var f = 0; f < thisYearList.length; f++) {
            row = thisYearList[f];
            price = 0;
            rawPrice = 0;

            if (row.team_out.team_name == teamName || row.team_in.team_name == teamName) {
                if (row.type != 'Loan' && row.type != 'N/A' && row.type != 'Free' && row.type != '€ Free') {
                    rawPrice = row.type;
    
                    if (rawPrice) {
                        newPrice = rawPrice.replace('€', '');
                        
                        var positionOfEuro = rawPrice.indexOf('M');
    
                        if (positionOfEuro != -1) {
                            newPrice = newPrice.replace('M', '');
                            newPrice = newPrice.replace(',', '');
                            newPrice = parseFloat(newPrice.trim());
                            price = newPrice * 1000000;
                        }
    
                        var positionOfEuro = rawPrice.indexOf('K');
    
                        if (positionOfEuro != -1) {
                            newPrice = newPrice.replace('K', '');
                            newPrice = newPrice.replace(',', '');
                            newPrice = parseFloat(newPrice.trim());
                            price = newPrice * 1000;
                        }
                    }
                }
    
                thisYearList[f]['price'] = price;
                thisYearList[f]['img'] = 'https://media.api-sports.io/football/players/' + row.player_id + '.png';
    
                temp.push(thisYearList[f]);
            }
        }

        marketListing = temp;

        marketListing.sort(comparePrice);
        clearWidgetSlide();
    }
}

function clearWidgetSlide() {
    slideLeft = 0;
    touchSlide = 0;
    var row = null;
    var urlOut, urlIn, outTeam, inTeam, name;

    if (marketListing.length > 0) {
        var slideEle = '';
        for (var i = 0; i < marketListing.length; i++) {
            row = marketListing[i];

            name = row.player_name;
            name = name.replace(/ /g, '-');

            urlOut = 'https://media.api-sports.io/football/teams/' + row.team_out.team_id + '.png';
            urlIn = 'https://media.api-sports.io/football/teams/' + row.team_in.team_id + '.png';

            outTeam = '<img src="' + urlOut + '" title="' + row.team_out.team_name + '" class="img-round" height="35" onerror="javascript:this.src=' + noImage + '">';
            inTeam = '<img src="' + urlIn + '" title="' + row.team_out.team_name + '" title="' + row.team_in.team_name + '" class="img-round" height="35" onerror="javascript:this.src=' + noImage + '">';

            slideEle += '<div class="slide-box">';
            slideEle +=     '<a  class="slide-ele link-nodec" href="' + thisHost + '/players/' + name + '" target="_BLANK">';
            slideEle +=         '<img src="' + row.img + '" class="img-fluid img-round" />';
            slideEle +=         '<div class="text-white">' + row.player_name + '</div>';
            slideEle +=     '</a>';
            slideEle +=     '<div class="row mt-3 text-white">';
            slideEle +=         '<div class="col-4">' + outTeam + '<br>ย้ายจาก</div>';
            slideEle +=         '<div class="col-4">' + inTeam + '<br>เข้าร่วม</div>';
            slideEle +=         '<div class="col-4  d-flex alit-end">' + ((row.type) ? row.type : '-') + '<br>มูลค่า</div>';
            slideEle +=     '</div>';
            slideEle += '</div>';
        }

        $('.slide-widget-cover').html(slideEle);
        $('.slide-widget-cover').css('transform', 'translate(0px, 0px)');
    }
}

function prevWidgetSlide() {
    if (slideLeft < 0) {
        slideLeft += slidePoint;
        touchSlide--;
        $('.slide-widget-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
    }
}

function nextWidgetSlide() {
    slideLeft -= slidePoint;
    touchSlide++;
    var i = (touchSlide - 1);
    marketListing.push(marketListing[i]);
    var slideEle = '';
    
    slideEle += '<div class="slide-box">';
    // slideEle +=     '<a  class="slide-ele" href="' + marketListing[i].slug + '">';
    slideEle +=         '<img src="' + marketListing[i].img + '">';
    // slideEle +=         '<span>' + marketListing[i].title + '</span>';
    // slideEle +=     '</a>';
    slideEle += '</div>';

    $('.slide-widget-cover').append(slideEle);

    var wd = $('.slide-widget-cover').css('width');
    var slideWidth = wd.replace('px', '');
    var wdth = parseInt(slideWidth, 10);
    var newWidth = wdth + slidePoint;

    $('.slide-widget-cover').css('width', newWidth + 'px');
    $('.slide-widget-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
}