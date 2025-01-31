<?php


function  GetTotalStockTransferM($product_code,$store,$dateStart,$dateEnd){

    global $conn;


	$arrFrames = array();

    	$query =    "SELECT 
							
							coalesce(sum(ip.count),0),
							date(ip.status_date)
							
							
					FROM 
							poll_51_new p51
					
					LEFT JOIN 
                            inventory ip
						ON
							ip.product_code=p51.product_code
					WHERE
	                    	p51.product_code ='".$product_code."'
                    AND
                        ip.stock_from='".$store."'
                    AND 
                        status ='received'
					AND
						ip.type='stock_transfer'
					AND
								status_date>='".$dateStart."'
								AND
								status_date<='".$dateEnd."'
                    
                        ";

	$grabParams = array(
	
		'pulloutcount',
		'pulloutdate'
	);
		
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrFrames[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

    };
    
   return $arrFrames[0]["pulloutcount"];
}



function  GetTotalStockTransferP($product_code,$store,$dateStart,$dateEnd){

    global $conn;


	$arrFrames = array();

    	$query =    "SELECT 
							
							coalesce(sum(ip.count),0),
							date(ip.status_date)
							
							
					FROM 
							poll_51_new p51
					
					LEFT JOIN 
							inventory ip
						ON
							ip.product_code=p51.product_code
					WHERE
	                    	p51.product_code ='".$product_code."'
                    AND
                        ip.store_id='".$store."'
                    AND 
                        status ='received'
					AND 
						ip.type='stock_transfer'
						AND
								status_date>='".$dateStart."'
								AND
								status_date<='".$dateEnd."'
                        ";

	$grabParams = array(
	
		'pulloutcount',
		'pulloutdate'
	);
		
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrFrames[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

    };
    
   return $arrFrames[0]["pulloutcount"];
}





function GetTotalOnTransit($product_code,$store,$dateStart,$dateEnd){

	global $conn;
	
	
	$arrBegInventory= array();
	
	$query =    "SELECT 
								
								coalesce(sum(ip.count),0),
								date(ip.status_date)
								
								
						FROM 
								poll_51_new p51
						
						LEFT JOIN 
								inventory ip
							ON
								ip.product_code=p51.product_code
						WHERE
								p51.product_code ='".$product_code."'
						AND
							ip.store_id='".$store."'
						
						AND 
							status ='in transit'
						AND
								status_date<='".$dateEnd."'
						
						
							";
	
		$grabParams = array(
		
			'pulloutcount',
			'pulloutdate'
		);
			
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
	
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2);
	
			while (mysqli_stmt_fetch($stmt)) {
	
				$tempArray = array();
	
				for ($i=0; $i < sizeOf($grabParams); $i++) { 
	
					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
	
				};
	
				$arrFrames[] = $tempArray;
	
			};
	
			mysqli_stmt_close($stmt);    
									
		}
		else {
	
			echo mysqli_error($conn);
	
		};
		
	   return $arrFrames[0]["pulloutcount"];
	
	
	}

// 	function GetDailySalesInventory($product_code,$store,$dateStart,$dateEnd){
// 			global $conn;

// $arrDailySales = array();
// 			$query="SELECT count(os.product_code) FROM `orders_specs` os

// 			WHERE 
// 					os.product_code='".$product_code."'
// 					And payment='y'
// 					And (status!='return' OR
// 						status!='cancelled' OR
// 						 status!='returned' 
// 						)
// 					  AND (os.payment_date)>='".$dateStart."'
// 					 AND (os.payment_date)<='".$dateEnd."' ";

// $grabParams = array(
			
// 	'sales'

// );
	
// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $query)) {

// 	mysqli_stmt_execute($stmt);
// 	mysqli_stmt_bind_result($stmt, $result1);

// 	while (mysqli_stmt_fetch($stmt)) {

// 		$tempArray = array();

// 		for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 		};

// 		$arrDailySales [] = $tempArray;

// 	};

// 	mysqli_stmt_close($stmt);    
							
// }
// else {

// 	echo mysqli_error($conn);

// };

// return $arrDailySales[0]["sales"];

// 	}


	function GetTotalBegInventory($product_code,$store,$dateStart,$dateEnd){

		global $conn;
		
		
		$arrBegInventory= array();
		
	 $query =    "SELECT 
									
									coalesce(sum(ip.count),0),
									date(ip.status_date),

									(select coalesce(sum(`count`),0) FROM inventory 
													WHERE
													product_code ='".$product_code."'
														AND
												stock_from='".$store."'
											
											AND 
												status ='received' 
											AND type='pullout'
											AND
												status_date<='".$dateStart."'
									) as pullout,

									(select coalesce(sum(`count`),0) FROM inventory 
													WHERE
													product_code ='".$product_code."'
														AND
												stock_from='".$store."'
											
											AND 
												status ='received' 
											AND type='damage'
											AND
												status_date<='".$dateStart."'
									) as damage,

									(select coalesce(sum(`count`),0) FROM inventory 
													WHERE
													product_code ='".$product_code."'
														AND
												stock_from='".$store."'
											
											AND 
												status ='received' 
											AND type='stock_transfer'
											AND
												status_date<='".$dateStart."'
									) as stock_transfer
									
									
							FROM 
									poll_51_new p51
							
							LEFT JOIN 
									inventory ip
								ON
									ip.product_code=p51.product_code
							WHERE
									p51.product_code ='".$product_code."'
							AND
								ip.store_id='".$store."'
							
							AND 
								status ='received'
							AND
								(
									ip.type='replenish'
									OR
									ip.type='stock_transfer'
									OR
									ip.type='interbranch'
								
								)
						
							AND
								status_date<='".$dateEnd."'
							
								";
		
			$grabParams = array(
			
				'stock',
				'pulloutdate',
				'pullout',
				'damage',
				'stock_transfer'

			);
				
			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $query)) {
		
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);
		
				while (mysqli_stmt_fetch($stmt)) {
		
					$tempArray = array();
		
					for ($i=0; $i < sizeOf($grabParams); $i++) { 
		
						$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
		
					};
		
					$arrBegInventory[] = $tempArray;
		
				};
		
				mysqli_stmt_close($stmt);    
										
			}
			else {
		
				echo mysqli_error($conn);
		
			};



$beginning_total= $arrBegInventory[0]["stock"]-$arrBegInventory[0]["pullout"]-$arrBegInventory[0]["damage"]-$arrBegInventory[0]["stock_transfer"];


			
		   return $beginning_total;
		
		
		}


		
function GetTotalOnTransit2($product_code,$store,$dateStart,$dateEnd){

	global $conn;
	
	
	$arrBegInventory= array();
	
	$query =    "SELECT 
								
								coalesce(sum(ip.count),0),
								date(ip.status_date)
								
								
						FROM 
								poll_51_new p51
						
						LEFT JOIN 
								inventory ip
							ON
								ip.product_code=p51.product_code
						WHERE
								p51.product_code ='".$product_code."'
						AND
							ip.stock_from='".$store."'
						
						AND 
							status ='in transit'
						AND
								status_date<='".$dateEnd."'
						
						
							";
	
		$grabParams = array(
		
			'pulloutcount',
			'pulloutdate'
		);
			
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
	
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2);
	
			while (mysqli_stmt_fetch($stmt)) {
	
				$tempArray = array();
	
				for ($i=0; $i < sizeOf($grabParams); $i++) { 
	
					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
	
				};
	
				$arrFrames[] = $tempArray;
	
			};
	
			mysqli_stmt_close($stmt);    
									
		}
		else {
	
			echo mysqli_error($conn);
	
		};
		
	   return $arrFrames[0]["pulloutcount"];
	
	
	}

?>