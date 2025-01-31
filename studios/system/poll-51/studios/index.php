<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

////////////////////////////////////////////////

$page = 'poll-51';

$filter_page = 'sunnies_studios';
$group_name = 'poll_51';

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/sidebar_update_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/includes/misc_data.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Send away if not super user
if($_SESSION['user_login']['userlvl'] != 1 && $_SESSION['user_login']['userlvl'] != 20) {

	header('location: /');
	exit;

};

if(!isset($_SESSION['dashboard_login'])){

	echo "<script>window.location = 'http://ssis.sunniessystems.com'</script>";	
	
}
	
?>

<?= get_header($page) ?>

<div class="row no-gutters align-items-strech">

	<?= get_sidebar($page) ?>
	
	<div id="ssis-main" class="col <?= str_replace(' ','-',$page) ?>">
			
		<?= get_topbar($page) ?>
		
		<div class="ssis-content">

			<div class="custom-card-header row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Grab New Poll 51</p>
							<!-- <p class="text-secondary mt-1"></p> -->
						</section>
					</div>
				</div>

			</div>
			<div id="grab-new-poll-51" class="custom-card lg">

				<?php 

					// FTP Connection
					$ftp_server = "13.251.107.240";
					$ftp_user = "sunnies00";
					$ftp_pass = "B!jQ3deu";

					$conn_id = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
						
					// try to login
					if (ftp_login($conn_id, $ftp_user, $ftp_pass)) {

						echo "<p>Connected as $ftp_user@$ftp_server</p><br>";

					} 
					else {

						echo "<p>Couldn't connect as $ftp_user</p><br>";

					}							

					echo "<p>Current directory: " . ftp_pwd($conn_id) . "</p><br>";

					// try to change the directory to somedir
					if (ftp_chdir($conn_id, "SUNNIES")) {

					    echo "<p>Current directory is now: " . ftp_pwd($conn_id) . "</p><br>";

					    // if (ftp_chdir($conn_id, "SUNNIES")) {

						    echo "<p>Current directory is now: " . ftp_pwd($conn_id) . "</p><br>";

						    // get contents of the current directory
							$contents = ftp_nlist($conn_id, ".");

							$remote_file = 'POLL51.csv';
							$local_file = 'POLL51.csv';

							// Empty file
							$handleInit = fopen($local_file, 'w');
							fwrite($handleInit, '');

							// open some file to write to
							$handle = fopen($local_file, 'w');
							$handleResponse = false;

							// try to download $remote_file and save it to $handle
							if (ftp_fget($conn_id, $handle, $remote_file, FTP_ASCII, 0)) {

								echo "<b><p>Data successfully written to $local_file!</p></b><br>";

								$handleResponse = true;

							} 
							else {

								echo "<p>There was a problem while downloading $remote_file to $local_file</p><br>";

								$handleResponse = false;

								exit;

							}
							
						// } 
						// else { 

						//     echo "<p>Couldn't change directory</p><br>";

						// };								

					} 
					else { 

					    echo "<p>Couldn't change directory</p><br>";

					};							

					// close the connection
					ftp_close($conn_id);

				?>

			</div>

			<hr class="spacing">

			<?php

				if($handleResponse) {

					// Poll 51 array
					$arrPoll51 = array_map('str_getcsv', file('POLL51.csv'));
					$numItems = sizeOf($arrPoll51);

				}
				else {

					$arrPoll51 = array();

				};

			?>

			<div class="custom-card-header">
				<div class="row no-gutters flex-column flex-sm-row align-items-sm-center justify-content-sm-between">
					<section>
						<p class="h3 font-bold">New Poll 51 Data</p>
						<p class="text-secondary mt-1"><?= $numItems ?> Items</p>
					</section>					
					<section>
						<div class="row no-gutters flex-column flex-sm-row align-items-sm-center justify-content-sm-between">
							<form name="update-poll-51" method="POST" action="/process/system/studios/poll_51.php" enctype="multipart/form-data">
							    <input type="submit" name="submit" value="Update Main Poll 51" class="btn btn-warning">
							</form>
							<form name="update-poll-51-new" method="POST" action="/process/system/studios/poll_51_new.php" enctype="multipart/form-data" style="margin-left: 15px;">
							    <input type="submit" name="submit" value="Update New Poll 51" class="btn btn-primary">
							</form>
						</div>
					</section>
				</div>
			</div>
			<div id="poll-51-list">
				<div class="table-default auto">
					<div class="table-responsive md">
						<table cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th nowrap>Item Description</th>
									<th nowrap>Item Name</th>
									<th nowrap>Count</th>
									<th nowrap>Item Code</th>
									<th nowrap>Stock</th>
									<th nowrap>Product Number</th>
									<th nowrap>Piece</th>
									<th nowrap>Price</th>
									<th nowrap>Zero 1</th>
									<th nowrap>Zero 2</th>
									<th nowrap>Product Code 2</th>
									<th nowrap>Product Code</th>
									<th nowrap>category</th>
									<th nowrap>collection</th>
									<th nowrap>correct_group_category</th>
									<th nowrap>finish</th>
									<th nowrap>general_color</th>
									<th nowrap>grouping</th>
									<th nowrap>material</th>
									<th nowrap>product_seasonality</th>
									<th nowrap>shape</th>
									<th nowrap>size</th>
									<th nowrap>sub_category</th>
									<th nowrap>sub_color</th>
									<th nowrap>sos_date</th>
									<th nowrap>vnd_srp</th>
									<th nowrap>udf3</th>
									<th nowrap>udf4</th>
									<th nowrap>usd price</th>
									<th nowrap>segment</th>
									<th nowrap>sub segment</th>
									<th nowrap>form group</th>
									<th nowrap>vision</th>
									<th nowrap>house brand</th>
									<th nowrap>s&r price</th>
								</tr>
							</thead>
							<tbody class="tableBody">

								<?php									

									// Cycle through array
									for ($i=0; $i < sizeOf($arrPoll51); $i++) { 
									
										echo 	'<tr>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['0'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['1'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['2'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['3'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['4'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['5'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['6'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['7'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['8'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['9'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['10'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['11'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['12'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['13'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['14'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['15'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['16'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['17'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['18'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['19'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['20'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['21'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['22'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['23'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['24'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['25'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['26'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['27'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['28'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['29'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['30'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['31'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['32'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['33'].'</td>';
										echo 		'<td nowrap>'.$arrPoll51[$i]['34'].'</td>';
										echo 	'</tr>';

									};

								?>

							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>

<?= get_footer() ?>