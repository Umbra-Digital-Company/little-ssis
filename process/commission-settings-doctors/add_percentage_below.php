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
			                commission_percentage_below_doctors cs
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
			                commission_percentage_below_doctors cs
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
			echo json_encode("Commission below threshold percentage for ".$arrStores[0]['store_name']." already saved!");

			exit;
		}
	}

	function selectLastStoreLogs($store_code){

		global $conn;

		$arrStores = array();
			$query = "SELECT 
			                id
			            FROM 
			                commission_percentage_below_doctors
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

	function selectAllStores($store_code, $nin){
		global $conn;

		$arrStores = array();
		$query = "SELECT 
		                sl.store_id
		            FROM 
		                `stores_locations` sl
		                    LEFT JOIN labs_locations ll 
		                        ON ll.lab_id = sl.lab_id
		            WHERE
		                sl.active = 'y'
		                and store_id NOT IN ('1000','142','150','155','999')";

		            $query .=" AND store_id ".$nin." ('".implode("','",$store_code)."')";

		            $query .=" ORDER BY
		                sl.store_name ASC";

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

	$arrAllGroups = ['all_locals','all_wheels','international'];

	$arrSearchStore = [];
	$arrSearchStoreGroup = [];
	foreach ($_POST['store_code_percentage_below'] as $key => $value) {
		(in_array($value, $arrAllGroups)) ? $arrSearchStoreGroup[] = $value : $arrSearchStore[] = $value;
	}

	$arrStoresDataSearch = [];
	if(in_array('all_locals', $arrSearchStoreGroup)){
		$arrStoresData = selectAllStores(['147','148','149','788'],'NOT IN');
		foreach ($arrStoresData as $key => $value) {
			$arrStoresDataSearch[] = $value['store_id'];
		}
	}

	if(in_array('all_wheels', $arrSearchStoreGroup)){
		$arrStoresData = selectAllStores(['147','148','149'],'IN');
		foreach ($arrStoresData as $key => $value) {
			$arrStoresDataSearch[] = $value['store_id'];
		}
	}

	if(in_array('international', $arrSearchStoreGroup)){
		$arrStoresData = selectAllStores(['788'],'IN');
		foreach ($arrStoresData as $key => $value) {
			$arrStoresDataSearch[] = $value['store_id'];
		}
	}

	foreach ($arrSearchStore as $key => $value) {
		if(!in_array($value, $arrStoresDataSearch)){
			$arrStoresDataSearch[] = $value;
		}
	}

	//loop checking of store date from not less than equal to selected date from ui
	foreach ($arrStoresDataSearch as $key => $value) {
		checkDateStoresExits($value);
	}

	foreach ($arrStoresDataSearch as $key => $value) {
		$lastLogs = selectLastStoreLogs($value);
		if(count($lastLogs) > 0){
			$query = 	'UPDATE
							commission_percentage_below_doctors
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
						commission_percentage_below_doctors (
							store_code,
							area_doctor,
							corporate_doctor,
							date_from,
							created_by
						)
					VALUES (
						"'.mysqli_real_escape_string($conn,$value).'",
						"'.mysqli_real_escape_string($conn,$_POST['adoc_below_percentage']).'",
						"'.mysqli_real_escape_string($conn,$_POST['cordoc_below_percentage']).'",
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
