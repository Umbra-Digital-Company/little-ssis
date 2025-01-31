<?php
	 
    // $query = 'SHOW COLUMNS FROM inventory';
    // $result = $conn->query($query);
    // //echo $query; exit;

    // $columns = [];
    // while($row = $result->fetch_assoc()) {
    //   $columns[] = $row['Field'];
    // }
	$users_id = ($page == 'server-query') ? "AND id =".$_SESSION['user_login']['id'] : '';
    $arrUsers = array();
	$query = "SELECT
				id,
				first_name,
				middle_name,
				last_name,
				position,
				store_location,
				store_code
				FROM users WHERE isadmin NOT IN (3,19) ".$users_id." ORDER BY first_name ASC;";

	

	$grabParams = array(

	    'id',
	    'first_name',
	    'middle_name',
	    'last_name',
	    'position',
	    'store_location',
		'store_code'

	);


	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
	    
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $arrUsers[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	$users_access = ($page == 'server-query') ? " OR find_in_set(".$_SESSION['user_login']['id'].",users_access) <> 0" : '';

	$arrColumns = array();
	$query = "SELECT
				id,
				creator_id,
				template_name,
				sales_columns,
				inventory_columns,
				users_access
				FROM server_query WHERE status ='active' AND (creator_id = '".$_SESSION['user_login']['id']."'".$users_access.") ORDER BY date_created ASC;";

	

	$grabParams = array(

	    'id',
	    'creator_id',
		'template_name',
	    'sales_columns',
	    'inventory_columns',
	    'users_access'

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

	        $arrColumns[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	//print_r($arrColumns);exit;
?>
