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
				<?php if (isset($user) and $quest->belongs_to_user($user)): ?>
				<script type="text/javascript">
				var self_quest = true;
				</script>
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
		<?php if (isset($user) and $quest->belongs_to_user($user, false)): ?>
		<div class="pushups">

		<div class="ajax-action btn-group marg-bottom full-width public-private-radios" >
			<?php if ($quest->is_public): ?>
				<label class="private-public btn btn-primary active" style="width:50%">
					<span href="<?= Uri::create($quest->url("access/public")) ?>"><i class="icon-unlock icon-large"></i>&nbsp;&nbsp;&nbsp;Public</span>
				</label>
				<label class="private-public btn btn-primary" style="width:50%">
					<span href="<?= Uri::create($quest->url("access/private")) ?>"><i class="icon-lock icon-large"></i>&nbsp;&nbsp;&nbsp;Private</span>
				</label>
			<?php else: ?>
				<label class="private-public btn btn-primary" style="width:50%">
					<span class="" href="<?= Uri::create($quest->url("access/public")) ?>"><i class="icon-unlock icon-large"></i>&nbsp;&nbsp;&nbsp;Public</span>
				</label>
				<label class="private-public btn btn-primary active" style="width:50%">
					<span class="" href="<?= Uri::create($quest->url("access/private")) ?>"><i class="icon-lock icon-large"></i>&nbsp;&nbsp;&nbsp;Private</span>
				</label>
			<?php endif; ?>
		</div>

			<button id="fb_share" class="marg-bottom push-center btn btn-fb btn-block push-center quest-invite"
			data-picture="<?= $quest->default_thumb_url() ?>"
			data-link="<?= $quest->full_url() ?>"
			data-name="Help me find a <?= $quest->name ?>"
			data-caption="ShopGab - Shop Socially!"
			data-description="<?= $user->display_name() ?> is trying to find a <?= $quest->name ?> through ShopGab and has requested your input! Please click on the link below to see their page and join in the search. Thanks!"><i class="icon-facebook icon-large"></i>&nbsp;&nbsp;&nbsp;Post to timeline</button>
			<?php if (Fuel::$env == 'production'): ?>
				<button id="fb_invite" class="btn btn-primary btn-fb btn-block push-center quest-message" href="" data-link="<?= $quest->full_url() ?>"><i class="icon-facebook icon-large"></i>&nbsp;&nbsp;&nbsp;Message friends</button>
			<?php else: ?>
				<button id="fb_invite" class="btn btn-primary btn-fb btn-block push-center quest-message" href="" data-link="http://test.shopgab.com/<?= Uri::string(); ?>"><i class="icon-facebook icon-large"></i>&nbsp;&nbsp;&nbsp;Message friends</button>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
</div>

