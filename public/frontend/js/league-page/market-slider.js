var slideLeft;
var touchSlide;
var marketListing = [];

function loadMarketSlider(fixtures) {
    // console.log(fixtures.length);

    var teamIds = [];

    if (fixtures.length > 0) {
        var hTeamId = 0;
        var aTeamId = 0;
        var hTeam = null;
        var aTeam = null;
        var slug = '';
        var teamSlug = '';
        var img = '';

        for (var i = 0; i < fixtures.length; i++) {
            hTeam = fixtures[i].homeTeam;
            aTeam = fixtures[i].awayTeam;

            hTeamId = hTeam.team_id;
            aTeamId = aTeam.team_id;

            if ($.inArray(hTeamId, teamIds) === -1) {
                teamIds.push(hTeamId);

                teamSlug = (hTeam.team_name) ? hTeam.team_name.replace(' ', '-') : '-';
                slug = thisHost + '/teams/' + teamSlug;
                img = hTeam.logo;

                marketListing.push({ title: "", slug: slug, img: img });
            }

            if ($.inArray(aTeamId, teamIds) === -1) {
                teamIds.push(aTeamId);

                teamSlug = (aTeam.team_name) ? aTeam.team_name.replace(' ', '-') : '-';
                slug = thisHost + '/teams/' + teamSlug;
                img = aTeam.logo;

                marketListing.push({ title: "", slug: slug, img: img });
            }
            
        }
    }

    // console.log(marketListing);
    clearMarketSlide();
}

function clearMarketSlide() {
    slideLeft = 0;
    touchSlide = 0;
    var slideEle = '';

    if (marketListing.length > 0) {
        for (var i = 0; i < marketListing.length; i++) {
            slideEle += '<div class="slide-box">';
            slideEle +=     '<a  class="slide-ele" href="' + marketListing[i].slug + '">';
            slideEle +=         '<img src="' + marketListing[i].img + '" class="img-fluid">';
            // slideEle +=         '<span>' + marketListing[i].title + '</span>';
            slideEle +=     '</a>';
            slideEle += '</div>';
        }
    }

    $('.slide-market-cover').html(slideEle);
    $('.slide-market-cover').css('transform', 'translate(0px, 0px)');
}

function prevMarketSlide() {
    if (slideLeft < 0) {
        slideLeft += 127; // 112 + 15
        touchSlide--;
        $('.slide-market-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
    }
}

function nextMarketSlide() {
    slideLeft -= 127; // 112 + 15
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

    $('.slide-market-cover').append(slideEle);

    var wd = $('.slide-market-cover').css('width');
    var slideWidth = wd.replace('px', '');
    var wdth = parseInt(slideWidth, 10);
    var newWidth = wdth + 127; // 112 + 15

    $('.slide-market-cover').css('width', newWidth + 'px');
    $('.slide-market-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
}