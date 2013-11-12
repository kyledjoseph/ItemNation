<h2>Deployment #<?= $deployment_payload->id ?></h2>
<hr>

<h3>Basic Info</h3>
<table class="table table-bordered">
	<tbody>
		<tr>
			<th>Request Address</th>
			<td><?= $deployment_payload->ip ?></td>
		</tr>
		<tr>
			<th>Received At</th>
			<td><?= $deployment_payload->date() ?> (<?= $deployment_payload->ago() ?>)</td>
		</tr>
		
	</tbody>
</table>

<br>

<h3>Event Log</h3>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Type</th>
			<th>Details</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; foreach ($deployment_payload->get_logs() as $log): ?>
		<tr class="<?= $log->display_class_type() ?>">
			<td><?= $i ?></td>
			<td><?= $log->type ?></td>
			<td><pre><?= $log->text ?></pre></td>
			<td><?= $log->date() ?></td>
		</tr>
		<?php $i++; endforeach; ?>

	</tbody>
</table>

<br>

<h3>Github Payload</h3>
<pre>
<?= $deployment_payload->pretty_data() ?>
</pre>