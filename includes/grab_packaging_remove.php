<?php 

// Set array
$arrCustomer = [];
$for_po_number = (isset($_GET['po_number'])) ? 'AND os.packaging_for = "'.$_GET['po_number'].'"' :'';
$querypn = 	"SELECT 
 				p.first_name,
				p.last_name,
				os.product_code,
				os.prescription_id,
				IF(
					os.product_upgrade = 'special_order',
					os.product_upgrade,
					IF(
						os.lens_code IN ('L016', 'L018', 'L020', 'L021', 'L023', 'L022', 'L024' ,'L049', 'L050', 'L051', 'L052', 'L053' ,'SO1001'),
						'special_order',
						os.product_upgrade
					)
				),
				os.order_id,
				os.po_number,
				os.orders_specs_id,
				 os.packaging,
				 os.packaging_date,
				 os.packaging_for,
				 os.lens_code,
				 ps.item_description,
				 psp.item_description
			FROM 
				profiles_info p
					LEFT JOIN orders_specs os 
						ON os.profile_id=p.profile_id
					LEFT  JOIN orders o 
						ON o.order_id=os.order_id 
					LEFT JOIN poll_51_studios ps 
							ON ps.product_code = os.product_upgrade
					LEFT JOIN 
							poll_51 psp
							ON psp.product_code = os.product_upgrade
			WHERE 
				os.payment='y'  
				".$for_po_number."
					 AND os.status!='for exam'
					 AND os.status!='for payment'
					 AND os.po_number!=''
					 AND os.orders_specs_id!=''
					AND os.status!='cancelled' 					
 					AND (
						 	o.store_id = '".$_SESSION['store_code']."'
								OR
							(		o.origin_branch ='".$_SESSION["store_code"]."'
                  				   and o.store_id!='".$_SESSION["store_code"]."'
									 )
					 		)
 			ORDER BY 
				os.product_upgrade ASC";
				// os.order_id like '".$_SESSION["store_code"]."-%'
$grabParams = array(
    'first_name',
    'last_name',
    'product_code',
    'prescription_id',
    'product_upgrade',   
    'order_id',   
	'po_number',
	'orders_specs_id',
	'packaging',
	'packaging_date',
	'packaging_for',
	'lens_code',
	'description_studios',
	'description',
	
	
);

 $query2 = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query2)) {

    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,
	 $result10, $result11, $result12, $result13, $result14);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };
        $tempArray['packaging_date'] = date('Y-m-d H:i:s', strtotime($tempArray['packaging_date'].'+12 hours'));
        $arrCustomer[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

//print_r($arrCustomer);

?>