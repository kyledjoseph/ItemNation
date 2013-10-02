	<div class="row">
		<div class="col-12 col-sm-2">
			<h4><?= $quest->user->display_name() ?></h4>
		</div>
		<div class="col-12 col-sm-5 col-lg-6">
			<h4 class="help-me">Please help me find a <span class="product-name"><?= $quest->name() ?></span></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-3 col-sm-2 col-lg-2">
			<img src="<?= $quest->user->get_avatar_uri(200, 200) ?>" width="90%" />
		</div>
		<div class="col-9 col-sm-5 col-lg-6">
			<div class="bubble">
				<p><?= $quest->description() ?></p>
				<?php if (isset($user) and $quest->belongs_to_user($user->id)): ?>
				<?= Html::anchor('#questModal', 'Edit Quest', array('class' => '', 'data-toggle' => 'modal')) ?> |
				<?= Html::anchor('#deleteQuestModal', 'Delete Quest', array('class' => '', 'data-toggle' => 'modal')) ?> 
			<?php endif; ?>
		</div>
		<div class="purchase-within">
			Purchase within:
			<?= Form::open(array('id' => 'purchase_within_form', 'class' => 'inline-block submit-on-change', 'action' => $quest->url('within'))) ?>
			<?= Form::select('purchase_within', $quest->purchase_within_option(), Model_Quest::purchase_within_fields(), array('class' => 'form-control')) ?>
			<?= Form::close() ?>
			<span class="faded"><?= $quest->purchase_within !== '0' ? "({$quest->purchase_within()}) days" : '' ?></span>
		</div>
	</div>

	<div class="col-12 col-sm-4 col-lg-3 col-sm-offset-1 align-center">
		<?php if (isset($user) and $quest->belongs_to_user($user->id)): ?>
		<div class="pushups">
			
			<?php if ($quest->is_public): ?>
				<a id="private-public" class="btn btn-primary btn-block marg-bottom quest-private" href="<?= Uri::create($quest->url("access/private")) ?>"><i class="icon-lock icon-large"></i>&nbsp;&nbsp;&nbsp;Public</a>
			<?php else: ?>
				<a id="private-public" class="btn btn-primary btn-block marg-bottom quest-private" href="<?= Uri::create($quest->url("access/public")) ?>"><i class="icon-lock icon-large"></i>&nbsp;&nbsp;&nbsp;Private</a>
			<?php endif; ?>

			<button id="fb_share" class="marg-bottom push-center btn btn-fb btn-block push-center quest-invite"
			data-picture="<?= $quest->default_thumb_url() ?>"
			data-link="<?= $quest->full_url() ?>"
			data-name="Help me find a <?= $quest->name ?>"
			data-caption="ShopGab - Shop Socially!"
			data-description="<?= $user->display_name() ?> is trying to find a <?= $quest->name ?> through ShopGab and has requested your input! Please click on the link below to see their page and join in the search. Thanks!"><i class="icon-facebook icon-large"></i>&nbsp;&nbsp;&nbsp;Post to timeline</button>
			<button id="fb_invite" class="btn btn-primary btn-fb btn-block push-center quest-message" href="" data-link="<?= $quest->full_url() ?>"><i class="icon-facebook icon-large"></i>&nbsp;&nbsp;&nbsp;Message friends</button>							
		</div>
	<?php endif; ?>
</div>
</div>

<div class="row">
	<div class="col-12 col-sm-8">
		<div class="box marg-top no-border">
			<h4>Products</h4>
			<?php if ($total_products > 0): ?>
			<?= Form::open(array('id' => 'sort_quest_by', 'class' => 'inline-block submit-on-change', 'method' => 'GET', 'action' => $quest->url())) ?>
			<?= Form::select('order', $quest->active_sort(), $quest->sort_options(), array('class' => 'form-control')) ?>
			<?= Form::close() ?>
		<?php endif; ?>
		<button style="position: relative; top: -58px;" href="#addProductModal" data-toggle="modal" class="btn btn-primary pull-right">Add Product</button>

		<div class="clear pad-bottom"></div>
		<div class="row product-rows">
			<?php $product_i = 1; foreach ($quest_products as $quest_product): ?>
			<?php $product = $quest_product->product ?>
			<div class="col-12 col-sm-4 col-lg-3 quest-product-square no<?= ! $quest_product->was_added_by_owner() ? 'from-tab' : null ?>" data-product="<?= $product_i ?>">
				<div class="quest-border">
					<div class="added-by<?php if (! $quest_product->was_added_by_owner()): ?> dark-grey<?php endif; ?>">
						<?php if (! $quest_product->was_added_by_owner()): ?>
						<i class="icon-user"></i> Added by <?= $quest_product->user->display_name() ?>
					<?php endif; ?>
					&nbsp;
				</div>
				<a href="<?= $product->product_url() ?>" class="quest-product-image-div" style="background-image:url(<?= $product->image_url(250, 220) ?>)" target="_blank">
					<div class="product-name">
						<div class="title-box"><?= $product->name() ?></div>
						<span class="faded"><?= $product->price() ?>
							<?php if (isset($user)): ?>
						</span>
					</div>
				</a>
				<?php if (isset($user) and $quest->belongs_to_user($user->id)): ?>
				<a class="close quest-page" href="<?= $quest_product->remove_url() ?>">&times;</a>
			<?php endif; ?>

			<div class="row product-info">
				<div class="pull-left fix-pull-left">
					<a class="no-dec" href="#commentsFor<?= $product_i ?>" data-toggle="modal"><span class="badge"><?= $quest_product->total_comments() ?></span> <i class="icon-comments-alt faded icon-large"></i><a>
					</div>
					<div class="pull-right fix-pull-right">
						<span class="badge"><?= $quest_product->total_likes() ?></span> <?= Html::anchor($quest_product->like_url(), '<i class="icon-thumbs-up-alt faded no-dec icon-large"></i>', array('title' => $quest_product->list_user_likes(), 'class' => 'user_product_vote')) ?> &nbsp; 
						<span class="badge"><?= $quest_product->total_dislikes() ?></span> <?= Html::anchor($quest_product->dislike_url(), '<i class="icon-thumbs-down-alt faded no-dec icon-large"></i>', array('title' => $quest_product->list_user_dislikes(), 'class' => 'user_product_vote')) ?>
					<?php else: ?>
					<span class="badge"><?= $quest_product->total_likes() ?></span> <a href="#registerModal" data-toggle="modal"><i class="icon-thumbs-up-alt faded no-dec icon-large"></i></a> &nbsp; 
					<span class="badge"><?= $quest_product->total_dislikes() ?></span> <a href="#registerModal" data-toggle="modal"><i class="icon-thumbs-down-alt faded no-dec icon-large"></i></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade comment-modal" id="commentsFor<?= $product_i ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?= $product->name() ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 col-sm-6 clearfix">
						<a href="<?= $product->product_url() ?>" class="quest-product-image-div" target="_blank">
							<img class="max-height-half" src="<?= $product->image_url(250, 220) ?>" />
							<br />
							<br />
							<?= $product->name() ?><br />
							<span class="faded"><?= $product->price() ?></span>
						</a>
					</div>

					<div class="col-12 col-sm-6">
						<?php foreach ($quest_product->get_comments() as $comment): ?>
						<div class="comment text">
							<div class="name"><?= $comment->user->display_name() ?></div>
							<div class="content">
								<div class="message"><?= $comment->comment ?></div>
								<div class="time"><?= $comment->time_ago() ?></div>
							</div>
						</div>
					<?php endforeach; ?>

					<?= Form::open(array('action' => $quest_product->comment_url(), 'class' => 'comment',)) ?>
					<div class="input-group">
						<input name="comment" type="text" class="form-control" placeholder="What do you think?">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit">Comment</button>
						</span>
					</div>
					<?= Form::close() ?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php $product_i++; endforeach; ?>

<div class="col-12 col-sm-4 col-lg-3 quest-product-square no" data-product="<?= $product_i ?>">
	<div class="quest-border">
		<div class="added-by">
			&nbsp;
		</div>
		<a style="border: 2px dashed #aaa; background-image:url(/assets/img/add-product.png)" href="#addProductModal" class="quest-product-image-div" data-toggle="modal">
		</a>
	</div>
</div>
</div>
</div>
</div>


<div class="col-12 col-sm-4">
	<div class="box marg-top participants">
		<h4>Participants</h4>
		<div class="content">
			<?php foreach ($quest->get_participants() as $participant): ?>
			<div class="friend-box">
				<div class="media">
					<a class="pull-left" href="#">
						<img class="media-object force-32-32" src="<?= $participant->user->get_avatar_uri() ?>" />
					</a>
					<div class="media-body">
						<strong><?= $participant->user->display_name() ?></strong>  - <?php // Html::anchor("friends/hide/{$friendship->id}", 'Remove', array('class' => 'remove')) ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="box marg-top">
	<h4>Chat</h4>
	<div class="row">
		<div class="col-12">
			<div class="chat">
				<?php if (empty($quest_messages)): ?>
				<div class="message">No messages</div>
			<?php else: ?>
			<?php foreach ($quest_messages as $message): ?>
			<div class="message">
				<div class="name"><?= $message->user->display_name() ?></div>
				<div class="content">
					<?= $message->body ?>
					<div class="time"><?= $message->time_ago() ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
</div>
</div>

	<div class="row">
		<div class="col-12">
			<?= Form::open(array('action' => $quest->url('message'))) ?>
			<div class="input-group">

				<?php if (isset($user)): ?>
				<input name="message" type="text" class="form-control">
				<?php else: ?>
				<input href="#registerModal" data-toggle="modal" name="message" type="text" class="form-control">
				<?php endif; ?>

				<span class="input-group-btn">
					<?php if (isset($user)): ?>
					<button class="chat-button btn btn-default" type="submit">Send</button>
					<?php else: ?>
					<button href="#registerModal" class="btn btn-default" data-toggle="modal">Send</button>
					<?php endif; ?>
				</span>
			</div>
			</form>
		</div>
	</div>

</div>
</div>
</div>