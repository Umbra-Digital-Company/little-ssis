<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'audit';
$page_url = 'physical-count';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/dashboard/functions.php";
// required $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";

if ( isset($_GET['filterStores']) && $_GET['filterStores'] != '' ) {

	require $sDocRoot."/inventory/includes/grab_inventory_products.php";
	// // require $sDocRoot."/inventory/includes/grab_inventory_products_pd.php";
	// require $sDocRoot."/inventory/includes/inventory_functions.php";
	// require $sDocRoot."/inventory/includes/grab_inventory_sales.php";
	require $sDocRoot."/inventory/includes/checker_functions.php";
}

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Send away if not super user
if($_SESSION['user_login']['userlvl'] != '15')  {

	header('location: /');
	exit;

};

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";

/////SET USER
$inventory_user = $_SESSION['user_login']['store_code'] ;

$dateStartpdh = "";
$dateEndpdh = "";
$store_id = "";

if(isset($_GET['filterStores'])){
	$dateStartpdh = $_GET['ds'];
	$dateEndpdh = $_GET['de'];
	$store_id = $_GET['filterStores'];

	$branchName = "";
	$branchType = "";
	
	if($_GET['filterStores']=='warehouse'){

		$branchType = 'warehouse';
		$branchName = 'Warehouse';
	
	}else{

		for ($i=0; $i < sizeOf($arrStore); $i++) { 
			if($arrStore[$i]['store_id'] == $store_id) {
				$branchType = 'store';
				$branchName = $arrStore[$i]['store_name'];
			}
		};

		if ( $branchType=="" ) {
			for ($i=0; $i < sizeOf($arrLab); $i++) { 
				if($arrLab[$i]['lab_id'] == $store_id) {
					$branchType = 'lab';
					$branchName = $arrLab[$i]['lab_name'];
				};
			};
		}

	}
}

//////////////////////// CSV UPLOAD

$arrImport = array();
$arrCSVData = array();

// Check if .csv file is submitted
if(isset($_POST["import"])) {

	// Set file and array variables
	$fileName  = $_FILES["file"]["tmp_name"];
	$arrImport = [];

	// Open the file
	$file = fopen($fileName, "r");

	// Grab all data from the file
    while (($data = fgetcsv($file, $limit, ",")) !== FALSE) {

        $arrImport[] = $data;

    };

    // Close the file
    fclose($file);

    // Remove column headers
    unset($arrImport[0]);
    $arrImport = array_values($arrImport);

    // Reindex array with SKUs
    for ($i=0; $i < sizeOf($arrImport); $i++) { 

    	// Set current data
    	$curSKU 	 = $arrImport[$i][0];
    	$curSKUCount = $arrImport[$i][1];

    	$arrCSVData[$curSKU] = $curSKUCount;

    };

};

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url) ?>
	
	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>
		
		<div class="ssis-content">

			<div class="row" style="max-width: 100%;">

 				<div class="col-12 col-lg-8" style="overflow:hidden">

					<div class="d-flex no-gutters align-items-center" id="data-filter">
						<div class="col">
							<div class="d-flex align-items-center">
								<img src="<?= get_url('images/icons/icon-dashboard.png') ?>" alt="dashboard" class="img-fluid d-none d-md-block">
								<section class="ml-0 ml-md-3">
									<p class="h3 font-bold"><?= $branchName ?></p>
									<p class="text-secondary mt-2"><?= ucwords($branchType) ?></p>
								</section>
							</div>
						</div>
						<label for="submitPhysicalCount" class="btn btn-primary">Submit Physical Count</label>
					</div>

					<hr class="spacing">

					<div id="excel-inventory" class="custom-card p-0">
						<form action="/inventory/process/actual_count_test.php" method="POST" autocomplete="off" class="table-default table-responsive" style="max-width: 100%;">

							<input type="hidden" name="date_today" value="<?= date('Y-m-d') ?>">
							<table class="table-striped table-inventory">
								<thead>
									<tr class="row100 head">
										<th class="cell100 text-uppercase small column1">SKU</th>
										<th> Running</th>
										<th class="cell100 text-uppercase small column1">Physical Count</th>
									</tr>
								</thead>
								<tbody>

									<?php 
									$running_product= array();
									for ($i=0;$i<sizeof($arrFrames);$i++) { 
										if($branchType=='warehouse'){
												$running_product[$arrFrames[$i]['product_code']] = WarehouseChecker_auditor($running_product[$arrFrames[$i]['product_code']],$_GET['ds'],$_GET['de']); 

										}elseif($branchType=='store'){
											$running_product[$arrFrames[$i]['product_code']] = StoreChecker_auditor($arrFrames[$i]['product_code'],$_GET['filterStores'],$_GET['ds'],$_GET['de']);
										}elseif($branchType=='lab'){
											$running_product[$arrFrames[$i]['product_code']]  = labChecker_auditor($arrFrames[$i]['product_code'],$_GET['filterStores'],$_GET['ds'],$_GET['de']);
										}
										else{

											$running_product[$arrFrames[$i]['product_code'] ] ="0";
										}
										?>
										
										<tr class="row100 body">
											<th nowrap class="cell100 small column1 ">
												<?= $arrFrames[$i]['product_style'] . " " . $arrFrames[$i]['product_color'] ?>
												<p class="small text-secondary m-0"><?= $arrFrames[$i]['product_code'] ?></p>
											</th>
											
											<td><?= $running_product[$arrFrames[$i]['product_code']] ?></td>

											<td style="max-width:100px;min-width:100px;width:100px" nowrap class="cell100 small text-center">
												<?php
													// $runningtotal = $beg_inventory + $arrFrames[$i]["stock_transfer_in_c"] + $arrFrames[$i]["interbranch_in_c"]- $arrFrames[$i]["stock_transfer_out_c"] - $arrFrames[$i]["interbranch_out_c"] - $arrFrames[$i]["damage_c"] - $arrFrames[$i]["pullout_c"] - $sale_frame - $arrFrames[$i]["transit_out"];
												?>
												<input type="hidden" name="running[]" value="<?= $running_product[$arrFrames[$i]['product_code'] ] ?>">							
												<input type="hidden" name="product_code[]" value="<?= $arrFrames[$i]['product_code'] ?>">

												<?php

													// Check if import isset
													if(isset($arrCSVData[$arrFrames[$i]['product_code']])) {

														$curPhysicalCount = $arrCSVData[$arrFrames[$i]['product_code']];

													}
													else {

														$curPhysicalCount = 0;

													};

												?>

												<input type="number" class="form-control" name="actual_count[]" min="0" value="<?= $curPhysicalCount ?>">

											</td>
										</tr>

									<?php } ?>
									<input type="hidden" name="store_audited" value="<?= $_GET['filterStores'] ?>">
									<input type="hidden" name="dateStartRange" value="<?= isset($_GET['ds']) && $_GET['ds']!='' ? $_GET['ds'] : '' ?>">
									<input type="hidden" name="dateEndRange" value="<?= isset($_GET['de']) && $_GET['de']!='' ? $_GET['de'] : '' ?>" >
								</tbody>

							</table>
							<input type="submit" id="submitPhysicalCount" class="sr-only">
						
						</form>
					</div>
				</div>

				<div class="col-12 col-lg-4 mt-4 mt-lg-0">
					
					<div class="custom-card mb-3">
						<form method="GET">
							<input type="hidden" name="date" value="basic">
							<div class="form-group">
								<p class="text-uppercase font-bold text-primary mb-3">select branch - <?= $branchType ?></p>
								<select name="filterStores" id="id" class="select2 form-control" required>
									<option value="">Branches</option>
									<option value="warehouse" <?= (isset($_GET['filterStores']) && $_GET['filterStores']=='warehouse') ? 'selected' : '' ?>>Warehouse</option>
									<optgroup label="STORE NAME">
										<?php for ($i=0;$i<sizeof($arrStore);$i++) { ?>
											<option value="<?= $arrStore[$i]['store_id'] ?>" <?= isset($_GET['filterStores']) && $_GET['filterStores']==$arrStore[$i]['store_id'] ? 'selected' : '' ?>><?= ucwords(str_replace(['ali','sm','mw'],['ALI','SM','MW'],strtolower($arrStore[$i]['store_name']))) ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="LAB NAME">
										<?php for ($i=0;$i<sizeof($arrLab);$i++) { ?>
											<option value="<?= $arrLab[$i]['lab_id'] ?>" <?= isset($_GET['filterStores']) && $_GET['filterStores']==$arrLab[$i]['lab_id'] ? 'selected' : '' ?>><?= ucwords(str_replace('mtc', 'MTC', str_replace('-', ' ', $arrLab[$i]['lab_name']))) ?></option>
										<?php } ?>
									</optgroup>
								</select>
							</div>

							<div class="form-group">
								<p class="text-uppercase font-bold text-primary mb-3">date start</p>
								<input type="date" name="ds" id="ds" class="form-control" required value="<?= isset($_GET['ds']) && $_GET['ds']!='' ? $_GET['ds'] : '' ?>">
							</div>

							<div class="form-group">
								<p class="text-uppercase font-bold text-primary mb-3">date end</p>
								<input type="date" name="de" id="de" class="form-control" required value="<?= isset($_GET['de']) && $_GET['de']!='' ? $_GET['de'] : '' ?>">
							</div>

							<div class="text-right mt-4">
								<button type="submit" class="btn btn-secondary">Submit Filter</button>
							</div>
						</form>
					</div>

					<?php if ( isset($_GET['filterStores']) && $_GET['filterStores'] != '' ) { ?>

						<div class="custom-card">
							<form class="form-horizontal form-csv" action="" method="POST" name="uploadCSV" enctype="multipart/form-data">
								<div class="form-group">
									<p class="text-uppercase font-bold text-primary mb-3">import csv file</p>
									<p class="text-secondary">Import a .CSV file with two columns. The header of the first column should be "Product Code" and the second column should be "Count".</p>
								</div>
								<div class="form-group">
									<input class="form-control" type="file" name="file" id="file" accept=".csv" required>
								</div>
								<div class="text-right mt-4">
									<button type="submit" id="submit" name="import" class="btn btn-secondary btn-submit">Import</button>
								</div>
							</form>
						</div>

					<?php } ?>

				</div>

			</div>
			
		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>

<script>

	$(document).ready(function() {

		if ( $('.table100-nextcols tbody tr').length > 10 ) {

			$('.wrap-table100').addClass('scroll');

		};
	})

</script>

<?= get_footer() ?>