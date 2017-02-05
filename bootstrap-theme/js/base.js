var sideNavOffset = $('.right-nav').offset().top;

$(window).on('scroll', function (e) {
    if ($(window).scrollTop() + 20 > sideNavOffset) {
        $('.right-nav').addClass('right-nav-fixed');
    }
    else {
        $('.right-nav').removeClass('right-nav-fixed');
    }
});

/* Prevent disabled links from causing a page reload */
$("li.disabled a").click(function() {
    event.preventDefault();
});