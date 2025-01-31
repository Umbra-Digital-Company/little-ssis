<meta charset="UTF-8">
<?php 
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

$msg = '';
$valid = true;

if ($_POST['username'] == '') {
	$valid = false;
}

if (trim($_POST['month']) == '' || trim($_POST['day']) == '' || trim($_POST['year']) == '') {
	$valid = false;
}

if ($valid) {
	$username = $_POST['username'];
	$password = $_POST['month'] . $_POST['day'] . $_POST['year'];

	// Clean up phone number mke it 1 line
	$phone_number = str_replace(['+', '-', ' '], '', trim($username));
	$phone_number = ltrim($phone_number, "0");
	$phone_number = trim($_POST['country_codes_login']) . $phone_number;

	$qstmt = mysqli_stmt_init($conn);
	$query = "SELECT 
		s.email_address,
		s.s_pass,
		s.password,
		s.profile_id,
		s.id,
		pi.age
	FROM 
		profiles s
	LEFT JOIN
		profiles_info pi ON pi.profile_id = s.profile_id
	WHERE 
		(s.email_address = ? OR REPLACE(pi.phone_number, '-', '') = ?)
	AND 
		s.password = ?";

	if (mysqli_stmt_prepare($qstmt, $query)) {
		// Bind parameters (all strings, hence "sss")
		mysqli_stmt_bind_param($qstmt, "sss", $username, $phone_number, $password);

		// Execute the statement
		mysqli_stmt_execute($qstmt);

		// Bind the results
		mysqli_stmt_bind_result($qstmt, $r1, $r2, $r3, $r4, $r5, $r6);

		// Fetch the result
		mysqli_stmt_fetch($qstmt);

		// Store the result
		mysqli_stmt_store_result($qstmt);

		$email_add = $r1;
		$uSPass = $r2;
		$uPass = $r3;
		$customer_id = $r4;
		$id = $r5;
		$age = $r6;

		// Close the statement
		mysqli_stmt_close($qstmt);
	}

	// Check if password matches
	if ($uPass == $password) {
		$_SESSION["login_customer"] = "YES";
		$_SESSION["customer_id"] = $customer_id;
		$_SESSION["cust_id"] = $id;

		// Prepare the update query for sales person
		$queryUpdateSalesPerson = "UPDATE profiles_info SET sales_person = ? WHERE profile_id = ?";
		$stmt = mysqli_stmt_init($conn);

		if (mysqli_stmt_prepare($stmt, $queryUpdateSalesPerson)) {
			// Bind parameters for the update query (s = string)
			mysqli_stmt_bind_param($stmt, "ss", $_SESSION['id'], $customer_id);
			// Execute the statement
			mysqli_stmt_execute($stmt);
		}

		// Set priority based on age
		$_SESSION['priority'] = ($age > '55') ? '1' : '0';

		// Clear email taken session and set login session
		unset($_SESSION['email_taken']);
		unset($_SESSION['autologin']);
		$_SESSION['login_set'] = true;

		// Redirect to select store page
		echo '<script>window.location="./?page=order-confirmation&bpage='.htmlspecialchars($_GET['bpage']).'"</script>';
	} else {
		// Account does not exist message
		echo 'Account does not exist.';
		echo '<script>
			$(".placeholder.email").addClass("text-danger");
			$("#username").css("border-bottom", "2px solid red");
		</script>';
	}
} else {
	// Account does not exist message
	echo 'Account does not exist.';
}
?>
