<?php

if(!isset($_SESSION)){
	session_start();
}
$arrServiceItemPoll51= array();

$queryServicesItem="SELECT `item_description`, `item_name`, `price`, `product_code` 
FROM `poll_51`
 WHERE 
                 product_code NOT LIKE 'MC%'
            AND product_code NOT LIKE 'SS%'
            AND product_code NOT LIKE 'SP%'
            and product_code!='TINTSAP2019'
            AND product_code NOT LIKE 'SC%'
            AND product_code NOT LIKE 'PL%'
            AND product_code NOT LIKE 'CP%'
            AND product_code NOT LIKE 'C%'
            AND product_code NOT LIKE 'H%'
            AND product_code NOT LIKE 'GC%'
            AND product_code NOT LIKE 'L%'
            AND product_code NOT LIKE 'MH%'
            AND product_code NOT LIKE 'P%'
            AND product_code NOT LIKE 'VC%'
            AND product_code NOT LIKE 'SO%'
            AND product_code NOT LIKE 'S100%'
            AND product_code NOT LIKE 'M100%'
            AND product_code NOT LIKE 'SR100%'
            AND product_code NOT LIKE 'MSPVHC%'
            AND product_code NOT LIKE 'KIL%'
            AND product_code NOT LIKE 'F100%' 
           
            AND product_code NOT LIKE 'DR-FEE%'
            AND product_code NOT LIKE 'KSS%'
            AND product_code NOT LIKE 'MGC%'
            AND product_code NOT LIKE 'MSPHC%'
            AND price!='0'
            ORDER by price desc
            ";



					
$grabParamsservice = array("item_description", "item_name", "price", "product_code");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryServicesItem)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParamsservice); $i++) { 

      $tempArray[$grabParamsservice[$i]] =${'result' . ($i+1)};

    };

    $arrServiceItemPoll51[] = $tempArray;

  };

  mysqli_stmt_close($stmt);    
                            
}

function getdetailsPoll51($item_code){
	global $conn;

	$arrPOll51item=array();

		 $queryItem=" Select item_description
					 FROM
							poll_51
					WHERE
						product_code='".$item_code."' ";
						
$grabParams = array("item_name");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryItem)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1);

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParams); $i++) { 

      $tempArray[$grabParams[$i]] = $result1;

    };

    $arrPOll51item[] = $tempArray;

  };

  mysqli_stmt_close($stmt);    
                            
}

return $arrPOll51item;


}
?>