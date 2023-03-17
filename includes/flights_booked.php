<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>.card {max-width:100%;}</style>
<h1>Booked Flights</h1>

<?php
	global $wpdb;
	$table = FLIGHT_BOOKING_TAB;
	$flights = $wpdb->get_results("SELECT * FROM $table WHERE type = 'seat' ORDER BY id DESC");

?>
<div class="card">
	<div class="card-body">
	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Flight</th>
				<th>Booked at</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($flights as $f): ?>
				<?php $data = json_decode($f->data); ?>
				<tr>
					<td><?= $f->title ?></td>
					<td><?= $data->email ?></td>
					<td><?= $data->flight_id ?></td>
					<td>
						<?= date('F d, Y, h:ia', $f->create_time); ?>		
					</td>
					<td>
						<a class="btn btn-primary" href="<?= $_SERVER['SCRIPT_NAME']; ?>?page=flight&view=1&id=<?=$f->id; ?>">View</a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	</div>
</div>
