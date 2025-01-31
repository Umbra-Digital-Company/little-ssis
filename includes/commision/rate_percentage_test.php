<?php

	$arrProductPercentage = [];
	function getPercentage($store_code,$product_code, $payment_date){
		global $conn;
		global $arrProductPercentage;

		if(!array_key_exists($store_code.'|'.$product_code, $arrProductPercentage)){
			$arrPercentage = array();
			$query = "SELECT
							threshold,
							rate_percentage,
							date_from,
							date_to
						FROM commission_lens
						WHERE
							store_code = '".$store_code."'
							AND product_code = '".$product_code."'
			             ORDER BY id DESC";

			$grabParams = array(
				'threshold',
			    'rate_percentage',
			    'date_from',
			    'date_to'
			);

			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $query)) {
			    
			    mysqli_stmt_execute($stmt);
			    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

			    while (mysqli_stmt_fetch($stmt)) {

			        $tempArray = array();

			        for ($i=0; $i < sizeOf($grabParams); $i++) { 

			            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			        };
			        $arrPercentage[] = $tempArray;

			    };

			    mysqli_stmt_close($stmt);    
			                            
			}
			else {

			    echo mysqli_error($conn);

			};
			foreach ($arrPercentage as $key => $value) {
				$arrProductPercentage[$store_code.'|'.$product_code][] = [
					'threshold' => $value['threshold'], 
					'rate_percentage' => $value['rate_percentage'],
					'date_from' => $value['date_from'],
					'date_to' => $value['date_to']
				];
			}
		}
		$arr = [];
		foreach ($arrProductPercentage[$store_code.'|'.$product_code] as $key => $value) {
			$payment_date = date('Y-m-d', strtotime($payment_date));
			if(strtotime($payment_date) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){
				$arr['threshold'] = $value['threshold'];
				$arr['rate'] = $value['rate_percentage'];
				break;
			}elseif (strtotime($payment_date) >= strtotime($value['date_from']) && strtotime($payment_date) <= strtotime($value['date_to'])) {
				$arr['threshold'] = $value['threshold'];
				$arr['rate'] = $value['rate_percentage'];
				break;
			}
		}

		return $arr;
	}
?>