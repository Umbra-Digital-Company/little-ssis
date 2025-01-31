<meta charset="UTF-8">

<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// User Type
switch (strtolower($_POST['user_type'])) {

	case 'dispatch':
		$position = 'store';
		$isadmin  = 3;
		break;

	case 'lab':
		$position = 'laboratory';
		$isadmin  = 3;
		break;

	case 'admin':
		$position = 'admin';
		$isadmin  = 1;
		break;
	case 'supervisor':
			$position = 'supervisor';
			$isadmin  = 5;
			break;
			case 'aim-auditor':
				$position = 'aim-auditor';
				$isadmin  = 15;
				break;		
	
};
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
if(isset($_POST['store_names'])){
$stores_handled=implode(',',$_POST['store_names']);
}else{
$stores_handled="";
}
// Name
$fullname = $_POST['fname'].' '.$_POST['mname'].' '.$_POST['lname']; 

// Store / Lab
if(isset($_POST['branch_code'])) {

	$localeID = $_POST['branch_code'];

}
else {

	$localeID = $_POST['lab_code'];

};

$s_pass 	  = openssl_random_pseudo_bytes(32, $cstrong);
$new_password = $s_pass.$_POST['password'];
$new_password = password_hash($new_password, PASSWORD_DEFAULT);

 echo $query = 	'UPDATE
				users
			SET
				username = "'.mysqli_real_escape_string($conn,$_POST['username']).'",
				`password` = "'.mysqli_real_escape_string($conn,$new_password).'",
				first_name = "'.mysqli_real_escape_string($conn,$_POST['fname']).'",
				middle_name = "'.mysqli_real_escape_string($conn,$_POST['mname']).'",
				last_name = "'.mysqli_real_escape_string($conn,$_POST['lname']).'",
				isadmin = "'.$isadmin.'",
				`position` = "'.$position.'",
				store_code = "'.mysqli_real_escape_string($conn,$localeID).'",
				s_pass = "'.$s_pass.'",
				init_pass = "'.mysqli_real_escape_string($conn,$_POST['password']).'",
				store_location="'.mysqli_real_escape_string($conn,$stores_handled).'"
			WHERE
				id = '.$_POST['id'];				

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}

echo "<script> window.location='/user-management/edit-user/?user_id=".$_POST['id']."&user_username=".$_POST['username']."'; </script>";

?>
