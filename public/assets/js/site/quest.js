$(function(){


    $('.quest-product-image-div').css('height', $('.quest-product-image-div').width());

    $( window ).resize(function() {
        $('.quest-product-image-div').css('height', $('.quest-product-image-div').width());
    });


    // init tooltip for product votes
    // docs: http://code.drewwilson.com/entry/tiptip-jquery-plugin
    //
    $(".user_product_vote").tipTip({
        maxWidth: "auto",
        edgeOffset: 0,
        defaultPosition: 'right'
    });



    // $(".invite-btn").click(function() {

    // 	facebook.add_callback(function() {

    // 		FB.api({ method: 'fql.query', query: 'SELECT uid,name FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) ORDER BY name'},
    // 			function(response) {

    // 				$('#facebook-friends').empty();

    // 				$.each(response, function(index, friend) {

    // 					if (friend.name == 'Christian Bundy' || friend.name == 'Kyle Joseph' || friend.name == 'Tyler Matthews' || friend.name == 'Dinko Kecanovic')
    // 					{
    // 						c = '<label>';
    // 						c+= '	<div class="inline-block">';
    // 						c+= '		<input class="select_fb_friend inline-block auto-width" name="fb_friends[]" value="'+friend.uid+'" type="checkbox" />';
    // 						c+= '	</div>';
    // 						c+= '	<div class="inline-block">';
    // 						c+= '		<img style="width:32px;height:32px" src="https://graph.facebook.com/'+friend.uid+'/picture?width=32&height=32">';
    // 						c+= '		'+ friend.name;
    // 						c+= '	</div>';
    // 						c+= '</label>';
    // 						c+= '<br>';

    // 						$('#facebook-friends').append(c);
    // 					}


    // 				});
    // 			}
    // 		);
    // 	});

    // });



    function selected_friends()
    {
        friends = [];

        $( ".select_fb_friend:checked" ).each(function(index) {
            friends.push($(this).val());
        });

        return friends;
    }


    function quest_id()
    {
        //return $('#');
    }


    $('body')
    .on("change",".submit-on-change", function() {
        $(this).submit();
    })
    .on('click', '#open_url',function(){
        var url = $('#open_url_location').val();

        if (!/^(f|ht)tps?:\/\//i.test(url))
            {
                url = "http://" + url;
            }

            window.open(url);
            $('#open_url_location').val('');
            return false;
    })
    .on('click','.comments', function(){
        event.preventDefault();
        var comment = $(this).parent().parent().data('product');
        $('.quest-item[data-product='+comment+'] .comment').toggle(100);
    })
    .on('click','#fb_invite', function() {

        //facebook.add_callback(function() {
        FB.ui({
            method: 'send',
            link: $(this).attr('data-link'),
        }, function(response) {
            console.log('invites sent', friends, response);
        }
        );
        //});

        return false;
    })
    .on('click','#fb_share', function() {
        FB.ui({
            method: 'feed',
            link: $(this).attr('data-link'),
            picture: $(this).attr('data-picture'), //'http://fbrell.com/f8.jpg',
            name: $(this).attr('data-name'),
            caption: $(this).attr('data-caption'),
            description: $(this).attr('data-description')
        }, function(response) {})

        return false;
    });



    // message submit on enter
    // $('textarea[name=message]').keydown(function(e) {
    // 	if (e.keyCode == 13)
    // 	{
    // 		$(this).parent().submit();
    // 		return false;
    // 	}
    // });


    // init product description read more
    //
    //
    // $('.chat-product-description').expander({

    // 	// the number of characters at which the contents will be sliced into two parts.
    // 	slicePoint: 82,

    // 	// whether to keep the last word of the summary whole (true) or let it slice in the middle of a word (false)
    // 	preserveWords: true,

    // 	// a threshold of sorts for whether to initially hide/collapse part of the element's contents.
    // 	// If after slicing the contents in two there are fewer words in the second part than
    // 	// the value set by widow, we won't bother hiding/collapsing anything.
    // 	widow: 4,

    // 	// text displayed in a link instead of the hidden part of the element.
    // 	// clicking this will expand/show the hidden/collapsed text
    // 	expandText: 'Learn more',
    // 	expandPrefix: '&hellip; ',

    // 	// class names for summary element and detail element
    // 	summaryClass: 'summary',
    // 	detailClass: 'details',

    // 	// one or more space-separated class names for <span> around
    // 	// "read-more" link and "read-less" link
    // 	moreClass: 'read-more',
    // 	lessClass: 'read-less',

    // 	// number of milliseconds after text has been expanded at which to collapse the text again.
    // 	// when 0, no auto-collapsing
    // 	collapseTimer: 0,

    // 	// effects for expanding and collapsing
    // 	expandEffect: 'fadeIn',
    // 	expandSpeed: 250,
    // 	collapseEffect: 'fadeOut',
    // 	collapseSpeed: 200,

    // 	// allow the user to re-collapse the expanded text.
    // 	userCollapse: false,

    // 	// text to use for the link to re-collapse the text
    // 	userCollapseText: 'read less',
    // 	userCollapsePrefix: ' ',

    // 	// all callback functions have the this keyword mapped to the element in the jQuery set when .expander() is called
    // 	onSlice: null, // function() {},
    // 	beforeExpand: null, // function() {},
    // 	afterExpand: null, // function() {},
    // 	onCollapse: null, // function(byUser) {},
    // 	afterCollapse: null // function() {}
    // });


});
