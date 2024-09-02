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
				phone_number = "'.mysqli_real_escape_string($conn, $store_phone_number).'"
			WHERE
				store_id = "'.$store_id.'"';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo '<script> window.location="/store-locations/edit-store/?id='.$store_id.'"; </script>';

?>
