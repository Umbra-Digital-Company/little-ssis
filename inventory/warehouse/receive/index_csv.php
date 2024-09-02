<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'warehouse';
$page_url = 'receive';

////////////////////////////////////////////////

// Set access for Admin and Warehouse account
if($_SESSION['user_login']['userlvl'] != '8') {

	header('location: /');
	exit;

}

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_transferable_items.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Check for GET 
if(isset($_GET['id']) && $_GET['id'] != '') {

	require $sDocRoot."/inventory/includes/grab_all_receivable_specific.php";

}
else {

	require $sDocRoot."/inventory/includes/grab_all_receivable.php";

};

$_SESSION['permalink'] = $filter_page; 

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
		
		<?php if($_SESSION["store_code"]=='warehouse_qa'){?>

			<div class="text-right mb-4">
				<a href="receive_warehouse\">
					<button type="button" class="btn btn-secondary">receive from warehouse</button>
				</a>
			</div>

			<div id="inventory-receive">

				<form action="/inventory/process/warehouse/stock_transfer.php" method="POST" id="pullout-form" autocomplete="off">
					<div class="row align-items-start">
						<div class="col-12 col-lg-8">
							<div class="custom-card">
								<div class="row">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<input type="text" name="stock_from_id" class="form-control text-capitalize" value="manufacturer" readonly>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<input type="hidden" name="stock_to_id" class="text-capitalize form-control" value="warehouse_qa">
										<input type="text" class="text-capitalize form-control" value="Warehouse QA" readonly>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table table-striped table-inventory mb-0" id="multiple-item-row">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase text-center">Transferred Count</th>
												<th class="small text-white text-uppercase text-center">Received Count</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>

											<?php for ($x=0;$x<10;$x++) { ?>

												<tr>
													<th style="width: 360px;">
														<select name="frame_code[]" class="select2 filled frame_code" <?= ($x==0) ? 'required' : '' ?>>
															<option value="">Select Item</option>

															<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>

																<option value="<?= $arrItems[$i]['product_code'] ?>"><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))).' ('.$arrItems[$i]['product_code'].')' ?></option>

															<?php } ?>

														</select>
													</th>
													<td style="min-width:100px;max-width:100px;">
														<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#" <?= ($x==0) ? 'required' : '' ?>>
													</td>
													<td style="min-width:100px;max-width:100px;">
														<input type="text" name="received_count[]" class="form-control filled" placeholder="#" <?= ($x==0) ? 'required' : '' ?>>
													</td>
													<td style="vertical-align:middle!important;cursor:pointer;"><img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
												</tr>

											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th class="small text-white text-uppercase">Total</th>
												<th class="small text-white text-uppercase text-center" id="total-transfer-calc">0</th>
												<th class="small text-white text-uppercase text-center" id="total-receive-calc">0</th>
												<th class="small"></th>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="text-center mt-3">
									<a href="#" class="text-primary text-uppercase for-manufacturer" id="add-new-row-item">
										<button type="button" class="btn btn-primary">add items</button>
									</a>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4">
							<div class="custom-card">
								<form class="form-horizontal form-csv" action="" method="POST" name="uploadCSV" enctype="multipart/form-data">
									<div class="form-group">
										<p class="text-uppercase font-bold text-primary mb-3">import csv file</p>
										<p class="text-secondary">Import a .CSV file with three columns. The header of the first column should be "Product Code" and the second column should be "Transferred Count" and the third column should be "Received Count".</p>
									</div>
									<div class="form-group">
										<input class="form-control" type="file" name="file" id="file" accept=".csv" required>
									</div>
									<div class="form-group error" style="background-color:rgba(255, 99, 71, 0.4); text-align: center;"></div>
									<div class="text-right mt-4">
										<button id="upload_file" name="import" class="btn btn-secondary btn-submit">Import</button>
									</div>
								</form>
							</div>
							<hr class="spacing">
							<p class="text-primary text-uppercase font-bold">sender details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Employee Name</h5>
								<input type="hidden" name="emp_id" value="<?= $_SESSION['user_login']['store_code'] ?>">
								<input type="text" name="emp_name" class="form-control" placeholder="Full name">
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<input type="hidden" name="signature" class="signature64" value="">
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
								<div class="d-flex justify-content-end">
									<a href="/inventory/warehouse/receive/" class="mr-3">
										<button type="button" class="btn btn-secondary">Exit</button>
									</a>
									<button type="submit" class="btn btn-primary" id="confirmForm" disabled>receive</button>
								</div>
							</div>
						</div>
					</div>
				</form>

			</div>
		
		<?php } else { ?>

			<?php if ( isset($_GET['id']) && $_GET['id'] != '' ) { 
					
				if($arrReceivableItems[0]['stock_from_id']=="warehouse_qa"){
					$from_code_recieve="Warehouse QA";
				}elseif($arrReceivableItems[0]['stock_from_id']=="warehouse_damage"){
					$from_code_recieve="Warehouse Damage";
				}else{
					$from_code_recieve=$arrReceivableItems[0]['stock_from_branch'];
				}
					
			?>
				<form action="/inventory/process/warehouse/receive.php" method="POST" id="receive-form">
					<div class="row align-items-start">
						<div class="col-12 col-lg-8">
							<div class="custom-card">
								<div class="row">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<input type="hidden" name="stock_from_id" value="<?= $arrReceivableItems[0]['stock_from_id'] ?>" readonly>
										<input type="text" name="stock_from_branch" class="form-control" value="<?= ucwords(str_replace(['ali','sm','mw','mtc','hq','-'],['ALI','SM','MW','MTC','HQ',' '],$from_code_recieve)) ?>" readonly>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<input name="stock_to_id" class="text-capitalize form-control" value="<?= $arrReceivableItems[0]['stock_to_branch'] ?>" readonly>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table table-striped table-inventory mb-0" id="multiple-item-row">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase">Remarks</th>
												<th class="small text-white text-uppercase text-center">Transferred Count</th>
												<th class="small text-white text-uppercase text-center">Received Count</th>
											</tr>
										</thead>
										<tbody>

											<?php for ($i=0; $i < sizeOf($arrReceivableItems); $i++) { ?>

												<tr>
													<th style="vertical-align:middle!important;">
														<input type="hidden" name="frame_code[]" value="<?= $arrReceivableItems[$i]['product_code'] ?>" readonly>
														<input type="hidden" name="delivery_id[]" value="<?= $arrReceivableItems[$i]['delivery_unique'] ?>" readonly>
														<?= ucwords(strtolower($arrReceivableItems[$i]['product_style'].$arrReceivableItems[$i]['product_color'])) ?>
														<span class="d-block text-secondary"><?= $arrReceivableItems[$i]['product_code'] ?></span>
													</th>
													<td style="vertical-align:middle!important;min-width:300px;">
														<?= $arrReceivableItems[$i]['item_remarks'] ?>
													</td>
													<td align="center" style="vertical-align:middle!important;">
														<input type="hidden" name="delivered_count[]" class="form-control" value="<?= $arrReceivableItems[$i]['count'] ?>" readonly>
														<?= $arrReceivableItems[$i]['count'] ?>
													</td>
													<td style="min-width:100px;max-width:100px;">
														<input type="number" name="received_count[]" class="form-control" placeholder="#" value="<?= str_replace(' ','',$arrReceivableItems[$i]['count']) ?>"  required>
													</td>
												</tr>

											<?php } ?>

										</tbody>
										<tfoot>
											<th class="small text-white text-uppercase">Total</th>
											<th class="small text-white text-uppercase">&nbsp;</th>
											<th class="small text-white text-uppercase text-center"><?= array_sum(array_column($arrReceivableItems, 'count')); ?></th>
											<th class="small text-white text-uppercase text-center" id="total-receive-calc"><?= array_sum(array_column($arrReceivableItems, 'count')); ?></th>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4">
							<p class="text-primary text-uppercase font-bold">order details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Transaction</h5>
								<input type="hidden" name="type" value="<?= $arrReceivableItems[0]['type'] ?>">
								<p class="h6 large"><?= ucwords(str_replace('_',' ',$arrReceivableItems[0]['type'])) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Reference Number</h5>
								<input type="hidden" name="reference_number" value="<?= $arrReceivableItems[0]['reference_number'] ?>" readonly>
								<p class="h6 large"><?= strtoupper($arrReceivableItems[0]['reference_number']) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Date Sent</h5>
								<p class="h6 large"><?= cvdate2($arrReceivableItems[0]['date_created']) ?></p>
							</div>
							
							<?php if ( $arrReceivableItems[0]['remarks'] != '' ) { ?>
							
								<div class="mt-3">
									<h5 class="text-secondary font-bold">Additional message</h5>
									<p class="h6 large"><?= $arrReceivableItems[0]['remarks'] ?></p>
								</div>
								
							<?php } ?>
							
							<p class="text-primary text-uppercase font-bold mt-5">recipient details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Authorized by</h5>
								<p class="h6 large">
									<?php if ($arrReceivableItems[0]['sender_id']=='warehouse_damage') {
										echo 'Warehouse Damage';
									} elseif ($arrReceivableItems[0]['sender_id']=='warehouse_qa') {
										echo 'Warehouse QA';
									} else {
										echo ucwords(strtolower($arrReceivableItems[0]['sender_first_name'].' '.$arrReceivableItems[0]['sender_last_name']));
									} ?>
								</p>
								<!-- <input type="text" name="emp_id" class="form-control" placeholder="ID #" required> -->
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="<?= $arrReceivableItems[0]["signature"]; ?>">
								</div>
							</div>

							<?php if ( $arrReceivableItems[0]['status']=='in transit') { ?>

								<p class="text-primary text-uppercase font-bold mt-5">recipient details</p>
								<div class="mt-4">
									<h5 class="text-secondary font-bold">Employee Name</h5>
									<input type="hidden" name="emp_id" value="<?= $_SESSION['user_login']['store_code'] ?>">
									<input type="text" name="emp_name" class="form-control" placeholder="Full name">
								</div>
								<div class="mt-3">
									<h5 class="text-secondary font-bold">Signature</h5>
									<input type="hidden" name="signature" class="signature64" value="">
									<div class="custom-card">
										<div class="canvas-holder text-center">
											<canvas id="thecanvas" width="475" height="148" border="0"></canvas>
											<div class="row no-gutters justify-content-center mt-3">
												<a href="#" class="text-uppercase save text-success pl-3 pr-3 d-block">save</a>
												<a href="#" class="text-uppercase clear text-danger pl-3 pr-3 d-block">clear</a>
											</div>
										</div>
									</div>
								</div>

							<?php } ?>

							<div class="mt-5">
								<div class="d-flex justify-content-end">
									<?php if ( $arrReceivableItems[0]['status']=='in transit') { ?>
										<button type="submit" class="btn btn-primary" id="confirmForm" disabled>receive</button>
									<?php } ?>									
									<a href="/inventory/warehouse/receive/" class="ml-3"><button type="button" class="btn btn-secondary">Exit</button></a>
								</div>
							</div>

						</div>
					</div>
				</form>

			<?php } else {

				if ( !empty($arrReceivable) ) { 
					
					if($arrReceivable[$i]['stock_from_id']=="warehouse_qa"){
						$from_code="Warehouse QA";
					}elseif($arrReceivable[$i]['stock_from_id']=="warehouse_damage"){
						$from_code="Warehouse Damage";
					}else{
						$from_code=$arrReceivable[$i]['stock_from_branch'];
					}
					
					?>

					<div id="inventory-receive">					
						<div class="table-default table-responsive">
							<table class="table table-striped mb-0">
								<thead>
									<tr>
										<th class="small text-white text-uppercase">from</th>
										<th class="small text-white text-uppercase">to</th>													
										<th class="small text-white text-uppercase">total items</th>										
										<th class="small text-white text-uppercase">status</th>
										<th class="small text-white text-uppercase">reference number</th>
										<th class="small text-white text-uppercase">date sent</th>
										<th class="small text-white text-uppercase"></th>
									</tr>
								</thead>
								<tbody>

									<?php for ( $i=0; $i<sizeof($arrReceivable); $i++ ) { 
										
										if($arrReceivable[$i]['stock_from_id']=="warehouse_qa"){
											$from_code="Warehouse QA";
										}
										else{
											$from_code=$arrReceivable[$i]['stock_from_branch'];
										}
										
										?>

										<tr>
											<td nowrap class=""><?= ucwords(str_replace(['ali','mw','sm','mtc','qa','-'],['ALI','MW','SM','MTC','QA',' '],strtolower($from_code))) ?></td>
											<td nowrap class=""><?= ucwords(str_replace("_"," ",$_SESSION['store_code'])) ?></td>
											<td nowrap class=""><?= $arrReceivable[$i]['total_items'] ?></td>
											<td nowrap class=""><?= ucwords($arrReceivable[$i]['status']) ?></td>
											<td nowrap class=""><?= $arrReceivable[$i]['reference_number'] ?></td>
											<td nowrap class=""><?= cvdate2($arrReceivable[$i]['date_created']) ?></td>
											<td nowrap class=""><a href="/inventory/warehouse/receive/?id=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-success text-uppercase font-weight-bold"><?= $arrReceivable[$i]['status']=='in transit' ? 'receive' : 'view' ?></a></td>
										</tr>

									<?php } ?>

								</tbody>
							</table>
						</div>

					</div>

				<?php } else { ?>
				
					<div class="text-center p-4 mt-4">
						<h4>You don't have any pending to receive</h4>
						<!-- <a href="/warehouse/pullout/" class="btn-primary text-uppercase mt-3 d-inline-block pt-3 pb-3 pr-4 pl-4">request now</a> -->
					</div>
				
				<?php }

			}
			
		}?>

		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js"></script>
<script>
	$(document).ready(function() {
		$(".error").hide(); 
		let files = '';
		$('body').on('change', 'input[type=file]', function(e) {
	        files = e.target.files;
	        $(".error").hide();
	    });
	    function trNewLine(code, p_count, r_count){
			let newLine =  '<tr>'
						+'<th style="width: 360px;">'
							+'<select name="frame_code[]" class="select2 filled frame_code" style="width: 336px;" first_line_required >'
								+'<option value="">Select Item</option>'

								+'<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>';
								selected = (code == "<?= $arrItems[$i]['product_code'] ?>") ? 'selected' : '';

								newLine += '<option value="<?= $arrItems[$i]['product_code'] ?>" '+selected+'><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?> ( <?= $arrItems[$i]['product_code'] ?> )</option>'

								+'<?php } ?>'

							+'</select>'
						+'</th>'
						+'<td style="min-width:100px;max-width:100px;">'
							+'<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#" value="'+p_count+'" first_line_required>'
						+'</td>'
						+'<td style="min-width:100px;max-width:100px;">'
							+'<input type="text" name="received_count[]" class="form-control filled" placeholder="#" value="'+r_count+'"first_line_required>'
						+'</td>'
						+'<td style="vertical-align:middle!important;cursor:pointer;"><img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>'
					+'</tr>';
			return newLine;
		}
		
	    $('#upload_file').on('click', function(e) {
	        e.stopPropagation();
	        e.preventDefault();
	        var data = new FormData();
	        var validFormat = true;
	        var filetype_validFormat = true;
	        var validSize = true;
	        // Append files to post data
	        $.each(files, function(key, value) {
	            // Check if file is valid
	            if(( !(/\.(CSV|csv)$/i).test( $('input[type=file]').val() )) ) 
	                {filetype_validFormat = false;}
	            data.append(key, value);
	        });
	        var ch = $('input[type=file]').val();
	        if (ch.indexOf(';') > -1)
	        {
	          validFormat = false;
	        }
	        // Check if there is file chosen
	        if($('input[type=file]').val() == '') {
	             $(".error").text('No file was uploaded.');
	             $(".error").show();
	        }else{
	            // Check file format
	            if (validFormat == false) {
	                $('input[type=file]').val('');
	                $(".error").text('Upload failed. Invalid file type. <File Type/File Format>');
	                $(".error").show();
	            // Check file size
	            }else if(filetype_validFormat == false){
	                $(".error").text('Upload failed. Invalid file type. (Accepted formats: CSV)');
	                $(".error").show();
	            }else{
	                $.ajax({
	                    url: '/process/upload_received_count.php',
	                    type: 'POST',
	                    data: data,
	                    cache: false,
	                    dataType: 'json',
	                    processData: false,
	                    contentType: false,
	                    success: function(response, textStatus, jqXHR){
	                    	//console.log(response);
	                    	if(response.invalid_message != false){
	                    		$(".error").text(response.invalid_message);
	                    		$(".error").show();
	                    	}else{
	                    		$("#multiple-item-row tbody").find('tr').remove();
		                    	for(let i = 0; i < response.data.length; i++){
		                    		 newLine = trNewLine(response.data[i].product_code, response.data[i].transferred_count, response.data[i].received_count);
		                    		newLine = (i == 0) ? newLine.replace(/first_line_required/g, "required") :  newLine.replace(/first_line_required/g, "");
		                    		$("#multiple-item-row tbody").append(newLine);
		                    		$("#multiple-item-row tbody").find('tr:last th select').select2({});
		                        }
		                    }
	                        $('input[type=file]').val('');
	                    },
	                    error: function(jqXHR, textStatus, errorThrown){ console.log('ERRORS: ' + textStatus) }
	                });
	            }
	        }
	    });
	});
</script>
<?= get_footer() ?>
