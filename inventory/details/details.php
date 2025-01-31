<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/inventory/includes/y_pd_functionv3.php";


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
	$returner .= " at ".AddZero($hour).":".AddZero($datae['minute']).":".AddZero($datae['second'])." ".$suffix."<br>";	
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
		case '9': return "Sep"; break;
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

	$_GET['branch'];
	$_GET['filterStores'];
	$_GET['column_header'];
	$_GET['sku_desc'];
	$_GET['sku_code'];

	// echo"<pre>";
	// print_r($_GET);
	// echo "</pre>";
$GetData=array();

if($_GET['branch']=='store'){
	if($_GET['column_header']=='DAILY SALES'){

	$GetData=	perdayStoreSales($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');

	// echo"<pre>";
	// print_r($GetData);
	// echo "</pre>";

	

	}elseif($_GET['column_header']=='STOCK TRANSFER (+)'){
		// echo "aa";
		$GetData=StockTransferPlus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}elseif($_GET['column_header']=='STOCK TRANSFER (-)'){
		// echo "aa";
		$GetData=StockTransferMinus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}	elseif($_GET['column_header']=='INTER BRANCH (+)'){

		$GetData=InterBranchPlus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}
	elseif($_GET['column_header']=='INTER BRANCH (-)'){

		$GetData=InterBranchMinus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}
	elseif($_GET['column_header']=='PULLOUT'){

		$GetData=pullout($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}elseif($_GET['column_header']=='DAMAGE'){

		$GetData=Damage($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}elseif($_GET['column_header']=='IN TRANSIT(+)'){

		$GetData=inTransitIn($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}
	elseif($_GET['column_header']=='IN TRANSIT(-)'){

		$GetData=inTransitOut($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'store');
	}
	

}elseif($_GET['branch']=='lab'){
	if($_GET['column_header']=='DAILY SALES'){
	$GetData=	perdayStoreSales($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}elseif($_GET['column_header']=='STOCK TRANSFER (+)'){

		$GetData=StockTransferPlus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}elseif($_GET['column_header']=='STOCK TRANSFER (-)'){

		$GetData=StockTransferMinus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}

	elseif($_GET['column_header']=='INTER BRANCH (+)'){

		$GetData=InterBranchPlus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}	elseif($_GET['column_header']=='INTER BRANCH (-)'){

		$GetData=InterBranchMinus($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}elseif($_GET['column_header']=='PULLOUT'){

		$GetData=pullout($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}elseif($_GET['column_header']=='DAMAGE'){

		$GetData=Damage($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}elseif($_GET['column_header']=='IN TRANSIT(+)'){

		$GetData=inTransitIn($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}
	elseif($_GET['column_header']=='IN TRANSIT(-)'){

		$GetData=inTransitOut($_GET['filterStores'],$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'lab');
	}
	
	
}else{
	if($_GET['column_header']=='STOCK TRANSFER (+)'){

		$GetData=StockTransferPlus('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}elseif($_GET['column_header']=='STOCK TRANSFER (-)'){
		
		$GetData=StockTransferMinus('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}elseif($_GET['column_header']=='INTER BRANCH (+)'){

		$GetData=InterBranchPlus('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}
	elseif($_GET['column_header']=='INTER BRANCH (-)'){

		$GetData=InterBranchMinus('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}elseif($_GET['column_header']=='PULLOUT'){

		$GetData=pullout('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}elseif($_GET['column_header']=='DAMAGE'){

		$GetData=Damage('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}elseif($_GET['column_header']=='IN TRANSIT(+)'){

		$GetData=inTransitIn('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}
	elseif($_GET['column_header']=='IN TRANSIT(-)'){

		$GetData=inTransitOut('warehouse',$_GET['sku_code'],$_GET['dateStart'],$_GET['dateEnd'],'warehouse');
	}

}
	

function GetName($store_id){
	global $conn;
	$name=array();
	$storelength=strlen($store_id);
	if($storelength=='3' || $storelength=='7'){
		$query="SELECT store_name FROM stores_locations where store_id='".$store_id."' ";
	}
	elseif ($store_id=='1000') {

		$query="SELECT store_name FROM stores_locations where store_id='".$store_id."' ";
	}
	elseif (preg_match('/\warehouse\b/', $store_id)) {

		$query=" SELECT '".$store_id."'  ";
	}elseif (preg_match('/\warehouse_damage\b/', $store_id)) {

		$query=" SELECT '".$store_id."'  ";
	}elseif (preg_match('/\warehouse_qa\b/', $store_id)) {

		$query=" SELECT '".$store_id."'  ";
	}elseif($storelength>='19'){
		
	$query=" SELECT lab_name FROM `labs_locations` WHERE `lab_id`='".$store_id."' ";
	}else{
		$query=" SELECT '".$store_id."'  ";
	}
	


	$grabParams=array("name");

    //   echo $query;             
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$name[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);	

	};


	return $name;

}

?><style>
.table-inventory tbody tr td{
					cursor: pointer;
				}
</style>
<?php if($_GET['column_header']=='DAILY SALES'){ ?>
				<table class="table-striped table-inventory" style="width:100%">
				<tr>
				<th>#</th>
				<th>Date of Transaction</th>
				<th>Count</th>
				<th>Branch</th>
				<th>Po number</th>
				</tr>
				<?php if($GetData){ ?>
					<?php for($i=0;$i<sizeof($GetData);$i++){?>
						<tr>
						<td><?= $i+1 ?></td>
						<td><?= cvdate($GetData[$i]["payment_date"])?></td>
						<td><?= $GetData[$i]["sales_count"]?></td>
						<td><?= $GetData[$i]["branch"]?></td>
						<td><?= $GetData[$i]["po_number"]?></td>
						</tr>

						<?php } ?>
				<?php  } ?>

				</table>
<?php  } ?>

<?php if($_GET['column_header']=='STOCK TRANSFER (+)' || $_GET['column_header']=='INTER BRANCH (+)' || $_GET['column_header']=='PULLOUT' || $_GET['column_header']=='DAMAGE' || $_GET['column_header']=='IN TRANSIT(+)'  ){ ?>
				<table class="table-striped table-inventory" style="width:100%">
				<tr>
				<th>#</th>
				<th>Date of Transaction</th>
				<th>Count</th>
				<th>Stock From</th>
				<th>Reference Number</th>
				</tr>
				<?php if($GetData){ ?>
					<?php for($i=0;$i<sizeof($GetData);$i++){?>
						<tr>
						<td><?= $i+1 ?></td>
						<td><?= cvdate($GetData[$i]["payment_date"])?></td>
						<td><?= $GetData[$i]["sales_count"]?></td>
						<td><?php $name= GetName($GetData[$i]["stock_from"]);
									echo  ucwords(str_replace("_"," ", $name[0]["name"]))." ".$GetData[$i]["stock_from"];
						?></td>
						<td><?= $GetData[$i]["reference_number"]?></td>
						</tr>

						<?php } ?>
				<?php  } ?>

				</table>
<?php  } ?>


<?php if($_GET['column_header']=='STOCK TRANSFER (-)' || $_GET['column_header']=='INTER BRANCH (-)'  || $_GET['column_header']=='IN TRANSIT(-)'){ ?>
				<table class="table-striped table-inventory" style="width:100%">
				<tr>
				<th>#</th>
				<th>Date of Transaction</th>
				<th>Count</th>
				<th>Store</th>
				<th>Reference Number</th>
				</tr>
				<?php if($GetData){ ?>
					<?php for($i=0;$i<sizeof($GetData);$i++){?>
						<tr>
						<td><?= $i+1 ?></td>
						<td><?= cvdate($GetData[$i]["payment_date"])?></td>
						<td><?= $GetData[$i]["sales_count"]?></td>
						<td><?php $name= GetName($GetData[$i]["stock_from"]);
									echo $name[0]["name"];
						?></td>
						<td><?= $GetData[$i]["reference_number"]?></td>
						</tr>

						<?php } ?>
				<?php  } ?>

				</table>
<?php  } ?>