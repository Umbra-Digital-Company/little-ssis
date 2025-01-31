<?php

// ========================= grab all customers who rates the system

// $query =" SELECT * FROM store_ratings WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(date_created, '%Y-%m') and store_id='".$_SESSION['store_code']."'"  ;

// $grabParams = array(
// 	'id',
// 	'date_created',
// 	'date_updated',
// 	'store_id',
// 	'profile_id',
// 	'rating_status',
// 	'feedback'
// );

// $stmt = mysqli_stmt_init($conn);

// if (mysqli_stmt_prepare($stmt, $query)) {
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

//     while (mysqli_stmt_fetch($stmt)) {

//         $tempArray = array();

//         for ($i=0; $i < 7; $i++) { 

//             $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

//         };

//         $arrTotalRates[] = $tempArray;

//     };

//     mysqli_stmt_close($stmt);    
                            
// }
// else {

//     echo mysqli_error($conn);

// };


// // ================================= grab the type of rates

// function get_rates_status($where) {
// 	global $conn;

// 	$query =" SELECT * FROM store_ratings WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(date_created, '%Y-%m') AND rating_status = '".$where."' and store_id='".$_SESSION['store_code']."' ";

// 	$grabParams = array(
// 		'id',
// 		'date_created',
// 		'date_updated',
// 		'store_id',
// 		'profile_id',
// 		'rating_status',
// 		'feedback'
// 	);

// 	$stmt = mysqli_stmt_init($conn);

// 	if (mysqli_stmt_prepare($stmt, $query)) {
// 		mysqli_stmt_execute($stmt);
// 		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

// 		while (mysqli_stmt_fetch($stmt)) {

// 			$tempArray = array();

// 			for ($i=0; $i < 7; $i++) { 

// 				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 			};

// 			$arrTypesOfRates[] = $tempArray;

// 		};

// 		mysqli_stmt_close($stmt);    

// 		if ( !empty($arrTypesOfRates) ) {
// 			$count = sizeof($arrTypesOfRates);
// 		} else {
// 			$count = 0;
// 		}

// 		return $count;
								
// 	}
// 	else {

// 		echo mysqli_error($conn);

// 	};
// }

// function get_total_rates($where) {
// 	global $conn;

// 	$query =" SELECT * FROM store_ratings WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(date_created, '%Y-%m') AND rating_status != '".$where."'  and store_id='".$_SESSION['store_code']."' ";

// 	$grabParams = array(
// 		'id',
// 		'date_created',
// 		'date_updated',
// 		'store_id',
// 		'profile_id',
// 		'rating_status',
// 		'feedback'
// 	);

// 	$stmt = mysqli_stmt_init($conn);

// 	if (mysqli_stmt_prepare($stmt, $query)) {
// 		mysqli_stmt_execute($stmt);
// 		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

// 		while (mysqli_stmt_fetch($stmt)) {

// 			$tempArray = array();

// 			for ($i=0; $i < 7; $i++) { 

// 				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 			};

// 			$arrTypesOfRates[] = $tempArray;

// 		};

// 		mysqli_stmt_close($stmt);    

// 		if ( !empty($arrTypesOfRates) ) {
// 			$count = sizeof($arrTypesOfRates);
// 		} else {
// 			$count = 0;
// 		}

// 		return $count;
								
// 	}
// 	else {

// 		echo mysqli_error($conn);

// 	};
// }

?>