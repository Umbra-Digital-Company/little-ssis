<?php 

if(!isset($_SESSION)){
        session_start();
    }

$arrHistory= array();


  $queryHistory ="SELECT os.order_id,o.`status`,o.status_date,branch,store_name,lab_name
FROM order_status o
LEFT JOIN stores_locations sl on sl.store_id=o.branch
LEFT JOIN labs_locations ll on ll.lab_id=o.branch
LEFT JOIN orders_specs os on os.order_id=o.order_id
where
os.order_id='".$_GET['orderNo']."'
GROUP BY o.`status`
";
	$grabHistoryParams=array("order_id","status","status_date","branch","store_name","lab_name");
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryHistory)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 6; $i++) { 

            $tempArray[$grabHistoryParams[$i]] = ${'result' . ($i+1)};

        };

        $arrHistory[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};
?>