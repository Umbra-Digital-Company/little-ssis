<style>
	.personal-card {
		border-radius: 16px !important;
    	background: white !important;
	}
	.label-text{
		color:grey;
		font-size: 14px;
		margin-left: 0px !important;
	}
	.prescription-card {
		border-radius: 16px;
		background: white;
		padding: 24px;
	}
	.list-item {
		height: unset !important;
	}
	.overview-details{
    	padding-top: 80px !important;
	}

	/* DETAILS HEADER */
	.details-header {
		max-width: 572px;
		width: 100%;
		position: fixed;
		z-index: 1000;
		height: 88px;
		background-color: #f0f0f0;
	}

	
	.survey-container {
  padding: 24px;
  background-color: #ffffff;
  height: 142px;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px; /* Adjust as needed */
  border-radius: 16px;
  -webkit-border-radius: 16px;
  -moz-border-radius: 16px;
  -ms-border-radius: 16px;
  -o-border-radius: 16px;
  -webkit-box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.06) !important;
  -moz-box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.06) !important;
  box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.06) !important;
}

		.details-header canvas {
		width: 100%;
		height: 35px; }
		
		.details-header p {
			padding-bottom: 20px; }
		.details-header p.nav-2 {
			font-size: 18px;
			color: #919191; }
			.details-header p.nav-2.active {
				font-size: 18px;
				color: black; 
    			border-bottom: 4px solid black;}
			
		.details-header p.nav-3 {
			font-size: 18px;
			color: #919191; }
			.details-header p.nav-3.active {
				font-size: 18px;
				color: black; 
    			border-bottom: 4px solid black;}
			
		.details-header p.nav-4 {
			font-size: 18px;
			color: #919191; }
			.details-header p.nav-4.active {
				font-size: 18px;
				color: black; 
    			border-bottom: 4px solid black;}
			

	/* END DETAILS HEADER */

	.table-prescription table{
		width: 100%;
		text-align: center;
	}
	.table-prescription table th, td {
        padding: 10px;
    }

	/* HEADER EDITS */
	#admin-bar {
		z-index: 200 !important;
	}
	
	header{
		z-index: 100 !important;
	}
	

	body header #navigation-tab {
		padding-top: 20px;
		background-color: #f0f0f0;
		margin-top: 88px !important;
		height: 102px !important;
		max-height: 0px !important;
	}

	.gender-icon-overview{
		display: block;
		margin-left: 11px;
		margin-right: 14px;
		/* background-color: #2a2323; */
		background-repeat: no-repeat;
		background-size: 40px;
		background-position: center;
		width: 50px;
		height: 50px;
		border-radius: 50px;
		-webkit-border-radius: 50px;
	}
	.card-name{
		height: 104px;
		padding: 24px;
	}
	.card-info{
		height: 332px;
		padding: 24px;
	}
	.card-info .col-12{
		padding-bottom: 16px;
	}
	.card-other-info{
		height: 90px;
		padding: 24px;
	}
	.prescription-card p {
		font-size: 16px !important;
	}

	.total-text{
		font-size: 18px !important;
	}
	.store-location .store-address {
		font-size: 14px !important;
	}
	.patient-details{
		padding-left: 14px;
		background-color: white;
		height: 592px;
		border-radius: 16px;
		box-shadow: 0 2px 3px 0 rgba(42, 35, 35, 0.25);
	}
	.patient-details-item{
		padding-bottom: 10px;
	}
	.location-details{
		padding-left: 14px;
		background-color: white;
		height: 160px;
		border-radius: 16px;
		box-shadow: 0 2px 3px 0 rgba(42, 35, 35, 0.25);
	}
	.signiture-details{
		padding-top: 1px;
		padding-left: 14px;
		background-color: white;
		height: 244px;
		border-radius: 16px;
		box-shadow: 0 2px 3px 0 rgba(42, 35, 35, 0.25);
	}
	.occupation{
		color: #919191;
    	font-size: 14px;
	}

	body>.container {
        margin-top: 20px;
    }

    .card {
        width: 100%;
        border: none;
        border-radius: 16px;
        padding: 24px;
        overflow: hidden;
    }

    .card-body {

        padding: 0;

    }
</style>


<?php
  include("./modules/includes/grab_profile.php");
  include("./modules/includes/products/orders_confirmed.php");
?>

<div class="details-header">
	<div class="d-flex align-items-center" style="border-bottom: 2px solid #aaa;padding-top: 20px;">
		<!-- <a href="./?page=ordermanagement<?= ( isset($_GET['theme']) ) ? '&theme='.$_GET['theme'] : '' ?>" class="col text-center logout"><canvas class="d-block nav-1"></canvas></a> -->
		<a href="#personal" class="col text-center "><p class="d-block nav-2 active">Personal info</p></a>
		<a href="#order" class="col text-center"><p class="d-block nav-4">Order details</p></a>
	</div>
</div>


<div class="overview-details">

	<div id="personal-content" class="details-content active">
		<!-- <p class="text-uppercase font-bold text-primary">personal information</p> -->
		<div class="personal-card mt-3 card-name">
			<div class="d-flex align-items-center justify-content-between no-gutters">
				<?php $gender_icon = ( $arrCustomerProfile[0]["gender"] == 'male' ) ? 'male' : 'female' ?>
				<div class="col">
					<div class="d-flex align-items-center">
						<canvas class="gender-icon-overview" style="background-image: url(/v2.0/sis/face/assets/images/icons/icon-gender-male.png)" alt="<?= $gender_icon ?>)"></canvas>
						<section>
							<p class="font-bold"><?= ucwords($arrCustomerProfile[0]["first_name"] . ' ' . $arrCustomerProfile[0]["middle_name"] . ' ' . $arrCustomerProfile[0]["last_name"]) ?></p>
							<p class="occupation"><?= ( $arrCustomerProfile[0]["occupation"] != NULL ) ?ucwords($arrCustomerProfile[0]["occupation"]) : 'No Occupation' ?></p>
						</section>
					</div>
				</div>
			</div>
		</div>

		<div class="personal-card mt-3 card-info">

			<div class="col-12">
				<span class="label-text">Birthday</span>
				<p><?= cvdate(2, $arrCustomerProfile[0]["birthday"]) ?></p>
			</div>
			
			<div class="col-12">
				<span class="label-text">Age</span>
				<p><?= $arrCustomerProfile[0]['age'] ?> Years Old</p>
			</div>
			
			<div class="col-12">
				<span class="label-text">Address</span>
				<p><?php
					$address_parts = array(
						$arrCustomerProfile[0]["address"],
						str_replace('-', ' ', $arrCustomerProfile[0]["barangay"]),
						str_replace('-', ' ', $arrCustomerProfile[0]["city"]), 
						str_replace('-', ' ', $arrCustomerProfile[0]["province"])
					);
					
					// Filter out N/A values
					$address_parts = array_filter($address_parts, function($part) {
						return $part != 'N/A' && $part != 'n/a';
					});
					
					// If all parts are N/A, show single N/A
					echo empty($address_parts) ? 'N/A' : ucwords(implode(', ', $address_parts));
				?></p>
			</div>
			
			<div class="col-12">
				<span class="label-text">Contact number</span>
				<p><?= str_replace('63', '+63 ', str_replace('-', ' ', $arrCustomerProfile[0]["phone_number"])) ?></p>
			</div>
			
			<div class="col-12">
				<span class="label-text">Email Address</span>
				<p><?= $arrCustomerProfile[0]["email_address"] ?></p>
			</div>

		</div>

		<!-- <p class="text-uppercase font-bold text-primary mt-4">joining date</p> -->
		<div class="personal-card mt-3 card-other-info">
			
				<div class="col-12">
					<!-- <div class="details-list"> -->
						<span class="label-text">Join date</span>
						<p><?= cvdate(2,$arrCustomerProfile[0]["date_created"]) ?></p>
					<!-- </div> -->
				</div>
			
		</div> 
    </div>

	<div id="order-content" class="details-content">
	<div class="frame-style" data-style="">
        <!-- <div class="row">

        <p class="col-12 text-secondary mt-3"><b>Name</b>: <?= $_GET['name'] ?></p>
        <p class="col-12 text-secondary mb-3"><b>Order ID</b>: <?= $_GET['order_id'] ?></p>
    </div> -->
        <div class="card mt-4">
            <div class="d-flex justify-content-between">
                <p class="text-uppercase font-bold mb-3 " style="font-size: 18px; font-weight: 650">Order Details</p>

                <div style="height: 24px; width: 24px; cursor: pointer" id="toggle-chevron">
                    <img src="<?= get_url('images/icons') ?>/icon-down-chevron.png" alt="user" class="img-fluid">
                </div>
            </div>


            <?php $total_price = 0 ?>
            <?php for ($i = 0; $i < count($arrOrdersConfirmed); $i++) { ?>

                <?php
                $dataMid = '';
                $arrOrdersConfirmed[$i]['type'] = '';
                $item_name = strtoupper($arrOrdersConfirmed[$i]['item_description']);
                $product_code = strtoupper($arrOrdersConfirmed[$i]['product_code']);
                if (strstr($item_name, 'PAPER BAG')) {
                    $arrOrdersConfirmed[$i]['type'] = 'Merch';
                    $dataMid = 'Merch';
                } elseif (strstr($item_name, 'HARDCASE') || strstr($item_name, 'ANTI FOG') || strstr($item_name, 'DAILY SHIELD') || strstr($item_name, 'DAILY MASK') || strstr($item_name, 'DAILY DUO')) {
                    $arrOrdersConfirmed[$i]['type'] = 'Merch';
                    $dataMid = 'Merch';
                } elseif (!strstr($product_code, 'C') && !strstr($product_code, 'PL') && !strstr($product_code, 'P') && !strstr($product_code, 'H') && !strstr($product_code, 'SC') && !strstr($product_code, 'SGC') && !strstr($product_code, 'SCL') && !strstr($product_code, 'SW') && !strstr($product_code, 'SS') && !strstr($product_code, 'ST') && !strstr($item_name, 'AGENDA')) {
                    $arrOrdersConfirmed[$i]['type'] = 'Frame Style';

                    $dataMid = 'Frame';
                }
                if ($arrOrdersConfirmed[$i]['price'] > 0) {
                } elseif (strstr(strtolower($arrOrdersConfirmed[$i]['item_description']), 'paper bag') || strstr(strtolower($arrOrdersConfirmed[$i]['item_description']), 'sac') || strstr(strtolower($arrOrdersConfirmed[$i]['item_description']), 'receipt')) {

                    continue;
                }

                if ($arrOrdersConfirmed[$i]['dispatch_type'] == 'packaging') {
                    continue;
                }
                ?>



                <?php

                $total = $arrOrdersConfirmed[$i]['count'] * $arrOrdersConfirmed[$i]['price'];
                $total_price += $total;

                ?>


                <div class="card-body mb-3 order-item">
                    <div class="d-flex align-items-center">
                        <div class="image-wrapper"
                            style="border-radius: 16px; background-color: #e8e8e8; height: 120px; width: 126px; background-image: url(<?= $arrOrdersConfirmed[$i]['image_url'] ?>); background-repeat: no-repeat; background-size: 80%; background-position: center;">
                        </div>
                        <div class="ml-3">
                            <span style="text-transform: uppercase; font-size: 18px; font-weight: 700;"
                                class="mt-2 product-title">
                                <?= $arrOrdersConfirmed[$i]['item_description'] ?>
                            </span>
                            <p class="mt-1" style=" font-size: 18px; font-weight: 400; color: #727272;">
                                <!-- <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : 'P' ?> -->
                                P
                                <?= number_format($total, 2) ?>
                            </p>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <script>
                document.getElementById('toggle-chevron').addEventListener('click', function() {
                    const orderItems = document.querySelectorAll('.order-item');
                    const isCollapsed = orderItems[1] && orderItems[1].style.display === 'none';


                    orderItems.forEach((item, index) => {
                        if (index !== 0) {
                            item.style.display = isCollapsed ? 'block' : 'none';
                        }
                    });


                    const chevronIcon = this.querySelector('img');
                    chevronIcon.src = isCollapsed ? '<?= get_url("images/icons") ?>/icon-up-chevron.png' : '<?= get_url("images/icons") ?>/icon-down-chevron.png';
                });


                window.onload = function() {
                    const orderItems = document.querySelectorAll('.order-item');
                    orderItems.forEach((item, index) => {
                        if (index !== 0) {
                            item.style.display = 'none';
                        }
                    });
                };
            </script>

            <hr class="spacing">
            <div class="d-flex justify-content-between">
                <div class="text-center">
                    <p style="font-size: 18px; font-weight: 700;">Total amount</p>
                </div>
                <div class="text-center">
                    <p style="font-size: 18px; font-weight: 700; color: #956E46;">
                        <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : 'P' ?><?= number_format($total_price, 2) ?>
                    </p>
                </div>
            </div>
        </div>
				
				<div class="survey-container mt-4 d-flex justify-content-between align-items-center" style="height: 80px;">
					<p class="text-uppercase font-bold">Customer Feedback</p>
					<div class="d-flex" style="justify-content: center; gap: 20px;">
						<?php 
							$feedback = $arrOrdersConfirmed[0]['feedback'];
							if ($feedback === 'happy') {
								echo '<img class="emoji-btn" src="../assets/images/icons/smile_colored.png" alt="Happy Emoji" style="width: 48px; height: 48px;">';
							} elseif ($feedback === 'sad') {
								echo '<img class="emoji-btn" src="../assets/images/icons/frown_colored.png" alt="Sad Emoji" style="width: 48px; height: 48px;">';
							} else {
								// If no feedback, show both emojis
								echo '<img class="emoji-btn" src="../assets/images/icons/smile_colored.png" alt="Happy Emoji" style="width: 48px; height: 48px;">';
								echo '<img class="emoji-btn" src="../assets/images/icons/frown_colored.png" alt="Sad Emoji" style="width: 48px; height: 48px;">';
							}
						?>
					</div>
					<input type="hidden" id="feedback" name="feedback" value="<?php echo htmlspecialchars($feedback); ?>">					
				</div>
    </div>

	</div>

	
</div>


<script>
    $(document).ready(function() {
        const $dispatchContent = $('#dispatch-content');
        const $paymentContent = $('#payment-content');
        const $transactionsOption = $('.transactions-option');
        const $switchAnimation = $('.switch-animation');

        const activeParam = new URLSearchParams(window.location.search).get('active') || 'dispatch';

        $dispatchContent.hide();
        $paymentContent.hide();
        $transactionsOption.removeClass('active');

        if (activeParam === 'dispatch') {
            $transactionsOption.filter('[href="#dispatch-content"]').addClass('active');
            $dispatchContent.fadeIn();
            $switchAnimation.removeClass('slide');
        } else {
            $transactionsOption.filter('[href="#payment-content"]').addClass('active');
            $paymentContent.fadeIn();
            $switchAnimation.addClass('slide');
        }
    });


    $(document).ready(function() {
        if ($('.transaction-navigation').length) {

            $('.transactions-option').on('click', function(e) {
                var target = $(this).attr('href');
                e.preventDefault();

                var url = new URL(window.location.href);
                url.searchParams.delete('active');

                if (target == '#dispatch-content') {
                    $('.switch-animation').removeClass('slide');
                    url.searchParams.set('active', 'dispatch');
                } else {
                    $('.switch-animation').addClass('slide');
                    url.searchParams.set('active', 'payment');
                }

                history.replaceState(null, '', url);

                $(this).addClass('active').siblings().removeClass('active');
                $(target).fadeIn().addClass('active').siblings('.transactions-content').hide().removeClass('active');
            });


        }
    });

    const resetValue = (val) => {
		$("#edit-personal-content .form-group input").each(function () {
		$(this).val($(this).data(val));
		});
	};

	const toggleProfileContent = (target) => {
		$(".details-content").each(function () {
		if ($(this).attr("id") == target) {
			$(this).fadeIn().addClass("active");
		} else {
			$(this).hide().removeClass("active");
		}
		});
	};

	if ($(".overview-details").length) {
		$(".details-header a").on("click", function (e) {
		resetValue("old");
		if (!$(this).hasClass("logout")) {
			e.preventDefault();
			var target = $(this).attr("href").replace("#", "") + "-content";

			$(this).siblings().find("p").removeClass("active");
			$(this).find("p").addClass("active");
			toggleProfileContent(target);
		}
		});
	}
</script>