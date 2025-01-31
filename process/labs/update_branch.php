<meta charset="UTF-8">

<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set POST DATA
$lab_id = $_POST['lab_id_locked'];
$lab_name = str_replace(" ", "-", strtolower($_POST['lab_name']));
$lab_pos_code = strtoupper($_POST['lab_pos_code']);
$lab_address = $_POST['address'];
$lab_province = str_replace(" ", "-", strtolower($_POST['province']));
$lab_city = str_replace(" ", "-", strtolower($_POST['city']));
$lab_barangay = str_replace(" ", "-", strtolower($_POST['barangay']));
$lab_email_address = $_POST['email_address'];
$lab_phone_number = $_POST['phone_number'];
$lab_warehouse_code = $_POST['warehouse_code'];
$lab_zip_code = $_POST['zip_code'];

$query = 	'UPDATE
				labs_locations
			SET
				lab_name = "'.mysqli_real_escape_string($conn, $lab_name).'",
				lab_pos_code = "'.mysqli_real_escape_string($conn, $lab_pos_code).'",
				address = "'.mysqli_real_escape_string($conn, $lab_address).'",
				province = "'.mysqli_real_escape_string($conn, $lab_province).'",
				city = "'.mysqli_real_escape_string($conn, $lab_city).'",
				barangay = "'.mysqli_real_escape_string($conn, $lab_barangay).'",
				email_address = "'.mysqli_real_escape_string($conn, $lab_email_address).'",
				phone_number = "'.mysqli_real_escape_string($conn, $lab_phone_number).'",
				warehouse_code = "'.mysqli_real_escape_string($conn, $lab_warehouse_code).'",
				zip_code = "'.mysqli_real_escape_string($conn, $lab_zip_code).'"
			WHERE
				lab_id = "'.$lab_id.'"';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo '<script> window.location="/store-locations"; </script>';

?>
