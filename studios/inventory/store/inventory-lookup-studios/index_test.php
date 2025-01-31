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

// require $sDocRoot."/inventory/includes/grab_all_moving_stock.php";
$stmtBig = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	mysqli_stmt_execute($stmtBig);
	mysqli_stmt_close($stmtBig);

}
else {

	echo mysqli_error($conn);

}
// require $sDocRoot."/inventory/includes/w_admin_functionv14.php";

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

$arrStoreData = [];
if(isset($_GET['frame_code'])){
	for ($i=0;$i<sizeof($arrStudiosStore);$i++) {
			$arrStoreData[] = ['storelab' => 'store', 'store_id' => $arrStudiosStore[$i]['store_id'],'store_name' => $arrStudiosStore[$i]['store_name'],'phone_number' => "none"];
	}
	

	$arrStoreData[] = ['storelab' => 'warehouse', 'store_id' => 'warehouse' ,'store_name' => 'warehouse' ,'phone_number' => 'none'];
}

	
?>
<?php 
// echo "<pre>";
// print_r($arrStoreData);
// echo "</pre>";
 ?>
<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div id="inventory-receive" style="max-width: 768px">
				
				<form id="request-form">
					<p class="text-uppercase text-primary font-bold">search items</p>
					<div class="multiple-frame-holder">
						<div class="multiple-frame mt-3">
							<div class="row no-gutters align-items-center frame-field">
								<div class="col pr-3">
									<select name="frame_code" id="frame_code" class="select2" required>
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
								<p class="h3 font-bold"> <?= (isset($_GET['frame_name'])) ? $_GET['frame_name'] : '' ?> </p>
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
									<!-- <th class="small">Display</th> -->
									<th class="small">contact</th>
								</tr>
							</thead>
							<tbody>
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
let arrStore = <?= json_encode($arrStoreData, true) ?>;
$(document).ready(function() {

	$('#request-form').submit(function(e){
		e.preventDefault();
		window.location = '?frame_code='+$('#frame_code').val()+'&frame_name='+$('#frame_code option:selected').text();
	});

	$('input[name="pullout_option"]').on('click', function() {
		if ($('#pullout_option_1').is(':checked')) {
			$('select[name="recipient_branch"]').prop('disabled', false).prop('required', true);
		} else {
			$('select[name="recipient_branch"]').prop('disabled', true).prop('required', false);
		}
	})

	frame = '<?= (isset($_GET['frame_code'])) ? $_GET['frame_code'] : ""?>';

	// for(i = 0; i < arrStore.length; i++){
	// 	$.get('get_data.php',{frame_code:frame, arrStore: arrStore[i] },function(result){
	// 			$('table tbody').append(result);
	// 	});
	// 	// return false;
	// }

	function getData($rowCount){
		$.get('get_data2.php',{frame_code:frame, arrStore: arrStore[$rowCount] },function(result){
			
				$('table tbody').append(result);

				if($rowCount < arrStore.length-1){
					
					getData($rowCount+1);
				}
		});
	}
	getData(0);

});

</script>

<?= get_footer() ?>