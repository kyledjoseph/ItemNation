<!DOCTYPE html>
<html>
<head>
	<title>ShopGab - Shop Socially!</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:url"             content="http://samples.ogp.me/136756249803614" /> 
        <meta property="og:title"           content="Chocolate Pecan Pie" /> 
        <meta property="og:image"           content="https://fbcdn-dragon-a.akamaihd.net/hphotos-ak-prn1/851565_496755187057665_544240989_n.jpg" /> 
	<!-- Bootstrap -->
	<script type="text/javascript" src="//use.typekit.net/kxv0tmh.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="/assets/css/font-awesome.min.css" rel="stylesheet" media="screen">
	<?= Casset::render_css() ?>
<!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("0c06e22671690f1006e02a4d071839e9");</script><!-- end Mixpanel -->
	</head>

	<body>

		<!-- Header -->
		<?= $view->header ?>

		<?php if (isset($notice)): ?>
		<div class="alert alert-<?= $notice->type ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?= $notice->message ?>
		</div>
	<?php endif; ?>

	<?= $body ?>
</div>
<!-- Footer -->
  <div class="footer">
    <div class="footer-white">
      <div class="footer">
        <div class="about col-12 col-sm-4">
          <h4>About Us</h4>
          <hr />
          <p>At ShopGab our mission is to help shoppers confidently purchase the best products for their needs. We provide a fun and simple way to store any product, gather feedback and get product recommendations from trusted friends. No longer does online shopping have to be a lonely, individualized process! Shop together and buy with the confidence and support of your friends and family.</p>
        </div>
        <div class="flickr-widget col-12 col-sm-4">
          <h4>Photostream</h4>
          <hr />
        <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=9&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=99861439@N02"></script>
        </div>
        <div class="follow col-12 col-sm-4">
          <h4>Join the conversation</h4>
          <hr />
          <div class="social-links">
            <a href="http://facebook.com/shopgab" class="facebook">
              <i class="icon-facebook icon-2x icon-light"></i>
          </a>
            <a href="http://twitter.com/weshopgab" class="twitter">
              <i class="icon-twitter icon-2x icon-light"></i>
            </a>
            <a href="http://pinterest.com/WeShopGab/boards/" class="pinterest">
              <i class="icon-pinterest icon-2x icon-light"></i>
            </a>
            <a href="https://plus.google.com/u/0/113964272651935695754/posts" class="google-plus">
              <i class="icon-google-plus icon-2x icon-light"></i>
            </a>
            <a href="https://shopgab.wordpress.com" class="wordpress">
              <img src="/assets/img/wordpress.png" />
            </a>
          </div>
          <a href="http://rackspace.com/"><img class="rackspace" src="/assets/img/rackspace.png" /></a>
        </div>
      </div>
      <div class="footer">
      <div class="col-12">
          <p class="text-muted">&copy; ShopGab 2013, all rights reserved.</p>
          </div>
      </div>
    </div>

	<a href="#feedbackModal" class="feedback-button" data-toggle="modal"><img src="/assets/img/feedback.png" /></a>

	<?= isset($modal) ? $modal : null ?>
	<div id="fb-root"></div>



	<!-- JavaScript plugins (requires jQuery) -->
	<script src="http://code.jquery.com/jquery.js"></script>
  <script src="//cdn.optimizely.com/js/303981027.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="/assets/js/script.js"></script>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42842086-1', 'shopgab.com');
  ga('send', 'pageview');

</script>
	<?= Casset::render_js() ?>
</body>
</html>