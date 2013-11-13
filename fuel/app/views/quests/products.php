				<h4>Products</h4>
				
				<?php if (count($quest_products) > 0): ?>
				<?= Form::open(array('id' => 'sort_quest_by', 'class' => 'inline-block submit-on-change', 'method' => 'GET', 'action' => $quest->url())) ?>
				<?= Form::select('order', $quest->active_sort(), $quest->sort_options(), array('class' => 'form-control')) ?>
				<?= Form::close() ?>
				<?php endif; ?>
		
				<?php if (isset($user)): ?>
				<button style="position: relative; top: -58px;" href="#addProductModal" data-toggle="modal" class="btn btn-primary pull-right mx-install-extension">Add Product</button>
				<?php else: ?>
				<button style="position: relative; top: -58px;" href="#registerModal" data-toggle="modal" class="btn btn-primary pull-right">Add Product</button>
				<?php endif; ?>
		
				<div class="clear pad-bottom"></div>
				<div class="row product-rows">
					
					<?php $product_i = 1; foreach ($quest_products as $quest_product): ?>
					<?php $product = $quest_product->product ?>
					
					<div class="col-12 col-sm-4 col-lg-3 quest-product-square no<?= ! $quest_product->was_added_by_owner() ? 'from-tab' : null ?>" data-product="<?= $product_i ?>" data-product-id="<?= $quest_product->id ?>">
						<div class="quest-border">
							<div class="added-by<?php if (! $quest_product->was_added_by_owner()): ?> dark-grey<?php endif; ?>">
								<?php if (! $quest_product->was_added_by_owner()): ?>
								<i class="icon-user"></i> Added by <?= $quest_product->user->display_name() ?>
								<?php endif; ?>
								&nbsp;
							</div>
							<a href="<?= $product->product_url() ?>" class="quest-product-image-div" style="background-image:url(<?= $product->image_url(250, 220) ?>)" target="_blank">
								<div class="product-name">
									<div class="title-box"><?= $product->name() ?></div>
									<span class="faded">
										<?= $product->price() ?>
									</span>
								</div>
							</a>
			
							<?php if (isset($user) and $quest->belongs_to_user($user)): ?>
							<a class="close quest-page" href="<?= $quest_product->remove_url() ?>">&times;</a>
							<?php endif; ?>
		
							<div class="row product-info">
								<div class="pull-left fix-pull-left">
									<?php if (isset($user)): ?>
									<a class="no-dec" href="#commentsFor<?= $product_i ?>" data-toggle="modal">
										<span class="badge"><?= $quest_product->total_comments() ?></span>
										<i class="icon-comments-alt faded icon-large"></i>
									</a>
									<?php else: ?>
									<a class="no-dec" href="#registerModal" data-toggle="modal">
										<span class="badge"><?= $quest_product->total_comments() ?></span>
										<i class="icon-comments-alt faded icon-large"></i>
									</a>
									<?php endif; ?>
								</div>
								
								<div class="quest-product-votes pull-right fix-pull-right">
									<?= View::forge('quests/votes', array('quest_product' => $quest_product)) ?>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade comment-modal" id="commentsFor<?= $product_i ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title"><?= $product->name() ?></h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-12 col-sm-6 clearfix">
											<a href="<?= $product->product_url() ?>" class="quest-product-image-div" target="_blank">
												<img class="max-height-half" src="<?= $product->image_url(250, 220) ?>" />
												<br /><br />
												<?= $product->name() ?>
												<br />
												<span class="faded"><?= $product->price() ?></span>
											</a>
										</div>

										<div class="col-12 col-sm-6">
											<div class="product-comments">
												<?php foreach ($quest_product->get_comments() as $comment): ?>
												<?= View::forge('quests/comment', array('comment' => $comment)) ?>
												<?php endforeach; ?>
											</div>

											<?= Form::open(array('action' => $quest_product->comment_url(), 'class' => 'comment', 'data-product-id' => $quest_product->id)) ?>
												<div class="input-group">
													<input name="comment" type="text" class="form-control" placeholder="What do you think?">
													<span class="input-group-btn">
														<button class="btn btn-default" type="submit">Comment</button>
													</span>
												</div>
											<?= Form::close() ?>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->

					<?php $product_i++; endforeach; ?>

					<div class="col-12 col-sm-4 col-lg-3 quest-product-square no" data-product="<?= $product_i ?>">
						<div class="quest-border">
							<div class="added-by">&nbsp;</div>
							<?php if (isset($user)): ?>
							<a style="border: 2px dashed #aaa; background-image:url(/assets/img/add-product.png)" href="#addProductModal" class="quest-product-image-div mx-install-extension" data-toggle="modal"></a>
							<?php else: ?>
							<a style="border: 2px dashed #aaa; background-image:url(/assets/img/add-product.png)" href="#registerModal" class="quest-product-image-div" data-toggle="modal"></a>
							<?php endif; ?>
						</div>
					</div>
				</div>