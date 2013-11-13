

	shopgab.dashboard = {

		/**
		 * remove facebook authentication redirect hash (#_=_)
		 */
		_remove_fb_redirect_hash: function()
		{
			if (window.location.hash == '#_=_')
			{
				var scrollV, scrollH, loc = window.location;
				if ("pushState" in history)
				{
					history.pushState("", document.title, loc.pathname + loc.search);
				}
				else
				{
					// Prevent scrolling by storing the page's current scroll offset
					scrollV = document.body.scrollTop;
					scrollH = document.body.scrollLeft;

					loc.hash = "";

					// Restore the scroll offset, should be flicker free
					document.body.scrollTop = scrollV;
					document.body.scrollLeft = scrollH;
				}
			}
		},

		init: function()
		{
			console.log('shopgab.dashboard.init');
			this._remove_fb_redirect_hash();
		}
	};

	$(document).ready(function() {
		shopgab.dashboard.init();
	});