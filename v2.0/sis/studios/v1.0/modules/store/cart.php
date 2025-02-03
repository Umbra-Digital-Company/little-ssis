<?php if (!isset($_SESSION['customer_id'])): ?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/v2.0/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>
<?php else:
    include "./modules/includes/products/grab_antirad_frames.php";

?>

    <div class="mx-2 ">
        <div class="custom-subtitle my-4" style="height: 48px;  display: flex; align-items: center; justify-content: start;  ">
            Total items
            <span class="custom-title" style="margin-left: 12px;">
                <?= count(array_filter($arrCart, function ($item) {
                    return $item['dispatch_type'] !== 'packaging';
                })) ?>
            </span>
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
            <div class="customized-card my-4" style="box-shadow: 0 1px 3px 0 rgba(54, 72, 46, 0.3);">

                <div class="d-flex justify-content-between">
                    <div class=" no-gutters d-flex ">

                    <img src="<?= !empty($order['image_url']) ? $order['image_url'] : '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png' ?>"
                    class="card-img" alt="Product Image"
                    style="width: 120px; height: 126px; object-fit: <?= !empty($order['image_url']) ? 'cover' : 'contain' ?>; border-radius: 8px;">

                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body d-flex flex-column gap-3 pt-0 pb-0 pr-0">
                                <p class="custom-title"><?= ucwords(strtolower ($order['style']))?></p>
                                <p class="custom-subtitle"><?= ucwords(strtolower($order['color'])) ?></p>
                                <p class="custom-subtitle" style="color: #919191;">
                                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : 'P' ?><?= $order['price'] ?>
                                </p>

                                <div class="d-flex align-items-center mx-1 mt-2">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="button" id="btn-decrement" class="count_decrement custom-button" price="<?= $order['price'] ?>" group-orders-specs-id="<?= $order['group_orders_specs_id'] ?>"
                                            style="height: 40px; width: 48px; background: #fff;">
                                            <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="minus"
                                                style="height: 24px; width: 24px;">
                                        </button>
                                    </div>

                                    <input type="text" class="form-control count_num" value="<?= $order['count'] ?>"
                                        style="background-color: transparent; border: 0; font-size: 16px; text-align: center; width: 50px;"
                                        readonly>

                                    <div class="d-flex justify-content-center">
                                        <button type="button" id="btn-increment" class="count_increment custom-button" <?= $merchItem ?> price="<?= $order['price'] ?>" group-orders-specs-id="<?= $order['group_orders_specs_id'] ?>" product-code="<?= $order['product_code'] ?>"
                                            style="height: 40px; width: 48px; background: #fff;">
                                            <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                                style="height: 24px; width: 24px;">
                                        </button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="d-flex align-items-center mx-2">
                        <button class="btn remove_item bg-transparent d-flex" orders-specs-id=<?= $order['group_orders_specs_id'] ?> style="cursor: pointer;">
                            <img src="<?= get_url('images/icons') ?>/icon-delete.svg" alt="remove" class="btn-custom-svg">
                        </button>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>


        <a href="/v2.0/sis/studios/v1.0/?page=select-store" class="btn-custom-white w-100 d-flex align-items-center justify-content-center">
            Add Item
            <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="plus" class="btn-custom-svg">
        </a>

        <a href="?page=order-confirmation&bpage=<?= $_GET['page'] ?>" class="btn-custom-blue w-100 mt-4 d-flex align-items-center justify-content-center">
            Checkout
        </a>
    </div>

<?php endif; ?>


<script>
    var arrCart = <?= json_encode($arrCart) ?>;
    $(document).on('click', '.count_decrement ', function() {
        _this = $(this);

        current_value = $(this).parent().parent().find('.count_num').val();
        let groupOrdersSpecsId = $(this).attr('group-orders-specs-id');

        if (current_value > 1 && groupOrdersSpecsId) {

            let arrOrdersSpescId = groupOrdersSpecsId.split(",");
            const arrOrdersSpescIdRemove = arrOrdersSpescId.pop();

            const new_value = current_value - 1;
            _this.parent().parent().find('.count_num').val(new_value);
            _this.attr('group-orders-specs-id', arrOrdersSpescId.join(","));

            const index = arrCart.findIndex(item => item.group_orders_specs_id === groupOrdersSpecsId);
            if (index !== -1) {
                arrCart[index].count = new_value;
                arrCart[index].group_orders_specs_id = arrOrdersSpescId.join(",");
            }

            $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                orders_specs_id: arrOrdersSpescIdRemove
            }).fail(function() {
                alert("Error while removing the item");
                $parent.find('.count_num').val(current_value);
                arrOrdersSpescId.push(arrOrdersSpescIdRemove);
                _this.attr('group-orders-specs-id', arrOrdersSpescId.join(","));
                if (index !== -1) {
                    arrCart[index].count = current_value;
                    arrCart[index].group_orders_specs_id = arrOrdersSpescId.join(",");
                }
            });

        }
    });


    $(document).on('click', '.count_increment', function() {
        _this = $(this);

        const current_value = $(this).parent().parent().find('.count_num').val();
        let groupOrdersSpecsId = $(this).attr('group-orders-specs-id');

        if (groupOrdersSpecsId) {
            let arrOrdersSpescId = groupOrdersSpecsId.split(",");
            const attrProdItem = $(this).attr('prod-item');
            const itemProd = (attrProdItem != 'frame') ? '_' + attrProdItem : '';

            const new_value = parseInt(current_value) + 1;
            _this.parent().parent().find('.count_num').val(new_value);

            $.post("/v2.0/sis/studios/func/process/add_to_bag" + itemProd + ".php", {
                studios_product_code: _this.attr('product-code')
            }, function(result) {

                arrOrdersSpescId.push(result);

                const updatedOrdersSpecsId = arrOrdersSpescId.join(",");
                _this.attr('group-orders-specs-id', updatedOrdersSpecsId);

                const index = arrCart.findIndex(item => item.group_orders_specs_id === groupOrdersSpecsId);
                if (index !== -1) {
                    arrCart[index].count = new_value;
                    arrCart[index].group_orders_specs_id = updatedOrdersSpecsId;
                }
            });
        }
    });

    $(document).on("click", ".remove_item", function() {
        const orderSpecsId = $(this).attr('orders-specs-id');
        // console.log("Order Specs ID: " + orderSpecsId);
        $.post("/v2.0/sis/studios/func/process/remove_item.php", {
            orders_specs_id: orderSpecsId
        }, function() {
            totalCount();
            location.reload();
        });

    })


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