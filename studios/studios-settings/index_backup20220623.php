<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'studios-settings';

$filter_page = 'studios_settings';
$group_name = 'sunnies_studios';

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/sidebar_update_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/includes/studios-settings/grab_poll51_studios.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

function getDateModify($date){
$date = explode('-', $date);

	switch($date[1]){
		case '01': return "Jan ".$date[2].", ".$date[0]; break;
		case '02': return "Feb ".$date[2].", ".$date[0]; break;
		case '03': return "Mar ".$date[2].", ".$date[0]; break;
		case '04': return "Apr ".$date[2].", ".$date[0]; break;
		case '05': return "May ".$date[2].", ".$date[0]; break;
		case '06': return "Jun ".$date[2].", ".$date[0]; break;
		case '07': return "Jul ".$date[2].", ".$date[0]; break;
		case '08': return "Aug ".$date[2].", ".$date[0]; break;
		case '09': return "Sep ".$date[2].", ".$date[0]; break;
		case '10': return "Oct ".$date[2].", ".$date[0]; break;
		case '11': return "Nov ".$date[2].", ".$date[0]; break;
		case '12': return "Dec ".$date[2].", ".$date[0]; break;

	}
}

?>

<?= get_header($page) ?>

<style type="text/css">
	@media screen and (min-width: 1600px) {
		.col-or-3 {
			flex: 0 0 33.33% !important;
			max-width: 33% !important;
		}
	};

	.dropdown-check-list {
      display: inline-block;
    }
    .dropdown-check-list .anchor {
      position: relative;
      cursor: pointer;
      display: inline-block;
      padding: 5px 50px 5px 10px;
      border: 1px solid #ccc;
    }
    .dropdown-check-list .anchor:after {
      position: absolute;
      content: "";
      border-left: 2px solid black;
      border-top: 2px solid black;
      padding: 5px;
      right: 10px;
      top: 20%;
      -moz-transform: rotate(-135deg);
      -ms-transform: rotate(-135deg);
      -o-transform: rotate(-135deg);
      -webkit-transform: rotate(-135deg);
      transform: rotate(-135deg);
    }
    .dropdown-check-list .anchor:active:after {
      right: 8px;
      top: 21%;
    }

    #div-status .dropdown-item{
	    padding: .25rem 0.6rem;
    }
</style>

<div class="row no-gutters align-items-strech">

	<?= get_sidebar($page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page) ?>">

		<?= get_topbar($page) ?>

		<div class="ssis-content">
			<div class="d-flex justify-content-between">
				<p class="h3 font-bold mt-3">Text/Images Settings</p>
				<input type="button" class="btn btn-primary" id="set-text-image" value="Set Text/Image">
			</div>
			<div class="custom-card mt-2">
				<?php if($arrTextImages[0]['image_1'] != '' || $arrTextImages[0]['image_2'] != ''){ ?>
					<div class="border p-3">
						<div class="row">
							<?php if($arrTextImages[0]['image_1'] != ''){ ?>
								<div class="col-md-6 col-xs-12">
									<img src="images/<?= $arrTextImages[0]['image_1'] ?>" class="img-thumbnail">
								</div>
							<?php } ?>
							<?php if($arrTextImages[0]['image_2'] != ''){ ?>
								<div class="col-md-6 col-xs-12">
									<img src="images/<?= $arrTextImages[0]['image_2'] ?>" class="img-thumbnail">
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php if($arrTextImages[0]['text'] != ''){ ?>
					<div class="mt-4 border p-3">
						<?= $arrTextImages[0]['text'] ?>
					</div>
				<?php } ?>
			</div>
			<hr class="spacing">
			<div class="d-flex justify-content-between">
				<p class="h3 font-bold mt-3">Recommended Settings</p>
				<input type="button" class="btn btn-primary" id="set-products" value="Set Products">
			</div>
			<div class="table-default auto mt-2" style="max-width: 100%;">
				<p class="text-uppercase font-bold" id="date_selected"></p>
				<div class="table-responsive border p-3">
					<table class="table table-hover recommended-list">
						<thead>
							<tr class="text-center">
								<th scope="col" class="text-uppercase font-bold text-left">#</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Name</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Code</th>
							</tr>
						</thead>
						<tbody class="text-center tableBody">
							<?php 
								$arrSelected = [];
								for ($i=0; $i < count($arrRecommended) ; $i++) {
									$arrSelected[] =  $arrRecommended[$i]['product_code'];
							?>
								<tr class="row100 body">
									<td><?= $i+1 ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrRecommended[$i]['item_name'] ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrRecommended[$i]['product_code'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<hr class="spacing">
			<div class="d-flex justify-content-start">
				<p class="h3 font-bold mt-3">Products Settings</p>
			</div>
			<div class="table-default auto mt-2" style="max-width: 100%;">
				<div class="d-flex justify-content-between p-2">
					<p class="h7 font-bold mt-3 ml-3">Priority Frames Settings</p>
					<input type="button" class="btn btn-primary" id="set-priority" value="Set Priority Frames">
				</div>
				<div class="table-responsive border p-3">
					<table class="table table-hover priority-list">
						<thead>
							<tr class="text-center">
								<th scope="col" class="text-uppercase font-bold text-left">#</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Name</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Code</th>
							</tr>
						</thead>
						<tbody class="text-center tableBody">
							<?php 
								$arrSelectedPriority = [];
								for ($i=0; $i < count($arrPriority) ; $i++) {
									$arrSelectedPriority[] =  $arrPriority[$i]['product_code'];
							?>
								<tr class="row100 body">
									<td><?= $i+1 ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrPriority[$i]['item_name'] ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrPriority[$i]['product_code'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<hr class="spacing">
				<div class="d-flex justify-content-between p-2">
					<p class="h7 font-bold mt-3 ml-3">Antirad Settings</p>
					<input type="button" class="btn btn-primary" id="set-antirad" value="Set Priority Antirad">
				</div>
				<div class="table-responsive border p-3">
					<table class="table table-hover antirad-list">
						<thead>
							<tr class="text-center">
								<th scope="col" class="text-uppercase font-bold text-left">#</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Name</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Code</th>
							</tr>
						</thead>
						<tbody class="text-center tableBody">
							<?php 
								$arrSelectedANtirad = [];
								for ($i=0; $i < count($arrAntirad) ; $i++) {
									$arrSelectedANtirad[] =  $arrAntirad[$i]['product_code'];
							?>
								<tr class="row100 body">
									<td><?= $i+1 ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrAntirad[$i]['item_name'] ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrAntirad[$i]['product_code'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<hr class="spacing">
				<div class="d-flex justify-content-between p-2">
					<p class="h7 font-bold mt-3 ml-3">Merch Settings</p>
					<input type="button" class="btn btn-primary" id="set-merch" value="Set Priority Merch">
				</div>
				<div class="table-responsive border p-3">
					<table class="table table-hover merch-list">
						<thead>
							<tr class="text-center">
								<th scope="col" class="text-uppercase font-bold text-left">#</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Name</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Code</th>
							</tr>
						</thead>
						<tbody class="text-center tableBody">
							<?php 
								$arrSelectedMerch = [];
								for ($i=0; $i < count($arrMerch) ; $i++) {
									$arrSelectedMerch[] =  $arrMerch[$i]['product_code'];
							?>
								<tr class="row100 body">
									<td><?= $i+1 ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrMerch[$i]['item_name'] ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrMerch[$i]['product_code'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<hr class="spacing">
				<div class="d-flex justify-content-between p-2">
					<p class="h7 font-bold mt-3 ml-3">Paper Bag Settings</p>
					<input type="button" class="btn btn-primary" id="set-paperbag" value="Set Priority Paper Bag">
				</div>
				<div class="table-responsive border p-3">
					<table class="table table-hover paperbag-list">
						<thead>
							<tr class="text-center">
								<th scope="col" class="text-uppercase font-bold text-left">#</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Name</th>
								<th scope="col" class="text-uppercase font-bold text-left">Product Code</th>
							</tr>
						</thead>
						<tbody class="text-center tableBody">
							<?php 
								$arrSelectedPaperBag = [];
								for ($i=0; $i < count($arrPaperBag) ; $i++) {
									$arrSelectedPaperBag[] =  $arrPaperBag[$i]['product_code'];
							?>
								<tr class="row100 body">
									<td><?= $i+1 ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrPaperBag[$i]['item_name'] ?></td>
									<td nowrap class="cell100 small text-left"><?= $arrPaperBag[$i]['product_code'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
	<style>
		.modal-content{
		border-radius: .5rem;
		}
		.modal-header{
			background-color : #36482E !important;
			border-radius: 5px;
		}
		.modal-title, .close{
			color: #f7f7f7;
			opacity: 1;
		}
		.modal-dialog{
			max-width: 50%;
		}
		#modal-daily-list .modal-dialog{
			max-width: 70%;
		}
		.modal-body .details {
		    /* 100% = dialog height, 120px = header + footer */
		    max-height: 300px;
		    overflow-y: auto;
		}
		.submit{
			background-color : #36482E !important;
			border-radius:30px;
			padding-left: 30px;
			padding-right: 30px;
		}
		@media only screen and (max-width: 800px) {
		  	.modal-dialog{
			  	width: auto;
			  	max-width: 100%;
		 	}
		 	#modal-daily-list .modal-dialog{
				max-width: 100%;
			}
			
		}
		.modal-dialog{
			max-width: 70%;
		}
		 .fc-title{
		 	color: #fff;
		 	cursor: pointer;
		 	margin-left: 5px;
		 }
		 .fc-content {
		 	white-space: inherit !important;
		 	text-align: center;
		 }
		.collapse_view td{
			text-align: left;
		}
		.table .well {
			padding: 0;
			background-color: #36482e82;
		}
		.table .well > div {
			padding: 30px;
		}
		.table .well .left-title {
			border-right: 1px solid #000;
		}
		.collapse_view td{
			text-align: left;
		}
		.desc-limit{
			width: 1100px;
			word-break: break-word;
			white-space: normal;
		}
		.checkin-event .fc-content{
			height: 45px !important;
		}
		.select2-container {
		    width: 100% !important;
		    padding: 0;
		}
	</style>

	<div class="modal fade" id="text-image-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Text/Image Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/process/studios-settings/set_text_image.php" method="POST" enctype="multipart/form-data">
						<div class="d-flex justify-content-between">
							<div class="col-md-2 col-xs-12 input-group">
								<input type="hidden" name="retain_image_1" value="<?=  $arrTextImages[0]['image_1']?>">
		                     	<img src="images/<?= $arrTextImages[0]['image_1'] ?>" class="img-thumbnail">
							</div>
							<div class="col-md-4 col-xs-12 input-group">
		                       	<div class="custom-file">
								    <input name="filename_1" type="file" class="custom-file-input" id="customFile">
								    <label class="custom-file-label" for="customFile">Choose Image 1</label>
							  	</div>
							 </div>
							<div class="col-md-2 col-xs-12 input-group">
								<input type="hidden" name="retain_image_2" value="<?=  $arrTextImages[0]['image_2']?>">
		                     	<img src="images/<?= $arrTextImages[0]['image_2'] ?>" class="img-thumbnail">
							</div>
							 <div class="col-md-4 col-xs-12 input-group">
		                       	<div class="custom-file">
								    <input name="filename_2" type="file" class="custom-file-input" id="customFile">
								    <label class="custom-file-label" for="customFile">Choose Image 2</label>
							  	</div>
							 </div>
						</div>
						<div class="row mt-2">
							<div class="col-md-12 col-xs-12">
								<div class="form-group" style="height: 75vh; overflow-y: auto;">
									<textarea class="tinymce" name="text_data"></textarea>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="recommended-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Recommended Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/process/studios-settings/set_recommended.php" method="POST">
						<div class="form-group col-12">
							<label>Product List</label>
							<select class="form-control" id='product_list' name='product_list[]' multiple required>
								<?php
									for($i = 0; $i < count($arrProducts); $i++){
										$selected = (in_array($arrProducts[$i]['product_code'], $arrSelected)) ? 'selected' : '';
									?>
									<option value="<?= $arrProducts[$i]['product_code'] ?>" <?= $selected?>><?= strtoupper($arrProducts[$i]['item_name']) ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="priority-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Frames Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/process/studios-settings/set_priority.php" method="POST">
						<div class="form-group col-12">
							<label>Priority List</label>
							<select class="form-control" id='priority_list' name='priority_list[]' multiple required>
								<?php
									for($i = 0; $i < count($arrProducts); $i++){
										$selected = (in_array($arrProducts[$i]['product_code'], $arrSelectedPriority)) ? 'selected' : '';
									?>
									<option value="<?= $arrProducts[$i]['product_code'] ?>" <?= $selected?>><?= strtoupper($arrProducts[$i]['item_name']) ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="antirad-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Antirad Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/process/studios-settings/set_antirad.php" method="POST">
						<div class="form-group col-12">
							<label>Priority List</label>
							<select class="form-control" id='antirad_list' name='antirad_list[]' multiple required>
								<?php
									for($i = 0; $i < count($arrProductsAntirad); $i++){
										$selected = (in_array($arrProductsAntirad[$i]['product_code'], $arrSelectedANtirad)) ? 'selected' : '';
									?>
									<option value="<?= $arrProductsAntirad[$i]['product_code'] ?>" <?= $selected?>><?= strtoupper($arrProductsAntirad[$i]['item_name']) ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="merch-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Merch Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/process/studios-settings/set_merch.php" method="POST">
						<div class="form-group col-12">
							<label>Merch List</label>
							<select class="form-control" id='merch_list' name='merch_list[]' multiple required>
								<?php
									for($i = 0; $i < count($arrProductsMerch); $i++){
										$selected = (in_array($arrProductsMerch[$i]['product_code'], $arrSelectedMerch)) ? 'selected' : '';
									?>
									<option value="<?= $arrProductsMerch[$i]['product_code'] ?>" <?= $selected?>><?= strtoupper($arrProductsMerch[$i]['item_name']) ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="paperbag-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Paper Bag Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="/process/studios-settings/set_paperbag.php" method="POST">
						<div class="form-group col-12">
							<label>Paper Bag List</label>
							<select class="form-control" id='paperbag_list' name='paperbag_list[]' multiple required>
								<?php
									for($i = 0; $i < count($arrProductsPaperBag); $i++){
										$selected = (in_array($arrProductsPaperBag[$i]['product_code'], $arrSelectedPaperBag)) ? 'selected' : '';
									?>
									<option value="<?= $arrProductsPaperBag[$i]['product_code'] ?>" <?= $selected?>><?= strtoupper($arrProductsPaperBag[$i]['item_name']) ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	

</div>

<?= get_footer() ?>
<script src="/assets/tinymce/plugin/tinymce/tinymce.min.js"></script>
<script src="/assets/tinymce/plugin/tinymce/init-tinymce.js"></script>
<script src="/js/select2.min.js"></script>
<script>
	$(".custom-file-input").on("change", function() {
		console.log( $(this).val());
		  var fileName = $(this).val().split("\\").pop();
		  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	$('#product_list').select2({
		closeOnSelect : false
    });
    $('#priority_list').select2({
		closeOnSelect : false
    });
    $('#antirad_list').select2({
		closeOnSelect : false
    });
    $('#merch_list').select2({
		closeOnSelect : false
    });
    $('#paperbag_list').select2({
		closeOnSelect : false
    });

    $('#set-text-image').click(function(){
    	tinymce.activeEditor.setContent('<?=$arrTextImages[0]['text']?>');
			$('#mceu_14').hide();
			$('#mceu_17').hide();
			$('#mceu_23').hide();
			$('#mceu_24').hide();
			$('#mceu_25').hide();
			$('#mceu_28').hide();
			$('#mceu_29').hide();
			$('#mceu_37').hide();
			$('#mceu_15').hide();
			$('#mceu_20').hide();
    	$('#text-image-settings').modal('show');
    });

	$('#set-products').click(function(){
		$('#recommended-settings').modal('show');
	});

	$('#set-priority').click(function(){
		$('#priority-settings').modal('show');
	});

	$('#set-antirad').click(function(){
		$('#antirad-settings').modal('show');
	});

	$('#set-merch').click(function(){
		$('#merch-settings').modal('show');
	});
	
	$('#set-paperbag').click(function(){
		$('#paperbag-settings').modal('show');
	});
</script>