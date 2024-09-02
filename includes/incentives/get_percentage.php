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
	$sramDataBelow = 0;
	$amDataBelow = 0;
	$supareaDataBelow = 0;
	$staffBelow = 0;

	$sramDataAbove = 0;
	$amDataAbove = 0;
	$supareaDataAbove = 0;
	$staffAbove = 0;
	if($valueThreshold != 'n'){
		$arrPercentageBelowData = $arrPercentageBelow[$line['store_id']];
		foreach ($arrPercentageBelowData as $key => $value) {
			
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$sramDataBelow = $value['sr_area_manager']/100;
				$amDataBelow = $value['area_manager']/100;
				$supareaDataBelow = $value['area_supervisor']/100;
				$staffBelow = $value['staff']/100;
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$sramDataBelow = $value['sr_area_manager']/100;
				$amDataBelow = $value['area_manager']/100;
				$supareaDataBelow = $value['area_supervisor']/100;
				$staffBelow = $value['staff']/100;

				break;

			}
		}

		$arrPercentageAboveData = $arrPercentageAbove[$line['store_id']];
		foreach ($arrPercentageAboveData as $key => $value) {
			
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$sramDataAbove = $value['sr_area_manager']/100;
				$amDataAbove = $value['area_manager']/100;
				$supareaDataAbove = $value['area_supervisor']/100;
				$staffAbove = $value['staff']/100;
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$sramDataAbove = $value['sr_area_manager']/100;
				$amDataAbove = $value['area_manager']/100;
				$supareaDataAbove = $value['area_supervisor']/100;
				$staffAbove = $value['staff']/100;

				break;

			}
		}
	}
?>