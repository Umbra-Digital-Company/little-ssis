<?php
if(!isset($_SESSION)){
    session_start();
}
//  $queryLogOut="UPDATE sunniess_specs.users2 SET
// 						online='0',
// 						date_log=now()
				
// 					";
		
// $stmt = mysqli_stmt_init($conn);

// mysqli_stmt_prepare($stmt, $queryLogOut);
// mysqli_stmt_execute($stmt);

$querylogs = 	'INSERT INTO  
order_status(order_id,status,status_date,branch,updatee)
VALUES("'.$_SESSION['id'].'", "Logout all session"
, now(),"'.$_SESSION['store_code'].'","'.$_SESSION['id'].'")';

$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $querylogs)) {

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

} 

			$logoutmsg= "<script>	window.location='/sis/studios/v1.0'; </script>";
	


session_destroy();

echo $logoutmsg;
?>