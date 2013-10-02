<div class="row">
		<div class="col-12 col-sm-4">
		<h2 class="header-underline">My Account</h2>
		<?= Form::open(array('class' => 'form-horizontal')) ?>
			
			<div class="control-group account-form">
				<label class="control-label" for="name">Name:</label>
				<div class="controls">
					<input type="text" class="form-control" name="name" id="inputName" placeholder="John Smith" value="<?= $user->display_name() ?>">
				</div>
			</div>
			
			<div class="control-group account-form">
				<label class="control-label" for="email">Email:</label>
				<div class="controls">
					<input type="text" class="form-control" name="email" id="inputEmail" placeholder="Email" value="<?= $user->email ?>">
				</div>
			</div>
			
			<div class="control-group account-form">
				<label class="control-label" for="inputEmail">Facebook:</label>
				<div class="controls">
					<span class="facebook-account"><?= $user->is_authenticated_with('facebook') ? "Authenticated as {$user->user_authentication('facebook')->email}" : Html::anchor('auth/facebook?r=account', 'Login with Facebook') ?></span>
				</div>
			</div>

			<div class="control-group account-form">
				<div class="controls">
					<button class="btn btn-default marg-top">Update Account</button>
				</div>
			</div>
		<?= Form::close() ?>
		
		<hr>

		<h3 class="header-underline">Notifications</h3>
		<?= Form::open(array('action' => 'account/notifications', 'class' => 'form-horizontal')) ?>
			<div class="control-group account-form">
				<div class="controls">
					<input type="checkbox" name="digest" value="1" <?= $user->receives_notification('digest') ? 'checked="checked"' : null ?>> Daily Digest
				</div>
			</div>
			<div class="control-group account-form">
				<div class="controls">
					<button class="btn btn-default marg-top">Update Notifications</button>
				</div>
			</div>
		<?= Form::close() ?>

		<hr>

		<h3 class="header-underline">Profile Picture</h3>
		<?= Form::open(array('action' => 'account/avatar', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal')) ?>
			<div class="control-group account-form">
				<label class="control-label" for="inputName"></label>
				<div class="controls">
					<img class="account-img" src="<?= $user->get_avatar_uri() ?>" />

				</div>
				<label class="control-label" for="avatar">Change avatar:</label>
				<div class="controls">
					<input class="avatar-upload" type="file" name="avatar" id="avatar" />
				</div>
			</div>
			<div class="control-group account-form">
				<div class="controls">
					<button class="btn btn-default marg-top">Upload Avatar</button>
				</div>
			</div>

			<?php if ($user->is_authenticated_with('facebook') and $user->avatar_type() !== 'facebook'): ?>

			<div class="control-group account-form">
				<div class="controls">
					<?= Html::anchor('user/avatar/use/facebook', 'or use your Facebook profile picture') ?>
				</div>
			</div>

			<?php endif; ?>

		<?= Form::close() ?>
		
		<hr>

		<h3 class="header-underline">Password</h3>
		<?= Form::open(array('class' => 'form-horizontal')) ?>
			<?php if ($user->has_password()): ?>

			<div class="control-group">
				<div class="controls">
					<span class="facebook-account"><?= Html::anchor('account/password', 'Change Password') ?></span>
				</div>
			</div>

			<?php else: ?>

			<div class="control-group">
				<div class="controls">
					<span class="facebook-account"><?= Html::anchor('account/password', 'Set a Password') ?></span>
				</div>
			</div>

			<?php endif; ?>
		<?= Form::close() ?>
		</div>
	</div>