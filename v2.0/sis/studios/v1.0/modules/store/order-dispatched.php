<?php include "./modules/includes/products/orders_confirmed.php"; ?>



<?php if (isset($_SESSION['customer_page']) && $_SESSION['customer_page'] !== 'YES'): ?>
    <?php include "./modules/store/access-denied.php"; ?>

<?php else: ?>
    <div class="mx-2">

        <div class="customized-card mt-4 w-100 p-4 ">
            <div>
                <div class="d-flex justify-content-between align-content-center">
                    <p class="custom-title">Order Details</p>
                    <div style="height: 24px; width: 24px; cursor: pointer" id="toggle-chevron">
                        <img src="<?= get_url('images/icons') ?>/icon-down-chevron.png" alt="user" class="img-fluid">
                    </div>
                </div>

                <?php
                foreach ($arrOrdersConfirmed as $order):
                    $dataMid = '';
                    $order['type'] = '';
                    $item_name = strtoupper($order['item_description']);
                    $product_code = strtoupper($order['product_code']);

                    // Define the types based on item names
                    $merch_keywords = ['PAPER BAG', 'HARDCASE', 'ANTI FOG', 'DAILY SHIELD', 'DAILY MASK', 'DAILY DUO'];
                    $merch_found = false;

                    // Check if the item is of type 'Merch'
                    foreach ($merch_keywords as $keyword) {
                        if (strstr($item_name, $keyword)) {
                            $order['type'] = 'Merch';
                            $dataMid = 'Merch';
                            $merch_found = true;
                            break;
                        }
                    }

                    // Check if the item is of type 'Frame Style'
                    if (!$merch_found && !preg_match('/^[C|PL|P|H|SC|SGC|SCL|SW|SS|ST]/', $product_code) && !strstr($item_name, 'AGENDA')) {
                        $order['type'] = 'Frame Style';
                        $dataMid = 'Frame';
                    }

                    // Skip to the next iteration if price is 0 or item description indicates a non-sellable item
                    if ($order['price'] <= 0 && (
                        strstr(strtolower($item_name), 'paper bag') ||
                        strstr(strtolower($item_name), 'sac') ||
                        strstr(strtolower($item_name), 'receipt')
                    )) {
                        continue;
                    }

                    // Skip if dispatch type is 'packaging'
                    if ($order['dispatch_type'] === 'packaging') {
                        continue;
                    }

                ?>
                    <div class="my-4 order-item">
                        <div class="row no-gutters">
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="card-img-container" style="border-radius: 8px; height: 150px; width: 100%; display: flex; justify-content: center; align-items: center;">
                                    <img src="<?= !empty($order['image_url']) ? $order['image_url'] : '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png' ?>" class="object-fit-contain" alt="Product Image" style="max-height: 100%; max-width: 100%; border-radius: 8px;">
                                </div>
                            </div>
                            <div class="col-md-8 d-flex align-items-center">
                                <div class="card-body d-flex flex-column gap-3 pt-0 pb-0 pr-0">
                                    <p class="custom-title"><?= $order['item_description'] ?></p>
                                    <p class="custom-subtitle"><?= $order['product_code'] ?></p>
                                    <p class="custom-subtitle" style="color: #919191;">
                                        <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>
                                        <?= number_format($order['price'], 2) ?>
                                        <?php if ($order['count'] > 1): ?>
                                            x <?= $order['count'] ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>



                <hr>

                <?php
                $total_price = 0;
                $voucher_amount = 0;

                if (!empty($arrOrdersConfirmed) && isset($arrOrdersConfirmed[0]['promo_code_amount'])) {
                    $voucher_amount = $arrOrdersConfirmed[0]['promo_code_amount'];
                }

                foreach ($arrOrdersConfirmed as $item):
                    if ($item['price'] > 0):
                        $total_price += $item['price'] * $item['count'];
                    endif;
                endforeach;

                ?>
                <div class="d-flex justify-content-between">
                    <p class="custom-title">Total amount</p>
                    <p class="custom-title" style="color: #0B5893;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?><?= number_format($total_price - $voucher_amount, 2) ?></p>
                </div>
            </div>

        </div>

        <div class="customized-card my-4 w-100 p-4 d-flex align-items-center" style="gap: 1rem">
            <img style="height: 30px; width: auto" src="/v2.0/sis/studios/assets/images/icons/user.svg" alt="user" class="btn-custom-svg">
            <div>
                <p class="custom-title"><?= $profile['full_name'] ?></p>
                <p style="font-size: 14px; font-weight: 500;"><?= $profile['age'] ?> years old</p>
            </div>
        </div>

        <div class="mb-5">

            <?php if (isset($_SESSION['dispatch_studios_no_access']) && !$_SESSION['dispatch_studios_no_access']): ?>
                <a href="/v2.0/sis/studios/v1.0/?page=transactions" class="btn-custom-white w-100 mt-3 d-flex align-items-center justify-content-center">
                    Open Dispatch
                </a>
            <?php endif; ?>

            <a href="/v2.0/sis/studios/v1.0/?page=store-home" class="btn-custom-blue w-100 mt-3 d-flex align-items-center justify-content-center">
                Make new order
            </a>
        </div>


        <div class="alert alert-warning alert-dismissible fade show text-center border-0 mb-0 " role="alert" style="background-color: #9DE356; color: #342C29; font-size: 18px; border-radius: 16px 16px 0 0; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); z-index: 1; width: 575px;">
           <?= ($_SESSION['store_type'] == 'ds' || $_SESSION['store_type'] == 'sr' || $_SESSION['store_type'] == 'vs') ? 'Order successfully forwarded to Dispatch' : 'Order has successfully been sent to Cashier' ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <img src="<?= get_url('images/icons') ?>/icon-close.png" alt="Icon" class="notification-icon">
            </button>
        </div>

    </div>
<?php endif; ?>


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
        chevronIcon.src = isCollapsed ? '<?= get_url("images/icons") ?>/icon-down-chevron.png' : '<?= get_url("images/icons") ?>/icon-up-chevron.png';
    });


    // window.onload = function() {
    //     const orderItems = document.querySelectorAll('.order-item');
    //     orderItems.forEach((item, index) => {
    //         if (index !== 0) {
    //             item.style.display = 'none';
    //         }
    //     });
    // };
</script>