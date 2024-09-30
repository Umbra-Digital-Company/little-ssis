<?php include "./modules/includes/products/orders_confirmed.php"; ?>

<div class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel">

    <div class="customized-card mt-4 w-100 p-4 ">
        <div>
            <div class="d-flex justify-content-between align-content-center">
                <p class="custom-title">Order Details</p>
                <svg style="width: 24px; height: 24px" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 15l-6-6l-6 6" />
                </svg>
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
                <div class="my-4">
                    <div class="row no-gutters">
                        <div>
                            <img src="<?= !empty($order['image_url']) ? $order['image_url'] : 'https://via.placeholder.com/120x126' ?>" class="card-img" alt="Product Image">
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
        <img style="height: 30px; width: auto" src="/sis/studios/assets/images/icons/user.svg" alt="user" class="btn-custom-svg">
        <div>
            <p class="custom-title"><?= $profile['full_name'] ?></p>
            <p style="font-size: 14px; font-weight: 500;"><?= $profile['age'] ?> years old</p>
        </div>
    </div>

    <div class="mb-5">

        <?php if (isset($_SESSION['dispatch_studios_no_access']) && !$_SESSION['dispatch_studios_no_access']): ?>
            <a href="/sis/studios/v1.0/?page=transactions" class="btn-custom-white w-100 mt-3 d-flex align-items-center justify-content-center">
                Open Dispatch
            </a>
        <?php endif; ?>

        <a href="/sis/studios/v1.0/?page=store-home" class="btn-custom-blue w-100 mt-3 d-flex align-items-center justify-content-center">
            Make new order
        </a>
    </div>

    <div class="alert alert-warning alert-dismissible fade show text-center border-0 mb-0" role="alert" style="background-color: #9DE356; color: #342C29; font-size: 18px; border-radius: 16px 16px 0 0; margin-top: 6rem;">
        Order has successfully been sent to Cashier
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <svg style="height: 24px; width: 24px" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <path fill="none" stroke="#342C29" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>