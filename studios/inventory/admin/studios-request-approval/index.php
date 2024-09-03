<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'admin';
$page_url = 'studios-request-approval';

$filter_page = 'request_approval_admin_studios';
$group_name = 'aim_studios';

////////////////////////////////////////////////

// Set access for Admin and Store account
// if($_SESSION['user_login']['userlvl'] != '13') {

// 	header('location: /');
// 	exit;

// };

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_approval_stock_studios.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$filter_page

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div id="inventory-receive">

				<?php if ( !empty($arrReceivable) ) { ?>
					<div class="row" style="margin-right: unset; float: right; margin-bottom: 15px;">
						<button class="btn btn-primary mx-2" id="btn-apparove">Approve</button>
					</div>
					
					<br>
					
					<div class="table-default table-responsive">
					<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th class="small text-white text-uppercase"><input type="checkbox" name="checkbox-all"></th>
									<th class="small text-white text-uppercase">from</th>
									<th class="small text-white text-uppercase">to</th>													
									<th class="small text-white text-uppercase">total Sent</th>
									<th class="small text-white text-uppercase">requested by</th>
									<th class="small text-white text-uppercase">reference number</th>
									<th class="small text-white text-uppercase">date sent</th>
									<th class="small text-white text-uppercase"></th>
								</tr>
							</thead>
							<tbody>

								<?php for ( $i=0; $i<sizeof($arrReceivable); $i++ ) { ?>

									<tr>
										<td nowrap class=""><input type="checkbox" name="request" value="<?= $arrReceivable[$i]['reference_number'] ?>"></td>
										<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivable[$i]['stock_from_branch'])) ?></td>
										<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivable[$i]['stock_to_name'])) ?></td>
										<td nowrap class=""><?= $arrReceivable[$i]['total_items'] ?></td>
										<td nowrap class=""><?= ucwords($arrReceivable[$i]['admin_name']) ?></td>
										<td nowrap class=""><?= $arrReceivable[$i]['reference_number'] ?></td>
										<td nowrap class=""><?= cvdate2($arrReceivable[$i]['date_created']) ?></td>
										<td nowrap class=""><a href="view/?ref_num=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">view</a></td>
									</tr>

								<?php } ?>

							</tbody>
						</table>
					</div>

				<?php } else { ?>
				
					<div class="text-center p-4 mt-4">
						<h4>You don't have any pending to receive</h4>
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
		//*********************************************************** CHECK ALL

						let pre_approve = [];

				$("[name=checkbox-all]").click(function(){
					$('[name="request"]').not(this).prop('checked', this.checked);
				});
				//END:: CHECK ALL

				$('#btn-apparove').click(function(){
					pre_approve = [];
					$('[name="request"]:checked').each(function(){
						pre_approve.push($(this).val());
					})
					//END:: EACH

					if(pre_approve.length<1){
						alert("Please select to apparove");
						return;
					}

					let data = pre_approve.join(",");

					location.href='/inventory/process/admin/approvev2_studios.php?ref='+data;

				})
				//END::CLICK

});


		</script>

<?= get_footer() ?>