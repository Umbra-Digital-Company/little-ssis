<?php   

	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";
	require $sDocRoot."/includes/date_convert.php";

	$arrSearchStore = [];
	foreach ($_GET['store_code'] as $key => $value) {
		$arrSearchStore[] = $value;
	}

	$arrStores = array();
	$query = "SELECT
					cs.date_created,
	                cs.store_code,
	                sl.store_name,
	                (SELECT CONCAT_WS(' ', first_name, CONCAT(LEFT(middle_name,1),'.'),last_name) FROM emp_table WHERE emp_id = cs.sr_area_manager),
	                (SELECT CONCAT_WS(' ', first_name, CONCAT(LEFT(middle_name,1),'.'),last_name) FROM emp_table WHERE emp_id = cs.area_manager),
	                (SELECT CONCAT_WS(' ', first_name, CONCAT(LEFT(middle_name,1),'.'),last_name) FROM emp_table WHERE emp_id = cs.area_supervisor),
	                cs.date_from,
	                cs.date_to
	            FROM 
	                commission_settings cs
	             LEFT JOIN  stores_locations sl ON cs.store_code = sl.store_id
	            WHERE
	               cs.store_code IN ('".implode("','",$arrSearchStore)."')
	            ORDER BY
	                cs.store_code, cs.id  DESC";

	$grabParams = array(
		'date_created',
	    'store_code',
	    'store_name',
	    'sram',
	    'am',
	    'suparea',
	    'date_from',
	    'date_to'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
	    
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $tempArray['date_created'] = dateTimeConvert(date('Y-m-d H:i:s' , strtotime($tempArray['date_created'].'+12 hour')));
	        $tempArray['date_from'] = dateConvert($tempArray['date_from']);
	        $tempArray['date_to'] = ($tempArray['date_to'] != '0000-00-00') ? dateConvert($tempArray['date_to']) : 'Present';

	        $arrStores[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	echo json_encode($arrStores);
	

?>
