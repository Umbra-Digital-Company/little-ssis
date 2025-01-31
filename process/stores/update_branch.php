<meta charset="UTF-8">

<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set POST DATA
$store_id = $_POST['store_id_locked'];
$store_name = strtoupper($_POST['store_name']);
$store_lab_id = $_POST['lab_id'];
$store_address = $_POST['address'];
$store_province = str_replace(" ", "-", strtolower($_POST['province']));
$store_city = str_replace(" ", "-", strtolower($_POST['city']));
$store_barangay = str_replace(" ", "-", strtolower($_POST['barangay']));
$store_email_address = $_POST['email_address'];
$store_phone_number = $_POST['phone_number'];

$store_warehouse_code = $_POST['warehouse_code'];
$store_zone = $_POST['zone'];
$store_location = $_POST['location'];
$store_name_proper = $_POST['store_name_proper'];
$store_name_title = $_POST['store_name_title'];
$store_country = $_POST['country'];
$store_time_open = $_POST['time_open'];
$store_time_close = $_POST['time_close'];

$query = 	'UPDATE
				stores_locations
			SET
				store_name = "'.mysqli_real_escape_string($conn, $store_name).'",
				lab_id = "'.mysqli_real_escape_string($conn, $store_lab_id).'",
				address = "'.mysqli_real_escape_string($conn, $store_address).'",
				province = "'.mysqli_real_escape_string($conn, $store_province).'",
				city = "'.mysqli_real_escape_string($conn, $store_city).'",
				barangay = "'.mysqli_real_escape_string($conn, $store_barangay).'",
				email_address = "'.mysqli_real_escape_string($conn, $store_email_address).'",
				phone_number = "'.mysqli_real_escape_string($conn, $store_phone_number).'",

                warehouse_code = "'.mysqli_real_escape_string($conn, $store_warehouse_code).'",
				zone = "'.mysqli_real_escape_string($conn, $store_zone).'",
				location = "'.mysqli_real_escape_string($conn, $store_location).'",
				store_name_proper = "'.mysqli_real_escape_string($conn, $store_name_proper).'",
				store_name_title = "'.mysqli_real_escape_string($conn, $store_name_title).'",
				country = "'.mysqli_real_escape_string($conn, $store_country).'",
				time_open = "'.mysqli_real_escape_string($conn, $store_time_open).'",
				time_close = "'.mysqli_real_escape_string($conn, $store_time_close).'"
			WHERE
				store_id = "'.$store_id.'"';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

$query = 	'UPDATE
				store_codes
			SET
				branch = "'.mysqli_real_escape_string($conn, $store_name).'"
				
			WHERE
				location_code = "'.$store_id.'"';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo '<script> window.location="/store-locations/"; </script>';

?>
