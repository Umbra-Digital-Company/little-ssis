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

.promo-container {
        padding: 24px;
        background-color: #ffffff;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        -webkit-border-radius: 16px;
        -moz-border-radius: 16px;
        -ms-border-radius: 16px;
        -o-border-radius: 16px;
        -webkit-box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.06) !important;
        -moz-box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.06) !important;
        box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.06) !important;
    }

.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0,0,0,0.4); /* Black with opacity */
}

/* Modal Content/Box */
.modal-content-promo {
  background-color: #fefefe;
  margin: 10% auto; /* Center the modal */
  border-radius: 16px;
  padding: 20px;
  border: 1px solid #888;
  max-width: 500px; /* Set a maximum width */
  max-height: 440px;
}

.modal-data {
  width: 100%;
}

.modal-backdrop {
  z-index: 9 !important;
}



    .card-others {
        height: 176px;
        gap: 2rem;
        font-size: 1.125rem;
    }


    .form-check-label {
        font-size: 1.125rem;
    }

    .form-check-input {
        accent-color: #0B5893;
        height: 24px;
        width: 24px;
        padding: 0;
        margin-top: 0;
    }


    .count_num_pbag,
    .count_num_sac,
    .count_num_others {
        width: 50px;
        border: none;
        text-align: center;
    }
</style>



<?php
include "./modules/includes/grab_product_studios.php";
include "./modules/includes/products/packaging_list.php";

if (!isset($_SESSION['customer_id'])): ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3"><?= $arrTranslate['Complete step 1 to proceed'] ?></p>
        <div class="text-center mt-4">
            <a href="/v2.0/sis/studios/v1.0/?page=store-home">
                <button class="btn btn-primary"><?= $arrTranslate['Go to step 1'] ?></button>
            </a>
        </div>
    </div>

<?php elseif (count($arrCart) == 0): ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 2 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=select-store-studios">
                <button class="btn btn-primary">Go to step 2</button>
            </a>
        </div>
    </div>

<?php elseif (isset($_SESSION['customer_page']) && $_SESSION['customer_page'] !== 'YES'): ?>

    <?php include "./modules/store/access-denied.php"; ?>

<?php else: ?>


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




    <div class="mx-2">

        <div class="custom-subtitle my-4"  style="height: 48px;  display: flex; align-items: center; justify-content: start;  ">
            Total order
            <span class="custom-title" style="margin-left: 12px;">
                <?= count(array_filter($arrCart, function ($item) {
                    return $item['dispatch_type'] !== 'packaging';
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
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                            <img src="<?= !empty($item['image_url']) ? $item['image_url'] : '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png' ?>" class="card-img" alt="Product Image">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body d-flex flex-column gap-3 pt-0 pb-0 pr-0">
                                <p class="custom-title"><?= ucwords($item['style']) ?></p>
                                <p class="custom-subtitle" style="font-size: 1.125rem; font-weight: 400;"><?= ucwords($item['color']) ?></p>
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

            $arrExistPBag = getExistingPaperBagSac($arrPaperBag, $arrCart);

            ?>


            <div class="card-body p-0">
                <?php if (count($arrExistPBag) == 0): ?>
                    <div class="product-section d-flex align-items-center" style="gap: 16px;">
                        <div class="d-flex justify-content-between flex-grow-1">
                            <div class="form-group w-100">
                                <select class="form-control add_paper" id="add_paper">
                                    <option value="">Additional paper bag</option>
                                    <?php foreach ($arrPaperBag as $paperBag): ?>
                                        <option value="<?= htmlspecialchars($paperBag['product_code']) ?>">
                                            <?= htmlspecialchars($paperBag['item_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>


                        <div class="d-flex align-items-center justify-content-center count_item">
                            <div class="button-container">
                                <!-- <input type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id="" value="-"> -->
                                <button type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id=""
                                    style="height: 40px; width: 48px; background: #fff;">
                                    <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="minus"
                                        style="height: 24px; width: 24px;">
                                </button>
                            </div>

                            <input type="text" style="font-size: 16px;" class="form-control count_num_pbag bg-transparent" group-orders-specs-id="" value="0" readonly>

                            <div class="button-container">
                                <!-- <input type="button" class="add_count_increment_pbag custom-button" group-orders-specs-id="" value="+"> -->
                                <button type="button" class="add_count_increment_pbag custom-button"
                                    style="height: 40px; width: 48px; background: #fff;">
                                    <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                        style="height: 24px; width: 24px;">
                                </button>
                            </div>
                        </div>


                    </div>
                <?php else: ?>
                    <?php foreach ($arrExistPBag as $key => $value): ?>
                        <?php
                        $groupSelected = explode(',', $value);
                        $countSelected = count($groupSelected);
                        $product_name = '';

                        foreach ($arrPaperBag as $paperBag) {
                            if ($key == $paperBag['product_code']) {
                                $product_name = $paperBag['item_name'];
                                break;
                            }
                        }
                        ?>
                        <div class="product-section d-flex align-items-center" style="gap: 16px;">
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="form-group w-100">
                                    <select class="form-control add_paper " id="add_paper">
                                        <option value="<?= $key ?>"><?= $product_name ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center count_item">
                                <div class="button-container">
                                    <button type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id="<?= implode(",", $groupSelected) ?>"
                                        style="height: 40px; width: 48px; background: #fff;">
                                        <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="minus"
                                            style="height: 24px; width: 24px;">
                                    </button>
                                </div>

                                <input type="text" style="font-size: 16px;" class="form-control count_num_pbag bg-transparent" group-orders-specs-id="" value="<?= $countSelected ?>" readonly>

                                <div class="button-container">
                                    <button type="button" class="add_count_increment_pbag custom-button" group-orders-specs-id="<?= implode(",", $groupSelected) ?>"
                                        style="height: 40px; width: 48px; background: #fff;">
                                        <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                            style="height: 24px; width: 24px;">
                                    </button>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div>

                <button class="btn-custom-white w-100 mt-3 d-flex align-items-center justify-content-center" id="add_section_paper_bag">
                    <img src="/v2.0/sis/studios/assets/images/icons/add-circle-plus.svg" alt="plus" class="btn-custom-svg">
                    Additional paper bags
                </button>




            </div>
        </div>



        <div class="card mt-3 card-sac">

            <?php
            $arrPaperBagSelected = [];
            $arrPaperBag = sacList();

            $arrExistSac = getExistingPaperBagSac($arrPaperBag, $arrCart);

            // echo '<pre>';
            // print_r($arrExistPBag);
            ?>
            <div class="card-body p-0">
                <?php if (count($arrExistSac) == 0): ?>
                    <div class="product-section d-flex align-items-center" style="gap: 16px;">
                        <div class="d-flex justify-content-between flex-grow-1">
                            <div class="form-group w-100">
                                <select class="form-control add_paper " id="add_paper">
                                    <option value="">Sac</option>
                                    <?php for ($i = 0; $i < count($arrPaperBag); $i++) { ?>
                                        <option value="<?= $arrPaperBag[$i]['product_code'] ?>"><?= $arrPaperBag[$i]['item_name'] ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>


                        <div class="d-flex align-items-center justify-content-center count_item">
                            <div class="button-container">
                                <!-- <input type="button" class="minus_count_decrement_sac custom-button" group-orders-specs-id="" value="-"> -->
                                <button type="button" class="minus_count_decrement_sac custom-button"
                                    style="height: 40px; width: 48px; background: #fff;">
                                    <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="add"
                                        style="height: 24px; width: 24px;">
                                </button>
                            </div>

                            <input type="text" style="font-size: 16px;" class="form-control count_num_sac bg-transparent" group-orders-specs-id="" value="0" readonly>


                            <div class="button-container">
                                <!-- <input type="button" class="add_count_increment_sac custom-button" group-orders-specs-id="" value="+"> -->
                                <button type="button" class="add_count_increment_sac custom-button"
                                    style="height: 40px; width: 48px; background: #fff;">
                                    <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                        style="height: 24px; width: 24px;">
                                </button>
                            </div>
                        </div>


                    </div>
                <?php else: ?>
                    <?php foreach ($arrExistSac as $key => $value): ?>
                        <?php
                        $groupSelected = explode(',', $value);
                        $countSelected = count($groupSelected);
                        $product_name = '';

                        foreach ($arrPaperBag as $paperBag) {
                            if ($key == $paperBag['product_code']) {
                                $product_name = $paperBag['item_name'];
                                break;
                            }
                        }
                        ?>

                        <div class="product-section d-flex align-items-center" style="gap: 16px;">
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="form-group w-100">
                                    <select class="form-control add_paper" id="add_paper">
                                        <option value="<?= $key ?>"><?= $product_name ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="d-flex align-items-center justify-content-center count_item">
                                <div class="button-container">
                                    <!-- <input type="button" class="minus_count_decrement_sac custom-button" group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="-"> -->
                                    <button type="button" class="minus_count_decrement_sac custom-button" group-orders-specs-id="<?= implode(",", $groupSelected) ?>"
                                        style="height: 40px; width: 48px; background: #fff;">
                                        <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="add"
                                            style="height: 24px; width: 24px;">
                                    </button>
                                </div>

                                <input type="text" style="font-size: 16px;" class="form-control count_num_sac bg-transparent" group-orders-specs-id="" value="<?= $countSelected ?>" readonly>

                                <div class="button-container">
                                    <!-- <input type="button" class="add_count_increment_sac custom-button" group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="+"> -->
                                    <button type="button" class="add_count_increment_sac custom-button" group-orders-specs-id="<?= implode(",", $groupSelected) ?>"
                                        style="height: 40px; width: 48px; background: #fff;">
                                        <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                            style="height: 24px; width: 24px;">
                                    </button>
                                </div>
                            </div>







                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div>
                <!-- <input type="button" value="<?= $arrTranslate['Add Additional Sac'] ?>" id="add_section_sac" class="btn btn-primary"> -->

                <button class="btn-custom-white w-100 mt-3 d-flex align-items-center justify-content-center" id="add_section_sac">
                    <img src="/v2.0/sis/studios/assets/images/icons/add-circle-plus.svg" alt="plus" class="btn-custom-svg">
                    Additional Sac
                </button>




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
            <div class="form-check d-flex align-items-center">

                <input name="receipt_holder" class="set_receipt_holder form-check-input" type="checkbox" orders-specs-id="<?= $orders_specs_id_selected ?>" product-code="<?= $arrPaperBag[0]['product_code'] ?>" <?= $orders_specs_id_selected != '' ? 'checked' : '' ?>>
                <label class="form-check-label ml-4" for="flexCheckChecked">
                    Receipt holder?
                </label>
            </div>
            <div class="form-check d-flex align-items-center">
                <input class="form-check-input" type="checkbox" value="" <?= isset($arrCart[0]['club_member']) && $arrCart[0]['club_member'] === 'y' ? 'checked' : '' ?>>
                <label class="form-check-label ml-4" for="flexCheckChecked">
                    Sunnies Club Member?
                </label>
            </div>

        </div>


        <div class="card mt-4 w-100 p-4 d-flex align-items-left justify-content-center" style="height: 88px;">

            <?php
            $arrFindOut = ['Facebook', 'Instagram', 'Tiktok', 'Youtube', 'Google', 'Sunnies Studios website', 'Influencers', 'Family/friends', 'Billboards', 'Mall ads', 'Flyers', 'Radio ad'];
            ?>

            <select name="findout" id="findout" class="form-control w-100" style="height: 56px; font-size: 18px; font-weight: 400">
                <option disabled selected>How did you find out about us?</option>
                <?php foreach ($arrFindOut as $value) {
                    $selected = ($value == $arrCart[0]['find_out']) ? 'selected' : '';
                ?>
                    <option value="<?= $value ?>" <?= $selected ?>><?= $value ?></option>
                <?php } ?>
            </select>
        </div>

		<div class="survey-container mt-4" style="height: 232px;">
			<p class="text-uppercase font-bold">Customer Survey</p>
			<div class="d-flex mt-4" style="justify-content: center;">
				<!-- Non-colored images initially displayed -->
				<img id="happy-btn" class="emoji-btn" src="../assets/images/icons/smile_colored.png" onclick="selectEmoji('happy')" alt="Happy Emoji">
				<img id="sad-btn" class="emoji-btn" src="../assets/images/icons/frown_colored.png" onclick="selectEmoji('sad')" alt="Sad Emoji">
			</div>
			<input type="hidden" id="feedback" name="feedback" value="">					
		</div>

        <?php if($_SESSION['store_type'] == 'ns' && !strstr($arrCart[0]['email_address'],'guest')){ ?>
            <?php 
                    if(  isset($_GET['checkout']) && $_GET['checkout'] == 'guest'){?>
            
            <?php } else if (!isset($_SESSION['autologin'])) { ?>
                <div class="d-flex align-items-center justify-content-between w-100">
                    <p class="text-uppercase font-bold mb-0">Promo Code/Voucher Code:</p>
                    <?php if(isset($arrCart[0]['promo_code']) && $arrCart[0]['promo_code']!='') { ?>
                        <p class="text-uppercase text-primary font-bold mb-0"><?= $arrCart[0]['promo_code'] ?>-<?= $arrCart[0]['promo_code_amount'] ?> off</p>
                    <?php } else { ?> 
                        <input type="button" class="btn-promo check-promo-code" id="btn-check-reward" value="Check Promo">
                    <?php } ?>
                </div>
                            
                    
            <?php //} 
                } ?>
            </div>
        <?php } ?>

        <div class="card mt-4 w-100 p-4 d-flex" style="color: #342C29; gap: 1.5rem">
            <?php
            $total_price = 0;
            $voucher_amount = 0;
            $promo_code = '';
            $total_count = 0;
            foreach ($arrCart as $item):
                if ($item['price'] > 0):
                    $voucher_amount += $item['promo_code_amount'];
                    $total_price += $item['price'] * $item['count'];
                    $total_count += $item['count'];
                    if (isset($item['promo_code']) && !empty($item['promo_code'])) {
                        $promo_code = $item['promo_code']; // Store the promo code
                    }
                endif;
            endforeach;


            ?>


            <div class="d-flex justify-content-between">
                <p class="custom-subtitle">Subtotal</p>
                <p class="custom-subtitle">
                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?> <?= number_format($total_price, 2) ?>
                </p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="custom-subtitle">Discount</p>
                <p class="custom-subtitle">
                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?> <?= $voucher_amount != '' ? $voucher_amount : number_format(0, 2) ?>
                </p>
            </div>

            <hr>

            <div class="d-flex justify-content-between" style="font-weight: 700">
                <p class="custom-title">Total amount</p>
                <p class="custom-title" style="color: #956E46;">
                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?> <?= number_format($total_price - $voucher_amount, 2) ?>
                </p>
            </div>


        </div>


        <div>
            <?php if (isset($_SESSION['guest_customer']) && $_SESSION['guest_customer']) { ?>
                <input type="button" class="btn-custom-white w-100 mt-4 d-flex align-items-center justify-content-center" id="btn-signup" value="<?= $arrTranslate['Sign Up'] ?>">
            <?php } ?>

            <?php $textSend = (trim($_SESSION['store_type']) == 'ns') ? 'Send to Cashier' : $arrTranslate['Dispatch Order']; ?>
            <?php $textSend = ($_SESSION['store_type'] == 'ds' || $_SESSION['store_type'] == 'sr' || $_SESSION['store_type'] == 'vs') ? 'Send to Dispatch' : $textSend; ?>
            <a href="/v2.0/sis/studios/func/process/order_payment.php?path_loc=v1.0" id="send-order">
                <input type="button" class="btn-custom-blue my-4 w-100  d-flex align-items-center justify-content-center" value="<?= $textSend ?>">
            </a>
        </div>



    </div>


    <script>
        let total_count = <?= $total_count ?>;
        let arrCart = <?= json_encode($arrCart) ?>;

        $(document).ready(function() {
            $(".use_code").hide();
            $('.check-promo-code').click(function() {


                var modal = document.getElementById("myModal");
                var modalData = document.getElementById("modal-data");
                //     console.log(modalData); // Debugging statement

                modal.style.display = "block";
                $("#myVoucher").modal("show");
                $('input[name=email]').val('<?= $arrCart[0]['email_address'] ?>');
                $('#email_address_text').text('<?= $arrCart[0]['email_address'] ?>');
            });

            $(".check_code").click(function(e) {
                e.preventDefault();

                $.ajax({

                    url: "./modules/promo/check_promo_api.php?type=check",
                    type: "GET",
                    data: $("#form-check-promo").serialize(),
                    dataType: 'json',
                    success: function(response) {

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
                    error: function() {

                    }

                }); //END :: AJAX

            });

            $("#form-check-promo").submit(function(e) {
                e.preventDefault();

                $.ajax({

                    url: "./modules/promo/check_promo_api.php?type=use",
                    type: "GET",
                    data: $("#form-check-promo").serialize(),
                    dataType: 'json',
                    success: function(response) {

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
                    error: function() {

                    }

                }); //END :: AJAX

            });


            $(document).on('click', '.add_count_increment_pbag', function() {
                const $this = $(this);
                // let count_num_val = parseInt($this.parent().parent().find('.count_num_pbag').val());
                let count_num_val = 0;

                $('.count_num_pbag').each(function() {
                    count_num_val += parseInt($(this).val()) || 0;
                    console.log("Test: ", count_num_val);
                });

                const productSelected = $this.parents('.product-section').find('select').val();
                if (!productSelected) {
                    alert('Please select paper bag');
                    return false;
                }

                $('#loading').modal('show');
                if (count_num_val >= total_count && !confirm('You are about to exceed the total count of frames.')) {
                    setTimeout(() => $('#loading').modal('hide'), 50);
                    return;
                }

                const groupOrdersSpecsIdAttr = $this.attr('group-orders-specs-id');
                let arrOrdersSpescId = groupOrdersSpecsIdAttr ? groupOrdersSpecsIdAttr.split(",") : [];

                // console.log(count_num_val >= total_count ? "test 1" : "test 2", arrOrdersSpescId);

                $.post("/v2.0/sis/studios/func/process/add_to_bag_merch.php", {
                    studios_product_code: productSelected,
                    paper_bag: true
                }, function(result) {
                    arrOrdersSpescId.push(result);
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    console.log("Test", arrOrdersSpescId)
                    $this.attr('group-orders-specs-id', arrOrdersSpescId);
                    $this.parent().parent().find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);

                    const current_value = parseInt($this.parent().parent().find('.count_num_pbag').val()) || 0;
                    const updated_value = current_value + 1;
                    $this.parent().parent().find('.count_num_pbag').val(updated_value);

                    $this.parents('.product-section').find('select option').each(function() {
                        if ($(this).val() != productSelected) {
                            $(this).remove();
                        }
                    });

                    // setTimeout(() => $('#loading').modal('hide'), 200);
                    updated_value === 1 ?
                        location.reload() :
                        setTimeout(() => $('#loading').modal('hide'), 500);

                });
            });


            $(document).on('click', '.minus_count_decrement_pbag', function() {
                const _this = $(this);
                const $parent = _this.closest('.count_item');
                let current_value = parseInt($parent.find('.count_num_pbag').val());

                if (current_value > 0) {
                    $('#loading').modal('show');

                    let arrOrdersSpescId = _this.attr('group-orders-specs-id').split(",");
                    console.log("Group:", arrOrdersSpescId);
                    const arrOrdersSpescIdRemove = arrOrdersSpescId.pop();
                    console.log("Remove: ", arrOrdersSpescIdRemove)

                    $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function() {
                        const updatedOrdersSpecId = arrOrdersSpescId.join(",");

                        _this.attr('group-orders-specs-id', updatedOrdersSpecId);
                        $parent.find('.add_count_increment_pbag').attr('group-orders-specs-id', updatedOrdersSpecId);
                        const updatedCount = current_value - 1;
                        $parent.find('.count_num_pbag').val(updatedCount);

                        updatedCount === 0 ?
                            location.reload() :
                            setTimeout(() => $('#loading').modal('hide'), 500);
                    });
                }
            });


            $(this).on('click', '#add_section_paper_bag', function() {
                $.get('/v2.0/sis/studios/v1.0/modules/includes/products/add_paper_bag_section.php', function(result) {
                    $('.card-paper-bag .card-body').append(result);
                });
            });


            $(document).on('click', '.add_count_increment_sac', function() {
                const $this = $(this);
                let count_num_val = 0;

                $('.count_num_sac').each(function() {
                    count_num_val += parseInt($(this).val()) || 0;
                });

                const productSelected = $this.parents('.product-section').find('select').val();
                if (!productSelected) {
                    alert('Please select sac');
                    return false;
                }

                $('#loading').modal('show');
                if (count_num_val >= total_count && !confirm('You are about to exceed the total count of frames.')) {
                    setTimeout(() => $('#loading').modal('hide'), 50);
                    return;
                }

                const groupOrdersSpecsIdAttr = $this.attr('group-orders-specs-id');
                let arrOrdersSpescId = groupOrdersSpecsIdAttr ? groupOrdersSpecsIdAttr.split(",") : [];

                // console.log(count_num_val >= total_count ? "test 1" : "test 2", arrOrdersSpescId);

                $.post("/v2.0/sis/studios/func/process/add_to_bag_merch.php", {
                    studios_product_code: productSelected,
                    paper_bag: true
                }, function(result) {
                    arrOrdersSpescId.push(result);
                    arrOrdersSpescId = arrOrdersSpescId.join(",");

                    $this.attr('group-orders-specs-id', arrOrdersSpescId);
                    $this.parent().parent().find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);

                    const current_value = parseInt($this.parent().parent().find('.count_num_sac').val()) || 0;
                    const updated_value = current_value + 1;
                    $this.parent().parent().find('.count_num_sac').val(updated_value);

                    $this.parents('.product-section').find('select option').each(function() {
                        if ($(this).val() != productSelected) {
                            $(this).remove();
                        }
                    });

                    // setTimeout(() => $('#loading').modal('hide'), 200);
                    updated_value === 1 ?
                        location.reload() :
                        setTimeout(() => $('#loading').modal('hide'), 500);

                });
            });

            $(document).on('click', '.minus_count_decrement_sac', function() {
                const _this = $(this);
                const $parent = _this.closest('.count_item');
                let current_value = parseInt($parent.find('.count_num_sac').val());

                if (current_value > 0) {
                    $('#loading').modal('show');
                    let arrOrdersSpescId = _this.attr('group-orders-specs-id').split(",");
                    console.log("Sac: ", arrOrdersSpescId)
                    const arrOrdersSpescIdRemove = arrOrdersSpescId.pop();

                    $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function() {
                        const updatedOrdersSpecId = arrOrdersSpescId.join(",");

                        _this.attr('group-orders-specs-id', updatedOrdersSpecId);
                        $parent.find('.add_count_increment_sac').attr('group-orders-specs-id', updatedOrdersSpecId);
                        const updatedCount = current_value - 1;
                        $parent.find('.count_num_sac').val(updatedCount);

                        updatedCount === 0 ?
                            location.reload() :
                            setTimeout(() => $('#loading').modal('hide'), 500);
                    });
                }
            });


            $(this).on('click', '#add_section_sac', function() {
                // console.log("Hello World!")
                $.get('/v2.0/sis/studios/v1.0/modules/includes/products/add_sac_section.php', function(result) {
                    $('.card-sac .card-body').append(result);
                });
            });

            $(this).on('change', '#findout', function() {
                $('#loading').modal('show');
                $.post("/v2.0/sis/studios/func/process/findout.php", {
                    findout: $(this).val()
                }, function(result) {
                    setTimeout(() => {
                        $('#loading').modal('hide');
                    }, 200);
                });
            });

            $(this).on('click', '.club_member', function() {
                $('#loading').modal('show');
                $.post("/v2.0/sis/studios/func/process/club_member.php", {
                    club_member: $(this).val()
                }, function(result) {
                    setTimeout(() => {
                        $('#loading').modal('hide');
                    }, 200);
                });
            });

            bool_receipt_holder = ($('.set_receipt_holder').attr('orders-specs-id') == '') ? false : true;

            $(this).on('click', '.set_receipt_holder', function() {
                bool_receipt_holder = true;
                if ($(this).val() == 'yes') {
                    if ($(this).attr('orders-specs-id') == '') {
                        $('#loading').modal('show');
                        $.post("/v2.0/sis/studios/func/process/add_to_bag_merch.php", {
                            studios_product_code: $(this).attr('product-code'),
                            paper_bag: true
                        }, function(result) {
                            $('.set_receipt_holder').attr('orders-specs-id', result);
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    }
                } else {
                    if ($(this).attr('orders-specs-id') != '') {
                        $('#loading').modal('show');
                        $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                            orders_specs_id: $(this).attr('orders-specs-id')
                        }, function() {
                            $('.set_receipt_holder').attr('orders-specs-id', '');
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    }
                }
            });

            // $('#send-order').click(function(e) {
            //     if (!bool_receipt_holder) {
            //         e.preventDefault();
            //         alert('Select Yes/No Does this order include a Receipt Holder?');
            //     }
            // });


            $(this).on('click', '.add_count_increment_others', function() {

                let count_num_val = 0;
                $('.count_num_others').each(function() {
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
                        $.post("/v2.0/sis/studios/func/process/add_to_bag_merch.php", {
                            studios_product_code: productSelected,
                            paper_bag: true
                        }, function(result) {
                            //console.log(result);
                            arrOrdersSpescId.push(result);
                            arrOrdersSpescId = arrOrdersSpescId.join(",");
                            _this.attr('group-orders-specs-id', arrOrdersSpescId);
                            _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_others').attr('group-orders-specs-id', arrOrdersSpescId);
                            current_value = _this.parent().parent().find('.count_num_others').val();
                            _this.parent().parent().find('.count_num_others').val(parseInt(current_value) + 1);

                            _this.parents('.product-section').find('select option').each(function() {
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
                    $.post("/v2.0/sis/studios/func/process/add_to_bag_merch.php", {
                        studios_product_code: productSelected,
                        paper_bag: true
                    }, function(result) {
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_others').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value = _this.parent().parent().find('.count_num_others').val();
                        _this.parent().parent().find('.count_num_others').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function() {
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

            $(this).on('click', '.minus_count_decrement_others', function() {
                $('#loading').modal('show');
                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num_others').val();
                if (current_value > 0) {
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function() {
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

            $(this).on('click', '#add_section_others', function() {
                $.get('/v2.0/sis/studios/v1.0/modules/includes/products/add_others_section.php', function(result) {
                    $('.card-others .card-body').append(result);
                });
            });
            // $(this).on('click', '.minus_count_decrement', function(){
            //     current_value = $(this).parent().parent().find('.count_num').val();
            //     if(current_value > 0){
            //         $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
            //     }
            // });
            $('#btn-signup').click(function() {
                $('#modal-signup').modal('show');
            });

            $('#update_guest_account').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/v2.0/sis/studios/func/process/signup-guest-account.php",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        if (response.indexOf('done') > -1) {
                            location.reload(true);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
            const bdateElement = document.getElementById("bdate2");
            if (bdateElement) {
                bdateElement.setAttribute("max", '2019-12-31');
            }

            let getAge = (value) => {
                var today = new Date().getTime(),
                    dob = new Date(value).getTime(),
                    age = today - dob;
                yoa = Math.floor(age / 1000 / 60 / 60 / 24 / 365.25);
                $('#age').val(yoa);
            }

            $('#bdate2').on('change', function() {
                var bday = $(this).val();
                $('#bdate').val(bday);
                getAge(bday);
            });

            $('#mnum').on('blur', function() {
                if (/^[0-9]/.test(this.value)) {
                    this.value = this.value.replace(/^0/, "");
                    formatNumber(this);
                }
            });
        });

        function selectEmoji(type) {
		// Hide both non-colored images
            document.getElementById('happy-btn').style.display = 'none';
            document.getElementById('sad-btn').style.display = 'none';

            // Show the corresponding colored image
            let emojiImage = document.createElement('img');
            emojiImage.alt = type === 'sad' ? 'Sad Emoji' : 'Happy Emoji';
            emojiImage.src = type === 'sad' ? '../assets/images/icons/frown_colored.png' : '../assets/images/icons/smile_colored.png'; // Path to the colored image
            document.querySelector('.survey-container .d-flex').appendChild(emojiImage);

            // Set the feedback value
            document.getElementById('feedback').value = type; // Set feedback value to 'happy' or 'sad'

            sendFeedback(document.getElementById('feedback').value);
	    }

        function sendFeedback(feedback){
		$.ajax({
			url: "./modules/process/customer_survey.php",
			type: "POST",
			data: {
				feedback: feedback
			}
		});
	}


    </script>


<?php endif; ?>