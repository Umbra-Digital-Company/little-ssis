<?php
if (!isset($_SESSION['customer_id'])) {
    ?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/v2.0/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>
    <?php
} else {
    include "./modules/includes/products/grab_select_brows.php";

    ?>
    <style>
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
            border-radius: 16px;

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

            margin: 0;
            height: auto;
        }

        .list-item.frame-grid {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        #btn-filter {
            max-width: 100px;
            color: #fff;
            height: 40px;
        }

        #cart {
            padding: 0px 5px 0px 5px;
            vertical-align: center;
            border-radius: 30px;
            cursor: pointer;
        }

        #cart .bag-wrapper {
            background-image: url(/sis/studios/assets/images/icons/icon-bag-black.png);
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

        .select-store-studios {
            background-color: white !Important;
        }

        #ssis_header {
            background-color: #e8e8e4;
        }

        ul {
            list-style: none;


        }

        ul li {
            line-height: 1;
            padding: 16px 0;


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

            
        }

        .frame-list {
            height: auto;
        }
    </style>
<div class="packages-list hiding">

        <section class="product-panel" id="product-panel">

                <div class="frame-list">
                  
                    <div class="row align-items-start align-items-stretch" style="margin: 0 -10px;">     
                        <?php for($i = 0; $i < count($arrProductsSorted); $i++) { ?>

                            <?php
                                    $curColors = $arrProductsSorted[$i]["colors"];
                                    for($a = 0; $a < sizeOf($curColors); $a++) { 

                                        $categoryItem = '';
                                        $subProduct = '';
                                        $prod = $arrProductsSorted[$i]['item_description'].' '.trim($curColors[$a]['color']);
                                        
                                        switch($prodCategory){
                                            case 'lips':
                                                if(strstr($prod, 'fluffbalm')){
                                                    $categoryItem = 'fluffbalm';
                                                }elseif(strstr($prod, 'fluffmatte')){
                                                    $categoryItem = 'fluffmatte';
                                                }elseif(strstr($prod, 'dip')){
                                                    $categoryItem = 'lip dip';
                                                }elseif(strstr($prod, 'treat')){
                                                    $categoryItem = 'lip treat';
                                                }
                                                $subProduct = $categoryItem;
                                            break;
                                            case 'face':
                                                if(strstr($prod, 'multistick')){
                                                    $categoryItem = 'the multistick';
                                                    $subProduct = 'multistick';
                                                }elseif(strstr($prod, 'powder')){
                                                    $categoryItem = 'the powder';
                                                    $subProduct = 'powder';
                                                }elseif(strstr($prod, '+')){
                                                    $categoryItem = 'the perfector';
                                                    $subProduct = '+';
                                                }
                                            break;
                                            case 'brows':
                                                if(strstr($prod, 'gel')){
                                                    $categoryItem = 'lifebrow grooming gel';
                                                    $subProduct = 'gel';
                                                }elseif(strstr($prod, 'micro marker')){
                                                    $categoryItem = 'lifebrow micromarker';
                                                    $subProduct = 'micro marker';
                                                }elseif(strstr($prod, 'pencil')){
                                                    $categoryItem = 'lifebrow skinny pencil';
                                                    $subProduct = 'pencil';
                                                }elseif(strstr($prod, 'bundle')){
                                                    $categoryItem = 'lifebrow bundle';
                                                    $subProduct = 'bundle';
                                                }
                                            break;
                                            case 'eyes':
                                                if(strstr($prod, 'eye crayon')){
                                                    $categoryItem = 'eyecrayon';
                                                    $subProduct = 'eye crayon';
                                                }elseif(strstr($prod, 'lashlift')){
                                                    $categoryItem = 'lashlift';
                                                    $subProduct = $categoryItem;
                                                }
                                            break;
                                            case 'skin':
                                                if(strstr($prod, 'dream cream')){
                                                    $categoryItem = 'dream cream';
                                                }elseif(strstr($prod, 'sunsafe')){
                                                    $categoryItem = 'sunsafe';
                                                }elseif(strstr($prod, 'face erase')){
                                                    $categoryItem = 'face erase';
                                                }
                                                 $subProduct = $categoryItem;
                                            break;

                                            case 'cheeks':
                                                if(strstr($prod, 'airblush')){
                                                    $categoryItem = 'airblush';
                                                }elseif(strstr($prod, 'face glass')){
                                                    $categoryItem = 'face glass';
                                                }elseif(strstr($prod, 'glowbeam')){
                                                    $categoryItem = 'glowbeam';
                                                }
                                                 $subProduct = $categoryItem;
                                            break;

                                             case 'nails':
                                                if(strstr($prod, 'play')){
                                                    $categoryItem = '';
                                                }
                                                 $subProduct = $categoryItem;
                                            break;
                                             case 'sets':
                                                $categoryItem = '';
                                                 $subProduct = $categoryItem;
                                            break;
                                        }
                                    }
                                        // if($categoryItem == '') continue;

                                ?> 
                            
                                <div class="frame-style col-6 mb-3" data-style="<?= $arrProductsSorted[$i]['item_description'] ?>">
                                    <div class="frame-style__slider">
                                    <?php

                                        // Set current colors array
                                        $curColors = $arrProductsSorted[$i]["colors"];
                                        for ($a = 0; $a < 1; $a++) {
                                            
                                            ?>

                                            <div class="product-option" prod-item-link="brows"
                                                product-code="<?= $curColors[$a]['product_code'] ?>">
                                                <input type="radio" name="frame_style" class="sr-only">
                                                <label
                                                    class="list-item frame-grid d-flex flex-column align-items-center justify-content-center"
                                                    style="background-color: #fff;">

                                                    <?php

                                                    $curImageURL = $curColors[$a]["image"];
                                                    // $curStyle        = $arrProductsSorted[$i]['item_description'];
                                                    // $curColor        = str_replace("-g", "-gradient", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($curColors[$a]['color'])))));

                                                    // $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';

                                                    ?>

                                                    <div class="image-wrapper"
                                                        style="width: 100%; padding-bottom: 75%; border-radius: 8px; background-color: #f1f1f1; background-image: url('<?= $curImageURL ?>'); background-repeat: no-repeat; background-size: 100%; background-position: center  ;">
                                                    </div>

                                                    <!-- <p style="font-size: 12px; position: absolute; top: 10px; right: 10px;">
                                                        <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>
                                                        <?= $curColors[$a]['price'] ?>
                                                    </p> -->

                                                </label>
                                            </div>
                                            
                                        <?php } ?>
                                        
                                     </div>
                                     
                                     <div
                                    style="border-radius: 0 0 16px 16px !important; background-color: #fff; padding: 0 15px 15px 15px;">
                                    <div class="d-flex flex-column justify-content-between align-items-center">
                                        <section
                                            class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                            <h4><?= $arrProductsSorted[$i]['item_description'] ?></h4>
                                            <?php 
                                            ?>
                                        </section>

                                        <section
                                            class="product-details flex-nowrap no-gutters align-items-start justify-content-between">
                                            <h5>
                                                <span class="item-price">P<?= trim($curColors[0]['price']); ?></span>
                                            </h5>
                                        </section>
                                        <button
                                            class="btn btn-product product-option" prod-item-link="brows"
                                            product-code="<?= $curColors[0]['product_code'] ?>">
                                            Shop
                                        </button>
                                    </div>

                                    <!-- <div class="row d-flex justify-content-center mt-3">
                                        <form class="col-12 form-quick-add-to-bag" item="antirad"
                                            id="form-quick-add-to-bag<?= $i ?>" method="POST">
                                            <input type="hidden" name="studios_product_code"
                                                id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>"
                                                value="<?= trim($curColors[0]['product_code']) ?>">
                                            <input type="hidden" class="form-control count_num" name="count_num_value" value="1"
                                                readonly>
                                            <button type="submit" class="btn btn-primary">add to bag</button>
                                        </form>
                                    </div> -->
                                </div>
                            </div>

                        <?php } ?>

                    </div>
                </div>

            </section>

    </div>
<?php } ?>