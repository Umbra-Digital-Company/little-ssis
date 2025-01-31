<?php

if(isset($_GET['i'])){

$SearchItemWord = mysqli_real_escape_string($conn,strip_tags(str_replace("+", " ", str_replace('"','',$_GET['i']))));


	$searchBreakdown["searchword"] = explode(" ", $SearchItemWord);
}

$arrItems= array();


$grabParams = array(
					"id",
					"item_name",
					"item_description",
					"product_code",
					"owner",
					"status",
					"first_name",
					"middle_name",
					"last_name",
					"item_id",
					"active"
				);
$queryItemq ="";

$queryItemq .=" SELECT 
				i.id,
				i.item_name,
				i.item_description,
				i.product_code,
				i.owner,
				i.status,
				e.first_name,
				e.middle_name,
				e.last_name,
				i.item_id,
				i.active
				
			FROM
				admin_item_list i
			LEFT JOIN admin_employee e on e.emp_no=i.owner
			where i.item_name!='' 
				";

if(isset($_GET['profile_id']) && !isset($_GET['id'])){
	
$queryItemq .=" AND e.emp_no='".$_GET['profile_id']."'

						
";
}
if(isset($_GET['id'])){
	
	$queryItemq .=" AND i.status !='Owned'  and active='y' ";
}

if(isset($_GET['i'])){
			
			for($y=0 ;$y<sizeof($searchBreakdown["searchword"]);$y++){
					$queryItemq .=" and (i.item_name LIKE '%".$searchBreakdown["searchword"][$y]."%' OR i.item_description LIKE '%".$searchBreakdown["searchword"][$y]."%' OR i.product_code LIKE '%".$searchBreakdown["searchword"][$y]."%' )  ";
			}
		}







 $queryItem =$queryItemq;


$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryItem)) {
    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,
	 $result4, $result5, $result6, $result7, $result8, $result9, $result10
	 , $result11);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 11; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrItems[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>