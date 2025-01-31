<?php 


//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

////////////////////////////// SEARCH

// Set search term

	$email= (isset($_GET['email_address']) && trim($_GET['email_address']) != '') ? " pi.email_address='".$_GET['email_address']."' " : " pi.email_address='@umbradigital' ";
	
	$andor = (isset($_GET['email_address']) && trim($_GET['email_address']) != '') ? ' AND ' : '';
	$birthdate= (isset($_GET['birth_date']) && trim($_GET['birth_date']) != '') ? $andor."pi.birthday ='".$_GET['birth_date']."' " : '';
	$email = (isset($_GET['birth_date']) && (isset($_GET['email_address']) && trim($_GET['email_address'])) == '') ? '' : $email;
	$arrCustomerP = array();

	$grabParams = array(	
		"profile_id",
		"first_name",
		"middle_name",
		"last_name",
		"gender",
		"age_recorded",
		"age",
		"branch_applied_code",
		"branch_applied_name",	
		"last_payment_date"
	);

	 $query  = 	"SELECT					
					pi.profile_id,
					pi.first_name,
					pi.middle_name,
					pi.last_name,	
					pi.gender,
					pi.age,
					DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0,
					pi.branch_applied,
					sc.branch,				
					os.payment_date
				FROM 
					profiles p
						LEFT JOIN profiles_info pi
							ON pi.profile_id = p.profile_id
						LEFT JOIN store_codes sc
							ON sc.location_code = pi.branch_applied
						LEFT JOIN orders o 
							ON o.profile_id = p.profile_id
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						
				WHERE
					".$email."
					".$birthdate."
				
						
						
				GROUP BY
					pi.profile_id	
				ORDER BY
					os.date_created DESC, pi.joining_date DESC		
						LIMIT 20
				";
		// echo $query;		
		// exit;	
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $arrCustomerP[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

// echo '<pre>';
// print_r($arrCustomerP);
// echo '</pre>';
// exit;

?>