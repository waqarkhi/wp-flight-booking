<?php 

if (@$_POST) {
	foreach ($_POST as $k => $v) {$data[$k]=$v;}
}

switch (@$_REQUEST['name']) {
	case 'flight': add_flight($data);break;
	case 'seat': book_seat($data); break;
	default: echo 'Invalid Route.';break;
}


switch (@$_GET['name']) {
	case 'delete': delete_flight($_GET['id']);break;
	default:echo 'Invalid Route.';break;
}

function book_seat($data)
{
	header('content-type:application/json');
	global $wpdb;
	$flight = [
		'title' => $_POST['title'],
		'type' => $_REQUEST['name'],
		'data' => json_encode($data),
		'create_time' => time()
	];
	$wpdb->insert(FLIGHT_BOOKING_TAB, $flight);
	$res['id'] = $wpdb->insert_id;
	echo json_encode($res);
	die();
}

function add_flight($data)
{
	global $wpdb;
	$flight = [
		'title' => $_POST['title'],
		'type' => $_REQUEST['name'],
		'data' => json_encode($data)
	];

	if ($_POST['id'] == "") {
		$flight['create_time'] = time();
		$wpdb->insert(FLIGHT_BOOKING_TAB, $flight);
		$id = $wpdb->insert_id;
	} else {
		$id = $_POST['id'];
		$wpdb->update(FLIGHT_BOOKING_TAB, $flight, ['id'=>$id]);
	}
	send('flight&i='.$id);
}

function delete_flight($id)
{
	global $wpdb;
	$wpdb->delete(FLIGHT_BOOKING_TAB, ['id'=>$id]);
	send('flight-list');
}

function send($loc,$type='admin')
{
	if ($type == 'admin') {
		header('location: '.$_SERVER['SCRIPT_NAME'].'?page='.$loc);
	}
}