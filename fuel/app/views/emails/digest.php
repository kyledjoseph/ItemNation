
	<h2><?= $digest->title() ?></h2>
	<p>
		<?php foreach ($digest->get_quests() as $quest): ?>
			<?= Html::anchor($quest->full_url(), $quest->name()) ?>
			<ul>
			<?php foreach ($quest->get_notifications_on_date($digest->date) as $notification): ?>
				<li><?= $notification->text() ?></li>
			<?php endforeach; ?>
			</ul>
		<?php endforeach; ?>
	</p>