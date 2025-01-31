<?php
if(isset($_GET['date'])){

	if($_GET['date']=='month'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}
	elseif($_GET['date']=='yesterday'){

	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');

	}
    elseif($_GET['date']=='week'){

		$dateStart = date( 'Y-m-d', strtotime( 'monday this week' ) );
		$dateEnd = date( 'Y-m-d', strtotime( 'sunday next week' ) );

	}
	elseif($_GET['date']=='custom'){

		$dateStart = $_GET['startCdate'];
		$dateEnd =$_GET['endCdate'];
	}
	elseif($_GET['date']=='all-time'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}
	elseif($_GET['date']=='day'){

		$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-d');

	}

}
else{

	$dateStart = date('Y-m').'-1';
	$dateEnd= date('Y-m-t');

};


// echo "<pre>";
// echo print_r($_GET);
// echo "</pre>";
// if(!empty($arrFilterStores)) {
//     $specStore = "AND (";

// 		for ($i=0; $i < sizeOf($_GET['filterStores']); $i++) { 

// 			if($i > 0) {

// 				$specStore .= "OR ";

// 			}
		
// 			$specStore .= " o.origin_branch = '".$_GET['filterStores'][$i]."'  ";

// 		};

// 		$specStore .= ") ";		

// 	}
// 	else {

// 		$specStore = "";

// 	};


	if(!empty($_GET['filterStores'])) {


					if( sizeOf($_GET['filterStores'])=='1'){

						$specStore .= " AND o.origin_branch = '".$_GET['filterStores'][0]."'  ";

					}
					else{
						$stoper=sizeOf($_GET['filterStores'])-1;
						$specStore .= " AND  o.origin_branch IN ( ";
						for ($i=0; $i < sizeOf($_GET['filterStores']); $i++) { 
				
							
							$specStore .= "  '".$_GET['filterStores'][$i]."'  ";

							
							if($i < sizeOf($_GET['filterStores']) &&  $stoper!=$i ) {
				
								$specStore .= ", ";
				
							}
						
				
						};
				
						$specStore .= ") ";		

					}
				}
		
	
			
		else {
	
			$specStore = "";
	
		};
	





	$arrSalesReport= array();

	   $query="SELECT os.po_number,
      o.origin_branch,
      sl.store_name,
      item_name,
      os.lens_code,
      os.product_upgrade,
      os.lens_option,
      os.product_code,
      os.prescription_vision
                FROM
                orders_specs os
            LEFT JOIN orders o ON o.order_id=os.order_id
			LEFT JOIN stores_locations sl on sl.store_id=o.origin_branch
			LEFT JOIN poll_51 p ON p.product_code=os.product_code

        WHERE   
         date(os.store_dispatch_date)>='".$dateStart."'
          AND  date(os.store_dispatch_date)<='".$dateEnd."'
          and payment='y'
		 and status='dispatched'
	
		   ".$specStore."   
         
            ";


			$grabParams = array(
				'po_number',
				'branch_code',
				'branch_name',
				'item_name',
            'lens_code',
        'product_upgrade',
    'lens_option',
        'product_code',
    'prescription_vision');

				$stmt = mysqli_stmt_init($conn);
				if (mysqli_stmt_prepare($stmt, $query)) {
			
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);
			
					while (mysqli_stmt_fetch($stmt)) {
			
						$tempArray = array();
			
						for ($i=0; $i < sizeOf($grabParams); $i++) { 
			
							$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
			
						};
			
						$arrSalesReport[] = $tempArray;
			
					};
			
					mysqli_stmt_close($stmt);    
											
				}
				else {
			
					echo mysqli_error($conn);
			
				};


?>