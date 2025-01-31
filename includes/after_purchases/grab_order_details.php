<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
function getdetailsPoll51($item_code){
	global $conn;
	$arrPOll51item=array();

		 $queryItem=" Select item_description
					 FROM
							poll_51
					WHERE
						product_code='".$item_code."' ";
	$grabParams = array("item_name");

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $queryItem)) {

		  mysqli_stmt_execute($stmt);
		  mysqli_stmt_bind_result($stmt, $result1);

		  while (mysqli_stmt_fetch($stmt)) {

		    $tempArray = array();

		    for ($i=0; $i < sizeOf($grabParams); $i++) { 

		      $tempArray[$grabParams[$i]] = $result1;

		    };
		  }

	    $arrPOll51item[] = $tempArray;
	 }

  	mysqli_stmt_close($stmt);
 	return $arrPOll51item;
}
// Set array
$arrOrders_details = array();

$grabParams = array(
	"first_name",
	"middle_name",
	"last_name",
	"email_address",
	"birthday",
	"po_number",
	"lens_option",
	"lens_code",
	"product_code",
	"prescription_id",
	"product_upgrade",
	"osprice",
	"orders_specs_id",
	"item_description",
	"status",
	"payment_date",
	"color",
	"warranty",
	"warranty_reason",
	"warranty_date",
	"warranty_store_claim"
);

$query  = 	"SELECT
				pi.first_name,
				pi.middle_name,
				pi.last_name,
				pi.email_address,
				pi.birthday,
				os.po_number,
				os.lens_option,
				os.lens_code,
				os.product_code,
				os.prescription_id,
				os.product_upgrade,
				os.price,
				os.orders_specs_id,
				LOWER(TRIM(LEFT(pr.item_name , LOCATE(' ', pr.item_name) - 1))),
				os.status,
				os.payment_date,
				p.color,
				os.warranty,
				os.waranty_reason,
				os.warranty_date,
				os.warranty_store_claim
				FROM orders_test o
				LEFT JOIN orders_specs_test os ON o.order_id =  os.order_id
				LEFT JOIN profiles_info pi ON pi.profile_id =o.profile_id
				LEFT JOIN stores_locations sl ON os.warranty_store_claim = sl.store_id
				LEFT JOIN poll_51 pr ON pr.product_code = os.product_code
				LEFT JOIN products p ON os.product_code = p.product_code
				WHERE os.orders_specs_id = '".$_GET['orders_specs_id']."'";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,  $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

        };
        $date_now = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').'+12hours'));
        $am_pm = (trim($tempArray['warranty_date']) != '') ? explode(' ', $tempArray['warranty_date']) : explode(' ',$date_now);
        $am_pm = explode(":", $am_pm[1]);
        $am_pm = ($am_pm[0] < 12) ? ' AM' : ' PM';
        $tempArray['warranty_date'] = (trim($tempArray['warranty_date']) != '') ? date('m/d/Y h:i:s', strtotime($tempArray['warranty_date'])).$am_pm : date('m/d/Y h:i:s', strtotime($date_now)).$am_pm;
        $am_pm = explode(' ', $tempArray['payment_date']);
        $am_pm = explode(":", $am_pm[1]);
        $am_pm = ($am_pm[0] < 12) ? ' AM' : ' PM';
        $tempArray['payment_date'] = ($tempArray['payment_date'] != '') ? date('m/d/Y h:i:s', strtotime($tempArray['payment_date'])).$am_pm : 'N/A';
        $tempArray['birthday'] = date('m/d/Y', strtotime($tempArray['birthday']));

        $am_pm = explode(' ', $date_now);
        $am_pm = explode(":", $am_pm[1]);
        $am_pm = ($am_pm[0] < 12) ? ' AM' : ' PM';
        $tempArray['claiming_date'] =  date('m/d/Y h:i:s', strtotime($date_now)).$am_pm;
        $arrOrders_details[] = $tempArray;

    };

    mysqli_stmt_close($stmt);
    $div_details = '';
    for($y = 0; $y < count($arrOrders_details); $y++){
	    if ($arrOrders_details[$y]['lens_option'] == 'lens only') {
			$image_url = "/images/icons/icon-lens-only.png";
			$titleName = "Lens Only";
			$framePrice = 0;
		} else {
			if ( file_exists($sDocRoot.'/images/specs/'.$arrOrders_details[$y]['item_description'].'/'.str_replace(" ", "-", trim($arrOrders_details[$y]['color'])).'/front.png') ) {
				$image_url = '/images/specs/'.$arrOrders_details[$y]['item_description'].'/'.str_replace(" ", "-", trim($arrOrders_details[$y]['color'])).'/front.png';
			} else {
				$image_url = "/images/specs/no-image/no_specs_frame_available_b.png";
			}
		}
	    $div_details .= '<div class="list-item frame d-flex no-gutters align-items-center justify-content-between" data-required="no">
	    	<input type="hidden" name="orders_specs_id" value="'. $arrOrders_details[$y]["orders_specs_id"].'"/>
				<div class="d-flex align-items-center">';
				 if ( $arrOrders_details[$y]["lens_option"] != 'service'  &&  $arrOrders_details[$y]["product_code"]!='M100' &&  $arrOrders_details[$y]["product_code"]!='S100') {

						$div_details .= '<img src="'.$image_url.'" class="img-fluid frame-preview">';
				}else{
					$div_details .= '<span style="display: block; width: 100px;"></span>';
				}							
				$div_details .='<div>';
					 if ( $arrOrders_details[$y]["lens_option"] != 'service'  &&  $arrOrders_details[$y]["product_code"]!='M100' &&  $arrOrders_details[$y]["product_code"]!='S100') {
									
						$div_details .= '<span class="font-bold">'.ucwords( $arrOrders_details[$y]["item_description"] )."</span> ". ucwords( $arrOrders_details[$y]["color"] );
					 } else {
					 	$service_item_poll = getdetailsPoll51($arrOrders_details[$y]["product_upgrade"]);
					 	$capitalize_item = ucwords(strtolower($service_item_poll[0]["item_name"]));
					 	$div_details .= '<span style="display: block; width: 100px;"></span>';
					 	$div_details .= '<span class="font-bold">'.str_replace("Lbc","LBC",$capitalize_item).'</span>';	
					}
					if ( $arrOrders_details[$y]['lens_option'] == 'without prescription' || $arrOrders_details[$y]['lens_option'] == 'service' ){
						if($arrOrders_details[$y]['lens_code'] == 'L035') {

							$lensOption = 'Kids Plano Screen Safe';

						}
						else {
							if($arrOrders_details[$y]['product_code']=='M100'){
									$lensOption="Merch";
							}elseif($arrOrders_details[$y]['product_code']=='S100'){
								$lensOption="Services";
							}else{
								$lensOption = ucwords( $arrOrders_details[$y]["lens_option"] );
							}
						};
						$div_details .= '<p class="small text-secondary">'.ucwords($lensOption).'</p>';
					}
					else{
						$div_details .= ( $arrOrders_details[$y]['prescription_id'] != '' ) ?
						'<p class="small text-secondary">'.ucwords(str_replace(["_","-"]," ",$arrOrders_details[$y]["product_upgrade"])).'</p>'
							:
						'<p class="small text-danger">No Prescription Attached</p>';
					}
				$div_details .='</div>
				</div>';
				$price = ( $arrOrders_details[$y]['lens_option'] == 'without prescription' || $arrOrders_details[$y]['lens_option'] == 'service' || $arrOrders_details[$y]['prescription_id'] != '' ) ? 'P'.number_format($arrOrders_details[$y]['osprice']) : '-';
						$div_details .= '<p class="small text-primary font-bold frame-price">'.$price.'</p>';
					
		$div_details .='</div>';
	}
	$arrLogs = [];
	$grabParams = array(
		"warranty_type",
		"duration",
		"store_name",
		"claimed_date"
	);

	$query  = 	"SELECT
					wt.description,
					wt.duration,
					sl.store_name,
					wl.claimed_date
					FROM warranty_logs wl 
					LEFT JOIN stores_locations sl ON wl.warranty_store_claim =  sl.store_id
					LEFT JOIN warranty_type wt ON wl.warranty_type_id = wt.id
					WHERE wl.orders_specs_id = '".$_GET['orders_specs_id']."'";
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);
	    $logs = "";
	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

	        };
	        $am_pm = explode(' ', $tempArray['claimed_date']);
	        $am_pm = explode(":", $am_pm[1]);
	        $am_pm = ($am_pm[0] < 12) ? ' AM' : ' PM';
	        $tempArray['claimed_date'] = date('m/d/Y h:i:s', strtotime($tempArray['claimed_date'])).$am_pm;
	        $logs .= "<tr><td>".$tempArray['warranty_type']." - ".$tempArray['duration']."</td><td>".$tempArray['store_name']."</td><td>".$tempArray['claimed_date']."</td></tr>";

	    };
	}

	mysqli_stmt_close($stmt);
	$json = [];
	$json['claim'] = ($arrOrders_details[0]['warranty'] == 'y') ? true : false;
	$json['header'] = $arrOrders_details;
	$json['details'] = $div_details;
	$json['warranty_logs'] = ($logs != '') ? $logs : "<tr><td style='text-align:center;' colspan='3'>No Warranty Claimed Record</td>";
    echo json_encode($json);   
                            
}
else {

    echo mysqli_error($conn);

}; 

?>