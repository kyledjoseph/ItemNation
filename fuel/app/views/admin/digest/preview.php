

	<h2><?= count($digests) . ' pending for ' . $date ?></h2>
	<?= Html::anchor('admin/digest/send', 'send digests', array('class' => 'btn btn-primary')) ?>
	<hr>
	
	<?php foreach ($digests as $digest): ?>

		<h4><?= $digest->user->display_name() ?> (<?= $digest->user->email ?>) <?= $digest->total_notifications() ?></h4>
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
	