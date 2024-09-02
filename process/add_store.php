<?php 
 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
// Required includes
require $sDocRoot."/includes/connect.php";


?>
<!--<pre>-->
<?php 
		//print_r($_POST);


 $query="INSERT INTO stores_locations(store_id,lab_id,store_name,address,province,city,barangay,phone_number,email_address)
VALUES('".$_POST['store_code']."',
'".$_POST['lab_code']."',
'".mysqli_real_escape_string($conn,$_POST['store_name'])."',
'".mysqli_real_escape_string($conn,$_POST['address'])."',
'".mysqli_real_escape_string($conn,$_POST['province'])."',
'".mysqli_real_escape_string($conn,$_POST['city'])."',
'".mysqli_real_escape_string($conn,$_POST['barangay'])."',
'".mysqli_real_escape_string($conn,$_POST['phone_number'])."',
'".mysqli_real_escape_string($conn,$_POST['email_address'])."'

)
ON DUPLICATE KEY UPDATE

store_id=VALUES(store_id),
lab_id=VALUES(lab_id),
store_name=VALUES(store_name),
address=VALUES(address),
province=VALUES(province),
city=VALUES(city),
barangay=VALUES(barangay),
phone_number=VALUES(phone_number),
email_address=VALUES(email_address)

";
//
//
//$query2="INSERT INTO stores(store_id,lab_id)
//VALUES('".$_POST['store_code']."',
//'".$_POST['lab_code']."'
//)
//ON DUPLICATE KEY UPDATE 
//store_id=values(store_id),
//lab_id=values(lab_id)
//
//
//";


	$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $query)) {
	mysqli_stmt_execute($stmt);		
}
//if (mysqli_stmt_prepare($stmt, $query2)) {
//	mysqli_stmt_execute($stmt);		
//}

echo "<script> window.alert('Succesfully Added');	window.location='../storelocation/'; </script>";


?>
<!--</pre>-->