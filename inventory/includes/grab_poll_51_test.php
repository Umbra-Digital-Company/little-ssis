<?php
$arrPoll51_items= array();


if(isset($_GET['filterStores'])  ){
	if($_GET['filterStores']=='787' ||  $_GET['filterStores']=='935') {
	$query="SELECT 
							p51.item_name,
							p51.product_code
                        FROM 
                                poll_51_new p51
								where p51.item_code!='LENS001'
								
								UNION ALL 
								SELECT item_name,
									product_code
							 FROM poll_51_studios 
								ORDER BY item_name ASC	";
								}else{
									$query="SELECT 
									item_name,
									product_code
								FROM 
										poll_51_new p51
										where item_code!='LENS001'
									
										ORDER BY 
			
				item_name ASC
		
				
				
				";
								}


}else{
	$query="SELECT 
							p51.item_name,
							p51.product_code
                        FROM 
                                poll_51_new p51
								where item_code!='LENS001'
							
                                ORDER BY 
	
		item_name ASC

		
		
		";
}




$grabParams = array(
    'product_style',
   
    'product_code');

    $stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrPoll51_items[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

	};

                        ?>