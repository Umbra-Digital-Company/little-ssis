<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(!isset($_SESSION['customer_id'])) {
?>
<div class="wrapper">
    <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
    <div class="text-center mt-4">
        <a href="/v2.0/sis/face/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
    </div>    
</div>
<?php
}else{
   include "./modules/includes/products/grab_select_sun_frames.php";

?>
<link rel="stylesheet" type="text/css" href="/v2.0/sis/face/assets/css/color_pickers.css">
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
    .panel-group .panel {
        
        border-top: 1px solid #000000;
        border-radius: 0;
        margin-top: 20px;
    }
    .panel-last{
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
    [type="checkbox"]:not(:checked) + label, [type="checkbox"]:checked + label {
        position: relative;
        padding-left: 1em;
        cursor: pointer;
    }
    .radio-active label, .radio .checked, .checkbox .checked, .checkbox-active label {
        font-weight: 600;
    }
    .radio label, .checkbox label {
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
    .frame-style .product-option{
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
        max-width: 100px;
        color: #fff;
        height: 40px;
    }
    #cart{
        padding: 5px 5px 0px 5px;
        vertical-align: center;
        border-radius: 30px;
        cursor: pointer;
    }
    #cart .bag-wrapper {
        background-image: url(/sis/face/assets/images/icons/icon-bag-black.png);
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        height: 28px;
        width: 28px;
    }
    .count{
        /*background-color: #FFD5C6; padding: 0px 7px; border-radius: 15px;*/
        height: 100%;
        text-align: center;
        padding-top: 8px;
        font-size: 12px;
        color:  #fff;        
    }

    .card{
        border-radius: 15px;
    }
    .cart_view .card-body{
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
            -ms-flex-pack: left!important;
            justify-content: left!important;
        }
        main.customer-layout .wrapper {
            overflow-y: none;
        }
    }
    .frame-list {
    height: auto;
    overflow-y: none;
  }
</style>
<div class="packages-list hiding">

        <section class="product-panel" id="product-panel">
                <div class="frame-list">

                  
                    <div class="row align-items-start align-items-stretch" style="margin: 0 -10px;">     

                        <?php for($i = 0; $i < count($arrProductsSorted); $i++) { ?>                       

                            <div class="frame-style col-6 mb-3" data-style="<?= $arrProductsSorted[$i]['item_description'] ?>">
                                <div class="frame-style__slider">

                                <?php                                

                                    // Set current colors array
                                    $curColors = $arrProductsSorted[$i]["colors"];

                                    for($a = 0; $a < sizeOf($curColors); $a++) { 

                                ?> 

                                    <div class="product-option" prod-item-link="frame" data-color-name="<?= $curColors[$a]['color'] ?>" data-color-code="<?= $curColors[$a]['product_code'] ?>" product-code="<?= $curColors[$a]['product_code'] ?>">
                                        <input type="radio" name="frame_style" class="sr-only" >
                                        <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center" style="background-color: #e8e8e4;">

                                        <?php

                                            $curImageURL = $curColors[$a]["image"];
                                            // $curStyle        = $arrProductsSorted[$i]['item_description'];
                                            // $curColor        = str_replace("-g", "-gradient", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($curColors[$a]['color'])))));

                                            // $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';

                                        ?>

                                            <div class="image-wrapper" style="width: 100%; padding-bottom: 75%; background-image: url(<?= $curImageURL ?>); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>

                                            <p style="font-size: 12px; position: absolute; top: 10px; right: 10px;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= $curColors[$a]['price'] ?></p>

                                        </label>
                                    </div>
                                                
                                <?php } ?>

                                </div>
                                <div style="background: #e8e8e4; padding: 15px; border-radius: 0 0 10px 10px;">
                                    <section class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                        <h4><?= $arrProductsSorted[$i]['item_description'] ?>&nbsp;<span class="blk"><?= trimColor($curColors[0]['color']); ?></span></h4>
                                    </section>
                                    <ul class="row switch-color p-0" style="margin: 0 -3px;">

                                        <?php for($a = 0; $a< sizeOf($curColors); $a++) { ?>
                                    
                                            <li class="<?= $a === 0 ? 'active' : '' ?>" data-index="<?= $a ?>" data-style-name="<?= trim($arrProductsSorted[$i]['item_description']) ?>" data-color-name="<?= trimColor($curColors[$a]['color']) ?>" data-color-code="<?= trim($curColors[$a]['product_code']) ?>" style="<?=($curColors[$a]['color_swatch'] != '') ? 'background-color: '.$curColors[$a]['color_swatch'].';' : 'background-color: #000;' ?>"></li>
                                            
                                        <?php } ?>

                                    </ul>    
                                    <div class="row d-flex justify-content-center mt-3">
                                        <form class="col-12 form-quick-add-to-bag" item="frame" id="form-quick-add-to-bag<?= $i ?>" method="POST">
                                            <input type="hidden" name="studios_product_code" id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>" value="<?= trim($curColors[0]['product_code']) ?>">
                                            <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                            <button type="submit" class="btn btn-primary" id="btn-add-<?= strtolower($arrProductsSorted[$i]['item_description']) ?>">add to bag</button>
                                        </form>
                                    </div>                                
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
           
        </section>
</div>
<?php } ?>