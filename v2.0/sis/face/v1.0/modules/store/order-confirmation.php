<style>
    .card {
        width: 100%;
        border: none;
        border-radius: 16px;
        padding: 16px;
        overflow: hidden;
    }

    .add_paper {
        height: 48px;
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

    .select_paper_bag {
        margin-bottom: 0;
    }

    .count_num_pbag,
    .count_num_sac,
    .count_num_others {
        width: 50px;
        border: none;
        text-align: center;
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
            <a href="/v2.0/sis/face/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
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



        <div class="custom-subtitle mb-3 mt-3">
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

                <div class="customized-card my-4 w-100 p-4 " style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="no-gutters d-flex">
                        <div class="d-flex align-items-center justify-content-center">
                                                    
                        <?php
                            if (!empty($item['image_url'])) {
                                $image_url = $item['image_url'];
                                $fit = 'cover';
                            } else {
                                $image_url = '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png';
                                $fit = 'contain';
                            }
                        ?>
                            <img src="<?= !empty($item['image_url']) ? $item['image_url'] : '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png' ?>"
                                class="card-img" alt="Product Image" style="width: 120px; height: 126px; object-fit: <?= $fit ?>; border-radius: 8px">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body d-flex flex-column gap-3 pt-0 pb-0 pr-0">
                                <p class="custom-title"><?= ucfirst($item['style']) ?></p>
                                <p class="custom-subtitle" style="font-size: 1.125rem; font-weight: 400;"><?= ucfirst(trim($item['color'])) ?>
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




        <div class="custom-title mb-3" style="text-transform: uppercase;">
            <?= $arrTranslate['Packaging'] ?>
        </div>


        <div class="card mt-3 card-paper-bag" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
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
                            <div class="form-group select_paper_bag">
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
                                <!-- <input type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id=""
                                    value="-"> -->
                                <button type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id=""
                                    style="height: 40px; width: 48px; background: #fff;">
                                    <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="minus"
                                        style="height: 24px; width: 24px;">
                                </button>
                            </div>

                            <input type="text" style="font-size: 16px;" class="form-control count_num_pbag bg-transparent"
                                group-orders-specs-id="" value="0" readonly>


                            <div class="button-container">
                                <!-- <input type="button" class="add_count_increment_pbag custom-button" group-orders-specs-id=""
                                    value="+"> -->
                                <button type="button" class="add_count_increment_pbag custom-button" group-orders-specs-id=""
                                    style="height: 40px; width: 48px; background: #fff;">
                                    <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                        style="height: 24px; width: 24px;">
                                </button>
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

        <!-- <div class="card card-others mt-3 w-100 p-4 d-flex align-items-left justify-content-center" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
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
                <div class="d-flex">
                    <div class="d-flex align-items-center radio">
                        <input type="checkbox" name="receipt_holder" id="receipt_holder_checkbox"
                            class="sr-only set_receipt_holder checkbox" orders-specs-id="<?= $orders_specs_id_selected ?>"
                            product-code="<?= $arrPaperBag[0]['product_code'] ?>" value="yes" <?= $orders_specs_id_selected != '' ? 'checked' : '' ?>>
                        <label for="receipt_holder_checkbox" class="custom_checkbox"></label>
                    </div>
                </div>

                <div class="col align-items-center">
                    <p style="font-size: 18px; font-weight: 400">Receipt Holder?</p>
                </div>

            </div>
        </div> -->

		<div class="survey-container mt-4" style="height: 232px;">
			<p class="text-uppercase font-bold">Customer Survey</p>
			<div class="d-flex mt-4" style="justify-content: center;">
				<!-- Non-colored images initially displayed -->
				<img id="happy-btn" class="emoji-btn" src="../assets/images/icons/smile_colored.png" onclick="selectEmoji('happy')" alt="Happy Emoji">
				<img id="sad-btn" class="emoji-btn" src="../assets/images/icons/frown_colored.png" onclick="selectEmoji('sad')" alt="Sad Emoji">
			</div>
			<input type="hidden" id="feedback" name="feedback" value="">					
		</div>



        <div class="card mt-3 p-4 d-flex" style="color: #342C29; gap: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 544px; margin-left: auto; margin-right: auto;">
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

        <?php if (!isset($_SESSION["autologin"])) { ?>
            <div class="card mt-4 w-100 p-4 d-flex" style="color: #342C29; gap: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 544px !important; margin-left: auto; margin-right: auto;">
                <div class="d-flex align-items-center ">
                    <div style="height: 40px; width: 40px">
                        <img src="<?= get_url('images/icons') ?>/icon-user.png" alt="user" class="img-fluid">
                    </div>
                    <div class="col ml-2">
                        <p class="custom-subtitle" style="text-transform: capitalize; font-weight: 700; font-size: 18px;">
                            <?= get_customer_data("first_name") ?> <?= get_customer_data("last_name") ?>
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
                <div id="bottom-content" class=" d-flex text-center align-items-center justify-content-center mt-3 mb-3"
                    style=" bottom: 0; left: 0; width: 100%; ">
                    <div id="bottom-content-inner" style=" width: 544px; padding: 8px 0;">

                        <div class="row">
                            <div class="col-12 ">
                                <div class="customer-account text-center">
                                    <a
                                        href="./?page=account-form&type=sign-up&bpage=<?php echo htmlspecialchars($_GET['bpage']); ?>">
                                        <button class="btn btn-primary" style='height: 56px;'>Log in or Sign up </button>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <div class="customer-account text-center">
                                    <a
                                        href="./?page=account-form&guest=true&bpage=<?php echo htmlspecialchars($_GET['bpage']); ?>">
                                        <button class="btn btn-not-cancel" style='height: 56px;'>Check out as guest</button>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            <?php } else { ?>


                <?php $textSend = (trim($_SESSION['store_type']) == 'ns') ? 'Send to Cashier' : $arrTranslate['Dispatch Order']; ?>
                <a href="/v2.0/sis/face/func/process/order_payment.php?path_loc=v1.0&bdate=<?= get_customer_data('age') ?>" id="send-order" style="display: flex; justify-content: center; width: 100%;">
                    <button class="btn btn-primary mt-4 mb-5" style="height: 56px; width: 544px;"><?php echo $textSend; ?></button>
                </a>

            <?php } ?>
        </div>



    </div>


    <script>
        let total_count = <?= $total_count ?>;
        $(document).ready(function() {
            	$('.close-button-promo').click(function(){
		location.reload();
	})


	$(".use_code").hide();
	$('.check-promo-code').click(function(){
		
		var modal = document.getElementById("myModal");
		var modalData = document.getElementById("modal-data");
			console.log(modalData); // Debugging statement
		
			modal.style.display = "block";
			
		$("#modal-data").load("modules/promo/check_promo.php");
		
		$("#modal-data").modal("show");
	});


	$(".check_code").click(function(e){
			
		e.preventDefault();

		$.ajax({

			url: "./modules/promo/check_promo_api.php?type=check",
			type: "GET",
			data: $("#form-check-promo").serialize(),
			dataType: 'json',
			success: function(response){

					color = "red";
					if(response.valid==true){
						color = "green";
						// location.reload();
						$(".use_code").show();
					}else{
						$(".use_code").hide();
					}

					$("#check-promo-message").text(response.message);
					$("#check-promo-message").css("color",color);

			},
			error: function(){

			}

		});//END :: AJAX

	});

	$("#form-check-promo").submit(function(e){
		e.preventDefault();

			$.ajax({

				url: "./modules/promo/check_promo_api.php?type=use",
				type: "GET",
				data: $("#form-check-promo").serialize(),
				dataType: 'json',
				success: function(response){

						color = "red";
						if(response.valid==true){
							color = "green";
							location.reload();
						}else{
							$(".use_code").hide();
						}

						$("#check-promo-message").text(response.message);
						$("#check-promo-message").css("color",color);

				},
				error: function(){

				}

			});//END :: AJAX

	});


            $(this).on('click', '.add_count_increment_pbag', function() {
                let count_num_val = 0;
                $('.count_num_pbag').each(function() {
                    count_num_val += parseInt($(this).val());
                });
                productSelected = $(this).parents('.product-section').find('select').val();
                if (productSelected == '') {
                    alert('Please select paper bag');
                    return false;
                }
                $('#loading').modal('show');
                
                if (count_num_val >= total_count) {
                    if (confirm('Your about to exceed the total count of items.')) {

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
                            _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                            current_value = _this.parent().parent().find('.count_num_pbag').val();
                            _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) + 1);

                            // _this.parents('.product-section').find('select option').each(function() {
                            //     if ($(this).val() != productSelected) {
                            //         $(this).remove();
                            //     }
                            // });
                            setTimeout(() => {
                                $('#loading').modal('hide');
                            }, 200);
                        });
                    } else {
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 50);
                    }
                } else if ((count_num_val < total_count) && (count_num_val >= 0)) {
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
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value = _this.parent().parent().find('.count_num_pbag').val();
                        _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) + 1);
                        // _this.parents('.product-section').find('select option').each(function() {
                        //     if ($(this).val() != productSelected) {
                        //         $(this).remove();
                        //     }
                        // });
                        setTimeout(() => {
                            $('#loading').modal('hide');
                        }, 200);
                    });
                }
            });

            $(this).on('click', '.minus_count_decrement_pbag', function() {

                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num_pbag').val();
                if (current_value > 0) {
                    $('#loading').modal('show');
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function() {
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

            $(this).on('click', '#add_section_paper_bag', function() {
                $.get('/v2.0/sis/studios/v1.0/modules/includes/products/add_paper_bag_section.php', function(result) {
                    $('.card-paper-bag .card-body').append(result);
                });
            });

            $(this).on('click', '.add_count_increment_sac', function() {
                let count_num_val = 0;
                $('.count_num_sac').each(function() {
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
                        $.post("/v2.0/sis/studios/func/process/add_to_bag_merch.php", {
                            studios_product_code: productSelected,
                            paper_bag: true
                        }, function(result) {
                            //console.log(result);
                            arrOrdersSpescId.push(result);
                            arrOrdersSpescId = arrOrdersSpescId.join(",");
                            _this.attr('group-orders-specs-id', arrOrdersSpescId);
                            _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                            current_value = _this.parent().parent().find('.count_num_sac').val();
                            _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) + 1);

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
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value = _this.parent().parent().find('.count_num_sac').val();
                        _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) + 1);
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

            $(this).on('click', '.minus_count_decrement_sac', function() {
                $('#loading').modal('show');
                _this = $(this);
                current_value = $(this).parent().parent().find('.count_num_sac').val();
                if (current_value > 0) {
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/v2.0/sis/studios/func/process/remove_item.php", {
                        orders_specs_id: arrOrdersSpescIdRemove
                    }, function() {
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
                        $.post("/v2.0/sis/face/func/process/add_to_bag_merch.php", {
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
                        $.post("/v2.0/sis/face/func/process/remove_item.php", {
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

            // $('#send-order').click(function (e) {
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


<?php } ?>