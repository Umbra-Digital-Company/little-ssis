<?php   

	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	set_time_limit(0);
	ini_set('memory_limit', '2G');

	// Required includes
	require $sDocRoot."/includes/connect.php";
	require $sDocRoot."/includes/date_convert.php";

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
	foreach ($_GET['store_code'] as $key => $value) {
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
	
	$arrLens = array();
	foreach ($arrStoresDataSearch as $keyStore => $valueStore) {
		$arrAllGroups = ['all_housebrand','all_essilor'];
		$arrSearchLens = [];
		$arrSearchLensGroup = [];
		foreach ($_GET['product_code'] as $key => $value) {
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

		
		$query = "SELECT
					p.item_name,
					sl.store_name,
					cl.store_code,
					cl.product_code,
					cl.threshold,
					cl.rate_percentage,
					cl.date_from,
					cl.date_to,
					cl.date_created
		            FROM 
		                commission_lens cl
		                LEFT JOIN stores_locations sl ON sl.store_id = cl.store_code
		             	LEFT JOIN  poll_51 p ON p.product_code = cl.product_code
		            WHERE
		            	cl.store_code = '".$valueStore."'
		            	AND cl.product_code IN ('".implode("','",$arrLensDataSearch)."')
		            ORDER BY
		                p.item_name, cl.id  DESC";

		$grabParams = array(
			'item_name',
			'store_name',
			'store_code',
			'product_code',
			'threshold',
			'rate_percentage',
			'date_from',
			'date_to',
			'date_created'
		);

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
		    
		    mysqli_stmt_execute($stmt);
		    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

		    while (mysqli_stmt_fetch($stmt)) {

		        $tempArray = array();

		        for ($i=0; $i < sizeOf($grabParams); $i++) { 

		            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		        };

		        $tempArray['date_created'] = dateTimeConvert(date('Y-m-d H:i:s' , strtotime($tempArray['date_created'].'+12 hour')));
		        $tempArray['date_from'] = dateConvert($tempArray['date_from']);
		        $tempArray['date_to'] = ($tempArray['date_to'] != '0000-00-00') ? dateConvert($tempArray['date_to']) : 'Present';

		        $arrLens[] = $tempArray;

		    };

		    mysqli_stmt_close($stmt);    
		                            
		}
		else {

		    echo mysqli_error($conn);

		};
	}

	echo json_encode($arrLens);
	

?>
