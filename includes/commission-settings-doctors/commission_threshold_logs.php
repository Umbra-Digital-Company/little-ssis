<?php   

	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";
	require $sDocRoot."/includes/date_convert.php";

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
	foreach ($_GET['store_code'] as $key => $value) {
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


	$arrStores = array();
	$query = "SELECT
					cp.date_created,
	                cp.store_code,
	                sl.store_name,
	                cp.threshold,
	                cp.date_from,
	                cp.date_to
	            FROM 
	                commission_threshold_doctors cp
	             LEFT JOIN  stores_locations sl ON cp.store_code = sl.store_id
	             WHERE cp.store_code IN ('".implode("','",$arrStoresDataSearch)."')
	             ORDER BY sl.store_name, cp.id DESC";

	$grabParams = array(
		'date_created',
	    'store_code',
	    'store_name',
	    'threshold',
	    'date_from',
	    'date_to'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
	    
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $tempArray['date_created'] = dateTimeConvert(date('Y-m-d H:i:s' , strtotime($tempArray['date_created'].'+12 hour')));
	        $tempArray['date_from'] = dateConvert($tempArray['date_from']);
	        $tempArray['date_to'] = ($tempArray['date_to'] != '0000-00-00') ? dateConvert($tempArray['date_to']) : 'Present';
	        $tempArray['threshold'] = number_format($tempArray['threshold'], 2);

	        $arrStores[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	echo json_encode($arrStores);
	

?>
