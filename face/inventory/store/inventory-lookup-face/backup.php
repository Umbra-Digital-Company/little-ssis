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



// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page;  



if(isset($_GET['frame_code'])){
	require $sDocRoot."/inventory/includes/grab_inventory_lookup.php";

}
// Grab Store
$storeName = "";

for ($i=0; $i < sizeOf($arrStore); $i++) { 

	if($arrStore[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

		$storeName = $arrStore[$i]['store_name'];

	};
	
};

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
								<p class="h3 font-bold"><?= ucwords($frameStyle) ?> in <?= ucwords($frameColor) ?></p>
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

								<?php
								
								// echo "<pre>";
								// 	print_r($arrInvLook);
								// 	echo "<pre>";
									
									for ($i=0; $i<sizeof($arrInvLook); $i++) {
								if($arrInvLook[$i]['store_name']=='warehouse')	{
									
									// echo $beg_inventory."<br>"; 
									// echo $arrInvLook[$i]["stock_transfer_in_c"]."<br>"; 
									// echo $arrInvLook[$i]["interbranch_in_c"]."<br>"; 
									// echo $arrInvLook[$i]["stock_transfer_out_c"]."<br>"; 
									// echo $arrInvLook[$i]["interbranch_out_c"]."<br>"; 
									// echo $arrInvLook[$i]["damage_c"]."<br>"; 
									// echo $arrInvLook[$i]["pullout_c"]."<br>";
									
								}
										$beg_inventory = $arrInvLook[$i]["beg_inventory"]-$arrInvLook[$i]["pullout"]-$arrInvLook[$i]["damage"]-$arrInvLook[$i]["stock_transfer_out"]-$arrInvLook[$i]["sales"];
										$runningtotal=  $beg_inventory +$arrInvLook[$i]["stock_transfer_in_c"]
											+$arrInvLook[$i]["interbranch_in_c"]- $arrInvLook[$i]["stock_transfer_out_c"]-
											$arrInvLook[$i]["interbranch_out_c"]-$arrInvLook[$i]["damage_c"]-$arrInvLook[$i]["pullout_c"]-$arrInvLook[$i]["interbranch_out"]; 
											// echo $runningtotal;
									?>
								
									<tr>
										<td class="text-uppercase"><?= $arrInvLook[$i]['store_name'] ?></td>
										<td><?= $runningtotal ?></td>
										<td><?=   $arrInvLook[$i]['number'] ?></td>
									</tr>

								<?php } ?>

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