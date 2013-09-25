	<div class="row">
		<div class="col-12">
			<ul class="nav nav-tabs">
				<li class="active"><?= Html::anchor('/', 'My Quests') ?></li>
				<li class="tour-friends-tab" data-container="body" data-placement="bottom" data-content="Check out what your friends are looking for." data-original-title="" title=""><?= Html::anchor('friends', 'Friends') ?></li>
			</ul>
		</div>
		<div class="col-12">
		<div class="row">
			<div class="col-12 col-sm-8">
				<div class="row">
					<div class="col-12 col-sm-8 pad-top">
						<select class="dash-search-select form-control">
							<option disabled selected>Sort By Recent Updates</option>
						</select>
					</div>
					<div class="col-12 col-sm-4 pad-top">
						<button href="#questModal" class="btn btn-success pull-right tour-quest-btn" data-container="body" data-placement="top" data-content="Get started looking for your first product." data-original-title="" title="" data-toggle="modal">Start a new Quest!</button>
					</div>
				</div>
				<div class="row pad-top">
					<div class="col-12"
						<div class="row product-rows">
							<?php foreach ($quests as $quest): ?>
								<div class="col-12 col-sm-4 col-lg-3 dash-product-square">
									<div class="added-by">&nbsp;</div>
									<a href="<?= Uri::create($quest->url()) ?>" class="dash-product-image-div" style="background-image:url(<?= $quest->default_thumb_url(250, 220) ?>)">
										<div class="product-name"><?= $quest->name() ?></div>
										<span class="close dash-close"><span class="badge"><?= $quest->total_unseen_notifications() ?></span>
									</a>
								</div>
							<?php endforeach; ?>
							<div class="col-12 col-sm-4 col-lg-3 dash-product-square no">
								<div class="added-by">
									&nbsp;
								</div>
								<a style="border: 2px dashed #aaa; background-image:url(/assets/img/add-quest.png)" href="#questModal" class="dash-product-image-div" data-toggle="modal">
								</a>
							</div>
					</div>
			</div>
		</div>
		<div class="col-12 col-sm-4 pad-top">
			<div class="box help-a-friend">
				<h4>Help A Friend</h4>
				<div class="content">
					<?php foreach ($user->get_friends_upcoming_quests() as $quest): ?>
					<div class="friend-box">
						<div class="media">
							<a class="pull-left" href="<?= Uri::create($quest->url()) ?>">
								<img class="media-object force-32-32" src="<?= $quest->default_thumb_url(50, 50) ?>" />
							</a>
							<div class="media-body">
								<strong><?= Html::anchor($user->get_friend_profile_url($quest->user->id), $quest->user->display_name()) ?></strong> - <?= $quest->name() ?> (<?= $quest->purchase_within() ?> days)
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
		</div>
	</div>