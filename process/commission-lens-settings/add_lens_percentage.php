<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
set_time_limit(0);
ini_set('memory_limit', '2G');

// Required includes
require $sDocRoot."/includes/connect.php";

	function checkDateLensExits($store_code,$product_code){

		global $conn;
		//validate logs today

		$arrLens = array();
			$query = "SELECT 
			                cl.date_from,
			                p.item_name
			            FROM 
			                commission_lens cl
			                LEFT JOIN poll_51 p ON p.product_code = cl.product_code 
			            WHERE
			            	cl.store_code = '".mysqli_real_escape_string($conn,$store_code)."'
			               	AND cl.product_code = '".mysqli_real_escape_string($conn,$product_code)."'
			               	AND cl.date_to = '000-00-00';";

			$grabParams = array(
			    'date_from',
			    'item_name'
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

			        $arrLens[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};

		if(count($arrLens) > 0){
			if(strtotime($arrLens[0]['date_from']) > strtotime($_POST['date_from'])){
				echo json_encode("Invalid start date for ".$arrLens[0]['item_name']."!");
				exit;
			}
		}

		$arrLens = array();
			$query = "SELECT 
			                cl.id,
			                p.item_name
			            FROM 
			                commission_lens cl
			                LEFT JOIN poll_51 p ON p.product_code = cl.product_code 
			            WHERE
			            	cl.store_code = '".mysqli_real_escape_string($conn,$store_code)."'
			               	AND cl.product_code = '".mysqli_real_escape_string($conn,$product_code)."'
			               	AND cl.date_from >= '".mysqli_real_escape_string($conn,$_POST['date_from'])."'
			                AND cl.date_to = '000-00-00';";

			$grabParams = array(
			    'id',
			    'item_name'
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

			        $arrLens[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};

		if(count($arrLens) > 0){
			echo json_encode("Settings for ".$arrLens[0]['item_name']." already saved!");

			exit;
		}
	}

	function selectLastLensLogs($store_code,$product_code){

		global $conn;

		$arrLens = array();
			$query = "SELECT 
			                id
			            FROM 
			                commission_lens
			            WHERE
			            	store_code = '".mysqli_real_escape_string($conn,$store_code)."'
			                AND product_code = '".mysqli_real_escape_string($conn,$product_code)."'
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

			        $arrLens[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};

			return $arrLens;
	}

	function selectAllLens($brand){
		global $conn;

		$arrLens = array();
		$query = "SELECT 
		                product_code
		            FROM 
		                poll_51
		            WHERE
		                 item_code ='LENS001'";
		            $query .=" AND house_brand ='".$brand."'";

		            $query .=" ORDER BY
		                item_name ASC";

		$grabParams = array(
		    'product_code'
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

		        $arrLens[] = $tempArray;

		    };

		    mysqli_stmt_close($stmt);    
		                            
		}
		else {

		    echo mysqli_error($conn);

		};

		return $arrLens;
	}

	function selectAllStore($store_code){
		global $conn;
		$store_code = implode("','",$store_code);
		$arrStore = array();
		$query = "SELECT 
		                sl.store_id
		            FROM 
		                stores_locations sl
		                    
		            WHERE
		                sl.active = 'y'
		                AND sl.store_id NOT IN ('".$store_code."')
		                ORDER BY
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

		        $arrStore[] = $tempArray;

		    };

		    mysqli_stmt_close($stmt);    
		                            
		}
		else {

		    echo mysqli_error($conn);

		};
		return $arrStore;
	}

	$arrAllStoreGroup = ['all_locals'];

	$arrSearchStore = [];
	$arrSearchStoreGroup = [];
	foreach ($_POST['stores_list'] as $key => $value) {
		(in_array($value, $arrAllStoreGroup)) ? $arrSearchStoreGroup[] = $value : $arrSearchStore[] = $value;
	}

	$arrStoresDataSearch = [];
	if(in_array('all_locals', $arrSearchStoreGroup)){
		$arrData = selectAllStore(['155','142','150','787','788','1000']);
		foreach ($arrData as $key => $value) {
			$arrStoresDataSearch[] = $value['store_id'];
		}
	}
	foreach ($arrSearchStore as $key => $value) {
		if(!in_array($value, $arrStoresDataSearch)){
			$arrStoresDataSearch[] = $value;
		}
	}

	foreach ($arrStoresDataSearch as $keyStore => $valueStore) {
		$arrAllGroups = ['all_housebrand','all_essilor'];
		$arrSearchLens = [];
		$arrSearchLensGroup = [];
		foreach ($_POST['lens_list'] as $key => $value) {
			(in_array($value, $arrAllGroups)) ? $arrSearchLensGroup[] = $value : $arrSearchLens[] = $value;
		}

		$arrLensDataSearch = [];
		if(in_array('all_housebrand', $arrSearchLensGroup)){
			$arrData = selectAllLens('HBR0001');
			foreach ($arrData as $key => $value) {
				$arrLensDataSearch[] = $value['product_code'];
			}
		}

		if(in_array('all_essilor', $arrSearchLensGroup)){
			$arrData = selectAllLens('HBR0002');
			foreach ($arrData as $key => $value) {
				$arrLensDataSearch[] = $value['product_code'];
			}
		}

		foreach ($arrSearchLens as $key => $value) {
			if(!in_array($value, $arrLensDataSearch)){
				$arrLensDataSearch[] = $value;
			}
		}

		//loop checking of lens date from not less than equal to selected date from ui
		foreach ($arrLensDataSearch as $key => $value) {
			checkDateLensExits($valueStore, $value);
		}

		$arrDataValues = [];
		$arrUpdateDateTo = [];
		foreach ($arrLensDataSearch as $key => $value) {
			$lastLogs = selectLastLensLogs($valueStore, $value);
			if(count($lastLogs) > 0){
				$arrUpdateDateTo[] = $lastLogs[0]['store_id'];
			}
			$store = mysqli_real_escape_string($conn,$valueStore);
			$product_code = mysqli_real_escape_string($conn,$value);
			$threshold = mysqli_real_escape_string($conn,$_POST['threshold']);
			$percentage = mysqli_real_escape_string($conn,$_POST['rate_percentage']);
			$date_from = mysqli_real_escape_string($conn,$_POST['date_from']);
			$user_id = $_SESSION['user_login']['id'];
			$arrDataValues[] = '("'.$store.'","'.$product_code.'","'.$threshold.'","'.$percentage.'","'.$date_from.'","'.$user_id.'")';
		}
		if(count($arrUpdateDateTo) > 0){
			$query = 	'UPDATE
							commission_lens
						SET
						date_to = "'.date('Y-m-d', strtotime(mysqli_real_escape_string($conn,$_POST['date_from']). '-1 day') ).'"
						WHERE id IN ("'.implode('","', $arrUpdateDateTo).'");';

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
							commission_lens (
								store_code,
								product_code,
								threshold,
								rate_percentage,
								date_from,
								created_by
							)
						VALUES '.implode(',', $arrDataValues);

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
	echo json_encode("Selected Lens successfully saved");
exit;

?>
