<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

	function checkDateStoresExits($store_code){

		global $conn;
		//validate logs today

		$arrStores = array();
			$query = "SELECT 
			                cs.date_from,
			                sl.store_name
			            FROM 
			                commission_settings_test cs
			                LEFT JOIN stores_locations sl ON sl.store_id = cs.store_code 
			            WHERE
			               cs.store_code = '".mysqli_real_escape_string($conn,$store_code)."'
			               AND cs.date_to = '000-00-00';";

			$grabParams = array(
			    'date_from',
			    'store_name'
			);

			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $query)) {
			    
			    mysqli_stmt_execute($stmt);
			    mysqli_stmt_bind_result($stmt, $result1, $result2);

			    while (mysqli_stmt_fetch($stmt)) {

			        $tempArray = array();

			        for ($i=0; $i < sizeOf($grabParams); $i++) { 

			            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			        };

			        $arrStores[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};

		if(count($arrStores) > 0){
			if(strtotime($arrStores[0]['date_from']) > strtotime($_POST['date_from'])){
				echo json_encode("Invalid start date for ".$arrStores[0]['store_name']."!");
				exit;
			}
		}

		$arrStores = array();
			$query = "SELECT 
			                sl.id,
			                sl.store_name
			            FROM 
			                commission_settings_test cs
			                LEFT JOIN stores_locations sl ON sl.store_id = cs.store_code 
			            WHERE
			               cs.store_code = '".mysqli_real_escape_string($conn,$store_code)."'
			               AND cs.date_from >= '".mysqli_real_escape_string($conn,$_POST['date_from'])."'
			               AND cs.date_to = '000-00-00';";

			$grabParams = array(
			    'store_id',
			    'store_name'
			);

			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $query)) {
			    
			    mysqli_stmt_execute($stmt);
			    mysqli_stmt_bind_result($stmt, $result1, $result2);

			    while (mysqli_stmt_fetch($stmt)) {

			        $tempArray = array();

			        for ($i=0; $i < sizeOf($grabParams); $i++) { 

			            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			        };

			        $arrStores[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};

		if(count($arrStores) > 0){
			echo json_encode("commission settings for ".$arrStores[0]['store_name']." already saved!");

			exit;
		}
	}

	function selectLastStoreLogs($store_code){

		global $conn;

		$arrStores = array();
			$query = "SELECT 
			                id
			            FROM 
			                commission_settings_test
			            WHERE
			               store_code = '".mysqli_real_escape_string($conn,$store_code)."'
			               AND date_to = '000-00-00'
			            ORDER BY
			                id DESC LIMIT 1";

			$grabParams = array(
			    'store_id'
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

			        $arrStores[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};

			return $arrStores;
	}

	//loop checking of store date from not less than equal to selected date from ui
	foreach ($_POST['store_code_user']as $key => $value) {
		checkDateStoresExits($value);
	}

	foreach ($_POST['store_code_user'] as $key => $value) {
		$lastLogs = selectLastStoreLogs($value);
		if(count($lastLogs) > 0){
			$query = 	'UPDATE
							commission_settings_test
						SET
						date_to = "'.date('Y-m-d', strtotime(mysqli_real_escape_string($conn,$_POST['date_from']). '-1 day') ).'"
						WHERE id = "'.$lastLogs[0]['store_id'].'";';

			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $query)) {

			    mysqli_stmt_execute($stmt);		
			    mysqli_stmt_close($stmt);		

			}
			else {

				echo mysqli_error($conn);
				exit;

			};
		}
		
		$query = 	'INSERT IGNORE INTO
						commission_settings_test (
							store_code,
							sr_area_manager,
							area_manager,
							area_supervisor,
							date_from,
							created_by
						)
					VALUES (
						"'.mysqli_real_escape_string($conn,$value).'",
						"'.mysqli_real_escape_string($conn,$_POST['sram']).'",
						"'.mysqli_real_escape_string($conn,$_POST['am']).'",
						"'.mysqli_real_escape_string($conn,$_POST['sup-area']).'",
						"'.mysqli_real_escape_string($conn,$_POST['date_from']).'",
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
		
	}
	echo json_encode("Selected stores successfully saved");
exit;

?>
