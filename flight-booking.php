<?php 
/*
	Plugin Name: Flight Booking
	description: Book flight and download tickets 
	Version: 1.0.0
	Author: Waqar Ahmed
	Author URI: https://waqaronline.com/
*/


global $wpdb;
define('FLIGHT_BOOKING_URL', plugin_dir_url(__FILE__));
define('FLIGHT_BOOKING_PATH', plugin_dir_path(__FILE__));
define('FLIGHT_BOOKING_VERSION','1.0.0');
define('FLIGHT_BOOKING_TAB', $wpdb->prefix.'pg_flight_booking');

register_activation_hook(__FILE__, 'activate_flight_book');
add_action('wp_enqueue_scripts', 'flight_booking_scripts');
add_action('admin_menu','flight_booking_admin_pages');
add_shortcode('flight_booking', 'shortcode');
add_action('wp_head', 'bs_css');
add_action('wp_footer', 'bs_js');

function bs_css()
{
	echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';
}
function bs_js()
{
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>';
}

function get_pic($id)
{
	$files = ['.jpg','.jpeg','.png','.gif','.pdf'];
	$url = "";
	foreach ($files as $i) {
		$file = FLIGHT_BOOKING_PATH.'upload/'.$id.$i;
		if (file_exists($file)) { $url = FLIGHT_BOOKING_URL.'upload/'.$id.$i;}
	}
	return $url;
}

function date_form($time) { return date('F d, Y h:ia', $time); }

if (@$_GET['name']) { require_once('form-data.php'); }

if (@$_REQUEST['action']) {	
	switch ($_REQUEST['action']) {
		case 'flight-booking':
			require_once('form-data.php');
			break;

		case 'ticket':
			_send_mail();
			break;
		
		default:
			'INVALID ROUTE';
			break;
	}
}

if(@$_REQUEST['action']) {
	switch ($_REQUEST['action']) {
		case 'delete':delete();break;
		case 'send-ticket': send_mail();break;
		default: echo "Invalid";break;
	}
}

if (@$_GET['page'] == 'flight-view') { view(); }

function activate_flight_book()
{
	global $wpdb;
	$table = FLIGHT_BOOKING_TAB;
	$sql = "CREATE TABLE $table(
		id int(11) NOT NULL AUTO_INCREMENT,
		title varchar(255) DEFAULT '',
		type varchar(255) DEFAULT '',
		data longtext,
		create_time varchar(255) DEFAULT '',
		PRIMARY KEY (id)
	)";
	require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
	dbDelta($sql);
}



function flight_booking_scripts()
{
	wp_register_style('flight_booking_style', FLIGHT_BOOKING_URL.'/css/flight_booking.css',false,FLIGHT_BOOKING_VERSION);
	wp_enqueue_style('flight_booking_style');
	wp_enqueue_script('flight_booking_script', FLIGHT_BOOKING_URL .'/js/flight_booking.js', ['jquery'], FLIGHT_BOOKING_VERSION, true);
}

function flight_booking_admin_pages()
{
	add_menu_page('Flight Booking','Flight Booking', 'manage_options','flight-list', 'flights_list');
	add_submenu_page('flight-list','Flights','All Flights','manage_options', 'flight-list', 'flights_list');
	add_submenu_page('flight-list','Flights','Add Flight','manage_options', 'flight', 'flights_add');
	add_submenu_page('flight-list','Book Flights','Booked Flights','manage_options', 'flight-booked', 'flights_booked');
}


function shortcode()
{
	require_once 'includes/shortcode.php';
}

function flights_list()
{
	require_once 'includes/flight_list.php';
}

function flights_add() { require_once 'includes/flight.php'; }

function flights_booked() { require_once 'includes/flights_booked.php'; }

function view() { require_once 'includes/view.php'; }

function send_mail() {
	header('content-type:application/json');
	$to = $_POST['to'];
	$sub = $_POST['sub'];
	$msg = $_POST['msg'];
	$headers = "From: test@thelogocrafters.com" . "\r\n"; //. "CC: waqar.tek@gmail.com";

 	$data['send'] = mail($to,$sub,$msg,$headers);
    echo json_encode($data);
    die();
}


?>
