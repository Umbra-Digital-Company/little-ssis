<?php 
 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
// Required includes
require $sDocRoot."/includes/connect.php";


session_start(); 

?>
<pre>
<?php 
		
        exit;
?>
</pre>

<?php 

$stat_update='-';

if(isset($_POST['update_type'])){
	if($_POST['update_type']=='reject'){
		
		$stat_update='n';
		
	}
	elseif($_POST['update_type']=='save'){
		
		$stat_update='y';
	}
	
	
}


if(isset($_POST['Tdate']) && isset($_POST['Ttime']) ){
	$time=$_POST['Tdate']." ".$_POST['Ttime'].":00";
}
else{
	$time='1910-01-01 22:59:52';
}

for ($y=0; $y<sizeof($_POST['off']); $y++)

	{
							if(isset($_POST['production'][$_POST['off'][$y]])){ 

											$stmt = mysqli_stmt_init($conn);

								 $queryProduction="UPDATE orders_specs SET
														lab_production='".$stat_update."',
														lab_production_date=now(),
													remarks='".$_POST['remarks']."',
													lab_remarks='".mysqli_real_escape_string($conn,$_POST['remarks'])."',
													target_date='".$time."',
													synched='n'
													where orders_specs_id='".$_POST['off'][$y]."'
													";

								
								// if (mysqli_stmt_prepare($stmt, $queryProduction)) {
								// mysqli_stmt_execute($stmt);		
								// }


											
								$query3="INSERT INTO order_status(order_id, status,status_date,updatee,branch)
													VALUES(
													'".$_POST['order_id'][$_POST['off'][$y]]."',
													'On Process',
													now(),
													'".$_SESSION['id']."',
													'".$_SESSION['store_code']."'
													)";
							
								
								// if (mysqli_stmt_prepare($stmt, $query3)) {
								// mysqli_stmt_execute($stmt);		
								// }
//								
								
							}
	
	
	///////////////////// completion
	 if(isset($_POST['complete'][$_POST['off'][$y]])){
			$stmt = mysqli_stmt_init($conn);
					
		 				if( ($_POST['remarks']!=' ' && $_POST['remarks']!='') && $_POST['update_type']=='reject'){
							
								
								
							
															$queryComplete="UPDATE orders_specs SET
																	lab_status='n',
																	lab_production='n',
																	status='complete',
																	lab_status_date=now(),
																	lab_production_date=now(),
																	lab_remarks='".mysqli_real_escape_string($conn,$_POST['remarks'])."',
																	synched='n'
																	
																	where orders_specs_id='".$_POST['off'][$y]."' ";

																// if (mysqli_stmt_prepare($stmt, $queryComplete)) {
																// mysqli_stmt_execute($stmt);		
																// }
//								

					
					}


											 elseif($_POST['update_type']=='save'){
														$queryComplete="UPDATE orders_specs SET
																	lab_status='y',
																	status='complete',
																	lab_status_date=now(),
																	received_stat='n',
																		lab_remarks='".mysqli_real_escape_string($conn,$_POST['remarks'])."',
																		synched='n'
																	where orders_specs_id='".$_POST['off'][$y]."' ";
																// 	if (mysqli_stmt_prepare($stmt, $queryComplete)) {
																// mysqli_stmt_execute($stmt);		
																// }


									}else{
												
											 }




																	$query4="INSERT INTO order_status(order_id, status,status_date,updatee,branch)
																	VALUES(
																	'".$_POST['off'][$y]."',
																	'complete',
																	now(),
																	'".$_SESSION['id']."',
																	'".$_SESSION['store_code']."'
																	)";

														// if (mysqli_stmt_prepare($stmt, $query4)) {
														// mysqli_stmt_execute($stmt);		
														// }


	 		}
	
	
	
	}

if( ($_POST['remarks']==' ' || $_POST['remarks']=='') && $_POST['update_type']=='reject'){
	//echo "aaaaaaa";
	echo "<script> window.alert('Please enter remarks before rejecting');	window.location='../list/'; </script>";
}else{
	echo "<script> window.alert('Succesfully Updated');	window.location='../list/'; </script>";
}

?>