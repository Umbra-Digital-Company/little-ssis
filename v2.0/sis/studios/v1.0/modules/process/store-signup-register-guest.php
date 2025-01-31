<meta charset="utf-8"> 

<?php if(!isset($_SESSION)){
	session_start();
}

include("../connect.php");

function checkProfileID($profile_id){

	global $conn;

	$arrProfileChecker= array();

	$query = 	"SELECT 
					profile_id 
				FROM 
					profiles_info 
				WHERE 
					profile_id = '".$profile_id."'";

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

		for ($i=0; $i < 21; $i++) { 

			$profileID .=$generate_id[rand(0, (strlen($generate_id)-1))];

		};

		$profileIDF = $_SESSION["store_code"]."-".$profileID;

	}
	else{

		$profileIDF = $profile_id;

	};

	return $profileIDF;

};

// Set everything to guest data
$pi_emailAddress = "specsguest@sunniesspecsoptical.com".time();
$pi_store 		 = $_POST['specs_branch'];
$pi_joiningDate  = $_POST['joining_date'];
$pi_birthday 	 = strtotime("1900-01-01");
$password 		 = date("MdY", $pi_birthday);
$priority 		 = "0";

// SET UP PROFILE ID
$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
$profileID   = "";

for ($i=0; $i < 22; $i++) { 

	$profileID .= $generate_id[rand(0, (strlen($generate_id)-1))];

};

$profileIDF = checkProfileID($_SESSION["store_code"]."-".$profileID);

// INSERT DATA
// NEW customer

$query = 	"INSERT INTO 
				profiles_info(
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
			) 
			VALUES (			
				'".mysqli_real_escape_string($conn,$profileIDF)."',
				'guest',
				'guest',
				'guest',
				'philippines',
				'N/A',
				'N/A',
				'N/A',
				'1900-01-01',
				'0',
				'N/A',
				'".mysqli_real_escape_string($conn,$pi_emailAddress)."',
				'630123456789',
				'".mysqli_real_escape_string($conn,$_SESSION["store_code"])."',
				now(),
				'".mysqli_real_escape_string($conn,$_SESSION['id'])."',
				'N/A',
				'0'
			)";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		

};

$query2 = 	"INSERT INTO 
				profiles(
					profile_id,
					email_address,
					password
				)
			VALUES (
				'".mysqli_real_escape_string($conn,$profileIDF)."',
				'".mysqli_real_escape_string($conn,$pi_emailAddress)."',
				'".mysqli_real_escape_string($conn,$password)."'
			)";

$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $query2)) {

	mysqli_stmt_execute($stmt2);		

};

$_SESSION["customer_id"] = $profileIDF;
$_SESSION['priority'] = $priority;

unset($_SESSION['email_taken']);		
echo "	<script>	window.location='../../?page=store-frame&checkout=guest'</script>";

?>
