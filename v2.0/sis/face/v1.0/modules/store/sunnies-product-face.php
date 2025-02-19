<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['customer_id'])) {
?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/v2.0/sis/face/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>
<?php
} else {

    $subProduct = (isset($_GET['sub-product'])) ? ' AND p.item_name LIKE "%' . mysqli_real_escape_String($conn, $_GET['sub-product']) . '%"' : '';

    switch ($_GET['page']) {

        case "select-store-lips":
            include "./modules/includes/products/grab_sun_frames.php";
            break;
        case "select-store-face":
            include "./modules/includes/products/grab_face.php";
            break;
        case "select-store-brows":
            include "./modules/includes/products/grab_brows.php";
            break;
        case "select-store-eyes":
            include "./modules/includes/products/grab_eyes.php";
            break;
        case "select-store-skin":
            include "./modules/includes/products/grab_skin.php";
            break;
        case "select-store-cheeks":
            include "./modules/includes/products/grab_cheeks.php";
            break;
    }


?>

    <link rel="stylesheet" type="text/css" href="/v2.0/sis/face/v1.0/modules/store/little_sis.css">
    <link rel="stylesheet" type="text/css" href="/v2.0/sis/face/assets/css/color_pickers.css">
    <style>
        .item-price {
            color: #f1f1f1;
        }

        /* Remove Progress Bar */
        .step-progress {
            display: none !important;
        }

        .customer-layout {
            padding-top: 20px !important;
        }

        body * {
            font-size: revert;
        }

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

        .frame-style {
            padding: 0 10px;
        }

        .frame-style .frame-style__slider {
            border-radius: 10px 10px 0 0;
            overflow: hidden;
        }

        .frame-style .product-option {
            cursor: pointer;
        }

        .frame-style .list-item {
            box-shadow: none;
            border-radius: 0;
            margin: 0;
            height: auto;
        }

        /*.list-item.frame-grid {
        padding-bottom: 0 !important;
    }*/
        #btn-filter {
            max-width: 111px;
            color: #956E46;
            height: 40px;
        }

        #cart {
            padding: 2px 5px 0px 5px;
            vertical-align: center;
            border-radius: 30px;
            cursor: pointer;
        }

        #cart .bag-wrapper {
            background-image: url(/v2.0/sis/face/assets/images/icons/icon-bag-black.png);
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            height: 28px;
            width: 28px;
        }

        .count {
            /*background-color: #FFD5C6; padding: 0px 7px; border-radius: 15px;*/
            height: 100%;
            text-align: center;
            padding-top: 8px;
            font-size: 12px;
            color: #fff;
        }

        .card {
            border-radius: 15px;
        }

        .cart_view .card-body {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        
        .btn-product {
            /* Should be outline button, text is not bold and padding is small */
            background-color: #fff;
            border: 3px solid #956E46;
            color: #956E46;
            padding: 3px 10px;
            font-size: 16px;
            line-height: 1; /* Adjust the line-height to reduce height */
        }

        .btn-product:hover {
            background-color: #956E46;
            color: #fff;
        }

        .select-store-studios {
            background-color: white !Important;
        }

        #ssis_header {
            background-color: #e8e8e4;
        }

        ul {
            list-style: none
        }

        ul li {
            line-height: 1;
            padding: 16px 0
        }

        ul.tags-list {
            padding-left: 10px;
            padding-top: 16px;
            padding-bottom: 16px;
            list-style: none;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        ul.tags-list li {
            width: 50%;
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            -ms-flex-align: start;
            align-items: flex-start;
            border: 0
        }

        ul.tags-list li.tag-icon {
            background-repeat: no-repeat;
            background-size: 30px;
            padding-left: 40px;
            background-position: 0
        }

        .tag-icon__polycarbonate {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Polycarbonate.png?v=1610094044)
        }

        .tag-icon__copper {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Copper.png?v=1610094044)
        }

        .tag-icon__stainless-steel {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Stainless_steel.png?v=1610094044)
        }

        .tag-icon__acetate {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Acetate.png?v=1610094044)
        }

        .tag-icon__tr90 {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_TR90.png?v=1610094044)
        }

        .tag-icon__extra-wide-frame {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Extra_wide_frame.png?v=1610094044)
        }

        .tag-icon__wide-frame {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Wide_frame.png?v=1610094044)
        }

        .tag-icon__medium-frame {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Medium_frame.png?v=1610094044)
        }

        .tag-icon__kids-frame,
        .tag-icon__narrow-frame {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Narrow_frame.png?v=1610094044)
        }

        .tag-icon__shiny-finish {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Shiny_finish.png?v=1610094044)
        }

        .tag-icon__matte-finish {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Matte_finish.png?v=1610094044)
        }

        .tag-icon__bestseller {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Bestseller.png?v=1610094044)
        }

        .tag-icon__new-in {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_New_in.png?v=1610094044)
        }

        .tag-icon__lightweight {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Lightweight.png?v=1610094044)
        }

        .tag-icon__men-s-daily,
        .tag-icon__mens-daily {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Men_s_daily.png?v=1610094044)
        }

        .tag-icon__mixed-material {
            background-image: url(https://cdn.shopify.com/s/files/1/0172/4383/2374/files/Web_Mixed_Material.png?v=1611803243)
        }

        .type-header {
            font-size: 18px !important;
            padding: 10px 0 !important;
        }

        @media only screen and (max-width: 600px) {

            ul.tags-list li {
                font-size: 12px;
            }

            #form-add-to-bag .d-flex-m {
                display: block !important;
            }

            .product-top {
                flex: 0 0 100%;
            }

            .product-top .justify-content-center {
                -ms-flex-pack: left !important;
                justify-content: left !important;
            }

            main.customer-layout .wrapper {
                overflow-y: none;
            }

            #form-search {
                display: flex;

                height: 43px;
                border: none;
                width: 100%;
            }

            #search_frame_filter {
                flex: 1;
                /* Take up remaining space */
                border: none;
                /* Remove any existing border */
                border-bottom: 1px solid #ccc;
                /* Add a bottom border */
                margin-right: 20px;
            }

            #search_frame_filter:focus {
                outline: none;
            }



            .search-icon {
                width: 25px;
                /* Adjust image size */
                height: 25px;

                /* Ensure image is block-level */
            }

            .product-view {
                overflow-x: hidden !important;
            }

        }
    </style>
    <div class="packages-list hiding">
        <?php if (isset($_GET['product-detail']) && trim($_GET['product-detail']) != "") {
            $arrProductDetails = [];


            $arrProductDetails = array_values(array_filter($arrProduct, function ($item) {
                return (trim($item['item_description'])) === (trim($_GET['style']));
                // return $item['item_description'] ;
            }));

        ?>
            <section class="product-view" id="product-panel" style="height:100vh; max-width: 575px; overflow: auto;  margin-bottom: 120px;">

                <div class="search-container-store d-flex align-items-center mb-4">
                    <!-- Add Back Button here -->
                    <a href="/v2.0/sis/face/v1.0/?page=<?= $_GET['page'] ?>" class="exit-frame-selection">
                        <img src="<?= get_url('images/icons') ?>/icon-arrow-left.png" alt="exit" class="img-fluid" style="width: 24px;">
                    </a>
                    <!-- <div id="btn-filter" class="btn btn-not-cancel"> <img id="bag-icon"
                            src="<?= get_url('images/icons') ?>/icon-filter.png" alt="Bag"
                            style="margin-left: 3px; margin-right: 9px; height: 24px; width: 24px;"> Filter</div>
                    <div id="form-search" class="d-flex align-items-center"></div> -->
                    <input type="search" name="search_frame" id="search_frame" class="form-control  search" placeholder="Search"
                        value="<?= (isset($_GET['search']) && $_GET['search'] != '') ? $_GET['search'] : '' ?>">
                    <div id="toggleLayout" style="display: none;"></div>

                    <div class="">
                        <button id="btn-search" class="btn-search">
                            <img src="<?= get_url('images/icons') ?>/icon-search.png" alt="Search"
                                style=" height: 24px; width: 24px;">
                        </button>
                    </div>
                </div>



                <?php
                if (!isset($_GET['search'])) {
                ?>
                    <div class="flex-container mb-3">
                        <button class="btn btn-bag d-flex align-items-center justify-content-center" id="cart" disabled>
                            <img id="bag-icon" src="<?= get_url('images/icons') ?>/icon-shopping-bag.png" alt="Bag"
                                style="margin-left: 3px; margin-right: 9px; width: 18px;"> View Bag
                        </button>
                    </div>
                <?php
                }
                ?>

                <form href="#" id="form-add-to-bag">
                    <div class="d-flex flex-row" style="width: 100%; height: 50vh;">
                        <div class="frame-style card mb-3 hide-lazy d-flex align-items-stretch" data-style="<?= $arrProductDetails[0]['item_description'] ?>" style="width: 50%; height: 100%;">
                            <div class="frame-style__slider">
                                <?php
                                // Set current colors array
                                $curColors = $arrProductDetails;

                                for ($a = 0; $a < sizeOf($curColors); $a++) {
                                ?>
                                    <div class="specific-product" data-color-name="<?= $curColors[$a]['color'] ?>"
                                        data-color-code="<?= $curColors[$a]['product_code'] ?>"
                                        product-code="<?= $curColors[$a]['product_code'] ?>">

                                        <input type="radio" name="frame_style" class="sr-only">
                                        <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center"
                                            style="background-color: #fff;">

                                            <?php
                                            $curImageURL = '';
                                            $ImageURL = $curColors[$a]["image_url"];

                                            if ($ImageURL == null || $ImageURL == '') {
                                                $curImageURL = '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png';
                                            } else {
                                                $curImageURL = $ImageURL;
                                            }

                                            // $curImageURL = $ImageURL;
                                            ?>

                                            <div class="image-wrapper"
                                                style="width: 100%; height: 45vh;  padding-bottom: 50%; border-radius: 8px; background-color: #f2efea; background-image: url('<?= $curImageURL ?>'); background-repeat: no-repeat; background-size: 100%; background-position: center;">
                                            </div>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div style="background: #fff; width: 50%; height: 100%;" class="card p-3 pt-4 d-flex align-items-stretch ml-3">
                            <input type="hidden" id="studios-product-code" name="studios_product_code"
                                value="<?= trim($curColors[0]['product_code']) ?>">

                            <div class="d-flex justify-content-between">
                                <section class="product-details align-items-start justify-content-between">
                                    <div class="d-flex flex-column gap-4">
                                        <h4 class="mb-3"><?= $arrProductDetails[0]['item_description'] ?></h4>
                                        <h4 class="mb-3"><span class="item-price" style="color:#919191 !important;"><?= trimColor($curColors[0]['color']); ?></span></h4>
                                    </div>
                                </section>
                            </div>

                            <section class="product-details align-items-start justify-content-between mb-3">
                                <h5>
                                    <span class="item-price">P<?= trim($curColors[0]['price']); ?></span>
                                </h5>
                            </section>

                            <ul class="switch-color d-flex align-items-center justify-content-between flex-wrap" style="overflow-y: auto;">
                                <?php
                                $totalColors = sizeof($curColors);

                                foreach ($curColors as $key => $value) {
                                    $a = $key;
                                ?>
                                    <li class="visible color-swatch" data-index="<?= $a ?>"
                                        data-style-name="<?= trim($arrProductDetails[0]['item_description']) ?>"
                                        data-color-name="<?= trimColor($curColors[$a]['color']) ?>"
                                        data-color-code="<?= trim($curColors[$a]['product_code']) ?>"
                                        data-color-price="P<?= $curColors[$a]['price'] ?>"
                                        style="<?= ($curColors[$a]['color_swatch'] != '') ? 'background-color: ' . $curColors[$a]['color_swatch'] . ';' : 'background-color: #000;' ?>">
                                    </li>
                                <?php } ?>
                            </ul>


                        </div>
                    </div>


                    <div class="d-flex justify-content-between mt-3 card p-3" style="height: 20vh;">
                        <div class="description">
                            <?php
                            $product_description = ($arrProductDetails[0]['main_description'] != null && $arrProductDetails[0]['main_description'] != '')
                                ? $arrProductDetails[0]['main_description']
                                : 'No description available';
                            ?>
                            <span style="font-size: 16px; font-weight: 400; color: #342C29;"><?= $product_description ?></span>
                        </div>
                    </div>



                    <div id="bottom-content" class=" d-flex p-2 text-center align-items-center justify-content-center"
                        style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1; background: #fff;">
                        <div id="bottom-content-inner" style=" width: 575px; padding: 20px 0;">

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center mr-4">
                                    <div class="d-flex justify-content-center" style="width: 50px; background: #fff;">
                                        <button type="button" id="btn-decrement" class="btn btn-counts minus_count_decrement"
                                            style="height: 40px; width: 48px; background: #fff;">
                                            <img src="<?= get_url('images/icons') ?>/icon-decrement.png" alt="minus"
                                                style="height: 20px; width: 20px;">
                                        </button>
                                    </div>

                                    <input type="text" class="form-control count_num" name="count_num_value" value="1"
                                        style="background-color: transparent; border: 0; font-size: 16px; text-align: center; width: 50px; padding-bottom: 1px;"
                                        readonly>

                                    <div class="d-flex justify-content-center" style="width: 50px; background: #fff;">
                                        <button type="button" id="btn-increment" class="btn btn-counts add_count_increment"
                                            style="height: 40px; width: 48px; background: #fff;">
                                            <img src="<?= get_url('images/icons') ?>/icon-increment.png" alt="add"
                                                style="height: 20px; width: 20px;">
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" style="width: 359px; height: 56px;">Add to
                                    bag</button>
                            </div>


                        </div>
                    </div>
                </form>

            </section>

            <script>
                $(document).ready(function() {
                    // Event listener for color swatch clicks
                    $('.color-swatch').on('click', function() {
                        // Get the product code of the selected color
                        let newProductCode = $(this).data('color-code');
                        let newColorName = $(this).data('color-name');
                        let newColorPrice = $(this).data('color-price');

                        // Update the hidden input field with the new product code
                        $('#studios-product-code').val(newProductCode);

                        // Optionally update other elements (like color name or price) if needed
                        $('.blk').text(newColorName);
                        $('.item-price').text(newColorPrice);

                        // Add active class to the selected color and remove it from siblings
                        $(this).addClass('active').siblings().removeClass('active');
                    });
                });
            </script>

        <?php } else { ?>

            <section class="product-view" id="product-panel" style="max-width: 575px; overflow: auto;">

                <!-- <div class="d-flex align-items-center mb-3 mt-5">
                    <a href="./?page=select-store" class="exit-frame-selection"><img src="/assets/images/icons/icon-left-arrow.png" alt="exit" class="img-fluid" style="width: 50px;"></a>
                    <input type="search" name="search_frame" id="search_frame" class="form-control filled search" placeholder="Search" style="margin-left: 20px;" value="<?= (isset($_GET['search']) && $_GET['search'] != '') ? $_GET['search'] : '' ?>">
                    <div id="btn-filter" class="btn btn-secondary mr-4 ml-4">Filter</div>
                    <div id="toggleLayout" style="display: none;"></div>
                    <div  class="d-flex justify-content-between" id="cart" title="Cart" style="padding: 0;">
                        <div class="bag-wrapper">
                            <span><div class="count" count="<?= $order_count ?>"></div></span>    
                        </div>
                    </div>
                </div> -->

                <div class="search-container-store d-flex align-items-center">
                    <!-- Add Back Button here -->
                    <a href="/v2.0/sis/face/v1.0/?page=select-store" class="exit-frame-selection">
                        <img src="<?= get_url('images/icons') ?>/icon-arrow-left.png" alt="exit" class="img-fluid" style="width: 24px;">
                    </a>

                    <input type="search" name="search_frame" id="search_frame" class="form-control  search" placeholder="Search"
                        value="<?= (isset($_GET['search']) && $_GET['search'] != '') ? $_GET['search'] : '' ?>">
                    <div id="toggleLayout" style="display: none;"></div>

                    <div class="">
                        <button id="btn-search" class="btn-search">
                            <img src="<?= get_url('images/icons') ?>/icon-search.png" alt="Search"
                                style=" height: 24px; width: 24px;">
                        </button>
                    </div>
                </div>



                <?php
                if (!isset($_GET['search']) || $_GET['search'] == '') {
                ?>
                    <div class="flex-container">
                        <button class="btn btn-bag d-flex align-items-center justify-content-center" id="cart" disabled>
                            <img id="bag-icon" src="<?= get_url('images/icons') ?>/icon-shopping-bag.png" alt="Bag"
                                style="margin-left: 3px; margin-right: 9px; width: 18px;"> View Bag
                        </button>
                    </div>
                <?php
                }
                ?>
                <div class="font-weight-bold">
                    <h4 class="type-header font-weight-bold p-0">
                        <?php
                        $parts = explode('-', $_GET['page']);
                        echo strtoupper(end($parts));
                        ?>
                    </h4>
                </div>

                <div class="frame-list" style="height:70vh; overflow-y: auto; overflow-x: hidden; padding: 0 5px 0 0;">
                    <?php if (isset($_GET['filter']) && $_GET['filter']) { ?>
                        <!-- <div class="d-flex justify-content-center mt-2 mb-2">
                            <a href="/v2.0/sis/face/v1.0/?page=<?= $_GET['page'] ?>&filter=true&sub-product=<?= $_GET['sub-product'] ?>&data_cgc=<?= $_GET['data_cgc'] ?>&sub_category=<?= $_GET['sub_category'] ?>&category=<?= $_GET['category'] ?>">
                                <div class="btn btn-link" style="color: #000 !important; text-decoration: underline !important;">
                                    Reset Filter</div>
                            </a>
                        </div> -->

                    <?php } ?>

                    <div class="row align-items-start align-items-stretch product-show" style="margin: 0;">
    <?php
    // Loop through all products
    $countData = count($arrProductsSorted);
    for ($i = 0; $i < $countData; $i++) {
        // Loop through each product's colors
        $curColors = $arrProductsSorted[$i]["colors"];
        foreach ($curColors as $color) {
    ?>
            
            <div class="frame-style col-6 mb-3" data-style="<?= $arrProductsSorted[$i]['item_description'] ?>">
                <!-- Single color frame -->
                <div class="product-option"
                     data-color-name="<?= $color['color'] ?>"
                     data-color-code="<?= $color['product_code'] ?>"
                     product-code="<?= $color['product_code'] ?>">
                    <input type="radio" name="frame_style_<?= $i ?>" class="sr-only">
                    <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center"
                           style="background-color: #fff;">
                        <div class="image-wrapper"
                             style="width: 100%; padding-bottom: 75%; border-radius: 8px; background-color: #f1f1f1; background-image: url('<?= $color["image"] ?>'); background-repeat: no-repeat; background-size: 100%; background-position: center;">
                        </div>
                    </label>
                </div>
                <!-- Product details for this color -->
                <div style="background: #fff; border-radius: 0 0 10px 10px; padding: 15px;">
                    <div class="d-flex justify-content-between">
                        <section class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                            <div>
                                <h4><?= $arrProductsSorted[$i]['item_description'] !== '' ? $arrProductsSorted[$i]['item_description'] : '-' ?></h4>
                                <h4><span class="blk"><?= trimColor($color['color']); ?></span></h4>
                                <!-- <?php if (isset($_GET['search'])) { ?>
                                    <h4><span class="blk" style="color:#919191 "><?= $arrProductsSorted[$i]['color_code'] !== '' ? $arrProductsSorted[$i]['color_code'] : '-' ?></span></h4>
                                <?php } ?> -->
                            </div>
                        </section>
                        <section class="product-details flex-nowrap no-gutters align-items-start justify-content-between">
                            <h5>
                                <span class="item-price">P<?= trim($color['price']); ?></span>
                            </h5>
                        </section>
                    </div>
                        <div class="row d-flex justify-content-center mt-3">
                            <div class="col-12">
                                <input type="hidden" name="studios_product_code" id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>" value="<?= trim($color['product_code']) ?>">
                                <form href="#" id="form-add-to-bag">
                                    <input type="hidden" id="studios-product-code" name="studios_product_code" value="<?= trim($color['product_code']) ?>">
                                    <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                    <button type="submit" class="btn btn-product"
                                            data-color-name="<?= $color['color'] ?>"
                                            data-color-code="<?= $color['product_code'] ?>"
                                            product-code="<?= $color['product_code'] ?>">Add To Bag</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
    <?php
        } // end foreach color
    } // end for each product
    ?>
    <div id="notification" class="notification p-2 text-center align-items-center justify-content-center hidden "
            style="background-color: #9DE356; height: 48px; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); z-index: 1; max-width: 492px; width: 100%; margin: 0 auto; border-top-left-radius: 20px; border-top-right-radius: 20px;">

        <div id="notification" class="notification d-flex align-items-center justify-content-between "
            style="width: 100%; padding: 0px 10px;">

            <span class="notification-message text-align-center">Item successfully added to bag</span>
            <button class="btn notification-close" style="background-color: transparent;" onclick="closeNotification()">
                <img src="<?= get_url('images/icons') ?>/icon-close.png" alt="Icon" class="notification-icon">
            </button>
        </div>
    </div>
</div>


            </section>
        <?php } ?>

    </div>

    <style>
        .btn-black {
            background: #000000;
            color: #ffffff;
        }

        .packages-list {
            opacity: 0;
            transition: opacity .3s ease;
            overflow-y: hidden !important;
        }

        .packages-list.show {
            opacity: 1;
        }

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



        /* #toggleLayout {
                                                                    min-width: 25px;
                                                                    min-height: 25px;
                                                                    margin: 0 10px 0 15px;
                                                                    background-image: url(<?= get_url('images') ?>/icons/icon-grid-view.png);
                                                                    background-repeat: no-repeat;
                                                                    background-size: 25px;
                                                                    background-position: center;
                                                                } */
        /* 
                    #toggleLayout.false {
                        background-image: url(<?= get_url('images') ?>/icons/icon-list-secondary.png);
                    } */


        .product-details {
            display: flex;
            flex-direction: column;
            /* Align children in a column */
        }

        .product-details h4 {
            font-size: 14px;
            text-transform: capitalize;
            font-weight: 700;
            line-height: 12px;

        }

        .product-details h4 span {
            font-size: 14px;
            font-weight: 500;
            color: #342C29;
        }

        .product-details h5 span {
            font-size: 14px;
            font-weight: 400;
            color: #919191;
        }

        .product-details p {
            font-size: 12px;
            line-height: 12px;
            white-space: nowrap;
            font-weight: 500;
        }

        .frame-list {
            overflow-x: hidden !important;
        }
    </style>

    <?php
    $arrProductsSortedToShow = [];
    if (isset($countData) && $countData >= 10) {
        for ($i = 10; $i < count($arrProductsSorted); $i++) {
            $arrProductsSortedToShow[] = $arrProductsSorted[$i];
        }
    }
    ?>

    <script>
        function closeNotification() {
            document.getElementById('notification')?.classList.remove('show');
            document.getElementById('notification')?.classList.add('hidden');
        }

        function openNotification() {
            document.getElementById('notification')?.classList.remove('hidden');
            document.getElementById('notification')?.classList.add('show');
        }

        closeNotification();

        let arrProduct = JSON.parse(JSON.stringify(<?= json_encode($arrProduct); ?>));
        let arrCart = JSON.parse(JSON.stringify(<?= json_encode($arrCart); ?>));
        console.log(arrCart);
        let arrColors = <?= json_encode($getColors) ?>;
        let arrShapes = <?= json_encode($getShapes) ?>;
        let arrCollections = <?= json_encode($getCollections) ?>;
        let queryProduct = <?= json_encode($arrProductsSortedToShow) ?>;
        // console.log(queryProduct);
        $(document).ready(function() {

            $('.packages-list').addClass('show');

            totalCount();
            $('#filter').on('click', function() {
                $('.ssis-overlay').load("/ssis/modules/store/studios-filter.php", function(d) {
                    overlayFilter(d);
                });
            });

           $(this).on('click', '.product-option', function() {
               let tempProduct = arrProduct.find(x => x.product_code == $(this).attr('product-code'));
               window.location = "?page=<?= $_GET['page'] ?>&product-detail=true&product-code=" + tempProduct.product_code + "&desc=" + tempProduct.description + "&style=" + tempProduct.item_description + "&color=" + tempProduct.color + "&price=" + tempProduct.price + "&image=" + tempProduct.image_url + "&descr=" + tempProduct.product_description + "&tags=" + tempProduct.tags;
            });

            $("#cart").click(function() {
                window.location.href = "/v2.0/sis/face/v1.0/?page=cart";
                //     let item_cart = '';
                //     for(let i =0 ; i<arrCart.length; i++){
                //         if(parseFloat(arrCart[i].price) > 0){}
                //         else if(arrCart[i].item_description.toLowerCase().indexOf('paper bag') > -1 || arrCart[i].item_description.toLowerCase().indexOf('sac') > -1 || arrCart[i].item_description.toLowerCase().indexOf('receipt') > -1){
                //             continue;
                //         }

                //         if(arrCart[i].dispatch_type == 'packaging'){
                //             continue;
                //         }
                //         total_price = parseFloat(arrCart[i].price) * parseInt(arrCart[i].count);
                //         merchItem = (arrCart[i].product_code_order.indexOf('M100') > -1 ) ? 'prod-item="merch"' : 'prod-item="frame"';
                //         merchItem = (arrCart[i].product_upgrade.indexOf('G100') > -1 ) ? 'prod-item="antirad"' : merchItem;

                //         curStyle = arrCart[i].style;
                //         curColor = arrCart[i].color.trim();
                //         // curColor = arrCart[i].color.trim().replace(/v2.0/ /g, "-");
                //         // curColor = curColor.replace(/v2.0/-f/g, "-full");
                //         // curColor = curColor.replace(/v2.0/-m/g, "-mirror");
                //         // curColor = curColor.replace(/v2.0/-gdt/, "-g");
                //         // curImageURL = "images/face/"+curStyle+"/"+curColor+"/front.png";
                //         width = '';
                //         curImageURL =  arrCart[i].image_url;
                //         if(curImageURL == null) {

                //             curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'+curStyle+'/'+curColor+'/front.png';
                //             width = 'width:100px;';

                //         }

                //         item_cart+='<div class="card cart_view mt-4">'
                //             +'<div class="card-body cart-item">'
                //             +'<div class="row">'
                //             +'<img src="/v2.0/sis/face/assets/images/icons/icon-delete.png" class="img-responsive remove_item" orders-specs-id="'+arrCart[i].group_orders_specs_id+'" style="cursor: pointer; position: absolute; top: 10px; right: 10px;" width="25" height="25" title="Remove this item">'
                //             +'</div>'
                //             +'<div class="row mt-4">'
                //             +'<div class="col-6" style="text-align:left">'
                //             +'<div class="row justify-content-center">'
                //             +'<div style="height: 100px; '+width+' background-image:url('+curImageURL+'); background-repeat: no-repeat; background-size: 80%; background-position: center;" class="img-responsive cart-item-image"></div>'
                //             +'</div>'                                
                //             +'</div>'
                //             +'<div class="col-6 count_item">'
                //             +'<div class="row no-gutters d-flex justify-content-start mt-5 mt-xs-0">'
                //             +'<h2 style="text-transform: uppercase; font-size: 18px;" class="mt-2 product-title">'+curStyle+' <br><span style="font-size: 12px;">'+curColor.replace("-", " ")+'</span></h2>'                                    
                //             +'</div>'
                //             +'<div class="row no-gutters d-flex justify-content-start mt-3">'
                //             +'<p style="font-size: 12px;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>'+parseFloat(arrCart[i].price).toFixed(2)+'</p>'
                //             +'</div>'
                //             +'<div class="row no-gutters d-flex justify-content-start mt-1">'
                //             +'<span><input type="button" class="form-control count_decrement"  price="'+arrCart[i].price+'" group-orders-specs-id="'+arrCart[i].group_orders_specs_id+'" value="-"></span>'
                //             +'<input type="text" class="form-control count_num" value="'+arrCart[i].count+'" readonly>'
                //             +'<span><input type="button" class="form-control count_increment" '+merchItem+' price="'+arrCart[i].price+'" group-orders-specs-id="'+arrCart[i].group_orders_specs_id+'" product-code="'+arrCart[i].product_code+'" value="+"></span>'
                //             +'</div>'
                //             +'</div>'
                //             +'</div>'                        
                //             +'</div>'
                //             +'</div>';
                //     }
                //     if(item_cart == ''){
                //         item_cart +=itemCart();
                //     }else{
                //    item_cart +='<div class="d-flex justify-content-center mt-4" id="btn-sect" style="text-align: center;">'
                //         +'<div class="col-6">'
                //         +'<input type="button" class="btn btn-primary" data-dismiss="modal" value="<?= $arrTranslate['Shop More'] ?>">'
                //         +'</div>'
                //         +'<div class="col-6">'
                //         +'<a href="?page=order-confirmation&bpage='+'<?= $_GET['page'] ?>'+'"><input type="button" class="btn btn-black" value="Proceed"></a>'
                //         +'</div>'
                //         +'</div>';
                //     }
                //     $("#item_cart").html(item_cart);
                //     $("#modal-item").modal("show");
            });
            $(document).on('submit', "#form-add-to-bag", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    url: "/v2.0/sis/face/func/process/add_to_bag.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: 'html',
                    success: function(response) {
                        window.location = "?page=<?= $_GET['page'] ?>";
                    },
                    error: function() {}
                }); 
            });
            $(document).on('submit', ".form-quick-add-to-bag", function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/v2.0/sis/face/func/process/add_to_bag.php",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: 'html',
                    success: function(response) {
                        location.reload(true);
                    },
                    error: function() {}
                }); //END :: AJAX
            });
            $(this).on('click', '.remove_item', function() {
                let this_div = $(this);
                let remove = $.post("/v2.0/sis/face/func/process/remove_item.php", {
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
                if (current_value > 0) {
                    $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                    arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                    $.post("/v2.0/sis/face/func/process/remove_item.php", {
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
            $(this).on('click', '.count_increment', function() {
                _this = $(this);
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                processItem = '';
                attrProdItem = $(this).attr('prod-item');

                // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                itemProd = (attrProdItem != 'frame') ? '_' + attrProdItem : '';
                $.post("/v2.0/sis/face/func/process/add_to_bag" + itemProd + ".php", {
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
                    _this.parent().parent().find('span').eq(0).find('.count_decrement').attr('group-orders-specs-id', arrOrdersSpescId);
                    current_value = _this.parent().parent().find('.count_num').val();
                    _this.parent().parent().find('.count_num').val(parseInt(current_value) + 1);
                    _this.parent().parent().parent().parent().parent().find('.count_times').text(arrCart[index].count);
                    t_price = parseFloat(arrCart[index].count) * parseInt(_this.attr('price'));
                    _this.parent().parent().parent().parent().parent().find('.t_price').text(t_price);
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

            $('#search_frame').on('keyup', function(e) {
                if (e.keyCode === 13) { // Check if Enter key is pressed
                    let filter = '';
                    filter = (arrColors.length > 0 || arrShapes.length > 0 || arrCollections.length > 0 || $('#search_frame').val().trim() !== '') ? '&filter=true' : '';


                    window.location = '?page=<?= $_GET['page'] ?>' + filter + '&search=' + $(this).val();
                }
            });

            // Click event for search button
            $('#btn-search').on('click', function() {
                let filter = '';
                filter = (arrColors.length > 0 || arrShapes.length > 0 || arrCollections.length > 0 || $('#search_frame').val().trim() !== '') ? '&filter=true' : '';


                window.location = '?page=<?= $_GET['page'] ?>' + filter + '&search=' + $('#search_frame').val();
            });


            // $('#search_frame').on('keyup', function () {
            //     // clearTimeout(typingTimer);
            //     // typingTimer = setTimeout(showAvailableFrame, doneTypingInterval);
            //     filter = '';
            //     filter = (arrColors.length > 0 || arrShapes.length > 0 || arrCollections.length > 0 || $(this).val().trim() != '') ? '&filter=true' : '';

            //     setTimeout(()=>{
            //          window.location = '?page=<?= $_GET['page'] ?>'+filter+'&colors='+arrColors+'&shapes='+arrShapes+'&collections='+arrCollections+'&search='+$(this).val()+'&sub-product='+'<?= (isset($_GET['sub-product'])) ? $_GET['sub-product'] : '' ?>'+
            //                         "<?= (isset($_GET['data_cgc'])) ? '&data_cgc=' . $_GET['data_cgc'] : '' ?>"+
            //                         "<?= (isset($_GET['sub_category'])) ? '&sub_category=' . $_GET['sub_category'] : '' ?>"+
            //                         "<?= (isset($_GET['sub-product'])) ? '&category=' . $_GET['category'] : '' ?>";
            //      },3000);
            // });

            // $('#search_frame').on('keydown', function () {
            //     clearTimeout(typingTimer);
            // });

            $('#btn-filter').click(function() {
                $('#modal-filter').modal('show');
            });

            $('.my-color').click(function() {
                if (arrColors.includes($(this).attr('colorData'))) {
                    arrColors = arrColors.filter(e => e !== $(this).attr('colorData'));
                    $(this).addClass('btn-no-filter').removeClass('btn-' + $(this).attr('colorData'));
                } else {
                    arrColors.push($(this).attr('colorData'));
                    $(this).addClass('btn-' + $(this).attr('colorData')).removeClass('btn-no-filter');
                }
            });
            $('.my-shapes').click(function() {
                if (arrShapes.includes($(this).attr('shapesData'))) {
                    arrShapes = arrShapes.filter(e => e !== $(this).attr('shapesData'));
                    $(this).addClass('btn-no-filter').removeClass('btn-filter-selected');
                } else {
                    arrShapes.push($(this).attr('shapesData'));
                    $(this).addClass('btn-filter-selected').removeClass('btn-no-filter');
                }
            });
            $('.my-collection').click(function() {
                if (arrCollections.includes($(this).attr('collectionsData'))) {
                    arrCollections = arrCollections.filter(e => e !== $(this).attr('collectionsData'));
                    $(this).addClass('btn-no-filter').removeClass('btn-filter-selected');
                } else {
                    arrCollections.push($(this).attr('collectionsData'));
                    $(this).addClass('btn-filter-selected').removeClass('btn-no-filter');
                }
            });
            $('#filter-search-data').click(function() {
                filter = '';
                filter = (arrColors.length > 0 || arrShapes.length > 0 || arrCollections.length > 0) ? '&filter=true' : '';
                window.location = '?page=<?= $_GET['page'] ?>' + filter + '&colors=' + arrColors + '&shapes=' + arrShapes + '&collections=' + arrCollections;
            });

            $('.exit-frame-selection').click(function() {
                e.preventDefault();
                window.location = "?page=select-store";
            });

            $("#bag-button").click(function() {
                window.location.href = "/v2.0/sis/face/v1.0/?page=cart";
            });

        });

        var twoColumn = true;
        $('#toggleLayout').on('click', function() {
            twoColumn = !twoColumn;

            $('.frame-style__slider')
                .slick('slickSetOption', 'swipeToSlide', !twoColumn)
                .slick('slickSetOption', 'touchMove', !twoColumn)
                .slick('slickSetOption', 'swipe', !twoColumn)
                .slick('refresh')

            $('.product-details h4 span').toggleClass('blk')

            $(this).toggleClass('false');
            $('.frame-style').toggleClass('col-6 col-12')
        })

        // $('.frame-style__slider').slick({
        //     dots: false,
        //     arrows: false,
        //     swipeToSlide: false,
        //     touchMove: false,
        //     swipe: false
        // }).on('swipe', function(event, slick, direction) {
        //     var newActive = slick.currentSlide;
        //     var colorPicker = $(this).parents('.frame-style').find('.switch-color li');        

        //     colorPicker.each(function() {
        //         if ($(this).data('index') == newActive) {
        //             $(this).addClass('active').siblings().removeClass('active')
        //             $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));
        //         }
        //     })
        // })

        $(document).on('click', '.switch-color li', function() {
            var slideIndex = $(this).data('index');
            var slider = $(this).parent().parent().siblings('.frame-style').find('.frame-style__slider');
            console.log(slider);
            var curStyle = $(this).data('style-name');
            var curSKU = $(this).data('color-code');

            $(this).addClass('active').siblings().removeClass('active');
            slider.slick('slickGoTo', parseInt(slideIndex));
            $(this).closest('.card').find('.product-details h4 span').text($(this).data("color-name"));
            $(this).closest('.card').find('input[name="studios_product_code"]').val(curSKU);
        });

        $('.image-wrapper').each(function() {
            var image = $(this).data('src');
            var no_image = $(this).data('no-image');
            var elem = $(this);

            checkImage(image).on('error', function() {
                elem.css('background-image', 'url(' + no_image + ')');
            }).on('load', function() {
                elem.css('background-image', 'url(' + image + ')');
            });
        });

        function checkImage(url) {
            return $('<img>').attr('src', url);
        }

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
                '<input type="button" class="btn btn-black" data-dismiss="modal" value="Shop More">' +
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
        $(document).ready(function() {
            $('.frame-list').scroll(lazyload);
            lazyload();
        });
        let timeout = null;
        let firstload = false;
        let toShowCount = 0;

        function lazyload() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                var wt = $(window).scrollTop(); //* top of the window
                var wb = wt + $(window).height(); //* bottom of the window

                $(".hide-lazy").each(function() {
                    var ot = $(this).offset().top; //* top of object (i.e. advertising div)
                    var ob = ot + $(this).height(); //* bottom of object

                    if ((wt <= ob && wb >= ot) || ob <= 0) {
                        $(this).removeClass('hide-lazy').addClass('show-lazy');
                        if (firstload) {
                            appendProduct(toShowCount);
                            toShowCount++;
                        }
                    }
                });
                firstload = true;
            }, 10);

            $('.frame-style__slider').slick({
                dots: false,
                arrows: false,
                swipeToSlide: false,
                touchMove: false,
                swipe: false
            }).on('swipe', function(event, slick, direction) {
                var newActive = slick.currentSlide;
                var colorPicker = $(this).parents('.frame-style').find('.switch-color li');

                colorPicker.each(function() {
                    if ($(this).data('index') == newActive) {
                        $(this).addClass('active').siblings().removeClass('active')
                        $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));
                        $(this).parents('.frame-style').find('.product-details h5 span').text($(this).data("color-price"));
                    }
                })
            })

            $(document).on('click', '.switch-color li', function() {
                var slideIndex = $(this).data('index');
                var slider = $(this).parents('.frame-style').find('.frame-style__slider');
                var curStyle = $(this).data('style-name');
                var curSKU = $(this).data('color-code');

                $(this).addClass('active').siblings().removeClass('active');
                slider.slick('slickGoTo', parseInt(slideIndex))
                $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));
                $(this).parents('.frame-style').find('.product-details h5 span').text($(this).data("color-price"));
                // $('#input-sku-' + curStyle).val(curSKU);
                $(this).parents('.frame-style').find('.form-quick-add-to-bag').find('input').eq(0).val(curSKU);
            })
        }



        // let timeoutProduct = null;
        const appendProduct = (toShowCount) => {
            let arrProd = [];
            arrProd.push(queryProduct[toShowCount]);

            $.post('modules/store/append-products.php', {
                arrProd: arrProd
            }, function(result) {
                // $('.product-show').append(result.show_product);
                $(result.show_product).appendTo(".product-show").hide().fadeIn(2000);
                $(document).find('#slider-product-' + result.item_description).slick({
                    dots: false,
                    arrows: false,
                    swipeToSlide: false,
                    touchMove: false,
                    swipe: false
                }).on('swipe', function(event, slick, direction) {
                    var newActive = slick.currentSlide;
                    var colorPicker = $(this).parents('.frame-style').find('.switch-color li');

                    colorPicker.each(function() {
                        if ($(this).data('index') == newActive) {
                            $(this).addClass('active').siblings().removeClass('active')
                            $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));
                        }
                    })
                })
            }, 'JSON');
        }

        const rebindMoreItemEvents = () => {
            const moreItems = document.querySelectorAll('.more-item');

            // Unbind previous click events and bind a new one
            $(document).find('.more-item').off('click').on('click', function() {
                const parentCard = $(this).closest('.frame-style'); // Find the parent card
                const hiddenColors = parentCard.find('.hidden-colors'); // Find hidden color elements in this card
                const colorShowArrow = "<?= get_url('images/icons') ?>/icon-color-down.png";
                const colorHideArrow = "<?= get_url('images/icons') ?>/icon-color-up.png";

                let isHidden = true;

                hiddenColors.each(function() {
                    if ($(this).hasClass('hidden')) {
                        $(this).removeClass('hidden');
                        isHidden = false;
                    } else {
                        $(this).addClass('hidden');
                        isHidden = true;
                    }
                });

                // Update the icon in the clicked .more-item
                if (isHidden) {
                    $(this).html(`<img id="down-arrow" src="${colorShowArrow}" alt="down" style="height: 20px; width: 20px; border-radius: 50%; background-color: #fff; border: 2px solid black;">`);
                } else {
                    $(this).html(`<img id="up-arrow" src="${colorHideArrow}" alt="up" style="height: 20px; width: 20px; border-radius: 50%; background-color: #fff; border: 2px solid black;">`);
                }
            });
        };

        // Initial call to attach event listeners on page load
        $(document).ready(function() {
            rebindMoreItemEvents();
        });



        //-----------bag icon show when populated
        const bagEmptyURL = " <?= get_url('images/icons') ?>/icon-shopping-bag.png";
        const bagActiveURL = " <?= get_url('images/icons') ?>/icon-shopping-bag-active.png";
        if (arrCart.length == 0) {
            const button = document.getElementById('cart');

            button.disabled = true;
            button.innerHTML = `<img id="bag-icon" src="${bagEmptyURL}" alt="Bag"
                                                                                                        style="margin-left: 3px; margin-right: 9px; width: 18px;">View Bag`;

        } else {
            const button = document.getElementById('cart');
            button.disabled = false;
            const totalCount = arrCart.reduce((total, item) => total + item.count, 0);

            button.innerHTML = `<img id="bag-icon" src="${bagActiveURL}" alt="Bag Active"
                                                                                                        style="margin-left: 3px; margin-right: 9px; width: 18px;">View Bag (${totalCount})`;

        }

        function updateBag() {
            const bagEmptyURL = " <?= get_url('images/icons') ?>/icon-shopping-bag.png";
            const bagActiveURL = " <?= get_url('images/icons') ?>/icon-shopping-bag-active.png";


            if (cartCount == 0) {
                const button = document.getElementById('cart');

                button.disabled = true;
                button.innerHTML = `<img id="bag-icon" src="${bagEmptyURL}" alt="Bag"
                                                style="margin-left: 3px; margin-right: 9px; width: 18px;">View Bag`;

            } else {
                const button = document.getElementById('cart');
                button.disabled = false;
                button.innerHTML = `<img id="bag-icon" src="${bagActiveURL}" alt="Bag Active"
                                                style="margin-left: 3px; margin-right: 9px; width: 18px;">View Bag (${cartCount})`;

            }
        }
    </script>
<?php } ?>