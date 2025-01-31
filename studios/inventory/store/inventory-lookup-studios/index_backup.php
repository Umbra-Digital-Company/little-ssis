<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'store';
$page_url = 'inventory-lookup-studios';

$filter_page = 'inventory_lookup_store_studios';
$group_name = 'aim_studios';

////////////////////////////////////////////////

// Set access for Admin and Store account
// if($_SESSION['user_login']['position'] !== 'store') {

// 	if($_SESSION['user_login']['userlvl'] != '1' && $_SESSION['user_login']['userlvl'] != '3'   && $_SESSION['user_login']['userlvl'] != '19' && $_SESSION['user_login']['userlvl'] != '13') {

// 		header('location: /');
// 		exit;

// 	}

// };


// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_transferable_items_studios.php";
// require $sDocRoot."/inventory/includes/grab_all_moving_stock.php";

require $sDocRoot."/inventory/includes/s_admin_functionv2.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
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
 
    for ($i=0;$i<sizeof($arrStudiosStore);$i++) {
		$store_name=array_push($branches,$arrStudiosStore[$i]["store_id"]);
		$store_array=array_push($Names_branch,$arrStudiosStore[$i]["store_name"]);
		$storeNo_array=array_push($phone_branch,"none");
        $FrameData[$_GET['frame_code']][$arrStudiosStore[$i]["store_id"]]= storeChecker_smr($_GET['frame_code'],$arrStudiosStore[$i]["store_id"],$dateStart,$dateEnd);

   };


	

	$warr_array=array_push($branches,'warehouse');
	$warename=array_push($Names_branch,'warehouse');
	$war_no_aray=array_push($phone_branch,'None');
    $FrameData[$_GET['frame_code']]["warehouse"] = WarehouseChecker_smr($_GET['frame_code'],$dateStart,$dateEnd);
}



// Grab Store
$storeName = "";

$MergeStores=array_merge($arrStore, $arrStudiosStore);


// echo $MergeStores[110]['store_id'];

// echo "<pre>";
// print_r($MergeStores);

for ($i=0; $i < sizeOf($MergeStores); $i++) { 

	if($MergeStores[$i]['store_id'] == $_SESSION['user_login']['store_code'] ) {

		$storeName = $MergeStores[$i]['store_name'];

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
				
				<form action="/studios/inventory/store/inventory-lookup-studios/" method="GET" id="request-form">
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



										
									if(($arrColumnData['beg_inventory'] =='0'  || $arrColumnData['beg_inventory'] ==''  ) &&
									$arrColumnData['pullout'] =='0' &&
									$arrColumnData['damage'] =='0' &&
									$arrColumnData['stock_transfer_out'] =='0' &&
									$arrColumnData['stock_transfer_in_c'] =='0' &&
									$arrColumnData['stock_transfer_out_c'] =='0' &&
									$arrColumnData['interbranch_out_c'] =='0' &&
									$arrColumnData['interbranch_in_c'] =='0' &&
									$arrColumnData['pullout_c'] =='0' &&
									$arrColumnData['damage_c'] =='0' &&
									$arrColumnData['damage_i'] =='0' &&
									$arrColumnData['sales'] =='0' &&
									$arrColumnData['number'] =='0' &&
									$arrColumnData['transit_out'] =='0' &&
									$arrColumnData['requested'] =='0' &&
									$arrColumnData['transit_in'] =='0' &&
									$arrColumnData['transit_out_c'] =='0' &&
									$arrColumnData['sales_deduct_physical'] =='0'
								
									 ){
										$beg_inventoryfloat = 0;
										$beg_inventoryfloat += $arrColumnData["past_variance_2"];
										 $beg_inventory= $beg_inventoryfloat;
							
									}
									else{
										
									
									
										$beg_inventoryx =$arrColumnData["beg_inventory"];
										$beg_inventory=$beg_inventoryx;
								   
									}
								
									$arrColumnData['beg_inventory'] = $beg_inventory;
							
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