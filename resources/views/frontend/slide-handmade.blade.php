<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Test slider</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('frontend/fontawesome-free-5.9.0/css/all.min.css') }}">
        <style>
            body {
                background: #cdcdcd;
            }
            .btn {
                display: inline-block;
                font-weight: 400;
                color: #212529;
                text-align: center;
                vertical-align: middle;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                background-color: transparent;
                border: 1px solid transparent;
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                border-radius: .25rem;
                transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            }
            .slide-area {
                height: 155px;
                margin: 0 auto;
            }
            .slide-area img {
                max-height: 100%;
            }
            .flex-center {
                display: -webkit-box;
                display: -moz-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                -moz-align-items: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                -moz-justify-content: center;
                justify-content: center;
            }
            .slide-area .slide-next,
            .slide-area .slide-prev {
                width: 22px;
                height: 34px;
            }
            .slide-area .slide-outmost {
                height: 155px;
                margin: 0 auto;
                padding: 0;
                overflow: hidden;
            }
            .slide-area .slide-next .btn,
            .slide-area .slide-prev .btn {
                -webkit-transition: all .3s ease-in;
                transition: all .3s ease-in;
                margin: 0;
                padding: 0;
                color: rgba(255,255,255,.2);
                font-size: 3rem;
                background-color: transparent;
            }
            .slide-area .slide-outmost .slide-cover {
                width: 1220px;
                height: 155px;
                -webkit-transform: translate(0,0);
                transform: translate(0,0);
                -webkit-transition: transform .3s ease-in-out;
                -webkit-transition: -webkit-transform .3s ease-in-out;
                transition: -webkit-transform .3s ease-in-out;
                transition: transform .3s ease-in-out;
                transition: transform .3s ease-in-out,
                -webkit-transform .3s ease-in-out;
            }
            .slide-area .slide-outmost .slide-cover .slide-box {
                width: 290px;
                height: 155px;
                margin-right: 15px;
                display: inline-block;
                overflow-wrap: break-word;
                word-wrap: break-word;
                -ms-word-break: break-word;
                word-break: break-word;
                -webkit-transition: all .3s ease-in;
                transition: all .3s ease-in;
                overflow: hidden;
            }
            .slide-area .slide-outmost .slide-cover .slide-box a.slide-ele {
                display: -webkit-box;
                display: -moz-flex;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-direction: normal;
                -webkit-box-orient: vertical;
                -moz-flex-direction: column;
                -ms-flex-direction: column;
                flex-direction: column;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                -moz-justify-content: flex-start;
                justify-content: flex-start;
                -webkit-box-align: center;
                -ms-flex-align: center;
                -moz-align-items: center;
                align-items: center;
                position: relative;
                width: 100%;
                height: 100%;
                text-decoration: none;
                color: rgba(255,255,255,.5);
                background: 0 0;
                -webkit-transition: all .2s ease-in;
                transition: all .2s ease-in;
                overflow-wrap: break-word;
                word-wrap: break-word;
                -ms-word-break: break-word;
                word-break: break-word;
                overflow: hidden;
            }
            @media only screen and (min-width: 980px) {
                .slide-area {
                    width: 965px!important;
                    margin-top: 20px!important;
                }
                .slide-area .slide-outmost {
                    width: 915px!important;
                }
            }
            @media only screen and (min-width: 1024px) {
                .slide-area {
                    margin-top: 25px!important;
                }
            }
            @media only screen and (min-width: 1260px) {
                .slide-area {
                    width: 1260px!important;
                    margin-top: 35px!important;
                }
                .slide-area .slide-outmost {
                    width: 1205px!important;
                }
            }
            @media only screen and (min-width: 1349px) {
                .slide-area {
                    width: 1300px!important;
                }
            }
            @media only screen and (min-width: 1501px) {
                .slide-area {
                    margin-top: 30px!important;
                }
            }
        </style>
    </head>
    <body>
        <div class="slide-area flex-center">
            <div class="slide-prev flex-center">
                <button class="btn" type="button" onclick="prevSlide()">
                    <i class="fa fa-chevron-left"></i>
                </button>
            </div>
            <div class="slide-outmost">
                <div class="slide-cover"></div>
            </div>
            <div class="slide-next flex-center">
                <button  class="btn" type="button" onclick="nextSlide()">
                    <i  class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <input type="hidden" id="mock_one" value="{{ asset('images/slider/01.png') }}">
        <input type="hidden" id="mock_two" value="{{ asset('images/slider/02.png') }}">
        <input type="hidden" id="mock_three" value="{{ asset('images/slider/03.png') }}">
        <input type="hidden" id="mock_four" value="{{ asset('images/slider/04.jpg') }}">
        <input type="hidden" id="mock_five" value="{{ asset('images/slider/05.jpg') }}">
        <script src="{{asset('frontend/js/jquery-min.js')}}"></script>
        <script>
            var imgOne = $('#mock_one').val();
            var imgTwo = $('#mock_two').val();
            var imgThree = $('#mock_three').val();
            var imgFour = $('#mock_four').val();
            var imgFive = $('#mock_five').val();

            var slideLeft;
            var touchSlide;
            var newListing = [
                                { title: "How to buy flower with Cash?", slug: '/premier', img: imgOne },
                                { title: 'What is changing and why?', slug: '/premier', img: imgTwo },
                                { title: 'Protect Yourself from Scams', slug: '/premier', img: imgThree },
                                { title: 'How to Protect Your Pooldax Account from Phishing Scams', slug: '/premier', img: imgFour },
                                { title: 'How to verify an account?', slug: '/premier', img: imgFive }
                            ];

            clearSlide();

            function clearSlide() {
                slideLeft = 0;
                touchSlide = 0;
                var slideEle = '';

                if (newListing.length > 0) {
                    for (var i = 0; i < newListing.length; i++) {
                        slideEle += '<div class="slide-box">';
                        slideEle +=     '<a  class="slide-ele" href="' + newListing[i].slug + '">';
                        slideEle +=         '<img src="' + newListing[i].img + '">';
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
                    slideLeft += 305;
                    touchSlide--;
                    $('.slide-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
                }
            }

            function nextSlide() {
                slideLeft -= 305;
                touchSlide++;
                var i = (touchSlide - 1);
                newListing.push(newListing[i]);
                var slideEle = '';
                
                slideEle += '<div class="slide-box">';
                slideEle +=     '<a  class="slide-ele" href="' + newListing[i].slug + '">';
                slideEle +=         '<img src="' + newListing[i].img + '">';
                // slideEle +=         '<span>' + newListing[i].title + '</span>';
                slideEle +=     '</a>';
                slideEle += '</div>';

                $('.slide-cover').append(slideEle);

                var wd = $('.slide-cover').css('width');
                var slideWidth = wd.replace('px', '');
                var wdth = parseInt(slideWidth, 10);
                var newWidth = wdth + 305;

                $('.slide-cover').css('width', newWidth + 'px');
                $('.slide-cover').css('transform', 'translate(' + slideLeft + 'px, 0px)');
            }
        </script>
    </body>
</html>
