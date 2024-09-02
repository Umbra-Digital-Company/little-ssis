<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
require $sDocRoot."/includes/connect.php";

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

//SET ONLINE TO 0
$username = $_SESSION['user_login']['username'];
$query = "UPDATE
	users
SET
	online = 0,
	date_log = now()
WHERE
	username = '$username'";				

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		
}

$querlog="INSERT INTO users_access_logs(`username`,`action`,`application`) 
				VALUES('".$_SESSION['user_login']['username'] ."',
				'logout',
				'sunniesstore')";


				$stmtBig2 = mysqli_stmt_init($conn);
				if (mysqli_stmt_prepare($stmtBig2, $querlog)) {

				    mysqli_stmt_execute($stmtBig2);
				    mysqli_stmt_close($stmtBig2);

				}
				else {

				    echo mysqli_error($conn);

				}

session_destroy();


header("location: ../");
?>