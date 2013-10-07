
					<div class="row pad-top">
						<?= Html::anchor($quest->url(), $quest->name) ?>
						<?= $quest->purchase_by_relative() ?>

						<?php foreach ($quest->get_quest_products() as $quest_product): ?>
							<li><?= Html::anchor($quest_product->url('close'), $quest_product->product->name) ?></li>
						<?php endforeach; ?>
					</div>