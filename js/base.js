$('a[href^="#"]:not([role="tab"])').on('click', function (e) {
    var section = $($(this).attr('href'));
    console.log(section);
    if (section.length > 0) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: section.offset().top - 70
        }, {}, 500);
    }
});
/*
$(window).on('scroll', function (e) {
    if ($(window).scrollTop() > topNavOffset) {
        $('.navbar').addClass('navbar-fixed-top');
        $('.jumbotron').addClass('more-padding');
    }
    else {
        $('.navbar').removeClass('navbar-fixed-top');
        $('.jumbotron').removeClass('more-padding');
    }
});*/

/* Prevent disabled links from causing a page reload */
$("li.disabled a").click(function() {
    event.preventDefault();
});
