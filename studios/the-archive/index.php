<?php 

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'the-archive';

$filter_page = 'the_archive';
$group_name = 'ssis';

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// exit;

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/sidebar_update_v2.php";
require $sDocRoot."/includes/archive/grab_sun_frames.php";
require $sDocRoot."/includes/v2/functions.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_studios.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

////////////////////////////////////////////////

function checkBox($filter_array, $code) {

	if(isset($_POST[$filter_array]) && in_array($code, $_POST[$filter_array])) {

		echo 'checked';

	}

};

?>

<?= get_header($page) ?>

<style type="text/css">
	
	.pointer {
		cursor: pointer !important;
	}
	.type-label {
		font-size: 7px; 
		border: 1px solid #ccc; 
		padding: 5px; 
		border-radius: 5px;
	}

	#filterOptions .filter {
		cursor: pointer;
	}
	#filterOptions .active {
	    font-weight: bold;
    	border-bottom: 3px solid #000;
	}
	#filterWell .filter-option-result {
		display: none;
	}

	.show-product {
		display: block !important;
	}

	.custom-card section img {
		max-width: 200px;
	}
	.btn-head-to-section {
		width: 100%;
	}
	.color-picker {		
		width: 25px;
		height: 25px;
		border-radius: 50%;
		vertical-align: bottom;
		cursor: pointer;
	}
	.table-list td {
		vertical-align: middle;
	}
	.table-list img {
		max-width: 100px;
	}
	.table-list .switch-button {
		margin: 0 auto;
		width: 60px;
		border: 1px solid #282828;
		position: relative;
		height: 20px;
		cursor: pointer;
		color: #282828;
		-webkit-border-radius: 20px;
		-moz-border-radius: 20px;
		border-radius: 20px;
		-webkit-transition: all .3s ease;
		-o-transition: all .3s ease;
		transition: all .3s ease;
	}

	.table-list .switch-button:after {
		content: '';
		display: block;
		position: absolute;
		left: 1px;
		top: 1px;
		height: 16px;
		width: 26px;
		background: #282828;
		-webkit-border-radius: 16px;
		-moz-border-radius: 16px;
		border-radius: 16px;
		-webkit-transition: all .3s ease;
		-o-transition: all .3s ease;
		transition: all .3s ease;
	}

	.table-list .switch-button.on {
		background: #dff0d8;
		border-color: #dff0d8;
		color: #449d44;
	}

	.table-list .switch-button.on:after {
		left: 100%;
		margin-left: -27px;
		background: #449d44;
	}

	.table-list .switch-button span {
		width: 50%;
		display: block;
		float: left;
		line-height: 20px;
		padding: 0;
		margin: 0;
		text-align: center;
	}
	.table-list .stock-value {
		margin: 0 auto;
		width: 100%;
		max-width: 100px;
	}

	.section-color-picker .color-picker {
		margin: 0 auto;
	}
	.section-color-picker .submit-new-color {
		cursor: pointer;
	}
	.section-color-picker .submit-new-color:hover {
		color: #fff;
		background-color: #ccc;
	}

</style>

<div class="row no-gutters align-items-strech">

	<?= get_sidebar($page) ?>
	
	<div id="ssis-main" class="col <?= str_replace(' ','-',$page) ?>">
			
		<?= get_topbar($page) ?>
		
		<div class="ssis-content">

			<!-- Filter -->

			<div class="row mt-4 mb-5 align-items-strech">
				<div class="col-md-8 col-xs-12">
					<div class="custom-card-header">
					    <p>Filter:</p>
					</div>
					<div class="custom-card">					
						<section class="d-flex justify-content-between" id="filterOptions">
							<p class="h3 text-uppercase filter mt-2 mb-2" data-filter-option="frame-color">Color</p>							
							<p class="h3 text-uppercase filter mt-2 mb-2" data-filter-option="style">Style</p>
							<p class="h3 text-uppercase filter mt-2 mb-2" data-filter-option="width">Width</p>
							<p class="h3 text-uppercase filter mt-2 mb-2" data-filter-option="material">Material</p>
							<p class="h3 text-uppercase filter mt-2 mb-2" data-filter-option="finish">Finish</p>							
						</section>
						<section class="d-flex justify-content-between" style="background-color: #fff;">
							<form class="col-12 filter-option-well collapse" id="filterWell" data-filter-option="" method="POST" target="">

								<!-- COLOR -->

								<div class="row filter-option-result mt-4 mb-4" id="frame-color">
									<div class="col-12">
										<div class="form-row">
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0001" value="SGE0001" <?php checkBox('filterColors','SGE0001') ?>>
													<label for="optionSGE0001" class="custom_checkbox pointer"></label>
													<label for="optionSGE0001" class="m-0 ml-2 pointer">BLACK</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0002" value="SGE0002" <?php checkBox('filterColors','SGE0002') ?>>
													<label for="optionSGE0002" class="custom_checkbox pointer"></label>
													<label for="optionSGE0002" class="m-0 ml-2 pointer">BLACK / GOLD</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0004" value="SGE0004" <?php checkBox('filterColors','SGE0004') ?>>
													<label for="optionSGE0004" class="custom_checkbox pointer"></label>
													<label for="optionSGE0004" class="m-0 ml-2 pointer">BLUE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0003" value="SGE0003" <?php checkBox('filterColors','SGE0003') ?>>
													<label for="optionSGE0003" class="custom_checkbox pointer"></label>
													<label for="optionSGE0003" class="m-0 ml-2 pointer">BLUE / GRAY</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0029" value="SGE0029" <?php checkBox('filterColors','SGE0029') ?>>
													<label for="optionSGE0029" class="custom_checkbox pointer"></label>
													<label for="optionSGE0029" class="m-0 ml-2 pointer">BRONZE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0005" value="SGE0005" <?php checkBox('filterColors','SGE0005') ?>>
													<label for="optionSGE0005" class="custom_checkbox pointer"></label>
													<label for="optionSGE0005" class="m-0 ml-2 pointer">BROWN</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0006" value="SGE0006" <?php checkBox('filterColors','SGE0006') ?>>
													<label for="optionSGE0006" class="custom_checkbox pointer"></label>
													<label for="optionSGE0006" class="m-0 ml-2 pointer">CLEAR</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0032" value="SGE0032" <?php checkBox('filterColors','SGE0032') ?>>
													<label for="optionSGE0032" class="custom_checkbox pointer"></label>
													<label for="optionSGE0032" class="m-0 ml-2 pointer">CREAM</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0007" value="SGE0007" <?php checkBox('filterColors','SGE0007') ?>>
													<label for="optionSGE0007" class="custom_checkbox pointer"></label>
													<label for="optionSGE0007" class="m-0 ml-2 pointer">DARK BROWN</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0008" value="SGE0008" <?php checkBox('filterColors','SGE0008') ?>>
													<label for="optionSGE0008" class="custom_checkbox pointer"></label>
													<label for="optionSGE0008" class="m-0 ml-2 pointer">GOLD</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0009" value="SGE0009" <?php checkBox('filterColors','SGE0009') ?>>
													<label for="optionSGE0009" class="custom_checkbox pointer"></label>
													<label for="optionSGE0009" class="m-0 ml-2 pointer">GRAY</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0010" value="SGE0010" <?php checkBox('filterColors','SGE0010') ?>>
													<label for="optionSGE0010" class="custom_checkbox pointer"></label>
													<label for="optionSGE0010" class="m-0 ml-2 pointer">GREEN</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0011" value="SGE0011" <?php checkBox('filterColors','SGE0011') ?>>
													<label for="optionSGE0011" class="custom_checkbox pointer"></label>
													<label for="optionSGE0011" class="m-0 ml-2 pointer">GREY</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0012" value="SGE0012" <?php checkBox('filterColors','SGE0012') ?>>
													<label for="optionSGE0012" class="custom_checkbox pointer"></label>
													<label for="optionSGE0012" class="m-0 ml-2 pointer">GUNMETAL</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0013" value="SGE0013" <?php checkBox('filterColors','SGE0013') ?>>
													<label for="optionSGE0013" class="custom_checkbox pointer"></label>
													<label for="optionSGE0013" class="m-0 ml-2 pointer">MULTI</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0014" value="SGE0014" <?php checkBox('filterColors','SGE0014') ?>>
													<label for="optionSGE0014" class="custom_checkbox pointer"></label>
													<label for="optionSGE0014" class="m-0 ml-2 pointer">NUDE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0030" value="SGE0030" <?php checkBox('filterColors','SGE0030') ?>>
													<label for="optionSGE0030" class="custom_checkbox pointer"></label>
													<label for="optionSGE0030" class="m-0 ml-2 pointer">OMBRE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0015" value="SGE0015" <?php checkBox('filterColors','SGE0015') ?>>
													<label for="optionSGE0015" class="custom_checkbox pointer"></label>
													<label for="optionSGE0015" class="m-0 ml-2 pointer">ORANGE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0016" value="SGE0016" <?php checkBox('filterColors','SGE0016') ?>>
													<label for="optionSGE0016" class="custom_checkbox pointer"></label>
													<label for="optionSGE0016" class="m-0 ml-2 pointer">ORANGE TORT</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0017" value="SGE0017" <?php checkBox('filterColors','SGE0017') ?>>
													<label for="optionSGE0017" class="custom_checkbox pointer"></label>
													<label for="optionSGE0017" class="m-0 ml-2 pointer">PALE NUDE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0018" value="SGE0018" <?php checkBox('filterColors','SGE0018') ?>>
													<label for="optionSGE0018" class="custom_checkbox pointer"></label>
													<label for="optionSGE0018" class="m-0 ml-2 pointer">PEACH</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0019" value="SGE0019" <?php checkBox('filterColors','SGE0019') ?>>
													<label for="optionSGE0019" class="custom_checkbox pointer"></label>
													<label for="optionSGE0019" class="m-0 ml-2 pointer">PINK</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0020" value="SGE0020" <?php checkBox('filterColors','SGE0020') ?>>
													<label for="optionSGE0020" class="custom_checkbox pointer"></label>
													<label for="optionSGE0020" class="m-0 ml-2 pointer">PURPLE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0021" value="SGE0021" <?php checkBox('filterColors','SGE0021') ?>>
													<label for="optionSGE0021" class="custom_checkbox pointer"></label>
													<label for="optionSGE0021" class="m-0 ml-2 pointer">PURPLE TORT</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0022" value="SGE0022" <?php checkBox('filterColors','SGE0022') ?>>
													<label for="optionSGE0022" class="custom_checkbox pointer"></label>
													<label for="optionSGE0022" class="m-0 ml-2 pointer">RED</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0023" value="SGE0023" <?php checkBox('filterColors','SGE0023') ?>>
													<label for="optionSGE0023" class="custom_checkbox pointer"></label>
													<label for="optionSGE0023" class="m-0 ml-2 pointer">ROSE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0031" value="SGE0031" <?php checkBox('filterColors','SGE0031') ?>>
													<label for="optionSGE0031" class="custom_checkbox pointer"></label>
													<label for="optionSGE0031" class="m-0 ml-2 pointer">ROSE GOLD</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0024" value="SGE0024" <?php checkBox('filterColors','SGE0024') ?>>
													<label for="optionSGE0024" class="custom_checkbox pointer"></label>
													<label for="optionSGE0024" class="m-0 ml-2 pointer">SILVER</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0025" value="SGE0025" <?php checkBox('filterColors','SGE0025') ?>>
													<label for="optionSGE0025" class="custom_checkbox pointer"></label>
													<label for="optionSGE0025" class="m-0 ml-2 pointer">TAUPE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0026" value="SGE0026" <?php checkBox('filterColors','SGE0026') ?>>
													<label for="optionSGE0026" class="custom_checkbox pointer"></label>
													<label for="optionSGE0026" class="m-0 ml-2 pointer">TORT</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0027" value="SGE0027" <?php checkBox('filterColors','SGE0027') ?>>
													<label for="optionSGE0027" class="custom_checkbox pointer"></label>
													<label for="optionSGE0027" class="m-0 ml-2 pointer">WHITE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterColors[]" type="checkbox" id="optionSGE0028" value="SGE0028" <?php checkBox('filterColors','SGE0028') ?>>
													<label for="optionSGE0028" class="custom_checkbox pointer"></label>
													<label for="optionSGE0028" class="m-0 ml-2 pointer">YELLOW</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12">
										<section class="d-flex justify-content-center">
											<button class="h3 text-uppercase btn btn-primary mt-4 mb-2" style="border-radius: 7px;" type="submit">Filter Frames</button>			
										</section>
									</div>
								</div>

								<!-- Style -->

								<div class="row filter-option-result mt-4 mb-4" id="style">
									<div class="col-12">
										<div class="form-row">
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0001" value="SSH0001" <?php checkBox('filterStyles','SSH0001') ?>>
													<label for="optionSSH0001" class="custom_checkbox pointer"></label>
													<label for="optionSSH0001" class="m-0 ml-2 pointer">BROWLINE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0002" value="SSH0002" <?php checkBox('filterStyles','SSH0002') ?>>
													<label for="optionSSH0002" class="custom_checkbox pointer"></label>
													<label for="optionSSH0002" class="m-0 ml-2 pointer">CAT EYE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0003" value="SSH0003" <?php checkBox('filterStyles','SSH0003') ?>>
													<label for="optionSSH0003" class="custom_checkbox pointer"></label>
													<label for="optionSSH0003" class="m-0 ml-2 pointer">PILOT</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0004" value="SSH0004" <?php checkBox('filterStyles','SSH0004') ?>>
													<label for="optionSSH0004" class="custom_checkbox pointer"></label>
													<label for="optionSSH0004" class="m-0 ml-2 pointer">OVERSIZED</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0005" value="SSH0005" <?php checkBox('filterStyles','SSH0005') ?>>
													<label for="optionSSH0005" class="custom_checkbox pointer"></label>
													<label for="optionSSH0005" class="m-0 ml-2 pointer">RECTANGLE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0006" value="SSH0006" <?php checkBox('filterStyles','SSH0006') ?>>
													<label for="optionSSH0006" class="custom_checkbox pointer"></label>
													<label for="optionSSH0006" class="m-0 ml-2 pointer">ROUND</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterStyles[]" type="checkbox" id="optionSSH0007" value="SSH0007" <?php checkBox('filterStyles','SSH0007') ?>>
													<label for="optionSSH0007" class="custom_checkbox pointer"></label>
													<label for="optionSSH0007" class="m-0 ml-2 pointer">SQUARE</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12">
										<section class="d-flex justify-content-center">
											<button class="h3 text-uppercase btn btn-primary mt-4 mb-2" style="border-radius: 7px;" type="submit">Filter Frames</button>			
										</section>
									</div>
								</div>

								<!-- Width -->

								<div class="row filter-option-result mt-4 mb-4" id="width">
									<div class="col-12">
										<div class="form-row">
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterWidths[]" type="checkbox" id="optionSDI00270" value="SDI00270" <?php checkBox('filterWidths','SDI00270') ?>>
													<label for="optionSDI00270" class="custom_checkbox pointer"></label>
													<label for="optionSDI00270" class="m-0 ml-2 pointer">EXTRA WIDE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterWidths[]" type="checkbox" id="optionSDI00266" value="SDI00266" <?php checkBox('filterWidths','SDI00266') ?>>
													<label for="optionSDI00266" class="custom_checkbox pointer"></label>
													<label for="optionSDI00266" class="m-0 ml-2 pointer">KIDS</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterWidths[]" type="checkbox" id="optionSDI00268" value="SDI00268" <?php checkBox('filterWidths','SDI00268') ?>>
													<label for="optionSDI00268" class="custom_checkbox pointer"></label>
													<label for="optionSDI00268" class="m-0 ml-2 pointer">MEDIUM</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterWidths[]" type="checkbox" id="optionSDI00267" value="SDI00267" <?php checkBox('filterWidths','SDI00267') ?>>
													<label for="optionSDI00267" class="custom_checkbox pointer"></label>
													<label for="optionSDI00267" class="m-0 ml-2 pointer">NARROW</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterWidths[]" type="checkbox" id="optionSDI00269" value="SDI00269" <?php checkBox('filterWidths','SDI00269') ?>>
													<label for="optionSDI00269" class="custom_checkbox pointer"></label>
													<label for="optionSDI00269" class="m-0 ml-2 pointer">WIDE</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12">
										<section class="d-flex justify-content-center">
											<button class="h3 text-uppercase btn btn-primary mt-4 mb-2" style="border-radius: 7px;" type="submit">Filter Frames</button>			
										</section>
									</div>
								</div>

								<!-- Material -->

								<div class="row filter-option-result mt-4 mb-4" id="material">
									<div class="col-12">
										<div class="form-row">
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterMaterials[]" type="checkbox" id="optionSMT0003" value="SMT0003" <?php checkBox('filterMaterials','SMT0003') ?>>
													<label for="optionSMT0003" class="custom_checkbox pointer"></label>
													<label for="optionSMT0003" class="m-0 ml-2 pointer">ACETATE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterMaterials[]" type="checkbox" id="optionSMT0006" value="SMT0006" <?php checkBox('filterMaterials','SMT0006') ?>>
													<label for="optionSMT0006" class="custom_checkbox pointer"></label>
													<label for="optionSMT0006" class="m-0 ml-2 pointer">BIO ACETATE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterMaterials[]" type="checkbox" id="optionSMT0001" value="SMT0001" <?php checkBox('filterMaterials','SMT0001') ?>>
													<label for="optionSMT0001" class="custom_checkbox pointer"></label>
													<label for="optionSMT0001" class="m-0 ml-2 pointer">METAL</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterMaterials[]" type="checkbox" id="optionSMT0002" value="SMT0002" <?php checkBox('filterMaterials','SMT0002') ?>>
													<label for="optionSMT0002" class="custom_checkbox pointer"></label>
													<label for="optionSMT0002" class="m-0 ml-2 pointer">PLASTIC</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterMaterials[]" type="checkbox" id="optionSMT0004" value="SMT0004" <?php checkBox('filterMaterials','SMT0004') ?>>
													<label for="optionSMT0004" class="custom_checkbox pointer"></label>
													<label for="optionSMT0004" class="m-0 ml-2 pointer">PLASTIC / METAL</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterMaterials[]" type="checkbox" id="optionSMT0005" value="SMT0005" <?php checkBox('filterMaterials','SMT0005') ?>>
													<label for="optionSMT0005" class="custom_checkbox pointer"></label>
													<label for="optionSMT0005" class="m-0 ml-2 pointer">TR90</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12">
										<section class="d-flex justify-content-center">
											<button class="h3 text-uppercase btn btn-primary mt-4 mb-2" style="border-radius: 7px;" type="submit">Filter Frames</button>			
										</section>
									</div>
								</div>

								<!-- Finish -->

								<div class="row filter-option-result mt-4 mb-4" id="finish">
									<div class="col-12">
										<div class="form-row">
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterFinishes[]" type="checkbox" id="optionSFI0001" value="SFI0001" <?php checkBox('filterFinishes','SFI0001') ?>>
													<label for="optionSFI0001" class="custom_checkbox pointer"></label>
													<label for="optionSFI0001" class="m-0 ml-2 pointer">MATTE</label>
												</div>
											</div>
											<div class="col-3 col-xs-12 mb-2">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox pointer" name="filterFinishes[]" type="checkbox" id="optionSFI0002" value="SFI0002" <?php checkBox('filterFinishes','SFI0002') ?>>
													<label for="optionSFI0002" class="custom_checkbox pointer"></label>
													<label for="optionSFI0002" class="m-0 ml-2 pointer">SHINY</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12">
										<section class="d-flex justify-content-center">
											<button class="h3 text-uppercase btn btn-primary mt-4 mb-2" style="border-radius: 7px;" type="submit">Filter Frames</button>			
										</section>
									</div>
								</div>

							</form>
						</section>

						<?php if(isset($_POST) && !empty($_POST)) { ?>

							<section class="d-flex justify-content-center" id="clearFilter">														
								<p class="h3 text-uppercase btn btn-danger mt-4 mb-2" style="border-radius: 7px;">Clear Filter</p>
							</section>

						<?php } ?>

					</div>
				</div>
			</div>

			<!-- Products -->

			<?php

				// Cycle through Sun array
				for ($i=0; $i < sizeOf($arrSunFrames); $i++) { 

					// Set current data					
					$curStyle = array_values($arrSunFrames)[$i][0]['item_description'];

					// Set current array
					$curArr = array_values($arrSunFrames)[$i];

			?>

			<div class="row mt-4 align-items-strech">
				<div class="col-12">
					<div class="custom-card-header">
						<section class="d-flex justify-content-between" style="border-bottom: 1px solid #000;">
							<p class="h1 font-bold text-uppercase"><?= $curStyle ?></p>
							<p class="h1 font-bold text-uppercase"><?= sizeOf($curArr) ?></p>
						</section>
					</div>
				</div>
			</div>
			<div class="row mt-4 align-items-strech">

			<?php				

				// Cycle through Sun array
				for ($a=0; $a < sizeOf($curArr); $a++) { 

					// Set current data				
					$curImageURL = str_replace("-model", "", $curArr[$a]['image_url']);
					$curColor    = $curArr[$a]['color'];
					$curSKU      = $curArr[$a]['product_code'];

					if($curImageURL == '/images/specs/no-image/no_specs_frame_available_b.png' || $curImageURL == '/ssis/assets/images/frames/no-image/no_specs_frame_available_b.png' || strpos($curImageURL, 'no_specs_frame_available_b')) {
						$curImagePos = '70%';
					}
					elseif(strpos($curImageURL, 'sunniesstudioseyewear')) {
						$curImagePos = '60%';
					}
					else {
						$curImagePos = 'cover';
					}

					$curSwatchShopify = $curArr[$a]['color_swatch_shopify'];
					$curSwatchAlpha   = $curArr[$a]['color_swatch_alpha'];
					$curSwatchNew 	  = $curArr[$a]['color_swatch_new'];

			?>
	    	
	    		<div class="col-md-3 col-sm-12 col-xs-12 mb-4 product-card <?= $curShapeCode ?> <?= $curMaterialCode ?> <?= $curGenColorCode ?> <?= $curFinishCode ?> <?= $curWidthCode ?>">
		    		<div class="col-12 custom-card lg" style="padding: 0;">
						<div class="d-flex flex-column align-items-center justify-content-center" style="height: 100%;">
							<div class="mb-3 text-center" style="background-image: url(<?= $curImageURL ?>); background-repeat: no-repeat; background-size: <?= $curImagePos ?>; background-position: center; height: 25vh; width: 100%; border-bottom: 1px solid #ccc;"></div>
							<div class="text-center mt-2">
								<p class="h5 text-uppercase font-bold"><?= $curColor ?></p>
								<p class="span text-uppercase font-bold"><?= $curSKU ?></p>
							</div>
							<div class="col-12">
								<div class="d-flex justify-content-between mt-5 text-center">
									<div class="col-md-4 col-xs-12 mb-4">
										<p class="span text-uppercase type-label"><?= $curShape ?></p>
									</div>
									<div class="col-md-4 col-xs-12 mb-4">
										<p class="span text-uppercase type-label"><?= $curMaterial ?></p>
									</div>
									<div class="col-md-4 col-xs-12 mb-4">
										<p class="span text-uppercase type-label"><?= $curFinish ?></p>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="d-flex justify-content-between mt-5 text-center section-color-picker">
									<div class="col-md-3 col-xs-12 mb-4">

										<?php

											// Check the color
											if($curSwatchShopify != "") {

												$color_swatch = $curSwatchShopify;

											}
											elseif($curSwatchNew != "") {

												$color_swatch = $curSwatchNew;

											}
											else {

												$color_swatch = $curSwatchAlpha;

											};


										?>

										<div class="color-picker" style="background-color: <?= $color_swatch ?>;"></div>
									</div>
									<div class="col-md-6 col-xs-12 mb-4">
										<input type="color" name="color_picker" class="form-control input-color" value="<?= $color_swatch ?>">
									</div>
									<div class="col-md-3 col-xs-12 mb-4">
										<input type="submit" name="color_picker_new" class="form-control submit-new-color" data-sku="<?= $curSKU ?>" data-hex-code="">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>			

			<?php } ?>			

			</div>

			<?php } ?>

			<?php				

				// Empty array
				if(empty($arrSunFrames)) {

			?>

				<div class="row mt-4 align-items-strech">
					<div class="col-12">
						<div class="custom-card-header">
							<section class="d-flex justify-content-between" style="border-bottom: 1px solid #000;">
								<p class="h1 font-bold text-uppercase">No Frames Found</p>
								<p class="h1 font-bold text-uppercase">0</p>
							</section>
						</div>
					</div>
				</div>

			<?php } ?>

		</div>

	</div>

</div>

<?= get_footer() ?>


<script>

	// Filter Functions
	$('.filter').click(function() {		

		// Show if hidden
		if(!$('#filterWell').hasClass('show')) {
			$('#filterWell').collapse('toggle');			
		}

		// Set active tab
		$('.filter.active').removeClass('active');
		$(this).addClass('active');

		// Grab previous set option
		setFilterReq = $('#filterWell').attr('data-filter-option');
		
		// Set current requested option
		curFilterReq = $(this).data('filter-option');		

		if(curFilterReq == setFilterReq) {
			$('#filterWell').collapse('toggle');
			$('.filter.active').removeClass('active');
		};

		// Fill well
		$('.filter-option-result').hide();
		$('#' + curFilterReq).fadeIn(300);

		// Set data attribute of well
		$('#filterWell').attr('data-filter-option', curFilterReq);

	});

	// View More on Cards
	$('.view-more').click(function() {

		if($(this).hasClass('collapsed')) {			
			$(this).text('View Less');			
		}
		else {
			$(this).text('View More');
		}

	});

	// Reset Filter
	$('#clearFilter').click(function() {

		window.location.href = "/the-archive/";

	});

	// Edit color picker
	$('.submit-new-color').click(function() {

		// Set current data
		var curSKU = $(this).data('sku');
		var hexCode = $(this).parent().parent().find('.input-color').val();

		// Update DB
		$.ajax({

			type: "POST",
			url: "/process/archive/update_color_picker.php",
			data: {brand: "studios", sku: curSKU, hex_code: hexCode},
			success: function(data) {

				// alert("Color successfully updated for SKU " + curSKU);
				alert(data);

			}

		})

	});

</script>