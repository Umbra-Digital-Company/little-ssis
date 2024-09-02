<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set POST data
$emp_employee_id  = $_POST['employee_id'];

function getLatLngToAddress($lat,$lng){

      $ch = curl_init();

      $dataArray = array(
                          'latlng' => "{$lat},{$lng}",
                          'key' => 'AIzaSyDsQoIxkSpCjRFaLXB-lOSzB2llFe7XdOU'
                        );

      $data = http_build_query($dataArray);

      $getUrl = "https://maps.googleapis.com/maps/api/geocode/json"."?".$data;
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_URL, $getUrl);
      curl_setopt($ch, CURLOPT_TIMEOUT, 80);

      $response = curl_exec($ch);

      $address = "";

      if(curl_error($ch)){
        // echo 'Request Error:' . curl_error($ch);
        $address = "Invalid Address";
      }
      else
      {
        $resp = json_decode($response);

        if($resp->status=="OK"){
          $address = $resp->results[0]->formatted_address;
        }

      }

      curl_close($ch);

      return 'test '.$address;

}//END:: getLatLngToAddress

function searchEmployee(){
	global $conn;
	$arrEmpId = array();

    $query  =   "SELECT
                   emp_id
                FROM
                    emp_table
                WHERE emp_id = '".mysqli_real_escape_string($conn,$_POST['employee_id'])."';";

    //echo $query;
    $grabParams = array(
        'emp_id'
    );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };
            $arrEmpId[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    }; 
   if(count($arrEmpId) == 0){
   		echo '<script type="text/javascript"> alert("Employee Id not exist"); window.location = "/daily-login"; </script>';
   		exit;
   };
}

if($_POST['action_status'] == 'login'){

	// echo getLatLngToAddress($_POST['latitude'],$_POST['longitude']);
	// exit;
	$query = 	'INSERT IGNORE INTO
					daily_login (
						emp_id,
						store_code,
						daily_in,
            date_in,
						daily_date,
						device,
            ip_address,
            latitude,
            longitude,
						created_by
					)
				VALUES (
					"'.mysqli_real_escape_string($conn,$emp_employee_id).'",
					"'.mysqli_real_escape_string($conn,$_SESSION['user_login']['store_code']).'",
					"1",
          now(),
					"'.date('Y-m-d',strtotime(date('Y-m-d H:i:s').' +13 hours')).'",
					"ip-address('. $_SERVER['REMOTE_ADDR'].') '.$_SERVER['HTTP_USER_AGENT'].'",
          "'.$_SERVER['REMOTE_ADDR'].'",
          "'.mysqli_real_escape_string($conn,$_POST['latitude']).'",
          "'.mysqli_real_escape_string($conn,$_POST['longitude']).'",
					"'.$_SESSION['user_login']['id'].'"
				)';

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);		
	    mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);
		exit;

	};
	echo '<script type="text/javascript"> alert("Successfully Login"); window.location = "/daily-login"; </script>';
}elseif($_POST['action_status'] == 'logout'){
	$query = 	'UPDATE 
					daily_login
				SET
					daily_out = 1,
          date_out = now(),
          device_out = "ip-address('. $_SERVER['REMOTE_ADDR'].') '.$_SERVER['HTTP_USER_AGENT'].'"
				WHERE
					emp_id = "'.mysqli_real_escape_string($conn,$emp_employee_id).'"
					AND daily_date ="'.date('Y-m-d',strtotime(date('Y-m-d H:i:s').' +13 hours')).'";';

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);		
	    mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);
		exit;

	};
	echo '<script type="text/javascript"> alert("Successfully Logout"); window.location = "/daily-login"; </script>';
}else{
	searchEmployee();
}


exit;

?>
