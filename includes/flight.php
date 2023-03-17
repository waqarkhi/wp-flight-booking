<style type="text/css">
.row {clear:both;}
.row div[class*="col"] {float:left;}
.col-6 {width:50%;}
.form-group {margin-bottom:20px;}
.form-group label, .form-group input {display:block;width:100%;}
form {max-width:600px;background-color:#fff;padding:30px;}
</style>

<?php if (@$_GET['view']==1): ?>
	<?php require_once 'view.php'; ?>
<?php else: ?>
	
<?php 
	if (@$_GET['i']) {
		global $wpdb;
		$id = $_GET['i'];
		$table = FLIGHT_BOOKING_TAB;
		$f = $wpdb->get_results("SELECT * FROM $table WHERE id = $id")[0];
		$fd = json_decode($f->data);
	}
?>
<h1>Add Flight</h1>
<form action="?action=flight-booking&name=flight" method="post">
	<input type="hidden" name="id" value="<?= @$f->id;?>">
	<div class="form-group">
		<label for="title">Title</label>
		<input type="text" name="title" value="<?= @$f->title;?>">
	</div>

	<div class="form-group">
		<label for="from">Flight From</label>
		<input type="text" name="from" value="<?= @$fd->from;?>">
	</div>

	<div class="form-group">
		<label for="to">Flight To</label>
		<input type="text" name="to" value="<?= @$fd->to;?>">
	</div>

	<div class="form-group">
		<label for="date">Flight Date</label>
		<input type="datetime-local" name="date" value="<?= @$fd->date;?>">
	</div>

	<div class="form-group">
		<label for="seats">Available Seats</label>
		<input type="number" name="seats" value="<?= @$fd->seats;?>">
	</div>

	<div class="form-group">
		<button class="button button-primary">Submit</button>
	</div>

</form>
<?php endif ?>