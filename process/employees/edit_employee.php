<?php   

// session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
// session_start();

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// // Required includes
// require $sDocRoot."/includes/connect.php";

// // Set POST data
// $emp_employee_id  = $_POST['employee_id'];
// $emp_first_name   = $_POST['employee_first_name'];
// $emp_middle_name  = $_POST['employee_middle_name'];
// $emp_last_name 	  = $_POST['employee_last_name'];
// $emp_gender 	  = $_POST['employee_gender'];
// $emp_civil_status = $_POST['employee_civil_status'];
// $emp_birth_date   = $_POST['employee_birth_date'];
// $emp_department   = $_POST['employee_department'];
// $emp_designation  = $_POST['employee_designation'];
// $emp_brand 		  = $_POST['employee_brand'];
// $emp_location 	  = $_POST['employee_location'];
// $emp_start_date   = $_POST['employee_start_date'];

// $query = 	'UPDATE
// 				emp_table
// 			SET
// 				emp_id = "'.mysqli_real_escape_string($conn,$emp_employee_id).'",
// 				first_name = "'.mysqli_real_escape_string($conn,$emp_first_name).'",
// 				middle_name = "'.mysqli_real_escape_string($conn,$emp_middle_name).'",
// 				last_name = "'.mysqli_real_escape_string($conn,$emp_last_name).'",
// 				gender = "'.mysqli_real_escape_string($conn,$emp_gender).'",
// 				civil_status = "'.mysqli_real_escape_string($conn,$emp_civil_status).'",
// 				birth_date = "'.mysqli_real_escape_string($conn,$emp_birth_date).'",
// 				department = "'.mysqli_real_escape_string($conn,$emp_department).'",
// 				designation = "'.mysqli_real_escape_string($conn,$emp_designation).'",
// 				brand = "'.mysqli_real_escape_string($conn,$emp_brand).'",
// 				location = "'.mysqli_real_escape_string($conn,$emp_location).'",
// 				start_date = "'.mysqli_real_escape_string($conn,$emp_start_date).'"
// 			WHERE
// 				emp_id = "'.$emp_employee_id.'"';

// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $query)) {

//     mysqli_stmt_execute($stmt);		
//     mysqli_stmt_close($stmt);		

// }
// else {

// 	echo mysqli_error($conn);
// 	exit;

// };

// // Head back
// header('location: /employees/details/?emp_id='.$emp_employee_id);
// exit;

?>
