<?php if(!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/v2.0/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>    
    </div>

<?php }else{ $_SESSION['store_type'] = 'vs';?> 

    <link rel="stylesheet" type="text/css" href="/v2.0/sis/studios/v1.0/modules/store/little_sis.css">
    <style>
        .form-row{
            margin-left: 5%;
            margin-right: 5%;
        }

        .frame-style > div {
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
    <div class="d-flex align-items-center mb-3">
                  
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
            <div class="col-12 text-center type-header">
                <h3>SUN</h3>
                <p>Made with form and function in mind, our UV400 sunglasses are great for daily wear.</p>
                <a href="./?page=select-store-studios" class="select-link"><input type="button" class="btn btn-black" value="Shop All Sun"></a>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <a href="./?page=select-store-studios" class="select-link" id="studios">
                    <img class="img-fluid" src="/v2.0/sis/studios/assets/images/sections/sun-frames.webp?v=1614047286">
                </a>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <?php include './modules/store/sunnies-select-studios-test.php'; ?>
            </div>
        </div>
        <?php 
            $arrFramesCount = count($arrProduct);
            $arrProductMerge = $arrProduct;
        ?>        
        <div class="form-row mb-5 section-antirad">
            <div class="col-12 text-center type-header">
                <h3>ANTI-RAD</h3>
                <p>Blue light protection for daily screen time.</p>
                <a href="./?page=select-antirad" class="select-link"><input type="button" class="btn btn-black" value="Shop All Anti-Rad"></a>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <a href="./?page=select-antirad" class="select-link" id="studios">
                    <img class="img-fluid" src="/v2.0/sis/studios/assets/images/sections/anti-rad-frames.webp?v=1614047286">
                </a>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <?php include './modules/store/sunnies-select-antirad.php'; ?>
            </div>
        </div>
        <?php
            $arrAntiRadCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge,$arrProduct);
        ?>
        <div class="form-row mb-5 section-merch">
             <div class="col-12 text-center type-header">
                <h3>MERCH</h3>
                <p>Care Kits, Clean Lens and Anti-fog Wipes.</p>
                <a href="./?page=select-merch" class="select-link"><input type="button" class="btn btn-black" value="Shop All MERCH"></a>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <a href="./?page=select-merch" id="studios" class="select-link">                    
                    <div  class="card" style="height: 254px; padding: 14px; text-align: center; border-radius: 0; background-image: url(/sis/studios/assets/images/sections/merch.webp?v=1614047286); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    </div>
                </a>
            </div>
            <div class="col-12 col-lg-6 mt-4">
                <?php include './modules/store/sunnies-select-merch.php'; ?>
            </div>
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
    let arrAntiRadCount = <?= json_encode($arrAntiRadCount) ?>;
    let arrMerchCount = <?= json_encode($arrMerchCount) ?>;
    $(document).ready(function(){

        (arrFramesCount == 0) ? $('.section-frames').hide() : '';
        (arrAntiRadCount == 0) ? $('.section-antirad').hide() : '';
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
                if(arrCart[i].item_description.toLowerCase().indexOf('paper bag') > -1 || arrCart[i].item_description.toLowerCase().indexOf('sac') > -1 || arrCart[i].item_description.toLowerCase().indexOf('receipt') > -1){
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
                // curImageURL = "images/studios/"+curStyle+"/"+curColor+"/front.png";
                width = '';
                curImageURL =  arrCart[i].image_url;
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
        //         url: "/v2.0/sis/studios/func/process/add_to_bag.php",
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
                url: "/v2.0/sis/studios/func/process/add_to_bag"+process_file+".php",
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
                $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name")+' '+$(this).data("color-code"));
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
        $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name")+' '+$(this).data("color-code"));   
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
            value += (arrCart[i].item_description.toLowerCase().indexOf('paper bag') == -1 && arrCart[i].item_description.toLowerCase().indexOf('sac') == -1 && arrCart[i].item_description.toLowerCase().indexOf('receipt') == -1) ? parseInt(arrCart[i].count) : 0;
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