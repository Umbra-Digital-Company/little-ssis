<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'store';
$page_url = 'inventory-lookup';

////////////////////////////////////////////////

// Set access for Admin and Store account
if($_SESSION['user_login']['userlvl'] != '3' || $_SESSION['user_login']['position'] !== 'store') {

	header('location: /');
	exit;

};




// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_transferable_items.php";
require $sDocRoot."/inventory/includes/grab_all_moving_stock.php";

require $sDocRoot."/inventory/includes/w_admin_functionv5.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page;  



// if(isset($_GET['frame_code'])){
// 	require $sDocRoot."/inventory/includes/grab_inventory_lookup_v2.php";

// }
$dateStart = date('Y-m-d');
$dateEnd= date('Y-m-t');
$branches=array();
$Names_branch=array();
$phone_branch=array();





if(isset($_GET['frame_code'])){
 
    for ($i=0;$i<sizeof($arrStore);$i++) {
		$store_name=array_push($branches,$arrStore[$i]["store_id"]);
		$store_array=array_push($Names_branch,$arrStore[$i]["store_name"]);
		$storeNo_array=array_push($phone_branch,$arrStore[$i]["phone_number"]);
        $FrameData[$_GET['frame_code']][$arrStore[$i]["store_id"]]= storeChecker_smr($_GET['frame_code'],$arrStore[$i]["store_id"],$dateStart,$dateEnd);

   };


   for ($a=0;$a<sizeof($arrLab);$a++) {
			$lab_array=array_push($branches,$arrLab[$a]["lab_id"]);
			$labname_array=array_push($Names_branch,$arrLab[$a]["lab_name"]);
			$labNo_array=array_push($phone_branch,$arrLab[$a]["phone_number"]);
			$FrameData[$_GET['frame_code']][$arrLab[$a]["lab_id"]]=labChecker_smr($_GET['frame_code'],$arrLab[$a]["lab_id"],$dateStart,$dateEnd);
	};
	

	$warr_array=array_push($branches,'warehouse');
	$warename=array_push($Names_branch,'warehouse');
	$war_no_aray=array_push($phone_branch,'None');
    $FrameData[$_GET['frame_code']]["warehouse"] = WarehouseChecker_smr($_GET['frame_code'],$dateStart,$dateEnd);
}



// Grab Store
$storeName = "";

for ($i=0; $i < sizeOf($arrStore); $i++) { 

	if($arrStore[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

		$storeName = $arrStore[$i]['store_name'];

	};
	
};


			// echo "<pre>";
			// print_r( $FrameData[$_GET['frame_code']]);
			// echo "</pre>";
			// exit;		
	
?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div id="inventory-receive" style="max-width: 768px">
				
				<form action="/inventory/store/inventory-lookup/" method="GET" id="request-form">
					<p class="text-uppercase text-primary font-bold">search items</p>
					<div class="multiple-frame-holder">
						<div class="multiple-frame mt-3">
							<div class="row no-gutters align-items-center frame-field">
								<div class="col pr-3">
									<select name="frame_code" class="select2" required>
										<option value="">Select Item</option>

										<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>

											<option value="<?= $arrItems[$i]['product_code'] ?>"><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?> ( <?= $arrItems[$i]['product_code'] ?> )</option>

										<?php } ?>


									</select>
								</div>
								<!-- <div class="col pr-3 pl-3" style="max-width: 100px">
									<input type="text" name="count" class="form-control" placeholder="#" value="<?= (isset($_GET['count'])) ?$_GET['count'] : '' ?>" required>
								</div> -->
								<button type="submit" class="btn btn-primary" id="search_inventory">search</button>
							</div>
						</div>
					</div>
				</form>

				<?php 
				// echo "<pre>";
				// print_r($arrInvLook);
				// echo "</pre>";

			

				if ( isset($_GET['frame_code']) ) { 
					
					// $sampleData = array(
					// 	array(
					// 		"branch" => "bgc uptown",
					// 		"stock" => "150",
					// 		"contact" => "01234567890"
					// 	),
					// 	array(
					// 		"branch" => "sm north",
					// 		"stock" => "120",
					// 		"contact" => "12345678765"
					// 	),
					// 	array(
					// 		"branch" => "market market",
					// 		"stock" => "118",
					// 		"contact" => "12341234123"
					// 	)											
					// );

					// Grab Style Name
					$frameStyle = "";
					$frameColor = "";

					for ($i=0; $i < sizeOf($arrItems); $i++) { 
					
						$curProductCode  = $arrItems[$i]['product_code'];
						$curProductStyle = $arrItems[$i]['product_style'];
						$curProductColor = $arrItems[$i]['product_color'];

						if($curProductCode == $_GET['frame_code']) {

							$frameStyle = strtolower($curProductStyle);
							$frameColor = strtolower($curProductColor);

						};

					};
					
				?>

					<hr class="spacing">

					<div class="custom-card lg">
						<div class="custom-card-header p-0">
							<section>
								<p class="h3 font-bold"> <?= $FrameData[$_GET['frame_code']]["warehouse"][0]["item_name"] ?> </p>
								<p class="text-secondary mt-1"><?= $_GET['frame_code'] ?></p>
							</section>
						</div>
					</div>

					<hr class="spacing">
				
					<div class="table-default table-responsive mt-4">
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th class="small">branch</th>
									<th class="small">stock</th>
									<th class="small">contact</th>
								</tr>
							</thead>
							<tbody>
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

									
									$beg_inventoryx =$FrameData[$_GET['frame_code']][$branches[$s]][0]["beg_inventory"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["pullout"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["damage"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_out"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["sales_past"]
									-$FrameData[$_GET['frame_code']][$branches[$s]][0]["transit_out"];

									// echo "<br>";
									
									if($branches[$s]=='warehouse'){

									
											if(strpos($FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance"],"-")){
												$beg_inventory=$beg_inventoryx-$FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance"];

											}else{
													$beg_inventory=$beg_inventoryx+$FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance"];
											}
										}else{

									

									// 	}
									// if($beg_inventoryx<'0'){
									// 	// echo "aaaaaa";
									// 		$beg_inventory=$FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"];
									// }else{
									// 	echo "dddd";
									if(strpos($FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance"],"-")){
									
												$beg_inventory=$beg_inventoryx-$FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance"];
											
											}else{
												//
												// echo "cccc"; 
											// 	// $beg_inventory=$beg_inventoryx+$FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance"];

											// 	echo $beg_inventoryx."<br>";
											// 	echo $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"]."<br>";
											// echo 	$varriable  = $beg_inventoryx - $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"];
											// echo "<br>";

											// 	if( $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"]=='0'){
											// 		echo "xx";
											// 		$beg_inventory= $beg_inventoryx;
											// 	}elseif($FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"]>'0' && $varriable!='0' ){
											// 		echo "nn";
											// 	echo	$beg_inventory=$beg_inventoryx - $varriable; 
											// 	}elseif($FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"]>'0' ){
											// 		echo "vv";
											// 		$beg_inventory= $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"] ;
											// 	}else{
											// 		echo "zz";
											// 		$beg_inventory= $beg_inventoryx - $varriable;
											// 	}




												if(  ( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] >=$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"] 
														&&  !empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"] )  && empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_past_date"]  )  )
													
												){
													
											
													$beg_inventory= $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"] - $FrameData[$_GET['frame_code']][$branches[$s]][0]["sales_deduct_physical"];
													

												}
												elseif($FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] >=$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_past_date"] 
												&&  !empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_past_date"]) && empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"] ) ){


													$beg_inventory= $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"] - $FrameData[$_GET['frame_code']][$branches[$s]][0]["sales_deduct_physical"];
												}
												
												elseif( ( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <=$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"]) 
														|| ( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <=$FrameData[$_GET['frame_code']][$branches[$s]][0]["damage_past_date"]) 
														|| ($FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"]!=''  &&  empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"]))
														|| ( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <= $FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_minus_date"]) 
														|| ( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <= $FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_past_date"])
														||  ( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <= $FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_in_past_date"])
														){
														
															if( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <=$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"] || 
															( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"]!=''  &&  empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_status_date"])) ) {
																
															$stok_transfer_beg=$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_in_past"];
															}else{
																$stok_transfer_beg="0";

															}
															


															if( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <=$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_in_past_date"] || 
															( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"]!=''  &&  empty($FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_in_past_date"])) ) {
																
																$interbranch_in_past=$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_in_past"];
															}else{
																$interbranch_in_past="0";

															}
															


															if( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <=$FrameData[$_GET['frame_code']][$branches[$s]][0]["damage_past_date"]) {
																	$damage_beg =$FrameData[$_GET['frame_code']][$branches[$s]][0]["damage"];
															}else{
																$damage_beg ="0";
															}

															if( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <= $FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_past_date"]) {
																$past_interbranch =$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_past"];
														}else{
															$past_interbranch ="0";
														}
														
															
															if(( $FrameData[$_GET['frame_code']][$branches[$s]][0]["audit_date"] <=$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_minus_date"]) ){
															$stock_transfer_beg_minus =$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_minus"];
															echo"aaa";

															}else{
																$stock_transfer_beg_minus = "0";
															}

														


															
													// echo "bbb";
													$beg_inventory=  $FrameData[$_GET['frame_code']][$branches[$s]][0]["past_variance_2"]+ $stok_transfer_beg
													+$interbranch_in_past
													- $FrameData[$_GET['frame_code']][$branches[$s]][0]["sales_deduct_physical"]
													- $damage_beg
													-$stock_transfer_beg_minus
													-$past_interbranch;
												
												}else{
													
														$beg_inventory=$beg_inventoryx;
													}
											}
											// }	
										}		
									}
									$runningtotal=  $beg_inventory +$FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_in_c"]
									+$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_in_c"]- $FrameData[$_GET['frame_code']][$branches[$s]][0]["stock_transfer_out_c"]-
									$FrameData[$_GET['frame_code']][$branches[$s]][0]["interbranch_out_c"]-$FrameData[$_GET['frame_code']][$branches[$s]][0]["damage_c"]-$FrameData[$_GET['frame_code']][$branches[$s]][0]["pullout_c"]-$FrameData[$_GET['frame_code']][$branches[$s]][0]['sales']; 
									// -$sale_frame;
									

							
							
							
							?>
							<tr>
                            <Td><?=$Names_branch[$s]?></td>
                            <td><?= $runningtotal ?></td>
                            <td><?=$phone_branch[$s] ?></td>
                            </tr>
                        <?php }     ?>

                      

							</tbody>
						</table>
					</div>

				<?php } ?>

			</div>

		</div>

	</div>

</div>

						
<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>

<script>

$(document).ready(function() {

	$('input[name="pullout_option"]').on('click', function() {
		if ($('#pullout_option_1').is(':checked')) {
			$('select[name="recipient_branch"]').prop('disabled', false).prop('required', true);
		} else {
			$('select[name="recipient_branch"]').prop('disabled', true).prop('required', false);
		}
	})

})

</script>

<?= get_footer() ?>