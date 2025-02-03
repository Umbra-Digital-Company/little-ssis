
<?php

 include("connect.php");
 if(!isset($_SESSION)){
    session_start();
}
$msg = '';
$valid = true;

if (trim($_POST['user'])=='') {
	$valid=false;
	$msg .= "* Enter username<br />";
}
if (trim($_POST['pass'])=='') {
	$valid=false;
	$msg .= "* Enter Password<br />";
}

if($valid){
	
	
	$username = strtolower($_POST['user']);	
	$password = $_POST['pass']; 

	// Select cms_users
	$stmt = mysqli_stmt_init($conn);
	  $query="SELECT 

										s.username,

										s.first_name,
										
										s.middle_name,
										
										s.last_name,
										
										s.id ,
										
										s.isadmin,
										
										s.position,
										
										s.store_location,
										
										s.store_code,
										
										s.s_pass,
										
										s.password

									FROM 
									
										users s

									WHERE 

										s.username=? 

											";

	if (mysqli_stmt_prepare($stmt, 	$query)) {



		mysqli_stmt_bind_param($stmt, 's', $username);

		mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,  $result10 , $result11);

        mysqli_stmt_fetch($stmt);

		mysqli_stmt_store_result($stmt);

				$fullname 		= 	$result2."  ".$result4;
				$id				= 	$result5;
				$isadmin		=	$result6;
				$position 		=	$result7;
				$store_location	=	$result8;
				$store_code		=	$result9;
				$uSPass 		= $result10;
				$uPass 			= $result11;

		mysqli_stmt_close($stmt);

	}

$tryP = $uSPass.$password;



	
	
	if(password_verify($tryP, $uPass)) {
	
		
		
 $queryLogActive="UPDATE sunniess_specs.users SET
						online='1',
						date_log=now()
					where
						id='".$id."'
					";
		
			$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $queryLogActive)) {
	mysqli_stmt_execute($stmt);		
}
			
					$_SESSION['userlvl'] = $isadmin;
					$_SESSION['position'] = $position;
					$_SESSION['id'] = $id;
					$_SESSION['name'] = $fullname;
					//$_SESSION["store_code"]	= "103"; ///should be hardcoded per store
					$_SESSION['login'] = "YES";
				
					echo 'success';
		
		if($_SESSION['userlvl']=='1'){
				echo "<script>	window.location='./?page=doctor'; </script>";
			}
		elseif($_SESSION['userlvl']=='2'){
				echo "<script>	window.location='./?page=store-home'; </script>";
		}
	
	}
	else{
			
		echo "<font color='red'>Sorry, that username or password is incorrect.</font>";
		}
	
	
	
}
else{
	
	echo $msg;
	}

?>