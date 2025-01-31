 
<?php
 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
// Required includes
require $sDocRoot."/includes/connect.php";

include("../includes/search_data.php"); ?>

<div class="table100 ver1">
	<div class="wrap-table100-nextcols js-pscroll">
			<div class="table100-nextcols">
				<table>
					<thead>
						<tr class="row100 head">
							<th class="cell100 text-center">NAME</th>
							<th class="cell100 text-center">PO #</th>
							<th class="cell100 text-center">Branch</th>
							<th class="cell100 text-center">Paid</th>
							<th class="cell100 text-center">Processed <br> <font size="-3">(laboratory)</font></th>
							<th class="cell100 text-center">Produced<br> <font size="-3">(laboratory)</font></th>
							<th class="cell100 text-center">Completed<br> <font size="-3">(laboratory)</font></th>
							<th class="cell100 text-center">Received<br> <font size="-3">(store)</font></th>
							<th class="cell100 text-center">Dispatched<br> <font size="-3">(store)</font></th>
						</tr>
					</thead>
					<tbody>

						<?php for ( $i = 0; $i < sizeof($arrCustomerSearch); $i++ ) : ?>
						<input type="hidden" name="off[]" value="<?php echo $arrCustomerSearch[$i]["id"] ?>" id="off">
						<input type="hidden" name="order_id[<?php echo $arrCustomerSearch[$i]["id"] ?>]" value="<?php echo $arrCustomerSearch[$i]["order_id"] ?>" id="off">
						<input type="hidden" name="name[<?php echo $arrCustomerSearch[$i]["id"] ?>]" value="<?= ucwords( $arrCustomerSearch[$i]['first_name'] )." ".ucwords( $arrCustomerSearch[$i]['last_name'] ); ?>" id="off">
							<?php 

								$status 	= $arrCustomerSearch[$i]['payment'];
								$print  	= $arrCustomerSearch[$i]['lab_print'];
								$production = $arrCustomerSearch[$i]['lab_production'];
								$lab_status = $arrCustomerSearch[$i]['lab_status'];
								$receive 	= $arrCustomerSearch[$i]['received_stat'];
								$dispatch 	= $arrCustomerSearch[$i]['store_dispatch'];

							?>

							<tr class="row100 body">
								<td nowrap class="cell100 column1">
									<div class="table-wrapper row no-gutters align-items-center justify-content-start">
										<?= $i + 1 ?>.&nbsp;<a href="customer/?profile_id=<?= $arrCustomerSearch[$i]["profile_id"]?>&orderNo=<?= $arrCustomerSearch[$i]["orders_specs_id"]?>" ><?= ucwords( $arrCustomerSearch[$i]['first_name'] )." ".ucwords( $arrCustomerSearch[$i]['last_name'] ); ?></a>
								
										<a href="#" id="iremarks_<?php echo $arrCustomerSearch[$i]['orders_specs_id']; ?>" class="iremarks text-success" data-toggle="modal" data-target="#informationRemarks" data-id="<?php echo $arrCustomerSearch[$i]['orders_specs_id']; ?>"><i class="zmdi zmdi-info"></i></a>
									</div>
								</td>
								<?php 
							
									$order_id_new=$arrCustomerSearch[$i]['po_number'];
									
								?>
								
								
								<td nowrap class="cell100 text-center">
									<?php echo $order_id_new; ?> <Br> <font size="-2"><?= ucwords($arrCustomerSearch[$i]["dispatch_type"]) ?></font>
									
									
									
									</td>
								<td nowrap class="cell100 column2 text-center"><?= ucwords($arrCustomerSearch[$i]['branch']) ?></td>
								<td nowrap class="cell100 column3 text-center <?= ( $status == 'y' ) ? 'bg-success' : 'bg-danger'; ?>" ><?= ( $status == 'y' ) ? 'Yes' : 'No'; ?><Br>
									<?php echo "<font size='-5'>".$arrCustomerSearch[$i]['payment_date']."</font>"; ?></td>
									
									<!----   Print  --->
								<td nowrap class="cell100 column4 text-center <?php if( $print == 'y' && $receive!='r' ) { echo  'bg-success'; } elseif($receive=='r'){ echo  'bg-warning'; }  ?>"> 
									
									<?php if ( $print == 'n' ) {  ?>
									<a href="../process/print.php?profile_id=<?= $arrCustomerSearch[$i]["profile_id"]?>&orderNo=<?= $arrCustomerSearch[$i]["orders_specs_id"]?>" >
											<button <?= ( $status != 'y' ) ? 'disabled="disabled' : ''; ?> type="button" class="btn btn-secondary center-block" >Print</button>
										</a>
									<?php } elseif($receive=='r'){
																echo "Rejected";

													}
									
									else { ?>
									<a class="text-white" href="../process/print.php?profile_id=<?= $arrCustomerSearch[$i]["profile_id"]?>&orderNo=<?= $arrCustomerSearch[$i]["orders_specs_id"]?>" >Reprint</a>
									<?php } ?>
								</td>
								<!----   production  --->
								<td nowrap class="cell100 column5 text-center 
												  <?php
												  if($receive=='r'){ echo 'bg-warning'; }else{
												  if( $production == 'y' && $lab_status=='y' ) { echo 'bg-success'; } 
												  elseif( $production == 'y' && $lab_status=='n' ){ echo 'bg-warning'; } 
												  elseif( $production == 'n' && $print=='n' ){   }
												  }?>">

									<?php 
									if($receive=='r'){  echo "Rejected"; }
									elseif ( $production == 'n' && $print=='y' ) {?>
										<input class="sr-only checkbox-original-style" id="production_checkbox_<?php echo $arrCustomerSearch[$i]["id"] ?>" type="checkbox" name="production[<?php echo $arrCustomerSearch[$i]["id"] ?>]" value="y">
										<label class="checkbox-custom-style" for="production_checkbox_<?php echo $arrCustomerSearch[$i]["id"] ?>"></label>
									<?php } elseif ( $production == 'n' && $print=='n' ) {  ?> 
										No
									<?php  } elseif( $production == 'y' && $lab_status=='n') {  ?>
										On Going<br />
										<span class="small"><?php echo $arrCustomerSearch[$i]['lab_production_date']; ?></span>

									<?php }  elseif($lab_status=='y') { ?>
										Done<br />
										<span class="small"><?php echo $arrCustomerSearch[$i]['lab_production_date']; ?></span>
									<?php } ?>
								</td>
								
								<!----   complete  --->
								<td nowrap class="cell100 column6 text-center <?php if( $lab_status == 'y' && $receive!='r' ) { echo 'bg-success'; } elseif($receive=='r'){ echo 'bg-warning'; }  ?>">
									
									
									<?php
									if($receive=='r'){  echo "Rejected"; } else{
									if ( $lab_status == 'n' && $production=='y') : ?>
										<input class="sr-only checkbox-original-style" id="complete_checkbox_<?php echo $arrCustomerSearch[$i]["id"] ?>" type="checkbox" name="complete[<?php echo $arrCustomerSearch[$i]["id"] ?>]" value="y">
										<label class="checkbox-custom-style" for="complete_checkbox_<?php echo $arrCustomerSearch[$i]["id"] ?>"></label>
									<?php elseif ($lab_status == 'n' && $production=='n') : ?>
										No
									<?php elseif ($lab_status == 'y' && $production=='y') : ?>
										Completed<br />
										<span class="small"><?php echo $arrCustomerSearch[$i]['lab_status_date']; ?></span>
									<?php endif;
									} ?>
								</td>
								
								
								<td nowrap class="cell100 column7 text-center <?php  if( $receive == 'y' ) { echo  'bg-success'; } elseif($receive=='r'){ echo "bg-warning"; } else{  } ?>">
									<?php if( $receive == 'n' ) { echo "No"; }  elseif($receive =='r'){ echo 'Rejected'; }  else{ echo 'Received'; } ?>
								</td>
								<td nowrap class="cell100 column8 text-center <?php if( $dispatch == 'y' ) { echo 'bg-success'; }elseif($receive=='r'){ echo "bg-warning"; }  else{  } ?>">
									<?= ( $dispatch == 'n' ) ? 'No' : 'Dispatched'; ?>
								</td>
							</tr>

						<?php endfor; ?>

					</tbody>
				</table>
			</div>
		</div>
</div>


	<div class="modal fade" id="informationRemarks">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- load anything here -->
		</div>
	</div>
</div>
<Script>
	
	$('.iremarks').click(function(){
		//var id =$('.iremarks').attr('data-id');
		var id = $(this).attr("id").replace("iremarks_","");
		$('#informationRemarks .modal-content').load("remarks_chat.php?id="+ id, function() {
			scrollChat( 500 );
		});
	});

</Script>
