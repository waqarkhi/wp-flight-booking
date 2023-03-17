<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style type="text/css">
	img {max-width: 100%;}
	#pdf iframe {width:100%;height:1000px}
</style>
<?php 
	global $wpdb;
	$table = FLIGHT_BOOKING_TAB;
	$id = @$_GET['id'];
	$res = $wpdb->get_results("SELECT * FROM $table WHERE id=$id", ARRAY_A)[0];
	$res['data'] = json_decode($res['data'], true);
	$flight_id = $res['data']['flight_id'];
	$res['flight'] = $wpdb->get_results("SELECT * FROM $table WHERE id = $flight_id", ARRAY_A)[0];
	$res['flight']['data'] = json_decode($res['flight']['data'], true);

?>
<h1>Booked Flight</h1>

<div class="row">
	<div class="col-md-8">
		<table class="table table-bordered">
			<tr><th>Name</th><td><?= $res['title']; ?></td></tr>
			<tr><th>Email</th><td><?= $res['data']['email']; ?></td></tr>
			<tr><th>Phone</th><td><?= @$res['data']['phone']; ?></td></tr>
			<tr><th>Company</th><td><?= @$res['data']['company']; ?></td></tr>
			<tr><th>Booking Date</th><td><?= date_form($res['create_time']); ?></td></tr>
			<tr><th>Flight Title</th><td><?= $res['flight']['title']; ?></td></tr>
			<tr><th>Location</th><td><?= $res['flight']['data']['from'] .' to '.$res['flight']['data']['to']; ?></td></tr>
			<tr><th>Flight Date</th><td><?= date_form(strtotime($res['flight']['data']['date'])); ?></td></tr>
		</table>
	</div>
	<div class="col-md-4">
		<img id="profile" src="<?= get_pic($res['id']); ?>">
		<button id="ticket" class="btn btn-dark btn-block">Download Ticket</button>
	</div>
</div>
<div class="container" id="pdf"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		var doc = window.jspdf.jsPDF();
		function generatePdf() {
			var date = '<?= $res['flight']['data']['date']; ?>';
			doc.addImage("<?= FLIGHT_BOOKING_URL.'upload/	' ?>ticket.jpg", "JPEG", 0, 0, 0, 280);
			doc.addImage($('#profile').attr('src'), "JPEG", 167, 83, 25, 0);

			doc.setTextColor('#0c1933');
			doc.text('<?= $res['title']; ?>'.toUpperCase(), 114, 101);

			doc.setFontSize(10);
			doc.text('<?= @$res['data']['phone']; ?>', 166.5, 126);

			doc.text(moment(date).format('ll') + "\n" + moment(date).format('LT'), 166.5, 143);

			doc.text('<?= $res['data']['email']; ?>', 114, 154);

			doc.text('<?= @$res['data']['company']; ?>', 114, 143);

			doc.text('<?= $res['flight']['data']['from'];?>'.toUpperCase(), 128, 121.5);
			doc.text('<?= $res['flight']['data']['to'];?>'.toUpperCase(), 122, 126.5);

/*			var pdf = btoa(doc.output());
			// $('#pdf').after(pdf);
			return pdf;
*/			
			var url = '<?= FLIGHT_BOOKING_URL.'upload.php'; ?>',
                pdf = doc.output("blob"),
                data = new FormData();
			data.append('file', pdf);
			data.append('name', '<?= 'ticket-no-'.$res['id']; ?>');
			$.ajax({
				url: "<?= FLIGHT_BOOKING_URL.'upload.php'; ?>",
				type:'POST',
				contentType:'multipart/form-data',
				data: data,
				contentType: false,
				processData: false,
				success: function (res) { 
				    var pdf = '<?= FLIGHT_BOOKING_URL;?>'+res.file;
				    $('#pdf').html('<iframe src="<?= FLIGHT_BOOKING_URL;?>'+res.file+'"></iframe>')
				    var msg = "This is your Ticket \n \n"+pdf;
				    $.post('?action=send-ticket', {to:'<?= $res['data']['email']; ?>', sub:"<?= $res['flight']['title']; ?> - Ticket", msg: msg})
					console.log(res);
				}
			});

		    // doc.save('<?= $res['title'];?>-ticket.pdf');
		};
			
		$('#ticket').on('click', generatePdf);
		
	});
</script>