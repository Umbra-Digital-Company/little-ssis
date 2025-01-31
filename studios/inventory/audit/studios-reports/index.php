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
require $sDocRoot."/inventory/includes/grab_all_auditor_reports_studios.php";
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
					
				<?php if ( !empty($arrVarianceReport) ) { ?>
					<div style="display: flex; justify-content: flex-end;">
						<div class ="col-md-3 col-xs-12 input-group" style="padding-right: 0px;">
							<input type="text" name="branch_search" class="form-control" id="branch_search" value ="<?php if(isset($_GET['search']) && !empty($_GET['search'])) echo $_GET['search']; ?>" placeholder="Search Branch"/>
							<div class="input-group-prepend" ">
						    	<span class="input-group-text" id="btn_search" style="border-bottom-right-radius: 10px; border-top-right-radius: 10px;">Search</span>
						  	</div>
						</div>
					</div>
					<br>
					<div class="table-default table-responsive">
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th class="small text-white text-uppercase">date created</th>
									<th class="small text-white text-uppercase">branch</th>
									<th class="small text-white text-uppercase text-center">total items</th>
									<th class="small text-white text-uppercase text-center">total variance</th>
									<th class="small text-white text-uppercase">date start</th>
									<th class="small text-white text-uppercase">date end</th>
									<th class="small text-white text-uppercase">auditor</th>
									<th class="small"></th>
								</tr>
							</thead>
							<tbody>

							<?php for($i=0;$i<sizeof($arrVarianceReport);$i++){ ?>
								<tr>
									<td nowrap class=""><?= cvdate2($arrVarianceReport[$i]['date_created']) ?></td>
									<td nowrap class=""><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],$arrVarianceReport[$i]['store_audited'])) ?></td>
									<td nowrap class="text-center"><?= $arrVarianceReport[$i]['total_items'] ?></td>
									<td nowrap class="text-center"><?= $arrVarianceReport[$i]['total_variance'] ?></td>
									<td nowrap class=""><?= cvdate(3,$arrVarianceReport[$i]['date_start']) ?></td>
									<td nowrap class=""><?= cvdate(3,$arrVarianceReport[$i]['date_end']) ?></td>
									<td nowrap class="">
										<?php if ( $arrVarianceReport[$i]['auditor_firstname']=='' && $arrVarianceReport[$i]['auditor_lastname']=='' ) {
											echo $arrVarianceReport[$i]['auditor_id'];
										} else {
											echo ucwords($arrVarianceReport[$i]['auditor_firstname'] . ' ' . $arrVarianceReport[$i]['auditor_lastname']);
										} ?>
									</td>
									<td nowrap class=""><a href="/studios/inventory/audit/studios-reports/view/?id=<?= $arrVarianceReport[$i]['branch_id'] ?>&date=<?= $arrVarianceReport[$i]['date_end'] ?>" class="text-primary text-uppercase font-weight-bold">view</a></td>
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
<script type="text/javascript">
	$(document).ready(function(){
		$('#branch_search').focus();
		$('#btn_search').on('click', function(){
			let search = ($.trim($('#branch_search').val()) != '') ? '?search='+$('#branch_search').val() : '';
			window.location ='/studios/inventory/audit/studios-reports/'+search;
		});
		$(this).keypress(function(event){
		    var keycode = (event.keyCode ? event.keyCode : event.which);
		    if(keycode == '13'){
		        ($('#branch_search').is(":focus")) ? $('#btn_search').click() : '';
		    }
		});
	});
</script>
<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js"></script>

<?= get_footer() ?>
