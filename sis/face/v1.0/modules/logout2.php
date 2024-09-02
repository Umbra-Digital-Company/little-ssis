<?php

if(!isset($_SESSION)){
    session_start();
}


if ( isset($_GET['acct']) ) {
	$id = $_GET['acct'];

	$queryRemove = " UPDATE sunniess_specs.users2 SET
						online='0',
						date_log=now()
					WHERE
						id='".$id."'
					";
				
	$stmt = mysqli_stmt_init($conn);

	mysqli_stmt_prepare($stmt, $queryRemove);
	mysqli_stmt_execute($stmt);

} else {
	$id = $_SESSION['id'];

	$queryLogOut = " UPDATE sunniess_specs.users2 SET
						online='0',
						date_log=now()
					WHERE
						id='".$id."'
					";
				
	$stmt = mysqli_stmt_init($conn);

	mysqli_stmt_prepare($stmt, $queryLogOut);
	mysqli_stmt_execute($stmt);

	session_destroy();

}
$querylogs = 	'INSERT INTO  
order_status(order_id,status,status_date,branch,updatee)
VALUES("'.$id.'", "Logout"
, now(),"'.$_SESSION['store_code'].'","'.$id.'")';

$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $querylogs)) {

mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

} 
if($_SESSION['userlvl']=='1'){
	echo "<script>	window.location='./?page=doctor'; </script>";
}elseif($_SESSION['userlvl']=='2'){
	echo "<script>	window.location='./?page=store-home'; </script>";
}


?>