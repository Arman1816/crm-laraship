$('.show-mega').on('mouseover touchstart', function (event) {
    event.preventDefault();

    let megaMenu = $(this).closest('li').find('.mega');

    if (megaMenu.length === 0) {
        return false;
    }

    $('.mega').not(megaMenu).removeClass('open');

    if ($("#modalShopFilters").is(":visible")) {
        megaMenu.css('left', '-15px');
    } else {
        megaMenu.css('left', megaMenu.closest('.widget').width() + 30 + 'px');
    }

    megaMenu.css('width', $("#shop-items").width() + 'px');
    megaMenu.addClass('open');

    event.stopPropagation();
});

$('.show-mega').on('touchstart', function (event) {
    if ($(window).width() < 768) {
        event.preventDefault();
    }
});

$(document).on('click', function (event) {
    let target = $(event.target);

    if (!target.closest('.mega').length && !target.hasClass('mega')) {
        $('.mega').removeClass('open');
    }
});