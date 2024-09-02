<?php 
	
 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
//session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
//	session_start();

// Required includes
require $sDocRoot."/includes/connect.php";

include("../includes/grab_order_details_shopify.php");

 $queryUpdate="UPDATE orders_specs SET
				lab_print='y',
				lab_print_date=now(),
				synched='n'
			where orders_specs_id='".$_GET['orderNo']."'";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryUpdate)) {
	mysqli_stmt_execute($stmt);		
};	
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

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1.0, minimum-scale=1.0, maximum-scale=1.0">
	<title>Order Details</title>
 <script src="downloadpdf.js"></script>
	<link rel="stylesheet" href="../css/style.css">

	<style>

		*, *:after, *:before {
			margin: 0;
			padding: 0;
		}

		body {
			font-family: 'Poppins';
			font-size: 14px;
			letter-spacing: .5px;
		}

		.print-page {
			width: 850px;
			margin: 20px auto;
		}

		.print-page h1 {
			text-align: center;
		}

		table {
			width: 100%;
			margin: 20px auto;
			border-collapse: collapse;
			border-color: #2d2d2d;
		}

		table tr td,
		table tr th {
			border-color: #2d2d2d;
			white-space: nowrap;
		}

		table tr th {
			font-family: 'Poppins-Medium';
		}

		table tr td strong {
			font-family: 'Poppins-Medium';
			margin-right: 15px;
		}

		table tr td > table {
			margin: 0;
		}

		table thead tr th {
			padding: 10px 15px;
		}

		table tbody tr td > table tr th,
		table tbody tr td > table tr td {
			padding: 5px 15px;
		}

		table tr.no-padding > td {
			padding: 0;
		}

		table tbody tr.table-first-row td > table.first-row-first-table tr:not(:last-of-type) td,
		table tbody tr.table-first-row td > table.first-row-second-table tr:first-of-type td {
			border-bottom: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription th,
		table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription-a th,
		table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription-a td {
			border-bottom: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription th {
			border-bottom: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription-a th {
			border-right: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription th:not(:first-of-type),
		table tbody tr.table-second-row td > table tr td:not(:first-of-type) {
			border-left: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-first-table tr td table.second-row-first-table-first-table tr:first-of-type th {
			width: 33%;
		}

		table tbody tr.table-second-row td > table.second-row-first-table tr td table.second-row-first-table-first-table tr.table-tint th {
			border-top: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-second-table tr th,
		table tbody tr.table-second-row td > table.second-row-second-table tr td {
			border-bottom: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-second-table tr:not(:first-of-type) th {
			border-right: 1px solid #2d2d2d;
			width: 25%;
		}

		table tbody tr.table-second-row td > table.second-row-second-table tr:last-of-type th,
		table tbody tr.table-second-row td > table.second-row-second-table tr:last-of-type td {
			border: 0;
		}

		table tbody tr.table-second-row td > table.second-row-second-table tr:last-of-type td {
			border-left: 1px solid #2d2d2d;
		}

		table tbody tr.table-second-row td > table.second-row-second-table tr.total-row th,
		table tbody tr.table-second-row td > table.second-row-second-table tr.total-row td {
			border-bottom: 0;
			padding: 20px 0 19px;
		}

		@media print {

			@page {
				margin: 0 20px;
			}
			
			*, *:after, *:before {
				margin: 0;
				padding: 0;
			}

			body {
				font-family: 'Poppins';
				font-size: 14px;
				letter-spacing: .5px;
			}

			.print-page {
				width: 850px;
				margin: 20px auto;
			}

			.print-page h1 {
				text-align: center;
			}

			table {
				width: 100%;
				margin: 20px auto;
				border-collapse: collapse;
				border-color: #2d2d2d;
			}

			table tr td,
			table tr th {
				border-color: #2d2d2d;
				white-space: nowrap;
			}

			table tr th {
				font-family: 'Poppins-Medium';
			}

			table tr td strong {
				font-family: 'Poppins-Medium';
				margin-right: 15px;
			}

			table tr td > table {
				margin: 0;
			}

			table thead tr th {
				padding: 10px 15px;
			}

			table tbody tr td > table tr th,
			table tbody tr td > table tr td {
				padding: 5px 15px;
			}

			table tr.no-padding > td {
				padding: 0;
			}

			table tbody tr.table-first-row td > table.first-row-first-table tr:not(:last-of-type) td,
			table tbody tr.table-first-row td > table.first-row-second-table tr:first-of-type td {
				border-bottom: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription th,
			table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription-a th,
			table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription-a td {
				border-bottom: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription th {
				border-bottom: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription-a th {
				border-right: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-first-table tr.table-prescription th:not(:first-of-type),
			table tbody tr.table-second-row td > table tr td:not(:first-of-type) {
				border-left: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-first-table tr td table.second-row-first-table-first-table tr:first-of-type th {
				width: 33%;
			}

			table tbody tr.table-second-row td > table.second-row-first-table tr td table.second-row-first-table-first-table tr.table-tint th {
				border-top: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-second-table tr th,
			table tbody tr.table-second-row td > table.second-row-second-table tr td {
				border-bottom: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-second-table tr:not(:first-of-type) th {
				border-right: 1px solid #2d2d2d;
				width: 25%;
			}

			table tbody tr.table-second-row td > table.second-row-second-table tr:last-of-type th,
			table tbody tr.table-second-row td > table.second-row-second-table tr:last-of-type td {
				border: 0;
			}

			table tbody tr.table-second-row td > table.second-row-second-table tr:last-of-type td {
				border-left: 1px solid #2d2d2d;
			}

			table tbody tr.table-second-row td > table.second-row-second-table tr.total-row th,
			table tbody tr.table-second-row td > table.second-row-second-table tr.total-row td {
				border-bottom: 0;
				padding: 20px 0 19px;
			}
		}

	</style>

</head>

<body>
<div>
			<a href="/list/" class="active">
				<span><i class="zmdi zmdi-view-list"></i></span>
				Back
			</a>
OR 
<button  onclick="javascript:printDiv('printme')" >Print</button>
	</div>
	
	<button class="text-white textFile" data-profile-id="<?= $_GET['profile_id']?>" data-order-no="<?= $_GET['orderNo']?>" >Download textFile</button>
	
	
<div id="printme"  >
	<div class="print-page">
	<?php if($arrCustomerDetail[0]["store_id"]!='142'){ ?>
		<h1>CEI OPTICAL LABORATORY</h1>
	<?php } ?>
		<table border="1" cellpadding="0" cellspacing="0" class="main-table">
			<thead class="table-header">
				<tr>
					<th colspan="5">RX ORDER FORM</th>
					<th colspan="2" align="left">NO.</th>
				</tr>
			</thead>
			<tbody class="table-body">
				<tr class="table-first-row">
					<td colspan="5">
						<table class="first-row-first-table" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><strong>CUSTOMER:</strong> <?php echo ucwords($arrCustomerDetail[0]["first_name"] . ' ' . $arrCustomerDetail[0]["last_name"]); ?></td>
							</tr>
							<tr>
								<td align="left"><strong>OPTOMETRIST:</strong> <?php echo ucwords(strtolower($arrCustomerPrescription[0]["doc_first_name"]) . ' ' . strtolower($arrCustomerPrescription[0]["doc_last_name"])); ?></td>
							</tr>
							<tr>
								<td align="left"><strong>ADDRESS:</strong> <?php echo $arrCustomerDetail[0]["branch"] ?></td>
							</tr>
							<tr>
								<td align="left"><strong>PO #:</strong> <?php echo $arrCustomerDetail[0]["po_number"] ?></td>
							</tr>
						</table>
					</td>
					<td colspan="2" valign="top">
						<table class="first-row-second-table" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><strong>DATE:</strong> <?php echo cvdate($arrCustomerDetail[0]["order_date"]);?></td>
							</tr>
							<tr>
								<td align="left"><strong>TRAY NO.:</strong></td>
							</tr>
							<tr>
								<td align="left">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="table-second-row">
					<td colspan="5" valign="top">
						<table class="second-row-first-table" cellpadding="0" cellspacing="0">
							<tr class="table-prescription">
								<th align="center"></th>
								<th align="center">SPH</th>
								<th align="center">CYL</th>
								<th align="center">AXIS</th>
								<th align="center">ADD</th>
								<th align="center">IPD</th>
								<th align="center">PH</th>
								<th align="center">VA / VH</th>
							</tr>
							<tr class="table-prescription-a">
								<th align="center">R</th>
								<td align="center"><?php if($arrCustomerPrescription[0]['sph_od']=='0'){ echo "plano"; } 
									else{
										if($arrCustomerPrescription[0]['sph_od']>0){ $signSphOD="+"; }else{ $signSphOD="";}
										echo $signSphOD.number_format($arrCustomerPrescription[0]['sph_od'], 2, '.', ''); 
									} ?></td>
								<td align="center"><?php echo number_format($arrCustomerPrescription[0]['cyl_od'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['axis_od']; ?></td>
								<td align="center"><?php $addSignOD="";
									if($arrCustomerPrescription[0]['add_od']>0){ $addSignOD="+";}else{ $addSignOD=""; }
									echo $addSignOD.number_format($arrCustomerPrescription[0]['add_od'], 2, '.', ''); 
									?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ipd_od']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ph_od']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['va_od']; ?></td>
							</tr>
							<tr class="table-prescription-a">
								<th align="center">L</th>
								<td align="center"><?php if($arrCustomerPrescription[0]['sph_os']=='0'){ echo "plano"; }else{
											$signSphOS="";
									if($arrCustomerPrescription[0]['sph_os']>0){ $signSphOS="+"; }else{  $signSphOS=""; } 
										echo $signSphOS.number_format($arrCustomerPrescription[0]['sph_os'], 2, '.', ''); 

									}?></td>
								<td align="center"><?php echo number_format($arrCustomerPrescription[0]['cyl_os'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['axis_os']; ?></td>
								<td align="center"><?php 
									$signAddOs="";
									if($arrCustomerPrescription[0]['add_os']>0){  $signAddOs="+"; }else{  $signAddOS =""; }
									
									echo $signAddOs.number_format($arrCustomerPrescription[0]['add_os'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ipd_os']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ph_os']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['va_os']; ?></td>
							</tr>
							<tr class="no-padding">
								<td valign="top" colspan="6">
									<table class="second-row-first-table-first-table" cellpadding="0" cellspacing="0">
										<tr>
											<th align="left">LENS</th>
											
										</tr>
										<tr>
											<td colspan="6"><?php echo ucwords(str_replace("_"," ", $arrCustomerDetail[0]["pol_item"] ));?></td>
										</tr>
										<tr>
											<td colspan="6">&nbsp;</td>
										</tr>
										<tr class="table-tint">
											<th align="left" colspan="6">TINT</th>
										</tr>
										<tr>
											<td colspan="6"><?php echo ucwords(str_replace("-"," ",$arrCustomerDetail[0]["tints"]));?></td>
										</tr>
										<tr>
											<td colspan="6">&nbsp;</td>
										</tr>
									</table>
								</td>
								<td valign="top" colspan="2">
									<table class="second-row-first-table-second-table" cellpadding="0" cellspacing="0">
										<tr>
											<th align="center">FRAME</th>
										</tr>
										<tr>
											<td align="center"><?= ucwords( $arrCustomerDetail[0]['item_name_poll'] ); ?><br>
											(<?=  $arrCustomerDetail[0]['product_code']?>) </td>
										</tr>
										<tr>
											<td align="center"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										
										<tr>
											<th align="center">DBC/LFV</th>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td colspan="2" valign="top">
						<table class="second-row-second-table" cellpadding="0" cellspacing="0">
							<tr class="charges-row">
								<th colspan="2">CHARGES</th>
							</tr>
							<tr>
								<th align="center">RL</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">LL</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">UV</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">TINT</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">EDGE</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">Others</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr class="total-row">
								<th align="center">TOTAL</th>
								<td align="left">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="table-third-row">
					<td colspan="5">
						<table class="third-row-first-table" cellspacing="0" cellpadding="0">
							<tr>
								<th align="left">REMARKS:</th>
							</tr>
							<tr>
								<td><?= $arrCustomerDetail[0]['remarks'] ?> <?= $arrCustomerDetail[0]['prescription_remarks'] ?></td>
							</tr>
						</table>
					</td>
					<td colspan="2">
						<table class="third-row-second-table" cellspacing="0" cellpadding="0">
							<tr>
								<th align="left">RECEIVED BY:</th>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<br>
		----------------------------------------------------------------------------------------------------
		<h1>SUNNIES SPECS OPTICAL</h1>

		<table border="1" cellpadding="0" cellspacing="0" class="main-table">
			<thead class="table-header">
				<tr>
					<th colspan="5">RX ORDER FORM</th>
					<th colspan="2" align="left">NO.</th>
				</tr>
			</thead>
			<tbody class="table-body">
				<tr class="table-first-row">
					<td colspan="5">
						<table class="first-row-first-table" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><strong>CUSTOMER:</strong> <?php echo ucwords($arrCustomerDetail[0]["first_name"] . ' ' . $arrCustomerDetail[0]["last_name"]); ?></td>
							</tr>
							<tr>
								<td align="left"><strong>OPTOMETRIST:</strong> <?php echo ucwords(strtolower($arrCustomerPrescription[0]["doc_first_name"]) . ' ' . strtolower($arrCustomerPrescription[0]["doc_last_name"])); ?></td>
							</tr>
							<tr>
								<td align="left"><strong>ADDRESS:</strong> <?php echo $arrCustomerDetail[0]["branch"] ?></td>
							</tr>
							<tr>
								<td align="left"><strong>PO #:</strong> <?php echo $arrCustomerDetail[0]["po_number"] ?></td>
							</tr>
						</table>
					</td>
					<td colspan="2" valign="top">
						<table class="first-row-second-table" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><strong>DATE:</strong> <?php echo cvdate($arrCustomerDetail[0]["order_date"]);?></td>
							</tr>
							<tr>
								<td align="left"><strong>TRAY NO.:</strong></td>
							</tr>
							<tr>
								<td align="left">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="table-second-row">
					<td colspan="5" valign="top">
						<table class="second-row-first-table" cellpadding="0" cellspacing="0">
							<tr class="table-prescription">
								<th align="center"></th>
								<th align="center">SPH</th>
								<th align="center">CYL</th>
								<th align="center">AXIS</th>
								<th align="center">ADD</th>
								<th align="center">IPD</th>
								<th align="center">PH</th>
								<th align="center">VA / VH</th>
							</tr>
							<tr class="table-prescription-a">
								<th align="center">R</th>
								<td align="center"><?php if($arrCustomerPrescription[0]['sph_od']=='0'){ echo "plano"; } 
									else{
										if($arrCustomerPrescription[0]['sph_od']>0){ $signSphOD="+"; }else{ $signSphOD="";}
										echo $signSphOD.number_format($arrCustomerPrescription[0]['sph_od'], 2, '.', ''); 
									} ?></td>
								<td align="center"><?php echo number_format($arrCustomerPrescription[0]['cyl_od'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['axis_od']; ?></td>
								<td align="center"><?php $addSignOD="";
									if($arrCustomerPrescription[0]['add_od']>0){ $addSignOD="+";}else{ $addSignOD=""; }
									echo $addSignOD.number_format($arrCustomerPrescription[0]['add_od'], 2, '.', ''); 
									?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ipd_od']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ph_od']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['va_od']; ?></td>
							</tr>
							<tr class="table-prescription-a">
								<th align="center">L</th>
								<td align="center"><?php if($arrCustomerPrescription[0]['sph_os']=='0'){ echo "plano"; }else{
											$signSphOS="";
									if($arrCustomerPrescription[0]['sph_os']>0){ $signSphOS="+"; }else{  $signSphOS=""; } 
										echo $signSphOS.number_format($arrCustomerPrescription[0]['sph_os'], 2, '.', ''); 

									}?></td>
								<td align="center"><?php echo number_format($arrCustomerPrescription[0]['cyl_os'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['axis_os']; ?></td>
								<td align="center"><?php 
									$signAddOs="";
									if($arrCustomerPrescription[0]['add_os']>0){  $signAddOs="+"; }else{  $signAddOS =""; }
									
									echo $signAddOs.number_format($arrCustomerPrescription[0]['add_os'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ipd_os']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ph_os']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['va_os']; ?></td>
							</tr>
							<tr class="no-padding">
								<td valign="top" colspan="6">
									<table class="second-row-first-table-first-table" cellpadding="0" cellspacing="0">
										<tr>
											<th align="left">LENS</th>
											
										</tr>
										<tr>
											<td colspan="6"><?php echo ucwords(str_replace("_"," ", $arrCustomerDetail[0]["pol_item"] ));?></td>
										</tr>
										<tr>
											<td colspan="6">&nbsp;</td>
										</tr>
										<tr class="table-tint">
											<th align="left" colspan="6">TINT</th>
										</tr>
										<tr>
											<td colspan="6"><?php echo ucwords(str_replace("-"," ",$arrCustomerDetail[0]["tints"]));?></td>
										</tr>
										<tr>
											<td colspan="6">&nbsp;</td>
										</tr>
									</table>
								</td>
								<td valign="top" colspan="2">
									<table class="second-row-first-table-second-table" cellpadding="0" cellspacing="0">
										<tr>
											<th align="center">FRAME</th>
										</tr>
										<tr>
											<td align="center"><?= ucwords( $arrCustomerDetail[0]['item_name_poll'] ); ?>
											<br>
											(<?=  $arrCustomerDetail[0]['product_code']?>) </td>
										</tr>
										<tr>
											<td align="center"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										
										<tr>
											<th align="center">DBC/LFV</th>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td colspan="2" valign="top">
						<table class="second-row-second-table" cellpadding="0" cellspacing="0">
							<tr class="charges-row">
								<th colspan="2">CHARGES</th>
							</tr>
							<tr>
								<th align="center">RL</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">LL</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">UV</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">TINT</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">EDGE</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<th align="center">Others</th>
								<td align="left">&nbsp;</td>
							</tr>
							<tr class="total-row">
								<th align="center">TOTAL</th>
								<td align="left">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="table-third-row">
					<td colspan="5">
						<table class="third-row-first-table" cellspacing="0" cellpadding="0">
							<tr>
								<th align="left">REMARKS:</th>
							</tr>
							<tr>
								<td><?= $arrCustomerDetail[0]['remarks'] ?>   <?= $arrCustomerDetail[0]['prescription_remarks'] ?></td>
							</tr>
						</table>
					</td>
					<td colspan="2">
						<table class="third-row-second-table" cellspacing="0" cellpadding="0">
							<tr>
								<th align="left">RECEIVED BY:</th>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
	<!------------ download ------->
<div class="dtxt" style="display: none" id="dtxt">
		<table  style="width: 100%" cellpadding="0" cellspacing="0" class="main-table">
			<thead class="table-header">
				<tr>
					<th colspan="6">RX ORDER FORM</th>
				
				</tr>
			</thead>
			<tbody class="table-body">
				<tr class="table-first-row">
					<td colspan="5">
						<table class="first-row-first-table" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><strong>CUSTOMER:</strong> <?php echo ucwords($arrCustomerDetail[0]["first_name"] . ' ' . $arrCustomerDetail[0]["last_name"]); ?></td>
							</tr>
							<tr>
								<td align="left"><strong>ADDRESS:</strong> <?php echo $arrCustomerDetail[0]["branch"] ?></td>
							</tr>
							<tr>
								<td align="left"><strong>PO #:</strong> <?php echo $arrCustomerDetail[0]["po_number"] ?></td>
							</tr>
							<tr>
							<td align="left"><strong>DATE:</strong> <?php echo cvdate($arrCustomerDetail[0]["order_date"]);?></td>
							</tr>
						</table>
					</td>
					
					
				</tr>
				<tr class="table-second-row">
					<td colspan="5" valign="top">
						<table class="second-row-first-table" cellpadding="0" cellspacing="0">
							<tr class="table-prescription">
								<th align="center"></th>
								<th align="center">SPH</th>
								<th align="center">CYL</th>
								<th align="center">AXIS</th>
								<th align="center">ADD</th>
								<th align="center">IPD</th>
								<th align="center">PH</th>
								<th align="center">VA / VH</th>
							</tr>
							<tr class="table-prescription-a">
								<th align="center">R</th>
								<td align="center"><?php if($arrCustomerPrescription[0]['sph_od']=='0'){ echo "plano"; } 
									else{
										if($arrCustomerPrescription[0]['sph_od']>0){ $signSphOD="+"; }else{ $signSphOD="";}
										echo $signSphOD.number_format($arrCustomerPrescription[0]['sph_od'], 2, '.', ''); 
									} ?></td>
								<td align="center"><?php echo number_format($arrCustomerPrescription[0]['cyl_od'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['axis_od']; ?></td>
								<td align="center"><?php $addSignOD="";
									if($arrCustomerPrescription[0]['add_od']>0){ $addSignOD="+";}else{ $addSignOD=""; }
									echo $addSignOD.number_format($arrCustomerPrescription[0]['add_od'], 2, '.', ''); 
									?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ipd_od']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ph_od']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['va_od']; ?></td>
							</tr>
							<tr class="table-prescription-a">
								<th align="center">L</th>
								<td align="center"><?php if($arrCustomerPrescription[0]['sph_os']=='0'){ echo "plano"; }else{
											$signSphOS="";
									if($arrCustomerPrescription[0]['sph_os']>0){ $signSphOS="+"; }else{  $signSphOS=""; } 
										echo $signSphOS.number_format($arrCustomerPrescription[0]['sph_os'], 2, '.', ''); 

									}?></td>
								<td align="center"><?php echo number_format($arrCustomerPrescription[0]['cyl_os'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['axis_os']; ?></td>
								<td align="center"><?php 
									$signAddOs="";
									if($arrCustomerPrescription[0]['add_os']>0){  $signAddOs="+"; }else{  $signAddOS =""; }
									
									echo $signAddOs.number_format($arrCustomerPrescription[0]['add_os'], 2, '.', ''); ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ipd_os']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['ph_os']; ?></td>
								<td align="center"><?php echo $arrCustomerPrescription[0]['va_os']; ?></td>
							</tr>
							<tr class="no-padding">
								<td valign="top" colspan="6">
									<table class="second-row-first-table-first-table" cellpadding="0" cellspacing="0">
										<tr>
											<th align="left">LENS</th>
											
										</tr>
										<tr>
											<td colspan="6"><?php echo ucwords(str_replace("_"," ", $arrCustomerDetail[0]["pol_item"] ));?></td>
										</tr>
										<tr>
											<td colspan="6">&nbsp;</td>
										</tr>
										<tr class="table-tint">
											<th align="left" colspan="6">TINT</th>
										</tr>
										<tr>
											<td colspan="6"><?php echo ucwords(str_replace("-"," ",$arrCustomerDetail[0]["tints"]));?></td>
										</tr>
										<tr>
											<td colspan="6">&nbsp;</td>
										</tr>
									</table>
								</td>
								<td valign="top" colspan="2">
									<table class="second-row-first-table-second-table" cellpadding="0" cellspacing="0">
										<tr>
											<th align="center">FRAME</th>
										</tr>
										<tr>
											<td align="center"><?= ucwords( $arrCustomerDetail[0]['item_name_poll'] ); ?> ( <?php echo $arrCustomerDetail[0]["product_code"]?> )</td>
										</tr>
										<tr>
											<td align="center"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
										
									</table>
								</td>
							</tr>
						</table>
					</td>
	</tr>
				<tr class="table-third-row">
					<td colspan="5">
						<table class="third-row-first-table" cellspacing="0" cellpadding="0">
							<tr>
								<th align="left">REMARKS:</th>
							</tr>
							<tr>
								<td><?= $arrCustomerDetail[0]['remarks'] ?>  <?= $arrCustomerDetail[0]['prescription_remarks'] ?></td>
							</tr>
						</table>
					</td>
				</tr>
	</table>
	
	</div>

<script src="../js/jquery-3.2.1.min.js"></script>

<script src="../js/ssis_functions.js"></script>
<script src="../js/custom.js"></script>
<script type="text/javascript">

$('.dtext').hide();

	
	 
	 function printDiv(divID) {
		//Get the HTML of div
		var divElements = document.getElementById(divID).innerHTML;
		//Get the HTML of whole page
		var oldPage = document.body.innerHTML;

		//Reset the page's HTML with div's HTML only
		document.body.innerHTML = 
		"<html><head><title></title></head><body><b><br>" + 
		divElements + "</body>";

		//Print Page
		window.print();

		//Restore orignal HTML
		document.body.innerHTML = oldPage;


}
			
	$('.textFile').click(function(){

		var divElements2 = document.getElementById("dtxt").innerHTML;

		download(document.getElementById("dtxt").innerHTML, "<?php echo $arrCustomerDetail[0]["po_number"] ?>.html", "text/html");

	});		
	
	</script>
</body>
</html>