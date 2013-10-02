
		<h2>analytics</h2>

		<p>dispaying results <?= $analytics->report_title() ?></p>

		<li>total_signups: <?= $analytics->total_signups() ?></li>
		<li>total_quests_created: <?= $analytics->total_quests_created() ?></li>
		<li>total_emails_sent: <?= $analytics->total_emails_sent() ?></li>
		<li>total_product_comments: <?= $analytics->total_product_comments() ?></li>
		<li>total_quest_messages: <?= $analytics->total_quest_messages() ?></li>
		<li>total_comments_added: <?= $analytics->total_comments_added() ?></li>

		<hr>

		<p>
			download csv: 
			<?= Html::anchor("admin/analytics/csv/users?start={$analytics->start_date()}", 'user signups') ?> | 
			<?= Html::anchor("admin/analytics/csv/quests?start={$analytics->start_date()}", 'new quests') ?> | 
			<?= Html::anchor("admin/analytics/csv/messages?start={$analytics->start_date()}", 'quest messages') ?> | 
			<?= Html::anchor("admin/analytics/csv/products?start={$analytics->start_date()}", 'products added') ?> | 
			<?= Html::anchor("admin/analytics/csv/comments?start={$analytics->start_date()}", 'product comments') ?>
		</p>