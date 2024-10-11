<style>
    .card {
        width: 100%;
        border: none;
        border-radius: 16px;
        padding: 16px;
        overflow: hidden;
    }

    .add_paper {
        height: 56px;
        width: 100%;
        border-left: none;
        border-right: none;
        border-top: none;
        font-size: 12px;
        font-weight: 400;
        color: #919191;
        cursor: pointer;
        border-bottom: 1px solid #DCDCDC;
    }

    .add_paper:focus {
        outline: none;
        box-shadow: none;
    }


    .card-others {

        gap: 2rem;
        font-size: 1.125rem;
    }


    .form-check-label {
        font-size: 1.125rem;
    }

    .checkbox~.custom_checkbox {
        margin: 0;
        width: 24px;
        height: 24px;
        background-color: #f7f7f7;
        border: 2px solid #956E46;
        /* Add a border when unchecked */
        border-radius: 5px;
        background-size: 24px;
        background-repeat: no-repeat;
        background-position: center;
        background-image: none;
        /* No icon when unchecked */
    }

    .checkbox:checked~.custom_checkbox {
        margin: 0;
        width: 24px;
        height: 24px;
        background-color: #956E46;
        border-radius: 5px;

        background-repeat: no-repeat;
        background-position: center;
        background-size: 18px 18px;
        /* Adjust the size of the background image */
        background-image: url("../../face/assets/images/icons/icon-check.png");
        /* Show check icon */
        border: 2px solid #956E46;
    }


    .count_num_pbag,
    .count_num_sac,
    .count_num_others {
        width: 50px;
        border: none;
        text-align: center;
    }
</style>


<?php include "./modules/includes/products/grab_cart.php"; ?>
<?php
include "./modules/includes/products/packaging_list.php";
if (isset($_SESSION['customer_id'])) {
    include("./modules/includes/grab_customer_hdf.php");
}

function get_customer_data($data)
{
    global $arrCustomer;
    if (isset($_SESSION['customer_id'])) {
        if ($arrCustomer[0][$data] != '') {
            $value = $arrCustomer[0][$data];
        } else {
            $value = '';
        }
    } elseif (isset($_SESSION['temp_data'])) {
        $value = $_SESSION[$data];
    } else {
        $value = '';
    }

    return $value;
}


if (!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/sis/face/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            alert('error');
        });
    </script>
<?php } elseif (count($arrCart) == 0) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 2 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=select-store"><button class="btn btn-primary">go to step 2</button></a>
        </div>
    </div>

<?php } else { ?>
    <?php

    function getExistingPaperBagSac($arrParams, $arrCart)
    {
        $arrExist = [];
        foreach ($arrParams as $arrParam) {
            foreach ($arrCart as $cartItem) {
                if (trim($arrParam["product_code"]) == trim($cartItem["product_upgrade"])) {
                    $arrExist[$arrParam["product_code"]] = $cartItem["group_orders_specs_id"];
                    break;
                }
                // if (trim($arrParam["product_code"]) == "P1009-34") {
                //     $arrExist[$arrParam["product_code"]] = $cartItem["group_orders_specs_id"];
                //     break;
                // }
            }
        }
        return $arrExist;
    }
    ?>


    <div class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel">



        <div class="custom-subtitle my-4">
            Total order
            <span class="custom-title">
                <?= count(array_filter($arrCart, function ($item) {
                    return $item['price'] > 0;
                })) ?>
            </span>

        </div>



        <div>
            <?php


            $merchItem = false;
            $paperBag = false;

            foreach ($arrCart as $item) {
                if (strstr(strtolower($item['item_description']), 'paper bag') && !$paperBag) {
                    $paperBag = true;
                }
            }

            foreach ($arrCart as $item):
                if ($item['price'] > 0) {
                    // Handle case where price is greater than 0
                } elseif (
                    strstr(strtolower($item['item_description']), 'paper bag') ||
                    strstr(strtolower($item['item_description']), 'sac') ||
                    strstr(strtolower($item['item_description']), 'receipt')
                ) {
                    continue;
                }

                if ($item['dispatch_type'] == 'packaging') {
                    continue;
                }

                if (
                    (strstr(strtolower($item['item_description']), 'hardcase') ||
                        strstr(strtolower($item['item_description']), 'agenda')) &&
                    !$merchItem
                ) {
                    $merchItem = true;
                }

                ?>

                <div class="customized-card my-4 w-100 p-4 ">
                    <div class="no-gutters d-flex">
                        <div class="">
                            <img src="<?= !empty($cart['image_url']) ? $cart['image_url'] : 'https://via.placeholder.com/120x126' ?>"
                                class="card-img" alt="Product Image">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body d-flex flex-column gap-3 pt-0 pb-0 pr-0">
                                <p class="custom-title"><?= $item['style'] ?></p>
                                <p class="custom-subtitle" style="font-size: 1.125rem; font-weight: 400;"><?= $item['color'] ?>
                                </p>
                                <p class="custom-subtitle" style="color: #919191;">
                                    <?=
                                        (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs')
                                        ? 'VND '
                                        : '₱'
                                        ?>
                                    <?= number_format($item['price'], 2) ?>
                                    <?php if ($item['count'] > 1): ?>
                                        x <?= $item['count'] ?>
                                    <?php endif; ?>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>




        <div class="custom-title mb-4" style="text-transform: uppercase;">
            <?= $arrTranslate['Packaging'] ?>
        </div>


        <div class="card mt-3 card-paper-bag">
            <?php
            $arrPaperBagSelected = [];
            $arrPaperBag = paperBagList();

            $arrExistPBag = getExistingPaperBagSac($arrPaperBag, $arrCart)
                ?>

            <div class="card-body p-0">
                <?php

                if (count($arrExistPBag) == 0) {
                    ?>
                    <div class="product-section d-flex align-items-center justify-content-between gap-3" style="gap: 16px;">
                        <div class="d-flex justify-content-between">
                            <div class="form-group">
                                <select class="form-control add_paper " id="add_paper">
                                    <option value="">Additional paper bag</option>
                                    <?php for ($i = 0; $i < count($arrPaperBag); $i++) { ?>
                                        <option value="<?= $arrPaperBag[$i]['product_code'] ?>"><?= $arrPaperBag[$i]['item_name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>


                        <div class="d-flex align-items-center justify-content-center count_item">
                            <div class="button-container">
                                <input type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id=""
                                    value="-">
                            </div>

                            <input type="text" style="font-size: 16px;" class="form-control count_num_pbag bg-transparent"
                                group-orders-specs-id="" value="0" readonly>


                            <div class="button-container">
                                <input type="button" class="add_count_increment_pbag custom-button" group-orders-specs-id=""
                                    value="+">
                            </div>
                        </div>


                    </div>
                <?php } else {

                    foreach ($arrExistPBag as $key => $value) {
                        $groupSelected = explode(',', $value);
                        $countSelected = count($groupSelected);

                        for ($i = 0; $i < count($arrPaperBag); $i++) {

                            if ($key == $arrPaperBag[$i]['product_code']) {
                                $product_name = $arrPaperBag[$i]['item_name'];
                                break;
                            }
                        }
                        ?>
                        <div class="product-section d-flex align-items-center justify-content-between" style="gap: 16px;">
                            <div class="d-flex justify-content-between">
                                <div class="form-group ">
                                    <select class="form-control add_paper" id="add_paper">
                                        <option value="<?= $key ?>"><?= $product_name ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="d-flex align-items-center justify-content-center count_item">
                                <div class="button-container">
                                    <input type="button" class="minus_count_decrement_pbag custom-button"
                                        group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="-">
                                </div>

                                <input type="text" style="font-size: 16px;" class="form-control count_num_pbag bg-transparent"
                                    group-orders-specs-id="" value="<?= $countSelected ?>" readonly>

                                <div class="button-container">
                                    <input type="button" class="add_count_increment_pbag custom-button"
                                        group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="+">
                                </div>
                            </div>

                        </div>
                    <?php }
                }
                ?>
            </div>
        </div>

        <div class="card card-others mt-4 w-100 p-4 d-flex align-items-left justify-content-center">
            <?php
            $arrPaperBagSelected = [];
            $arrPaperBag = othersList();
            

            $arrExistPBag = [];
            $orders_specs_id_selected = '';
            for ($i = 0; $i < count($arrPaperBag); $i++) {
                $selected = '';
                for ($b = 0; $b < count($arrCart); $b++) {
                    if (trim($arrPaperBag[$i]["product_code"]) == trim($arrCart[$b]["product_upgrade"])) {

                        $arrExistPBag[$arrPaperBag[$i]["product_code"]] = $arrCart[$b]["group_orders_specs_id"];
                        $orders_specs_id_selected = $arrCart[$b]["group_orders_specs_id"];
                        break;
                    }
                }
            }
            ?>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex pb-2">
                    <div class="d-flex align-items-center radio">
                        <input type="checkbox" name="receipt_holder" id="receipt_holder_checkbox"
                            class="sr-only set_receipt_holder checkbox" orders-specs-id="<?= $orders_specs_id_selected ?>"
                            product-code="<?= $arrPaperBag[0]['product_code'] ?>" value="yes" <?= $orders_specs_id_selected != '' ? 'checked' : '' ?>
                        >
                        <label for="receipt_holder_checkbox" class="custom_checkbox"></label>
                    </div>
                </div>

                <div class="col align-items-center pb-2">
                    <p style="font-size: 18px; font-weight: 400">Receipt Holder?</p>
                </div>

            </div>
        </div>

        <div class="card mt-4 w-100 p-4 d-flex" style="color: #342C29; gap: 1.5rem">
            <?php
            // $total_price = 0;
            // $voucher_amount = 0;
            // $promo_code = '';
            // $total_count = 0;
            // foreach ($arrCart as $item):
            //     if ($item['price'] > 0):
            //         $voucher_amount += $item['promo_code_amount'];
            //         $total_price += $item['price'] * $item['count'];
            //         $total_count += $item['count'];
            //         if (isset($item['promo_code']) && !empty($item['promo_code'])) {
            //             $promo_code = $item['promo_code']; // Store the promo code
            //         }
            //     endif;
            // endforeach;
        
            $total_price = 0;
            $voucher_amount = 0;
            $promo_code = '';
            $total_count = 0;

            if (!empty($arrCart) && isset($arrCart[0]['promo_code_amount'])) {
                $voucher_amount = $arrOrdersConfirmed[0]['promo_code_amount'];
                $promo_code = $item['promo_code'];
            }

            foreach ($arrCart as $item):
                if ($item['price'] > 0):
                    $total_price += $item['price'] * $item['count'];
                    $total_count += $item['count'];
                endif;
            endforeach;

            ?>

            <div class="d-flex justify-content-between">
                <p class="custom-subtitle">Subtotal</p>
                <p class="custom-subtitle">
                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>    <?= number_format($total_price, 2) ?>
                </p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="custom-subtitle">Discount</p>
                <p class="custom-subtitle">
                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>    <?= $promo_code != '' ? $promo_code : number_format(0, 2) ?>
                </p>
            </div>

            <hr>

            <div class="d-flex justify-content-between" style="font-weight: 700">
                <p class="custom-title">Total amount</p>
                <p class="custom-title" style="color: #956E46;">
                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>    <?= number_format($total_price - $voucher_amount, 2) ?>
                </p>
            </div>


        </div>

        <?php if (!isset($_SESSION["autologin"])) { ?>
            <div class="card mt-4 w-100 p-4 d-flex" style="color: #342C29; gap: 1.5rem">

                <div class="d-flex align-items-center ">
                    <div style="height: 40px; width: 40px">
                        <img src="<?= get_url('images/icons') ?>/icon-user.png" alt="user" class="img-fluid">
                    </div>
                    <div class="col ml-2">
                        <p class="custom-subtitle" style="text-transform: capitalize; font-weight: 700; font-size: 18px;">
                            <?= get_customer_data("first_name") ?>         <?= get_customer_data("last_name") ?>
                        </p>
                        <p class="custom-subtitle" style=" font-weight: 500; font-size: 14px;">
                            <?= get_customer_data("age") ?> <?= get_customer_data("age") == 1 ? 'year old' : 'years old' ?>
                        </p>
                    </div>

                </div>

            </div>
        <?php } ?>

        <div>
            
            <!-- <?php print_r(get_customer_data("first_name")); ?>
            <?php print_r(get_customer_data("last_name")); ?>
            <?php print_r(get_customer_data("birthday")); ?>
            <?php print_r($_SESSION) ?> -->
            <?php if (isset($_SESSION["autologin"])) { ?>
                <div id="bottom-content" class=" d-flex text-center align-items-center justify-content-center mt-5 mb-5"
                style=" bottom: 0; left: 0; width: 100%; ">
                <div id="bottom-content-inner" style=" width: 100%  ; ">

                    <div class="row">
                        <div class="col-12 ">
                            <div class="customer-account text-center">
                                <a
                                    href="./?page=account-form&type=sign-up&bpage=<?php echo htmlspecialchars($_GET['bpage']); ?>">
                                    <button class="btn btn-primary">Log in or Sign up </button>
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="customer-account text-center">
                                <a
                                    href="./?page=account-form&guest=true&bpage=<?php echo htmlspecialchars($_GET['bpage']); ?>">
                                    <button class="btn btn-not-cancel">Continue as guest</button>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
                <?php } else { ?>

            


            <?php $textSend = (trim($_SESSION['store_type']) == 'ns') ? 'Send to Cashier' : $arrTranslate['Dispatch Order']; ?>
            <a href="/sis/face/func/process/order_payment.php?path_loc=v1.0&bdate=<?= get_customer_data('age') ?>" id="send-order">
                <!-- <input type="button" class="btn-custom-blue my-4 w-100  d-flex align-items-center justify-content-center"
                    value="<?= $textSend ?>"> -->

                    <button class="btn btn-primary mt-4 mb-5"><?php echo $textSend; ?></button>
            </a>

            <?php } ?>
        </div>



    </div>


    <script>


        let total_count = <?= $total_count ?>;
        $(document).ready(function () {
            $(".use_code").hide();
            $(".check_code").click(function (e) {
                e.preventDefault();

                $.ajax({

                    url: "./modules/promo/check_promo_api.php?type=check",
                    type: "GET",
                    data: $("#form-check-promo").serialize(),
                    dataType: 'json',
                    success: function (response) {

                        color = "red";
                        if (response.valid == true) {
                            color = "green";
                            // location.reload();
                            $(".use_code").show();
                        } else {
                            $(".use_code").hide();
                        }

                        $("#check-promo-message").text(response.message);
                        $("#check-promo-message").css("color", color);

                    },
                    error: function () {

                    }

                }); //END :: AJAX

            });

            $("#form-check-promo").submit(function (e) {
                e.preventDefault();

                $.ajax({

                    url: "./modules/promo/check_promo_api.php?type=use",
                    type: "GET",
                    data: $("#form-check-promo").serialize(),
                    dataType: 'json',
                    success: function (response) {

                        color = "red";
                        if (response.valid == true) {
                            color = "green";
                            location.reload();
                        } else {
                            $(".use_code").hide();
                        }

                        $("#check-promo-message").text(response.message);
                        $("#check-promo-message").css("color", color);

                    },
                    error: function () {

                    }

                }); //END :: AJAX

            });



            $(this).on('click', '.add_count_increment_pbag', function () {
                let count_num_val = 0;
                $('.count_num_pbag').each(function () {
                    count_num_val += parseInt($(this).val());
                });
                productSelected = $(this).parents('.product-section').find('select').val();
                if (productSelected == '') {
                    alert('Please select paper bag');
                    return false;
                }
                $('#loading').modal('show');
                if (count_num_val >= total_count) {
                    if (confirm('Your about to exceed the total count of frames.')) {

                        _this = $(this);
                        arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                        processItem = '';
                        attr = $(this).attr('merch_item');

                        // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                        $.post("/sis/studios/func/process/add_to_bag_merch.php", {
                            studios_product_code: productSelected,
                            paper_bag: true
                        }, function (result) {
                            //console.log(result);
                            arrOrdersSpescId.push(result);
                            arrOrdersSpescId = arrOrdersSpescId.join(",");
                            _this.attr('group-orders-specs-id', arrOrdersSpescId);
                            _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                            current_value = _this.parent().parent().find('.count_num_pbag').val();
                            _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) + 1);

                            _this.parents('.product-section').find('select option').each(function () {
                                if ($(this).val() != productSelected) {
                                    $(this).remove();
                                }
                            });
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    } else {
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 50);
                    }
                } else {
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php", {
                        studios_product_code: productSelected,
                        paper_bag: true
                    }, function (result) {
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value = _this.parent().parent().find('.count_num_pbag').val();
                        _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function () {
                            if ($(this).val() != productSelected) {
                                $(this).remove();
                            }
                        });
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });
                }
            });

            $(this).on('click', '.minus_count_decrement_pbag', function () {

                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num_pbag').val();
                if (current_value > 0) {
                    $('#loading').modal('show');
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function () {
                        arrOrdersSpescId.pop();
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(1).find('.add_count_increment_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) - 1);
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });

                }
            });

            $(this).on('click', '#add_section_paper_bag', function () {
                $.get('/sis/studios/v1.0/modules/includes/products/add_paper_bag_section.php', function (result) {
                    $('.card-paper-bag .card-body').append(result);
                });
            });

            $(this).on('click', '.add_count_increment_sac', function () {
                let count_num_val = 0;
                $('.count_num_sac').each(function () {
                    count_num_val += parseInt($(this).val());
                });
                productSelected = $(this).parents('.product-section').find('select').val();
                if (productSelected == '') {
                    alert('Please select sac');
                    return false;
                }
                $('#loading').modal('show');
                if (count_num_val >= total_count) {
                    if (confirm('Your about to exceed the total count of frames.')) {

                        _this = $(this);
                        arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                        processItem = '';
                        attr = $(this).attr('merch_item');

                        // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                        $.post("/sis/studios/func/process/add_to_bag_merch.php", {
                            studios_product_code: productSelected,
                            paper_bag: true
                        }, function (result) {
                            //console.log(result);
                            arrOrdersSpescId.push(result);
                            arrOrdersSpescId = arrOrdersSpescId.join(",");
                            _this.attr('group-orders-specs-id', arrOrdersSpescId);
                            _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                            current_value = _this.parent().parent().find('.count_num_sac').val();
                            _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) + 1);

                            _this.parents('.product-section').find('select option').each(function () {
                                if ($(this).val() != productSelected) {
                                    $(this).remove();
                                }
                            });
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    } else {
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 50);
                    }
                } else {
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php", {
                        studios_product_code: productSelected,
                        paper_bag: true
                    }, function (result) {
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value = _this.parent().parent().find('.count_num_sac').val();
                        _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function () {
                            if ($(this).val() != productSelected) {
                                $(this).remove();
                            }
                        });
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });
                }
            });

            $(this).on('click', '.minus_count_decrement_sac', function () {
                $('#loading').modal('show');
                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num_sac').val();
                if (current_value > 0) {
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function () {
                        arrOrdersSpescId.pop();
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(1).find('.add_count_increment_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) - 1);
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });

                }
            });

            $(this).on('click', '#add_section_sac', function () {
                // console.log("Hello World!")
                $.get('/sis/studios/v1.0/modules/includes/products/add_sac_section.php', function (result) {
                    $('.card-sac .card-body').append(result);
                });
            });

            $(this).on('change', '#findout', function () {
                $('#loading').modal('show');
                $.post("/sis/studios/func/process/findout.php", {
                    findout: $(this).val()
                }, function (result) {
                    setTimeout(() => {
                        $('#loading').modal('hide');
                    }, 200);
                });
            });

            $(this).on('click', '.club_member', function () {
                $('#loading').modal('show');
                $.post("/sis/studios/func/process/club_member.php", {
                    club_member: $(this).val()
                }, function (result) {
                    setTimeout(() => {
                        $('#loading').modal('hide');
                    }, 200);
                });
            });
            
            bool_receipt_holder = ($('.set_receipt_holder').attr('orders-specs-id') == '') ? false : true;

            $(this).on('click', '.set_receipt_holder', function () {
                bool_receipt_holder = true;
                if ($(this).val() == 'yes') {
                    if ($(this).attr('orders-specs-id') == '') {
                        $('#loading').modal('show');
                        $.post("/sis/face/func/process/add_to_bag_merch.php", {
                            studios_product_code: $(this).attr('product-code'),
                            paper_bag: true
                        }, function (result) {
                            $('.set_receipt_holder').attr('orders-specs-id', result);
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    }
                } else {
                    if ($(this).attr('orders-specs-id') != '') {
                        $('#loading').modal('show');
                        $.post("/sis/face/func/process/remove_item.php", {
                            orders_specs_id: $(this).attr('orders-specs-id')
                        }, function () {
                            $('.set_receipt_holder').attr('orders-specs-id', '');
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    }
                }
            });

            // $('#send-order').click(function (e) {
            //     if (!bool_receipt_holder) {
            //         e.preventDefault();
            //         alert('Select Yes/No Does this order include a Receipt Holder?');
            //     }
            // });


            $(this).on('click', '.add_count_increment_others', function () {

                let count_num_val = 0;
                $('.count_num_others').each(function () {
                    count_num_val += parseInt($(this).val());
                });
                productSelected = $(this).parents('.product-section').find('select').val();
                if (productSelected == '') {
                    alert('Please select others');
                    return false;
                }
                $('#loading').modal('show');
                if (count_num_val >= total_count) {
                    if (confirm('Your about to exceed the total count of frames.')) {

                        _this = $(this);
                        arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                        processItem = '';
                        attr = $(this).attr('merch_item');

                        // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                        $.post("/sis/studios/func/process/add_to_bag_merch.php", {
                            studios_product_code: productSelected,
                            paper_bag: true
                        }, function (result) {
                            //console.log(result);
                            arrOrdersSpescId.push(result);
                            arrOrdersSpescId = arrOrdersSpescId.join(",");
                            _this.attr('group-orders-specs-id', arrOrdersSpescId);
                            _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_others').attr('group-orders-specs-id', arrOrdersSpescId);
                            current_value = _this.parent().parent().find('.count_num_others').val();
                            _this.parent().parent().find('.count_num_others').val(parseInt(current_value) + 1);

                            _this.parents('.product-section').find('select option').each(function () {
                                if ($(this).val() != productSelected) {
                                    $(this).remove();
                                }
                            });
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    } else {
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 50);
                    }
                } else {
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php", {
                        studios_product_code: productSelected,
                        paper_bag: true
                    }, function (result) {
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_others').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value = _this.parent().parent().find('.count_num_others').val();
                        _this.parent().parent().find('.count_num_others').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function () {
                            if ($(this).val() != productSelected) {
                                $(this).remove();
                            }
                        });
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });
                }
            });

            $(this).on('click', '.minus_count_decrement_others', function () {
                $('#loading').modal('show');
                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num_others').val();
                if (current_value > 0) {
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function () {
                        arrOrdersSpescId.pop();
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(1).find('.add_count_increment_others').attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('.count_num_others').val(parseInt(current_value) - 1);
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });

                }
            });

            $(this).on('click', '#add_section_others', function () {
                $.get('/sis/studios/v1.0/modules/includes/products/add_others_section.php', function (result) {
                    $('.card-others .card-body').append(result);
                });
            });
            // $(this).on('click', '.minus_count_decrement', function(){
            //     current_value = $(this).parent().parent().find('.count_num').val();
            //     if(current_value > 0){
            //         $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
            //     }
            // });
            $('#btn-signup').click(function () {
                $('#modal-signup').modal('show');
            });

            $('#update_guest_account').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "/sis/studios/func/process/signup-guest-account.php",
                    type: "post",
                    data: $(this).serialize(),
                    success: function (response) {
                        alert(response);
                        if (response.indexOf('done') > -1) {
                            location.reload(true);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd;
            }

            if (mm < 10) {
                mm = '0' + mm;
            }

            today = yyyy + '-' + mm + '-' + dd;
            document.getElementById("bdate2").setAttribute("max", '2019-12-31');

            let getAge = (value) => {
                var today = new Date().getTime(),
                    dob = new Date(value).getTime(),
                    age = today - dob;
                yoa = Math.floor(age / 1000 / 60 / 60 / 24 / 365.25);
                $('#age').val(yoa);
            }

            $('#bdate2').on('change', function () {
                var bday = $(this).val();
                $('#bdate').val(bday);
                getAge(bday);
            });

            $('#mnum').on('blur', function () {
                if (/^[0-9]/.test(this.value)) {
                    this.value = this.value.replace(/^0/, "");
                    formatNumber(this);
                }
            });
        });
    </script>


<?php } ?>