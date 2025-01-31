<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////
$page_url = 'dashboard';

$filter_page = 'dashboard_studios';
$group_name = 'sunnies_studios';

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/dashboard_studios/functions.php";
require $sDocRoot."/includes/dashboard_studios/set_date.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Send away if not super user
// if($_SESSION['user_login']['userlvl'] != '1' && $_SESSION['user_login']['userlvl'] != '6' && $_SESSION['user_login']['userlvl'] != '7' && $_SESSION['user_login']['userlvl'] != '14' && $_SESSION['user_login']['userlvl'] != '17')  {

// 	header('location: /');
// 	exit;

// };

if(!isset($_SESSION['dashboard_login'])){

	echo "<script>window.location = 'www.sunniessytems.com'</script>";	
	
} else { 

?>

<?= get_header($page_url) ?>

<style type="text/css">
	
	@media screen and (min-width: 1600px) {

		.col-or-3 {
			flex: 0 0 33.33% !important;
			max-width: 33% !important;
		}

	};

</style>

<style type="text/css">
	
	#bar_chart_rev_breakdown .store-row .store-total-bar .data-bar {
		position: relative;
		background-color: #28a745;
		width: 0%;
		max-width: 70%;
		height: 30px;
	}	

	.table_div_head {
		font-family: 'Poppins-Medium';
		padding-top: 15px;
		padding-bottom: 15px;
		border: 1px solid #eeeeee;
		background: white;
		text-align: center;
	}
	.table_div_head p {
		margin-bottom: 0;
		font-weight: bold;
		font-size: 18px;
	}
	.table-header-black {
		background: #36482e;
		color: #f7f7f7;
	}
	.table-header-black th {
		font-family: 'Poppins-Medium' !important;
		text-transform: uppercase !important;
		padding: 20px !important;
		background-image: none !important;
		border-left: 0;
		border-right: 0;
		color: #f7f7f7;
	}
	.table-row-style td {
		padding: 15px 20px !important;
		border-left: 0;
		border-right: 0;
		font-family: 'Poppins-Medium' !important;
	}    	
	.table-header-black th:not(:first-of-type),
	.table-row-style td:not(:first-of-type) {
		text-align: center;
	} 

	@media screen and (max-width: 768px) {

		#bar_chart_rev_breakdown .store-row .store-total-bar .data-bar {
			display:  none;
		}
		#bar_chart_rev_breakdown p {
			font-size:  11px;
		}
		#bar_chart_rev_breakdown .store-row .store-name .col-10.text-right {
			text-align: left !important;
		}
		#bar_chart_rev_breakdown .store-row .store-total p {
			margin-left: 10px;
		}

	};

</style>

<style type="text/css">
	
	#data-gender > div {
	    height: 400px;
	}
	#data-gender > div > div {
	    height: 100%;
	}
	#data-gender > div .fillUP-container {
	    height: 100%;
	}
	#data-gender > div .fillUP-container #fillUP {
	    height: 100%;
	    /*padding-top: 5%;
	    padding-bottom: 25%;*/
	    width: 50px;
	    margin: 0 auto;
	}
	#data-gender > div .fillUP-container #fillUP #male {
	    height: 25%;
	    background: #28a745;
	    border-bottom: 1px solid #ffffff;
	}
	#data-gender > div .fillUP-container #fillUP #female {
	    height: 25%;
	    background: #585858;
	    border-top: 1px solid #ffffff;
	}
	#data-gender > div .fillUP-container #fillUP #guest {
	    height: 25%;
	    background: #2A4D14;
	    border-top: 1px solid #ffffff;
	}
	#data-gender > div .fillUP-container #fillUP #na {
	    height: 25%;
	    background: red;
	    border-top: 1px solid #ffffff;
	}
	#data-gender > div .fillUP-container #fillUP > div {
	    position: relative;
	}
	#data-gender > div .fillUP-container #fillUP > div > p {
	    position: absolute;
	    top: 50%;
	    transform: translateY(-50%);
	    left: 70px;
	    margin-bottom: 0;    
	    width: 200%;
	}
	#data-gender > div .list-container > .list {
	    
	}
	#data-gender > div .list-container > .list p {
	    margin-bottom: 0;
	}
	#data-gender > div .list-container > .list p span {
	    font-size: 30px;                            
	}
	#data-gender > div .list-container > .list p #list-male {
	    color: #28a745;
	}
	#data-gender > div .list-container > .list p #list-female {
	    color: #585858;
	}
	#data-gender > div .list-container > .list p #list-guest {
	    color: #2A4D14;
	}
	#data-gender > div .list-container > .list p #list-na {
	    color: red;
	}

</style>

<style type="text/css">
	
	#data-customer-types > div {
	    height: 400px;
	}
	#data-customer-types > div > div {
	    height: 100%;
	}
	#data-customer-types > div .fillUP-container {
	    height: 100%;
	}
	#data-customer-types > div .fillUP-container #fillUP {
	    height: 100%;
	    /*padding-top: 5%;
	    padding-bottom: 25%;*/
	    width: 50px;
	    margin: 0 auto;
	}
	#data-customer-types > div .fillUP-container #fillUP #newCustomer {
	    height: 33%;
	    background: #28a745;
	    border-bottom: 1px solid #ffffff;
	}
	#data-customer-types > div .fillUP-container #fillUP #recurringCustomer {
	    height: 33%;
	    background: #585858;
	    border-top: 1px solid #ffffff;
	}
	#data-customer-types > div .fillUP-container #fillUP #guestCustomer {
	    height: 33%;
	    background: #2A4D14;
	    border-top: 1px solid #ffffff;
	}
	#data-customer-types > div .fillUP-container #fillUP > div {
	    position: relative;
	}
	#data-customer-types > div .fillUP-container #fillUP > div > p {
	    position: absolute;
	    top: 50%;
	    transform: translateY(-50%);
	    left: 70px;
	    margin-bottom: 0;    
	    width: 200%;
	}
	#data-customer-types > div .list-container > .list {
	    
	}
	#data-customer-types > div .list-container > .list p {
	    margin-bottom: 0;
	}
	#data-customer-types > div .list-container > .list p span {
	    font-size: 30px;                            
	}
	#data-customer-types > div .list-container > .list p #list-newCustomer {
	    color: #28a745;
	}
	#data-customer-types > div .list-container > .list p #list-recurringCustomer {
	    color: #585858;
	}
	#data-customer-types > div .list-container > .list p #list-guestCustomer {
	    color: #2A4D14;
	}

</style>

<style type="text/css">
	
	#data-frame-option > div {
	    height: 400px;
	}
	#data-frame-option > div > div {
	    height: 100%;
	}
	#data-frame-option > div .fillUP-container {
	    height: 100%;
	}
	#data-frame-option > div .fillUP-container #fillUP {
	    height: 100%;	    
	    width: 50px;
	    margin: 0 auto;
	}
	#data-frame-option > div .fillUP-container #fillUP #withPRX {
	    height: 50%;
	    background: #28a745;
	    border-bottom: 1px solid #ffffff;
	}
	#data-frame-option > div .fillUP-container #fillUP #withoutPRX {
	    height: 50%;
	    background: #585858;
	    border-top: 1px solid #ffffff;
	}
	#data-frame-option > div .fillUP-container #fillUP > div {
	    position: relative;
	}
	#data-frame-option > div .fillUP-container #fillUP > div > p {
	    position: absolute;
	    top: 50%;
	    transform: translateY(-50%);
	    left: 70px;
	    margin-bottom: 0;    
	    width: 200%;
	}
	#data-frame-option > div .list-container > .list {	    
	}
	#data-frame-option > div .list-container > .list p {
	    margin-bottom: 0;
	}
	#data-frame-option > div .list-container > .list p span {
	    font-size: 30px;                            
	}
	#data-frame-option > div .list-container > .list p #list-withPRX {
	    color: #28a745;
	}
	#data-frame-option > div .list-container > .list p #list-withoutPRX {
	    color: #585858;
	}

</style>

<style type="text/css">
		
	#data-prescription > div {
	    height: 400px;
	}
	#data-prescription > div > div {
	    height: 100%;
	}
	#data-prescription > div .fillUP-container {
	    height: 50px;
	}
	#data-prescription > div .fillUP-container #fillUP {
	    height: 50px;
	    width: 100%;
	    margin: 0 auto;
	    padding-top: 100px;
	    padding-left: 15%;
	    padding-right: 15%;
	}
	#data-prescription > div .fillUP-container #fillUP > div {
	    width: 14%;
	    height: 50px;
	    float: left;
	    /*border-right: 1px solid #ffffff;*/
	}
	#data-prescription > div .fillUP-container #fillUP #single_vision_stock {    
	    background: #7BDF9D;
	}
	#data-prescription > div .fillUP-container #fillUP #single_vision_rx {    
	    background: #4ECD79;
	}
	#data-prescription > div .fillUP-container #fillUP #double_vision_stock {    
	    background: #2EBE5F;
	}
	#data-prescription > div .fillUP-container #fillUP #double_vision_rx {    
	    background: #09B543;
	}
	#data-prescription > div .fillUP-container #fillUP #progressive_stock {    
	    background: #038930;
	}
	#data-prescription > div .fillUP-container #fillUP #progressive_rx {    
	    background: #2A4D14;
	}
	#data-prescription > div .fillUP-container #fillUP #special_order {    
	    background: #585858;
	}
	#data-prescription > div .fillUP-container #fillUP > div {
	    position: relative;
	}
	#data-prescription > div .fillUP-container #fillUP > div > p {
	    display: none;
	}
	#data-prescription > div .list-container > .list {
	    padding-left: 15%;
	    padding-right: 15%;    
	}
	#data-prescription > div .list-container > .list p {
	    margin-bottom: 0;
	}
	#data-prescription > div .list-container > .list p span {
	    font-size: 20px;                            
	}
	#data-prescription > div .list-container > .list p #list-single_vision_stock {
	    color: #7BDF9D;
	}
	#data-prescription > div .list-container > .list p #list-single_vision_rx {
	    color: #4ECD79;
	}
	#data-prescription > div .list-container > .list p #list-double_vision_stock {
	    color: #2EBE5F;
	}
	#data-prescription > div .list-container > .list p #list-double_vision_rx {
	    color: #09B543;
	}
	#data-prescription > div .list-container > .list p #list-progressive_stock {
	    color: #038930;
	}
	#data-prescription > div .list-container > .list p #list-progressive_rx {
	    color: #2A4D14;
	}
	#data-prescription > div .list-container > .list p #list-special_order {
	    color: #585858;
	}

</style>

<style type="text/css">

	#total-revenue {
		position: relative;
	}
	#revenue-breakdown-cover {
		width: 100%; 
		height: 100%; 
		position: absolute; 
		left: 0; 
		top: 0; 
		background: #f7f7f7;
		display: none;
	}

	.dataTables_filter{
		display: none;
	}
</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

	google.charts.load('current', {
	  packages: ['bar', 'line', 'corechart', 'table']
	});
	
</script>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url) ?>
	
	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>
		
		<div class="ssis-content">

			<div class="dashboard-filter">
				<div class="filter-header row no-gutters align-items-center">
					<img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid" id="close-filter">
					<p class="h2 ml-3">Dashboard Filter</p>
				</div>
				<form method="GET" class="filter-body">
					<div id="showLocation">
						<p class="text-uppercase font-bold text-primary">select stores</p>
						<div class="row mt-3 store-form">

							<?php

								// Grab all stores
								$arrStores = grabStores();
								$arrStoreLocations = explode(',', $_SESSION['user_login']['store_location']);
								// Cycle through stores arra
								for ($i=0; $i < sizeOf($arrStores); $i++) { 

									if($arrStores[$i]['active'] == 'y') {
										if($_SESSION['user_login']['position'] == 'supervisor' && !in_array($arrStores[$i]['store_id'], $arrStoreLocations))  continue;

										$curStoreName = ucwords(str_replace("u.p.", "UP", str_replace("sm", "SM", str_replace("-", " ", $arrStores[$i]['store_name']))));
										$checked = checkFilter($arrStores[$i]['store_id']);

										echo 	'<div class="col-12 col-md-6 mb-2">
													<div class="d-flex align-items-center">
														<input class="sr-only checkbox" name="filterStores[]" type="checkbox" id="option'.$curStoreName.'" value="'.$arrStores[$i]['store_id'].'" '.$checked.'>
														<label for="option'.$curStoreName.'" class="custom_checkbox"></label>
														<label for="option'.$curStoreName.'" class="m-0 ml-2">'.$curStoreName.'</label>
													</div>
												</div>';

									};

								};

								// Echo select all option
								echo 	'<div class="col-12 col-md-6">
											<div class="d-flex align-items-center">
												<input class="sr-only checkbox" type="checkbox" id="optionAllStores" value="all-stores" '.( ($_GET['store'] == $arrStores[$i]['store_id'] ? "selected" : "") ).'>
												<label for="optionAllStores" class="custom_checkbox"></label>
												<label for="optionAllStores" class="m-0 ml-2" id="optionAllStoresLabel">ALL STORES</label>
											</div>
										</div>';

							?>

						</div>
					</div>
					<div class="mt-4" id="showDateTime">
						<p class="text-uppercase font-bold text-primary">Select Time Range</p>
						<div class="mt-3 row no-gutters">
							<div class="col-12 col-md-6">
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionYesterday" value="yesterday" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "yesterday" ) ? 'checked="checked"' : '' ?>>
										<label for="optionYesterday" class="custom_checkbox"></label>
										<label for="optionYesterday" class="m-0 ml-2">Yesterday</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionDay" value="day" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "day" || !isset($_GET["date"]) ) ? 'checked="checked"' : '' ?>>
										<label for="optionDay" class="custom_checkbox"></label>
										<label for="optionDay" class="m-0 ml-2">Today</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionWeek" value="week" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "week" ) ? 'checked="checked"' : '' ?>>
										<label for="optionWeek" class="custom_checkbox"></label>
										<label for="optionWeek" class="m-0 ml-2">This Week</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionMonth" value="month" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "month" ) ? 'checked="checked"' : '' ?>>
										<label for="optionMonth" class="custom_checkbox"></label>
										<label for="optionMonth" class="m-0 ml-2">This Month</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionPreviousMonth" value="pmonth" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "pmonth" ) ? 'checked="checked"' : '' ?>>
										<label for="optionPreviousMonth" class="custom_checkbox"></label>
										<label for="optionPreviousMonth" class="m-0 ml-2">Previous Month</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionYear" value="year" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "year" ) ? 'checked="checked"' : '' ?>>
										<label for="optionYear" class="custom_checkbox"></label>
										<label for="optionYear" class="m-0 ml-2">This Year</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionAllTime" value="all-time" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "all-time" ) ? 'checked="checked"' : '' ?>>
										<label for="optionAllTime" class="custom_checkbox"></label>
										<label for="optionAllTime" class="m-0 ml-2">All Time</label>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="d-flex align-items-center">
									<input class="sr-only checkbox" name="date" type="radio" id="optionCustom" value="custom" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "custom" ) ? 'checked="checked"' : '' ?>>
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
													$numDays = $arrMonthDays["January"];

												}
												else {

													$curMonth = date('F');
													$numDays = $arrMonthDays[$curMonth];

												};

											?>

											>

												<?php

													for ($i=1; $i < ($numDays + 1); $i++) { 

														$daySelect = "";

														if($sStartDay != "" && $sStartDay == $i) {

															$daySelect = " selected";

														};

														echo '<option value="'.$i.'"'.$daySelect.'>'.(sprintf("%02d", $i)).'</option>';
														
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
													$numDays = $arrMonthDays["January"];

												}
												else {

													$curMonth = date('F');
													$numDays = $arrMonthDays[$curMonth];

												};

											?>

											>

												<?php													    		

													for ($i=1; $i < ($numDays + 1); $i++) { 

														$daySelect = "";

														if($sEndDay != "" && $sEndDay == $i) {

															$daySelect = " selected";

														}
														else if($sEndDay == "" && $sTimeDay == $i) {

															$daySelect = " selected";

														};		

														echo '<option value="'.$i.'"'.$daySelect.'>'.(sprintf("%02d", $i)).'</option>';
														
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
						</div>
					</div>

					<div class="text-center mt-5 pb-5">
						<button class="btn btn-primary" type="submit">Submit</button>
						<?php if(isset($_GET['date'])) { ?>

							<a href="/studios/dashboard/studios" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline d-lg-none">
								<button class="btn btn-danger" type="button">reset filter</button>
							</a>

						<?php }; ?>
					</div>
				</form>
			</div>
		
			<div class="d-flex no-gutters align-items-center" id="data-filter">
				<div class="col">
					<div class="d-flex align-items-center">
						<img src="<?= get_url('images/icons/icon-dashboard.png') ?>" alt="dashboard" class="img-fluid d-none d-md-block">
						<section class="ml-0 ml-md-3">
							<p class="h3 font-bold">Showing Data</p>
							<p class="text-secondary mt-2"><?= $date_title ?></p>
						</section>
					</div>
				</div>
				<?php if(isset($_GET['date'])) { ?>

					<a href="/studios/dashboard/studios" class="mr-3 d-none d-lg-block">
						<button class="btn btn-danger" type="button">reset filter</button>
					</a>

				<?php }; ?>
				<a href="#" id="toggle-filter"><img src="<?= get_url('images/icons/icon-filter.png') ?>" width="30" alt="filter" class="img-fluid"></a>
			</div>

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Studios Packages</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>
			</div>

			<div class="row align-items-stretch no-gutters" id="top-data-preview">

				<!-- Loader for Top Blocks Data -->
				<div id="data-loader-top-blocks"></div>

				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-items-sold"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Items Sold</p>
							</section>
							<img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-top-selling-item"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Top Selling Frame</p>
							</section>
							<img src="<?= get_url('images/icons/icon-top-selling.png') ?>" alt="top selling item" class="img-fluid">
						</div>
					</div>
				</div>
				<!-- <div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-top-selling-item-lens"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Top Selling Lens</p>
							</section>
							<img src="<?= get_url('images/icons/icon-top-selling.png') ?>" alt="top selling item" class="img-fluid">
						</div>
					</div>
				</div> -->
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-basket-size"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Sales per Unit</p>
							</section>
							<img src="<?= get_url('images/icons/icon-basket.png') ?>" alt="basket size" class="img-fluid">
						</div>
					</div>
				</div>

				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-total-category-specs"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Total Sales</p>
							</section>
                            <img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
			</div>

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Merch</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>
			</div>

			<div class="row align-items-stretch no-gutters" id="top-data-preview">

				<!-- Loader for Top Blocks Data -->
				<div id="data-loader-top-blocks"></div>

				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-merch-items-sold"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Items Sold</p>
							</section>
							<img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-top-selling-merch-item"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Top Selling Merch</p>
							</section>
							<img src="<?= get_url('images/icons/icon-top-selling.png') ?>" alt="top selling item" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-merch-basket-size"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Sales per Unit</p>
							</section>
							<img src="<?= get_url('images/icons/icon-basket.png') ?>" alt="basket size" class="img-fluid">
						</div>
					</div>
				</div>

				
                <div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-total-category-merch"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Total Sales</p>
							</section>
                            <img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
			</div>

			<!-- <hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Services</p>
						</section>
					</div>
				</div>
			</div>

			<div class="row align-items-stretch no-gutters" id="top-data-preview">

				<div id="data-loader-top-blocks"></div>

				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-services-items-sold"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Items Sold</p>
							</section>
							<img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-top-selling-services-item"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Top Selling Service</p>
							</section>
							<img src="<?= get_url('images/icons/icon-top-selling.png') ?>" alt="top selling item" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-services-basket-size"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Sales per Unit</p>
							</section>
							<img src="<?= get_url('images/icons/icon-basket.png') ?>" alt="basket size" class="img-fluid">
						</div>
					</div>
				</div>



				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-services-total"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Total Sales</p>
							</section>
							<img src="<?= get_url('images/icons/icon-basket.png') ?>" alt="basket size" class="img-fluid">
						</div>
					</div>
				</div>
				
			</div> -->

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Grab and Go</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>
			</div>

			<div class="row align-items-stretch no-gutters" id="top-data-preview">

				<!-- Loader for Top Blocks Data -->
				<div id="data-loader-top-blocks"></div>

				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-antirad-items-sold"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Items Sold</p>
							</section>
							<img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-top-selling-antirad-item"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Top Selling Grab and Go</p>
							</section>
							<img src="<?= get_url('images/icons/icon-top-selling.png') ?>" alt="top selling item" class="img-fluid">
						</div>
					</div>
				</div>
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-antirad-sales-per-unit"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Sales per Unit</p>
							</section>
							<img src="<?= get_url('images/icons/icon-basket.png') ?>" alt="basket size" class="img-fluid">
						</div>
					</div>
				</div>

				
                <div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-total-category-antirad"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Total Sales</p>
							</section>
                            <img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
			</div>

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Transaction Count</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>
			</div>

			<div class="row align-items-stretch no-gutters" id="top-data-preview">

				<!-- Loader for Top Blocks Data -->
				<div id="data-loader-top-blocks"></div>

				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-transaction-count"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Total Transaction Count</p>
							</section>
							<img src="<?= get_url('images/icons/icon-eyeglass.png') ?>" alt="frame" class="img-fluid">
						</div>
					</div>
				</div>
			
				<div class="col top-data col-or-3">
					<div class="custom-card lg">
						<div class="d-flex align-items-end justify-content-between">
							<section>
								<p class="h2 font-bold" id="load-transaction-basket-count"><span style="font-size: 12px;">Calculating...</span>&nbsp;</p>
								<p class="mt-3">Basket size</p>
							</section>
							<img src="<?= get_url('images/icons/icon-basket.png') ?>" alt="basket size" class="img-fluid">
						</div>
					</div>
				</div>
			</div>

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				
				<div class="col-12 col-md-auto">					
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Total Revenue</p>
							<!-- <p class="text-secondary mt-1">Based on gross total income for given date range</p> -->
						</section>
					</div>
					<div class="d-flex align-items-center">
						<select class="form-control select" id="sort-select">
							<option selected disabled>Sort</option>
							<option value="highest-to-lowest">Highest to Lowest</option>
							<option value="lowest-to-highest">Lowest to Highest</option>
						</select>
					</div>
				</div>

				<!-- Loader for Revenue Data -->
				<div id="data-loader-revenue"></div>

				<p class="h2 font-bold text-success mt-2 mt-md-0" id="load-total-revenue">&#8369;0</p>

			</div>
			<div id="total-revenue" class="custom-card lg">

				<?php

					$breakdownH = 1470;

					if(isset($_GET['filterStores'])) {

						$breakdownH = 40 * sizeOf($_GET['filterStores']);

					};

				?>

				<!-- Loader for Revenue Breakdown Data -->
				<div id="bar_chart_rev_breakdown" style="height: auto;"></div>				
				<div id="revenue-breakdown-cover"></div>

			</div>

			<hr class="spacing">

			<?php if(!isset($_GET['date']) || $_GET['date'] == 'day' || $_GET['date'] == 'yesterday') { ?>

			<div class="custom-card-header">
				<section>
					<p class="h3 font-bold">Revenue by Hour</p>
					<!-- <p class="text-secondary mt-1"></p> -->
				</section>
			</div>
			<div id="data-revenue-hourly" class="custom-card lg">

				<div id="bar_chart_rev_hourly_sales" style="height: 400px;"></div>

			</div>

			<hr class="spacing">

			<?php } ?>

			<?php if(isset($_GET['date']) && ($_GET['date'] == 'month' || $customMonth || $_GET['date'] == 'pmonth')  ) { ?>

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Daily Sales</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>

				<!-- Loader for Revenue Data -->
				<div id="data-loader-daily-sales"></div>

			</div>
			<div id="total-revenue" class="custom-card lg">

				<!-- Loader for Revenue Daily Sales Data -->
				<div id="bar_chart_rev_daily_sales" style="height: 400px;"></div>

			</div>

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Daily Transactions</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>

			</div>
			<div id="daily-transactions" class="custom-card lg">

				<!-- Loader for Revenue Breakdown Data -->
				<div id="line_chart_daily_transactions" style="height: 400px;"></div>

			</div>

			<hr class="spacing">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Weekly Sales</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>

			</div>
			<div id="weekly-sales" class="custom-card lg">

				<!-- Loader for Revenue Breakdown Data -->
				<div id="line_chart_weekly_sales" style="height: 400px;"></div>

			</div>

			<hr class="spacing">

			<?php } ?>

			<div class="row align-items-stretch">
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Age Group</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div id="data-age-group" class="custom-card lg">

						<!-- Loader for Customer Data -->
						<div id="data-loader-customers-info"></div>

						<div id="pie_chart_age_group" style="width: 100%; height: 400px;"></div>

					</div>
				</div>	
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Age Range</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div id="data-age" class="custom-card lg">
						<div id="line_chart_age" style="width: 100%; height: 400px;"></div>
					</div>										
				</div>			
			</div>

			<hr class="spacing">

			<div class="row align-items-stretch">
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Customer Type</p>
							<!-- <p class="text-secondary mt-1">Customers based on gender</p> -->
						</section>
					</div>
					<div id="data-customer-types" class="custom-card lg">
						<div>    				
							<div class="row align-items-center justify-content-between">
		    					<div class="col-6 fillUP-container">
									<div id="fillUP">
										<div class="fill" id="newCustomer">
											<p>Calculating...</p>
										</div>
										<div class="fill" id="recurringCustomer">
											<p>Calculating...</p>
										</div>
										<div class="fill" id="guestCustomer">
											<p>Calculating...</p>
										</div>
									</div>
								</div>
								<div class="col-6 list-container">
									<div class="list">
										<p><span id="list-newCustomer"></span> New</p>
										<p><span id="list-recurringCustomer"></span> Existing</p>
										<p><span id="list-guestCustomer"></span> Guest</p>
									</div>								
								</div>
							</div>
						</div>	
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Gender Range</p>
							<!-- <p class="text-secondary mt-1">Customers based on gender</p> -->
						</section>
					</div>
					<div id="data-gender" class="custom-card lg">
						<div>    				
							<div class="row align-items-center justify-content-between">
		    					<div class="col-6 fillUP-container">
									<div id="fillUP">
										<div class="fill" id="male">
											<p>Calculating...</p>
										</div>
										<div class="fill" id="female">
											<p>Calculating...</p>
										</div>
										<div class="fill" id="guest">
											<p>Calculating...</p>
										</div>
										<div class="fill" id="na">
											<p>Calculating...</p>
										</div>
									</div>
								</div>
								<div class="col-6 list-container">
									<div class="list">
										<p><span id="list-male"></span> Male</p>
										<p><span id="list-female"></span> Female</p>
										<p><span id="list-guest"></span> Guest</p>
										<p><span id="list-na"></span> N/A</p>
									</div>								
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>

			<hr class="spacing">

			<div class="custom-card-header">
				<section>
					<div class="row">
						<div class="col-lg-9">
							<span class="h3 font-bold">
								Guest Purchases
							</span>
						</div>
						<div class="col-lg-3 d-flex justify-content-between">
							<input type="text" class="form-control" placeholder="Search" id="search_guest" value="">
							<!-- <input type="button" class="btn btn-primary ml-2" id="download_guest" value="Download"> -->
						</div>
					</div>	
					<!-- <p class="text-secondary mt-1">Based on items sold per SKU</p> -->
				</section>
			</div>
			<div class="custom-card p-0">
				<div class="table-responsive">
					<table class="table table-bordered tbl-guest">
						<thead class="table-header-black">
							<tr class="row100 head">
								<th class="cell100 small">#</th>
								<th class="cell100 small">ITEM DESCRIPTION</th>
								<th class="cell100 small">QTY</th>
								<th class="cell100 small">VALUE</th>
								<th class="cell100 small">TOTAL VALUE</th>
							</tr>
						</thead>
						<tbody id="load-item-guest">
							<tr>
								<td colspan="5"><center>Loading Data...</center></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<hr class="spacing">

			<div class="row align-items-stretch">				
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Customer Locations (PH)</p>
							<!-- <p class="text-secondary mt-1">Customers based on location</p> -->
						</section>
					</div>
					<div id="data-location" class="custom-card lg">
						<div id="donut_chart_locations" style="height: 400px; overflow: hidden;"></div>
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Customer Locations (INT)</p>
							<!-- <p class="text-secondary mt-1">Customers based on location</p> -->
						</section>
					</div>
					<div id="data-location-international" class="custom-card lg">
						<div id="donut_chart_locations_international" style="height: 400px; overflow: hidden;"></div>
					</div>
				</div>
			</div>

			<!-- <hr class="spacing">

			<div class="row align-items-stretch">
				
			</div> -->
			
			<hr class="spacing">

			<div class="custom-card-header">
				<section>
					<div class="row">
						<div class="col-lg-9">
							<span class="h3 font-bold">
								Studios Frames Sold
							</span>
						</div>
						<div class="col-lg-3">
							<input type="text" class="form-control" placeholder="Search" id="search_specs_sold" value="">
						</div>
					</div>	
					<!-- <p class="text-secondary mt-1">Based on items sold per SKU</p> -->
				</section>
			</div>
			<div id="data-revenue" class="custom-card p-0">
				<div id="table_div" style="width: 100%; max-height: 750px; overflow-y: scroll;">
					<table class="table table-bordered tbl-specs-sold">
						<thead class="table-header-black">
							<tr class="row100 head">
								<th class="cell100 small"></th>
								<th class="cell100 small">FRAME</th>
								<th class="cell100 small">SKU</th>
								<th class="cell100 small">FRAMES SOLD</th>
							</tr>
						</thead>
						<tbody id="load-item-table">
							<tr>
								<td colspan="4"><center>Loading Data...</center></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<hr class="spacing">

			<div class="custom-card-header">
				<section>
					<div class="row">
						<div class="col-lg-9">
							<span class="h3 font-bold">
								Merch Sold
							</span>
						</div>
						<div class="col-lg-3">
							<input type="text" class="form-control" placeholder="Search" id="search_merch_sold" value="">
						</div>
					</div>	
					<!-- <p class="text-secondary mt-1">Based on items sold per SKU</p> -->
				</section>
			</div>
			<div id="data-revenue" class="custom-card p-0">
				<div id="table_div" style="width: 100%; max-height: 750px; overflow-y: scroll;">
					<table class="table table-bordered merch-sold">
						<thead class="table-header-black">
							<tr class="row100 head">
								<th class="cell100 small"></th>
								<th class="cell100 small">ITEM</th>
								<th class="cell100 small">SKU</th>
								<th class="cell100 small">ITEMS SOLD</th>
							</tr>
						</thead>
						<tbody id="load-item-table-merch">
							<tr>
								<td colspan="4"><center>Loading Data...</center></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<hr class="spacing">

			<div class="custom-card-header">
				<section>
					<div class="row">
						<div class="col-lg-9">
							<span class="h3 font-bold">
								Grab and Go Sold
							</span>
						</div>
						<div class="col-lg-3">
							<input type="text" class="form-control" placeholder="Search" id="search_antirad_sold" value="">
						</div>
					</div>
				</section>
			</div>
			<div id="data-revenue" class="custom-card p-0">
				<div id="table_div" style="width: 100%; max-height: 750px; overflow-y: scroll;">
					<table class="table table-bordered antirad-sold">
						<thead class="table-header-black">
							<tr class="row100 head">
								<th class="cell100 small"></th>
								<th class="cell100 small">ITEM</th>
								<th class="cell100 small">SKU</th>
								<th class="cell100 small">ITEMS SOLD</th>
							</tr>
						</thead>
						<tbody id="load-item-table-antirad">
							<tr>
								<td colspan="4"><center>Loading Data...</center></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<hr class="spacing">

			<!-- Loader for frame data Data -->
			<div id="data-loader-frame-data-blocks"></div>

			<div class="row align-items-stretch">
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Collections</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div class="custom-card lg">
						<div id="donut_chart_collections" style="height: 400px; overflow: hidden;"></div>
					</div>				
				</div>
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Width</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div class="custom-card lg">
						<div id="donut_chart_size" style="height: 400px; overflow: hidden;"></div>
					</div>				
				</div>
			</div>

			<hr class="spacing">

			<div class="row align-items-stretch">
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">General Colors</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div  class="custom-card lg">
						<div id="donut_chart_general_color" style="height: 400px; overflow: hidden;"></div>
					</div>
				</div>
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Styles</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div class="custom-card lg">
						<div id="donut_chart_shape" style="height: 400px; overflow: hidden;"></div>
					</div>				
				</div>
			</div>

			<hr class="spacing">

			<div class="row align-items-stretch">
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Materials</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div class="custom-card lg">
						<div id="donut_chart_material" style="height: 400px; overflow: hidden;"></div>
					</div>				
				</div>
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Finish</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div class="custom-card lg">
						<div id="donut_chart_finish" style="height: 400px; overflow: hidden;"></div>
					</div>				
				</div>				
			</div>

			<hr class="spacing">

			<div class="row align-items-stretch">
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Product Seasonality</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div  class="custom-card lg">
						<div id="donut_chart_product_seasonality" style="height: 400px; overflow: hidden;"></div>
					</div>
				</div>
			</div>

			<hr class="spacing">

			<div class="row align-items-stretch" style="display: none;">				
				<div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Group / Category</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
					<div  class="custom-card lg">
						<div id="donut_chart_category" style="height: 400px; overflow: hidden;"></div>
					</div>
				</div>
				<!-- <div class="col-12 col-lg-6">
					<div class="custom-card-header">
						<section>
							<p class="h3 font-bold">Sizes</p>
						</section>
					</div>
					<div  class="custom-card lg">
						<div id="donut_chart_size" style="height: 400px; overflow: hidden;"></div>
					</div>
				</div>	 -->		
			</div>

			<hr class="spacing">

		</div>

	</div>

</div>

<script src="/js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/dataTables/datatables.min.css"/>
<script type="text/javascript" src="/js/dataTables/datatables.min.js"></script>
<script type="text/javascript">	
				
	$(document).ready(function() {		

		////////////////////////////////////////// FUNCTIONS

		function round(value, precision) {

			var multiplier = Math.pow(10, precision || 0);

			return Math.round(value * multiplier) / multiplier;

		};

		function fillUp(value_a, value_class_a, value_b, value_class_b) {

			var fillFull = value_a + value_b,
				fUPA = round( ((value_a / fillFull) * 100), 1 ),
				fUPB = round( ((value_b / fillFull) * 100), 1 );

			setTimeout(function() {
				
				$('#' + value_class_a + ' p').html('- ' + fUPA + '%');
				$('#' + value_class_b + ' p').html('- ' + fUPB + '%');

				$('#list-' + value_class_a).html(value_a);
				$('#list-' + value_class_b).html(value_b);

				// Animate the bar						
				$('#' + value_class_a).animate({

					'height': fUPA + '%'

				}, 1000);
				$('#' + value_class_b).animate({

					'height': fUPB + '%'

				}, 1000);

			}, 2000);	

		};

		function fillUpCustomerType(value_a, value_class_a, value_b, value_class_b, value_c, value_class_c, value_d = null, value_class_d = null) {

			var fillFull = value_a + value_b + value_c + value_d,
				fUPA = round( ((value_a / fillFull) * 100), 1 ),
				fUPB = round( ((value_b / fillFull) * 100), 1 );
				fUPC = round( ((value_c / fillFull) * 100), 1 );
				fUPD = round( ((value_d / fillFull) * 100), 1 );

			setTimeout(function() {
				
				$('#' + value_class_a + ' p').html('- ' + fUPA + '%');
				$('#' + value_class_b + ' p').html('- ' + fUPB + '%');
				$('#' + value_class_c + ' p').html('- ' + fUPC + '%');
				$('#' + value_class_d + ' p').html('- ' + fUPD + '%');

				$('#list-' + value_class_a).html(value_a);
				$('#list-' + value_class_b).html(value_b);
				$('#list-' + value_class_c).html(value_c);
				$('#list-' + value_class_d).html(value_d);

				// Animate the bar						
				$('#' + value_class_a).animate({

					'height': fUPA + '%'

				}, 1000);
				$('#' + value_class_b).animate({

					'height': fUPB + '%'

				}, 1000);
				$('#' + value_class_c).animate({

					'height': fUPC + '%'

				}, 1000);
				$('#' + value_class_d).animate({

					'height': fUPD + '%'

				}, 1000);

			}, 2000);	

		};

		function fillRight(value_a, value_class_a, value_b, value_class_b, value_c, value_class_c, value_d, value_class_d, value_e, value_class_e, value_f, value_class_f, value_g, value_class_g) {

			var fillFull = value_a + value_b + value_c + value_d + value_e + value_f + value_g,
				fUPA = round( ((value_a / fillFull) * 100), 3 ),
				fUPB = round( ((value_b / fillFull) * 100), 3 ),
				fUPC = round( ((value_c / fillFull) * 100), 3 ),
				fUPD = round( ((value_d / fillFull) * 100), 3 ),
				fUPE = round( ((value_e / fillFull) * 100), 3 ),
				fUPF = round( ((value_f / fillFull) * 100), 3 ),
				fUPG = round( ((value_g / fillFull) * 100), 3 );

			setTimeout(function() {
				
				$('#' + value_class_a + ' p').html('- ' + fUPA + '%');
				$('#' + value_class_b + ' p').html('- ' + fUPB + '%');
				$('#' + value_class_c + ' p').html('- ' + fUPC + '%');
				$('#' + value_class_d + ' p').html('- ' + fUPD + '%');
				$('#' + value_class_e + ' p').html('- ' + fUPE + '%');
				$('#' + value_class_f + ' p').html('- ' + fUPF + '%');
				$('#' + value_class_g + ' p').html('- ' + fUPG + '%');

				$('#list-' + value_class_a).html(value_a);
				$('#list-' + value_class_b).html(value_b);
				$('#list-' + value_class_c).html(value_c);
				$('#list-' + value_class_d).html(value_d);
				$('#list-' + value_class_e).html(value_e);
				$('#list-' + value_class_f).html(value_f);
				$('#list-' + value_class_g).html(value_g);

				// Animate the bar						
				$('#' + value_class_a).animate({

					'width': fUPA + '%'

				}, 1000);
				$('#' + value_class_b).animate({

					'width': fUPB + '%'

				}, 1000);
				$('#' + value_class_c).animate({

					'width': fUPC + '%'

				}, 1000);
				$('#' + value_class_d).animate({

					'width': fUPD + '%'

				}, 1000);
				$('#' + value_class_e).animate({

					'width': fUPE + '%'

				}, 1000);
				$('#' + value_class_f).animate({

					'width': fUPF + '%'

				}, 1000);
				$('#' + value_class_g).animate({

					'width': fUPG + '%'

				}, 1000);

			}, 2000);	

		};				    	  


		function formatNumber(num) {

  			return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')

		};

		////////////////////////////////////////// LOAD DATA

		<?php

			// Set stores GET parameter
			if(isset($_GET['filterStores'])) {

				$arrStores = $_GET['filterStores'];
				$storeGET = "&";

				for ($i=0; $i < sizeOf($arrStores); $i++) { 
		
					$storeGET .= "filterStores%5B%5D=".$arrStores[$i];

					if($i < sizeOf($arrStores) - 1) {

						$storeGET .= "&";

					}

				};

			}
			else {

				$storeGET = "";

			};

			// Set date GET parameter
			if(isset($_GET['date']) && $_GET['date'] != 'custom') {

				$dateGET = "date=".$_GET['date'];

			}
			elseif(isset($_GET['date']) && $_GET['date'] == 'custom' && $customMonth) {

				$dateGET = "date=custom&data_range_start_month=".$_GET['data_range_start_month']."&data_range_start_day=".$_GET['data_range_start_day']."&data_range_start_year=".$_GET['data_range_start_year']."&data_range_end_month=".$_GET['data_range_end_month']."&data_range_end_day=".$_GET['data_range_end_day']."&data_range_end_year=".$_GET['data_range_end_year'];

			}
			elseif(isset($_GET['data_range_start_month'])) {

				$dateGET = "data_range_start_month=".$_GET['data_range_start_month']."&data_range_start_day=".$_GET['data_range_start_day']."&data_range_start_year=".$_GET['data_range_start_year']."&data_range_end_month=".$_GET['data_range_end_month']."&data_range_end_day=".$_GET['data_range_end_day']."&data_range_end_year=".$_GET['data_range_end_year'];

			}
			else {

				$dateGET = "";

			};

		?>

		var loadItemsSold = 0,
			loadSoldRaw   = 0,
			loadBestItem  = "",			
			totalRevenueRaw = 0;

		var loadItemsSoldStudios   = 0,
			loadSoldRawStudios     = 0,
			loadBestItemStudios    = "",			
			totalRevenueRawStudios = 0;

		var loadItemsSoldMerch   = 0,
			loadSoldRawMerch     = 0,
			loadBestItemMerch    = "",			
			totalRevenueRawMerch = 0;

		var loadItemsSoldServices   = 0,
			loadSoldRawServices     = 0,
			loadBestItemServices    = "",			
			totalRevenueRawServices = 0;

		var TotalBagSize="0";

		// TOP BLOCKS
		$('#data-loader-top-blocks').load('/includes/dashboard_studios/calculations_frames.php?<?= $dateGET ?><?= $storeGET ?>', function() {

			//// SPECS

			// SET LOADED DATA
			loadItemsSold = $('#data-top-blocks').data('items-sold');
			loadSoldRaw   = $('#data-top-blocks').data('items-sold-raw');			
			loadBestItem  = $('#data-top-blocks').data('best-item');
			loadBestLens  = $('#data-top-blocks').data('best-lens');

			// ITEMS SOLD
			$('#load-items-sold').html(loadItemsSold);
			$('#load-top-selling-item').html(loadBestItem);
			$('#load-top-selling-item-lens').html(loadBestLens);

			// WITH / WITHOUT PRX
			// var totalFramesWithPRX    = $('#data-top-blocks-prx').data('with-prx'),
			// 	totalFramesWithoutPRX = $('#data-top-blocks-prx').data('without-prx');			

			// fillUp(totalFramesWithPRX, "withPRX", totalFramesWithoutPRX, "withoutPRX");			

			// LOAD GOOGLE CHARTS
			//// STUDIIOS

			// SET LOADED DATA
			// loadItemsSoldStudios = $('#data-top-blocks-studios').data('studios-items-sold');
			// loadSoldRawStudios   = $('#data-top-blocks-studios').data('studios-items-sold-raw');			
			// loadBestItemStudios  = $('#data-top-blocks-studios').data('best-studios-item');

			// ITEMS SOLD
			// $('#load-studios-items-sold').html(loadItemsSoldStudios);
			// $('#load-top-selling-studios-item').html(loadBestItemStudios);

			//// MERCH

			// SET LOADED DATA
			loadItemsSoldMerch = $('#data-top-blocks-merch').data('merch-items-sold');
			loadSoldRawMerch   = $('#data-top-blocks-merch').data('merch-items-sold-raw');			
			loadBestItemMerch  = $('#data-top-blocks-merch').data('best-merch-item');

			// ITEMS SOLD
			$('#load-merch-items-sold').html(loadItemsSoldMerch);
			$('#load-top-selling-merch-item').html(loadBestItemMerch);

			//// SERVICES

			// SET LOADED DATA
			// loadItemsSoldServices = $('#data-top-blocks-services').data('services-items-sold');
			// loadSoldRawServices   = $('#data-top-blocks-services').data('services-items-sold-raw');			
			// loadBestItemServices  = $('#data-top-blocks-services').data('best-services-item');

			// ITEMS SOLD
			// $('#load-services-items-sold').html(loadItemsSoldServices);
			// $('#load-top-selling-services-item').html(loadBestItemServices);

			//// ANTIRAD

			// SET LOADED DATA
			loadItemsSoldAntiRad = $('#data-top-blocks-antirad').data('antirad-items-sold');
			loadSoldRawAntiRad = $('#data-top-blocks-antirad').data('antirad-items-sold-raw');			
			loadBestItemAntiRad = $('#data-top-blocks-antirad').data('best-antirad-item');

			// ITEMS SOLD
			$('#load-antirad-items-sold').html(loadItemsSoldAntiRad);
			$('#load-top-selling-antirad-item').html(loadBestItemAntiRad);

			loadRevenue();

		});

		// FRAME DATA
		$('#data-loader-frame-data-blocks').load('/includes/dashboard_studios/calculations_frames_data.php?<?= $dateGET ?>&function=grabFrameData<?= $storeGET ?>')

		// REVENUE
		function loadRevenue(){
			$('#data-loader-revenue').load('/includes/dashboard_studios/calculations_revenue.php?<?= $dateGET ?><?= $storeGET ?>', function() {

				// CALCULATE TOTAL			

				// STUDIOS
				// var totalRevenueStudios    = $('#studios-revenue-grand-total').data('grand-total');
				// var totalRevenueRawStudios = $('#studios-revenue-grand-total').data('grand-total-raw');			
				// var basketSizeStudios 	   = round( (totalRevenueRawStudios / loadSoldRawStudios), 2 );

				// $('#load-total-revenue-studios').html('&#8369;' + totalRevenueStudios);			
				// $('#load-studios-basket-size').html('&#8369;' + basketSizeStudios);

				// MERCH
				var totalRevenueMerch    = $('#merch-revenue-grand-total').data('grand-total');
				var totalRevenueRawMerch = $('#merch-revenue-grand-total').data('grand-total-raw');			
				var basketSizeMerch 	 = round( (totalRevenueRawMerch / loadSoldRawMerch), 2 );			

				$('#load-total-revenue-merch').html('&#8369;' + totalRevenueMerch);			
				$('#load-merch-basket-size').html('&#8369;' + basketSizeMerch);

				// SERVICES
				// var totalRevenueServices    = $('#services-revenue-grand-total').data('grand-total');
				// var totalRevenueRawServices = $('#services-revenue-grand-total').data('grand-total-raw');			
				// var basketSizeServices 	 = round( (totalRevenueRawServices / loadSoldRawServices), 2 );			

				// $('#load-total-revenue-services').html('&#8369;' + totalRevenueServices);			
				// $('#load-services-basket-size').html('&#8369;' + basketSizeServices);

				// ANTI RAD
				var totalRevenueAntiRad    = $('#antirad-revenue-grand-total').data('grand-total');
				var totalRevenueRawAntiRad = $('#antirad-revenue-grand-total').data('grand-total-raw');			
				var basketSizeAntiRad	 = round( (totalRevenueAntiRad / loadSoldRawAntiRad), 2 );

				// SPECS
				var totalRevenue    = $('#revenue-grand-total').data('grand-total');
				var totalRevenueRaw = $('#revenue-grand-total').data('grand-total-raw');
				var basketSize = round( ((totalRevenueRaw - totalRevenueRawMerch  - totalRevenueRawAntiRad) / loadSoldRaw), 2 );

				$('#load-total-revenue').html('&#8369;' + totalRevenue);			
				$('#load-basket-size').html('&#8369;' + basketSize);

				// TIMEOUTS
				setTimeout(function() {
					// console.log(loadItemsSold);
					// basketSize = round( ((totalRevenueRaw - totalRevenueRawStudios - totalRevenueRawMerch - totalRevenueRawServices- totalRevenueRawAntiRad) / (loadItemsSold) ), 2 );
					// $('#load-basket-size').html('&#8369;' + formatNumber(basketSize));

				

					// basketSizeMerch = round( (totalRevenueRawMerch / loadSoldRawMerch), 2 );
					// $('#load-merch-basket-size').html('&#8369;' + formatNumber(basketSizeMerch));

					// basketSizeServices = round( (totalRevenueRawServices / loadSoldRawServices), 2 );
					// $('#load-services-basket-size').html('&#8369;' + formatNumber(basketSizeServices));

	                totalSalesSpecs = round( ((totalRevenueRaw -  totalRevenueRawMerch - totalRevenueRawAntiRad) ), 2 );
	                $('#load-total-category-specs').html(formatNumber(totalSalesSpecs) + ' PHP');


				}, 1000);

				// setTimeout(function() {

				// 	basketSizeStudios = round( (totalRevenueRawStudios / loadSoldRawStudios), 2 );
				// 	$('#load-studios-basket-size').html('&#8369;' + formatNumber(basketSizeStudios));

				// 	totalSalesStudios = round( totalRevenueRawStudios, 2 );
	   //              $('#load-total-category-studios').html(formatNumber(totalSalesStudios) + ' PHP');


				// }, 2000);

				setTimeout(function() {

					// basketSize = round( ((totalRevenueRaw - totalRevenueRawStudios - totalRevenueRawMerch) / loadSoldRaw), 2 );
					// $('#load-basket-size').html('&#8369;' + formatNumber(basketSize));

					// basketSizeStudios = round( (totalRevenueRawStudios / loadSoldRawStudios), 2 );
					// $('#load-studios-basket-size').html('&#8369;' + formatNumber(basketSizeStudios));

				
				
	                
					basketSizeMerch = round( (totalRevenueRawMerch / loadSoldRawMerch), 2 );
					$('#load-merch-basket-size').html(formatNumber(basketSizeMerch) + ' PHP');

	            


	                totalSalesMerch =  round( (totalRevenueRawMerch ), 2 );
	                $('#load-total-category-merch').html(formatNumber(totalSalesMerch) + ' PHP');

				}, 3000);

				// setTimeout(function() {

				// 	// basketSize = round( ((totalRevenueRaw - totalRevenueRawStudios - totalRevenueRawMerch) / loadSoldRaw), 2 );	
				// 	// $('#load-basket-size').html('&#8369;' + formatNumber(basketSize));

				

				// 	basketSizeServices = round( (totalRevenueRawServices / loadSoldRawServices), 2 );
				// 	$('#load-services-basket-size').html('&#8369;' + formatNumber(basketSizeServices));

	   //              TotalSalesServices =  round( (totalRevenueRawServices ), 2 );
	   //              $('#load-services-total').html(formatNumber(TotalSalesServices) + ' PHP');

				// 	// TotalBagSize = round( ((totalRevenueRaw) / loadSoldRaw), 2 );	
				// 	// $('#load-transaction-basket-count').html('&#8369;' + formatNumber(TotalBagSize));


				// }, 4000);

				setTimeout(function() {

					// basketSize = round( ((totalRevenueRaw - totalRevenueRawStudios - totalRevenueRawMerch) / loadSoldRaw), 2 );
					// $('#load-basket-size').html('&#8369;' + formatNumber(basketSize));

					// basketSizeStudios = round( (totalRevenueRawStudios / loadSoldRawStudios), 2 );
					// $('#load-studios-basket-size').html('&#8369;' + formatNumber(basketSizeStudios));

				
				
	                
					basketSizeAntiRad = round( (totalRevenueRawAntiRad / loadSoldRawAntiRad), 2 );
					$('#load-antirad-sales-per-unit').html(formatNumber(basketSizeAntiRad) + ' PHP');

	            


	                totalSalesAntiRad =  round( (totalRevenueRawAntiRad ), 2 );
	                $('#load-total-category-antirad').html(formatNumber(totalSalesAntiRad) + ' PHP');

				}, 3000);


				setTimeout(function() {
					TotalBagSize = round( ((parseFloat(totalRevenue.replace(/,/g,''))) / transactionCount), 2 );	
					$('#load-transaction-basket-count').html('&#8369;' + formatNumber(TotalBagSize));


				}, 5000);

			});
		}
		

		// REVENUE BREAKDOWN
		$('#bar_chart_rev_breakdown').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabRevenueBreakdown<?= $storeGET ?>', function() {

			// Set highest store total
			var revBTotal = $('#revenue-breakdown-highest').data('total');			
			var revBID 	  = $('#revenue-breakdown-highest').data('store-id');

			// Highest total set as 100% width
			$('#dataBar' + revBID).css('width', '100%');			

			$('.data-bar').each(function() {

				if($(this).attr('id') != 'revenue-breakdown-highest') {

					var curTotal = $(this).data('store-total');
					var calcWidth = (curTotal / revBTotal) * 80;

					$(this).css('width', calcWidth + '%');

				};

			});			

		});

		let transactionCount = 0;
		$('#load-transaction-count').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabTransactionCount<?= $storeGET ?>', function(result) {
			transactionCount = result;
			$('#load-transaction-count').text(result);
		});

		$('#sort-select').on('change', function() {

			// Set current value
			var curSort = $(this).val();			

			$('#revenue-breakdown-cover').fadeIn(100);

			// Reload breakdown
			$('#bar_chart_rev_breakdown').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabRevenueBreakdown<?= $storeGET ?>&sort=' + curSort, function() {

				// Set highest store total
				var revBTotal = $('#revenue-breakdown-highest').data('total');			
				var revBID 	  = $('#revenue-breakdown-highest').data('store-id');

				// Highest total set as 100% width
				$('#dataBar' + revBID).css('width', '100%');			

				$('.data-bar').each(function() {

					if($(this).attr('id') != 'revenue-breakdown-highest') {

						var curTotal = $(this).data('store-total');
						var calcWidth = (curTotal / revBTotal) * 80;

						$(this).css('width', calcWidth + '%');

					};

				});	

				$('#revenue-breakdown-cover').fadeOut(100);		

			});

		});

		// Customers
		$('#data-loader-customers-info').load('/includes/dashboard_studios/calculations_customers.php?<?= $dateGET ?><?= $storeGET ?>', function() {

			// CUSTOMER TYPE BAR
			var numNew   	 = $('#data-customer-types').data('new-customer');
			var numRecurring = $('#data-customer-types').data('recurring-customer');
			var numGuest = $('#data-customer-types').data('guest-customer');

			fillUpCustomerType(numNew, "newCustomer", numRecurring, "recurringCustomer", numGuest, "guestCustomer");

			setTimeout(function() {
				// GENDER BAR
				var numMale    = $('#data-genders').data('male');
				var numFemale  = $('#data-genders').data('female');
				var numGuest   = $('#data-genders').data('guest');
				var numNA 	   = $('#data-genders').data('na');

				fillUpCustomerType(numMale, "male", numFemale, "female", numGuest, "guest", numNA, "na");
			},2000);

		});		

		let dt_SS, dt_specs, dt_merch, dt_services, dt_antirad, dt_guest;

		// ITEMS TABLES
		$('#load-item-table').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabBestFramesTable<?= $storeGET ?>',function(){

		    dt_SS = $('.tbl-specs-sold').DataTable({
			    "lengthChange": false,
			    "searching": true,
			    "ordering": false,
			    "info": false,
			    "paging": false,
		      	"columnDefs": [
			    	{ "searchable": false, "targets": 0 },
			    	{ "searchable": false, "targets": 3 }
			  	]
		    });

		});

		// $('#load-item-table-lenses').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabBestLensesTable<?= $storeGET ?>',function(){

		//     dt_specs = $('.tbl-lenses-sold').DataTable({
		// 	    "lengthChange": false,
		// 	    "searching": true,
		// 	    "ordering": false,
		// 	    "info": false,
		// 	    "paging": false,
		// 	    "columnDefs": [
		// 	    	{ "searchable": false, "targets": 0 },
		// 	    	{ "searchable": false, "targets": 3 }
		// 	  	]
		//     });

		// });

		$('#load-item-table-merch').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabBestFramesTableMerch<?= $storeGET ?>',function(){

		    dt_merch = $('.merch-sold').DataTable({
			    "lengthChange": false,
			    "searching": true,
			    "ordering": false,
			    "info": false,
			    "paging": false,
			    "columnDefs": [
			    	{ "searchable": false, "targets": 0 },
			    	{ "searchable": false, "targets": 3 }
			  	]
		    });

		});

		// $('#load-item-table-services').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabBestServicesTable<?= $storeGET ?>',function(){

		//     dt_services = $('.services-sold').DataTable({
		// 	    "lengthChange": false,
		// 	    "searching": true,
		// 	    "ordering": false,
		// 	    "info": false,
		// 	    "paging": false,
		// 	    "columnDefs": [
		// 	    	{ "searchable": false, "targets": 0 },
		// 	    	{ "searchable": false, "targets": 3 }
		// 	  	]
		//     });

		// });

		$('#load-item-table-antirad').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabBestFramesTableAntiRad<?= $storeGET ?>',function(){

		    dt_antirad = $('.antirad-sold').DataTable({
			    "lengthChange": false,
			    "searching": true,
			    "ordering": false,
			    "info": false,
			    "paging": false,
			    "columnDefs": [
			    	{ "searchable": false, "targets": 0 },
			    	{ "searchable": false, "targets": 3 }
			  	]
		    });

		});

		$('#load-item-guest').load('/includes/dashboard_studios/functions.php?<?= $dateGET ?>&function=grabGuestTable<?= $storeGET ?>',function(){

		    dt_guest = $('.tbl-guest').DataTable({
			    "lengthChange": false,
			    "searching": true,
			    "ordering": false,
			    "info": false,
			    "paging": false,
		      	"columnDefs": [
			    	{ "searchable": false, "targets": 0 },
			    	{ "searchable": false, "targets": 3 }
			  	]

		    });

		});

		// $('#download_details').click(function(){
		// 		window.open('/includes/dashboard_daily_login/functions.php?<?= $dateGET ?>&function=grabPromoCodesTableCSV<?= $storeGET ?>');
		// });

		
		$("#search_guest").keyup(function(){
			dt_guest.search($(this).val()).draw();
		});

		$("#search_specs_sold").keyup(function(){
			dt_SS.search('');
			dt_SS.search($(this).val()).draw();
		});

		// $("#search_lenses_sold").keyup(function(){
		// 	dt_specs.search('');
		// 	dt_specs.search($(this).val()).draw();
		// });

		$("#search_merch_sold").keyup(function(){
			dt_merch.search('');
			dt_merch.search($(this).val()).draw();
		});

		// $("#search_services_sold").keyup(function(){
		// 	dt_services.search('');
		// 	dt_services.search($(this).val()).draw();
		// });

		$("#search_antirad_sold").keyup(function(){
			dt_antirad.search('');
			dt_antirad.search($(this).val()).draw();
		});


		var frameFontW = $('.frame-name').width();
		var frameContW = $('#top-sold > div').width();

		if(frameFontW > frameContW) {

			$('.frame-name').css('font-size', '19px');

		}				
		
		var allStores = false;

		$('#optionAllStores').click(function() {

			if(allStores) {

				$('.store-form').find('input').attr('checked', false);
				allStores = false;

			}
			else {

				$('.store-form').find('input').attr('checked', true);
				allStores = true;

			};

		});

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

			$('#data_range_start_day').load('/includes/date_select_dropdown.php?month=' + thisMonth);

		});

		$('#data_range_end_month').change(function() {

			var thisMonth = $(this).val();

			$('#data_range_end_day').load('/includes/date_select_dropdown.php?month=' + thisMonth);

		});

	});

</script>

<?= get_footer() ?>

<?php

	require $sDocRoot."/includes/notification.php";	

} ?>