<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	if(!defined('DB_SERVER')){
		require $sDocRoot."/includes/connect.php";
	}

// Required includes
require $sDocRoot."/includes/grab_packaging_count.php";
	function cvdate3($d){
	      $returner = '';
	      $datae=date_parse($d);
	      $returner .= getMonth3($datae['month'])." ".$datae['day'].", ".$datae['year'];
	      // $suffix = "AM";
	      // $hour = $datae['hour'];
	      // if ($datae['hour']>'12') {
	      //   $hour = $datae['hour']-12;
	      // }
	      // if ($datae['hour']>'11' && $datae['hour']<'24') {
	      //   $suffix = "PM";
	      // }
	      // $returner .= " ".AddZero3($hour).":".AddZero3($datae['minute']).":".AddZero3($datae['second'])." ".$suffix;
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

<form class="dispatch-form">
	<div class="canvas-holder-package text-center">
				<div class="d-flex justify-content-between" style="padding-left: 23px; padding-right: 23px; padding-top: 20px;">
					<div class="form-group">
						<label>Package List</label>
					</div>
					<div class="form-group text-left">
						<label>Date Filter</label>
						<input type="date" id="date-now" class="form-control" value="<?= (!isset($_GET['date'])) ? date('Y-m-d',strtotime(date('Y-m-d H:i:s').'+12 hours')) : $_GET['date'] ?>">
					</div> 
					
				</div>
		<div style="padding-left: 23px; padding-right: 23px;">
				<div class="table-responsive tableFixHead text-left">

					<table class="table table-hover">
						<thead>
							<tr class="row100 head">
								<th class="cell100 small">Product Description</th>
								<th class="cell100 small">Product Code</th>
								<th class="cell100 small column3" nowrap>Store Count</th>
								<th class="cell100 small column3" nowrap> Lab Count</th>
								<th class="cell100 small column3" nowrap>Total</th>
								<th class="cell100 small column2">Packaging Date</th>
							</tr>
						</thead>
						<tbody>
							<?php $storeSum = 0; $labSum = 0; $totalSum = 0; ?>
							<?php for ( $i = 0; $i < sizeof($arrCustomer); $i++ ) { ?> 
								<tr class="row100 body">
									<td nowrap class="cell100 small text-left"> <?= (trim($arrCustomer[$i]['description']) != '') ? $arrCustomer[$i]['description'] : $arrCustomer[$i]['description_studios'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['product_upgrade'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['store_count'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['lab_count'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=$arrCustomer[$i]['count_po_number'] ?></td>
									<td nowrap class="cell100 small text-left"> <?=cvdate3($arrCustomer[$i]['packaging_date']) ?></td>
								</tr>
								 <?php
								 	 $storeSum += $arrCustomer[$i]['store_count'];
								 	 $labSum += $arrCustomer[$i]['lab_count'];
								 	 $totalSum += $arrCustomer[$i]['count_po_number'];
								 ?>

							<?php } ?>
							<tr class="row100 body" style="background-color: #ececec; font-size: 14px;">
									<td nowrap class="cell100 small text-left td_data"><strong>Total Summary</strong></td>
									<td nowrap class="cell100 small text-left td_data"> </td>
									<td nowrap class="cell100 small text-left td_data"><strong> <?= $storeSum ?></strong></td>
									<td nowrap class="cell100 small text-left td_data"><strong> <?= $labSum ?></strong></td>
									<td nowrap class="cell100 small text-left td_data"><strong> <?= $totalSum ?></strong></td>
									<td nowrap class="cell100 small text-left td_data"></td>
								</tr>

						</tbody>
					</table>
				</div>
		</div>
	
	
		<button type="button" class="btn ssis-btn-secondary close-signature" id="close-package">close</button>

	</div>

</form>

