<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>.card {max-width:100%;}</style>
<h1>Flights</h1>
<div>
Add this code display flights: <code>[flight_booking]</code>
</div>
<?php
	global $wpdb;
	$table = FLIGHT_BOOKING_TAB;
	$flights = $wpdb->get_results("SELECT * FROM $table WHERE type = 'flight' ORDER BY id DESC", ARRAY_A);

	for ($i=0; $i <sizeof($flights); $i++) { 
		$id = $flights[$i]['id'];
		$fd = json_decode($flights[$i]['data'], true);
		$fid = '"flight_id":"'.$id.'"';
		$booked =  $wpdb->get_results("SELECT * FROM $table WHERE data LIKE '%$fid%'");
		$fd['booked'] = count($booked);
		// $fd['booked'] = $id;
		$flights[$i]['data'] = $fd;
	}

?>
<div class="card">
	<div class="card-body">
	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>From</th>
				<th>To</th>
				<th>Time</th>
				<th>Available Seats</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($flights as $f): $seat = $f['data']['seats'];$book = $f['data']['booked']; ?>
				<tr>
					<td><?= $f['id'] ?></td>
					<td><?= $f['title'] ?></td>
					<td><?= $f['data']['from'] ?></td>
					<td><?= $f['data']['to'] ?></td>
					<td><?= date('F d, Y, h:ia', strtotime($f['data']['date'])) ?></td>
					<td><strong><?= ($seat-$book); ?></strong> Out of <?= $seat;?></td>
					<td>
						<a class="btn btn-warning" href="<?= bloginfo('url') ?>/wp-admin/admin.php?page=flight&i=<?=$f['id'] ?>">Edit</a>
						<a class="btn btn-danger" href="<?= $_SERVER['SCRIPT_NAME']; ?>?page=flight-list&name=delete&id=<?=$f['id'] ?>">Delete</a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	</div>
</div>



