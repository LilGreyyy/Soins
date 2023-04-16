$('a.page-scroll').bind('click', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
        scrollTop: $($anchor.attr('href')).offset().top,
    }, 500, 'linear');
    event.preventDefault();
});

var mn = $(".main-nav")

$(window).scroll(function() {
    if ($(this).scrollTop() > 250) {
        mn.addClass("main-nav-scrolled");
    } else {
        mn.removeClass("main-nav-scrolled");
    }
});