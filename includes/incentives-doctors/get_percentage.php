<?php
	getAreaPercentage($line['store_id'], date('Y-m-d', strtotime($line['payment_date'])));

	
	$valueThreshold = 'n';
	if(isset($arrThreshold[$line['store_id']])){
		$arrThresholdData = $arrThreshold[$line['store_id']];
		foreach ($arrThresholdData as $key => $value) {
			
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$valueThreshold = $value['threshold'];
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$valueThreshold = $value['threshold'];
				break;

			}
		}
	}
	$line['quantity'] = 1;
	$line['excess'] = ($valueThreshold != 'n') ? $line['total'] - $valueThreshold : 0;

	//below and above threshold percentage
	$adocDataBelow = 0;
	$cordocDataBelow = 0;

	$adocDataAbove = 0;
	$cordocDataAbove = 0;
	if($valueThreshold != 'n'){
		$arrPercentageBelowData = $arrPercentageBelow[$line['store_id']];
		foreach ($arrPercentageBelowData as $key => $value) {
			
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$adocDataBelow = $value['area_doctor']/100;
				$cordocDataBelow = $value['corporate_doctor']/100;
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$adocDataBelow = $value['area_doctor']/100;
				$cordocDataBelow = $value['corporate_doctor']/100;

				break;

			}
		}

		$arrPercentageAboveData = $arrPercentageAbove[$line['store_id']];
		foreach ($arrPercentageAboveData as $key => $value) {
			
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$adocDataAbove = $value['area_doctor']/100;
				$cordocDataAbove = $value['corporate_doctor']/100;
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$adocDataAbove = $value['area_doctor']/100;
				$cordocDataAbove = $value['corporate_doctor']/100;

				break;

			}
		}
	}
?>