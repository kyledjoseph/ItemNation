									<?php if (isset($user)): ?>
									
									<span class="badge"><?= $quest_product->total_likes() ?></span>
									<a href="<?= Uri::create($quest_product->like_url()) ?>" class="user_product_vote" title="<?= $quest_product->list_user_likes() ?>" data-product-id="<?= $quest_product->id ?>" data-vote-type="like">
										<i class="icon-thumbs-up-alt faded no-dec icon-large"></i>
									</a> &nbsp;
									
									<span class="badge"><?= $quest_product->total_dislikes() ?></span>
									<a href="<?= Uri::create($quest_product->dislike_url()) ?>" class="user_product_vote" title="<?= $quest_product->list_user_dislikes() ?>" data-product-id="<?= $quest_product->id ?>" data-vote-type="dislike">
										<i class="icon-thumbs-down-alt faded no-dec icon-large"></i>
									</a>
									
									<?php else: ?>
									
									<span class="badge"><?= $quest_product->total_likes() ?></span>
									<a href="#registerModal" data-toggle="modal">
										<i class="icon-thumbs-up-alt faded no-dec icon-large mx-rate"></i>
									</a> &nbsp;
									
									<span class="badge"><?= $quest_product->total_dislikes() ?></span>
									<a href="#registerModal" data-toggle="modal">
										<i class="icon-thumbs-down-alt faded no-dec icon-large mx-rate"></i>
									</a>

									<?php endif; ?>