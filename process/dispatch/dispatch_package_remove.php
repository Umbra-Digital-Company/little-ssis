<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";

// Required includes
if(isset($_GET['po_number'])){
	require $sDocRoot."/includes/grab_packaging_remove.php";
}
	function cvdate3($d){
	      $returner = '';
	      $datae=date_parse($d);
	      $returner .= getMonth3($datae['month'])." ".$datae['day'].", ".$datae['year'];
	      $suffix = "AM";
	      $hour = $datae['hour'];
	      if ($datae['hour']>'12') {
	        $hour = $datae['hour']-12;
	      }
	      if ($datae['hour']>'11' && $datae['hour']<'24') {
	        $suffix = "PM";
	      }
	      $returner .= " ".AddZero3($hour).":".AddZero3($datae['minute']).":".AddZero3($datae['second'])." ".$suffix;
	      return $returner;
	  }

	    
    function getMonth3($mid){
      switch($mid){
        case '1': return "Jan"; break;
        case '2': return "Feb"; break;
        case '3': return "Mar"; break;
        case '4': return "Apr"; break;
        case '5': return "May"; break;
        case '6': return "Jun"; break;
        case '7': return "Jul"; break;
        case '8': return "Aug"; break;
        case '9': return "Sep"; break;
        case '10': return "Oct"; break;
        case '11': return "Nov"; break;
        case '12': return "Dec"; break;
    
      }
    }
    
    function AddZero3($num){
      if (strlen($num)=='1') {
        return "0".$num;
      } else {
        return $num;
      }
    }
?>

<style>
	
	.canvas-holder-package {
		width: 100%;
		max-width: 80%;
		background: #fff;
		position: absolute;
		top: 50%;
		left: 50%;
		border-radius: 14px;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-o-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);	
	}

	.tableFixHead {height:auto; max-height:600px; overflow-y:auto;}
	.tableFixHead thead th{position: sticky; }
	.td_data{
		padding-top: 20px !important;
	}
</style>

<form class="package-remove-form" action="../process/dispatch/packaging_remove.php" method="POST" name="form-remove">
	<div class="canvas-holder-package text-center">
				<span style="float: right; margin-right: 10px;">
					<button type="button" class="btn ssis-btn-secondary close-signature" id="close-package">close</button>
				</span>
				<div class="d-flex justify-content-left" style="padding-left: 23px; padding-right: 23px; padding-top: 20px;">

					<div class="col-3 form-group text-left" style=" padding-right: 0px;">
						<label>Search PO Number</label>
						<input type="text" id="po-number-value" class="form-control" value="<?= (isset($_GET['po_number'])) ? $_GET['po_number'] : '' ?>">
					</div>
					<input type="button" id="search-po-number" class="btn btn-primary" style="margin-top: 1.85rem!important; background-color: #8e8e8e;" value="Search">
				</div>
		<?php if(isset($_GET['po_number'])){ ?>
		<div class="alert alert-warning">
		  	<strong>Note: upon clicking "remove packaging" all of the packages attached to this PO will be removed</strong>
		</div>
		<div style="padding-left: 23px; padding-right: 23px;">
				<div class="table-responsive tableFixHead text-left">

					<table class="table table-hover">
						<thead>
							<tr class="row100 head">
								<th class="cell100 small">Product Description</th>
								<th class="cell100 small">Product Code</th>
								<th class="cell100 small column3" nowrap>PO Number</th>
								<th class="cell100 small column3" nowrap>Packaging For</th>
								<th class="cell100 small column2">Packaging Date</th>
							</tr>
						</thead>
						<tbody>
							<input type="hidden" name="po_number" value="<?= $_GET['po_number'] ?>">
							<?php for ( $i = 0; $i < sizeof($arrCustomer); $i++ ) { ?> 
								<tr class="row100 body">
									<input type="hidden" name="orders_specs_id[]" value="<?= $arrCustomer[$i]['orders_specs_id']  ?>">
									<td nowrap class="cell100 small text-left"> <?= (trim($arrCustomer[$i]['description']) != '') ? $arrCustomer[$i]['description'] : $arrCustomer[$i]['description_studios'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['product_upgrade'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['po_number'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['packaging_for'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=cvdate3($arrCustomer[$i]['packaging_date']) ?></td>
								</tr>
							<?php } ?>

						</tbody>
					</table>
				</div>
		</div>
	
		<?php } ?>
		<div style="padding-top: 10px;padding-bottom: 10px;">
			<?php if(count($arrCustomer) > 0){ ?>
				<button type="submit" class="btn btn-info" id="remove-package">Remove Packaging</button>
			<?php } ?>
		</div>
	</div>

</form>

