<!DOCTYPE html>
<html>
<head>
  <title>ShopGab - Shop Socially!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <script type="text/javascript" src="//use.typekit.net/kxv0tmh.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="/assets/css/font-awesome.min.css" rel="stylesheet" media="screen">
<!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("0c06e22671690f1006e02a4d071839e9");</script><!-- end Mixpanel -->
</head>
<body>

  <!-- Header -->
  <div class="header container">
    <!-- Logo -->
    <div class="col-4 col-sm-2">
      <h1><img src="/assets/img/logo.png" alt="ShopGab" /></h1>
    </div>

    <!-- Navigation -->
    <div class="col-8 col-sm-10">
      <ul class="landing-nav nav nav-pills pull-right">
        <li><a class="landing-log-in" href="<?= Uri::create('auth/facebook?r=') ?>">Login</a></li>
        <li class="active landing-sign-up"><a href="#registerModal" data-toggle="modal">Sign Up</button></a></li>
      </ul>
    </div>
  </div>

  <!-- Title -->
  <div class="title">
    <div class="title-inner">
      <div class="title-content container">
        <div class="col-12 col-sm-8 hidden-sm">
          <img width="100%" src="/assets/img/girls.png" />
        </div>
        <div class="landing-text col-12 col-sm-4">
          <h2>Shop Socially!</h2>
          <h3>Because shopping is more fun with friends.</h3>
            <div class="email-form row">
              <div class="col-12 col-sm-12">
                <button class="btn btn-success btn-lg start-here btn-block" href="#registerModal" data-toggle="modal">Start Here</button>
                <div class="block">
                  <a class="learn-more block" href="http://youtu.be/rycC_GHBMv0" target="_blank"><i class="icon-facetime-video"></i> Learn how it works (click here)</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Steps -->
    <div class="steps container">
      <!-- Shop -->
      <a href="#">
        <div class="col-2 col-sm-1">
          <i class="icon-search icon-light icon-3x icon-background-circle"></i>
        </div>
        <div class="col-10 col-sm-3">
          <h3>Shop</h3>
          <p>Pick your favorite products from anywhere on the web.</p>
        </div>
      </a>

      <!-- Gab -->
      <a href="#">
        <div class="col-2 col-sm-1">
          <i class="icon-comments icon-light icon-3x icon-background-circle"></i>
        </div>
        <div class="col-10 col-sm-3">
          <h3>Gab</h3>
          <p>Invite your friends in for advice and recommendations.</p>
        </div>
      </a>

      <!-- Buy -->
      <a href="#">
        <div class="col-2 col-sm-1">
          <i class="icon-shopping-cart icon-light icon-3x icon-background-circle"></i>
        </div>
        <div class="col-10 col-sm-3">
          <h3>Buy</h3>
          <p>Purchase your favorite straight from the discussion.</p>
        </div>
      </a>
    </div>

    <!-- Proofs -->
    <div class="proofs container">
      <!-- Popular -->
      <div class="popular col-12 col-sm-8">
        <h3>Most Popular</h3>
        <hr />
        <div class="popular-images row">
          <div class="col-12 col-sm-4">
            <img src="/assets/tmp/img/photostream_1.jpg" />
          </div>
          <div class="col-12 col-sm-4">
            <img src="/assets/img/photostream_10.png" />
          </div>
          <div class="col-12 col-sm-4">
            <img src="/assets/tmp/img/photostream_3.jpg" />
          </div>
        </div>
      </div>

      <!-- Testimonial -->
      <div class="col-12 col-sm-4">
        <h3>What Users Say</h3>
        <hr />
        <div class="testimonial">
        <p>ShopGab was amazing! I still can't believe how easy it was for me and my friends to find and buy the best products for me and my new baby, and at the best prices. Teamwork in shopping!</p>
        </div>
        <div class="testimonial-credit">
        <span>Jill Sandberg</span>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <div class="footer">
    <div class="footer-white">
      <div class="container">
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
      <div class="container">
      <div class="col-12">
          <p class="text-muted">&copy; ShopGab 2013, all rights reserved.</p>
          </div>
      </div>
    </div>

    <?= isset($modal) ? $modal : null ?>

    <!-- JavaScript plugins (requires jQuery) -->
    <script src="http://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/assets/js/script.js"></script>
     <script src="//cdn.optimizely.com/js/303981027.js"></script>
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42842086-1', 'shopgab.com');
  ga('send', 'pageview');

  mixpanel.track("Land");
</script>
    <?= Casset::render_js() ?>
  </body>
  </html>