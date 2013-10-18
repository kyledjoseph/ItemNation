
		<h2>deployments</h2>

		<?php if (empty($deployment_payloads)): ?>
		<p>no accounts to display</p>
		
		<?php else: ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>

			<?php foreach ($deployment_payloads as $deployment_payload): ?>

			<tbody>
				<tr>
					<td><?= Html::anchor("admin/deploy/view/{$deployment_payload->id}", $deployment_payload->id) ?></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>

			<?php endforeach; ?>

		</table>
		<?php endif; ?>