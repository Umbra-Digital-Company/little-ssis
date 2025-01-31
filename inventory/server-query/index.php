<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'server-query';
$page_url = 'server-query';

$filter_page = 'server_query';
$group_name = 'server_query';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/dashboard/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/inventory/includes/grab_stores_aim.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/inventory/includes/grab_poll_51.php";
require $sDocRoot."/inventory/includes/grab_inventory_columns.php";
require $sDocRoot."/inventory/includes/s_admin_function.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v2.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

/////SET USER
 //print_r($arrStore); exit;
?>


<?= get_header($page_url) ?>
<style>
	input[type=checkbox]
	{
	  /* Double-sized Checkboxes */
	  -ms-transform: scale(2); /* IE */
	  -moz-transform: scale(2); /* FF */
	  -webkit-transform: scale(2); /* Safari and Chrome */
	  -o-transform: scale(2); /* Opera */
	  transform: scale(2);
	  padding: 10px;
	}

	/* Might want to wrap a span around your checkbox text */
	.checkboxtext
	{
	  /* Checkbox text */
	  font-size: 110%;
	  display: inline;
	  margin-left: 15px;
	}
</style>
<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url) ?>
	
	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>
		
		<div class="ssis-content">

			<div class="dashboard-filter">
				<div class="filter-header row no-gutters align-items-center">
					<img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid" id="close-filter">
					<p class="h2 ml-3">Server Query</p>
				</div>
				
			</div>
			<form id ="run-query">
				<div class="custom-card row">
					<div class="col-lg-6 col-xs-12">
						<div class="form-group">
							<label for="product">Template Name</label>
							<select class="form-control" id="query_list" name="query_list" required>
								<option value="">Select Template</option>
								<?php
									for($i =0; $i < count($arrColumns); $i++){
										
										echo '<option value="'.$arrColumns[$i]['id'].'">'.$arrColumns[$i]['template_name'].'</option>';
									}
								?>
							</select>
							
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="form-group">
							<label for="store">Start Date</label>
							<input type="date" name="start_date" id="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
							
						</div>
					</div>
					<div class="col-lg-3 col-xs-12">
						<div class="form-group">
							<label for="store">End Date</label>
							<input type="date" name="end_date" id="end_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
							
						</div>
					</div>
					<div class="col-lg-10 col-xs-12">
						<div class="form-group">
							<label for="store">Branch</label>
							<select class="form-control" name="store[]" id="store" multiple="multiple" required>
								<option value="all">ALL</option>
								<option value="all_store">ALL STORE</option>
								<option value="all_lab">ALL LAB</option>
								<?php
									$arrStoreAccess = [];
									if($arrUsers[0]['position'] == 'laboratory' || $arrUsers[0]['position'] == 'store'){
										$arrStoreAccess = explode(',',$arrUsers[0]['store_code']);
									}elseif($arrUsers[0]['position'] == 'supervisor'){
										$arrStoreAccess = explode(',',$arrUsers[0]['store_location']);
									}

									$jsonStoreLab = [];
								?>
								<optgroup label="STORE NAME">
										<?php for ($i=0;$i<sizeof($arrStore);$i++) { ?>
											<?php
											if(count($arrStoreAccess) > 0 ){
												if(in_array($arrStore[$i]['store_id'], $arrStoreAccess)){
													$store_name = ucwords(str_replace(['ali','sm','mw'],['ALI','SM','MW'],strtolower($arrStore[$i]['store_name'])));
													$jsonStoreLab[] = [$arrStore[$i]['store_id'],$store_name];
											?>
													<option value="<?= $arrStore[$i]['store_id'] ?>"><?= $store_name ?></option>
											<?php
												}
											}else{
												$store_name = ucwords(str_replace(['ali','sm','mw'],['ALI','SM','MW'],strtolower($arrStore[$i]['store_name'])));
												$jsonStoreLab[] = [$arrStore[$i]['store_id'],$store_name];
												
											?>
													<option value="<?= $arrStore[$i]['store_id'] ?>"><?= $store_name ?></option>
											<?php
											}
											?>

										<?php } ?>
								</optgroup>
								<optgroup label="LAB NAME">
										<?php for ($i=0;$i<sizeof($arrLab);$i++) { ?>
											<?php
											if(count($arrStoreAccess) > 0 ){
												if(in_array($arrLab[$i]['lab_id'], $arrStoreAccess)){
													$lab_name = ucwords(str_replace('mtc', 'MTC', str_replace('-', ' ', $arrLab[$i]['lab_name'])));
													$jsonStoreLab[] = [$arrLab[$i]['lab_id'],$lab_name];
											?>
													<option value="<?= $arrLab[$i]['lab_id'] ?>"><?= $lab_name ?></option>
											<?php
												}
											}else{
												$lab_name = ucwords(str_replace('mtc', 'MTC', str_replace('-', ' ', $arrLab[$i]['lab_name'])));
												$jsonStoreLab[] = [$arrLab[$i]['lab_id'],$lab_name];
											?>
													<option value="<?= $arrLab[$i]['lab_id'] ?>"><?= $lab_name ?></option>
											<?php
											}
											?>
										<?php } ?>
								</optgroup>
							</select>
							
						</div>
					</div>
					<div class="col-lg-10 col-xs-12">
						<div class="form-group">
							<label for="product">Products</label>
							<select class="form-control" id="product_list" name="product_list" multiple="multiple" required>
								<option value="all">ALL PRODUCTS</option>
								<?php
									for($i =0; $i < count($arrPoll51_items); $i++){
										
										echo '<option value="'.$arrPoll51_items[$i]['product_code'].'">'.$arrPoll51_items[$i]['product_style'].' '.$arrPoll51_items[$i]['product_color'].'</option>';
									}
								?>
							</select>
							
						</div>
					</div>
					<div class="col-2 col-xs-12" >
						<div class="form-group">
							<label for="store">Action</label>
							<input type="submit" class="btn btn-primary text-white input-group-text" value="Run Template">
						</div>
					</div>
				</div>
				<hr class="spacing">
				<div id="inventory-receive" class="mt-1">
					<div class="table-default table-responsive" style="max-width: 100%;">
						<input type="hidden" id="count_poll51" value ="0">
						<table class="table-striped table-inventory" style="display: none;">
							<thead>
								<tr class="row100 head">
								</tr>
							</thead>
							<tbody>
							</tbody>
							<!-- <tfoot >
								<tr class="row100 foot footerc" >
									<th class="cell100 text-uppercase small column1   text-white" style="padding: 20px 15px;">Total <span style="float: right;" class="loading_all"><img src="/images/loading_gray.gif" width="30px" height="30px"></span></th>
									<th class="cell100 text-uppercase text-center small column3  text-white" style="padding: 20px 15px;" nowrap>-</th>
									<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-daily" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-change-order" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"   style="padding: 20px 15px;"  id="col-delivery" nowrap>-</th>
								
									<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"    style="padding: 20px 15px;"  id="col-inter-inc" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;"  id="col-inter-dec" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-pullout" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-damage" nowrap>-</th>
								
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;" id="col-transit" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-transit" nowrap>-</th>
									
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-running" nowrap>-</th>
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-physical" nowrap>-</th>
									<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-variance" nowrap>-</th>
								</tr>
							</tfoot> -->
						</table>
					</div>
				</div>
				<hr class="spacing">
				<div class="custom-card row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between" id="action_download" style="display: none;">
					<div class="col-12 col-md-auto">
						<div class="d-flex align-items-center">
							<section>
								<p class="h3 font-bold">Action Download</p>
								<p class="text-secondary mt-1">Click the button to download the current template.</p>
							</section>
						</div>
					</div>
					<div class="col-12 col-md-auto" >
						<div class="d-flex align-items-center">
							<section>
								<div class="download_section" style="display: flex;">
									<div class="input-group-prepend" id="downloading_img" style="display: none;">
								    	<img src="/images/downloading.gif" width="30px" height="30px">
								  	</div>
									<div class="dl_btn input-group-prepend">
								    	<input type="button" class="btn btn-primary text-white input-group-text" id="download_query" onclick="download_csv()" value="Download">
								  	</div>
								</div>
							</section>
						</div>
					</div>
				</div>
			</form>
		</div>

	</div>

</div>
<style>
	.modal-header
	{
	display: block!important;
	}
	.modal-title
	{
	float: left;
	}
	.modal-header .close
	{
	float: right;
	}
</style>

<div id="modalStore" class="modal fade" role="dialog" data-backdrop="static">
  	<div class="modal-dialog" style="max-width: 60%;">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <h4 class="modal-title">Branch List</h4>
	      </div>
		      <div class="modal-body">
			    	<div class="table-def auto">
						<div class="table-responsive">
							<table class="table table-striped" id="store-table">
								<thead>
									<tr class="text-center">
										<th scope="col" class="text-uppercase font-bold text-center" style="color: #fff;">STORE</th>
										<th scope="col" class="text-uppercase font-bold text-center" style="color: #fff;">STATUS</th>
									</tr>
								</thead>
								<tbody class="text-center tableBody">
								</tbody>
							</table>
						</div>
					</div>
		      </div>
	    </div>
  	</div>
</div>


<script src="/js/select2.min.js"></script>
<!-- <script src="/js/inventory.js?v=<?= date('His') ?>"></script> -->
<!-- <style type="text/css">
	.dataTables_wrapper .pull-left, .dataTables_wrapper .pull-right{
		display: none;
	}
</style> -->
<!-- <link rel="stylesheet" type="text/css" href="/js/dataTables/datatables.min.css"/> -->
<!-- <script type="text/javascript" src="/js/dataTables/datatables.min.js"></script> -->
<script>
	let arrStoreLabList = <?= json_encode($jsonStoreLab) ?>;
	let arrColumns = <?= json_encode($arrColumns) ?>;
	let arrPoll51_items = <?= json_encode($arrPoll51_items) ?>;
	let count_poll51 = <?= json_encode(count($arrPoll51_items)); ?>;
	let arrAllObjectData = [];
	let arrClassNameColumn = [];
	let arrClassNameColumnHeader = [];
	$(document).ready(function() {
		$('#store').select2({
         	closeOnSelect: false
        });
        $('#product_list').select2({
         	closeOnSelect: false
        });
		$('#query_list').select2();
		
		function getClassColumn(column){
			columns_class = '';
	      
        	if(column == 'BEGINNING INVENTORY') columns_class = 'beginning_inventory_value';
        	else if(column == 'DAILY SALES') columns_class = 'daily_sales_value';
        	else if(column == 'STOCK TRANSFER (+)') columns_class = 'stock_transfer_plus_value';
        	else if(column == 'STOCK TRANSFER (-)') columns_class = 'stock_transfer_minus_value';
        	else if(column == 'INTER BRANCH (+)') columns_class = 'inter_branch_plus_value';
        	else if(column == 'INTER BRANCH (-)') columns_class = 'inter_branch_minus_value';
        	else if(column == 'PULLOUT') columns_class = 'pullout_value';
        	else if(column == 'DAMAGE') columns_class = 'damage_value';
        	else if(column == 'IN TRANSIT(+)') columns_class = 'in_transit_plus_value';
        	else if(column == 'IN TRANSIT(-)') columns_class = 'in_transit_minus_value';
        	else if(column == 'RUNNING INVENTORY') columns_class = 'running_inventory_value';
        	else if(column == 'PHYSICAL COUNT') columns_class = 'physical_count_value';

        	return columns_class;
		}

		let arrStore = [];
		let arrProductCode = [];
		let arrObjectData = [];
		let numStore = 0;
		let count = 0;
		let bool_header_column = false;
		let modalViewStore = [];
        $('#run-query').submit(function(e){
        	e.preventDefault();
        	arrStore = [];
			arrProductCode = [];
			arrObjectData = [];// array of all data to be show
			numStore = 0;
			count = 0;
			bool_header_column = false;
			arrAllObjectData = [];
			modalViewStore = [];
        	$('.table-inventory').hide();
        	$('#action_download').hide();
        	$('.table-inventory tbody tr').remove();

        	arrObjectStore = $("#store").select2('data');
        	//console.log(arrObjectStore);
        	for(i = 0; i < arrObjectStore.length; i++){
        		arrStore.push(arrObjectStore[i].id);

        		objStore = {
        			store_id : arrObjectStore[i].id,
        			store_name : arrObjectStore[i].text
        		}
        		modalViewStore.push(objStore);
        	}
        	if(arrStore.indexOf('all') > -1){
        		arrStore = [];
        		modalViewStore = [];
        		for(i =0 ; i < arrStoreLabList.length; i++){
        			arrStore.push(arrStoreLabList[i][0]);
        			objStore = {
	        			store_id : arrStoreLabList[i][0],
	        			store_name : arrStoreLabList[i][1]
	        		}
	        		modalViewStore.push(objStore);
        		}

        	}else if(arrStore.indexOf('all_store') > -1){
        		arrStore = [];
        		modalViewStore = [];
        		for(i =0 ; i < arrStoreLabList.length; i++){
        			if(arrStoreLabList[i][0].length <= 4){
	        			arrStore.push(arrStoreLabList[i][0]);
	        			objStore = {
		        			store_id : arrStoreLabList[i][0],
		        			store_name : arrStoreLabList[i][1]
		        		}
		        		modalViewStore.push(objStore);
		        	}
        		}
        	}
        	else if(arrStore.indexOf('all_lab') > -1){
        		arrStore = [];
        		modalViewStore = [];
        		for(i =0 ; i < arrStoreLabList.length; i++){
        			if(arrStoreLabList[i][0].length > 4){
	        			arrStore.push(arrStoreLabList[i][0]);
	        			objStore = {
		        			store_id : arrStoreLabList[i][0],
		        			store_name : arrStoreLabList[i][1]
		        		}
		        		modalViewStore.push(objStore);
		        	}
        		}
        	}

        	//show modal store
        	storeString ='';
        	for(i = 0; i <modalViewStore.length; i++){
        		storeString += '<tr store_id="'+modalViewStore[i].store_id+'">'+
        						'<td class="text-left">'+modalViewStore[i].store_name+'</td>'+
        						'<td class="text-left"><img src="/images/loading_gray.gif" width="20px" height="20px"></td>'+
        						'</tr>';
        	}
        	$('#store-table tbody').html(storeString);
        	$('#modalStore').modal('show');


        	arrObjectProducts = $("#product_list").select2('data');
        	for(i = 0; i < arrObjectProducts.length; i++){
        		arrProductCode.push(arrObjectProducts[i].id);
        	}
        	count_poll51 = arrObjectProducts.length;
        	if(arrProductCode.indexOf('all') > -1){
        		arrProductCode = [];
        		for(i =0 ; i < arrPoll51_items.length; i++){
        			arrProductCode.push(arrPoll51_items[i].product_code);
        		}
        		count_poll51 = arrPoll51_items.length;
        	}

        	//console.log(arrStore);

        	columns = arrColumns.find(e => e.id== $('#query_list').val());
	        arrSalesColumns = columns.sales_columns.split(",");
	        if(arrSalesColumns.length > 0 &&  arrSalesColumns[0] != ''){
	        	dataRow(numStore, arrSalesColumns);
	        }else{
		    	dataColumns(numStore);
		    }
	    	

	    	//$('#modalStore').modal('show');
        });
         function dataRow(store_id, arrSalesColumns){
        	filter_store = arrStore[store_id];
        	arrBranchName = modalViewStore.find(e => e.store_id== filter_store);
        	branch_name = arrBranchName.store_name;
        	ds = $('#start_date').val();
	    	de = $('#end_date').val();
	    	dateEndpdh = $('#end_date').val();
	    	branch = (!isNaN(filter_store)) ? 'store' : 'lab';
			for(p =0; p < arrProductCode.length; p++){
    			product_code = arrProductCode[p];
    			product = arrPoll51_items.find(e => e.product_code == product_code);
    			product_name = product.product_style+' '+product.product_color;

    			$.get("/inventory/details/aim_details.php",{branch:branch,branch_name:branch_name,filterStores:encodeURIComponent(filter_store),product_name:product_name,sku_code:encodeURIComponent(product_code),dateStart:encodeURIComponent(ds),dateEnd:encodeURIComponent(de),column_header:JSON.stringify(arrSalesColumns)}, function(result){

    				//console.log(result);
    				store_id_value =  result[0].aim_store_id;
    				for(i = 0; i < result.length; i++){
    					if(result[i].count == '-'){
    						continue;
    					}
	    				objectColumnData ={
	    					product_code : result[i].product_code,
	    					product_name : result[i].product_name,
	    					store_id_value : store_id_value,
	    					branch_name : result[i].branch_name,
	    					transaction_type : result[i].transaction_type,
	    					transaction_date : result[i].transaction_date,
	    					count : result[i].count,
	    					branch_stock_from : result[i].branch_stock_from,
	    					recipient : result[i].recipient,
	    					po_reference_number : result[i].po_reference_number
	    				}
	    				arrObjectData.push(objectColumnData);
	    			}

    				
		    		count++;
		    		if(count == parseInt(count_poll51)){
						dataToShowRow();
						storeDone(store_id_value);
						count = 0;
						numStore++;
						
						if(numStore < arrStore.length){
							arrObjectData = [];
							setTimeout(() => {
								dataRow(numStore,arrSalesColumns);
							}, 1000);
						}
						if(numStore == arrStore.length){
							$('.loading_all').hide();
							setTimeout(() =>{
								$('#modalStore').modal('hide');
							}, 2000);
							$('#action_download').show();
						}
						
		    		}

	    		},'JSON');
    		}
		}

        function dataColumns(store_id){
        	filter_store = arrStore[store_id];
        	arrBranchName = modalViewStore.find(e => e.store_id== filter_store);
        	branch_name = arrBranchName.store_name;
        	ds = $('#start_date').val();
	    	de = $('#end_date').val();
	    	dateEndpdh = $('#end_date').val();
			for(p =0; p < arrProductCode.length; p++){
    			product_code = arrProductCode[p];
    			product = arrPoll51_items.find(e => e.product_code == product_code);
    			product_name = product.product_style+' '+product.product_color;

    			$.get("/inventory/details/aim_columns_data.php",{branch_name:branch_name,filterStores:encodeURIComponent(filter_store),product_name:product_name,product_code:encodeURIComponent(product_code),dateStart:encodeURIComponent(ds),dateEnd:encodeURIComponent(de),dateEndpdh:encodeURIComponent(dateEndpdh)}, function(result){

    				product_code = result.aim_product_code;
    				product_name = result.aim_product_name;
    				store_id_value = result.aim_store_id;
    				branch_name_value = result.branch_name;
    				beginning_inventory_value = (result.beg_inventory != null) ? result.beg_inventory : 0;
    				daily_sales_value = (result.sales != null) ? result.sales : 0;
    				stock_transfer_plus_value = (result.stock_transfer_in_c != null) ? result.stock_transfer_in_c : 0;
    				stock_transfer_minus_value = (result.stock_transfer_out_c != null) ? result.stock_transfer_out_c : 0;
    				inter_branch_plus_value = (result.interbranch_in_c != null) ? result.interbranch_in_c : 0;
    				inter_branch_minus_value = (result.interbranch_out_c != null) ? result.interbranch_out_c : 0;
    				pullout_value = (result.pullout_c != null) ? result.pullout_c : 0;
    				damage_value = (result.damage_c != null) ? result.damage_c : 0;
    				in_transit_plus_value = (result.transit_in != null) ? result.transit_in : 0;
    				in_transit_minus_value = (result.transit_out_c != null) ? result.transit_out_c : 0;
    				running_inventory_value = (result.running_total != null) ? result.running_total : 0;
    				physical_count_value = (result.physical_count != null) ? result.physical_count : 0;
    				variance_value = (result.variance != null) ? result.variance : 0;
    				//console.log(store_id_value);
    				objectColumnData ={
    					product_code : product_code,
    					product_name : product_name,
    					store_id_value : store_id_value,
    					branch_name : branch_name_value,
    					beginning_inventory_value : beginning_inventory_value,
    					daily_sales_value : daily_sales_value,
    					stock_transfer_plus_value : stock_transfer_plus_value,
    					stock_transfer_minus_value : stock_transfer_minus_value,
    					inter_branch_plus_value : inter_branch_plus_value,
    					inter_branch_minus_value : inter_branch_minus_value,
    					pullout_value : pullout_value,
    					damage_value : damage_value,
    					in_transit_plus_value : in_transit_plus_value,
    					in_transit_minus_value : in_transit_minus_value,
    					running_inventory_value : running_inventory_value,
    					physical_count_value : physical_count_value,
    					variance_value : variance_value
    				}

    				arrObjectData.push(objectColumnData);
		    		count++;
		    		if(count == parseInt(count_poll51)){
						dataToShow();
						storeDone(store_id_value);
						count = 0;
						numStore++;
						
						if(numStore < arrStore.length){
							arrObjectData = [];
							setTimeout(() => {
								dataColumns(numStore);
							}, 1000);
						}
						if(numStore == arrStore.length){
							$('.loading_all').hide();
							setTimeout(() =>{
								$('#modalStore').modal('hide');
							}, 2000);
							$('#action_download').show();
						}
						
		    		}

	    		},'JSON');
    		}
		}
		
		function dataToShow(){
			
			columns = arrColumns.find(e => e.id== $('#query_list').val());
	        arrSalesColumns = columns.sales_columns.split(",");
	        arrInventoryColumns = columns.inventory_columns.split(",");
			if(!bool_header_column){
				arrClassNameColumnHeader = ['PRODUCT CODE','PRODUCT NAME','BRANCH'];
		        header = '<th class="cell100 text-uppercase small column1">SKU <span style="float: right;" class="loading_all"><img src="/images/loading_gray.gif" width="30px" height="30px"></span></th>';
		        header += '<th class="cell100 text-uppercase small column1">Store</th>';
		        if(arrSalesColumns.length > 0 &&  arrSalesColumns[0] != ''){
		        	for(i = 0; i < arrSalesColumns.length; i++){
		        		header += '<th class="cell100 text-uppercase text-center small column3 text-center" nowrap>'+arrSalesColumns[i]+'</th>';
		        		arrClassNameColumnHeader.push(arrSalesColumns[i]);
		        	}
		        }else{
		        	for(i = 0; i < arrInventoryColumns.length; i++){
		        		header += '<th class="cell100 text-uppercase text-center small column3 text-center" nowrap>'+arrInventoryColumns[i]+'</th>';
		        		arrClassNameColumnHeader.push(arrInventoryColumns[i]);
		        	}
		        }
		        $('.table-inventory thead tr').html(header);
		        $('.table-inventory').show();
		       	bool_header_column = true;
		    }
		    //to get specific columns in selected template query
		    arrClassNameColumn = [];
		    
		    if(arrSalesColumns.length > 0 &&  arrSalesColumns[0] != ''){
		        	for(i = 0; i < arrSalesColumns.length; i++){
		        		arrClassNameColumn.push(getClassColumn(arrSalesColumns[i]));
		        	}
	        }else{
	        	for(i = 0; i < arrInventoryColumns.length; i++){
	        		arrClassNameColumn.push(getClassColumn(arrInventoryColumns[i]));
	        	}
	        	
	        }
	        //sort column via product name
			arrObjectData.sort((a,b) => (a.product_name.trim() > b.product_name.trim()) ? 1 : ((b.product_name.trim() > a.product_name.trim()) ? -1 : 0))

			arrAllObjectData.push(arrObjectData);
			//console.log(arrAllObjectData);
			//showing the data
			let columns_data = '';
	        for(i = 0; i < arrObjectData.length; i++){
	        	columns_data += '<tr product_code="'+arrObjectData[i].product_code+'">';
	       		columns_data += '<th nowrap class="cell100 small column1 ">'+arrObjectData[i].product_name +'<p class="small text-secondary m-0">'+arrObjectData[i].product_code+'</p></th>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].branch_name+'</td>';
	       		
	       		columns_data += (arrClassNameColumn.indexOf("beginning_inventory_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].beginning_inventory_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("daily_sales_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].daily_sales_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("stock_transfer_plus_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].stock_transfer_plus_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("stock_transfer_minus_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].stock_transfer_minus_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("inter_branch_plus_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].inter_branch_plus_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("inter_branch_minus_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].inter_branch_minus_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("pullout_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].pullout_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("damage_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].damage_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("in_transit_plus_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].in_transit_plus_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("in_transit_minus_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].in_transit_minus_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("running_inventory_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].running_inventory_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("physical_count_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].physical_count_value+'</td>' : '';
	       		columns_data += (arrClassNameColumn.indexOf("variance_value") > -1) ? '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].variance_value+'</td>' : '';
		       		
	        	columns_data +='</tr>';
	        }
	        $('.table-inventory tbody').append(columns_data);
		}

		function dataToShowRow(){
			
			columns = arrColumns.find(e => e.id== $('#query_list').val());
	        arrSalesColumns = columns.sales_columns.split(",");
			if(!bool_header_column){
				arrClassNameColumnHeader = ['PRODUCT CODE','PRODUCT NAME','BRANCH','TRANSACTION','PO NUMBER/REFERENCE NUMBER', 'COUNT', 'STOCK FROM','RECIPIENT','TRANSACTION DATE'];
		        header = '<th class="cell100 text-uppercase small column1">SKU <span style="float: right;" class="loading_all"><img src="/images/loading_gray.gif" width="30px" height="30px"></span></th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">BRANCH</th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">Transaction</th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">Po Number/<br>Reference Number</th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">Count</th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">Stock From</th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">Recipient</th>';
		        header += '<th class="cell100 text-uppercase small column1 text-center">Transaction Date</th>';
		        

		        $('.table-inventory thead tr').html(header);
		        $('.table-inventory').show();
		       	bool_header_column = true;
		    }
	        //sort column via product name
			arrObjectData.sort((a,b) => (a.product_name.trim() > b.product_name.trim()) ? 1 : ((b.product_name.trim() > a.product_name.trim()) ? -1 : 0))

			arrAllObjectData.push(arrObjectData);
			//console.log(arrAllObjectData);
			//showing the data
			let columns_data = '';
	        for(i = 0; i < arrObjectData.length; i++){
	        	columns_data += '<tr product_code="'+arrObjectData[i].product_code+'">';
	       		columns_data += '<th nowrap class="cell100 small column1 ">'+arrObjectData[i].product_name +'<p class="small text-secondary m-0">'+arrObjectData[i].product_code+'</p></th>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].branch_name+'</td>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].transaction_type+'</td>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].po_reference_number+'</td>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].count+'</td>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].branch_stock_from+'</td>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].recipient+'</td>';
	       		columns_data += '<td nowrap class="cell100 small text-left ">'+arrObjectData[i].transaction_date+'</td>';		
	        	columns_data +='</tr>';
	        }
	        $('.table-inventory tbody').append(columns_data);
		}

		function storeDone(store_id){
			$('#store-table tbody tr').each(function(){
				if($(this).attr('store_id') == store_id){
					$(this).find('td').eq(1).text('Done');
					return false;
				}
			});
		}

		
	})
	function download_csv() {
		let csv_data =  [];
		csv_data.push(arrClassNameColumnHeader);
		$("#downloading_img").show();

		columns = arrColumns.find(e => e.id== $('#query_list').val());
	    arrSalesColumns = columns.sales_columns.split(",");
	       
		if(arrSalesColumns.length > 0 &&  arrSalesColumns[0] != ''){
			for(i =0; i < arrAllObjectData.length; i++){
				for(a = 0; a < arrAllObjectData[i].length; a ++){

					let arrIncludedColumns = [];
					arrIncludedColumns.push(arrAllObjectData[i][a].product_code);
					arrIncludedColumns.push(arrAllObjectData[i][a].product_name);
					arrIncludedColumns.push(arrAllObjectData[i][a].branch_name);
					arrIncludedColumns.push(arrAllObjectData[i][a].transaction_type);
					arrIncludedColumns.push('="'+arrAllObjectData[i][a].po_reference_number+'"');
					arrIncludedColumns.push(arrAllObjectData[i][a].count);
					arrIncludedColumns.push(arrAllObjectData[i][a].branch_stock_from);
					arrIncludedColumns.push(arrAllObjectData[i][a].recipient);
					arrIncludedColumns.push(arrAllObjectData[i][a].transaction_date.replace(',',''));
					csv_data.push(arrIncludedColumns);
				}
			}
		}else{

			for(i =0; i < arrAllObjectData.length; i++){
				for(a = 0; a < arrAllObjectData[i].length; a ++){

					let arrIncludedColumns = [];

					arrIncludedColumns.push(arrAllObjectData[i][a].product_code);
					arrIncludedColumns.push(arrAllObjectData[i][a].product_name);
					arrIncludedColumns.push(arrAllObjectData[i][a].branch_name);
					(arrClassNameColumn.indexOf("beginning_inventory_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].beginning_inventory_value) : '';
					(arrClassNameColumn.indexOf("daily_sales_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].daily_sales_value) : '';
					(arrClassNameColumn.indexOf("stock_transfer_plus_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].stock_transfer_plus_value) : '';
					(arrClassNameColumn.indexOf("stock_transfer_minus_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].stock_transfer_minus_value) : '';
					(arrClassNameColumn.indexOf("inter_branch_plus_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].inter_branch_plus_value) : '';
					(arrClassNameColumn.indexOf("inter_branch_minus_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].inter_branch_minus_value) : '';
					(arrClassNameColumn.indexOf("pullout_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].pullout_value) : '';
					(arrClassNameColumn.indexOf("damage_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].damage_value) : '';
					(arrClassNameColumn.indexOf("in_transit_plus_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].in_transit_plus_value) : '';
					(arrClassNameColumn.indexOf("in_transit_minus_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].in_transit_minus_value) : '';
					(arrClassNameColumn.indexOf("running_inventory_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].running_inventory_value) : '';
					(arrClassNameColumn.indexOf("physical_count_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].physical_count_value) : '';
					(arrClassNameColumn.indexOf("variance_value") > -1) ? arrIncludedColumns.push(arrAllObjectData[i][a].variance_value) : '';

					csv_data.push(arrIncludedColumns);
				}
			}
		}
	    csv ='';
	    csv_data.forEach(function(row) {
	            csv += row.join(',');
	            csv += "\n";
	    });


	    var hiddenElement = document.createElement('a');
	    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
	    hiddenElement.target = '_blank';
	    hiddenElement.download = 'server_query_'+getDate()+'.csv';
	    hiddenElement.click();

	    $("#downloading_img").hide();
	}
	function getDate(){
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();

		return yyyy+''+mm+''+dd;
	}

</script>

<?= get_footer() ?>