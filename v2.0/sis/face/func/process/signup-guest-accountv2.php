
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

$conn->set_charset( "utf8" );
if($_POST['country'] == 'viet-nam'){
	$_POST['province'] = 'N/A';
	$_POST['barangay'] = 'N/A';
}

function checkEmail($x){

	global $conn;

	$arrEmail = array();
	$query="Select email_address from profiles where email_address='".$x."'";

	$grabParams = array('email_address');
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < 1; $i++) {

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrEmail[] = $tempArray;

		};

		mysqli_stmt_close($stmt);

	}
	else {

		echo mysqli_error($conn);

	};
	$emailReturn = "";
	if(sizeof($arrEmail)!='0'){

		$emailReturn='n';

	}else{
	$emailReturn='y';
	}

	return $emailReturn;

}

function updateProfile() {
	global $conn;

	// BIRTHDATE
	if($_POST['bdate'] != '') {
		// Convert date
		$bDate = $_POST['bdate'];
		$password = date("MdY", strtotime($bDate));
		$year = date('Y', strtotime($bDate));
		$ageCount = 0;
		for($i = $year; $i < date('Y'); $i++){
			$ageCount++;
		}
	};
	if(isset($_POST['mnum'] )){
		$_POST['mnum'] = trim($_POST['country_codes']).trim($_POST['mnum']);
	}
	// GENDER
	///generate Priority
	$priority="0";
	if(isset($_POST['age'])){
		if($_POST['age']>='55'){
			$priority="1";
		}else{
			$priority="0";
		}
	}
	$query ="UPDATE
		profiles_info
	SET
		last_name = '".mysqli_real_escape_string($conn,$_POST['lname'])."',
		first_name = '".mysqli_real_escape_string($conn,$_POST['fname'])."',
		middle_name ='',

		country = '".mysqli_real_escape_string($conn,$_POST['country'])."',
		province = '".mysqli_real_escape_string($conn,$_POST['province'])."',
		city = '".mysqli_real_escape_string($conn,$_POST['city'])."',
		barangay = '".mysqli_real_escape_string($conn,$_POST['barangay'])."',
		birthday = '".mysqli_real_escape_string($conn,$bDate)."',
		age = '".mysqli_real_escape_string($conn,$ageCount)."',
		gender = '".mysqli_real_escape_string($conn,$_POST['gender'])."',
		email_address = '".mysqli_real_escape_string($conn,$_POST['email'])."',
		phone_number = '".mysqli_real_escape_string($conn,$_POST['mnum'])."',
		address = '".mysqli_real_escape_string($conn,$_POST['home_address'])."',
		priority = '".mysqli_real_escape_string($conn,$priority)."'
	WHERE
		profile_id = '".$_SESSION['customer_id']."';
	";

	// echo $query;
	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);
	}

	// Convert to timestamp
	$pi_birthday 	= $_POST['bdate'];
	$pi_birthday = strtotime($pi_birthday);
	$password = date("MdY", $pi_birthday);

	$query2 ="UPDATE
		profiles
	SET
		email_address = '".mysqli_real_escape_string($conn,$_POST['email'])."',
		password = '".mysqli_real_escape_string($conn,$password)."'
	WHERE
		profile_id = '".$_SESSION['customer_id']."';
	";

	$stmt2 = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt2, $query2)) {
		mysqli_stmt_execute($stmt2);
	}
	$_SESSION['priority'] = $priority;
	// echo $query2;
	if(isset($_SESSION['guest_customer'])){
	    unset($_SESSION['guest_customer']);
	}
	echo  "Sign up done. Thank you!";
}

	
		$pi_email = checkEmail($_POST['email']);
		if ($pi_email=='n') {
			echo  "Your Email is already taken. Please try another Email";
		} else {
			updateProfile();
			unset($_SESSION['email_taken']);
		}


?>
