<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(!isset($_SESSION['customer_id'])) {
?>
<div class="wrapper">
    <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
    <div class="text-center mt-4">
        <a href="/v2.0/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
    </div>    
</div>
<?php
}else{
   include "./modules/includes/products/grab_merch.php";
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
<link rel="stylesheet" type="text/css" href="/v2.0/sis/studios/assets/css/color_pickers.css">
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
        overflow-y: hidden;
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
        background-image: url(/v2.0/sis/studios/assets/images/icons/icon-bag-black.png);
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
  
</style>
<div class="packages-list hiding">
    <?php if(isset($_GET['product-detail']) && trim($_GET['product-detail']) != "") { ?>
        <section class="product-view" id="product-panel" style="height:65vh; overflow: auto;">
            <a href="./?page=<?= $_GET['page']?>" class="exit-frame-selection">
                <div class="d-flex align-items-start mb-3">
                    <img src="/ssis/assets/images/icons/icon-left-arrow.png" alt="back" class="img-fluid" title="back to shopping" style="padding-left: 20px;"><p class="mt-2" style="margin-left: 5px;">Back</p>
                </div>
            </a>
            <form href="#" id="form-add-to-bag">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex d-flex-m justify-content-between">
                            <div class="product-top" style="text-align:center;">
                                <input type="hidden" name="studios_product_code" value="<?= $_GET['product-code']?>">
                                <?php
                                
                                    $curStyle = trim($_GET['style']);
                                    $curColor = str_replace("-gdt", "-g", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($_GET['color'])))));                                    
                                    $curImageURL = $_GET["image"];

                                    if($curImageURL == '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png') {

                                        $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';

                                    };
                                    $curImageURL = (@getimagesize($curImageURL)) ?  $curImageURL : '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png';

                                ?>
                                <img src="<?=  $curImageURL ?> " class="img-responsive" style="width: 100%; max-width: 300px;">                                
                                <div class="mt-3">
                                    <h2 style="text-transform: uppercase;"><?= $curStyle ?> <span style="font-size: 14px; color: #b3a89b !important;"><?= trimColor($_GET['color']) ?></span></h2>
                                </div>                                
                            </div>
                           
                            <div class="product-top" style="text-align:center">
                                <div class="d-flex justify-content-start count_item">
                                    <div class="d-flex justify-content-start mt-2">
                                        <span><input type="button" class="form-control minus_count_decrement" value="-"></span>
                                        <input type="text" class="form-control count_num" name="count_num_value" value="1" readonly>
                                        <span><input type="button" class="form-control add_count_increment" value="+"></span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-2">
                                    <p style="font-size: 18px;"><strong><?= ($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150) ? 'VND ' : '₱' ?></strong><?= $_GET['price'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div style="width: 100%;">
                                <!-- <p>Description</p> -->
                                <hr class="spacing">
                                <div class="mt-4">
                                    <p><?=($_GET['descr'] != null) ? $_GET["descr"] : 'No description' ?></p>
                                </div>
                                <div>
                                    <ul class="row tags-list">

                                    <?php 

                                        // Create array of tags
                                        if(isset($_GET['tags'])) {

                                            $arrTags = explode(',', $_GET['tags']); 

                                        }   
                                        else {

                                            $arrTags = array();

                                        };                                  

                                        // Loop through array
                                        for ($i=0; $i < sizeOf($arrTags); $i++) { 

                                            // Set current data
                                            $curTag = $arrTags[$i];
                                            $curTagClass = str_replace("'", "-", str_replace(" ", "-", trim(strtolower(str_replace("desc::", "", $curTag)))));
                                            $curTagEdited = trim(strtolower(str_replace("desc::", "", $curTag)));

                                            // Check if "desc" tag
                                            if(strpos($curTag, 'desc::') !== false) {

                                                echo '<li class="col-6 tag-icon tag-icon__'.$curTagClass.'">'.$curTagEdited.'</li>';

                                            };

                                        };

                                    ?>

                                    </ul>
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
    <?php }else{ ?>
        <section class="product-panel" id="product-panel">
            
                <div class="d-flex align-items-center mb-3">
                    <a href="./?page=select-store" class="exit-frame-selection"><img src="/ssis/assets/images/icons/icon-left-arrow.png" alt="exit" class="img-fluid" style="width: 50px;"></a>
                    <input type="search" name="search_frame" id="search_frame" class="form-control filled search" placeholder="Search" style="margin-left: 20px;" value="<?= (isset($_GET['search']) && $_GET['search'] !='') ? $_GET['search'] : '' ?>">
                    <!-- <div id="btn-filter" class="btn btn-secondary mr-4 ml-4">Filter</div> -->
                    <!-- <div id="toggleLayout"></div> -->
                    <div  class="d-flex justify-content-between ml-4" id="cart" title="Cart" style="padding: 0;">
                        <div class="bag-wrapper">
                            <span><div class="count" count="<?= $order_count ?>"></div></span>    
                        </div>
                    </div>
                </div>
                <div class="frame-list" style="height:62vh; overflow: auto;">     
                    <div class="row align-items-start align-items-stretch" style="margin: 0 -10px;">

                        <?php for($i = 0; $i < sizeOf($arrMerchSorted); $i++) { ?>                       

                            <div class="frame-style col-6 mb-3" data-style="<?= $arrMerchSorted[$i]['item_description'] ?>">
                                <div class="frame-style__slider">

                                <?php                                

                                    // Set current colors array
                                    $curColors = $arrMerchSorted[$i]["colors"];

                                    for($a = 0; $a < sizeOf($curColors); $a++) { 

                                ?> 

                                    <div class="product-option" data-color-name="<?= $curColors[$a]['color'] ?>" data-color-code="<?= $curColors[$a]['product_code'] ?>" product-code="<?= $curColors[$a]['product_code'] ?>">
                                        <input type="radio" name="frame_style" class="sr-only" >
                                        <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center" style="background-color: #e8e8e4;">

                                        <?php

                                            $curImageURL = $curColors[$a]["image"];
                                            // $curStyle        = $arrMerchSorted[$i]['item_description'];
                                            // $curColor        = str_replace("-g", "-gradient", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($curColors[$a]['color'])))));

                                            // $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';

                                        ?>

                                            <div class="image-wrapper" style="width: 100%; padding-bottom: 75%; background-image: url(<?= $curImageURL ?>); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>

                                            <p style="font-size: 12px; position: absolute; top: 10px; right: 10px;">₱<?= $arrMerchSorted[$i]['price'] ?></p>

                                        </label>
                                    </div>
                                                
                                <?php } ?>

                                </div>
                                <div style="background: #e8e8e4; padding: 15px; border-radius: 0 0 10px 10px;">
                                    <section class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                        <h4><?= $arrMerchSorted[$i]['item_description'] ?>&nbsp;<span class="blk"><?= $curColors[0]['color']; ?></span></h4>
                                    </section>
                                    <ul class="row switch-color p-0" style="margin: 0 -3px;">

                                        <?php for($a = 0; $a< sizeOf($curColors); $a++) { ?>
                                    
                                            <li class="<?= $a === 0 ? 'active' : '' ?>" data-index="<?= $a ?>" data-style-name="<?= str_replace(" ", "-", strtolower($arrProductsSorted[$i]['item_description'])) ?>" data-color-name="<?= $curColors[$a]['color'] ?>" data-color-code="<?= $curColors[$a]['product_code'] ?>" style="<?=($curColors[$a]['color_picker'] != '') ? $curColors[$a]['color_picker'] : 'background-color: #000;' ?>"></li>
                                            
                                        <?php } ?>

                                    </ul>    
                                    <div class="row d-flex justify-content-center mt-3">
                                        <form class="col-12 form-quick-add-to-bag" id="form-quick-add-to-bag<?= $i ?>" method="POST">
                                            <input type="hidden" name="studios_product_code" id="input-sku-<?= str_replace(" ", "-", strtolower($arrProductsSorted[$i]['item_description'])) ?>" value="<?= $curColors[0]['product_code'] ?>">
                                            <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                            <button type="submit" class="btn btn-primary">add to bag</button>
                                        </form>
                                    </div>                                
                                </div>
                            </div>

                        <?php } ?>
                        
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
    
    let arrProduct = JSON.parse(JSON.stringify(<?= json_encode($arrProduct); ?>));
    let arrCart = JSON.parse(JSON.stringify(<?= json_encode($arrCart); ?>));
    let arrShapes = <?= json_encode($getShapes) ?>;
    let arrCollections = <?= json_encode($getCollections) ?>;
    $(document).ready(function(){

        $('.packages-list').addClass('show');

        totalCount();
        $('#filter').on('click', function() {
            $('.ssis-overlay').load("/ssis/modules/store/studios-filter.php", function (d) {
                overlayFilter(d);
            });
        });
        
        $(".product-option").click(function(){
            let tempProduct = arrProduct.find(x => x.product_code == $(this).attr('product-code'));
            window.location  ="?page=select-merch&product-detail=true&product-code="+tempProduct.product_code+"&desc="+tempProduct.description+"&style="+tempProduct.item_description+"&color="+tempProduct.color+"&price="+tempProduct.price+"&image="+tempProduct.image_url+"&descr="+tempProduct.main_description+"&tags="+tempProduct.tags;
        });

        $("#cart").click(function(){
            let item_cart = '';
            for(let i =0 ; i<arrCart.length; i++){
                if(arrCart[i].item_description.toLowerCase().indexOf('paper bag') > -1){
                    continue;
                }
                total_price = parseFloat(arrCart[i].price) * parseInt(arrCart[i].count);
                merchItem = (arrCart[i].product_code_order.indexOf('M100') > -1 ) ? 'prod-item="merch"' : 'prod-item="frame"';
                merchItem = (arrCart[i].product_upgrade.indexOf('G100') > -1 ) ? 'prod-item="antirad"' : merchItem;

                curStyle = arrCart[i].style;
                curColor = arrCart[i].color.trim();
                // curColor = curColor.replace(/v2.0/-f/g, "-full");
                // curColor = curColor.replace(/v2.0/-m/g, "-mirror");
                // curColor = curColor.replace(/v2.0/-gdt/, "-g");
                // curImageURL = "images/studios/"+curStyle+"/"+curColor+"/front.png";
                curImageURL =  arrCart[i].image_url;
                width = '';
                if(curImageURL == null) {

                    curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'+curStyle+'/'+curColor+'/front.png';
                    width = 'width:100px;';

                }
                
                item_cart+='<div class="card cart_view mt-4">'
                    +'<div class="card-body cart-item">'
                        +'<div class="row">'
                            +'<img src="/v2.0/sis/studios/assets/images/icons/icon-delete.png" class="img-responsive remove_item" orders-specs-id="'+arrCart[i].group_orders_specs_id+'" style="cursor: pointer; position: absolute; top: 10px; right: 10px;" width="25" height="25" title="Remove this item">'
                        +'</div>'
                        +'<div class="row mt-4">'
                            +'<div class="col-6" style="text-align:left">'
                                +'<div class="row justify-content-center">'
                                    +'<div style="height: 100px; '+width+' background-image:url('+curImageURL+'); background-repeat: no-repeat; background-size: 80%; background-position: center;" class="img-responsive cart-item-image"></div>'
                                +'</div>'                                
                            +'</div>'
                            +'<div class="col-6 count_item">'
                                +'<div class="row no-gutters d-flex justify-content-start mt-5 mt-xs-0">'
                                    +'<h2 style="text-transform: uppercase; font-size: 18px;" class="mt-2 product-title">'+curStyle+' <br><span style="font-size: 12px;">'+curColor.replace("-", " ")+'</span></h2>'                                    
                                +'</div>'
                                +'<div class="row no-gutters d-flex justify-content-start mt-3">'
                                    +'<p style="font-size: 12px;"><?= ($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150) ? 'VND ' : '₱' ?>'+parseFloat(arrCart[i].price).toFixed(2)+'</p>'
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
        $("#form-add-to-bag").submit(function(e){
            e.preventDefault();
            $.ajax({
                url: "/v2.0/sis/studios/func/process/add_to_bag_merch.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: 'html',
                success: function(response){
                    window.location  ="?page=select-merch";
                },
                error: function(){
                }
            });//END :: AJAX
        });
        $(".form-quick-add-to-bag").submit(function(e){
            e.preventDefault();
            $.ajax({
                url: "/v2.0/sis/studios/func/process/add_to_bag_merch.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: 'html',
                success: function(response){
                    window.location  ="?page=select-merch";
                },
                error: function(){
                }
            });//END :: AJAX
        });
       $(this).on('click', '.remove_item', function(){
           let this_div = $(this);
             let remove = $.post("/v2.0/sis/studios/func/process/remove_item.php",{orders_specs_id: this_div.attr('orders-specs-id')}, function(){
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

                $.post("/v2.0/sis/studios/func/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
                    
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
            $.post("/v2.0/sis/studios/func/process/add_to_bag"+itemProd+".php",{studios_product_code: _this.attr('product-code')}, function(result){
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

        var typingTimer;                
		var doneTypingInterval = 500;

		$('#search_frame').on('keyup', function () {
            // clearTimeout(typingTimer);
            // typingTimer = setTimeout(showAvailableFrame, doneTypingInterval);
            filter = '';
            filter = (arrShapes.length > 0 || arrCollections.length > 0 || $(this).val().trim() != '') ? '&filter=true' : '';

            setTimeout(()=>{
                 window.location = '?page=select-merch'+filter+'&shapes='+arrShapes+'&collections='+arrCollections+'&search='+$(this).val(); 
             },3000);
        });

        // $('#search_frame').on('keydown', function () {
        //     clearTimeout(typingTimer);
        // });

        $('#btn-filter').click(function(){
            $('#modal-filter').modal('show');
        });

        $('.my-shapes').click(function(){
            if(arrShapes.includes($(this).attr('shapesData'))){
                arrShapes = arrShapes.filter(e => e !== $(this).attr('shapesData'));
                $(this).css({'border': ""});
            }else{
                arrShapes.push($(this).attr('shapesData'));
                $(this).css({'border': "solid 5px #f98989"});
            }
        });

        $('.my-collection').click(function(){
            if(arrCollections.includes($(this).attr('collectionsData'))){
                arrCollections = arrCollections.filter(e => e !== $(this).attr('collectionsData'));
                $(this).css({'border': ""});
            }else{
                arrCollections.push($(this).attr('collectionsData'));
                $(this).css({'border': "solid 5px #f98989"});
            }
        });

        $('#filter-search-data').click(function(){
            filter = '';
            filter = (arrShapes.length > 0 || arrCollections.length > 0) ? '&filter=true' : '';
            window.location = '?page=select-merch'+filter+'&shapes='+arrShapes+'&collections='+arrCollections;
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
            }
        })
    })

    $('.switch-color li').on('click', function() {
        var slideIndex = $(this).data('index');
        var slider     = $(this).parents('.frame-style').find('.frame-style__slider');    
        var curStyle   = $(this).data('style-name');
        var curSKU     = $(this).data('color-code');
        
        $(this).addClass('active').siblings().removeClass('active');
        slider.slick('slickGoTo', parseInt(slideIndex))
        $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));   
        $('#input-sku-' + curStyle).val(curSKU);
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
            value += (arrCart[i].item_description.toLowerCase().indexOf('paper bag') == -1) ? parseInt(arrCart[i].count) : 0;
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
</script>
<?php } ?>