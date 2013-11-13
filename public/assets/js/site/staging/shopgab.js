
	/**
	 *
	 */
	var shopgab = {

		domain: 'test.shopgab.com',
		protocol: 'http',

		init: function()
		{
			
		},

		/**
		 *
		 */
		url: function(path)
		{
			return shopgab.protocol + '://' + shopgab.domain + '/' + path;
		},

		/**
		 *
		 */
		log: function(text, data)
		{
			if (typeof data == 'undefined')
			{
				console.log(text);
			}
			else
			{
				console.log(text, data);
			}
		}

	};