<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if ( !isset($_SESSION) ) {
	session_start();
}

//include($sDocRoot."/ssis/modules/functions.php");
include($sDocRoot."/ssis/modules/includes/grab_stores.php");
include($sDocRoot."/ssis/modules/includes/grab_employee.php");

$generate_pass = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$password = "";
$password2 = "";

for ($i=0; $i < 8; $i++) { 

	$password .= $generate_pass[rand(0, (strlen($generate_pass)-1))];
	$password2 .=$password;
};

?>

<div class="overlay-title">
		<div class="d-flex align-items-center">
			<span class="close-overlay" data-reload="no" data-sidebar="yes"><img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="close" class="img-fluid"></span>
			<p class="h2">Employee</p>
		</div>
	</div>
	<div class="overlay-body">
		<div class="table-default">
			<table>
				<thead>
					<tr>
						<th nowrap>Employee ID</th>
						<th nowrap>Name</th>
						<th nowrap>Branch</th>
						<th nowrap>Position</th>
					</tr>
				</thead>
				<tbody>

				<?php for ( $x = 0; $x < sizeof($arrEmp); $x++ ) : ?> 

					<tr>
						<td nowrap><?= $arrEmp[$x]["emp_id"]; ?></td>
						<td nowrap><?php echo $arrEmp[$x]["first_name"]." ".$arrEmp[$x]["middle_name"].", ".$arrEmp[$x]['last_name']; ?></td>
						<td nowrap><?= $arrEmp[$x]["location"]?></td>
						<td nowrap><?= $arrEmp[$x]["designation"]?></td>
					</tr>
					
				<?php endfor ?>

				</tbody>
			</table>
		</div>
	</div>

	<div class="text-center mt-5">
		<a href="modules/process/bulk-create-user.php"><button class="btn btn-primary">update users</button></a>
	</div>

</div>

<script>

	$(document).ready(function(){
		// Add Div under employee table
		$('#tbl_employee').after('<div id="nav""><nav aria-label="test"><ul class="pagination"></ul></nav></div>');
		
		// initialize Variables
		// initialize number of items
		var rowsShown 	= 30;
		// initilize number of items
		var rowsTotal 	= $('#tbl_employee tbody tr').length;
		// initialize result
		var numPages 	= rowsTotal/rowsShown;
		
		// loop through number of pages
		for(i = 0; i < numPages; i++){
			var pageNum = i + 1;
			// $('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a>');
			// Create pagination
			$('#nav nav ul').append('<li class="page-item"><a class="page-link" rel="'+i+'">'+pageNum+'</a></li>');
		}

		$('#tbl_employee tbody tr').hide();
		$('#tbl_employee tbody tr').slice(0, rowsShown).show();

		$('#nav nav ul li a:first').addClass('active');
		
		$('#nav nav ul li a').bind('click', function(){
			$('#nav nav ul li a').removeClass('active');
			$(this).addClass('active');

			var currPage  	= $(this).attr('rel');
			var startItem 	= currPage * rowsShown;
			var endItem  	= startItem + rowsShown;

			// Animate.
			$('#tbl_employee tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).css('display','table-row').animate({opacity:1}, 300);
		});
	});

</script>