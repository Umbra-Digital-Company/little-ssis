<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'audit';
$page_url = 'physical-count';

$filter_page = 'physical_count_page_auditor';
$group_name = 'main_menu';

// set_time_limit(0);
ini_set('memory_limit', '1G');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// error_reporting(0);
////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";

if ( isset($_GET['filterStores']) && $_GET['filterStores'] != '' ) {



	require $sDocRoot."/inventory/includes/grab_poll_51.php";
	require $sDocRoot."/inventory/includes/checker_functionsv5.php";


}

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v2.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Send away if not super user
// if($_SESSION['user_login']['userlvl'] != '15')  {

// 	header('location: /');
// 	exit;

// };

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";

/////SET USER
$inventory_user = $_SESSION['user_login']['store_code'] ;

$dateStartpdh = "";
$dateEndpdh = "";
$store_id = "";
$branchType="";
$branchName = "";
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
						<label for="submitPhysicalCount" class="btn btn-primary phSubmit" style="display: none;">Submit Physical Count</label>
					</div>

					<hr class="spacing">
					<div style="padding: 5px;display: flex; justify-content: flex-end;">
						<input type="hidden" id="count_poll51" value ="0">
						<div class="col-4" style="padding-right: 0px; padding-left: 0px;">
							<strong>TOTAL RUNNING:
							<span style="margin-left: 8px; display: none;" float="right" id="total_r">0</span>
						</strong>
						</div>
						<div class="col-5" style="padding-right: 0px; padding-left: 0px;">
							<strong>TOTAL PHYSICAL COUNT:
							<span style="margin-left: 8px" float="right" id="total_pc">0</span>
						</strong>
						</div>
					</div>
					<div id="excel-inventory" class="custom-card p-0">
						<form action="/inventory/process/actual_count_test.php" method="POST" autocomplete="off" class="table-default table-responsive" style="max-width: 100%;">
	
						<input type="hidden" name="store_audited" value="<?= $_GET['filterStores'] ?>">
									<input type="hidden" name="dateStartRange" value="<?= isset($_GET['ds']) && $_GET['ds']!='' ? $_GET['ds'] : '' ?>">
									<input type="hidden" name="dateEndRange" value="<?= isset($_GET['de']) && $_GET['de']!='' ? $_GET['de'] : '' ?>" >
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
								
					if ( isset($_GET['filterStores']) && $_GET['filterStores'] != '' ) {
						//$running_product= array();
							$fil_stores = '';
							$ds = '';
							$de = '';
							if(isset($_GET['filterStores'])){
								$fil_stores = $_GET['filterStores'];
								$ds = $_GET['ds'];
								$de = $_GET['de'];
							}
							for ($i=0;$i<sizeof($arrPoll51_items);$i++) {
											
										// if($branchType=='warehouse'){
										// 		$running_product[$arrPoll51_items[$i]['product_code']] = WarehouseChecker_auditor($arrPoll51_items[$i]['product_code'],$_GET['ds'],$_GET['de']); 

										// }elseif($branchType=='store'){
										// 	$running_product[$arrPoll51_items[$i]['product_code']] = StoreChecker_auditor($arrPoll51_items[$i]['product_code'],$_GET['filterStores'],$_GET['ds'],$_GET['de']);
										// }elseif($branchType=='lab'){
										// 	$running_product[$arrPoll51_items[$i]['product_code']]  = labChecker_auditor($arrPoll51_items[$i]['product_code'],$_GET['filterStores'],$_GET['ds'],$_GET['de']);
										// }
										// else{

										// 	$running_product[$arrPoll51_items[$i]['product_code'] ] ="0";
										// }
										
										?>
							
										<tr class="row100 body" branch_type ="<?= $branchType ?>" product_code ="<?= $arrPoll51_items[$i]['product_code'] ?>" filter_stores="<?= $fil_stores ?>" ds="<?= $ds ?>" de="<?= $de ?>">
										<th nowrap class="cell100 small column1">
												<?= $arrPoll51_items[$i]['product_style'] . " " . $arrPoll51_items[$i]['product_color'] ?>
												<p class="small text-secondary m-0"><?= $arrPoll51_items[$i]['product_code'] ?></p>
											</th>
											
											<!-- <td><?= $running_product[$arrPoll51_items[$i]['product_code']] ?></td> -->
											<td>-</td>

											<td style="max-width:100px;min-width:100px;width:100px" nowrap class="cell100 small text-center">
												
												<!-- <input type="hidden" name="running[]" value="<?= $running_product[$arrPoll51_items[$i]['product_code']] ?>">							
												<input type="hidden" name="product_code[]" value="<?= $arrPoll51_items[$i]['product_code'] ?>"> -->
												<input type="hidden" name="running[]">							
												<input type="hidden" name="product_code[]" value="<?= $arrPoll51_items[$i]['product_code'] ?>">


												<input type="number" class="form-control" name="actual_count[]" min="0" value="0">

											</td>
										</tr>

									<?php } ?>
								
									<?php } ?> 
								</tbody>

							</table>
							<input type="submit" id="submitPhysicalCount" class="sr-only" disabled>
						
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
								<div class="form-group error" style="background-color:rgba(255, 99, 71, 0.4); text-align: center;"></div>
								<div class="text-right mt-4">
									<button id="upload_file" name="import" class="btn btn-secondary btn-submit" disabled>Import</button>
								</div>
							</form>
						</div>

					<?php } ?>

				</div>

			</div>
			
		</div>

	</div>

</div>
<div id="loadingModal" class="modal" role="dialog" data-backdrop="static">
  <div class="modal-dialog" style=" margin-top: 23%;">
    <!-- Modal content-->
    <!-- <div class="modal-content"> -->
    	<div class="d-flex justify-content-center">
	      <img src="/images/loading.gif" width="15%" height="10%" />
	  </div>
   <!--  </div> -->
  </div>
</div>

<script src="/js/select2.min.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>
<style type="text/css">
	.dataTables_wrapper .pull-left, .dataTables_wrapper .pull-right{
		display: none;
	}
</style>
<link rel="stylesheet" type="text/css" href="/js/dataTables/datatables.min.css"/>
<script type="text/javascript" src="/js/dataTables/datatables.min.js"></script>
<script>
	 let count_poll51 = JSON.parse(JSON.stringify(<?= json_encode(count($arrPoll51_items)); ?>));
	$(document).ready(function() {
		$("#loadingModal").modal('show');
		let tableRowResult = tableRow();
		if(tableRow().row == 0){
			$("#submitPhysicalCount").attr('disabled', true);
			$(".phSubmit").attr('disabled', true);
			$('.phSubmit').hover(function(){
				$(this).css('background-color', '#36482e');
			})
			$(".phSubmit").css('cursor', 'default');
		}else{
			totalResult(tableRowResult.tr, tableRowResult.tpc);
		}
		$("#submitPhysicalCount").click(function(e){
			
			if(tableRow().row == 0){
				$("#submitPhysicalCount").attr('disabled', true);
				e.preventDefault();
			}
			else{
				$("#submitPhysicalCount").attr('disabled', false);
			}
		});
		$(".table-inventory tbody tr").on('keyup paste change', function(){
			tableRowResult = tableRow();
			totalResult(tableRowResult.tr, tableRowResult.tpc);
		});
		let oTable = $('.table-inventory').DataTable({
			"dom": '<"pull-left"f><"pull-right"l>tip',
			paging: false,
			"info": false,
			order: false,
			columnDefs: [{
			    targets: "_all",
			    orderable: false
			}]
		});
		function tableRow(){
			let row =0;
			let total_running = 0;
			let total_pc = 0;
			$(".table-inventory tbody tr").each(function(){
				tempTr =parseFloat($(this).find('td:eq(1)').find('input:eq(0)').val());
				tempTpc =parseFloat($(this).find('td:eq(1)').find('input:eq(2)').val());
				total_running += (!isNaN(tempTr)) ? tempTr : 0;
				total_pc += (!isNaN(tempTpc)) ? tempTpc : 0;
				row++;
			});
			return {"row":row, "tr":total_running, "tpc":total_pc};
		}
		function totalResult(tr,tpc){
			tr = formatNumber(tr);
			tpc = formatNumber(tpc);
			$("#total_r").text(tr);
			$("#total_pc").text(tpc);
		}
		function formatNumber(nStr)
		{
		    nStr += '';
		    x = nStr.split('.');
		    x1 = x[0];
		    x2 = x.length > 1 ? '.' + x[1] : '';
		    var rgx = /(\d+)(\d{3})/;
		    while (rgx.test(x1)) {
		        x1 = x1.replace(rgx, '$1' + ',' + '$2');
		    }
		    return x1 + x2;
		}
		if ( $('.table100-nextcols tbody tr').length > 10 ) {

			$('.wrap-table100').addClass('scroll');

		};
		$(".error").hide();
		let files = '';
		$('body').on('change', 'input[type=file]', function(e) {
	        files = e.target.files;
	        $(".error").hide();
	    });
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
	                    	console.log(response);
	                    	if(response.invalid_message != false){
	                    		$(".error").text(response.invalid_message);
	                    		$(".error").show();
	                    	}else{
		                    	for(let i = 0; i < response.data.length; i++){
		                        	$(".table-inventory tbody tr").each(function(){
		                        		if(response.data[i].product_code == $.trim($(this).find('td').find('input').eq(1).val())) {
		                        			$(this).find('td').find('input').eq(2).val(response.data[i].physical_count);
		                        			return false;
		                        		}
		                       		 });
		                        }
		                        tableRowResult = tableRow();
								totalResult(tableRowResult.tr, tableRowResult.tpc);

		                    }
	                        $('input[type=file]').val('');
	                    },
	                    error: function(jqXHR, textStatus, errorThrown){ console.log('ERRORS: ' + textStatus) }
	                });
	            }
	        }
	    });
	    $(".table-inventory tbody tr").each(function(){
	    	branch = $(this).attr('branch_type');
	    	product_code = $(this).attr('product_code');
	    	filter_stores = $(this).attr('filter_stores');
	    	ds = $(this).attr('ds');
	    	de = $(this).attr('de');
	    	let _this = $(this);


	    	$.get("running/running_data.php",{branch:encodeURIComponent(branch),filterStores:encodeURIComponent(filter_stores),product_code:encodeURIComponent(product_code),ds:encodeURIComponent(ds),de:encodeURIComponent(de)}, function(result){
	    		_this.find('td').eq(0).html(result);
	    		_this.find('td').eq(1).find('input').eq(0).val(result);
	    		let total_r = parseFloat($('#total_r').text());
	    		$('#total_r').text(total_r + parseFloat(result));

	    		let count = parseInt($("#count_poll51").val());
	    		count = count + 1;
	    		$("#count_poll51").val(count);

	    		if(parseInt($("#count_poll51").val()) == parseInt(count_poll51)){
	    			$('#total_r').show();
	    			$("#upload_file").removeAttr('disabled');
	    			$("#submitPhysicalCount").removeAttr('disabled');
	    			$("#loadingModal").modal('hide');
	    			$(".phSubmit").show();
	    		}

	    	});

	    	
	    });
	})

</script>

<?= get_footer() ?>