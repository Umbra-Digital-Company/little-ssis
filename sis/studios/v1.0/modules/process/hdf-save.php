<meta charset="utf-8">

<?php if(!isset($_SESSION)){
	session_start();
}

include("../connect.php");

function checkProfileID($profile_id){
	global $conn;

	$arrProfileChecker= array();

	$query="Select profile_id from profiles_info where profile_id= ? ";

	$grabParams = array('profile_id');
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_bind_param($stmt, 's', $profile_id);
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

function checkEmail($x){

	global $conn;

	$arrEmail = array();
	$query="Select email_address from profiles where email_address= ? ";

	$grabParams = array('email_address');
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_bind_param($stmt, 's', $x);
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
		$_POST['bdate'] = date("Y-m-d", strtotime($_POST['bdate']));
	};

	// GENDER
	if(strtolower($_POST['gender']) == 'female' || $_POST['gender'] == 'f') {
		$_POST['gender'] = 'female';
	}
	else if(strtolower($_POST['gender']) == 'male' || $_POST['gender'] == 'm') {
		$_POST['gender'] = 'male';
	};

	///generate Priority
	$priority="0";
	if(isset($_POST['age'])){
		if($_POST['age']>='55'){
			$priority="1";
		}else{
			$priority="0";
		}
	}
	//suffix_name ='".mysqli_real_escape_string($conn,$_POST['sname'])."',
	$query ="UPDATE
		profiles_info
	SET
		last_name = '".mysqli_real_escape_string($conn,$_POST['lname'])."',
		first_name = '".mysqli_real_escape_string($conn,$_POST['fname'])."',
		middle_name ='".mysqli_real_escape_string($conn,$_POST['mname'])."',

		country = '".mysqli_real_escape_string($conn,$_POST['country'])."',
		province = '".mysqli_real_escape_string($conn,$_POST['province'])."',
		city = '".mysqli_real_escape_string($conn,$_POST['city'])."',
		barangay = '".mysqli_real_escape_string($conn,$_POST['barangay'])."',
		birthday = '".mysqli_real_escape_string($conn,$_POST['bdate'])."',
		age = '".mysqli_real_escape_string($conn,$_POST['age'])."',
		gender = '".mysqli_real_escape_string($conn,$_POST['gender'])."',
		email_address = '".mysqli_real_escape_string($conn,$_POST['email'])."',
		phone_number = '".mysqli_real_escape_string($conn,$_POST['mnum'])."',
		address = '".mysqli_real_escape_string($conn,$_POST['home_address'])."',
		priority = '".mysqli_real_escape_string($conn,$priority)."'
	WHERE
		profile_id = '".$_SESSION['customer_id']."'
	";

	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);
	}

	$query2 ="UPDATE
		profiles
	SET
		email_address = '".mysqli_real_escape_string($conn,$_POST['email'])."'
	WHERE
		profile_id = '".$_SESSION['customer_id']."'
	";

	$stmt2 = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt2, $query2)) {
		mysqli_stmt_execute($stmt2);
	}

	$_SESSION['priority'] = $priority;

	unset($_SESSION['email_taken']);
	echo "	<script>	window.location='../../?page=store-frame'</script>";
}
if(isset($_POST['mnum'] )){
$_POST['mnum'] = trim($_POST['country_codes']).trim($_POST['mnum']);
}
// doctor updating customer occupation
if ( isset($_POST['update_occupation']) && $_POST['update_occupation'] != "" ) {

	// update profiles_info table
	$query ="UPDATE
		profiles_info
	SET
		occupation = '".mysqli_real_escape_string($conn,$_POST['occupation'])."'
	WHERE
		profile_id = '".$_POST['profile_id_occupation']."'
	";

	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);
	}

	// udpate orders table
	$query2 ="UPDATE
		orders
	SET
		occupation = '".mysqli_real_escape_string($conn,$_POST['occupation'])."'
	WHERE
		profile_id = '".$_POST['profile_id_occupation']."' and order_id = '".$_POST['order_id_occupation']."'
	";

	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query2)) {
		mysqli_stmt_execute($stmt);
	}

	echo "<script>window.history.back()</script>";

} // register from customer page
else {

	$pi_lastname = $_POST['lname'];
	$pi_firstname = $_POST['fname'];
	$pi_middlename = $_POST['mname'];
	//$pi_suffixname = $_POST['sname'];

	if(isset($_POST['province'])){
		$pi_province = $_POST['province'];
	}else{

		$pi_province="N/A";
	}
	if(isset($_POST['city'])){
		$pi_city = $_POST['city'];
	}else{
		$pi_city='N/A';
	}

	if(isset($_POST['barangay'])){
		$pi_barangay = $_POST['barangay'];
	}
	else{
		$pi_barangay="N/A";
	}

	$pi_home 		= $_POST['home_address'];
	$pi_birthday 	= $_POST['bdate'];
	$pi_age 		= $_POST['age'];
	$pi_gender 		= $_POST['gender'];
	$pi_email 		= checkEmail($_POST['email']);
	$pi_mobile 		= $_POST['mnum'];
	$pi_store 		= $_POST['specs_branch'];
	$pi_joiningDate = $_POST['joining_date'];

	// Convert to timestamp
	$pi_birthday = strtotime($pi_birthday);
	// Concatinate Lastname and birthday remove special characters and spaces
	// $pword_last_name = preg_replace('/[^A-Za-z0-9]/', '', $pi_lastname);
	// $password = ucfirst(strtolower($pword_last_name)).date("MdY", $pi_birthday);
	$password = date("MdY", $pi_birthday);
	// Comment Old Code
	// $password = $pi_lastname.str_replace("-","",$pi_birthday);

	// if the email changed, form is for UPDATE
	if ( isset($_POST['email_confirmation']) ) { // UPDATE DATA
		if ( $_POST['email_confirmation'] == $_POST['email'] ) {
			// good to UPDATE the profile

			updateProfile();

		} else {
			// check first if the UPDATED email doesnt exist
			if ( $pi_email=='n' ) {
				// do not UPDATE. go back to form
				$_SESSION['email_taken'] = "Your Email is already taken. Please try another Email";
				echo "<script>window.history.back();</script>";
			} else {
				// good to UPDATE the profile
				unset($_SESSION['email_taken']);
				updateProfile();
			}
		}
	} else { // INSERT DATA
		// NEW customer
		// If email address already exists, send back

		if ($pi_email=='n') {

			$_SESSION['temp_data'] = 'YES';
			$_SESSION['last_name'] = $_POST['lname'];
			$_SESSION['first_name'] = $_POST['fname'];
			$_SESSION['middle_name'] = $_POST['mname'];
			//$_SESSION['suffix_name'] = $_POST['sname'];
			$_SESSION['birthday'] = $_POST['bdate'];
			$_SESSION['age']   = $_POST['age'];
			$_SESSION['gender'] = $_POST['gender'];
			$_SESSION['address'] = $_POST['home_address'];
			$_SESSION['province'] = $_POST['province'];
			$_SESSION['city'] = $_POST['city'];
			$_SESSION['barangay'] = $_POST['barangay'];
			$_SESSION['email_address'] = $_POST['email'];
			$_SESSION['phone_number'] = $_POST['mnum'];

			$_SESSION['email_taken'] = "Your Email is already taken. Please try another Email";
			echo "<script>window.history.back();</script>";

		} else {

			// BIRTHDATE
			if($_POST['bdate'] != '') {
				// Convert date
				$_POST['bdate'] = date("Y-m-d", strtotime($_POST['bdate']));
			};

			// GENDER
			if(strtolower($_POST['gender']) == 'female' || $_POST['gender'] == 'f') {
				$_POST['gender'] = 'female';
			}
			else if(strtolower($_POST['gender']) == 'male' || $_POST['gender'] == 'm') {
				$_POST['gender'] = 'male';
			};

			///generate Priority
			$priority="0";
			if(isset($_POST['age'])){
				if($_POST['age']>='55'){
					$priority="1";
				}else{
					$priority="0";
				}
			}

			// GENERATE AND ENCRYPTED PASSWORD FOR THE STORE REGISTER
			// SET UP PROFILE PASSWORD
			$new_password = $_POST["password2"];
			$new_password = $_POST["confirmPassword2"];
			$s_pass 	  = openssl_random_pseudo_bytes(32, $cstrong);
			$new_password = $s_pass.$new_password;
			$new_password = password_hash($new_password, PASSWORD_DEFAULT);

			// SET UP PROFILE ID
			$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
			$profileID = "";

			for ($i=0; $i < 18; $i++) {

				$profileID .=$generate_id[rand(0, (strlen($generate_id)-1))];

			};

			//$profileIDF=$_SESSION["store_code"]."-".$profileID;
			$profileIDF=checkProfileID($_SESSION["store_code"]."-".date('ymd').$profileID);

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
				address,
				priority
			) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?,?)";

			$stmt = mysqli_stmt_init($conn);

			if (mysqli_stmt_prepare($stmt, $query)) {

				mysqli_stmt_bind_param($stmt, 'sssssssssssssssss', $profileIDF, $_POST['lname'], $_POST['fname'], $_POST['mname'], $_POST['country'], $pi_province, $pi_city, $pi_barangay, $_POST['bdate'], $_POST['age'], $_POST['gender'], $_POST['email'], $_POST['mnum'], $_POST['store_code'], $_SESSION['id'], $_POST['home_address'], $priority);
				mysqli_stmt_execute($stmt);
			}

			$query2 ="INSERT INTO profiles(
				profile_id,
				email_address,
				password
			) VALUES (?,?,?)
			";

			$stmt2 = mysqli_stmt_init($conn);

			if (mysqli_stmt_prepare($stmt2, $query2)) {

				mysqli_stmt_bind_param($stmt2, 'sss', $profileIDF, $_POST['email'], $password);
				mysqli_stmt_execute($stmt2);
			}

			$_SESSION["customer_id"] = $profileIDF;
			$_SESSION['priority'] = $priority;

			unset($_SESSION['email_taken']);
			echo "	<script>	window.location='../../?page=store-frame'</script>";

		}
	}

}

?>
