
	/**
	 * Shopgab Quest
	 */
	shopgab.quest = {

		/**
		 *
		 */
		url: function()
		{
			return $("#quest").attr('data-quest-url');
		},

		/**
		 *
		 */
		_init_interface: function()
		{
			shopgab.log('shopgab.quest._init_interface');

			shopgab.quest.purchase_within.init();
			shopgab.quest.access.init();
			shopgab.quest.chat.init();
			shopgab.quest.product.init();

			// 
			$('#open_url').click(function(){
				var url = $('#open_url_location').val();

				if (!/^(f|ht)tps?:\/\//i.test(url))
				{
					url = "http://" + url;
				}

				window.open(url);
				$('#open_url_location').val('');
				return false;
			});

			// product comments
			$('.comments').click(function(){
				event.preventDefault();
				var comment = $(this).parent().parent().data('product');
				$('.quest-item[data-product='+comment+'] .comment').toggle(100);
			});

			//
			$('.quest-product-image-div').css('height', $('.quest-product-image-div').width());

			// 
			$(window).resize(function() {
				$('.quest-product-image-div').css('height', $('.quest-product-image-div').width());
			});
			
		},

		/**
		 *
		 */
		init: function()
		{
			shopgab.log('shopgab.quest.init');

			this._init_interface();
			shopgab.quest.facebook.init();
		}

	};





	/**
	 * Quest Facebook Actions
	 */
	 shopgab.quest.facebook = {

		init: function()
		{
			shopgab.log('shopgab.quest.facebook.init');

			$('#fb_invite').click(function() {

				FB.ui({
					method: 'send',
					link: $(this).attr('data-link'),
					}, function(response) {
					console.log('invites sent', friends, response);
				});

				return false;
			});


			$('#fb_share').click(function() {
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
		}
	};






	/**
	 *
	 */
	shopgab.quest.purchase_within = {

		/**
		 *
		 */
		update: function()
		{
			shopgab.log('shopgab.quest.purchase_within.update');

			this.disable();
			this.text('');

			$.ajax({
				url: shopgab.quest.purchase_within.update_url(),
				type: 'POST',
				dataType: 'json',
				data: {
					purchase_within: shopgab.quest.purchase_within.value()
				},
				success: function(response) {
					if (response.success)
					{
						shopgab.quest.purchase_within.enable();
						shopgab.quest.purchase_within.text(response.text);
					}
				},
				error: function() {
					shopgab.log('ajax_error');
				}
			});
		},

		/**
		 *
		 */
		update_url: function()
		{
			return shopgab.url('quest/' + shopgab.quest.url() + '/within');
		},

		/**
		 *
		 */
		text: function(text)
		{
			return this.html.text.html(text);
		},

		/**
		 *
		 */
		value: function()
		{
			return this.html.select.val();
		},

		/**
		 *
		 */
		disable: function()
		{
			return this.html.form.prop('disabled', true);
		},

		/**
		 *
		 */
		enable: function()
		{
			return this.html.form.prop('disabled', false);
		},

		html: {
			form:   null,
			select: null,
			text:   null
		},

		/**
		 *
		 */
		_init_html: function()
		{
			this.html.form   = $("#purchase_within_form");
			this.html.select = $("#purchase_within_value");
			this.html.text   = $("#purchase_within_text");
		},

		/**
		 *
		 */
		_init_events: function()
		{
			this.html.form.change(function() {
				shopgab.quest.purchase_within.update();
			});
		},

		/**
		 *
		 */
		init: function()
		{
			shopgab.log('shopgab.quest.purchase_within.init');

			this._init_html();
			this._init_events();
		}

	};





	/**
	 *
	 */
	shopgab.quest.access = {

		/**
		 *
		 */
		update: function()
		{
			shopgab.log('shopgab.quest.access.update', $(this).val());

			//shopgab.quest.access.disable();

			$.ajax({
				url: shopgab.quest.access.update_url(),
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success)
					{
						//shopgab.quest.access.enable();
					}
				},
				error: function() {
					shopgab.log('ajax_error');
				}
			});

			return false;
		},

		/**
		 *
		 */
		update_url: function()
		{
			return shopgab.url('quest/' + shopgab.quest.url() + '/access/' + this.value());
		},

		/**
		 *
		 */
		value: function()
		{
			return $('input[name=access]:checked').val();
		},

		/**
		 *
		 */
		disable: function()
		{
			// return this.html.form.prop('disabled', true);
		},

		/**
		 *
		 */
		enable: function()
		{
			// return this.html.form.prop('disabled', false);
		},

		html: {
			access_public:  null,
			access_private: null
		},

		/**
		 *
		 */
		_init_html: function()
		{
			this.html.set_access_public  = $("#set_access_public");
			this.html.set_access_private = $("#set_access_private");
		},

		/**
		 *
		 */
		_init_events: function()
		{
			$("#quest_access").change(shopgab.quest.access.update);
		},

		/**
		 *
		 */
		init: function()
		{
			shopgab.log('shopgab.quest.access.init');

			this._init_html();
			this._init_events();
		}

	};





	/**
	 *
	 */
	shopgab.quest.chat = {

		/**
		 *
		 */
		update: function()
		{
			shopgab.log('shopgab.quest.chat.update');
			
			$.ajax({
				url: shopgab.url('quest/' + shopgab.quest.url() + '/message'),
				type: 'POST',
				dataType: 'json',
				data: {
					message: shopgab.quest.chat.message()
				},
				success: function(response) {
					if (response.success)
					{
						shopgab.quest.chat.append_message(response.view);
						shopgab.quest.chat.clear_message();
					}
					else
					{
						//TODO: error handler
					}
				},
				error: function() {
					shopgab.log('ajax_error');
				}
			});
		},


		/**
		 *
		 */
		message: function()
		{
			return this.html.text.val();
		},

		/**
		 *
		 */
		append_message: function(view)
		{
			shopgab.log('append_message', view);

			this.html.chat.append(view);
		},

		/**
		 *
		 */
		clear_message: function()
		{
			return this.html.text.val('');
		},

		/**
		 *
		 */
		html: {
			form: null,
			text: null,
			chat: null,
		},

		_init_html: function()
		{
			this.html.form = $("#quest_message_form");
			this.html.text = $("#quest_message_text");
			this.html.chat = $(".chat");
		},

		/**
		 *
		 */
		_init_events: function()
		{
			this.html.form.submit(function() {
				shopgab.quest.chat.update();
				return false;
			});
		},

		/**
		 *
		 */
		init: function()
		{
			shopgab.log('shopgab.quest.chat.init');

			this._init_html();
			this._init_events();
		}

	};




	shopgab.quest.product = {

		vote: function()
		{
			var product_id = $(this).attr('data-product-id'),
				type       = $(this).attr('data-vote-type');

			shopgab.log('shopgab.quest.product.vote(' + type + ', ' + product_id + ')');


			if (typeof product_id === 'undefined' || typeof type === 'undefined')
			{
				return true;
			}

			if (type !== 'like' && type !== 'dislike')
			{
				return true;
			}

			$.ajax({
				url: shopgab.url('quest/' + shopgab.quest.url() + '/' + type + '/' + product_id),
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.success)
					{
						$("div[data-product-id=" + product_id + "] div.quest-product-votes").html(response.view);
					}
					else
					{
						//TODO: error handler
					}
				},
				error: function() {
					//alert('ae')
					shopgab.log('ajax_error');
				}
			});

			mixpanel.track("Rate", null, function() {});

			return false;
		},

		/**
		 *
		 */
		comment: function()
		{
			var product_id = $(this).attr('data-product-id');

			shopgab.log('shopgab.quest.product.comment(' + product_id + ')');

			$.ajax({
				url: shopgab.url('quest/' + shopgab.quest.url() + '/comment/' + product_id),
				type: 'POST',
				dataType: 'json',
				data: {
					comment: shopgab.quest.product.comment_value(),
				},
				success: function(response) {
					if (response.success)
					{
						shopgab.quest.product.append_comment(response.view);
						shopgab.quest.product.clear_comment(response.view);
					}
					else
					{
						//TODO: error handler
					}
				},
				error: function() {
					//alert('ae')
					shopgab.log('ajax_error');
				}
			});

			mixpanel.track("Comment");

			return false;
		},

		comment_value: function()
		{
			return $(".comment input[name=comment]").val();
		},

		append_comment: function(view)
		{
			$(".product-comments").append(view);
		},

		clear_comment: function()
		{
			return $(".comment input[name=comment]").val('');
		},

		_init_events: function()
		{
			// product vote
			$(document).on('click', ".user_product_vote", this.vote);

			// product comment
			$("form.comment").submit(this.comment);
		},

		/**
		 * init tooltip for product votes
		 * docs: http://code.drewwilson.com/entry/tiptip-jquery-plugin
		 */
		 _init_tooltips: function()
		 {
			$(".user_product_vote").tipTip({
				maxWidth: "auto", 
				edgeOffset: 0,
				defaultPosition: 'right'
			});
		},

		/**
		 *
		 */
		init: function()
		{
			shopgab.log('shopgab.quest.product.init');

			this._init_events();
			this._init_tooltips();
		}

	};




	$(document).ready(function() {

		shopgab.quest.init();

	});
