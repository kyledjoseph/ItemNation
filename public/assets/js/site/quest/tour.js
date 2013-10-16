if ($.cookie('old_user') == 'false' || $.cookie('old_user') == undefined) {
	$('.quest-add-product').popover('show')
	$('#fb_share').popover('show')

	$.cookie('old_user', 'true', { expires: 99999, path: '/' })
}

if ($.cookie('admin_user') != 'true') {
mixpanel.track("View Quest");
if (self_quest) {
    mixpanel.track("View Quest (self)");
}
}

$(".chat").scrollTop($(".chat")[0].scrollHeight);




$(".public-private-radios label").click(function(event) {
    newURL = $(this).find('input').attr('href');
    window.location = newURL;
});

$('.private, .public').on('mouseenter', function () {
    $(this).tooltip('show')
});

$('#element').tooltip('show')