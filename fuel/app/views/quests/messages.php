				<h4>Chat</h4>
				<div class="row">
					<div class="col-12">
						<div class="chat">
							<?php if (empty($messages)): ?>
							<div class="message">No messages</div>
							
							<?php else: foreach ($messages as $message): ?>

							<?= View::forge('quests/message', array('message' => $message)) ?>
							
							<?php endforeach; endif; ?>
							
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<?= Form::open(array('id' => 'quest_message_form', 'action' => $quest->url('message'))) //, 'class' => 'mx-chat-form')) ?>
							<div class="input-group">
								<input id="quest_message_text" name="message" type="text" class="form-control" <?= isset($user) ? : 'href="#registerModal" data-toggle="modal"' ?>>
								<span class="input-group-btn">
									<button class="chat-button btn btn-default" type="submit" <?= isset($user) ? : 'href="#registerModal" data-toggle="modal"' ?>>Send</button>
								</span>
							</div>
						<?= Form::close() ?>
					</div>
				</div>