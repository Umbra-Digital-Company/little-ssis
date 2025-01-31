<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set array
$arrOrders = [];

$grabParams = array(	
	"order_id",
	"store_id",
	"origin_branch",
	"date_created",
	"date_updated"
);

$query  = 	"SELECT					
				order_id,
				store_id,
				origin_branch,
				date_created,
				date_updated
			FROM 
				orders
			WHERE
				(store_id = '' OR origin_branch = '') AND (order_id != '' AND profile_id != '') AND origin_branch != 'MLA'
			ORDER BY
				date_created DESC;";
			
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrOrders[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>