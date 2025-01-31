<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'runner';
$page_url = 'on-hand';

$filter_page = 'on_hand_runner';
$group_name = 'main_menu';

////////////////////////////////////////////////

// Set access for Admin and Store account
if($_SESSION['user_login']['userlvl'] != '11') {

	header('location: /');
	exit;

};

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_onhand_stocks_runner.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

?>
<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div id="inventory-receive">
				
				<?php if ( !empty($arrReceivable) ) { ?>
					
					<div class="table-default table-responsive" style="max-height: 500px;">
						<table class="table table-hover mb-0">
							<thead>
								<tr>
									<th class="small">from</th>
									<th class="small">to</th>													
									<th class="small">total items</th>
									<th class="small">reference number</th>
									<th class="small">date</th>
									<th class="small"></th>
								</tr>
							</thead>
							<tbody>

								<?php for ( $i=0; $i<sizeof($arrReceivable); $i++ ) { ?>

									<tr>
										<td nowrap><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[$i]['stock_from_branch']))) ?></td>
										<td nowrap><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[$i]['stock_to_name']))) ?></td>
										<td nowrap><?= $arrReceivable[$i]['total_items'] ?></td>
										<td nowrap><?= strtoupper($arrReceivable[$i]['reference_number']) ?></td>
										<td nowrap><?= cvdate2($arrReceivable[$i]['date_created']) ?></td>
										<td nowrap><a href="/inventory/runner/on-hand/view/?ref_num=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">view</a></td>
									</tr>

								<?php } ?>

							</tbody>
						</table>
					</div>

				<?php } else { ?>
				
					<div class="text-center p-4 mt-4">
						<h4>You don't have any pending to deliver</h4>
					</div>
				
				<?php } ?>

			</div>
		
		</div>

	</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>

<?= get_footer() ?>