var imgOne = $('#mock_one').val();
var imgTwo = $('#mock_two').val();
var imgThree = $('#mock_three').val();
var imgFour = $('#mock_four').val();
var imgFive = $('#mock_five').val();
var imgSix = $('#mock_six').val();
var imgSeven = $('#mock_seven').val();
var imgEight = $('#mock_eight').val();

var slideLeft;
var touchSlide;
var newListing = [
    { title: "...", slug: '/premierleague', img: imgOne },
    { title: '...', slug: '/laliga', img: imgTwo },
    { title: '...', slug: '/calcio', img: imgThree },
    { title: '...', slug: '/bundesliga', img: imgFour },
    { title: '...', slug: '/ligue1', img: imgFive },
    { title: '...', slug: '/jleague', img: imgSix },
    { title: '...', slug: '/thaipremierleague', img: imgSeven },
    { title: '...', slug: '/championship', img: imgEight }
];

function clearSlide() {
    slideLeft = 0;
    touchSlide = 0;

    $.ajax({
        url: $('#base_url').val() + '/api/big-league',
        type: 'GET',
        dataType: 'json',
        cache: false,
        success: function (response) {
            if (response.length > 0) {
                newListing = response;
            }

            storeSlider();
        },
        error: function(response) {
            storeSlider();
        }
    });
}

function storeSlider() {
    var slideEle = '';

    if (newListing.length > 0) {
        for (var i = 0; i < newListing.length; i++) {
            slideEle += '<div class="slide-box">';
            slideEle +=     '<a  class="slide-ele" href="' + newListing[i].slug + '">';
            slideEle +=         '<img src="' + newListing[i].img + '" class="img-league-slider">';
            // slideEle +=         '<span>' + newListing[i].title + '</span>';
            slideEle +=     '</a>';
            slideEle += '</div>';
        }
    }

    $('.slide-cover').html(slideEle);
    $('.slide-cover').css('transform', 'translate(0px, 0px)');
}

function prevSlide() {
    if (slideLeft < 0) {
        slideLeft += 130; // 115 + 15
        touchSlide--;
        $('.slide-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
    }
}

function nextSlide() {
    slideLeft -= 130; // 115 + 15
    touchSlide++;
    var i = (touchSlide - 1);
    newListing.push(newListing[i]);
    var slideEle = '';
    
    slideEle += '<div class="slide-box">';
    slideEle +=     '<a  class="slide-ele" href="' + newListing[i].slug + '">';
    slideEle +=         '<img src="' + newListing[i].img + '" class="img-league-slider">';
    // slideEle +=         '<span>' + newListing[i].title + '</span>';
    slideEle +=     '</a>';
    slideEle += '</div>';

    $('.slide-cover').append(slideEle);

    var wd = $('.slide-cover').css('width');
    var slideWidth = wd.replace('px', '');
    var wdth = parseInt(slideWidth, 10);
    var newWidth = wdth + 130; // 115 + 15

    $('.slide-cover').css('width', newWidth + 'px');
    $('.slide-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
}