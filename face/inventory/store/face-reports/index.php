<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'store';
$page_url = 'face-variance-report';

$filter_page = 'reports_store_face';
$group_name = 'aim_face';
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
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/face/includes/grab_stores_face.php";
require $sDocRoot."/face/inventory/includes/grab_all_transferable_items_face.php";
require $sDocRoot."/face/inventory/includes/grab_variance_report_face.php";
// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Check for GET 
if(isset($_GET['id']) && $_GET['id'] != '') {

	require $sDocRoot."/face/inventory/includes/grab_all_receivable_specific_face.php";

}
else {

	require $sDocRoot."/face/inventory/includes/grab_all_receivable_face.php";

};

$_SESSION['permalink'] = $filter_page;  

// Grab Store
$storeName = "";

for ($i=0; $i < sizeOf($arrStoresFace); $i++) { 

	if($arrStoresFace[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

		$storeName = $arrStoresFace[$i]['store_name'];

	};
	
};

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div class="variance-report-table">
					
				<?php 
				// print_r($arrVariance);
				 if ( !empty($arrVariance) ) { ?>
					
					<div class="table-default table-responsive">
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th class="small text-white text-uppercase">type</th>
									<th class="small text-white text-uppercase">from</th>
									<th class="small text-white text-uppercase">to</th>
									<th class="small text-white text-uppercase">reference number</th>
									<th class="small text-white text-uppercase">date sent</th>
									<th class="small text-white text-uppercase"></th>
								</tr>
							</thead>
							<tbody>

							<?php for($i=0;$i<sizeof($arrVariance);$i++){ ?>
								<tr>
								<td nowrap class=" text-uppercase text-<?= $arrVariance[$i]['direction'] == 'out' ? 'danger' : 'success' ?>"><?= $arrVariance[$i]['direction'] ?></td>
									<td nowrap class=""><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],$arrVariance[$i]['stock_from_branch'])) ?></td>
									<td nowrap class=""><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],$arrVariance[$i]['store_to_name'])) ?></td>
									<td nowrap class=""><?= $arrVariance[$i]['reference_number'] ?></td>
									<td nowrap class=""><?= cvdate2($arrVariance[$i]['date_created']) ?></td>
									<td nowrap class=""><a href="view/?ref_num=<?= $arrVariance[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">view</a></td>	
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>

				<?php  } else { ?>
				
					<div class="text-center p-4 mt-4">
						<h4>You don't have any variance report</h4>
					</div>
				
				<?php  } ?>

			</div>

		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js"></script>

<?= get_footer() ?>
