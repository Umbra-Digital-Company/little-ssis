<?php

if (!isset($_SESSION['customer_id'])) {
?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>
<?php
} else {
    include "./modules/includes/products/grab_antirad_frames.php";
?>


    <div class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel">

        <div class="custom-subtitle my-4">
            Total items <span class="custom-title"><?= count($arrCart); ?></span>
        </div>
        <?php foreach ($arrCart as $order) :

            if (floatval($order['price']) > 0) {
            } else if (
                strpos(strtolower($order['item_description']), 'paper bag') !== false ||
                strpos(strtolower($order['item_description']), 'sac') !== false ||
                strpos(strtolower($order['item_description']), 'receipt') !== false
            ) {
                continue;
            }

            if ($order['dispatch_type'] == 'packaging') {
                continue;
            }

            $merchItem = (strpos($order['product_code_order'], 'M100') > -1) ? 'prod-item="merch"' : 'prod-item="frame"';
            $merchItem = (strpos($order['product_upgrade'], 'G100') > -1) ? 'prod-item="antirad"' : $merchItem;



        ?>
            <div class="customized-card my-4">

                <div class="d-flex justify-content-between">
                    <div class=" no-gutters d-flex ">

                        <img src="<?= !empty($order['image_url']) ? $order['image_url'] : 'https://via.placeholder.com/120x126' ?>" class="card-img" alt="Product Image" style="width: 120px; height: 126px; object-fit: cover;">

                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body d-flex flex-column gap-3 pt-0 pb-0 pr-0">
                                <p class="custom-title"><?= $order['style'] ?></p>
                                <p class="custom-subtitle"><?= $order['color'] ?></p>
                                <p class="custom-subtitle" style="color: #919191;">
                                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?> <?= $order['price'] ?>
                                </p>
                                <div class="d-flex align-items-center justify-content-left mx-3">
                                    <div class="button-container">
                                        <input type="button" class="count_decrement custom-button" price="<?= $order['price'] ?>" group-orders-specs-id="<?= $order['group_orders_specs_id'] ?>" value="-">
                                    </div>

                                    <input type="text" style="font-size: 16px;" class="form-control count_num bg-transparent mx-3" group-orders-specs-id="<?= $order['group_orders_specs_id'] ?>" value="<?= $order['count'] ?>" readonly>


                                    <div class="button-container">
                                        <input type="button" class="count_increment custom-button" <?= $merchItem ?> price="<?= $order['price'] ?>" group-orders-specs-id="<?= $order['group_orders_specs_id'] ?>" product-code="<?= $order['product_code'] ?>" value="+">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="d-flex align-items-center mx-2">
                        <button class="btn remove_item bg-transparent d-flex" orders-specs-id=<?= $order['group_orders_specs_id'] ?> style="cursor: pointer;">
                            <img src="/sis/studios/assets/images/icons/icon-delete.svg" alt="plus" class="btn-custom-svg">
                        </button>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>


        <a href="/sis/studios/v1.0/?page=select-store" class="btn-custom-white w-100 mt-3 d-flex align-items-center justify-content-center">
            Add Item
            <img src="/sis/studios/assets/images/icons/add-circle-plus.svg" alt="plus" class="btn-custom-svg">
        </a>

        <a href="?page=order-confirmation&bpage=<?= $_GET['page'] ?>" class="btn-custom-blue w-100 mt-3 d-flex align-items-center justify-content-center">
            Checkout
        </a>
    </div>

<?php } ?>


<script>
    var arrCart = <?= json_encode($arrCart) ?>;
    $(document).on('click', '.count_decrement ', function() {
        _this = $(this);

        current_value = $(this).parent().parent().find('.count_num').val();
        if (current_value > 1) {
            $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
            arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
            arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

            $.post("/sis/studios/func/process/remove_item.php", {
                orders_specs_id: arrOrdersSpescIdRemove
            }, function() {

                arrOrdersSpescId.pop();
                arrOrdersSpescId = arrOrdersSpescId.join(",");
                index = arrCart.findIndex(item => item.group_orders_specs_id === _this.attr('group-orders-specs-id'));
                arrCart[index].group_orders_specs_id = arrOrdersSpescId;
                arrCart[index].orders_specs_id = arrOrdersSpescId;
                arrCart[index].count = arrCart[index].count - 1;
                _this.attr('group-orders-specs-id', arrOrdersSpescId);
                _this.parent().parent().find('span').eq(1).find('.count_increment').attr('group-orders-specs-id', arrOrdersSpescId);
                _this.parent().parent().parent().parent().parent().find('.count_times').text(arrCart[index].count);
                t_price = parseFloat(arrCart[index].count) * parseInt(_this.attr('price'));
                _this.parent().parent().parent().parent().parent().find('.t_price').text(t_price);
                totalCount();
            });

        }
    });

    $(document).on('click', '.count_increment', function() {
        _this = $(this);
        arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

        processItem = '';
        attrProdItem = $(this).attr('prod-item');

        //    For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
        itemProd = (attrProdItem != 'frame') ? '_' + attrProdItem : '';
        $.post("/sis/studios/func/process/add_to_bag" + itemProd + ".php", {
                studios_product_code: _this.attr('product-code')
            },
            function(result) {
                //console.log(result);
                arrOrdersSpescId.push(result);
                arrOrdersSpescId = arrOrdersSpescId.join(",");
                index = arrCart.findIndex(item => item.group_orders_specs_id === _this.attr('group-orders-specs-id'));
                // console.log(index, "test")
                arrCart[index].group_orders_specs_id = arrOrdersSpescId;
                arrCart[index].orders_specs_id = arrOrdersSpescId;
                arrCart[index].count = parseInt(arrCart[index].count) + 1;
                _this.attr('group-orders-specs-id', arrOrdersSpescId);
                _this.parent().parent().find('span').eq(0).find('.count_decrement').attr('group-orders-specs-id', arrOrdersSpescId);
                current_value = _this.parent().parent().find('.count_num').val();
                _this.parent().parent().find('.count_num').val(parseInt(current_value) + 1);
                _this.parent().parent().parent().parent().parent().find('.count_times').text(arrCart[index].count);
                t_price = parseFloat(arrCart[index].count) * parseInt(_this.attr('price'));
                _this.parent().parent().parent().parent().parent().find('.t_price').text(t_price);
                totalCount();
            });
    });

    $(document).on("click", ".remove_item", function() {
        let orderSpecsId = $(this).attr('orders-specs-id');
        // console.log("Order Specs ID: " + orderSpecsId);
        $.post("/sis/studios/func/process/remove_item.php", {
            orders_specs_id: orderSpecsId
        }, function() {
            totalCount();
            location.reload();
        });

    })

    // $(this).on('click', '.remove_item', function() {
    //     let this_div = $(this);
    //     let remove = $.post("/sis/studios/func/process/remove_item.php", {
    //         orders_specs_id: this_div.attr('orders-specs-id')
    //     }, function() {});
    //     $.when(remove).done(function() {
    //         arrCart = arrCart.filter(item => item.group_orders_specs_id !== this_div.attr('orders-specs-id'));
    //         //console.log(arrCart);
    //         totalCount();
    //         this_div.parent().parent().parent().remove();
    //         if (arrCart.length == 0) {
    //             $("#btn-sect").html(itemCart());
    //         }
    //     });
    // });

    const totalCount = () => {
        let value = 0;
        for (i = 0; i < arrCart.length; i++) {
            if (arrCart[i].dispatch_type == 'packaging') {
                continue;
            }
            value += ((arrCart[i].item_description.toLowerCase().indexOf('paper bag') == -1 && arrCart[i].item_description.toLowerCase().indexOf('sac') == -1 && arrCart[i].item_description.toLowerCase().indexOf('receipt') == -1) || parseFloat(arrCart[i].price) > 0) ? parseInt(arrCart[i].count) : 0;
        }

        $('.count').text(value);
    }
</script>