<?php
if (!isset($_SESSION['customer_id'])) {
?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=health-declaration-form"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>
<?php
} else {
    include "./modules/includes/grab_product_studios.php";
?>
    <style>
        main.customer-layout .wrapper {
            padding: 0 20px 100px;
            overflow-y: hidden;
        }

        .panel-group .panel {

            border-top: 1px solid #000000;
            border-radius: 0;
            margin-top: 20px;
        }

        .panel-last {
            border-bottom: 1px solid #000000;
        }

        .panel p {
            display: block;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
        }

        .panel .pull-right {
            float: right !important;
        }

        [type="checkbox"]:not(:checked)+label,
        [type="checkbox"]:checked+label {
            position: relative;
            padding-left: 1em;
            cursor: pointer;
        }

        .radio-active label,
        .radio .checked,
        .checkbox .checked,
        .checkbox-active label {
            font-weight: 600;
        }

        .radio label,
        .checkbox label {
            min-height: 20px;
            padding-left: 20px;
            margin-bottom: 0;
            font-weight: normal;
            cursor: pointer;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .frame-style .product-option {
            border-radius: 10px;
            cursor: pointer;
        }

        #cart {
            padding: 0px 5px 0px 5px;
            vertical-align: center;
            border-radius: 30px;
            cursor: pointer;
        }

        .count {
            background-color: #FFD5C6;
            padding: 0px 7px;
            border-radius: 15px;
        }

        .card {
            border-radius: 15px;
        }

        .cart_view .card-body {
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>
    <div class="packages-list ">

        <?php if (isset($_GET['product-detail']) && trim($_GET['product-detail']) != "") { ?>
            <section class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-view" id="product-panel">
                <a href="./?page=<?= $_GET['page'] ?>" class="exit-frame-selection">
                    <div class="d-flex align-items-start mb-3">
                        <img src="/ssis/assets/images/icons/icon-left-arrow.png" alt="back" class="img-fluid" title="back to shopping" style="padding-left: 20px;">
                        <p class="mt-2" style="margin-left: 5px;">Back</p>
                    </div>
                </a>

                <form href="#" id="form-add-to-bag">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div style="text-align:left">
                                    <input type="hidden" name="studios_product_code" value="<?= $_GET['product-code'] ?>">
                                    <img src="././images/studios/front.png" class="img-responsive" width="160" height="50">
                                    <hr class="spacing">
                                    <div style="text-align:center">
                                        <p><?= ucwords(strtolower($_GET['desc'])) ?></p>
                                    </div>
                                </div>

                                <div style="text-align:center">
                                    <div class="d-flex justify-content-start count_item">
                                        <div class="d-flex justify-content-start mt-2">
                                            <span><input type="button" class="form-control minus_count_decrement" value="-"></span>
                                            <input type="text" class="form-control count_num" name="count_num_value" value="1" readonly>
                                            <span><input type="button" class="form-control add_count_increment" value="+"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <p style="font-size: 18px;"><strong>₱</strong><?= $_GET['price'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="spacing">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">add to bag</button>
                    </div>

                </form>
            </section>
        <?php } else { ?>
            <section class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel" id="product-panel">

                <div class="d-flex align-items-center mb-3">
                    <a href="./?page=select-store" class="exit-frame-selection"><img src="/ssis/assets/images/icons/icon-left-arrow.png" alt="exit" class="img-fluid" style="padding-left: 20px;"></a>
                    <input type="search" name="search_frame" id="search_frame" class="form-control filled search" placeholder="Search" style="margin-left: 20px;">
                    <div class="d-flex justify-content-between" id="cart" title="Cart" style="padding-left: 20px;">
                        <img src="././assets/images/icons/icon-cart.png" class="img-responsive" width="40px" height="40px">
                        <span>
                            <div class="count" count="<?= $order_count ?>"></div>
                        </span>
                    </div>
                </div>
                <div class="frame-list" style="height:57vh; overflow: auto;">
                    <?php for ($b = 0; $b < count($arrFrames); $b++) { ?>
                        <div class="frame-style" data-style="<?= $arrFrames[$b]['item_description'] ?>">

                            <p class="text-uppercase text-primary font-bold mb-3 mt-3"><?= $arrFrames[$b]['item_description'] ?></p>
                            <div class="form-row align-items-stretch">
                                <?php
                                for ($i = 0; $i < count($arrProduct); $i++) {
                                    if ($arrProduct[$i]['item_description'] == $arrFrames[$b]['item_description']) {
                                ?>
                                        <div class="col-lg-4 col-xs-12 form-group product-option" product-code="<?= $arrProduct[$i]['product_code'] ?>">
                                            <input type="radio" name="frame_style" class="sr-only">
                                            <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center">
                                                <img src="././images/studios/front.png" class="img-fluid">
                                                <section class="text-center mt-2">
                                                    <p><?= ucwords(strtolower($arrProduct[$i]['color'])) ?> | <strong>₱</strong><?= $arrProduct[$i]['price'] ?> </p>
                                                </section>
                                            </label>
                                        </div>

                                <?php }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </section>
        <?php } ?>
    </div>
    <style>
        .count_item .form-control {
            padding: 1px 10px;
            height: 25px;
            border-radius: 0px;
            cursor: pointer;
        }

        .count_item .form-control:hover {
            background-color: #E4DBDB;
        }

        .count_item .form-control:active {
            background-color: #C1C1C1;
        }

        .count_num {
            width: 50px;
            border-left: none;
            border-right: none;
            text-align: center;
        }
    </style>
    <script>
        let arrProduct = JSON.parse(JSON.stringify(<?= json_encode($arrProduct); ?>));
        let arrCart = JSON.parse(JSON.stringify(<?= json_encode($arrCart); ?>));
        $(document).ready(function() {
            totalCount();
            $('#filter').on('click', function() {
                $('.ssis-overlay').load("/lil_ssis/modules/store/studios-filter.php", function(d) {
                    overlayFilter(d);
                });
            });

            $(".product-option").click(function() {
                let tempProduct = arrProduct.find(x => x.product_code == $(this).attr('product-code'));
                window.location = "?page=add-paper-bag&product-detail=true&product-code=" + tempProduct.product_code + "&desc=" + tempProduct.description + "&price=" + tempProduct.price;
            });

            $("#cart").click(function() {
                let item_cart = '';
                for (let i = 0; i < arrCart.length; i++) {
                    merchItem = (arrCart[i].item_description.toLowerCase().indexOf('agenda') > -1 || arrCart[i].item_description.toLowerCase().indexOf('hardcase') > -1) ? 'merch_item' : '';
                    item_cart += '<div class="card cart_view mt-4">' +
                        '<div class="card-body">' +
                        '<div class="d-flex justify-content-between">' +
                        '<div class="d-flex justify-content-between" style="text-align:left">' +
                        '<div style="text-align:left;">' +
                        '<img src="././images/studios/front.png" class="img-responsive" width="160" height="50" >' +
                        '</div>' +
                        '<div style="padding-left: 20px;">' +
                        '<p style="font-size: 12px;" class="mt-2">' + arrCart[i].item_description + '</p>' +
                        '</div>' +
                        '</div>' +
                        '<div class="d-flex justify-content-start count_item">' +
                        '<div class="d-flex justify-content-start mt-2">' +
                        '<span><input type="button" class="form-control count_decrement" group-orders-specs-id="' + arrCart[i].group_orders_specs_id + '" value="-"></span>' +
                        '<input type="text" class="form-control count_num" value="' + arrCart[i].count + '" readonly>' +
                        '<span><input type="button" class="form-control count_increment" ' + merchItem + ' group-orders-specs-id="' + arrCart[i].group_orders_specs_id + '" product-code="' + arrCart[i].product_code + '" value="+"></span>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="d-flex justify-content-end">' +
                        '<p style="font-size: 18px;"><strong>₱</strong>' + arrCart[i].price + '</p>' +
                        '<img src="././assets/images/icons/icon-delete.png" class="img-responsive remove_item" orders-specs-id="' + arrCart[i].group_orders_specs_id + '" style="cursor: pointer; margin-left: 10px;" width="25" height="25" title="Remove this item">' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
                if (item_cart == '') {
                    item_cart += itemCart();
                } else {
                    item_cart += '<div class="d-flex justify-content-center mt-4" id="btn-sect" style="text-align: center;">' +
                        '<div class="col-6">' +
                        '<input type="button" class="btn btn-success" data-dismiss="modal" value="Shop More">' +
                        '</div>' +
                        '<div class="col-6">' +
                        '<a href="?page=order-confirmation&bpage=' + '<?= $_GET['page'] ?>' + '"><input type="button" class="btn btn-primary" value="Proceed"></a>' +
                        '</div>' +
                        '</div>';
                }
                $("#item_cart").html(item_cart);
                $("#modal-item").modal("show");
            });
            $("#form-add-to-bag").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "modules/process/add_to_bag.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: 'html',
                    success: function(response) {
                        window.location = "?page=add-paper-bag";
                    },
                    error: function() {}
                }); //END :: AJAX
            });
            $(this).on('click', '.remove_item', function() {
                let this_div = $(this);
                let remove = $.post("modules/process/remove_item.php", {
                    orders_specs_id: this_div.attr('orders-specs-id')
                }, function() {});
                $.when(remove).done(function() {
                    arrCart = arrCart.filter(item => item.group_orders_specs_id !== this_div.attr('orders-specs-id'));
                    //console.log(arrCart);
                    totalCount();
                    this_div.parent().parent().parent().remove();
                    if (arrCart.length == 0) {
                        $("#btn-sect").html(itemCart());
                    }
                });
            });
            $(this).on('click', '.count_decrement ', function() {
                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num').val();
                if (current_value > 1) {
                    $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("modules/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function() {

                        arrOrdersSpescId.pop();
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        index = arrCart.findIndex(item => item.group_orders_specs_id === _this.attr('group-orders-specs-id'));
                        arrCart[index].group_orders_specs_id = arrOrdersSpescId;
                        arrCart[index].orders_specs_id = arrOrdersSpescId;
                        arrCart[index].count = arrCart[index].count - 1;
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        totalCount();
                    });

                }
            });
            $(this).on('click', '.count_increment', function() {
                _this = $(this);
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                $.post("modules/process/add_to_bag.php", {
                    studios_product_code: _this.attr('product-code')
                }, function(result) {
                    //console.log(result);
                    arrOrdersSpescId.push(result);
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    index = arrCart.findIndex(item => item.group_orders_specs_id === _this.attr('group-orders-specs-id'));
                    arrCart[index].group_orders_specs_id = arrOrdersSpescId;
                    arrCart[index].orders_specs_id = arrOrdersSpescId;
                    arrCart[index].count = parseInt(arrCart[index].count) + 1;
                    _this.attr('group-orders-specs-id', arrOrdersSpescId);
                    current_value = _this.parent().parent().find('.count_num').val();
                    _this.parent().parent().find('.count_num').val(parseInt(current_value) + 1);
                    totalCount();
                });
            });

            $(this).on('click', '.add_count_increment', function() {
                current_value = $(this).parent().parent().find('.count_num').val();
                $(this).parent().parent().find('.count_num').val(parseInt(current_value) + 1);
            });
            $(this).on('click', '.minus_count_decrement', function() {
                current_value = $(this).parent().parent().find('.count_num').val();
                if (current_value > 1) {
                    $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                }
            });

            var typingTimer;
            var doneTypingInterval = 500;

            $('#search_frame').on('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(showAvailableFrame, doneTypingInterval);
            });

            $('#search_frame').on('keydown', function() {
                clearTimeout(typingTimer);
            });
        });

        const totalCount = () => {
            let value = 0;
            for (i = 0; i < arrCart.length; i++) {
                value += parseInt(arrCart[i].count);
            }

            $('.count').text(value);
        }
        const showAvailableFrame = () => {
            if ($('#search_frame').val() != '') {
                var s = $("#search_frame").val().toLowerCase();
                $('.ssis-searching').fadeIn();

                $('.frame-style').each(function() {
                    if ($(this).data('style').match(s.toLowerCase())) {
                        $(this).fadeIn();
                    } else {
                        $(this).fadeOut();
                    }
                    $('.ssis-searching').fadeOut();
                });
            } else {
                $('.frame-style').fadeIn();
            }
        }
        const itemCart = () => {
            return '<div class="row mt-4" style="text-align: center;">' +
                '<div class="col-12">' +
                '<p style="font-weight: bold; font-size: 20px">Your cart is empty</p>' +
                '</div>' +
                '<div class="col-12 mt-4">' +
                '<input type="button" class="btn btn-success" data-dismiss="modal" value="Shop More">' +
                '</div>' +
                '</div>';
        }
        const overlayFilter = body => {
            $('.ssis-overlay').fadeIn(200).addClass('show').html(body);
            $('.close-overlay').click(function() {
                if ($(this).data('reload') == 'yes') {
                    window.location.reload(true);
                } else {
                    $('.ssis-overlay').removeClass('show').fadeOut().html("");
                }

                if ($(this).data('sidebar') == 'yes') {
                    toggleSidebar('show');
                }
            });
        }
    </script>
<?php } ?>