<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'admin';
$page_url = 'face-transfer';

$filter_page = 'transfer_admin_face';
$group_name = 'aim_face';

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
require $sDocRoot."/face/includes/grab_stores_face.php";
require $sDocRoot."/face/inventory/includes/grab_all_transferable_items_face.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Store
$storeName = "";



$MergeStores= $arrStoresFace;


// echo $MergeStores[110]['store_id'];

// echo "<pre>";
// print_r($MergeStores);

for ($i=0; $i < sizeOf($MergeStores); $i++) { 

	if($MergeStores[$i]['store_id'] == $_SESSION['user_login']['store_code'] ) {

		$storeName = $MergeStores[$i]['store_name'];

	};
	
};

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<form action="/face/inventory/process/admin/face_transfer.php" method="POST" id="pullout-form" autocomplete="off">
				<div class="row align-items-start">
					<div class="col-12 col-lg-8">
						<div class="custom-card">
							<div class="row">
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">From</h5>
									<input type="hidden" name="stock_from_id" value="warehouse">
									<input type="text" value="Warehouse" class="form-control" readonly>
								</div>
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">To</h5>
									<select name="recipient_branch" id="recipient_branch" class="select2 form-control" required>
										<option value="">-- Select Branch --</option>
										<optgroup label="STORE NAME">
											<?php for ($i=0;$i<sizeof($arrStoresFace);$i++) { ?>
												<option value="<?= $arrStoresFace[$i]['store_id'] ?>"><?= ucwords(str_replace(['ali','sm','mw'],['ALI','SM','MW'],strtolower($arrStoresFace[$i]['store_name']))) ?></option>
											<?php } ?>
										</optgroup>
									
									</select>
								</div>
							</div>
							<div class="table-responsive mt-4">
								<table class="table table-striped table-inventory mb-0" id="multiple-item-row">
									<thead>
										<tr>
											<th class="small text-white text-uppercase">Item Name</th>
											<th class="small text-white text-uppercase text-center">Count</th>
											<th class="small text-white text-uppercase">Remarks</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>

										<?php for ($x=0;$x<5;$x++) { ?>

											<tr>
												<th style="width: 360px">
													<select name="frame_code[]" class="select2 filled frame_code" <?= ($x==0) ? 'required' : '' ?>>
														<option value="">Select Item</option>

														<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>

															<option value="<?= $arrItems[$i]['product_code'] ?>"><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?> ( <?= $arrItems[$i]['product_code'] ?> )</option>

														<?php } ?>

													</select>
												</th>
												<td style="min-width:100px;max-width:100px;">
													<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#" <?= ($x==0) ? 'required' : '' ?>>
												</td>
												<td style="min-width:300px">
													<input type="text" name="item_remark[]" class="form-control filled" placeholder="Remarks for this item">
												</td>
												<td style="vertical-align:middle!important;cursor:pointer;width:50px;"><img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
											</tr>

										<?php } ?>
										
									</tbody>
									<tfoot>
										<tr>
											<th class="small text-white text-uppercase">Total</th>
											<th class="small text-white text-uppercase text-center" id="total-transfer-calc">0</th>
											<th class="small text-white text-uppercase"></th>
											<th class="small"></th>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="text-center mt-3">
								<a href="#" class="text-primary text-uppercase" id="add-new-row-item">
									<button type="button" class="btn btn-primary">add items</button>
								</a>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4 mt-5 mt-lg-0">
						<div class="custom-card">
							<form class="form-horizontal form-csv" action="" method="POST" name="uploadCSV" enctype="multipart/form-data">
								<div class="form-group">
									<p class="text-uppercase font-bold text-primary mb-3">import csv file</p>
									<p class="text-secondary">Import a .CSV file with two columns. The header of the first column should be "Product Code" and the second column should be "Count".</p>
								</div>
								<div class="form-group">
									<input class="form-control" type="file" name="file" id="file" accept=".csv">
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
							<select name="transaction_reason" class="form-control" required>
								<option value="">Select Reason</option>
								<option value="Allocation">Allocation</option>
								<option value="Frame to Come">Frame to Come</option>
								<option value="Essilor">Essilor</option>
								<option value="Requested by Merch Team">Requested by Merch Team</option>
								<option value="Others">Others</option>
							</select>
							<div class="mt-2 reason_sec" style="display: none;">
								<input type="text" name="others_reason" class="form-control" placeholder="Reason details" required>
							</div>
						</div>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Employee Name</h5>
							<input type="hidden" name="emp_id" class="form-control" placeholder="ID #" value="overseer">
							<input type="text" name="emp_name" class="form-control" placeholder="Full name" required>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Additional message</h5>
							<textarea name="sender_remarks" id="senderRemarks" class="form-control textarea"></textarea>
						</div>
						<div class="mt-3">
							<p class="text-primary text-uppercase font-bold">signature</p>
							<input type="hidden" name="signature" class="signature64" value="">
							<div class="custom-card mt-2">
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
								<button type="submit" class="btn btn-primary" id="confirmForm" disabled>send request</button>
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

	<script>
	
	$(document).ready(function() {

		$('input[name="pullout_option"]').on('click', function() {
			if ($('#pullout_option_1').is(':checked')) {
				$('select[name="recipient_branch"]').prop('disabled', false).prop('required', true);
			} else {
				$('select[name="recipient_branch"]').prop('disabled', true).prop('required', false);
			}
		})

		$(".error").hide(); 
		let files = '';
		$('body').on('change', 'input[type=file]', function(e) {
	        files = e.target.files;
	        $(".error").hide();
	    });
	    function trNewLine(code, count){
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
							+'<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#" value="'+count+'" first_line_required>'
						+'</td>'
						+'<td style="min-width:300px">'
							+'<input type="text" name="item_remark[]" class="form-control filled" placeholder="Remarks for this item">'
						+'</td>'
						+'<td style="vertical-align:middle!important;cursor:pointer;width:50px;"><img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>'
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
	                    url: '/process/upload_physical_count.php',
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
		                    		 newLine = trNewLine(response.data[i].product_code, response.data[i].physical_count);
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

	    $('select[name=transaction_reason]').change(function(){
	    	if($(this).val() == 'Others'){
	    		$('.reason_sec').show();
	    		$('input[name=others_reason]').attr('required', true);
	    		$('input[name=others_reason]').focus();
	    	}else{
	    		$('.reason_sec').hide();
	    		$('input[name=others_reason]').removeAttr('required');
	    	}
	    });

	})
	
	</script>

<?= get_footer() ?>