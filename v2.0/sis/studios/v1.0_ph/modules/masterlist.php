<?php
include("./modules/includes/grab_masterlist.php");

function cvdate($d){
	$returner = '';
	$datae=date_parse($d); 
	$returner .= getMonth($datae['month'])." ".$datae['day'].", ".$datae['year'];
	$suffix = "AM";
	$hour = $datae['hour'];
	if ($datae['hour']>'12') {
		$hour = $datae['hour']-12;
	}
	if ($datae['hour']>'11' && $datae['hour']<'24') {
		$suffix = "PM";
	}
	$returner .= " ";	
	return $returner;
}

function getMonth($mid){
	switch($mid){
		case '1': return "Jan"; break;
		case '2': return "Feb"; break;
		case '3': return "Mar"; break;
		case '4': return "Apr"; break;
		case '5': return "May"; break;
		case '6': return "Jun"; break;
		case '7': return "Jul"; break;
		case '8': return "Aug"; break;
		case '9': return "Sept"; break;
		case '10': return "Oct"; break;
		case '11': return "Nov"; break;
		case '12': return "Dec"; break;
		
	}
}

function AddZero($num){
	if (strlen($num)=='1') {
		return "0".$num;
	} else {
		return $num;
	}
}


$_SESSION['permalink']= $_GET['page']; 

?>

<style>

	.dispatch-home {
		margin-top: 105px;
	}

	.dispatch-home .search-dispatch-list p {
		margin-bottom: 10px;
	}

	.dispatch-home .search-dispatch-list .btn {
		height: 40px;
	}

	.dispatch-home .sort-arrow {
		margin-left: 5px;
	}

	.checkbox-custom-style {
		width: 20px;
		height: 20px;
		position: relative;
		border: 1px solid #000;
		cursor: pointer;
	}

	.checkbox-original-style:checked ~ .checkbox-custom-style {
		background: #5cb85c;
		border-color: transparent;
	}

	.checkbox-original-style:checked ~ .checkbox-custom-style:after {
		content: '';
		font-family: 'material-design-iconic-font';
		position: absolute;
		color: #fff;
		width: 100%;
		text-align: center;
		font-size: 15px;
		top: -3px;
		left: 0;
		padding: 0 0 0 1px;
	}

	.form-action {
		margin-bottom: 25px;
	}

	.remarks-overlay {
		display: none;
		z-index: 9950;
		height: 100%;
		top: 0;
		left: 0;
		width: 100%;
		position: fixed;
		background: rgba(0,0,0,0.65);
	}

	.js-pscroll {
	  position: relative;
	  overflow: hidden;
	}

	.table100 {
	  width: 100%;
	  position: relative;
	}

	.wrap-table100 {
		border: 1px solid #e6e6e6;
		margin-top: 20px;
	}

	.wrap-table100.scroll {
		overflow-y: auto;
		height: 640px;
	}

	.table100-firstcol {
	  background-color: #fff;
	  position: absolute;
	  z-index: 100;
	  top: 0;
	  left: 0;
	}

	.table100-firstcol table {
	  background-color: #fff;
	  width: 100%;
	}

	.table100-nextcols th {
		border-right: 1px solid #e6e6e6;
	}

	.wrap-table100-nextcols {
	  width: 100%;
	  width: 100%;
	}

	.table100-nextcols table{
	  table-layout: fixed;
	  min-width: 100%;
	}

	.shadow-table100-firstcol {
	  box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
	  -moz-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
	  -webkit-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
	  -o-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
	  -ms-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
	}

	.table100-firstcol table {
	  background-color: transparent;
	  border-right: 1px solid #e6e6e6;
	}

	.table100.ver1 th {
	  font-family: 'Poppins-Medium';
	  text-transform: uppercase;
	  padding: 10px 15px;
	  background: #f5f5f5;
	}

	.table100.ver1 th a {
		color: #000;
		text-decoration: none !important;
	}

	.table100.ver1 td {
	  font-family: 'Poppins-Regular';
	  padding: 10px 15px;
	}

	.table100.ver1 .table100-firstcol td a {
		color: #000;
		border-bottom: 1px solid #808080;
		text-decoration: none !important;
	}

	.table100.ver1 .table100-firstcol td,
	.table100.ver1 .table100-nextcols td {
	  color: #000;
	  height: 60px;
	}

	.table100.ver1 .table100-nextcols td {
		padding: 10px 15px;
		border: 1px solid #e6e6e6;
	}


	.table100.ver1 tr {
	  border-bottom: 1px solid #e6e6e6;
	}

	.table100.ver1 .btn {
		width: 100%;
	}

	.table100.ver1 td.bg-danger,
	.table100.ver1 td.bg-success {
		color: #fff;
	}

	.overlay{ /* we set all of the properties for are overlay */
	    height:100%;
	    width:100%;
	    background:rgba(0,0,0,0.65);
	    padding:20px;
	    position:fixed;
	    top:0;
	    left:0;
	    z-index:1000;
	    display:none;
	}

	.dispatch-form .btn {
		width: auto !important;
		display: inline-block !important;
	}




</style>

<?php

function tabDetails( $label, $arrow ) {
	// get sort link
	$sort_link = '';
	if ( isset($_GET['sort']) && (!isset($_GET['sort2']) )) {

		( $_GET['sort'] == $label ) ? $sort_link = '&sort2='.$label.'2' : '';

	}

	switch ( $arrow ) {

		case 'true' :
			// toggle arrow style
			if ( isset($_GET['sort'] ) || ( isset($_GET['sort2']) ) ) {

				if ($_GET['sort'] == $label && ( !isset($_GET['sort2']) ) ) {
					$arrow_style = '<i class="zmdi zmdi-caret-down-circle text-success"></i>';
				}
				elseif ( $_GET['sort'] == $label && $_GET['sort2'] == $label . '2' ) {
					$arrow_style = '<i class="zmdi zmdi-caret-down-circle text-danger"></i>';
				}
				else {
					$arrow_style = '<i class="zmdi zmdi-caret-down-circle"></i>';
				}
			
			} else {

				$arrow_style = '<i class="zmdi zmdi-caret-down-circle"></i>';

			}
			break;

		case 'false' :
			// toggle arrow style
			$arrow_style = '';
			break;
	}

	echo '<a href="./?page=dispatch&sort='.$label . $sort_link .'">';
	echo 	$label;
	echo 	'<span class="sort-arrow">';
	echo 		$arrow_style;
	echo 	'</span>';
	echo '</a>';
}

?>

<div class="row no-gutters align-items-center justify-content-between page-header">
	<h5>Total Order: <?php   echo sizeof($arrCustomer); ?></h5>
	<a href="./?page=returns" class="btn text-white ssis-btn-primary">request return</a>
</div>                     
   
<div class="dispatch-home">

	<?php if ( sizeOf($arrCustomer) < 1 ) : ?>

		<h4 class="text-danger text-center" style="position: absolute; top: 200px; width: 100%;">
			No Orders to dispatch
		</h4>

	<?php else : ?>	

		<div class="row no-gutters justify-content-between align-items-end">
			<div class="search-dispatch-list">
				<div class="row no-gutters justify-content-between align-items-center">
					<p>SEARCH:</p>
					<a href="./?page=dispatch" class="text-danger small d-none" id="clear_search">CLEAR SEARCH</a>
				</div>
				<input type="text"	name="search" class="search2 form-control col" id="search2" placeholder="Name">
			</div>
			<div>
				<label for="save_action" class="btn btn-success text-white" id="btnsubmit2" data-value="save">SAVE</label>
				<label for="reject_action" class="btn btn-danger text-white" id="btnsubmit" data-value="reject">REJECT</label>
			</div>
		</div>

		<div class="details"></div>
	
		<form method="post" name="lam_form" id="lab_form" >
				
			<div class="form-action text-right">
				<input type="button" class="sr-only" id="save_action"> 
				<input type="button" class="sr-only" id="reject_action">
				<input type="hidden" value="" name="action_type" id="action_type_lab">
			
				<div class="remarks-overlay"></div>
			</div>

			<div class="wrap-table100 non-search js-pscroll">
			<div class="table100 ver1">
				<div class="table100-firstcol">
					<table>
						<thead>
							<tr class="row100 head">
								<th class="cell100 column1"><a href="./?page=dispatch&sort=atoz<?php if(isset($_GET['sort']) && (!isset($_GET['sort2']))){ ?>&sort2=ztoa<?php } ?>">Customers</a></th>
							</tr>
						</thead>
						<tbody>

							<?php for ( $i = 0; $i < sizeof($arrCustomer); $i++ ) : ?>

								<tr class="row100 body">
									<td nowrap class="cell100 column1">
										<?= $i + 1 ?>. <a href="./?page=profileinfo&profile_id=<?= $arrCustomer[$i]['profile_id'] ?>&comp=release" ><?= ucwords( $arrCustomer[$i]['first_name'] )." ".ucwords( $arrCustomer[$i]['last_name'] ); ?></a>
									</td>
								</tr>

							<?php endfor; ?>

						</tbody>
					</table>
				</div>
				
				

					<div class="wrap-table100-nextcols js-pscroll">
						<div class="table100-nextcols">
							<table>
								<thead>
									<tr class="row100 head">
									
										<th class="cell100 column2"><?php tabDetails( 'lab', 'false' ); ?></th>
										
									</tr>
								</thead>
								<tbody>

									<?php 
									
									
									
									for ( $i = 0; $i < sizeof($arrCustomer); $i++ ) : ?>

										<input type="hidden" name="off[]" value="<?php echo $arrCustomer[$i]["id"] ?>">
										<input type="hidden" name="order_id[<?php echo $arrCustomer[$i]["id"] ?>]" value="<?php echo $arrCustomer[$i]["order_id"] ?>">
	<input type="hidden"  name="name[<?php echo $arrCustomer[$i]["id"] ?>]"  value="<?php echo  ucwords( $arrCustomer[$i]['first_name'] )." ".ucwords( $arrCustomer[$i]['last_name'] ); ?>">
										<?php 

											$payment 	= $arrCustomer[$i]['payment'];
											$print  	= $arrCustomer[$i]['lab_print'];
											$production = $arrCustomer[$i]['lab_production'];
											$lab_status = $arrCustomer[$i]['lab_status'];
											$receive = $arrCustomer[$i]['received_stat'];
											$dispatch = $arrCustomer[$i]['store_dispatch'];

										?>

										<tr class="row100 body">
											
											<td nowrap class="cell100 text-center column2"><?= ucwords($arrCustomer[$i]['lab_name']) ?></td>
											
										</tr>

									<?php endfor; ?>

								</tbody>
							</table>
						</div>
					</div>
	
			</div>
			</div>
		</form>
<div class="form-remarks text-left">
				</div>
	<?php endif; ?>
	
</div>

<script>
	
	$(document).ready(function(){

		$("#btnsubmit").click(function(){
			

			$('#action_type_lab').val( $(this).data('value') );
			$('.remarks-overlay').fadeIn();
		
			$.post("./modules/process/confirmation.php",$("form#lab_form").serialize(),function(d){

				$('.form-remarks').html(d);

				$('.close-confirmation').click(function() {
					window.location.reload(true);
				});

			});
		});
		
		
		$("#btnsubmit2").click(function(){

			$('#action_type_lab').val( $(this).data('value') );
			$('.remarks-overlay').fadeIn();
			
			$.post("./modules/process/confirmation.php",$("form#lab_form").serialize(),function(d){

				$('.form-remarks').html(d);

				$('.close-confirmation').click(function() {
					window.location.reload(true);
				});

			});
		});

		function updateW() {
			var px = $('.table100-firstcol').outerWidth(true);
			$('.wrap-table100-nextcols').css('padding-left', px );
		}

		updateW();
		$(window).resize(function() {
			updateW();
		});

		function customScroll() {
			$('.js-pscroll').each(function(){
				var ps = new PerfectScrollbar(this);

				$(window).on('resize', function(){
					ps.update();
				})

				$(this).on('ps-x-reach-start', function(){
					$(this).parent().find('.table100-firstcol').removeClass('shadow-table100-firstcol');
				});

				$(this).on('ps-scroll-x', function(){
					$(this).parent().find('.table100-firstcol').addClass('shadow-table100-firstcol');
				});

			});
		}

		customScroll();

		// ======================================= SIGNATURE

		function activateSignature() {
			$('.create-signature').click(function() {
				var id = $(this).attr("id").replace("create-signature_","");
				
				$("#overlay_"+id).load("/ssis/modules/dispatch/dispatch_detail.php?&cn=" + id);
				
		    	$('#overlay_'+ id).fadeIn();
				
		    });
			
		    $('.close-signature').click(function(e) {
		    	e.preventDefault();
				var id = $(this).attr("id").replace("close-signature_","");
		    	$('#overlay_'+ id).fadeOut();

		    });
		}

	    activateSignature();

		// ===================================== SEARCH

	

		$("#search2").keyup(function(e) {
			if ( e.keyCode  ) {
				s = $("#search2").val().replace(/\s/g, "+");
				$(".details").load("modules/dispatch/dispatch_search.php?s=" + s, function() {

					updateW();
					$(window).resize(function() {
						updateW();
					});

					customScroll();

					activateSignature();

					$("#btnsubmit").click(function(){
						

						$('#action_type_lab').val( $(this).data('value') );
						$('.remarks-overlay').fadeIn();
					
						$.post("./modules/process/confirmation.php",$("form#lab_form").serialize(),function(d){

							$('.form-remarks').html(d);

							$('.close-confirmation').click(function() {
								window.location.reload(true);
							});

						});
					});
					
					
					$("#btnsubmit2").click(function(){

						$('#action_type_lab').val( $(this).data('value') );
						$('.remarks-overlay').fadeIn();
						
						$.post("./modules/process/confirmation.php",$("form#lab_form").serialize(),function(d){

							$('.form-remarks').html(d);

							$('.close-confirmation').click(function() {
								window.location.reload(true);
							});

						});
					});
						
				});
				( s == '' ) ? $('#clear_search').addClass('d-none') : $('#clear_search').removeClass('d-none');
				$('.non-search').hide();
			}
		});
		
		if ( $('.table100-nextcols tbody tr').length > 10 ) {
			$('.wrap-table100').addClass('scroll');
		}

	});

</script>
