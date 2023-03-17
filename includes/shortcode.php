<?php 
	global $wpdb;
	$table = FLIGHT_BOOKING_TAB;
	$flights = $wpdb->get_results("SELECT * FROM $table WHERE type = 'flight' ORDER BY id ASC");
?>
<?php foreach ($flights as $f): ?>
	<?php 
		$f->data = json_decode($f->data);
		$fd = $f->data; 
	?>
	<div class="card">
		<div class="card-header">
			<h4><?= $f->title; ?></h4>
		</div>
		<div class="card-body">
			<p>
				From: <?= $fd->from; ?> <br>
				To: <?= $fd->to; ?> <br>
			</p>
		</div>
		<div class="card-footer">
			<div class="float-left">
				Time: <?= date('F d, Y, h:ia', strtotime($fd->date)); ?>
			</div>
			<div class="float-right text-right">
				<span class="info" style="display:none"><?= json_encode($f) ?></span>
				<button data-toggle="modal" data-target="#flight" class="btn btn-dark btn-sm">Book Now</button>
			</div>
		</div>
	</div>
<?php endforeach ?>
 <div class="modal" id="flight">
    <div class="modal-dialog">
      <div class="modal-content">
      
          <form method="post" id="flight_form">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          	<input type="hidden" name="flight_id" value="">
          	<div class="form-group">
	          	<label for="fn">First Name</label>
	          	<input id="fn" type="text" name="firstname">
          	</div>
          	<div class="form-group">
	          	<label for="ln">Last Name</label>
	          	<input id="ln" type="text" name="lastname">
          	</div>

          	<div class="form-group">
	          	<label for="cm">Company</label>
	          	<input id="cm" type="text" name="company">
          	</div>

          	<div class="form-group">
	          	<label for="ph">Phone</label>
	          	<input id="ph" type="text" name="phone">
          	</div>


						<div class="form-group">
	          	<label for="em">Email Address</label>
	          	<input id="em" type="email" name="email">
          	</div>
          	<div class="form-group">
	          	<label for="pic">upload</label>
	          	<input id="pic" type="file" name="pic">
          	</div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-dark">Submit</button> <br>
        </div>
          </form>
        
      </div>
    </div>
  </div>
  <script type="text/javascript">
  	jQuery(document).ready(function ($) {
  		$('.btn[data-toggle="modal"]').on('click', function () {
  			var info = JSON.parse($(this).prev('.info').html());
  			$('.modal-title').html(info.title);
  			$('[name="flight_id"]').val(info.id);
  		});

  		$('#flight_form').on('submit', function (e) {
  			e.preventDefault();
  			var data = {};
  			data.firstname = $('[name="firstname"]').val();
  			data.lastname = $('[name="lastname"]').val();
  			data.email = $('[name="email"]').val();
  			data.company = $('[name="company"]').val();
  			data.phone = $('[name="phone"]').val();
  			data.flight_id = $('[name="flight_id"]').val();
  			data.title = data.firstname +' '+ data.lastname;

  			$.post('?action=flight-booking&name=seat', data, function (res) {
  				console.log(res);
  				var data = new FormData(), file = $('#pic')[0].files[0];
					data.append('file', file);
					data.append('name', res.id);
					$.ajax({
						url: "<?= FLIGHT_BOOKING_URL.'upload.php'; ?>",
						type:'POST',
						contentType:'multipart/form-data',
						data: data,
						contentType: false,
						processData: false,
						success: function (res) { 
							$('[for="pic"]').html('<img src="<?= FLIGHT_BOOKING_URL ?>' + res.file + '">');
							$('#flight_form .modal-footer').append('<p class="alert alert-success">Your Request has been submitted.</p>');
							$('#flight_form .btn[type="submit"]').hide();
							$('#flight_form #pic').hide();
						}
					});
  			})


  		})
  	})
</script>