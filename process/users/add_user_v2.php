<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

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
switch ( $_POST['user_type'] ) {

	// =================== Dashboard Account

	case 'admin':
		$position = 'admin';
		$posID = '1';
		$localeID = 'admin';
		break;

		case 'vn-admin':
			$position = 'vn-admin';
			$posID = '1';
			$localeID = 'vn-admin';
			break;

	case 'finance':
		$position = 'finance';
		$posID = '2';
		$localeID = 'finance';
		break;

	case 'profiler':
		$position = 'profiler';
		$posID = '6';
		$localeID = 'profiler';
		break;

	case 'sourcing':
		$position = 'sourcing';
		$posID = '6';
		$localeID = 'sourcing';
		break;

	case 'marketing':
		$position = 'marketing';
		$posID = '6';
		$localeID = 'marketing';
		break;

	case 'marketing':
		$position = 'marketing';
		$posID = '6';
		$localeID = 'marketing';
		break;

	case 'dashboard':
		$position = 'dashboard';
		$posID = '14';
		$localeID = 'dashboard';
		break;

	case 'admin-distributor':
		$position = 'admin-distributor';
		$posID = '12';
		$localeID = 'distributor';
		break;

	case 'accountant':
		$position = 'accountant';
		$posID = '16';
		$localeID = 'accountant';
		break;

	case 'dashboard-plus':
		$position = 'dashboard-plus';
		$posID = '17';
		$localeID = 'dashboard-plus';
		break;

	// =================== SIS Account

	case 'sis-supervisor':
		$position = 'supervisor';
		$posID = '5';
		$localeID = '';
		break;

	case 'sis-store':
		$position = 'store';
		$posID = '3';
		$localeID = $_POST['branch_code'];
		break;

	case 'sis-lab':
		$position = 'laboratory';
		$posID = '3';
		$localeID = $_POST['lab_code'];
		break;

	// =================== VVM Account

	case 'vvm-admin':
		$position = 'vvm-admin';
		$posID = '4';
		$localeID = 'vvm_admin';
		break;

	case 'vvm-studios':
		$position = 'vvm-studios';
		$posID = '4';
		$localeID = 'vvm_studios';
		break;

	case 'vvm-specs':
		$position = 'vvm-specs';
		$posdID = '4';
		$localeID = 'vvm_specs';
		break;	

	// =================== AIM Account

	case 'aim-warehouse':
		$position = 'aim-warehouse';
		$posID = '8';
		$localeID = 'warehouse';
		break;

	case 'aim-warehouse-qa':
		$position = 'aim-warehouse';
		$posID = '8';
		$localeID = 'warehouse_qa';
		break;

	case 'aim-warehouse':
		$position = 'aim-warehouse-damage';
		$posID = '8';
		$localeID = 'warehouse_damage';
		break;

	case 'aim-runner':
		$position = 'aim-runner';
		$posID = '11';
		$localeID = 'runner';
		break;

	case 'aim-overseer':
		$position = 'aim-overseer';
		$posID = '13';
		$localeID = 'overseer';
		break;

	case 'aim-auditor':
		$position = 'aim-auditor';
		$posID = '15';
		$localeID = 'auditor';
		break;

	case 'aim-clerk':
		$position = 'aim-clerk';
		$posID = '22';
		$localeID = 'clerk';
		break;

	// =================== Virtual Store

	case 'vs-admin':
		$position = 'vs-admin';
		$posID = '18';
		$localeID = 'admin';
		break;

	case 'vs-doctor':
		$position = 'vs-doctor';
		$posID = '19';
		$localeID = 'doctor';
		break;

	// =================== Poll 51

	case 'activator':
		$position = 'activator';
		$posID = '20';
		$localeID = 'activator';
		break;

	// =================== Human Resources

	case 'hr':
		$position = 'hr';
		$posID = '21';
		$localeID = 'admin';
		break;

	case 'vvm-user-mixed':
		$position = 'vvm-user-mixed';
		$posID = '7';
		// $localeID = 'admin';
		break;
	
	
};

// Name
$fullname = $_POST['fname'].' '.$_POST['mname'].' '.$_POST['lname']; 

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
						init_pass,
						user_type
					)
				VALUES(
					'".mysqli_real_escape_string($conn,$_POST['username'])."',
					'".$new_password."',
					'".mysqli_real_escape_string($conn,$_POST['fname'])."',
					'".mysqli_real_escape_string($conn,$_POST['mname'])."',
					'".mysqli_real_escape_string($conn,$_POST['lname'])."',
					'".$posID."',
					'".$position."',
					'".mysqli_real_escape_string($conn,$localeID)."',
					'".$s_pass."',
					'".mysqli_real_escape_string($conn,$_POST['password2'])."',
					'".$_POST['studio_access']."'
				)";

// echo '<pre>';
// echo $queryUser;
// echo '</pre>';	

if (mysqli_stmt_prepare($stmt, $queryUser)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

    $username = mysqli_real_escape_string($conn,$_POST['username']);
	$sql = "INSERT INTO user_access_v2 (
								username
							) 
						VALUES(?)
						";

	if(!$stmt = $conn->prepare($sql)){
	  echo $conn->error;
	}

	$insert = [];
	$insert[] = $username;

	$stmt->bind_param("s", ...$insert);

	if(!$stmt->execute()){
	  printf("Error: %s.\n", $stmt->error);
	}

}

// print_r($_POST);

echo "<script> window.location='/user-management/'; </script>";

?>
