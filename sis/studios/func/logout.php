<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";

$querylogs = 	'INSERT INTO  
order_status(order_id,status,status_date,branch,updatee)
VALUES("'.$_SESSION['id'].'", "Logout all session"
, now(),"'.$_SESSION['store_code'].'","'.$_SESSION['id'].'")';

$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $querylogs)) {

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

			$logoutmsg= "<script>	window.location='/sis/studios/".$_GET['path_loc']."'; </script>";
	


session_destroy();

echo $logoutmsg;
?>