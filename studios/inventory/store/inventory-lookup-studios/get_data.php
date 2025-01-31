<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";

require $sDocRoot."/inventory/includes/grab_all_transferable_items_studios.php";
require $sDocRoot."/inventory/includes/grab_all_moving_stock_studios.php";
$stmtBig = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	mysqli_stmt_execute($stmtBig);
	mysqli_stmt_close($stmtBig);

}
else {

	echo mysqli_error($conn);

}
require $sDocRoot."/inventory/includes/s_admin_functionv2.php";

$dateStart = date('Y-m-d');
$dateEnd= date('Y-m-t');
$branches=array();
$Names_branch=array();
$phone_branch=array();

$FrameData=array();

$arrStore = $_GET['arrStore'];

if(isset($_GET['frame_code']) && $_GET['frame_code'] != ''){
 
  	if($arrStore["storelab"] == 'warehouse'){
		$warr_array=array_push($branches,'warehouse');
		$warename=array_push($Names_branch,'warehouse');
		$war_no_aray=array_push($phone_branch,'None');
	    $FrameData[$_GET['frame_code']]["warehouse"] = WarehouseChecker_smr($_GET['frame_code'],$dateStart,$dateEnd);
	}elseif($arrStore["storelab"] == 'store'){

		
		$store_name=array_push($branches,$arrStore["store_id"]);
		$store_array=array_push($Names_branch,$arrStore["store_name"]);
		$storeNo_array=array_push($phone_branch,$arrStore["phone_number"]);
		if($arrStore["store_id"]=='5000470' || $arrStore["store_id"]=='5000472' || $arrStore["store_id"]=='5000473'  || $arrStore["store_id"]=='6008053'  || $arrStore["store_id"]=='6008054'
		|| $arrStore["store_id"]=='6008055'  || $arrStore["store_id"]=='SP-MPWHC' || $arrStore["store_id"]=='6008058'|| $arrStore["store_id"]=='6008059'
				|| $arrStore["store_id"]=='6008060'|| $arrStore["store_id"]=='5000476'
				|| $arrStore["store_id"]=='5000477'|| $arrStore["store_id"]=='5000478'){ 
					$FrameData[$_GET['frame_code']]["SP-MPWHC"]= storeChecker_smr($_GET['frame_code'],'SP-MPWHC',$dateStart,$dateEnd);
				}
				else{
      			  $FrameData[$_GET['frame_code']][$arrStore["store_id"]]= storeChecker_smr($_GET['frame_code'],$arrStore["store_id"],$dateStart,$dateEnd);
			}
	}
	elseif($arrStore["storelab"] == 'lab'){
		$lab_array=array_push($branches,$arrStore["store_id"]);
		$labname_array=array_push($Names_branch,$arrStore["store_name"]);
		$labNo_array=array_push($phone_branch,"none");
		$FrameData[$_GET['frame_code']][$arrStore["store_id"]]=labChecker_smr($_GET['frame_code'],$arrStore["store_id"],$dateStart,$dateEnd);
	}
	
}
?>

                        <?php   for ($s=0; $s < sizeOf($branches); $s++) {  
															
															
									if(empty($FrameData[$_GET['frame_code']])){

										$FrameData[$_GET['frame_code']][$branches[$s]][0]['beg_inventory'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['pullout'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['damage'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['stock_transfer_out'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['stock_transfer_in_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['stock_transfer_out_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['interbranch_out_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['interbranch_in_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['pullout_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['damage_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['damage_i'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['sales'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['number'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['transit_out'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['requested'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['transit_in'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['transit_out_c'] ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['past_variance']  ='0';
										$FrameData[$_GET['frame_code']][$branches[$s]][0]['sales_past']  ='0';

										
									}



										
									if(($FrameData[$_GET['frame_code']][$branches[$s]][0]['beg_inventory'] =='0'  || $FrameData[$_GET['frame_code']][$branches[$s]][0]['beg_inventory'] ==''  ) &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['pullout'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['damage'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['stock_transfer_out'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['stock_transfer_in_c'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['stock_transfer_out_c'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['interbranch_out_c'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['interbranch_in_c'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['pullout_c'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['damage_c'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['damage_i'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['sales'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['number'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['transit_out'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['requested'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['transit_in'] =='0' &&
									$FrameData[$_GET['frame_code']][$branches[$s]][0]['transit_out_c'] =='0' 

									){
										// echo "aaaaaaaaaa";
										$beg_inventory= $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"];

									}
									else{
                                        $beg_inventoryx =$FrameData[$_GET['frame_code']][$branches[$s]][0]['beg_inventory'];
                                        $beg_inventory=$beg_inventoryx;
									}


									// $display=$FrameData[$_GET['frame_code']][$branches[$s]][0]["display_in"]-$FrameData[$_GET['frame_code']][$branches[$s]][0]["display_out"];
									// $display_column = str_replace('-','',$display);
							
									$runningtotal=  $beg_inventory +$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_in_c"]
									+$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_in_c"]
									- $FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_out_c"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_c"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["damage_c"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["pullout_c"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]['sales']; 
									// -$sale_frame;
									

                                    // $display_column
							
							
							?>
							<tr>
                            <td><?=$Names_branch[$s]?></td>
                            <td><?= $runningtotal ?></td>
							<!-- <td></td> -->
                            <td><?=$phone_branch[$s] ?></td>
                            </tr>
                        <?php }     ?>

