<!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("0c06e22671690f1006e02a4d071839e9");</script><!-- end Mixpanel -->

		<img class="arrow left" src="/assets/img/bookmark/left.png" />
		<img class="arrow right" src="/assets/img/bookmark/right.png" />

		<img class="product-image" id="1" class="image" src="http://placehold.it/200x200">
		
		<div class="gallery">
		</div>
		<br />
<div class="form-group">
		<input type="text" class="form-control title" placeholder="Product Name" maxlength="50">
	</div>
<div class="form-group">
		<input type="text" class="price form-control" placeholder="Price" maxlength="50">
	</div>
		<div class="form-group">
		<textarea class="description form-control" placeholder="Description"></textarea>
	</div>
		<div id="add_to" class="form-group">
			<?= Form::select('add_to', 'my', array('my' => 'My Quest', 'friend' => 'Friend\'s Quest', 'new' => 'New Quest'), array('class' => 'form-control')) ?>
		</div>
		<div id="friend_id" class="form-group" style="display:none">
			<?= Form::select('friend_id', null, $user->select_friends(), array('class' => 'form-control')) ?>
		</div>
		<div id="friend_quest_id" class="form-group"style="display:none">
			<?= Form::select('friend_quest_id', null, array('class' => 'form-control')) ?>
		</div>
		<div id="new_quest_name" class="form-group" style="display:none">
			<?= Form::input('new_quest_name', null, array('class' => 'form-control', 'placeholder' => 'New Quest')) ?>
		</div>
		<div id="my_quest_url" class="form-group">
			<?= Form::select('my_quest_url', null, $user->select_quest(), array('class' => 'form-control')) ?>
		</div>
		<div class="btn-container">
			<a class="cancel btn btn-default" href="#">Cancel</a>
			<a class="add btn btn-primary" href="#">Add Product</a>
		</div>

<script type="text/javascript">
$(".add").click(function() {
    mixpanel.track("Add product");
});
</script>