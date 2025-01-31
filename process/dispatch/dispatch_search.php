<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

require $sDocRoot."/includes/functions.php";
include("search-dispatch.php");

for($i=0;$i<sizeof($searchUser);$i++){
	
	$searchup = '1';

?>

<form method="post" name="lam_form" id="lab_form" >
				
	<div class="form-action text-right">

		<input type="button" class="sr-only" id="save_action"> 
		<input type="button" class="sr-only" id="reject_action">
		<input type="hidden" value="" name="action_type" id="action_type_lab">
		<div class="remarks-overlay"></div>

	</div>

	<div class="wrap-table100 non-search">
		<div class="table100 ver1">

			<div class="table100-firstcol">

				<table>
					<thead>
						<tr class="row100 head">
							<th class="cell100 small column1"><a href="./?page=dispatch&sort=atoz<?php if(isset($_GET['sort']) && (!isset($_GET['sort2']))){ ?>&sort2=ztoa<?php } ?>">Customers</a></th>
						</tr>
					</thead>
					<tbody>

					<?php 

						for ( $i = 0; $i < sizeof($searchUser); $i++ ) {

							$dateExpired[$i]= date("Y-m-d h:i:s",strtotime(date("Y-m-d", strtotime($searchUser[$i]["store_dispatch_date"])) . " +3days"));

							if($dateExpired[$i] >=date ("Y-m-d h:i:s") || ($searchUser[$i]["store_dispatch_date"]=='0000-00-00 00:00:00' || $searchUser[$i]["store_dispatch_date"]=='1910-01-01 00:00:00' || $searchUser[$i]["store_dispatch_date"]=='' ) ){
			
								$id_message= CheckMessage($searchUser[$i]['orders_specs_id']);
				
								if($id_message){

									if($id_message!=$_SESSION['id'] || $id_message=='' || $id_message == 'unknown'){

										$text = "danger";

									}
									elseif($id_message==$_SESSION['id']){

										$text = "success";
									}

								}	
								else{

									$text = "success";

								}	
			
					?>

						<tr class="row100 body">
							<td nowrap class="cell100 column1">
								<div class="row no-gutters align-items-center justify-content-start table-wrapper">
									<a href="#" id="iremarks_<?php echo $searchUser[$i]['orders_specs_id']; ?>" class="iremarks text-<?= $text ?>" data-toggle="modal" data-target="#informationRemarks" data-id="<?php echo $searchUser[$i]['orders_specs_id']; ?>"><i class="zmdi zmdi-info"></i></a>
									<!-- <?= $i + 1 ?>.<a href="./?page=customerdetails&orderNo=<?= $searchUser[$i]['order_id'] ?>&profile_id=<?= $searchUser[$i]['profile_id'] ?>&comp=release" ><?= ucwords( $searchUser[$i]['first_name'] )." ".ucwords( $searchUser[$i]['last_name'] ); ?></a> -->
									<?= $i + 1 ?>.<a href="customer/?profile_id=<?= $searchUser[$i]["profile_id"]?>&orderNo=<?= $searchUser[$i]["orders_specs_id"]?>" ><?= ucwords( $searchUser[$i]['first_name'] )." ".ucwords( $searchUser[$i]['last_name'] ); ?></a>
								</div>
							</td>
						</tr>

					<?php 

							};

						};

					?>

					</tbody>
				</table>

			</div>

			<div class="wrap-table100-nextcols js-pscroll">
				<div class="table100-nextcols">

					<table>
						<thead>
							<tr class="row100 head">
								<th class="cell100 small">PO #</th>
								<th class="cell100 small column2"><?php tabDetails( 'lab', 'false' ); ?></th>
								<th class="cell100 small column3" nowrap><?php tabDetails( 'payment', 'true' ); ?></th>
								<th class="cell100 small column3" nowrap><?php tabDetails( 'processed', 'true' ); ?></th>
								<th class="cell100 small column3" nowrap><?php tabDetails( 'production', 'true' ); ?></th>
								<th class="cell100 small column3" nowrap><?php tabDetails( 'completed', 'true' ); ?></th>
								<th class="cell100 small column3" nowrap><?php tabDetails( 'received', 'true' ); ?></th>
								<th class="cell100 small column3" nowrap><?php tabDetails( 'dispatched', 'true' ); ?></th>
								<th class="cell100 small column3 text-center" nowrap colspan="2">options</th>
							</tr>
						</thead>
						<tbody>

							<?php 
															
								for ( $i = 0; $i < sizeof($searchUser); $i++ ) { 

									$dateExpired[$i]= date("Y-m-d h:i:s",strtotime(date("Y-m-d", strtotime($searchUser[$i]["store_dispatch_date"])) . " +3days"));

									if($dateExpired[$i] >=date ("Y-m-d h:i:s") || ($searchUser[$i]["store_dispatch_date"]=='0000-00-00 00:00:00' || $searchUser[$i]["store_dispatch_date"]=='1910-01-01 00:00:00' || $searchUser[$i]["store_dispatch_date"]=='' ) ){

							?>

							<input type="hidden" name="off[]" value="<?php echo $searchUser[$i]["id"] ?>">
							<input type="hidden" name="order_id[<?php echo $searchUser[$i]["id"] ?>]" value="<?php echo $searchUser[$i]["order_id"] ?>">
							<input type="hidden"  name="name[<?php echo $searchUser[$i]["id"] ?>]"  value="<?php echo  ucwords( $searchUser[$i]['first_name'] )." ".ucwords( $searchUser[$i]['last_name'] ); ?>">

							<?php 

									$payment = $searchUser[$i]['payment'];
									$print = $searchUser[$i]['lab_print'];
									$production = $searchUser[$i]['lab_production'];
									$lab_status = $searchUser[$i]['lab_status'];
									$receive = $searchUser[$i]['received_stat'];
									$dispatch = $searchUser[$i]['store_dispatch'];

							?>

							<tr class="row100 body">
								<td nowrap class="cell100 small text-center">
									<?= $searchUser[$i]['po_number']; ?>
									<br> 
									<?= $searchUser[$i]['dispatch_type']; ?>
								</td>
								<td nowrap class="cell100 small text-center column2">
									<?= ucwords($searchUser[$i]['lab_name']) ?>
								</td>
								<td nowrap class="cell100 small text-center column3 <?= ( $payment == 'y' ) ? 'bg-success' : 'bg-danger'; ?>" >
									<?= ( $payment == 'y' ) ? 'Paid' : 'No'; ?>
									<br>
									<?php echo "<font size='-7'>".cvdate2($searchUser[$i]['payment_date'])."</font>"; ?>
								</td>
								<td nowrap class="cell100 small text-center column4 <?= ( $print == 'y' ) ? 'bg-success' : 'bg-danger'; ?>">

									<?php 

										if( $print == 'y' ) { 

											echo 'Processed'; 
										}
										elseif($searchUser[$i]['product_upgrade']=='special_order') { 

											echo "Essilor"; 

										}
										else{  

											echo 'No'; 

										} 

									?>
									<br>
								
									<?php 

										if($searchUser[$i]['lab_print_date']=='1910-01-01 22:59:52') { 
										} 
										else {  

											echo "<font size='-7'>".$searchUser[$i]['lab_print_date']."</font>"; 

										}	

									?>

								</td>												
								<td nowrap class="cell100 small text-center column5 

								<?php 
											  
								  if($receive=='r'){  

								  	echo  'bg-danger'; 

								  }
								  elseif( $production == 'y'  && $lab_status=='n' ) { 

								  	echo 'bg-warning'; 

								  } 
								  elseif($production == 'y' && $lab_status=='y'){ 

								  	echo 'bg-success'; 

								  } 
								  elseif($searchUser[$i]['product_upgrade']=='special_order'){ 

								  	echo 'bg-danger'; 

								  }

								?>">
								
									<?php 

										if($receive=='r'){

											echo "Returned";

										}
										else{

											if($production == 'y' && $lab_status=='n'){

												if(date("Y-m-d h:i:sa")>=$searchUser[$i]['target_date']){

													echo "Late";

												}
												else{

													echo "On Going";

												}

											} 
											elseif($searchUser[$i]['product_upgrade']=='special_order'){ 

												echo "Essilor"; 

											}
											elseif($production == 'y' && $lab_status=='y'){ 

												echo "Done"; 

											} elseif($production == 'n' && $lab_status=='n'){ 

												echo "No"; 

											}

										}

									?>
									<br>
									<span class="small">

										<?php 

											if($searchUser[$i]['lab_production_date']=='1910-01-01 22:59:52' || $searchUser[$i]['lab_production_date']=='0000-00-00 00:00:00') { 
											} 
											else { 

												echo $searchUser[$i]['lab_production_date']; 

											} 

										?>
										
									</span>	
								 
								</td>
								<td nowrap class="cell100 small text-center column6 

									<?php

										if($lab_status == 'y'  && $receive!='r') {

											echo 'bg-success';

										} 
										elseif($searchUser[$i]['product_upgrade']=='special_order'){ 

											echo 'bg-danger'; 

										}
										elseif($receive == 'r') {

											echo 'bg-danger'; 

										}

									?>

								">
								
									<?php 

										if( $lab_status == 'y' && $receive!='r' ) { 

											echo 'Yes'; 

										} 
										elseif($searchUser[$i]['product_upgrade']=='special_order'){ 

											echo "Essilor"; 

										} 
										elseif($receive == 'r') { 

											echo "Returned";

										} 
										else{ 

											echo 'No'; 

										}; 

									?>

									<br>
									<span class="small">

										<?php 

											if($searchUser[$i]['lab_status_date']=='1910-01-01 22:59:52' || $searchUser[$i]['lab_status_date']=='0000-00-00 00:00:00') { 
											} 
											else { 

												echo $searchUser[$i]['lab_status_date']; 

											}  

										?>
										
									</span>
								</td>											
								<td nowrap class="cell100 small column7 text-center 

									<?php 

										if (  $receive == 'y') { 

											echo 'bg-success'; 

										} 
										elseif (  $searchUser[$i]['osstatus'] == 'returned' || $receive == 'r' ) { 

											echo 'bg-danger'; 

										} 

									?>

								">
								
									<?php

										if ( $receive=='r' ) {

											echo "Returned <br><span class='small'>".$searchUser[$i]['received_stat_date']."</span>";

										}
										elseif ( ($receive=='n' && $lab_status == 'y') || ($searchUser[$i]['product_upgrade']=='special_order' && $receive!='y')  ) { 

									?>

									<input class="sr-only checkbox-original-style" id="checkbox_<?php echo $searchUser[$i]["id"] ?>" type="checkbox" name="receive[<?php echo $searchUser[$i]["id"] ?>]" value="y">
									<label class="checkbox-custom-style" for="checkbox_<?php echo $searchUser[$i]["id"] ?>"></label>

									<?php 

										}  
										elseif($lab_status == 'n' && $production=='y' && $receive != 'y' ||  $lab_status == 'n' && $production=='n' && $receive != 'y' ){
										
											echo 'Processing';

										} 
										else{

											echo "Yes"; 

									?>

									<br>
									<span class="small"><?php  if($searchUser[$i]['received_stat_date']=='1910-01-01 22:59:52' || $searchUser[$i]['received_stat_date']=='0000-00-00 00:00:00') { } else { echo $searchUser[$i]['received_stat_date']; } ?></span>

									<?php 

										} 

									?> 
			
								</td>
								<td style="border-right: 2px solid #000000;" nowrap class="cell100 small column8 text-center 

								<?php 

									if ( $dispatch == 'y' && $searchUser[$i]['osstatus'] != 'returned' ) { 

										echo 'bg-success'; 

									} 
									elseif ( $dispatch == 'y' && $searchUser[$i]['osstatus'] == 'returned' || $receive == 'r') { 

										echo 'bg-danger'; 

									} 

								?>">

									<?php 

										if($receive=='r'){

											echo "Returned";

										}
										elseif ( $dispatch == 'n' || $receive=='r' ) {

									?>

									<button <?= ($receive== 'n'  ) ? 'disabled="disabled' : ''; ?> type="button" class="btn btn-secondary create-signature center-block" id="create-signature_<?php echo $searchUser[$i]['id']; ?>" >dispatch</button>

									<?php 

										} 
										elseif( $dispatch == 'y' && $searchUser[$i]['osstatus'] == 'returned' || $receive == 'r'  ) { 

									?>

										Returned

									<?php 

										} else{ 

									?>

										Dispatched

									<?php 

										} 

									?>

								</td>
								<td>
									<button type="button" class="btn btn-warning btn-remake-order center-block" id="remake_<?php echo $searchUser[$i]['id']; ?>" data-customer-id="<?= $searchUser[$i]['id']; ?>" data-order-id="<?= $searchUser[$i]['order_id']; ?>" data-po-number="<?= $searchUser[$i]['po_number']; ?>" data-customer-name="<?= ucwords( $searchUser[$i]['first_name'] )." ".ucwords( $searchUser[$i]['last_name'] ); ?>" style="color: #ffffff;">remake</button>
								</td>
								<td style="border-right: 2px solid #000000;">
									<button type="button" class="btn btn-danger btn-cancel-order-search center-block" data-customer-id="<?= $searchUser[$i]['id']; ?>" data-order-id="<?= $searchUser[$i]['order_id']; ?>" data-po-number="<?= $searchUser[$i]['po_number']; ?>" data-customer-name="<?= ucwords( $searchUser[$i]['first_name'] )." ".ucwords( $searchUser[$i]['last_name'] ); ?>" data-toggle="modal" data-target="#cancelOrderSearch" style="color: #ffffff;">cancel</button>
								</td>
								<div id="overlay_<?php echo $searchUser[$i]['id']; ?>" class="overlay"></div>
							</tr>

							<?php

									};

								};

							?>

						</tbody>
						
					</table>

				</div>
			</div>

		</div>
	</div>

</form>

<div class="modal fade" id="informationRemarks">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- load anything here -->
		</div>
	</div>
</div>
<div class="modal fade" id="cancelOrderSearch">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">							
				<h4 class="modal-title">Are you sure you want to cancel order <b><span id="POnum"></span></b>?</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>You are about to cancel order <b><span id="POnum"></span></b> for customer <b><span id="POName"></span></b>.</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="cancelThisOrderSearch" class="btn btn-danger" data-customer-id="" data-order-id="" data-po-number="" data-customer-name="" style="color: #ffffff;">Cancel Order</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="form-remarks2 text-left"></div>

<?php 

};

?>

<script>

	function activateSignature() {

		$('.create-signature').click(function() {

			var id = $(this).attr("id").replace("create-signature_","");
			
			$("#overlay_"+id).load("./modules/dispatch/dispatch_detail.php?&cn=" + id);
			
	    	$('#overlay_'+ id).fadeIn();
			
	    });
		
	    $('.close-signature').click(function(e) {

	    	e.preventDefault();

			var id = $(this).attr("id").replace("close-signature_","");

	    	$('#overlay_'+ id).fadeOut();

	    });

	};

	activateSignature();

	$("#btnsubmit").click(function(){
			
		$('#action_type_lab').val( $(this).data('value') );
		$('.remarks-overlay').fadeIn();

		$.post("../process/dispatch/confirmation.php",$("form#lab_form2").serialize(),function(d){

			$('.form-remarks2').html(d);

			$('.close-confirmation').click(function() {
				window.location.reload(true);
			});

		});

	});
	
	
	$("#btnsubmit2").click(function(){

		$('#action_type_lab').val( $(this).data('value') );
		$('.remarks-overlay').fadeIn();
		
		$.post("../process/dispatch/confirmation.php",$("form#lab_form2").serialize(),function(d){

			$('.form-remarks2').html(d);

			$('.close-confirmation').click(function() {
				window.location.reload(true);
			});

		});

	});

	$('.iremarks').click(function(){
		
		var id = $(this).attr("id").replace("iremarks_","");

		$('#informationRemarks .modal-content').load("../process/dispatch/remarks_chat.php?id="+ id);
		
	});

	 // ======================================= REMAKE

	function remakeOrder() {

		$('.btn-remake-order').click(function() {

			var id = $(this).attr("id").replace("remake_","");
			
			$("#overlay_"+id).load("../process/dispatch/dispatch_detail_remake.php?&cn=" + id);
			
	    	$('#overlay_'+ id).fadeIn();
			
	    });
		
	    $('btn-remake-order').click(function(e) {

	    	e.preventDefault();

			var id = $(this).attr("id").replace("close-signature_","");

	    	$('#overlay_'+ id).fadeOut();

	    });

	};

    remakeOrder();

	// ===================================== CANCEL

	$('.btn-cancel-order-search').click(function() {

		var thisCustomerID = $(this).data('customer-id');
		var thisOrderID = $(this).data('order-id');
		var thisPONumber = $(this).data('po-number');
		var thisCustomerName = $(this).data('customer-name');

		$('#cancelOrderSearch #POnum').html(thisPONumber);
		$('#cancelOrderSearch #POName').html(thisCustomerName);

		$('#cancelThisOrderSearch').attr('data-customer-id', thisCustomerID);
		$('#cancelThisOrderSearch').attr('data-order-id', thisOrderID);
		$('#cancelThisOrderSearch').attr('data-po-number', thisPONumber);
		$('#cancelThisOrderSearch').attr('data-customer-name', thisCustomerName);

	});

	$('#cancelThisOrderSearch').click(function() {

		var thisCustomerID = $(this).data('customer-id');
		var thisOrderID = $(this).data('order-id');
		var thisPONumber = $(this).data('po-number');
		var thisCustomerName = $(this).data('customer-name');

		$.ajax({

			type: "POST",
		    url: "../process/dispatch/cancel.php",
		    data: {order_id: thisOrderID, po_number: thisPONumber, customer_id: thisCustomerID},
		    success: function(data) {

		    	alert('success!');
		    	window.location.reload(true);

		    }

		});

	});

</script>
