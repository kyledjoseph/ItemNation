		<img class="arrow left" src="/assets/img/bookmark/left.png" />
		<img class="arrow right" src="/assets/img/bookmark/right.png" />
		<!-- <img class="image" src="/assets/img/bookmark/logo.png" /> -->
		<img class="product-image" id="1" class="image" src="http://placehold.it/200x200">
		<div class="gallery">
		</div>
		<input type="text" placeholder="Product Name" maxlength="50">
		
		<textarea placeholder="Description"></textarea>
		
		<label for="add_to">add to</label>
		<?= Form::select('add_to', null, array('chat' => 'Chat', 'wishlist' => 'Wish List', 'my_items' => 'My Items')) ?>

		<label for="chat_id">chat name</label>
		<?= Form::select('chat_id', null, $user->select_chat()) ?>

		<a class="cancel" href="#">Cancel</a>
		<a class="add" href="#">Add Product</a>