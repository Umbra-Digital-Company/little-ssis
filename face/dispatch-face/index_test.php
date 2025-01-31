<?php 
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////
$page_url = 'dispatch-face';
$page = 'dispatch-face';
$filter_page = 'dispatch_face';
$group_name = 'sunnies_face';
////////////////////////////////////////////////
// Set store for Admin
if($_SESSION['user_login']['userlvl'] == 1 && $_SESSION['user_login']['position'] == 'admin') {

	if(isset($_GET['store'])) {

		$_SESSION['store_code'] = $_GET['store'];
		$_SESSION['user_login']['store_code'] = $_GET['store'];

	}
	else {

		$_SESSION['store_code'] = '789';
		$_SESSION['user_login']['store_code'] = '789';

	};

};

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/face/dispatch-face/dispatch_receivable.php";
require $sDocRoot."/face/includes/navbar_face_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/face/includes/grab_customer_list_dispatch_face.php";
// require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/face/includes/grab_stores_face.php";
// require $sDocRoot."/includes/grab_latest_chat.php";
include $sDocRoot.'/user-management/edit-user/user_department.php';

$_SESSION['permalink'] =$filter_page; 

// Grab Store
$storeName = "";



// $MergeStores=array_merge($arrStore, $arrStudiosStore);


// echo $MergeStores[110]['store_id'];

// echo "<pre>";
// print_r($MergeStores);

for ($i=0; $i < sizeOf($arrStoresFace); $i++) { 

	if($arrStoresFace[$i]['store_id'] == $_SESSION['user_login']['store_code'] ) {

		$storeName = $arrStoresFace[$i]['store_name'];

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

	<title>Dispatch | SSIS</title>

	<!-- fonts link -->

	<!-- css files -->
	<?= $header_css; ?>

	<?= $favicon; ?>
	
	<style>
		html, .ssis-body, .filter-select{
			background-color: #B3A89B !important;
		}
		.filter-select{
			color: #000 !important;
		}
		.ssis-2 .section-header > div h2, .ssis-2 .section-header > div p{
			color: #000 !important;
		}

		.dispatch-home {
			margin-top: 105px;
			padding-bottom: 50px;
		}		

		.dispatch-home .search-dispatch-list .form-control {
			margin: 0;
		}

		.dispatch-home .search-dispatch-list .btn {
			/*height: 40px;*/
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

		#lab_form {
			/*position: absolute;			
		    top: 0;*/
		    width: 100%;
		    /*overflow: scroll;*/
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
		  overflow: auto;
		}

		.table100 {
		  width: 100%;
		  position: relative;
		  border: 0;
		}

		.wrap-table100 {
			  border: 0;
			margin-top: 20px;
		}

		.wrap-table100.scroll {
			overflow-y: auto;
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
		  padding: 10px 30px 10px 15px;
		}

		.table100.ver1 .table100-firstcol td a {
			color: #000;
			border-bottom: 1px solid #808080;
			text-decoration: none !important;
		}

		.table100.ver1 .table100-firstcol td,
		.table100.ver1 .table100-nextcols td {
		  color: #000;
		  height: 90px;
		}

		.table100.ver1 .table100-nextcols td {
			padding: 10px 15px;
			border: 1px solid #e6e6e6;
		}


		.table100.ver1 tr {
		  border-bottom: 1px solid #e6e6e6;
		}

		.table100.ver1 .btn {
			font-size: 80%;
			width: 100%;
			padding: 10px 15px;
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

		.table-wrapper {
			position: relative;
			height: 100%;
			width: 100%;
		}

		.iremarks {
			position: absolute;
			top: -10px;
			right: -28px;
			font-size: 16px;
			border: 0 !important;
		}

		@media screen and (max-width: 1200px) {

			.dispatch-home .search-dispatch-list {
				width: 100%;
			}
			.dispatch-home .sare-btns {
				width: 100%;
			    text-align: right;
			    margin-top: 30px;
			}			

		}

		@media screen and (max-width: 768px) {

			#btnPackage {
				margin-top: 20px;
			}		

		}

	</style>



    <link rel="stylesheet" type="text/css" href="/css/navbar.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">

    <style>

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
		    padding-bottom: 50px;
		    width: 100%;
		    height: 350px;
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




		.filter-select {
			font-size: 13px;
		    padding: 0 15px;
		    margin: 0;
		    height: 50px;
		    width: 160px;
			color: #fff;
	    	background-color: #36482e;    	
		}

		#btnsubmit,
		#btnsubmit2 {
			width: 80px;
		}

		.lab-scrollable {
			position: relative;
			overflow-y: auto;
			max-height: 500px;
		}		
		.ssis-2 .total-orders h3 {		
			margin-bottom: 0;
			font-size: 14px;				
		}
		.ssis-2 .total-orders p {
			margin-bottom: 0;
			font-size: 28px;		
		}
		.ssis-2 .search-dispatch-list {
			float: end;
		}
		.ssis-2 .div-page-filter p {
			margin-bottom: 0;
		}
		.ssis-2 .div-page-filter select {
			margin-left: 10px;
		    margin-right: 10px;	    
		}
		.ssis-2 .div-page-filter select,
		.ssis-2 .div-page-filter .filter-page-move,
		.ssis-2 #btnsubmit,
		.ssis-2 #btnsubmit2 {
			padding: 15px 10px;
		    border-radius: 12px;
		    box-shadow: 0px 2px 4px -2px grey;
		    border: none;
		}
		.ssis-2 .div-page-filter .filter-page-move {
			width: 50px;
			background-color: #ffffff;
		}
		.ssis-2 .div-page-filter select:focus,
		.ssis-2 .div-page-filter select:active {
			outline: none;
			border: none;
		}
		.div-reset-filter {
			margin-left: 50px;
			padding-left: 10px;
			padding-right: 10px;
			border-radius: 50px;
		    color: #ffffff;
		    background-color: grey;
		    box-shadow: 0px 6px 10px -2px grey;
		    text-align: center;
		    cursor: pointer;
		}
		.div-reset-filter a,
		.div-reset-filter a .btn {
			color: #ffffff;
		}
		.div-reset-filter .btn:focus {
			outline: none;
			box-shadow: none;
		}

		form .wrap-table100 {
			border-radius: 25px;
			overflow: hidden;
			box-shadow: 0px 6px 10px -2px gray;
		}
		form .wrap-table100 tr.head th {
			background-color: #5f0000 !important;
			padding: 25px 30px;
		}

		#toggle-messenger {
			position: relative;
			height: 50px;
			width: 50px;
			cursor: pointer;
			background: #fff;
			-webkit-border-radius: 50px;
			-moz-border-radius: 50px;
			border-radius: 50px;
			box-shadow: 0px 3px 6px -2px rgba(0,0,0,0.25);
		}

		#toggle-messenger img {
			max-width: 26px;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-top: -13px;
			margin-left: -13px;
		}

		#toggle-messenger .notif {
			display: none;
			width: 16px;
			height: 16px;
			background: #DC3545;
			position: absolute;
			top: -3px;
			right: -3px;
			-webkit-border-radius: 16px;
			-moz-border-radius: 16px;
			border-radius: 16px;
		}
		#toggle-messenger .notif.new {
			display: block;
		}

		#chat-room {
			display: none;
			overflow: hidden;
			position: fixed;
			right: 15px;
			bottom: 15px;
			width: 300px;
			height: 550px;
			z-index: 700;
			background: #e6e6e6;
			-webkit-border-radius: 8px;
			-moz-border-radius: 8px;
			border-radius: 8px;
			box-shadow: 0px 3px 6px -2px rgba(0,0,0,0.25);
		}

		#chat-room .chat-head {
			background: #5F0000;
			height: 40px;
			padding: 10px;
		}

		#chat-room .chat-head #hide-chat-room {
			width: 15px;
			height: 15px;
			cursor: pointer;
			background: #e6e6e6;
			-webkit-border-radius: 15px;
			-moz-border-radius: 15px;
			border-radius: 15px;
			display: block;
		}

		#chat-room .chat-head img {
			max-width: 15px;
			display: block;
		}

		#chat-room .chat-head p {
			color: #fff;
		}

		#chat-room .chat-list {
			height:470px;
			overflow-y: auto;
		}

		#chat-room .chat-list .chat-item {
			padding: 10px; 
			cursor: pointer;
			position: relative;
			background: #fff;
			-webkit-transition: all .3s ease;
			-moz-transition: all .3s ease;
			transition: all .3s ease;
		}

		#chat-room .chat-list .chat-item:hover {
			background: #e6e6e6;
		}

		#chat-room .chat-list .chat-item:not(:last-of-type) {
			border-bottom: 1px solid #e6e6e6;
		}

		#chat-room .chat-list .chat-item .icon {
			height: 45px;
			width: 45px;
			position: relative;
			background: #d8d8d8;
			-webkit-border-radius: 45px;
			-moz-border-radius: 45px;
			border-radius: 45px;
		}

		#chat-room .chat-list .chat-item .icon p {
			font-size: 24px;
			position: absolute;
			left: 50%;
			top: 50%;
			color: #6c757d;
			-webkit-transform: translate(-50%,-50%);
			-moz-transform: translate(-50%,-50%);
			-ms-transform: translate(-50%,-50%);
			-o-transform: translate(-50%,-50%);
			transform: translate(-50%,-50%);
		}

		#chat-room .chat-list .chat-item .name p:last-of-type {
			font-size: 90%;
		}

		#chat-room .chat-list .chat-item .new-message {
			position: absolute;
			top: 5px;
			right: 5px;
		}

		#chat-room .chat-footer {
			position: relative;
		}

		#chat-room .chat-footer .form-control {
			height: 40px;
			padding: 0 10px;
			width: 100%;
			border-left: 0;
			border-bottom: 0;
			border-right: 0;
		}

		#chat-room .chat-footer #clear-search-chat {
			position: absolute;
			right: 5px;
			top: 50%;
			margin-top: -8px;
			width: 16px;
			height: 16px;
			cursor: pointer;
		}

		#chat-room .chhat-footer img {
			display: block;
			width: 16px;
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

				echo "<script>window.location = 'https://www.sunniessystems.com/'</script>";	
				
			}
			// else{ 

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
		
		<div id="ssis-content" class="dispatch-home">

		

			<section class="ssis-section">
				<div class="section-header row no-gutters align-items-top justify-content-between">
	                <div>
	                	<img class="img-fluid header-icon" src="/images/icons/white-dispatch-icon.png" />
	                    <h2><?= str_replace("S&r", "S&R", str_replace("Up Town Center", "UP Town Center", str_replace("Sm ", "SM ", str_replace("Mw ", "MW ", str_replace("Ali ", "ALI ", ucwords( strtolower($storeName) )))))); ?></h2>
	                    <p>Dispatch Page</p>
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

							<input type="text"name="search" class="search form-control col" id="search" placeholder="Search..." value="<?= $searchVal ?>">
							<button type="button" class="btn btn-gray" id="btnSearch"><i class="zmdi zmdi-search zmdi-hc-2x"></i></button>
						</div>
						<?php

							if(isset($_GET['search'])) {

								echo 	'<div class="row no-gutters justify-content-between align-items-center div-reset-filter">
											<a href="/face/dispatch-face/">
												<button type="button" class="btn">Reset Search</button>
											</a>
										</div>';

							};

						?>
					
		            </div>	
		            <div class="filter-well">

						<div class="row no-gutters align-items-center justify-content-between">									

							
							<?php
								if($_SESSION['user_login']['userlvl'] == 1 && $_SESSION['user_login']['position'] == 'admin') {

							?>

							<div class="col text-center">
								<select class="filter-select" id="filterStore">
									<option value="" <?php if(!isset($_GET['store'])) { echo "selected"; } ?>>Stores</option>>
									
									<?php 

										for($i=0; $i<sizeof($arrStoresFace); $i++){

											$storeName = str_replace("Sm ", "SM ", ucwords(str_replace('-',' ',$arrStoresFace[$i]["store_name"])));
											$storeID = $arrStoresFace[$i]['store_id'];

											if($_SESSION['user_login']['store_code'] == $storeID) { 

											 	$sel = " selected";

											}
											else {

											 	$sel = "";

											};

											echo '<option value="'.$storeID.'"'.$sel.'>FACE '.$storeName.'</option>';

										};

									?>

								</select>
							</div>

							<?php

								};

							?>

						</div>

		        	</div>
		        	<div class="section-inner">	

							

		        		<div class="row align-items-top justify-content-between no-gutters mt-80">
							<div class="total-orders">
								<h3>Total Orders:</h3>
								<p><?= number_format($totalNumberOfOrders, 0, '.', ',') ?></p>
							</div>									
							<div>
							
								<div class="search-dispatch-list row no-gutters justify-content-end align-items-center">
									<div class="row no-gutters justify-content-between align-items-center div-page-filter">

										<?php

											// Set values
											$numCurr = 0;
											$numPrev = 0;
											$numNext = 0;

											// Current Page
											if(isset($_GET['page'])) {

												$numCurr = $_GET['page'];

											}
											else {

												$numCurr = 1;

											};

											if($numCurr == 1) {

												$numPrev = 1;

											}
											else {

												$numPrev = $numCurr - 1;

											};								

											if($numCurr == $numberPages) {

												$numNext = $numberPages;

											}
											else {

												$numNext = $numCurr + 1;

											};

										?>

										<!-- <div class="btn filter-page-move" id="previous" data-page="<?= $numPrev ?>"><i class="zmdi zmdi-chevron-left zmdi-hc-2x"></i></div> -->
										<select class="filter-select-b" id="filterPage" data-current-page="<?= $numCurr ?>">
											
											<?php

												for ($i=0; $i < $numberPages; $i++) { 

													$selOpt = '';

													if($i + 1 == $page) {

														$selOpt = 'selected';

													};
												
													echo '<option '.$selOpt.' value="'.($i + 1).'">Page '.($i + 1).' of '.$numberPages.'</option>';

												};

											?>

										</select>
										<!-- <div class="btn filter-page-move" id="next" data-page="<?= $numNext ?>"><i class="zmdi zmdi-chevron-right zmdi-hc-2x"></i></div>									 -->

								</div>
								<!-- <div id="toggle-messenger" class="ml-2">
									<img src="/assets/images/icons/icon-chat-room-dispatch.png" alt="chat room" class="img-fluid">
									<span class="notif" data-count="2"></span>
								</div> -->
								<?php if($_SESSION['store_code'] != '150' &&  $_SESSION['store_code'] != '142'){ ?>
									<label class="btn btn-info ml-2" id="btnPackage" data-value="package" style="border-radius: 10px; display: none;">PACKAGE LIST</label>
								<?php } ?>
								<!-- <label for="save_action" class="btn btn-success ml-2" id="btnsubmit2" data-value="save">SAVE</label> -->
								<!-- <label for="reject_action" class="btn btn-danger ml-2" id="btnsubmit" data-value="reject">REJECT</label> -->
								
							</div>
						</div>

						<form method="post" name="lam_form" id="lab_form" >
				
							<div class="form-action text-right">

								<input type="button" class="sr-only" id="save_action"> 
								<!-- <input type="button" class="sr-only" id="reject_action"> -->
								<input type="hidden" value="" name="action_type" id="action_type_lab">
								<div class="remarks-overlay"></div>

							</div>

							<div class="wrap-table100 non-search">
								<div class="table100 ver1">

									<div class="table100-firstcol">

										<table cellpadding="0" cellspacing="0">
						 					<thead>
												<tr class="row100 head">
													<th class="cell100 small column1"><a href="./?page=dispatch&sort=atoz<?php if(isset($_GET['sort']) && (!isset($_GET['sort2']))){ ?>&sort2=ztoa<?php } ?>">Name</a></th>
												</tr>
											</thead>
											<tbody>

											<?php 

												$totalOrders = 0;

												for ( $i = 0; $i < sizeof($arrCustomer); $i++ ) {

													$id_message= CheckMessage($arrCustomer[$i]['orders_specs_id']);
													$totalOrders++;
									
													if($id_message){

														if($id_message != $_SESSION['id'] || $id_message == ""){

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
													<td nowrap class="cell100 small column1">
														<div class="row no-gutters align-items-center justify-content-start table-wrapper">
															<!-- <a href="#" id="iremarks_<?php echo $arrCustomer[$i]['orders_specs_id']; ?>" class="iremarks text-<?= $text ?>" data-toggle="modal" data-target="#informationRemarks" data-id="<?php echo $arrCustomer[$i]['orders_specs_id']; ?>"><i class="zmdi zmdi-info"></i></a> -->
															<?= $i + 1 ?>.&nbsp;<a href="customer/?profile_id=<?= $arrCustomer[$i]["profile_id"]?>&orderNo=<?= $arrCustomer[$i]["orders_specs_id"]?>" ><?= ucwords( $arrCustomer[$i]['first_name'] )." ".ucwords( $arrCustomer[$i]['last_name'] ); ?></a>
														</div>
													</td>
												</tr>

											<?php 

												};

											?>

											</tbody>
										</table>

									</div>
						
									<div class="wrap-table100-nextcols js-pscroll">
										<div class="table100-nextcols">

											<table cellpadding="0" cellspacing="0">
												<thead>
													<tr class="row100 head">
														<th class="cell100 small">PO #</th>
														<!-- <th class="cell100 small column2">Lab</th> -->
														<th class="cell100 small column3" nowrap>Payment</th>
														<th class="cell100 small column3" nowrap>Type</th>
														<th class="cell100 small column3" nowrap>Item Description</th>
														<!-- <?php if($_SESSION['store_code']=='147' ||  $_SESSION['store_code']=='148'  || $_SESSION['store_code'] == '149' ){ ?>

														<th class="cell100 small column3 text-center" nowrap colspan="2">Couriers</th>
														<th class="cell100 small column3 text-center" nowrap>Processing</th>
														<th class="cell100 small column3 text-center" nowrap>Tracking</th>

														<?php } ?> -->
														<th class="cell100 small column3 text-center" nowrap >dispatched</th>
														<th class="cell100 small column3 text-center" nowrap colspan="2">options</th>
													</tr>
												</thead>
												<tbody>

													<?php 
																					
														for ( $i = 0; $i < sizeof($arrCustomer); $i++ ) { 

													?>

													<input type="hidden" name="off[]" value="<?php echo $arrCustomer[$i]["id"] ?>">
													<input type="hidden" name="order_id[<?php echo $arrCustomer[$i]["id"] ?>]" value="<?php echo $arrCustomer[$i]["order_id"] ?>">
													<input type="hidden"  name="name[<?php echo $arrCustomer[$i]["id"] ?>]"  value="<?php echo  ucwords( $arrCustomer[$i]['first_name'] )." ".ucwords( $arrCustomer[$i]['last_name'] ); ?>">

													<?php 

															$payment = $arrCustomer[$i]['payment'];
															$print = $arrCustomer[$i]['lab_print'];
															$production = $arrCustomer[$i]['lab_production'];
															$lab_status = $arrCustomer[$i]['lab_status'];
															$receive = $arrCustomer[$i]['received_stat'];
															$dispatch = $arrCustomer[$i]['store_dispatch'];
															$order_status =  $arrCustomer[$i]['status'];

															$packaging = $arrCustomer[$i]['packaging'];
															$packaging_for = $arrCustomer[$i]['packaging_for'];

													?>

													<tr class="row100 body">
														<td nowrap class="cell100 small text-center">
															<?= $arrCustomer[$i]['po_number']; ?>
															<br> 
															<?= $arrCustomer[$i]['dispatch_type']; ?>
														</td>
														<!-- <td nowrap class="cell100 small text-center column2">
														<?php if($arrCustomer[$i]['product_upgrade']=='special_order'){


																} else{ ?>
																	<?= ucwords(str_replace("mtc", "MTC", str_replace("-", " ", $arrCustomer[$i]['lab_name']))) ?>
														<?php	}?>
															
														
														</td> -->
														<td nowrap class="cell100 small text-center column3 <?= ( $payment == 'y' ) ? 'bg-success' : 'bg-danger'; ?>" >
															<?= ( $payment == 'y' ) ? 'Paid' : 'No'; ?>
															<br>
															<?php echo "<font size='-7'>".cvdate2($arrCustomer[$i]['payment_date'])."</font>"; ?>
														</td>
														<td nowrap class="cell100 small text-center column4 

															<?php 

																		if($arrCustomer[$i]["status"]=='return'){
																			echo 'bg-danger';
																		}
																		elseif($print == 'y') { 

																	echo 'bg-success';

																}elseif($arrCustomer[$i]["lens_option"]=='without prescription' &&  $arrCustomer[$i]['product_upgrade']!='kids_screen_safe'){

																	echo 'bg-info';
																}
																elseif($_SESSION['store_code']!=$arrCustomer[$i]["store_id"]){

																	echo 'bg-secondary';
																}
																elseif($arrCustomer[$i]['product_upgrade']=='special_order') { 

																	echo 'bg-danger';

																}
																elseif($arrCustomer[$i]['product_upgrade']=='kids_screen_safe') { 

																	echo 'bg-danger';

																}
																else {

																	echo '';

																}; 

															?>

														">

															<?php 
																if($arrCustomer[$i]["status"]=='return'){
																		echo 'Returned';
																}
																elseif( $print == 'y' ) { 

																	echo 'Processed'; 
																}elseif($arrCustomer[$i]["lens_option"]=='without prescription' &&  $arrCustomer[$i]['product_upgrade']!='kids_screen_safe'){
																
																	if($arrCustomer[$i]["product_upgrade"]=='fashion_lens'){
																		echo "Frame Only";
																	}elseif($arrCustomer[$i]["product_code"]=='M100'){
																		echo "MERCH";
																	}elseif($arrCustomer[$i]["product_upgrade"]=='G100'){
																		echo "ANTI-RAD";
																	}
																	elseif($arrCustomer[$i]["product_upgrade"]=='sunnies_studios'){
																		echo "SUNNIES STUDIOS";
																	}
																	elseif($arrCustomer[$i]["product_upgrade"]=='sunnies_face'){
																		echo "SUNNIES FACE";
																	}else{
																	echo 'N/A';
																	}
																}
																elseif($_SESSION['store_code']!=$arrCustomer[$i]["store_id"]){

																	echo $arrCustomer[$i]["branch"];
																}
																elseif($arrCustomer[$i]['product_upgrade']=='special_order') { 

																	echo "Essilor"; 

																}
																elseif($arrCustomer[$i]['product_upgrade']=='kids_screen_safe') { 

																	echo "Kids"; 

																}
																else{  

																	echo 'No'; 

																} 

															?>
															<br>
														
															<?php 

																if($arrCustomer[$i]['lab_print_date']=='1910-01-01 22:59:52' || $arrCustomer[$i]['lab_print_date']=='0000-00-00 00:00:00') { 
																} 
																else {  

																	echo "<font size='-7'>".$arrCustomer[$i]['lab_print_date']."</font>"; 

																}	

															?>

														</td>												
														<td nowrap class="cell100 small text-center column5 

														<?php 
															if($_SESSION['store_code']!=$arrCustomer[$i]["store_id"]){

																echo 'bg-secondary';
                                                            }	
                                                            elseif($arrCustomer[$i]["lens_option"]=='without prescription' &&  $arrCustomer[$i]['product_upgrade']!='kids_screen_safe'){

                                                                echo 'bg-info';
                                                            }	  
														  elseif($receive=='r' ){  

														  	echo  'bg-danger'; 

														  }
														  
														  elseif($order_status=='returned' || $order_status=='return'){

															echo  'bg-danger'; 
														  }
														  elseif( $production == 'y'  && $lab_status=='n' ) { 

														  	echo 'bg-warning'; 

														  } 
														  elseif($production == 'y' && $lab_status=='y'){ 

														  	echo 'bg-success'; 

														  } 
														  elseif($arrCustomer[$i]['product_upgrade']=='special_order'){ 

														  	echo 'bg-danger'; 

														  }
														  elseif($arrCustomer[$i]['product_upgrade']=='kids_screen_safe'){ 

														  	echo 'bg-danger'; 

														  }

														?>">
														
															<?php 
																if($arrCustomer[$i]["product_upgrade"]=='sunnies_face'){
																	echo $arrCustomer[$i]["item_description_face"];
																}
                                                               
																else{

																	echo $arrCustomer[$i]["item_description_merch"];
																}

															?>
														 
														</td>			
														

														<td style="border-right: 2px solid #000000;" class="text-center">
															<?php if($arrCustomer[$i]["status"]=='return'){

																	echo "Returned";

																}elseif($arrCustomer[$i]["status"] == 'dispatched'){
																	echo 'Dispatched<br>';
																	echo '<span style="font-size: 10px;">'.$arrCustomer[$i]['status_date'].'</span>';
																}else{
																	
															?>
																<button type="button" class="btn btn-secondary create-signature center-block" id="create-signature_<?php echo $arrCustomer[$i]['id']; ?>" product-code="<?= $arrCustomer[$i]['product_code']?>" product-upgrade="<?= $arrCustomer[$i]['product_upgrade']?>"  order-id="<?= $arrCustomer[$i]['order_id']?>" po-number="<?= $arrCustomer[$i]['po_number']?>" payment-date="<?=date('Y-m-d', strtotime($arrCustomer[$i]['payment_date'])) ?>" customer-name="<?= ucwords( $arrCustomer[$i]['first_name'] )." ".ucwords( $arrCustomer[$i]['last_name'] ) ?>" orders-specs-id="<?= $arrCustomer[$i]['orders_specs_id'] ?>">dispatch</button>
															<?php } ?>
														</td>
														<td style="border-right: 2px solid #000000;">
														<?php if($_SESSION['store_code']!=$arrCustomer[$i]["store_id"]){
																	echo $arrCustomer[$i]["branch"];
																}
																else{ ?>
															<button type="button" class="btn btn-danger btn-cancel-order center-block" data-customer-id="<?= $arrCustomer[$i]['id']; ?>" data-order-specs-id="<?= $arrCustomer[$i]['orders_specs_id']; ?>" data-order-id="<?= $arrCustomer[$i]['order_id']; ?>" data-po-number="<?= $arrCustomer[$i]['po_number']; ?>" data-customer-name="<?= ucwords( $arrCustomer[$i]['first_name'] )." ".ucwords( $arrCustomer[$i]['last_name'] ); ?>" data-toggle="modal" data-target="#cancelOrder" style="color: #ffffff;">cancel</button>
															<?php } ?>
														</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-concern center-block" data-po-number="<?= $arrCustomer[$i]['po_number']; ?>"  data-toggle="modal" data-target="#modal-concern" style="color: #ffffff;">concern</button>
                                                        </td>
														<div id="overlay_<?php echo $arrCustomer[$i]['id']; ?>" class="overlay"></div>
													</tr>

													<?php

														};

													?>

												</tbody>
												
											</table>

										</div>
									</div>
			
								</div>
							</div>

						</form>

		        	</div>

		        </div>
	        </section>
<div class="payosend"></div>

                                

            <div class="modal fade" id="modal-concern" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Create Concern</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                            <div class="col-lg-12">
                                <form id="form-tickets" action='/general/sunnies-concern/process/tickets/create_ticket.php' method="POST" enctype="multipart/form-data">
                                    <div class="row">

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="po_number">PO Number *</label>
                                                <input type="text" name="po_number" class="form-control" id="po_number" autofocus autocomplete="off" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="po_number">REQUESTOR *</label>
                                                <input type="text" name="requestor" class="form-control" id="requestor" autofocus autocomplete="off" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="upload_image">Upload image</label>
                                                <input type="file" name="upload_image[]" class="form-control" id="upload_image" multiple >
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 form-group">
                                            <label class="font-weight-bold" for="storeName">Location</label>
                                            <select class="form-control select" name="branch_code" id="storeName">
                                                <option disabled="disabled" selected="selected" value=""></option>
                                                <option value="HQ">HQ</option>
                                                <option value="sunniessystem.com">sunniessystem.com</option>
                                                <option value="sunniesstore.com">sunniesstore.com</option>
                                                <option value="sunnieshub.com">sunnieshub.com</option>
                                                <?php

                                                    
                                                    // Grab all stores
                                                    $arrStoreLocation = [];
                                                    if($_SESSION['user_login']['position'] == 'supervisor'){
                                                        $arrStoreLocation = explode(',',$_SESSION['user_login']['store_location']);
                                                    }elseif($_SESSION['user_login']['position'] == 'store'){
                                                            $arrStoreLocation[] = $_SESSION['user_login']['store_code'];
                                                    }


                                                    $arrPosition = ['supervisor','store'];
                                                    $arrStores = grabStores();
                                                    $countSpecs = 0;
                                                    $countStudios = 0;
                                                    $countFace = 0;
                                                    echo '<optgroup label="SPECS STORES" id="specsOpt">';
                                                    for ($i=0; $i < sizeOf($arrStores); $i++) { 

                                                        if(in_array($_SESSION['user_login']['position'], $arrPosition)){
                                                            if(!in_array($arrStores[$i]['store_id'], $arrStoreLocation)) continue;
                                                        }

                                                        $curStoreName = ucwords(str_replace("u.p.", "UP", str_replace("sm", "SM", str_replace("-", " ", $arrStores[$i]['store_name']))));
                                                        echo '<option value="'.$arrStores[$i]['store_id'].'" >'.$curStoreName.'</option>';
                                                        $countSpecs++;	
                                                    };
                                                    echo '</optgroup>';
                                                    echo '<optgroup label="STUDIOS STORES" id="studiosOpt">';
                                                    for ($i=0; $i < sizeOf($arrStoresStudios); $i++) { 
                                                        if(in_array($_SESSION['user_login']['position'], $arrPosition)){
                                                            if(!in_array($arrStoresStudios[$i]['store_id'], $arrStoreLocation)) continue;
                                                        }
                                                        echo '<option value="'.$arrStoresStudios[$i]['store_id'].'" >STUDIOS '.$arrStoresStudios[$i]['store_name'].'</option>';
                                                        $countStudios++;
                                                    };
                                                    echo '</optgroup>';

                                                    echo '<optgroup label="FACE STORES" id="faceOpt">';
                                                    for ($i=0; $i < sizeOf($arrStoresFace); $i++) {
                                                        if(in_array($_SESSION['user_login']['position'], $arrPosition)){
                                                            if(!in_array($arrStoresFace[$i]['store_id'], $arrStoreLocation)) continue;
                                                        }
                                                        echo '<option value="'.$arrStoresFace[$i]['store_id'].'" >FACE '.$arrStoresFace[$i]['store_name'].'</option>';
                                                        $countFace++;
                                                    };
                                                    echo '</optgroup>';

                                                ?>
                                            </select>
                                        </div>
                                        <script>
                                            let countSpecs = <?= $countSpecs ?>;
                                            let countStudios = <?= $countStudios ?>;
                                            let countFace = <?= $countFace ?>;

                                            if(countSpecs == 0){
                                                $('#specsOpt').remove();
                                            }
                                            if(countStudios == 0){
                                                $('#studiosOpt').remove();
                                            }
                                            if(countFace == 0){
                                                $('#faceOpt').remove();
                                            }
                                        </script>
                                        <!-- <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="customer">Impact Level *</label>
                                                <select name="impact" class="form-control" id="impact" required>
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class="col-md-6 col-xs12">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="department">To Department *</label>
                                                <select class="form-control select" name="department" id="department">
                                                    <?php foreach ($arrDepartment as $key => $value) { 
                                                        if($arrDep[0]['department'] == $key) continue;
                                                        // if($arrDep[0]['department'] != 'retail_operation' &&  $key == 'retail_operation') continue;

                                                    ?>
                                                    <option value="<?= $key ?>"><?= $value ?></option>
                                                    <?php } ?>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <label class="font-weight-bold" for="customer">Remarks/Issue *</label>
                                            <div class="form-group" style="height: 65vh; overflow-y: auto;">
                                                <textarea class="tinymce" name="remarks"></textarea>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 claim">
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary submit" value="Submit" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>
			
					</div>
				</div>
			</div>



			<div class="modal fade" id="informationRemarks">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
			
					</div>
				</div>
			</div>
			<div id="overlay-package" class="overlay"></div>
			<div class="modal fade" id="cancelOrder">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">							
							<h4 class="modal-title">Are you sure you want to cancel order <b><span id="POnum"></span></b>?</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<p>You are about to cancel order <b><span id="POnum"></span></b> for customer <b><span id="POName"></span></b>.</p>
							<textarea class="form-control" name="cancel_reason" id="cancel_reason" placeholder="Reason for Cancel"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" id="cancelThisOrder" class="btn btn-danger" data-customer-id="" data-order-id="" data-po-number="" data-customer-name="" order-specs-id="" style="color: #ffffff;">Cancel Order</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="packagingOrder">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">							
							<h4 class="modal-title">Order Packaging </h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-primary" name="packaging_submit" value="submit">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-remarks text-left"></div>
			<div class="stat" style="display: none"></div>

<!-- ===================================================================================================================================== -->

            <script src="/js/tinymce/plugin/tinymce/tinymce.min.js"></script>
            <script src="/js/tinymce/plugin/tinymce/init-tinymce.js"></script>

			<script src="/js/jquery-3.2.1.min.js"></script>
			<script src="/js/tether.min.js"></script>
			<script src="/js/bootstrap.min.js"></script>
			<script src="/js/perfect-scrollbar.min.js"></script>
			<script src="/js/select2.min.js"></script>
			<script src="/js/ssis_functions.js"></script>
			<script src="/js/custom.js"></script>

			<script>		

				function grabVal(selector) {

					var value = $(selector).val();

					if(value == null) {

						value = "";

					};

					return value;

				};		
			

				$(".refresh_single_payo").click(function() {

// Grab values of filters
var orderidspecs= $(this).attr("id").replace("refreshPayo_","");

// alert(orderidspecs);
					$('.payosend').load("/process/couriers/refresh_payo_order.php?orderidspecs="+ orderidspecs, function() {

							// alert('Sending to Payo Success');
						window.location.reload(true);
								});


});	


				$(".btn-payo-refresh").click(function() {

				return $.ajax({

										url: "/process/couriers/refresh_payoxx.php",

										success: function(data) {

											dataResult = data.status;

										location.reload();

										}

									});
				});	
					$(".sendpayo").click(function() {

					// Grab values of filters
					 var orderspecsid= $(this).attr("id").replace("sendtoCourier_","");
					 
                    
					 $('.payosend').load("/process/couriers/send_to_payo.php?orderspecsid="+ orderspecsid, function() {

							// alert('Sending to Payo Success');
						window.location.reload(true);
								});


				});
				// $(".printpayo").click(function() {

				// 	let orders_specs_id = $(this).attr("id").replace("PrintWayBill_","");
				// 	let order_id = $(this).attr('data-order-id');
				// 	let po_number = $(this).attr('data-po-number');
				// 	let invoice_no = $(this).attr('data-invoice-no');
					 
				// 	window.open('/dispatch/payo-print/?order_id='+order_id+'&orders_specs_id='+orders_specs_id+'&invoice_no='+invoice_no);

				// });

				$("#btnSearch").click(function() {

					// Grab values of filters
					filterA = grabVal('#search').replace(/\s/g, "+");
						
					filterURL = "?search=" + filterA + "&page=1";
					filterURL += '<?= (isset($_GET["store"])) ? "&store=".$_GET["store"] : "" ?>';
					window.location.href = filterURL;

				});

				$('#filterPage').on('change', function() {

					// Grab values of filters
					filterA = grabVal('#search').replace(/\s/g, "+");
					filterB = grabVal('#filterPage');
						
					filterURL = "?search=" + filterA + "&page=" + filterB;
					filterURL += '<?= (isset($_GET["store"])) ? "&store=".$_GET["store"] : "" ?>';
					window.location.href = filterURL;

				});

			</script>

			<script>
	$('#toggle-filter-message').on('click', function(e) {
e.preventDefault();
toggleFilter('show');

$('#close-filter-message').on('click', function () {
toggleFilter('hide');
});
});
				$(document).ready(function(){		
                    
                    $('.btn-concern').click(function(){
                        var po = $(this).attr('data-po-number');

                        $('#po_number').val(po);
                    })


					// $("#btnsubmit").click(function(){	

					// 	$('#action_type_lab').val( $(this).data('value') );
					// 	$('.remarks-overlay').fadeIn();
					
					// 	$.post("../process/dispatch/confirmation.php",$("form#lab_form").serialize(),function(d){

					// 		$('.form-remarks').html(d);

					// 		$('.close-confirmation').click(function() {
					// 			window.location.reload(true);
					// 		});

					// 	});

					// });
					
					// $('.iremarks').click(function(){

					// 	var id = $(this).attr("id").replace("iremarks_","");

					// 	$('#informationRemarks .modal-content').load("../process/dispatch/remarks_chat.php?id="+ id, function() {

					// 		scrollChat( 500 );

					// 	});

					// });
					
					// $("#btnsubmit2").click(function(){

					// 	$('#action_type_lab').val( $(this).data('value') );
					// 	$('.remarks-overlay').fadeIn();
						
					// 	$.post("../process/dispatch/confirmation.php",$("form#lab_form").serialize(),function(d){

					// 		$('.form-remarks').html(d);

					// 		$('.close-confirmation').click(function() {
					// 			window.location.reload(true);
					// 		});

					// 	});

					// });

					function updateW() {

						var px = $('.table100-firstcol').outerWidth(true);

						$('.wrap-table100-nextcols').css('padding-left', px );

					};

					$(window).on('resize load scroll', function() {

						updateW();

					}).resize();

					// ======================================= SIGNATURE
					// let po_number ='', order_id = '', orders_specs_id ='';
					// $('.create-packaging').click(function(){
					// 	order_id = $(this).attr('order-id');
					// 	po_number = $(this).attr('po-number');
					// 	orders_specs_id = $(this).attr('orders-specs-id');
					// 	customer = encodeURIComponent($(this).attr('customer-name'));
					// 	$('#packagingOrder .modal-body').load('packaging.php?name='+customer+'&po_number='+po_number);
					// 	$('#packagingOrder').modal('show');
					// });
					function activateSignature() {

						$('.create-signature').click(function() {

							var id = $(this).attr("id").replace("create-signature_","");
							order_id = $(this).attr('order-id');
							po_number = $(this).attr('po-number');
							orders_specs_id = $(this).attr('orders-specs-id');
							customer = encodeURIComponent($(this).attr('customer-name'));
							product_code = encodeURIComponent($(this).attr('product-code'));
							product_upgrade = encodeURIComponent($(this).attr('product-upgrade'));
							payment_date = encodeURIComponent($(this).attr('payment-date'));
							
							$("#overlay_"+id).load('/face/process/dispatch_face/dispatch_detail_v2.php?&cn=' + id+'&name='+customer+'&po_number='+po_number+'&order_id='+order_id+'&orders_specs_id='+orders_specs_id+'&product_code='+product_code+'&product_upgrade='+product_upgrade+'&payment_date='+payment_date);
							
					    	$('#overlay_'+ id).fadeIn();
							
					    });
						
					    $('.close-signature').click(function(e) {

					    	e.preventDefault();

							var id = $(this).attr("id").replace("close-signature_","");

					    	$('#overlay_'+ id).fadeOut();

					    });

					};

				    activateSignature();

				 //    // ======================================= REMAKE

					// function remakeOrder() {

					// 	$('.btn-remake-order').click(function() {

					// 		var id = $(this).attr("id").replace("remake_","");
							
					// 		$("#overlay_"+id).load("../process/dispatch/dispatch_detail_remake_v2.php?&cn=" +id+"&po_number=" +$(this).attr('data-po-number')+"&order_specs_id=" +$(this).attr('data-order-specs-id'));
							
					//     	$('#overlay_'+ id).fadeIn();
							
					//     });
						
					//     $('btn-remake-order').click(function(e) {

					//     	e.preventDefault();

					// 		var id = $(this).attr("id").replace("close-signature_","");

					//     	$('#overlay_'+ id).fadeOut();

					//     });

					// };

				 //    remakeOrder();


				    // ===================================== CANCEL

					$('.btn-cancel-order').click(function() {

						var thisCustomerID = $(this).data('customer-id');
						var thisOrderID = $(this).data('order-id');
						var thisPONumber = $(this).data('po-number');
						var thisCustomerName = $(this).data('customer-name');
						var thisUniqueId = $(this).data('order-specs-id');

						$('#cancelOrder #POnum').html(thisPONumber);
						$('#cancelOrder #POName').html(thisCustomerName);

						$('#cancelThisOrder').attr('data-order-specs-id', thisUniqueId);

						$('#cancelThisOrder').attr('data-customer-id', thisCustomerID);
						$('#cancelThisOrder').attr('data-order-id', thisOrderID);
						$('#cancelThisOrder').attr('data-po-number', thisPONumber);
						$('#cancelThisOrder').attr('data-customer-name', thisCustomerName);

					});

					$('#cancelThisOrder').click(function() {

							var thisCustomerID = $(this).data('customer-id');
							var thisOrderID = $(this).data('order-id');
							var thisPONumber = $(this).data('po-number');
							var thisCustomerName = $(this).data('customer-name');
							var thisUniqueId = $(this).data('order-specs-id');
							var thisCancelReason = $('#cancel_reason').val();
							// alert(thisUniqueId);

							if(thisCancelReason=='' ){
								alert('Please a reason');
							}
							else{
								// alert('success');
								$.ajax({

									type: "POST",
									url: "/face/process/dispatch_face/cancel.php",
									data: {order_id: thisOrderID, po_number: thisPONumber, customer_id: thisCustomerID,cancel_reason: thisCancelReason,orders_specs_id: thisUniqueId},
									success: function(data) {

										alert('success!');
										window.location.reload(true);

									}

								});
							}

					});
					
					if ( $('.table100-nextcols tbody tr').length > 10 ) {

						$('.wrap-table100').addClass('scroll');

					};		

					<?php 

						if($_SESSION['user_login']['userlvl'] == 1 && $_SESSION['user_login']['position'] == 'admin') {

					?>

					// ===================================== ADMIN STORE SELECT

					$('#filterStore').on('change', function() {

						// Grab values of filter
						filterStore = grabVal('#filterStore');
							
						filterURL = "?store=" + filterStore;

						window.location.href = filterURL;

					});		

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

					<?php

						};

					?>
					// let po_number ='', order_id = '', orders_specs_id ='';
					// $('.create-packaging').click(function(){
					// 	order_id = $(this).attr('order-id');
					// 	po_number = $(this).attr('po-number');
					// 	orders_specs_id = $(this).attr('orders-specs-id');
					// 	customer = encodeURIComponent($(this).attr('customer-name'));
					// 	$('#packagingOrder .modal-body').load('packaging.php?name='+customer+'&po_number='+po_number);
					// 	$('#packagingOrder').modal('show');
					// });
					// $("input[name=packaging_submit]").click(function(){
					// 	if($('input[name=employee_id]').val().trim() == ''){
					// 		alert('Required Employee Id');
					// 	}
					// 	else if($('select[name=paper_bag]').val().trim() == '' || $('select[name=hard_case]').val().trim() == ''){
					// 		alert('Please Select Paper bag and Harcase');
					// 	}
					// 	else{
					// 		$(this).hide();
					// 		$.post('/process/dispatch/packaging_process.php',{employee_id: $('input[name=employee_id]').val(), order_id: order_id, po_number: po_number, orders_specs_id:orders_specs_id, paper_bag:$('select[name=paper_bag]').val(),hard_case:$('select[name=hard_case]').val(), paper_bag_from:'store',hardcase_from:$('select[name=hardcase_from]').val()},function(result){
					// 			alert(result);
					// 			location.reload();

					// 		});
					// 	}
					// });
					$(this).on('change','select[name=paper_bag]',function(){
						if($(this).val() != ''){
							$(document).find('input[name=paper_bag_quantity]').attr('required',true);
							$(document).find('input[name=paper_bag_quantity]').val(1);
						}
						else{
							$(document).find('input[name=paper_bag_quantity]').removeAttr('required');
							$(document).find('input[name=paper_bag_quantity]').val('');
						}
					});
					$(this).on('change','select[name=hard_case]',function(){
						if($(this).find(":selected").text().trim() == 'None'){
							$(this).removeAttr('required');
							$(document).find('select[name=hardcase_from]').removeAttr('required');
						}
						else{
							$(this).attr('required',true);
							$(document).find('select[name=hardcase_from]').attr('required',true);
						}
					});
				});
				
			</script>

			<script> ////////////////////////////////////////////// MESSENGER
			
				$(document).ready(function() {
					
					// check if there is new message
					if ( $('.notif').data('count') > 0 ) {
						$('.notif').addClass('new');
					} else {
						$('.notif').removeClass('new');
					}

					// open chat room
					$('#toggle-messenger').on('click', function() {
						$('#chat-room').fadeIn();

						// close chat room
						$('#hide-chat-room').on('click', function() {
							$('#chat-room').fadeOut();
						})

						// search chat room
						var typingTimer;
						var doneTypingInterval = 1000;
						var getInput = $('.chat-footer input');

						getInput.on('keyup', function (res) {

							clearTimeout(typingTimer);
							typingTimer = setTimeout(function() {

								if (getInput.val() != '') {

									/* LOAD THE SEARCHED RESULT INTO $('.chat-list) */

								} else {

									/* LOAD THE DEFAULT LIST OF CHATS INTO $('.chat-list) */

								}
							
							}, doneTypingInterval);
						}).on('keydown', function () {
							clearTimeout(typingTimer);
						}).on('blur', function() {
							
							if (getInput.val() != '') {

								/* LOAD THE SEARCHED RESULT INTO $('.chat-list) */

							} else {

								/* LOAD THE DEFAULT LIST OF CHATS INTO $('.chat-list) */

							}

						});

						// reset search chat room
						$('#clear-search-chat').on('click', function() {

							$('.chat-footer input').val("");
							/* LOAD THE DEFAULT LIST OF CHATS INTO $('.chat-list) */

						})
					})
					$('#btnPackage').click(function(){
						$('#overlay-package').load("/process/dispatch_studios/dispatch_package_list.php");
						$('#overlay-package').fadeIn();
					});
					$(this).on('click','#close-package',function(){
						$('#overlay-package').fadeOut();
					});

					$(this).on('change','#date-now',function(){
						$('#overlay-package').load("/process/dispatch_studios/dispatch_package_list.php?date="+$(this).val());
					});

					$(this).on('keyup change','input[name=paper_bag_quantity]', function(){
						($(this).val() > 5) ? $(this).val(5) : '';
					});
				})

			</script>

		</div>

		</div>

		<?php

			// require $sDocRoot."/includes/notification.php";	

		?>
	
		<?php //} ?>

	</div>
	
</body>
</html>