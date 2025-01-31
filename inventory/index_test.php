<?php 
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'dispatch';

////////////////////////////////////////////////

// Set store for Admin
if($_SESSION['user_login']['userlvl'] == 1 && $_SESSION['user_login']['position'] == 'admin') {

	if(isset($_GET['store'])) {

		$_SESSION['store_code'] = $_GET['store'];
		$_SESSION['user_login']['store_code'] = $_GET['store'];

	}
	else {

		$_SESSION['store_code'] = '109';
		$_SESSION['user_login']['store_code'] = '109';

	};

};



// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header.php";
require $sDocRoot."/includes/v2/navbar.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/includes/grab_customer_list_dispatch_v2.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_inventory_products.php";
require $sDocRoot."/includes/dashboard/set_date.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Store
$storeName = "";

for ($i=0; $i < sizeOf($arrStore); $i++) { 

	if($arrStore[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

		$storeName = $arrStore[$i]['store_name'];

	};
	
};

?>
<!DOCTYPE html>
<html>
<head>
	<!-- meta tag -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1.0, minimum-scale=1.0, maximum-scale=1.0">

	<title>Inventory | SSIS</title>

	<!-- fonts link -->

	<!-- css files -->
	<?= $header_css; ?>

	<?= $favicon; ?>

	<?= $ie ?>

    <link rel="stylesheet" type="text/css" href="/css/navbar.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">

    <style>

		.inventory-home {
			margin-top: 105px;
			padding-bottom: 50px;
		}	

		.mt-50 {
			margin-top: 50px;
		}
		.mt-80 {
			margin-top: 80px;
		}

		html, 
		body,
		.ssis-body {
			background-color: #5f0000;
		}
		td, th, table {
			border-left: 0 !important;
			border-right: 0 !important;
		}
		#ssis-content {
			padding: 0;		
		}
		#ssis-content .ssis-section .section-outter {
			position: relative;
		}
		#ssis-content .ssis-section .section-inner {
			position: relative;
			background-color: #e6e6e6;
			margin-top: 0;
			border-radius: 40px 40px 0 0;
			box-shadow: 0px -8px 16px 0px rgba(100, 100, 100, 0.35)
		}	
		.ssis-2 nav a,
		.ssis-2 .section-header {
			color: #ffffff;
		}
		.ssis-2 .section-header > div {
			padding-bottom: 100px;
		}
		.ssis-2 .filter-well {
			position: absolute;
		    top: 0;
		    left: 0;
		    padding-top: 50px;
		    padding-bottom: 100px;
		    width: 100%;
		    height: auto;
		    background: #ffffff;
		    border-radius: 40px 40px 0 0 ;	    
		}
		.ssis-2 .filter-well > div {
			height: 100%;
		}
		.ssis-2 .row-filter-bar {
			position: absolute;
			width: 100%;
			top: -35px;
			left: 0;
			z-index: 1;
		}
		.ssis-2 .row-filter-bar .div-open-filter {
			margin-right: 30px;
		}
		.ssis-2 .row-filter-bar .div-open-filter .btn-open-filter {
			padding: 15px;
			height: 70px;
		    width: 70px;
		    border-radius: 50px;
		    color: #000000;
		    background-color: #ffffff;
		    box-shadow: 0px 6px 10px -2px gray;
		    text-align: center;	    
		    cursor: pointer;
		}
		.ssis-2 .row-filter-bar .div-open-filter .btn-open-filter > img {
			max-width: 90%;
		}
		.ssis-2 .row-filter-bar .div-search-filter {
			width: 40%;		
		    height: 70px;
		    border-radius: 50px;
		    border: none;
		    box-shadow: 0px 6px 10px -2px gray;
		    background-color: #ffffff;
		    overflow: hidden;
		}
		@media screen and (max-width:768px) {
			.ssis-2 .row-filter-bar .div-search-filter {
				width: 60%;
			}
		}
		.ssis-2 .row-filter-bar .div-search-filter input {
			margin: 0 !important;
			border: none;
		}
		.ssis-2 .row-filter-bar .div-search-filter input:focus,
		.ssis-2 .row-filter-bar .div-search-filter button:focus {
			outline: none;
	    	box-shadow: none;
	    	background-color: #ffffff;
		}
		.ssis-2 .row-filter-bar .div-search-filter #search {
			padding: 20px;
		    height: 100%;
			border: none;
		}	
		.ssis-2 .row-filter-bar .div-search-filter button {
			width: 80px;
			color: #495057;
		}
		
		.js-pscroll {
		  position: relative;
		  overflow: auto;
		}

		.table100 {
		  width: 100%;
		  position: relative;
		  border: 0;
		}

		.wrap-table100 {
			border: 0;
		}

		.wrap-table100.scroll {
			overflow: auto;
			/*height: 640px;*/
			height: 70vh;
			border: 0;
		}

		.table100 table {
			width: auto;
		  border: 0;
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
			/*border-right: 1px solid #e6e6e6;*/
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
		  color: #fff;
		}

		.table100.ver1 th a {
			color: #fff;
			text-decoration: none !important;
		}

		.table100.ver1 td {
		  font-family: 'Poppins-Regular';
		  padding: 15px 10px;
		  height: auto;
		}

		.table100.ver1 .table100-firstcol td a {
			color: #000;
			border-bottom: 1px solid #808080;
			text-decoration: none !important;
		}

		.table100.ver1 .table100-firstcol td,
		.table100.ver1 .table100-nextcols td {
		  color: #000;
		  /*height: 60px;*/
		}

		.table100.ver1 .table100-nextcols td {
			padding: 15px 10px;
			border: 1px solid #e6e6e6;
		}


		.table100.ver1 tr {
		  border-bottom: 1px solid #e6e6e6;
		}

		.table100.ver1 td a {
			border: 0;
			line-height: 2.7;
		}

		.table-wrapper {
			position: relative;
			height: 100%;
			width: 100%;
		}

		.wrap-table100 {
			border-radius: 5px;
			overflow: hidden;
			box-shadow: 0px 6px 10px -2px gray;
		}
		.wrap-table100 tr.head th {
			background-color: #5f0000 !important;
			padding: 20px 10px;
		}

		table thead th {
			top: 0px !important;
			position: sticky !important;
		}

	</style>

</head>
<body class="ssis-2" id="ssis-admin">

	<div class="ssis-body">
<?php 


if(isset($_GET['seen'])){
	$querySeenMessage="UPDATE remarks_comm SET
							   seen='y',
							   seen_date=now()
					   WHERE 
						   order_po_id='".$_GET['seen']."'	
						   and  profile_id!='".$_SESSION['id']."' 
						   ";
$stmtSeen = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmtSeen, $querySeenMessage)) {

   mysqli_stmt_execute($stmtSeen);
   mysqli_stmt_close($stmtSeen);

} 
else {

   echo mysqli_error($conn);		
   exit;	

};
						   

}else{

}

?>
		<?php 
			
			if(!isset($_SESSION['dashboard_login'])){

				echo "<script>window.location = 'www.sunniessytems.com'</script>";	
				
			}
			else{ 

		?>
	
		<!-- =============================================================== TOP BAR -->

		<?php 			

			if($_SESSION['user_login']['userlvl'] == 1 && $_SESSION['user_login']['position'] == 'admin') {

				echo $navbar_return;

			}
			else {

				echo $navbar;

			};

		?>

		<!-- =============================================================== CONTENT -->
		
		<div id="ssis-content" class="inventory-home">

		

			<section class="ssis-section mb-0">
				<div class="section-header row no-gutters align-items-top justify-content-between">
	                <div>
	                	<img class="img-fluid header-icon" src="/assets/images/icons/icon-inventory.png" />
	                    <h2><?= str_replace("Up Town Center", "UP Town Center", str_replace("Sm ", "SM ", str_replace("Mw ", "MW ", str_replace("Ali ", "ALI ", ucwords( strtolower($storeName) ))))); ?></h2>
	                    <p>Inventory Page</p>
	                </div>
	            </div>
	            <div class="section-outter closed">				

					<div class="row no-gutters align-items-top justify-content-center row-filter-bar">	

						<?php 

							if($_SESSION['user_login']['userlvl'] == 1 && $_SESSION['user_login']['position'] == 'admin') {

						?>

						<div class="row no-gutters justify-content-between align-items-center div-open-filter">
		            		<div class="col-12">
		            			<div class="row no-gutters justify-content-between align-items-center btn-open-filter">
		            				<img class="center-block img-fluid gear" src="/images/icons/primary-setting-icon.png" />
		            			</div>
		           	 		</div>
		            	</div>

		            	<?php

		            		}

		            	?>

		            	<div class="row no-gutters justify-content-between align-items-center div-search-filter">

							<?php

								if(isset($_GET['search']) && $_GET['search'] != '') {

									$searchVal = $_GET['search'];

								}
								else {

									$searchVal = "";

								};

							?>

							<input type="text"name="search" class="search form-control col" id="search" placeholder="Search product code or style name" value="<?= $searchVal ?>">
							<button type="button" class="btn btn-gray" id="btnSearch"><i class="zmdi zmdi-search zmdi-hc-2x"></i></button>
						</div>
		            </div>	
		            <div class="filter-well">
					
						<form method="GET" action="/inventory/?" class="mt-4" id="filterDate">
							<p class="text-uppercase font-bold text-center font-weight-bold" style="color: #5F0000">Select date Range</p>
							<div class="mt-5 row no-gutters">
								<div class="col-12 col-md-4 pl-3 pl-md-4 pr-3 pr-md-4">
									<div class="mb-2">
										<div class="d-flex align-items-center">
											<input class="checkbox" name="date" type="radio" id="optionYesterday" value="yesterday" >
											<label for="optionYesterday" class="custom_checkbox"></label>
											<label for="optionYesterday" class="m-0 ml-2">Yesterday</label>
										</div>
									</div>
									<div class="mb-2">
										<div class="d-flex align-items-center">
											<input class="checkbox" name="date" type="radio" id="optionDay" value="day" checked="checked">
											<label for="optionDay" class="custom_checkbox"></label>
											<label for="optionDay" class="m-0 ml-2">Today</label>
										</div>
									</div>
									<div class="mb-2">
										<div class="d-flex align-items-center">
											<input class="checkbox" name="date" type="radio" id="optionWeek" value="week" >
											<label for="optionWeek" class="custom_checkbox"></label>
											<label for="optionWeek" class="m-0 ml-2">This Week</label>
										</div>
									</div>
									<div class="mb-2">
										<div class="d-flex align-items-center">
											<input class="checkbox" name="date" type="radio" id="optionMonth" value="month" >
											<label for="optionMonth" class="custom_checkbox"></label>
											<label for="optionMonth" class="m-0 ml-2">This Month</label>
										</div>
									</div>
									<div class="mb-2">
										<div class="d-flex align-items-center">
											<input class="checkbox" name="date" type="radio" id="optionYear" value="year" >
											<label for="optionYear" class="custom_checkbox"></label>
											<label for="optionYear" class="m-0 ml-2">This Year</label>
										</div>
									</div>
									<div class="mb-2">
										<div class="d-flex align-items-center">
											<input class="checkbox" name="date" type="radio" id="optionAllTime" value="all-time" >
											<label for="optionAllTime" class="custom_checkbox"></label>
											<label for="optionAllTime" class="m-0 ml-2">All Time</label>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-4 pl-3 pl-md-4 pr-3 pr-md-4">
									<div class="d-flex align-items-center">
										<input class="checkbox" name="date" type="radio" id="optionCustom" value="custom" >
										<label for="optionCustom" class="custom_checkbox"></label>
										<label for="optionCustom" class="m-0 ml-2">Custom Range</label>
									</div>								    		

									<?php

										// General Date
										$sTimeMonth = date('F');
										$sTimeDay 	= date('d');
										$sTimeYear  = date('Y');

										// Data Range Start
										if(isset($_GET['data_range_start_month'])) {

											$sStartMonth = $_GET['data_range_start_month'];
											$sStartDay 	 = $_GET['data_range_start_day'];
											$sStartYear  = $_GET['data_range_start_year'];

										}
										else {

											$sStartMonth = '';
											$sStartDay 	 = '';
											$sStartYear  = '';

										};

										// Data Range End
										if(isset($_GET['data_range_end_month'])) {

											$sEndMonth = $_GET['data_range_end_month'];
											$sEndDay   = $_GET['data_range_end_day'];
											$sEndYear  = $_GET['data_range_end_year'];

										}
										else {

											$sEndMonth = '';
											$sEndDay   = '';
											$sEndYear  = '';

										};

									?>
									
									<div class="row-data-range mt-3">	
										<p>Start Date</p>
										<div class="row no-gutters mt-2">
											<div class="col-12 col-md-6 mb-2 mb-md-0 pr-0 pr-md-2">												    		
												<select class="form-control select" id="data_range_start_month" name="data_range_start_month"

											<?php

												if(!isset($_GET['data_range_start_month'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=0; $i < 12; $i++) { 
													
														$month = date("F", strtotime('01.'.($i + 1).'.2001')); 
														$sStartMonthName = date("F", strtotime('01.'.$sStartMonth.'.2001')); 
														$monthSelect = "";

														if($sStartMonth != "" && $sStartMonthName == $month) {

															$monthSelect = " selected";

														};

														echo '<option value="'.($i + 1).'" data-month="'.$month.'"'.$monthSelect.'>'.$month.'</option>';

													};

												?>													    			

											</select>
											</div>
											<div class="col-12 col-md-3 mb-2 mb-md-0 pr-0  pr-md-2">
												<select class="form-control select" id="data_range_start_day" name="data_range_start_day"

											<?php

												if(!isset($_GET['data_range_start_day'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													$numDays = $arrMonthDays["January"];

													for ($i=0; $i < $numDays; $i++) { 

														$daySelect = "";

														if($sStartDay != "" && $sStartDay == ($i + 1)) {

															$daySelect = " selected";

														};

														echo '<option value="'.($i + 1).'"'.$daySelect.'>'.(sprintf("%02d", ($i + 1))).'</option>';
														
													};

												?>													    			
												
											</select>			
											</div>
											<div class="col-12 col-md-3">											    		
												<select class="form-control select mr-2" id="data_range_start_year" name="data_range_start_year"

											<?php

												if(!isset($_GET['data_range_start_year'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=2016; $i <= $sTimeYear; $i++) { 

														$yearSelect = "";

														if($sStartYear != "" && $i == $sStartYear) {

															$yearSelect = " selected";

														}
														else if($sStartYear == "" && $i == $sTimeYear) {

															$yearSelect = " selected";

														}
														
														echo '<option value="'.$i.'"'.$yearSelect.'>'.$i.'</option>';

													};

												?>

											</select>
											</div>
										</div>
									</div>
									<div class="row-data-range mt-3">			
										<p>End Date</p>
										<div class="row no-gutters mt-2">	
											<div class="col-12 col-md-6 mb-2 mb-md-0 pr-0  pr-md-2">
												<select class="form-control select" id="data_range_end_month" name="data_range_end_month"

											<?php

												if(!isset($_GET['data_range_end_month'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=0; $i < 12; $i++) { 
													
														$month = date("F", strtotime('01.'.($i + 1).'.2001')); 
														$sEndMonthName = date("F", strtotime('01.'.$sEndMonth.'.2001')); 
														$monthSelect = "";

														if($sEndMonth != "" && $sEndMonthName == $month) {

															$monthSelect = " selected";

														}
														else if($sEndMonth == "" && $sTimeMonth == $month) {

															$monthSelect = " selected";

														};

														echo '<option value="'.($i + 1).'" data-month="'.$month.'"'.$monthSelect.'>'.$month.'</option>';

													};

												?>													    			

											</select>
											</div>
											<div class="col-12 col-md-3 mb-2 mb-md-0 pr-0  pr-md-2">
												<select class="form-control select" id="data_range_end_day" name="data_range_end_day"

											<?php

												if(!isset($_GET['data_range_end_day'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													$numDays = $arrMonthDays["January"];													    		

													for ($i=0; $i < $numDays; $i++) { 

														$daySelect = "";

														if($sEndDay != "" && $sEndDay == $i) {

															$daySelect = " selected";

														}
														else if($sEndDay == "" && $sTimeDay == $i) {

															$daySelect = " selected";

														};		

														echo '<option value="'.($i + 1).'"'.$daySelect.'>'.(sprintf("%02d", ($i + 1))).'</option>';
														
													};

												?>													    			
												
											</select>		
											</div>	
											<div class="col-12 col-md-3">											    		
												<select class="form-control select mr-2" id="data_range_end_year" name="data_range_end_year"

											<?php

												if(!isset($_GET['data_range_end_year'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=2016; $i <= $sTimeYear; $i++) { 

														$yearSelect = "";

														if($sEndYear != "" && $i == $sEndYear) {

															$yearSelect = " selected";

														}
														else if($sEndYear == "" && $i == $sTimeYear) {

															$yearSelect = " selected";

														}
														
														echo '<option value="'.$i.'"'.$yearSelect.'>'.$i.'</option>';

													};

												?>

											</select>
											</div>
										</div>
									</div>		
								</div>
								<div class="col-12 col-md-4 pl-3 pl-md-4 pr-3 pr-md-4 align-self-end">
									<?php if(isset($_GET['date'])) { ?>

										<a href="/inventory/<?= isset($_GET['search']) ? '?search='.$_GET["search"] : '' ?>" class="d-block mb-3">
											<button class="btn btn-danger" type="button">reset filter</button>
										</a>

									<?php }; ?>
									<button type="submit" class="btn btn-primary">save filter</button>
								</div>
							</div>
						</form>

		        	</div>
		        	<div class="section-inner">	
							<?php

							if(isset($_GET['search'])) {

								echo 	'<div class="text-center mt-4">
												<a href="/inventory/" class="text-danger">
													Reset Search
												</a>
											</div>';

							};

						?>

		        		<div class="frame-inventory mt-5">

							<div class="inventory-body">
								<div class="wrap-table100 non-search">
								<div class="table100 ver1">
									<div class="wrap-table100-nextcols">
										<div class="table100-nextcols">
											<table cellpadding="0" cellspacing="0" style="border-collapse: collapse">
												<thead>
													<tr class="row100 head">
														<th class="cell100 small column1">SKU</th>
														<th class="cell100 text-center small column3" nowrap>starting inventory</th>
														<th class="cell100 text-center small column3 toggle-column" id="col-daily" nowrap>daily sales</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="daily"><?= $x ?></th>
														<?php } ?>
														<th class="cell100 text-center small column3 toggle-column" id="col-delivery" nowrap>delivery</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="delivery"><?= $x ?></th>
														<?php } ?>
														<th class="cell100 text-center small column3 toggle-column" id="col-inter" nowrap>inter branch</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="inter"><?= $x ?></th>
														<?php } ?>
														<th class="cell100 text-center small column3 toggle-column" id="col-pullout" nowrap>pullout</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="pullout"><?= $x ?></th>
														<?php } ?>
														<th class="cell100 text-center small column3 toggle-column" id="col-damage" nowrap>damage</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="damage"><?= $x ?></th>
														<?php } ?>
														<th class="cell100 text-center small column3 toggle-column" id="col-physical" nowrap>physical count</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="physical"><?= $x ?></th>
														<?php } ?>
														<th class="cell100 text-center small column3 toggle-column" id="col-variance" nowrap>variance</th>
														<?php for ($x=1;$x<=31;$x++) { ?>
															<th class="cell100 text-center small column3 d-none" nowrap data-col="variance"><?= $x ?></th>
														<?php } ?>
													</tr>
												</thead>
												<tbody>

													<?php for ($i=0;$i<15;$i++) { ?>
													
														<tr class="row100 body">
															<td nowrap class="cell100 small column1 ">
																<?= $arrFrames[$i]['product_code'] ?>
																<p class="small m-0"><?= $arrFrames[$i]['product_style'] . " " . $arrFrames[$i]['product_color'] ?></p>
															</td>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="daily"><a class="d-block">0</a></td>
															<?php } ?>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="delivery"><a class="d-block">0</a></td>
															<?php } ?>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="inter"><a class="d-block">0</a></td>
															<?php } ?>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="pullout"><a class="d-block">0</a></td>
															<?php } ?>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="damage"><a class="d-block">0</a></td>
															<?php } ?>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="physical"><a class="d-block">0</a></td>
															<?php } ?>
															<td nowrap class="cell100 small text-center"><a class="d-block">0</a></td>
															<?php for ($x=1;$x<=31;$x++) { ?>
																<td nowrap class="cell100 small text-center d-none" data-col="variance"><a class="d-block">0</a></td>
															<?php } ?>
														</tr>

													<?php } ?>

												</tbody>
											</table>
										</div>
									</div>
								</div>
								</div>
							</div>
						</div>

		        	</div>

		        </div>
	        </section>

			<div class="modal fade" id="informationRemarks">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
			
					</div>
				</div>
			</div>
			<div class="modal fade" id="cancelOrder">
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
							<button type="button" id="cancelThisOrder" class="btn btn-danger" data-customer-id="" data-order-id="" data-po-number="" data-customer-name="" style="color: #ffffff;">Cancel Order</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-remarks text-left"></div>
			<div class="stat" style="display: none"></div>

<!-- ===================================================================================================================================== -->

			<script src="../js/jquery-3.2.1.min.js"></script>
			<script src="../js/tether.min.js"></script>
			<script src="../js/bootstrap.min.js"></script>
			<script src="../js/perfect-scrollbar.min.js"></script>
			<script src="../js/select2.min.js"></script>
			<script src="../js/ssis_functions.js"></script>
			<script src="../js/custom.js"></script>

			<script>
			
				$('.select2').select2();

				//////////////// update the spacing of table

				function updateW() {

					var px = $('.table100-firstcol').outerWidth(true);

					$('.wrap-table100-nextcols').css('padding-left', px );

				};

				if ( $('.table100-nextcols tbody tr').length > 10 ) {

					$('.wrap-table100').addClass('scroll');

				};

				//////////////// SEARCH

				function grabVal(selector) {

					var value = $(selector).val();

					if(value == null) {

						value = "";

					};

					return value;

				};		

				$("#btnSearch").click(function() {

					// Grab values of filters
					filterA = grabVal('#search').replace(/\s/g, "+");
						
					filterURL = "/inventory/?search=" + filterA;

					window.location.href = filterURL;

				});

				//////////////// OPEN FILTER

				$('.btn-open-filter').click(function() {

					if($('.section-outter').hasClass('open')) {

						$('.section-outter').animate({

							'paddingTop': 0

						}, 300);

						$('.section-outter').addClass('closed').removeClass('open');

					}
					else {

						$('.section-outter').animate({

							'paddingTop': $('.filter-well').outerHeight() - 50

						}, 300);

						$('.section-outter').addClass('open').removeClass('closed');

					};

				});

				//////////////// DATE FITLER

				var customSwitch = false;

				$('input[type=radio][name=date]').change(function() {

					var thisID = $(this).attr('id');

					if(thisID != 'optionCustom') {

						$('#data_range_start_month').prop('disabled', true);
						$('#data_range_start_day').prop('disabled', true);
						$('#data_range_start_year').prop('disabled', true);
						$('#data_range_end_month').prop('disabled', true);
						$('#data_range_end_day').prop('disabled', true);
						$('#data_range_end_year').prop('disabled', true);
						customSwitch = false;						

					}
					else {

						if(customSwitch) {

							$('#data_range_start_month').prop('disabled', true);
							$('#data_range_start_day').prop('disabled', true);
							$('#data_range_start_year').prop('disabled', true);
							$('#data_range_end_month').prop('disabled', true);
							$('#data_range_end_day').prop('disabled', true);
							$('#data_range_end_year').prop('disabled', true);
							customSwitch = false;

						}
						else {

							$('#data_range_start_month').prop('disabled', false);
							$('#data_range_start_day').prop('disabled', false);
							$('#data_range_start_year').prop('disabled', false);
							$('#data_range_end_month').prop('disabled', false);
							$('#data_range_end_day').prop('disabled', false);
							$('#data_range_end_year').prop('disabled', false);
							customSwitch = true;

						}

					};

				});

				$('#data_range_start_month').change(function() {

					var thisMonth = $(this).val();

					$('#data_range_start_day').load('../includes/date_select_dropdown.php?month=' + thisMonth);

				});

				$('#data_range_end_month').change(function() {

					var thisMonth = $(this).val();

					$('#data_range_end_day').load('../includes/date_select_dropdown.php?month=' + thisMonth);

				});

				//////////////// TOGGLE COLUMN

				$('.toggle-column').on('click', function() {
					var col = $(this).attr('id').replace('col-','');
					$('[data-col='+col+']').toggleClass('d-none');
				})

				// ================================================== ON LOAD, SCROLL AND RESIZE FUNCTIONS

				$(window).on('resize load scroll', function() {

					updateW();

				}).resize();
			
			</script>

		</div>

		</div>
	
		<?php } ?>

	</div>
	
</body>
</html>