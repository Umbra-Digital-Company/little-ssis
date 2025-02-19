<?php include "./modules/includes/products/orders_confirmed.php";


?>



<style type="text/css">
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


<?php if (isset($_SESSION['customer_page']) && $_SESSION['customer_page'] !== 'YES'): ?>
    <?php include "./modules/store/access-denied.php"; ?>
<?php else: ?>
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
    </div>


    <div class=" col-12 card mt-4 w-100 p-4 " style="color: #342C29; gap: 1.5rem">
        <div class="d-flex align-items-center ">
            <div style="height: 40px; width: 40px">
                <img src="<?= get_url('images/icons') ?>/icon-user.png" alt="user" class="img-fluid">
            </div>
            <div class="col ml-2">
                <p class="custom-subtitle" style="text-transform: capitalize; font-weight: 700; font-size: 18px;">
                    <?= $_GET['name'] ?>
                </p>
                <p class="custom-subtitle" style=" font-weight: 500; font-size: 14px;">
                    <?= $_GET['age'] ?> <?= $_GET['age'] == 1 || $_GET['age'] == 0 ? 'year' : 'years' ?> old
                </p>
            </div>

        </div>
    </div>





    <div id="bottom-content" class=" d-flex text-center align-items-center justify-content-center mb-5"
        style=" bottom: 0; left: 0; width: 100%; ">
        <div id="bottom-content-inner" style=" width: 575px; padding: 8px 0;">

            <?php if (isset($_SESSION['dispatch_studios_no_access']) && !$_SESSION['dispatch_studios_no_access']) { ?>
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="customer-account text-center">
                            <a href="/face/dispatch-face">
                                <button class="btn btn-not-cancel"  style="height: 56px;">Open Dispatch </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>


            <div class="row">
                <div class="col-12 mt-3">
                    <div class="customer-account text-center">
                        <a href="/v2.0/sis/face/v1.0/?page=store-home">
                            <button class="btn btn-primary" style="height: 56px;">Make a new order</button>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div id="notification" class="notification p-2 text-center align-items-center justify-content-center show "
        style="background-color: #9DE356; height: 48px; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); z-index: 1; max-width: 575px; width: 100%; margin: 0 auto; border-top-left-radius: 20px; border-top-right-radius: 20px;">

        <div id="notification" class="notification d-flex align-items-center justify-content-between "
            style="width: 100%; padding: 0px 10px;">
            <span class="notification-message text-align-center"><?= ($_SESSION['store_type'] == 'ds' || $_SESSION['store_type'] == 'sr' || $_SESSION['store_type'] == 'vs') ? 'Order successfully forwarded to Dispatch' : 'Order has successfully been sent to Cashier' ?></span>
            <button class="btn notification-close" style="background-color: transparent;" onclick="closeNotification()">
                <img src="<?= get_url('images/icons') ?>/icon-close.png" alt="Icon" class="notification-icon">
            </button>
        </div>
</div>
<?php endif; ?>
<script>
    function closeNotification() {
        document.getElementById('notification').classList.remove('show');
        document.getElementById('notification').classList.add('hidden');

    }

    function openNotification() {
        document.getElementById('notification').classList.remove('hidden');
        document.getElementById('notification').classList.add('show');

        setTimeout(closeNotification, 5000);
    }

    window.onload = function() {
        openNotification();
    };
</script>
<!-- <hr class="spacing">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <?php if (isset($_SESSION['dispatch_studios_no_access']) && !$_SESSION['dispatch_studios_no_access']) { ?>
                    <a href="/face/dispatch-face" style="color: #fff; margin-right: 30px;"><button type="button"
                            class="btn btn-black">Go to Dispatch</button></a>
                <?php } ?>
                <a href="/v2.0/sis/face/v1.0/?page=store-home" style="color: #fff"><button type="button"
                        class="btn btn-primary">Go to Home Page</button></a>
            </div>
        </div>
    </div>
</div> -->