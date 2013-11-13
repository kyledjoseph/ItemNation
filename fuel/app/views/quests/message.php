							<div class="message">
								<div class="name"><?= $message->user->display_name() ?></div>
								<div class="content">
									<?= $message->body ?>
									<div class="time"><?= $message->time_ago() ?></div>
								</div>
							</div>