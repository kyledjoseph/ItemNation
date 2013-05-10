		<img class="arrow left" src="/assets/img/bookmark/left.png" />
		<img class="arrow right" src="/assets/img/bookmark/right.png" />

		<img class="product-image" id="1" class="image" src="http://placehold.it/200x200">
		
		<div class="gallery">
		</div>

		<ul>
			<li>
				<input type="text" class="title" placeholder="Product Name" maxlength="50">
			</li>
			<li>
				<input type="text" class="price" placeholder="Price" maxlength="50">
			</li>
			<li>
				<textarea class="description" placeholder="Description"></textarea>
			</li>

			<li>
				<label for="add_to">add to</label>
				<?= Form::select('add_to', null, array('chat' => 'Chat', 'wishlist' => 'Wish List', 'my_items' => 'My Items')) ?>
			</li>
			<li>	
				<label for="chat_id">chat name</label>
				<?= Form::select('chat_id', null, $user->select_chat()) ?>
			</li>
		</ul>
		<div class="btn-container">
			<a class="cancel" href="#">Cancel</a>
			<a class="add" href="#">Add Product</a>
		</div>
