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

if(isset($_POST['update_type'])){

	if($_POST['update_type']=='reject'){		
		
		$stat_update='Rejected';
		
	}
	elseif($_POST['update_type']=='save'){
		
		$stat_update='Received';
	}else{

		$stat_update='Received';
	}
	
}




for ($y=0; $y<sizeof($_POST['off']); $y++) {
	
	if(isset($_POST['receive'][$_POST['off'][$y]])){ 
				
		if($_POST['update_type']=='reject'){
			
			
			
			$query = 	"UPDATE 
							orders_specs 
						SET
							`status`='returned',
							received_stat='r',
							received_stat_date=now(),
							status_date=now(),
							remarks='rejected ".$_POST['remarks']."',
							synched='y'
						WHERE 
							orders_specs_id='".$_POST['off'][$y]."'";
			
			// $stmt = mysqli_stmt_init($conn);
			// if(mysqli_stmt_prepare($stmt, $query)) {

			// 	mysqli_stmt_execute($stmt);
			// 	mysqli_stmt_close($stmt);

			// } 
			// else {

			// 	echo mysqli_error($conn);
			// 	return false;
			// 	exit;	

			// };			
			
		}
		
		////////////////////////////////////////////////////////////////////// SAVE

		elseif($_POST['update_type']=='save'){
			
			$queryUpdate = 	"UPDATE 
								orders_specs 
							SET
								`status`='received',
								status_date=now(),
								received_stat='y',
								received_stat_date=now(),
								synched='y'
							WHERE 
                            orders_specs_id='".$_POST['off'][$y]."'";

			$stmt = mysqli_stmt_init($conn);
			if(mysqli_stmt_prepare($stmt, $queryUpdate)) {

				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);

			} 
			else {

				echo mysqli_error($conn);
				return false;
				exit;	

			};	

		}else{

								$queryUpdate = 	"UPDATE 
								orders_specs 
							SET
								`status`='received',
								status_date=now(),
								received_stat='y',
								received_stat_date=now(),
								synched='y'
							WHERE 
                            orders_specs_id='".$_POST['off'][$y]."'";

					$stmt = mysqli_stmt_init($conn);
					if(mysqli_stmt_prepare($stmt, $queryUpdate)) {

					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);

					} 
					else {

					echo mysqli_error($conn);
					return false;
					exit;	

					};	

		}
		
		$query2 = 	'INSERT INTO  
						order_status(
							order_id,
							status,
							status_date,
							branch,
							updatee
						)
					VALUES(
						"'.$_POST['order_id'][$_POST['off'][$y]].'",
						"'.$stat_update.'", 
						now(),
						"'.$_SESSION['store_code'].'",
						"'.$_SESSION['id'].'"
					)';

		$stmt2 = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt2, $query2)) {

			mysqli_stmt_execute($stmt2);
			mysqli_stmt_close($stmt2);

		} 
		else {

			echo mysqli_error($conn);
			return false;
			exit;	

		};		
		
	}

};
	
echo '<script>	window.location.href="/dispatch"; </script>';

?>
	

</pre>