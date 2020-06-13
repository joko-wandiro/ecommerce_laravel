jQuery.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
jQuery(document).ready(function ($) {
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        items: 1,
    })
    $("a.nav-cart, .cart-info").mouseenter(function () {
        $('.cart-info').show();
    })
    $("a.nav-cart, .cart-info").mouseleave(function () {
        $('.cart-info').hide();
    })
    // Trigger form submit for input search
    $('.dk-table-row-multiple-search :input[name="search[multiple]"]').keyup(function (e) {
        $elem = $(this);
        if (e.keyCode == 13) {
            $form = $elem.parent().parent().parent().parent().parent().parent().parent();
            $form.trigger('submit');
        }
    })
//
//    $('.dk-table-row-search :input[name^="search"]').keyup(function (e) {
//        $elem = $(this);
//        if (e.keyCode == 13) {
//            $form = $elem.parent().parent().parent().parent().parent().parent();
//            $form.trigger('submit');
//        }
//    })
});
