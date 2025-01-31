<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'create-query';
$page_url = 'create-query';

$filter_page = 'create_query';
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
					<p class="h2 ml-3">Create Query</p>
				</div>
				
			</div>
			<form id ="create_query">
				<div class="custom-card row">
					<div class="col-lg-7 col-xs-12">
						<div class="form-group">
							<label for="product">Template Name</label>
							<select class="form-control" id="query_list" name="query_list">
								<option value="">New Query</option>
								<?php
									for($i =0; $i < count($arrColumns); $i++){
										
										echo '<option value="'.$arrColumns[$i]['id'].'">'.$arrColumns[$i]['template_name'].'</option>';
									}
								?>
							</select>
							
						</div>
					</div>
				</div>
				<hr class="spacing">
				<div class="custom-card row">
					<div class="col-lg-12 col-xs-12">
						<div class="form-group">
							<label for="product">Users Access</label>
							<select class="form-control" id="users" name="users[]" multiple="multiple">
								<option value=""></option>
								<?php
									for($i =0; $i < count($arrUsers); $i++){
										if($arrUsers[$i]['id'] == $_SESSION['user_login']['id']){
											continue;
										}
										echo '<option value="'.$arrUsers[$i]['id'].'">'.ucwords($arrUsers[$i]['first_name'].' '.$arrUsers[$i]['last_name']).' - '.ucwords($arrUsers[$i]['position']).'</option>';
									}
								?>
							</select>
							
						</div>
					</div>
				</div>
				<!--<hr class="spacing">
				<div class="custom-card row">
					<div class="col-lg-4 col-xs-12">
						<div class="form-group">
							<label for="store">Store</label>
							<select class="form-control" name="store[]" id="store" multiple="multiple" required>
								<option value="all">All</option>
								<?php
									for($i =0; $i < count($arrStore); $i++){
										echo '<option value"'.$arrStore[$i]['store_id'].'">'.$arrStore[$i]['store_name'].'</option>';
									}
								?>
							</select>
							
						</div>
					</div>
				</div>
				 <hr class="spacing">
				<div class="custom-card row">
					<div class="col-lg-12" ><b><label style="font-size: 15px;">Table Columns</label></b></div><hr class="spacing"><br>
					<?php

					$arrColumnsNotIncluded = ['id','date_created','date_updated','delivery_unique','runner_count','admin_id','runner_id','runner_name','variance','variance_status','pick_up_date','transit_date'];
						for($i =0 ;$i < count($columns); $i++ ){

							if(in_array($columns[$i], $arrColumnsNotIncluded)){
								continue;
							}

							$column_name = explode('_', $columns[$i]);
							$final_column_name = '';
							for($b =0 ; $b < count($column_name); $b++){
								$space = ($b > 0) ? ' ' : '';
								$final_column_name .= $space.$column_name[$b];
							}
							echo '<div class="col-lg-2 col-xs-12" column="'.$columns[$i].'"> <div class="form-group"><input type="checkbox" class="columns_field" value="'.$columns[$i].'"><span class="checkboxtext">'.ucwords($final_column_name).'</span></div></div>';
						}

					?>
				</div> -->
				<hr class="spacing">
				<div class="custom-card row">
					<div class="col-lg-12" ><b><label style="font-size: 15px;">Raw Data</label></b></div><hr class="spacing"><br>
					<?php

					$arrColumnsSales = ['Daily Sales','Stock Transfer (+)','Stock Transfer (-)','Inter Branch (+)','Inter Branch (-)','Pullout','Damage (+)','Damage (-)','In Transit(+)','In Transit(-)'];
						for($i =0 ;$i < count($arrColumnsSales); $i++ ){
							echo '<div class="col-lg-3 col-xs-12"> <div class="form-group"><input type="checkbox" class="sales_columns_field" value="'.strtoupper($arrColumnsSales[$i]).'"><span class="checkboxtext">'.strtoupper($arrColumnsSales[$i]).'</span></div></div>';
						}
					?>
				</div>
				<hr class="spacing">
				<div class="custom-card row">
					<div class="col-lg-12" ><b><label style="font-size: 15px;">Processed Data</label></b></div><hr class="spacing"><br>
					<?php

					$arrColumnsSTP = ['Beginning Inventory','Daily Sales','Stock Transfer (+)','Stock Transfer (-)','Inter Branch (+)','Inter Branch (-)','Pullout','Damage (+)','Damage (-)','In Transit(+)','In Transit(-)','Running Inventory','Physical Count'];
						for($i =0 ;$i < count($arrColumnsSTP); $i++ ){
							echo '<div class="col-lg-3 col-xs-12"> <div class="form-group"><input type="checkbox" class="inventory_columns_field" value="'.strtoupper($arrColumnsSTP[$i]).'"><span class="checkboxtext">'.strtoupper($arrColumnsSTP[$i]).'</span></div></div>';
						}
					?>
				</div>
				<hr class="spacing">
				<div class="custom-card row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between" id="action">
					<div class="col-12 col-md-auto">
						<div class="d-flex align-items-center">
							<section>
								<p class="h3 font-bold">Action</p>
								<p class="text-secondary mt-1">Click the button to save/update/delete the current Template.</p>
							</section>
						</div>
					</div>
					<div class="col-12 col-md-auto" >
						<div class="d-flex align-items-center">
							<section>
								<div class="download_section" style="display: flex;">
									<div class="dl_btn input-group-prepend">
										<input type="button" class="btn btn-danger" id="delete_query" value="Delete Query" style="margin-right: 10px; display: none;">
								    	<input type="button" class="btn btn-primary text-white input-group-text" id="save_query" value="Save Template">
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

<div id="modal-query" class="modal fade" role="dialog">
  	<div class="modal-dialog" style="max-width: 80%;">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Query</h4>
	      </div>
		      <div class="modal-body">
			      	<form id="query-submit" action="POST">
			    		<div class="row">
			    			<div class="col-12">
				    			<div class="form-group">
			    					<label><strong>Template Name: </strong></label>
			    					<input type="text" name="template_name" id="template_name" class="form-control" required autocomplete="off">
			    				</div>
			    			</div>
			    		</div>
						<div class="d-flex justify-content-center">
							<input type="submit" class="btn btn-primary text-white input-group-text" id="submit_query" value="Submit">
						</div>
					</form>
		      </div>
	      <br>
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
		
	let arrColumns = <?= json_encode($arrColumns) ?>;
	let action_id = false;
	$(document).ready(function() {
		$('#query_list').select2();
		$('#users').select2({
         	closeOnSelect: false
        });

        $("#save_query").click(function(){
        	$('#template_name').focus();
        	$('#submit_query').show();
        	$("#modal-query").modal('show');
        });

        $('#query-submit').submit(function(e){
        	e.preventDefault();

        	let arrUsers = [];
        	let arrSalesColumns = [];
        	let arrInventoryColumns = [];

        	arrObjectUsers = $("#users").select2('data');

        	for(i = 0; i < arrObjectUsers.length; i++){
        		arrUsers.push(arrObjectUsers[i].id);
        	}

        	$('.sales_columns_field').each(function(){
        		if($(this).is(':checked')){
        			arrSalesColumns.push($(this).val());
        		}
        	});
        	$('.inventory_columns_field').each(function(){
        		if($(this).is(':checked')){
        			arrInventoryColumns.push($(this).val());
        		}
        	});

        	if(arrSalesColumns.length == 0 && arrInventoryColumns.length == 0){
		    	alert('Please select columns fields.'); 
		    	return false;
		    }
		    $('#submit_query').hide();
		    if(!action_id){
	        	$.post('/inventory/process/query/create_query.php',{template_name:$('#template_name').val(), users_id:JSON.stringify(arrUsers), sales_columns: JSON.stringify(arrSalesColumns),inventory_columns: JSON.stringify(arrInventoryColumns)}, function(result){
	        		alert(result);
	        		location.reload(true);
	        	},"JSON");
	        }else{
	        	$.post('/inventory/process/query/update_query.php',{id:action_id,template_name:$('#template_name').val(), users_id:JSON.stringify(arrUsers), sales_columns: JSON.stringify(arrSalesColumns),inventory_columns: JSON.stringify(arrInventoryColumns)}, function(result){
	        		alert(result);
	        		$('#template_name').val('');
	        		$("#modal-query").modal('hide');
	        		location.reload(true);
	        	},"JSON");
	        }
        });

        $('#query_list').change(function(){
        	if($(this).val() == ''){
        		$('#template_name').val('');
        		doneEach = $('#users option').each(function(){
					$(this).removeAttr('selected');
				});
				$.when(doneEach).done(function() {
					$('#users').css('width', '100%');
					$('#users').select2({
			         	closeOnSelect: false
			        });
				});

        		$('#users option').each(function(){
					$(this).removeAttr('selected');
				});
        		$('.sales_columns_field').each(function(){
	        		$(this).prop( "checked", false );
	        	});

	        	$('.inventory_columns_field').each(function(){
	        		$(this).prop( "checked", false );
	        	});
	        	$('#save_query').val('Save Query');
	        	action_id = false;
	        	$('#delete_query').hide();
        	}else{
        		action_id = $(this).val();
	        	columns = arrColumns.find(e => e.id== $(this).val());
	        	users_id = columns.users_access.split(",");
	        	sales_columns = columns.sales_columns.split(",");
	        	inventory_columns = columns.inventory_columns.split(",");
	        	$('#template_name').val(columns.template_name);
	        	doneEach = $('#users option').each(function(){
					(users_id.indexOf($(this).val().toString()) > -1) ? $(this).attr('selected', true) : $(this).removeAttr('selected');

				});
				$.when(doneEach).done(function() {
					$('#users').css('width', '100%');
					$('#users').select2({
			         	closeOnSelect: false
			        });
				});

	        	$('.sales_columns_field').each(function(){
	        		if(sales_columns.indexOf($(this).val()) > -1){
	        			$(this).prop( "checked", true );
	        		}else{
	        			$(this).prop( "checked", false );
	        		}
	        	});

	        	$('.inventory_columns_field').each(function(){
	        		if(inventory_columns.indexOf($(this).val()) > -1){
	        			$(this).prop( "checked", true );
	        		}else{
	        			$(this).prop( "checked", false );
	        		}
	        	});
	        	$('#save_query').val('Update Query');
	        	$('#delete_query').show();
	        }
        });
        $('.sales_columns_field').click(function(){
        	$('.inventory_columns_field').each(function(){
        		$(this).prop( "checked", false );
        	});
        });
        $('.inventory_columns_field').click(function(){
        	$('.sales_columns_field').each(function(){
        		$(this).prop( "checked", false );
        	});
        });

        $('#delete_query').click(function(){
        	if(confirm("Are you sure want delete the selected template query?")){
	        	$.post('/inventory/process/query/delete_query.php',{id:action_id}, function(result){
		        		alert(result);
		        		location.reload(true);
		        	},"JSON");
	        }
        });
	})

</script>

<?= get_footer() ?>