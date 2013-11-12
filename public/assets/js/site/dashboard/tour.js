//if ($.cookie('old_user') == 'false' || $.cookie('old_user') == undefined) {
	$('.tour-quest-btn').popover('show');
	$('.tour-friends-tab').popover('show');
//}

    $('.dash-product-image-div').css('height', $('.dash-product-image-div').width());

    $(window).resize(function() {
        $('.dash-product-image-div').css('height', $('.dash-product-image-div').width());
    });


