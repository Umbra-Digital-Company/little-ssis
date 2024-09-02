<?php if(!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/sis/face/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>    
    </div>

<?php }else{ ?> 

    <link rel="stylesheet" type="text/css" href="/sis/face/v1.0/modules/store/little_sis.css">
    <style>
        .form-row{
            margin-left: 5%;
            margin-right: 5%;
        }

        .frame-style > div {
            border-radius: 0 !important;
        }

        .frame-style-2 > div {
            border-radius: 0 !important;
        }

        .type-header h3 {
            font-family: SharpGroteskSemiBold;
            font-size: 28px;
            margin-bottom: 15px;
        }
        .type-header p {
            font-family: ATSurtMedium;
            font-size: 15px;
            margin-bottom: 20px;
        }
        .type-header a input {
            font-family: ATSurtMedium;
            font-weight: bold;
            width: 200px;
        }
        @media only screen and (max-width: 800px) {

           .form-row{
                margin-left: 0%;
                margin-right: 0%;
            }
        }
        main.customer-layout .wrapper {
            padding-bottom: 0vh !important;
            overflow-y: hidden;
        }
        .page-select-store .packages-list {
            overflow-x: hidden;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="/sis/face/assets/css/color_pickers.css">
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

        .frame-style-2 {
            padding: 0 10px;
        }
        .frame-style-2 .frame-style__slider-2 {
            border-radius: 10px 10px 0 0;
            overflow: hidden;
        }
        .frame-style-2{
            cursor: pointer;
        }
        .frame-style-2 .list-item {
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

        @media only screen and (max-width: 700px) {

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
            .btn{
                padding-top: 13px !important;
            }

        }
        .frame-list {
            height: auto;
            overflow-y: none;
          }
    </style>
    <?php
    include './modules/includes/products/grab_cart.php';
        $arrProductMerge = [];
        $arrProduct = [];
        function trimColor($color_name) {
            // Remove abbreviations and classes
            $color_name =         
                str_replace("/", " ",
                str_replace("blk", "black",
                    str_replace("brown lns", "",
                str_replace("brn", "brown",        
                str_replace("mt", "m",
                str_replace("matte", "m",
                str_replace("flt", "f",
                str_replace("lens", "",
                str_replace("flat", "f",
                str_replace("grn", "",
                str_replace("gdt", "",
                str_replace("/crml", "",
                    trim($color_name)
                ))))))))))));

            return $color_name;

        };

    ?>
        <div class="d-flex align-items-center mb-3">
            <?php if(isset($_GET['categoryAll'])){ ?>
                <a href="./?page=select-store" class="exit-frame-selection">
                    <div class="d-flex align-items-start">
                        <img src="/assets/images/icons/icon-left-arrow.png" alt="back" class="img-fluid" title="back to shopping" style="padding-left: 20px;"><p class="mt-2" style="margin-left: 5px;"></p>
                    </div>
                </a>
            <?php } ?>
                      
            <input type="search" name="search_frame" id="search_frame" class="form-control filled search" placeholder="Search product code / product name" style="margin-left: 20px; margin-right: 20px;" value="<?= (isset($_GET['search']) && $_GET['search'] !='') ? $_GET['search'] : '' ?>">
            <div id="toggleLayout" style="display: none;"></div>
            <div  class="d-flex justify-content-between" id="cart" title="Cart" style="padding: 0;">
                <div class="bag-wrapper">
                    <span><div class="count" count="<?= (isset($order_count)) ? $order_count : '' ?>"></div></span>    
                </div>
            </div>
        </div>
    <div class="packages-list" style="height:66vh; overflow: auto;">
        <div class="form-row mt-1 mt-5 mb-5 section-frames">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>LIPS</h3>
                    <a href="./?page=select-store-all&categoryAll=lips" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/Final_Alond_Bianca.jpg?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'lips')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php 
                       include "./modules/includes/products/grab_select_sun_frames.php";
                       $prodCategory = 'lips';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php

            $arrFramesCount = count($arrProduct);
            $arrProductMerge = $arrProduct;

            // print_r($arrProduct); exit;
        ?>        
        
        <div class="form-row mt-1 mt-5 mb-5 section-face">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>FACE</h3>
                    <a href="./?page=select-store-all&categoryAll=face" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/face.webp?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'face')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_face.php";
                       $prodCategory = 'face';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrFaceCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-brows">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>BROWS</h3>
                    <a href="./?page=select-store-all&categoryAll=brows" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/brows.webp?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'brows')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_brows.php";
                       $prodCategory = 'brows';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrBrowsCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-eyes">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>EYES</h3>
                    <a href="./?page=select-store-all&categoryAll=eyes" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/eyes.webp?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'eyes')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_eyes.php";
                       $prodCategory = 'brows';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrEyesCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-skin">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>SKIN</h3>
                    <a href="./?page=select-store-all&categoryAll=skin" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/skin.webp?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'skin')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_skin.php";
                       $prodCategory = 'skin';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrSkinCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-cheeks">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>CHEEKS</h3>
                    <a href="./?page=select-store-all&categoryAll=cheeks" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/cheeks.jpg?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'cheeks')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_cheeks.php";
                       $prodCategory = 'cheeks';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrCheeksCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-nails">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>NAILS</h3>
                    <a href="./?page=select-store-all&categoryAll=nails" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/nails.jpg?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'nails')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_nails.php";
                       $prodCategory = 'nails';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrNailsCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-sets">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>SETS</h3>
                    <a href="./?page=select-store-all&categoryAll=sets" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                        <img class="img-fluid" src="/sis/face/assets/images/sections/sets.jpg?v=1614047286">
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'sets')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                       include "./modules/includes/products/grab_select_sets.php";
                       $prodCategory = 'sets';
                        include './modules/store/sunnies-select-lips.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php 
            $arrSetsCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);

            // print_r($arrProduct); exit;
        ?>

        <div class="form-row mt-1 mt-5 mb-5 section-merch">
             <?php if(!isset($_GET['categoryAll'])){ ?>
                <div class="col-12 text-center type-header">
                    <h3>MERCH</h3>
                    <a href="./?page=select-store-all&categoryAll=merch" class="select-link"><input type="button" class="btn btn-black" value="Shop All"></a>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <!-- <a href="./?page=select-store-lips" class="select-link" id="lips"> -->
                    <div  class="card" style="height: 254px; padding: 14px; text-align: center; border-radius: 0; background-image: url(/sis/face/assets/images/sections/Travel_Kit_Merch_Category_Banner_-_Desktop.webp?v=1614047286); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    </div>
                    <!-- </a> -->
                </div>
            <?php } ?>
            <?php if(!isset($_GET['categoryAll']) || (isset($_GET['categoryAll']) && $_GET['categoryAll'] == 'merch')){ ?>
                <div class="col-12 <?= (!isset($_GET['categoryAll'])) ? 'col-lg-6' : '' ?> mt-4">
                    <?php
                        include "./modules/includes/products/grab_select_merch.php";
                        $prodCategory = 'merch';
                        include './modules/store/sunnies-select-merch.php';
                    ?>
                </div>
            <?php } ?>
        </div>
        <?php
            $arrMerchCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);
        ?>
    </div>

<style>
    .btn-black {
        background: #000000;
        color: #ffffff;
    }
    .packages-list {
        opacity: 0;
        transition: opacity .3s ease;
    }
    .packages-list.show {
        opacity: 1;
    }
    .count_item .form-control{
        padding: 1px 10px;
        height: 25px;
        border-radius: 0px;
        cursor: pointer;
    }
    .count_item .form-control:hover{
        background-color: #E4DBDB;
    }
    .count_item .form-control:active{
        background-color: #C1C1C1;
    }
    .count_num{
        width: 50px;
        border-left: none;
        border-right: none;
        text-align: center;
    }
    .switch-color li {
        width:14px;
        height:14px;
        border-radius:7px;
        display:block;
        padding: 0;
        margin: 0 3px 5px;
        position: relative;
    }
    .switch-color li::before {
        content: '';
        display: block;
        position: absolute;
        border-color: transparent;
        width: 18px;
        height: 18px;
        display: block;
        top: -2px;
        border-radius:9px;
        left: -2px;
    }
    .switch-color li.active::before {
        border: 1px solid #2a2323;
    }
    #toggleLayout {
        min-width: 25px;
        min-height: 25px;
        margin: 0 10px 0 15px;
        background-image: url(<?= get_url('images') ?>/icons/icon-grid-view.png);
        background-repeat: no-repeat;
        background-size: 25px;
        background-position: center;
    }
    #toggleLayout.false {
        background-image: url(<?= get_url('images') ?>/icons/icon-list-secondary.png);
    }
    .product-details h4 {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        line-height: 12px;
    }
    .product-details h4 span {
        font-size: 10px;
        color: #b3a89b;
    }
    .product-details p {
        font-size: 12px;
        line-height: 12px;
        white-space: nowrap;
        font-weight: 600;
    }
    @media (max-width:480px) {
        .product-details h4 span.blk {
            margin-top: 5px;
            display: block;
        }
    }
</style>
<script>
    let arrProduct = JSON.parse(JSON.stringify(<?= json_encode($arrProductMerge); ?>));
    let arrCart = JSON.parse(JSON.stringify(<?= json_encode($arrCart); ?>));
    let arrColors = <?= json_encode($getColors) ?>;
    let arrShapes = <?= json_encode($getShapes) ?>;
    let arrCollections = <?= json_encode($getCollections) ?>;
    let arrFramesCount = <?= json_encode($arrFramesCount) ?>;
    let arrFaceCount = <?= json_encode($arrFaceCount) ?>;
    let arrBrowsCount = <?= json_encode($arrBrowsCount) ?>;
    let arrEyesCount = <?= json_encode($arrEyesCount) ?>;
    let arrSkinCount = <?= json_encode($arrSkinCount) ?>;
    let arrCheeksCount = <?= json_encode($arrCheeksCount) ?>;
    let arrNailsCount = <?= json_encode($arrNailsCount) ?>;
    let arrSetsCount = <?= json_encode($arrSetsCount) ?>;
    let arrMerchCount = <?= json_encode($arrMerchCount) ?>;
    $(document).ready(function(){

        (arrFramesCount == 0) ? $('.section-frames').hide() : '';
        (arrFaceCount == 0) ? $('.section-face').hide() : '';
        (arrBrowsCount == 0) ? $('.section-brows').hide() : '';

        (arrEyesCount == 0) ? $('.section-eyes').hide() : '';
        (arrSkinCount == 0) ? $('.section-skin').hide() : '';
        (arrCheeksCount == 0) ? $('.section-cheeks').hide() : '';
        (arrNailsCount == 0) ? $('.section-nails').hide() : '';
        (arrSetsCount == 0) ? $('.section-sets').hide() : '';

        (arrMerchCount == 0) ? $('.section-merch').hide() : '';
        $('#search_frame').focus();

        $('.packages-list').addClass('show');

        totalCount();
        $('#filter').on('click', function() {
            $('.ssis-overlay').load("/ssis/modules/store/studios-filter.php", function (d) {
                overlayFilter(d);
            });
        });
        
        $(this).on('click','.product-option',function(){
            let tempProduct = arrProduct.find(x => x.product_code == $(this).attr('product-code'));
            let linkProd = $(this).attr('prod-item-link');
            linkProd = (linkProd != 'frame') ? linkProd : 'store-studios';
            window.location  ="?page=select-"+linkProd+"&product-detail=true&product-code="+tempProduct.product_code+"&desc="+tempProduct.description+"&style="+tempProduct.item_description+"&color="+tempProduct.color+"&price="+tempProduct.price+"&image="+tempProduct.image_url+"&descr="+tempProduct.product_description+"&tags="+tempProduct.tags;
        });

        $("#cart").click(function(){
            let item_cart = '';
            for(let i =0 ; i<arrCart.length; i++){
                if(parseFloat(arrCart[i].price) > 0) {}
                else if(arrCart[i].item_description.toLowerCase().indexOf('paper bag') > -1 || arrCart[i].item_description.toLowerCase().indexOf('sac') > -1 || arrCart[i].item_description.toLowerCase().indexOf('receipt') > -1){
                    continue;
                }
                if(arrCart[i].dispatch_type == 'packaging'){
                    continue;
                }
                total_price = parseFloat(arrCart[i].price) * parseInt(arrCart[i].count);
                merchItem = (arrCart[i].product_code_order.indexOf('M100') > -1 ) ? 'prod-item="merch"' : 'prod-item="frame"';
                merchItem = (arrCart[i].product_upgrade.indexOf('G100') > -1 ) ? 'prod-item="antirad"' : merchItem;

                curStyle = arrCart[i].style;
                curColor = arrCart[i].color.trim();
                // curColor = arrCart[i].color.trim().replace(/ /g, "-");
                // curColor = curColor.replace(/-f/g, "-full");
                // curColor = curColor.replace(/-m/g, "-mirror");
                // curColor = curColor.replace(/-gdt/, "-g");
                // curImageURL = "images/face/"+curStyle+"/"+curColor+"/front.png";
                width = 'width:50%;';
                curImageURL =  arrCart[i].image_url;
                if(curImageURL == null) {

                    curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'+curStyle+'/'+curColor+'/front.png';
                    width = 'width:100px;';

                }
                
                item_cart+='<div class="card cart_view mt-4">'
                    +'<div class="card-body cart-item">'
                        +'<div class="row">'
                            +'<img src="/sis/face/assets/images/icons/icon-delete.png" class="img-responsive remove_item" orders-specs-id="'+arrCart[i].group_orders_specs_id+'" style="cursor: pointer; position: absolute; top: 10px; right: 10px;" width="25" height="25" title="Remove this item">'
                        +'</div>'
                        +'<div class="row mt-4">'
                            +'<div class="col-6" style="text-align:left">'
                                +'<div class="row justify-content-center">'
                                    +'<div style="height: 30vh; '+width+' background-image:url('+curImageURL+'); background-repeat: no-repeat; background-size: 80%; background-position: center;" class="img-responsive cart-item-image"></div>'
                                +'</div>'                                
                            +'</div>'
                            +'<div class="col-6 count_item">'
                                +'<div class="row no-gutters d-flex justify-content-start mt-5 mt-xs-0">'
                                    +'<h2 style="text-transform: uppercase; font-size: 18px;" class="mt-2 product-title">'+curStyle+' <br><span style="font-size: 12px;">'+curColor.replace("-", " ")+'</span></h2>'                                    
                                +'</div>'
                                +'<div class="row no-gutters d-flex justify-content-start mt-3">'
                                    +'<p style="font-size: 12px;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?>'+parseFloat(arrCart[i].price).toFixed(2)+'</p>'
                                +'</div>'
                                +'<div class="row no-gutters d-flex justify-content-start mt-1">'
                                    +'<span><input type="button" class="form-control count_decrement"  price="'+arrCart[i].price+'" group-orders-specs-id="'+arrCart[i].group_orders_specs_id+'" value="-"></span>'
                                    +'<input type="text" class="form-control count_num" value="'+arrCart[i].count+'" readonly>'
                                    +'<span><input type="button" class="form-control count_increment" '+merchItem+' price="'+arrCart[i].price+'" group-orders-specs-id="'+arrCart[i].group_orders_specs_id+'" product-code="'+arrCart[i].product_code+'" value="+"></span>'
                                +'</div>'
                            +'</div>'
                        +'</div>'                        
                    +'</div>'
                +'</div>';
            }
            if(item_cart == ''){
                item_cart +=itemCart();
            }else{
           item_cart    +='<div class="d-flex justify-content-center mt-4" id="btn-sect" style="text-align: center;">'
                            +'<div class="col-6">'
                                +'<input type="button" class="btn btn-primary" data-dismiss="modal" value="Shop More">'
                            +'</div>'
                            +'<div class="col-6">'
                                +'<a href="?page=order-confirmation&bpage='+'<?= $_GET['page'] ?>'+'"><input type="button" class="btn btn-black" value="Proceed"></a>'
                            +'</div>'
                        +'</div>';
            }
            $("#item_cart").html(item_cart);
            $("#modal-item").modal("show");
        });
        // $("#form-add-to-bag").submit(function(e){
        //     e.preventDefault();
        //     $.ajax({
        //         url: "/sis/face/func/process/add_to_bag.php",
        //         type: "POST",
        //         data: $(this).serialize(),
        //         dataType: 'html',
        //         success: function(response){
        //             window.location  ="?page=select-store-studios";
        //         },
        //         error: function(){
        //         }
        //     });//END :: AJAX
        // });
        $(".form-quick-add-to-bag").submit(function(e){
            e.preventDefault();
            process_file = '';
            if($(this).attr('item') != 'frame'){
                process_file = '_'+$(this).attr('item');
            }
            $.ajax({
                url: "/sis/face/func/process/add_to_bag.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: 'html',
                success: function(response){
                    location.reload();
                },
                error: function(){
                }
            });//END :: AJAX
        });
       $(this).on('click', '.remove_item', function(){
           let this_div = $(this);
             let remove = $.post("/sis/face/func/process/remove_item.php",{orders_specs_id: this_div.attr('orders-specs-id')}, function(){
            });
            $.when(remove).done(function(){
                arrCart = arrCart.filter(item => item.group_orders_specs_id !== this_div.attr('orders-specs-id'));
                //console.log(arrCart);
                totalCount();
                this_div.parent().parent().parent().remove();
                if(arrCart.length == 0){
                    $("#btn-sect").html(itemCart());
                }
            });
        });
        $(this).on('click', '.count_decrement ', function(){
            _this = $(this);
            current_value = $(this).parent().parent().find('.count_num').val();
           if(current_value > 0){
                $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                $.post("/sis/face/func/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
                    
                    arrOrdersSpescId.pop();
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    index = arrCart.findIndex(item => item.group_orders_specs_id === _this.attr('group-orders-specs-id'));
                    arrCart[index].group_orders_specs_id = arrOrdersSpescId;
                    arrCart[index].orders_specs_id = arrOrdersSpescId;
                    arrCart[index].count =  arrCart[index].count - 1;
                    _this.attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('span').eq(1).find('.count_increment').attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().parent().parent().parent().find('.count_times').text(arrCart[index].count);
                    t_price = parseFloat(arrCart[index].count) * parseInt(_this.attr('price'));
                    _this.parent().parent().parent().parent().parent().find('.t_price').text(t_price);
                    totalCount();
                });
               
           }
        });
        $(this).on('click', '.count_increment', function(){
            _this = $(this);
            arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

            processItem = '';
            attrProdItem = $(this).attr('prod-item');

            // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
            itemProd = (attrProdItem != 'frame') ? '_'+attrProdItem : '';
            $.post("/sis/face/func/process/add_to_bag"+itemProd+".php",{studios_product_code: _this.attr('product-code')}, function(result){
                //console.log(result);
                arrOrdersSpescId.push(result);
                arrOrdersSpescId = arrOrdersSpescId.join(",");
                index = arrCart.findIndex(item => item.group_orders_specs_id === _this.attr('group-orders-specs-id'));
                arrCart[index].group_orders_specs_id = arrOrdersSpescId;
                arrCart[index].orders_specs_id = arrOrdersSpescId;
                arrCart[index].count =  parseInt(arrCart[index].count) + 1;
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

        $(this).on('click', '.add_count_increment', function(){
            current_value = $(this).parent().parent().find('.count_num').val();
            $(this).parent().parent().find('.count_num').val(parseInt(current_value) + 1);
        });
        $(this).on('click', '.minus_count_decrement', function(){
            current_value = $(this).parent().parent().find('.count_num').val();
            if(current_value > 1){
                $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
            }
        });

        let timeout = null;
        $('#search_frame').on('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(()=>{
                if($(this).val() == ''){
                 window.location = '?page=select-store'; 
                }else{
                    window.location = '?page=select-store&filter=true&search='+$(this).val(); 
                }
             },2000);
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

    $('.switch-color li').on('click', function() {
        var slideIndex = $(this).data('index');
        var slider     = $(this).parents('.frame-style').find('.frame-style__slider');    
        var curStyle   = $(this).data('style-name');
        var curSKU     = $(this).data('color-code');
        
        $(this).addClass('active').siblings().removeClass('active');
        slider.slick('slickGoTo', parseInt(slideIndex))
        $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));   
        // $('#input-sku-' + curStyle).val(curSKU);
        $(this).parents('.frame-style').find('.form-quick-add-to-bag').find('input').eq(0).val(curSKU);
    })

    $('.image-wrapper').each(function() {
        var image = $(this).data('src');
        var no_image = $(this).data('no-image');
        var elem = $(this);

        checkImage(image).on('error', function() {
            elem.css('background-image', 'url('+no_image+')')
        }).on('load', function() {
            elem.css('background-image', 'url('+image+')')
        })
    })

    

    function checkImage(src) {
        return $("<img>").attr('src', src);
    }

    const totalCount = () =>{   
        let value = 0;
        for(i = 0; i < arrCart.length; i++){
            if(arrCart[i].dispatch_type == 'packaging'){
                continue;
            }
            value += ((arrCart[i].item_description.toLowerCase().indexOf('paper bag') == -1 && arrCart[i].item_description.toLowerCase().indexOf('sac') == -1 && arrCart[i].item_description.toLowerCase().indexOf('receipt') == -1) || parseFloat(arrCart[i].price) > 0) ? parseInt(arrCart[i].count) : 0;
        }

        $('.count').text(value);
    }
    const showAvailableFrame = () => {
        if ( $('#search_frame').val() != '' ) {
            var s = $("#search_frame").val().toLowerCase();
            $('.ssis-searching').fadeIn();

            $('.frame-style').each(function () {
                if ( $(this).data('style').match(s.toLowerCase()) ) {
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
    const itemCart  = () =>{
        return '<div class="row mt-4" style="text-align: center;">'
                    +'<div class="col-12">'
                        +'<p style="font-weight: bold; font-size: 20px">Your cart is empty</p>'
                    +'</div>'
                    +'<div class="col-12 mt-4">'
                        +'<input type="button" class="btn btn-black" data-dismiss="modal" value="Shop More">'
                    +'</div>'
                +'</div>';
    }
    const overlayFilter = body => {
        $('.ssis-overlay').fadeIn(200).addClass('show').html(body);
        $('.close-overlay').click(function () {
            if ($(this).data('reload') == 'yes') {
                window.location.reload(true);
            } else {
                $('.ssis-overlay').removeClass('show').fadeOut().html("");
            }

            if ( $(this).data('sidebar') == 'yes' ) {
                toggleSidebar('show');
            }
        });
    }

    $('.select-link').click(function(e){
        e.preventDefault();
        $('#loading').modal('show');
        window.location = $(this).attr('href');
    });
</script>
<?php } ?>  