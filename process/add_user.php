<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

$new_password = $_POST["password2"];
$new_password = $_POST["confirmPassword2"];
$s_pass 	  = openssl_random_pseudo_bytes(32, $cstrong);
$new_password = $s_pass.$new_password;
$new_password = password_hash($new_password, PASSWORD_DEFAULT);
	
$stmt = mysqli_stmt_init($conn);

// User Type
switch (strtolower($_POST['user_type'])) {

	case 'dispatch':
		$position = 'store';
		break;

	case 'lab':
		$position = 'laboratory';
		break;

	case 'admin':
		$position = 'admin';
		break;
	
};

// Name
$fullname = $_POST['fname'].' '.$_POST['mname'].' '.$_POST['lname']; 

// Store / Lab
if(isset($_POST['branch_code'])) {

	$localeID = $_POST['branch_code'];

}
else {

	$localeID = $_POST['lab_code'];

};

$queryUser = 	"INSERT INTO 
					users(
						username,
						`password`,
						first_name,
						middle_name,
						last_name,
						isadmin,
						`position`,
						store_code,
						s_pass,
						init_pass
					)
				VALUES(
					'".mysqli_real_escape_string($conn,$_POST['username'])."',
					'".$new_password."',
					'".mysqli_real_escape_string($conn,$_POST['fname'])."',
					'".mysqli_real_escape_string($conn,$_POST['mname'])."',
					'".mysqli_real_escape_string($conn,$_POST['lname'])."',
					'3',
					'".$position."',
					'".mysqli_real_escape_string($conn,$localeID)."',
					'".$s_pass."',
					'".mysqli_real_escape_string($conn,$_POST['password2'])."'		
				)";

// echo '<pre>';
// echo $queryUser;
// echo '</pre>';	

if (mysqli_stmt_prepare($stmt, $queryUser)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}

echo "<script> window.alert('Succesfully Created');	window.location='../createuser/'; </script>";

?>
