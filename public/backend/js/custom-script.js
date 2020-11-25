$(function() {
    $('.sidebar-menu li > a').on('click', function() {
        const link = $(this).attr('href');
        if (link != '#') {
            window.location = $('#base_url').val() + link;
        }
    });

    // maybe move admin script here..
});