											<div class="comment text">
												<div class="name"><?= $comment->user->display_name() ?></div>
												<div class="content">
													<div class="message"><?= $comment->comment ?></div>
													<div class="time"><?= $comment->time_ago() ?></div>
												</div>
											</div>