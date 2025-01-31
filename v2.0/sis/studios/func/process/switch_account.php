<?php 
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
// Get User  Credentials 
if(!isset($_SESSION['user_login']['username'])) {
	header("Location: /");
    exit;
}
$query="SELECT 
	s.username,
	LOWER(s.first_name),		
	LOWER(s.middle_name),		
	LOWER(s.last_name),		
	s.id ,		
	s.isadmin,		
	s.position,		
	s.store_location,		
	s.store_code,	
	s.password,		
	s.date_log
FROM 	
	users s

WHERE id='".$_GET['id']."'";
// echo $query; exit;
// set key parameters
$grabParams = array('username','first_name','middle_name','last_name','id' ,'isadmin','position','store_location','store_code','password','date_log');

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);
    // loop through Statement
    while (mysqli_stmt_fetch($stmt)) {
        $tempArray = array();
        for ($i=0; $i < 11; $i++) { 
        	// set keys and values
            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
        };
        // put in array
        $arrOnline [] = $tempArray;
    };
    mysqli_stmt_close($stmt);                             
}
else {
    echo mysqli_error($conn);
}

//Declare variables session
$_SESSION['userlvl'] = $arrOnline[0]["isadmin"];
$_SESSION['id'] = $arrOnline[0]["id"];
$_SESSION['name'] = $arrOnline[0]["first_name"]." ".$arrOnline[0]["last_name"];
$_SESSION["store_code"]	= $arrLogin[0]["store_code"];
$_SESSION['login'] = "YES";

// update user logged in
$queryLogupdate = "UPDATE users SET date_log=now() where id='".$_GET['id']."'";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryLogupdate)) {
	mysqli_stmt_execute($stmt);		
}

	echo "<script>	window.location='/v2.0/sis/studios/".$_POST['path_loc']."/?page=store-home'; </script>";
?>