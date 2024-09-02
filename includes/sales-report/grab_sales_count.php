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
		$dateEnd = $_GET['endCdate'];

	}
	
	elseif($_GET['date']=='day'){

		
	
		$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-d');

	}elseif($_GET['date']=='year'){
		
		$dateStart = date('Y-01-01');
		$dateEnd= date('Y-12-31');

	}elseif($_GET['date']=='all-time'){
	

		$dateStart = '2019-01-01';
		$dateEnd= date('Y-m-d');

	}elseif($_GET['date']=='custom'){
		$dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		$dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
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

	  $query="SELECT count(os.orders_specs_id) as total,o.origin_branch,sl.store_name,item_name
                FROM
                orders_specs os
            LEFT JOIN orders o ON o.order_id=os.order_id
			LEFT JOIN stores_locations sl on sl.store_id=o.origin_branch
			LEFT JOIN poll_51 p ON p.product_code=os.product_code

        WHERE   
         date(os.payment_date)>='".$dateStart."'
          AND  date(os.payment_date)<='".$dateEnd."'
          and payment='y'
		  and status NOT IN ('return','cancelled','returned','for payment','for exam','','failed')
		  and os.product_code='".$_GET['filterProduct']."'
		   ".$specStore." 
          GROUP BY o.origin_branch
            ";


			$grabParams = array(
				'total',
				'branch_code',
				'branch_name',
				'item_name');

				$stmt = mysqli_stmt_init($conn);
				if (mysqli_stmt_prepare($stmt, $query)) {
			
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);
			
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