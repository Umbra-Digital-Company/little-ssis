<pre>

<?php 


session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

if(!isset($_SESSION)){

    session_start();

}


if(isset($_POST['order_id']) && isset($_POST['po_number']) && isset($_POST['customer_id'])) {


	if(isset($_POST['cancel_reason'])){
		$cancel_reason=$_POST['cancel_reason'];
	}
	else{
		$cancel_reason="";
	}
	 $query = 	"UPDATE 
					orders_sunnies_studios
				SET
					`status`='cancelled',
					synched='y',
					remarks='".mysqli_real_escape_string($conn,$cancel_reason)."'

				WHERE 
					order_id='".$_POST['order_id']."'
						AND po_number='".$_POST['po_number']."' 
						and orders_specs_id='".$_POST['orders_specs_id']."' ";
	
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	} 
	else {

		echo mysqli_error($conn);		
		exit;	

	};

	$query2c = 	'INSERT INTO  
						order_status_studios(
							order_id,
							status,
							status_date,
							branch,
							updatee
						)
					VALUES(
						"'.$_POST['po_number'].'",
						"Cancelled", 
						now(),
						"'.$_SESSION['store_code'].'",
						"'.$_SESSION['id'].'"
					)';

		$stmt2c = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt2c, $query2c)) {

			mysqli_stmt_execute($stmt2c);
			mysqli_stmt_close($stmt2c);

		} 
		else {

			echo mysqli_error($conn);
			return false;
			exit;	

		};

};

?>
	

</pre>