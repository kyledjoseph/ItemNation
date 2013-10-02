

	<h2><?= $total_digests . ' pending for ' . $date ?></h2>
	

	<?php if ($total_digests > 0): ?>
	<?= Html::anchor("admin/digest/send/{$date}", 'send digests', array('class' => 'btn btn-primary')) ?>
	<?php endif; ?>

	<hr>
	
	<?php foreach ($digests as $digest): ?>

		<h4><?= Html::anchor($digest->user->admin_url(), $digest->user->display_name()) ?> (<?= $digest->user->email ?>)</h4>
		<p><?= ! $digest->user->receives_notification('digest') ? '&gt; user does not receive notifications' : null ?></p>
		<p><?= $digest->title() ?></p>
		<p>
			<?php foreach ($digest->get_quests() as $quest): ?>
				<?= Html::anchor($quest->full_url(), $quest->name()) ?>
				<ul>
				<?php foreach ($quest->get_notifications_on_date($date) as $notification): ?>
					<li><?= $notification->text() ?></li>
				<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		</p>

		<hr>

	<?php endforeach; ?>
	