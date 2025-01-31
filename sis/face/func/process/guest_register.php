<meta charset="utf-8">

<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION['user_login']['username'])) {
	header("Location: /");
    exit;
}

function checkProfileID($profile_id){
	global $conn;

	$arrProfileChecker= array();

	$query="Select profile_id from profiles_info where profile_id='".$profile_id."'";

	$grabParams = array('profile_id');
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);

		while (mysqli_stmt_fetch($stmt)) {
			$tempArray = array();

			for ($i=0; $i < 1; $i++) {
				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
			};

			$arrProfileChecker[] = $tempArray;
		};

		mysqli_stmt_close($stmt);
	}
	else {
		echo mysqli_error($conn);
	};

	if($arrProfileChecker){
		$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
		$profileID = "";

		for ($i=0; $i < 17; $i++) {
			$profileID .=$generate_id[rand(0, (strlen($generate_id)-1))];
		};

		$profileIDF=$_SESSION["store_code"]."-".date('ymd').$profileID;

	}else{
		$profileIDF=$profile_id;
	}

	return $profileIDF;
}

			// GENERATE AND ENCRYPTED PASSWORD FOR THE STORE REGISTER
			// SET UP PROFILE PASSWORD
			// SET UP PROFILE ID
			$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
			$generate_no = '0123456789';
			$profileID = "";
			$email = "";
			for ($i=0; $i < 18; $i++) {

				$profileID .=$generate_id[rand(0, (strlen($generate_id)-1))];

			};
			for ($i=0; $i < 10; $i++) {

				$email .=$generate_no[rand(0, (strlen($generate_no)-1))];

			};
			$email = date('YmdHis').'guest@'.$_SESSION["store_code"].'sunniesstudios.com'.$email;

			//$profileIDF=$_SESSION["store_code"]."-".$profileID;
			$profileIDF=checkProfileID($_SESSION["store_code"]."-".date('ymd').$profileID);
			$_POST['lastname'] = trim(mysqli_real_escape_string($conn,$_POST['lastname']));
			$_POST['firstname'] = trim(mysqli_real_escape_string($conn,$_POST['firstname']));
			$_POST['age_range'] = trim(mysqli_real_escape_string($conn,$_POST['age_range']));
			$_POST['gender'] = strtolower(trim(mysqli_real_escape_string($conn,$_POST['gender'])));

			$_POST['lastname'] = ($_POST['lastname'] == '') ? 'guest' : $_POST['lastname'];
			$_POST['firstname'] = ($_POST['firstname'] == '') ? 'guest' : $_POST['firstname'];
			$_POST['age_range'] = ($_POST['age_range'] == '') ? '0' : $_POST['age_range'];
			$_POST['gender'] = ($_POST['gender'] == '') ? 'N/A' : $_POST['gender'];

			$query="INSERT INTO profiles_info(
				profile_id,
				last_name,
				first_name,
				middle_name,

				country,
				province,
				city,
				barangay,
				birthday,
				age,
				gender,
				email_address,
				phone_number,
				branch_applied,
				joining_date,
				sales_person,
				address
			) VALUES (
				'".mysqli_real_escape_string($conn,$profileIDF)."',
				'".$_POST['lastname']."', '".$_POST['firstname']."',
				'guest',
				'philippines',
				'N/A',
				'N/A',
				'N/A',
				'1900-01-01',
				'".$_POST['age_range']."',
				'".$_POST['gender']."',
				'".$email."',
				'630123456789',
				'".mysqli_real_escape_string($conn,$_SESSION["store_code"])."',
				now(),
				'".mysqli_real_escape_string($conn,$_SESSION['id'])."',
				'N/A'
			)";

			$stmt = mysqli_stmt_init($conn);

			if (mysqli_stmt_prepare($stmt, $query)) {
				mysqli_stmt_execute($stmt);
			}

			$query2 ="INSERT INTO profiles(
				profile_id,
				email_address,
				password
			) VALUES (
				'".mysqli_real_escape_string($conn,$profileIDF)."',
				'".$email."',
				'Jan011970'
			)
			";

			$stmt2 = mysqli_stmt_init($conn);

			if (mysqli_stmt_prepare($stmt2, $query2)) {
				mysqli_stmt_execute($stmt2);
			}
		
			$_SESSION["customer_id"] = $profileIDF;
			$_SESSION['priority'] = 0;
			$_SESSION['guest_customer'] = true;
			$_SESSION['login_set'] = true;
			unset($_SESSION['email_taken']);
			echo "	<script>	window.location='/sis/face/".$_GET['path_loc']."/?page=select-store'</script>";

?>
