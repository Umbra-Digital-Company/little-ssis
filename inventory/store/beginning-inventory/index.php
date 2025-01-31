<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

// $page = 'store';
$page = 'beginning-inventory';

$filter_page = 'beginning_inventory_store';
$group_name = 'main_menu';

////////////////////////////////////////////////

// Set access for Admin and Store account
// if($_SESSION['user_login']['userlvl'] != '3' || $_SESSION['user_login']['position'] !== 'store') {

// 	header('location: /');
// 	exit;

// }

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_transferable_items.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v2.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 


function BegStoreSetup($store_id){

	global $conn;
	$arrBegInventorysetup=array();
	$query="SELECT `action` FROM inventory_store_setup WHERE store_code='".$_SESSION['user_login']['store_code']."'";


	$grabParams = array(
			
		'action'

	);
		
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrBegInventorysetup[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

	};

	if($arrBegInventorysetup[0]["action"]=='y'){
		header('location: /');
		exit;

	}else{

	}

	
}


echo BegStoreSetup($_SESSION['user_login']['store_code']);
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

			<form action="/inventory/process/store/beginning_inventory.php" method="POST" id="pullout-form" autocomplete="off">
				<input type="hidden" name="signature" class="signature64" value="">
				<div class="row align-items-start">
					<div class="col-12 col-lg-8">
						<div class="custom-card">
							<div class="table-responsive">
								<table class="table table-striped mb-0" id="multiple-item-row">
									<thead>
										<tr>
											<th class="small text-white text-uppercase">Item Name</th>
											<th class="small text-white text-uppercase">Stock</th>
										</tr>
									</thead>
									<tbody>
									<input type="hidden" name="recipient_branch" value="<?= $_SESSION['user_login']['store_code']?>">
									
										<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>
											
											<tr>
												<td style="width: 70%">
													<input type="hidden" name="product_code[]" value="<?= $arrItems[$i]['product_code'] ?>">
													<!-- <input type="text" name="product_name[]" class="form-control" value="<?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?> ( <?= $arrItems[$i]['product_code'] ?> )"> -->
													<p><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?></p>
													<p class="small font-bold"><?= $arrItems[$i]['product_code'] ?></p>
												</td>
												<td>
													<input type="text" name="product_stock[]" class="form-control filled" placeholder="#" required value="0">
												</td>
											</tr>

										<?php } ?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4">
						<p class="text-primary text-uppercase font-bold">created by</p>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Employee ID</h5>
							<input type="text" name="emp_id" class="form-control" placeholder="ID #" required>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Signature</h5>
							<div class="custom-card">
								<div class="canvas-holder text-center">
									<canvas id="thecanvas" width="475" height="148" border="1"></canvas>
									<div class="row no-gutters justify-content-center mt-3">
										<a href="#" class="text-uppercase save text-success pl-3 pr-3 d-block">save</a>
										<a href="#" class="text-uppercase clear text-danger pl-3 pr-3 d-block">clear</a>
									</div>
								</div>
							</div>
						</div>
						<div class="mt-5">
							<div class="d-flex justify-content-center">
								<button type="submit" class="btn btn-primary" id="confirmForm" disabled>save beginning inventory</button>
							</div>
						</div>
					</div>
				</div>
			</form>

		</div>

	</div>

</div>

	<script src="/js/select2.min.js"></script>
	<script src="/js/signature.js"></script>
	<script src="/js/inventory.js?v=<?= date('His') ?>"></script>

<?= get_footer() ?>