<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page_url = 'dispatch-studios';
$page = 'dispatch';
$filter_page = 'dispatch_studios';
$group_name = 'sunnies_studios';

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

function getDateModify($date){
$date = explode('-', $date);

	switch($date[1]){
		case '01': return "Jan ".$date[2].", ".$date[0]; break;
		case '02': return "Feb ".$date[2].", ".$date[0]; break;
		case '03': return "Mar ".$date[2].", ".$date[0]; break;
		case '04': return "Apr ".$date[2].", ".$date[0]; break;
		case '05': return "May ".$date[2].", ".$date[0]; break;
		case '06': return "Jun ".$date[2].", ".$date[0]; break;
		case '07': return "Jul ".$date[2].", ".$date[0]; break;
		case '08': return "Aug ".$date[2].", ".$date[0]; break;
		case '09': return "Sep ".$date[2].", ".$date[0]; break;
		case '10': return "Oct ".$date[2].", ".$date[0]; break;
		case '11': return "Nov ".$date[2].", ".$date[0]; break;
		case '12': return "Dec ".$date[2].", ".$date[0]; break;

	}
}

?>


<?= get_header($page) ?>

<div class="row no-gutters align-items-strech">

	<?= get_sidebar($page_url) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page) ?>">
		<?= get_topbar($page) ?>
		<div class="ssis-content p-0">
			<?php require 'index_dispatch.php'; ?>
		</div>
	</div>

</div>

<?= get_footer() ?>
</script>