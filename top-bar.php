<?php

function get_topbar($page = "") {

	global $conn;
	
	$arrUsers = array();

	$query  =   "SELECT 
					u.id,
					u.first_name,
					u.last_name
				FROM 
					users u
				WHERE
					u.id = '".$_SESSION["id"]."'";

	$grabParams = array(
		'id',
		'first_name',
		'last_name'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
		
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrUsers[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

	}; 
	
	?>

	<?php if ( $page != 'ssis' ) { ?>

		<div id="topbar">
			<div class="row no-gutters align-items-center justify-content-between">
				<div class="col">
					<div class="row no-gutters align-items-center">
						<div class="burger d-xl-none mr-4">
							<span class="burger-icon"></span>
							<span class="burger-icon"></span>
							<span class="burger-icon"></span>
						</div>
						<h1 class="h2 text-white"><?= ucwords(str_replace('-',' ',$page)) ?></h1>
					</div>
				</div>
				<div class="account">
					<div class="d-flex align-items-center justify-content-end">
						<p class="d-none d-md-block text-white"><?= ucwords($arrUsers[0]['first_name'] . ' ' . $arrUsers[0]['last_name']); ?></p>
						<img src="<?= get_url('images/icons/icon-account.png') ?>" alt="account" class="img-fluid ml-3">
					</div>
				</div>
			</div>
		</div>

	<?php } else { 
		
		global $arrCart;
		$order_id = isset( $_SESSION['order_id'] ) ? $_SESSION['order_id'] : '0'; 
		
		?>

		<div id="topbar">
			<div class="row no-gutters align-items-center justify-content-between">
				<div>
					<div class="row no-gutters align-items-center">
						<div class="burger d-xl-none mr-0 mr-lg-4">
							<span class="burger-icon"></span>
							<span class="burger-icon"></span>
							<span class="burger-icon"></span>
						</div>
					</div>
				</div>
				<h1 class="text-center text-white font-bold m-0" style="font-size:24px;">Sunnies Specs</h1>
				<a href="/store/shop/?bag=<?= $order_id ?>" class="order_review" data-count="<?= isset($arrCart) ? count($arrCart) : '0' ?>">
					<img src="<?= get_url('images/icons/icon-bag-white.png') ?>" alt="bag" class="img-fluid">
				</a>
			</div>
		</div>

	<?php } ?>

<?php } ?>