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