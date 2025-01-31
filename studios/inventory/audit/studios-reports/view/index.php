<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'audit';
$page_url = 'studios-variance-report';

$filter_page = 'reports_audit_studios';
$group_name = 'aim_studios';

////////////////////////////////////////////////

// Send away if not super user
if($_SESSION['user_login']['userlvl'] != '15')  {

	header('location: /');
	exit;

};

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_auditor_reports_specific_studios.php";
// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $_GET['page']; 

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div class="variance-report-table">
				<div class="row align-items-start">
					
					<div class="col-12 col-lg-8">
						<div class="table-default table-responsive">
							<table class="table table-striped mb-0">
								<thead>
									<tr>
										<th class="small text-white text-uppercase">product code</th>
										<th class="small text-white text-uppercase">product name</th>
										<th class="small text-white text-uppercase text-center">physical count</th>
										<th class="small text-white text-uppercase text-center">Running</th>
										<th class="small text-white text-uppercase text-center">variance</th>
									</tr>
								</thead>
								<tbody>

								<?php for($i=0;$i<sizeof($arrVarianceReport);$i++){ ?>
									<tr>
										<td nowrap class=""><?= $arrVarianceReport[$i]['product_code'] ?></td>
										<td nowrap class=""><?= ucwords($arrVarianceReport[$i]['product_name'] . ' ' . $arrVarianceReport[$i]['product_color']) ?></td>
										<td nowrap class="text-center"><?= $arrVarianceReport[$i]['input_count'] ?></td>
										<td nowrap class="text-center"><?= $arrVarianceReport[$i]['running'] ?></td>
										<td nowrap class="text-center"><?php echo  $variance= $arrVarianceReport[$i]['input_count']- $arrVarianceReport[$i]['running']?></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-12 col-lg-4">
						<p class="text-primary text-uppercase font-bold">details</p>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Date Created</h5>
							<p><?= cvdate2($arrVarianceReport[0]['date_created']) ?></p>
						</div>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Branch Name</h5>
							<p><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],$arrVarianceReport[0]['store_audited'])) ?></p>
						</div>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Auditor</h5>
							<p>
								<?php if ( $arrVarianceReport[0]['auditor_firstname']=='' && $arrVarianceReport[0]['auditor_lastname']=='' ) {
									echo $arrVarianceReport[0]['auditor_id'];
								} else {
									echo ucwords($arrVarianceReport[0]['auditor_firstname'] . ' ' . $arrVarianceReport[0]['auditor_lastname']);
								} ?>
							</p>
						</div>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Date Range</h5>
							<p><?= cvdate(3,$arrVarianceReport[0]['date_start']) . ' - ' . cvdate(3,$arrVarianceReport[0]['date_end']) ?></p>
						</div>
						<div class="mt-5">
							<div class="d-flex justify-content-center">
								<a href="/studios/inventory/audit/studios-reports/" class="mr-3"><button type="button" class="btn btn-secondary">return to reports</button></a>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js"></script>

<?= get_footer() ?>
