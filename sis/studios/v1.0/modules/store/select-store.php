<?php if (!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3"><?= $arrTranslate['Complete step 1 to proceed'] ?></p>
        <div class="text-center mt-4">
            <a href="/sis/studios/v1.0/?page=store-home"><button
                    class="btn btn-primary"><?= $arrTranslate['Go to step 1'] ?></button></a>
        </div>
    </div>

<?php } else { ?>

    <link rel="stylesheet" type="text/css" href="/sis/studios/v1.0/modules/store/little_sis.css">
    <style>
        .form-row {
            margin-left: .5%;
            margin-right: .5%;
        }

        .frame-style>div {
            border-radius: 0 !important;
        }

        .type-header {
            font-size: 18px;
            font-weight: 650;
            color: #342C29;
        }

        .type-header h3 {
            font-family: ATSurtMedium;
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

        main.customer-layout .wrapper {
            padding-bottom: 0vh !important;
            overflow-y: hidden;
        }

        .page-select-store .packages-list {
            overflow-x: hidden;
            scrollbar-width: none;
            -ms-overflow-style: none;
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

        .btn-search {
            width: 48px;
            /* Adjust width and height to make it circular */
            height: 48px;
            background-color: #0b5893;
            /* Button background color */
            border: none;
            /* Remove border */
            border-radius: 100%;
            /* Make it circular */
            cursor: pointer;
            /* Pointer cursor on hover */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px 20px 16px 20px;
          
            /* Remove default padding */
        }

        .search-icon {
            width: 25px;
            /* Adjust image size */
            height: 25px;
            display: block;
            /* Ensure image is block-level */
        }



        .type-header {
            position: relative;
            display: inline-block;
            /* This helps keep the image and button properly aligned */
            text-align: center;
            /* Ensure the content is centered */
        }

        .img-header {
            width: 100%;
            /* Adjust this to fit the image */
            height: 276px;
            object-fit: cover;
            border-radius: 16px;
        }

        .select-link {
            position: absolute;
            padding: 20px;
            width: 100%;
            top: 75%;
            left: 50%;
            transform: translate(-50%, -10%);

        }

        .select-link a {
            display: block;
            /* Makes the anchor take full width */
            width: 100%;
            /* Full width for the anchor */
            text-align: center;

            /* Center align the button inside the anchor */
        }
    </style>

    <div id="select_frame">
        <div class="search-container-store d-flex align-items-center mb-3" >
            <div id="form-search" class="d-flex align-items-center">
                <input type="search" name="search_frame" id="search_frame" class="form-control search"
                    placeholder="Search product code / product name"
                    value="<?= (isset($_GET['search']) && $_GET['search'] != '') ? $_GET['search'] : '' ?>">
                <!-- <input type="search" name="search_frame_filter" id="search_frame_filter" class="form-control search" placeholder="Search Frame Style"  value="<?= (isset($_GET['search']) && $_GET['search'] != '') ? $_GET['search'] : '' ?>"> -->
                <button class="btn-search">
                    <img src="<?= get_url('images/icons') ?>/icon-search.png" alt="Search" class="search-icon">
                </button>
            </div>
        </div>

        <div class="flex-container mb-3">

            <button class="btn btn-bag " id="bag-button" disabled>
                    <img id="bag-icon" src="<?= get_url('images/icons') ?>/icon-shopping-bag.png" alt="Bag"
                        style="margin-left: 3px; margin-right: 9px; height: 24px; width: 24px;"> View Bag
            </button>
        </div>

<!-- 


        <div class="d-flex align-items-center mb-3">
            <div id="toggleLayout" style="display: none;"></div>

            <div class="d-flex justify-content-between" id="cart" title="Cart" style="padding: 0;">
                <div class="bag-wrapper">
                    <span>
                        <div class="count" count="<?= (isset($order_count)) ? $order_count : '' ?>"></div>
                    </span>
                </div>
            </div>
        </div> -->




        <div class="packages-list">
        
            <div class="form-row  mb-5 section-frames">
            <span class="type-header  mb-3">SUN</span>
                <div class="col-12 text-start ">

                    <img class="img-fluid img-header"
                        src="/sis/studios/assets/images/sections/sun-frames.webp?v=1614047286">

                    <div class="select-link">
                        <a href="./?page=select-store-studios">
                            <button class="btn btn-shop-header"> Shop All Sun</button>
                        </a>
                    </div>



                </div>


                <div class=" col-sm-12 mt-4 product-container">
                    <div class="">
                    <?php include './modules/store/sunnies-select-studios.php'; ?>
                    </div>
                    
                </div>
            </div>
            <?php
            $arrFramesCount = count($arrProduct);
            $arrProductMerge = $arrProduct;
            ?>
            <div class="form-row mb-5 section-antirad">
                <span class="type-header mb-3">ANTI-RAD</span>
                <div class="col-12 text-center ">
                    <img class="img-fluid img-header"
                        src="/sis/studios/assets/images/sections/anti-rad-frames.webp?v=1614047286">

                    <div class="select-link">
                        <a href="./?page=select-antirad">
                            <button class="btn btn-shop-header"> Shop All Anti-Rad</button>
                        </a>

                    </div>
                </div>


                <div class="col-12 col-lg-12 mt-4">
                    <?php include './modules/store/sunnies-select-antirad.php'; ?>
                </div>
            </div>
            <?php
            $arrAntiRadCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge, $arrProduct);
            ?>
            <div class="form-row mb-5 section-readers">

                <span class="type-header mb-3">READERS</span>
                <div class="col-12 text-center ">
                    <img class="img-fluid img-header" src="/sis/studios/assets/images/sections/readers.png?v=1614047286">

                    <div class="select-link">
                        <a href="./?page=select-readers">
                            <button class="btn btn-shop-header"> Shop All Readers</button>
                        </a>

                    </div>
                </div>




                <div class="col-12 col-lg-12 mt-4">
                    <?php include './modules/store/sunnies-select-readers.php'; ?>
                </div>
            </div>
            <?php
            $arrReadersCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge, $arrProduct);
            ?>

            <div class="form-row mb-5 section-free-item">


                <span class="type-header mb-3">FREE ITEM</span>
                <div class="col-12 text-center ">
                    <img class="img-fluid img-header" src="/sis/studios/assets/images/sections/merch.webp?v=1614047286">

                    <div class="select-link">
                        <a href="./?page=select-free-item">
                            <button class="btn btn-shop-header"> Shop All Free Item</button>
                        </a>

                    </div>
                </div>


                <div class="col-12 col-lg-12 mt-4">
                    <?php include './modules/store/sunnies-select-free-items.php'; ?>
                </div>
            </div>
            <?php
            $arrFreeItemCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge, $arrProduct);
            ?>

            <div class="form-row mb-5 section-merch">


                <span class="type-header mb-3"> MERCH</span>
                <div class="col-12 text-center ">
                    <img class="img-fluid img-header" src="/sis/studios/assets/images/sections/merch.webp?v=1614047286">

                    <div class="select-link">
                        <a href="./?page=select-merch">
                            <button class="btn btn-shop-header"> Shop All MERCH</button>
                        </a>

                    </div>
                </div>


                <div class="col-12 col-lg-12 mt-4">
                    <?php include './modules/store/sunnies-select-merch.php'; ?>
                </div>
            </div>
            <?php
            $arrMerchCount = count($arrProduct);
            $arrProductMerge = array_merge($arrProductMerge, $arrProduct);
            ?>
        </div>

        <style>
            .btn-black {
                background: #000000;
                color: white;
            }

            .packages-list {
                opacity: 0;


                transition: opacity .3s ease;
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

            .switch-color li {
                width: 20px;
                height: 20px;
                border-radius: 20px;
                display: block;
                padding: 9px;
                top: 2px;
               

                margin: 10px;

                position: relative;
            }

            .switch-color {
                display: flex;
                /* Ensure items are displayed in a row */
                list-style-type: none;
                padding: 0;
                margin: 0;
                overflow: hidden;
                /* Hide overflowing items */
            }

            .switch-color .hidden {
                display: none;
                /* Hide items that are not visible */
            }

            .switch-color .more-item {
                
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: 16px;
                color: #333;
            }


            .switch-color li::before {
                content: '';
                display: block;
                position: absolute;
                border-color: transparent;
                width: 20px;
                height: 20px;
                display: block;
                top: -2px;
                border-radius: 9px;
                left: -2px;
               
            }

            .switch-color li.active::before {
                width: 24px;
                height: 24px;
                padding: 10px;
                top: -2px;
                left: -2px;
                border-radius: 25px;
                border: 2px solid #342C29;
                
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

            /* #toggleLayout.false {
                background-image: url(<?= get_url('images') ?>/icons/icon-list-secondary.png);
            } */


            .item-price {
                font-size: 14px;
                font-weight: 400;
                color: #919191;
            }

          

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

            .product-details p {
                font-size: 12px;
                line-height: 12px;
                white-space: nowrap;
                font-weight: 500;

            }

            @media (max-width:480px) {
                .product-details h4 span.blk {
                    margin-top: 5px;
                    font: 14px;
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
            let arrReadersCount = <?= json_encode($arrReadersCount) ?>;
            let arrFreeItemCount = <?= json_encode($arrFreeItemCount) ?>;
            let arrMerchCount = <?= json_encode($arrMerchCount) ?>;

            

            $(document).ready(function () {


                const bagEmptyURL = " <?= get_url('images/icons') ?>/icon-shopping-bag.png";
                const bagActiveURL = " <?= get_url('images/icons') ?>/icon-shopping-bag-active.png";

               
                if(arrCart.length == 0){
                    const button = document.getElementById('bag-button');
                   
                    button.disabled = true;
                    button.innerHTML = `<img id="bag-icon" src="${bagEmptyURL}" alt="Bag"
                    style="margin-left: 3px; margin-right: 9px; height: 24px; width: 24px;">View Bag`;
                    
                }else{
                    const button = document.getElementById('bag-button');
                    button.disabled = false;
                    button.innerHTML = `<img id="bag-icon" src="${bagActiveURL}" alt="Bag Active"
                    style="margin-left: 3px; margin-right: 9px; height: 24px; width: 28px;">View Bag (${arrCart.length})`;
                    
                }  
               

                 
       
                (arrFramesCount == 0) ? $('.section-frames').hide() : '';
                (arrAntiRadCount == 0) ? $('.section-antirad').hide() : '';
                (arrReadersCount == 0) ? $('.section-readers').hide() : '';
                (arrFreeItemCount == 0) ? $('.section-free-item').hide() : '';
                (arrMerchCount == 0) ? $('.section-merch').hide() : '';
                $('#search_frame').focus();

                $('.packages-list').addClass('show');

                totalCount();
                $('#filter').on('click', function () {
                    $('.ssis-overlay').load("/ssis/modules/store/studios-filter.php", function (d) {
                        overlayFilter(d);
                    });
                });

                $(this).on('click', '.product-option', function () {
                    let tempProduct = arrProduct.find(x => x.product_code == $(this).attr('product-code'));
                    let linkProd = $(this).attr('prod-item-link');
                    linkProd = (linkProd != 'frame') ? linkProd : 'store-studios';
                    window.location = "?page=select-" + linkProd + "&product-detail=true&product-code=" + tempProduct.product_code + "&desc=" + tempProduct.description + "&style=" + tempProduct.item_description + "&color=" + tempProduct.color + "&price=" + tempProduct.price + "&image=" + tempProduct.image_url + "&descr=" + tempProduct.product_description + "&tags=" + tempProduct.tags;
                });

                $("#cart").click(function () {
                    let item_cart = '';
                    for (let i = 0; i < arrCart.length; i++) {
                        if (parseFloat(arrCart[i].price) > 0) { }
                        else if (arrCart[i].item_description.toLowerCase().indexOf('paper bag') > -1 || arrCart[i].item_description.toLowerCase().indexOf('sac') > -1 || arrCart[i].item_description.toLowerCase().indexOf('receipt') > -1) {
                            continue;
                        }
                        if (arrCart[i].dispatch_type == 'packaging') {
                            continue;
                        }
                        total_price = parseFloat(arrCart[i].price) * parseInt(arrCart[i].count);
                        merchItem = (arrCart[i].product_code_order.indexOf('M100') > -1) ? 'prod-item="merch"' : 'prod-item="frame"';
                        merchItem = (arrCart[i].product_upgrade.indexOf('G100') > -1) ? 'prod-item="antirad"' : merchItem;

                        curStyle = arrCart[i].style;
                        curColor = arrCart[i].color.trim();
                        // curColor = arrCart[i].color.trim().replace(/ /g, "-");
                        // curColor = curColor.replace(/-f/g, "-full");
                        // curColor = curColor.replace(/-m/g, "-mirror");
                        // curColor = curColor.replace(/-gdt/, "-g");
                        // curImageURL = "images/studios/"+curStyle+"/"+curColor+"/front.png";
                        width = '';
                        curImageURL = arrCart[i].image_url;
                        if (curImageURL == null) {

                            curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/' + curStyle + '/' + curColor + '/front.png';
                            width = 'width:100px;';

                        }

                        item_cart += '<div class="card cart_view mt-4">'
                            + '<div class="card-body cart-item">'
                            + '<div class="row">'
                            + '<img src="/sis/studios/assets/images/icons/icon-delete.png" class="img-responsive remove_item" orders-specs-id="' + arrCart[i].group_orders_specs_id + '" style="cursor: pointer; position: absolute; top: 10px; right: 10px;" width="25" height="25" title="Remove this item">'
                            + '</div>'
                            + '<div class="row mt-4">'
                            + '<div class="col-6" style="text-align:left">'
                            + '<div class="row justify-content-center">'
                            + '<div style="height: 100px; ' + width + ' background-image:url(' + curImageURL + '); background-repeat: no-repeat; background-size: 80%; background-position: center;" class="img-responsive cart-item-image"></div>'
                            + '</div>'
                            + '</div>'
                            + '<div class="col-6 count_item">'
                            + '<div class="row no-gutters d-flex justify-content-start mt-5 mt-xs-0">'
                            + '<h2 style="text-transform: uppercase; font-size: 18px;" class="mt-2 product-title">' + curStyle + ' <br><span style="font-size: 12px;">' + curColor.replace("-", " ") + '</span></h2>'
                            + '</div>'
                            + '<div class="row no-gutters d-flex justify-content-start mt-3">'
                            + '<p style="font-size: 12px;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>' + parseFloat(arrCart[i].price).toFixed(2) + '</p>'
                            + '</div>'
                            + '<div class="row no-gutters d-flex justify-content-start mt-1">'
                            + '<span><input type="button" class="form-control count_decrement"  price="' + arrCart[i].price + '" group-orders-specs-id="' + arrCart[i].group_orders_specs_id + '" value="-"></span>'
                            + '<input type="text" class="form-control count_num" value="' + arrCart[i].count + '" readonly>'
                            + '<span><input type="button" class="form-control count_increment" ' + merchItem + ' price="' + arrCart[i].price + '" group-orders-specs-id="' + arrCart[i].group_orders_specs_id + '" product-code="' + arrCart[i].product_code + '" value="+"></span>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>';
                    }
                    if (item_cart == '') {
                        item_cart += itemCart();
                    } else {
                        item_cart += '<div class="d-flex justify-content-center mt-4" id="btn-sect" style="text-align: center;">'
                            + '<div class="col-6">'
                            + '<input type="button" class="btn btn-primary" data-dismiss="modal" value="<?= $arrTranslate['Shop More'] ?>">'
                            + '</div>'
                            + '<div class="col-6">'
                            + '<a href="?page=order-confirmation&bpage=' + '<?= $_GET['page'] ?>' + '"><input type="button" class="btn btn-black" value="<?= $arrTranslate['Proceed'] ?>"></a>'
                            + '</div>'
                            + '</div>';
                    }
                    $("#item_cart").html(item_cart);
                    $("#modal-item").modal("show");
                });
                // $("#form-add-to-bag").submit(function(e){
                //     e.preventDefault();
                //     $.ajax({
                //         url: "/sis/studios/func/process/add_to_bag.php",
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
                $(".form-quick-add-to-bag").submit(function (e) {
                    e.preventDefault();
                    process_file = '';
                    if ($(this).attr('item') != 'frame') {
                        process_file = '_' + $(this).attr('item');
                    }
                    $.ajax({
                        url: "/sis/studios/func/process/add_to_bag" + process_file + ".php",
                        type: "POST",
                        data: $(this).serialize(),
                        dataType: 'html',
                        success: function (response) {
                            location.reload();
                        },
                        error: function () {
                        }
                    });//END :: AJAX
                });
                $(this).on('click', '.remove_item', function () {
                    let this_div = $(this);
                    let remove = $.post("/sis/studios/func/process/remove_item.php", { orders_specs_id: this_div.attr('orders-specs-id') }, function () {
                    });
                    $.when(remove).done(function () {
                        arrCart = arrCart.filter(item => item.group_orders_specs_id !== this_div.attr('orders-specs-id'));
                        //console.log(arrCart);
                        totalCount();
                        this_div.parent().parent().parent().remove();
                        if (arrCart.length == 0) {
                            $("#btn-sect").html(itemCart());
                        }
                    });
                });
                $(this).on('click', '.count_decrement ', function () {
                    _this = $(this);
                    current_value = $(this).parent().parent().find('.count_num').val();
                    if (current_value > 0) {
                        $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                        arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                        arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                        $.post("/sis/studios/func/process/remove_item.php", { orders_specs_id: arrOrdersSpescIdRemove }, function () {

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
                $(this).on('click', '.count_increment', function () {
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attrProdItem = $(this).attr('prod-item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    itemProd = (attrProdItem != 'frame') ? '_' + attrProdItem : '';
                    $.post("/sis/studios/func/process/add_to_bag" + itemProd + ".php", { studios_product_code: _this.attr('product-code') }, function (result) {
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

                $(this).on('click', '.add_count_increment', function () {
                    current_value = $(this).parent().parent().find('.count_num').val();
                    $(this).parent().parent().find('.count_num').val(parseInt(current_value) + 1);
                });
                $(this).on('click', '.minus_count_decrement', function () {
                    current_value = $(this).parent().parent().find('.count_num').val();
                    if (current_value > 1) {
                        $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                    }
                });

                let timeout = null;
                $('#search_frame').on('keyup', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        if ($(this).val() == '') {
                            window.location = '?page=select-store';
                        } else {
                            window.location = '?page=select-store&filter=true&search=' + $(this).val();
                        }
                    }, 2000);
                });

            });

            var twoColumn = true;
            $('#toggleLayout').on('click', function () {
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
            }).on('swipe', function (event, slick, direction) {
                var newActive = slick.currentSlide;
                var colorPicker = $(this).parents('.frame-style').find('.switch-color li');

                colorPicker.each(function () {
                    if ($(this).data('index') == newActive) {
                        $(this).addClass('active').siblings().removeClass('active')
                        $(this).parents('.frame-style').find('.product-details h4 span').text($(this).data("color-name"));
                        $(this).parents('.frame-style').find('.product-details h5 span').text($(this).data("color-price"));
                    }
                })
            })

            $('.switch-color li').on('click', function () {
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

            $('.image-wrapper').each(function () {
                var image = $(this).data('src');
                var no_image = $(this).data('no-image');
                var elem = $(this);

                checkImage(image).on('error', function () {
                    elem.css('background-image', 'url(' + no_image + ')')
                }).on('load', function () {
                    elem.css('background-image', 'url(' + image + ')')
                })
            })



            function checkImage(src) {
                return $("<img>").attr('src', src);
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

                    $('.frame-style').each(function () {
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
                return '<div class="row mt-4" style="text-align: center;">'
                    + '<div class="col-12">'
                    + '<p style="font-weight: bold; font-size: 20px">Your cart is empty</p>'
                    + '</div>'
                    + '<div class="col-12 mt-4">'
                    + '<input type="button" class="btn btn-black" data-dismiss="modal" value="<?= $arrTranslate['Shop More'] ?>">'
                    + '</div>'
                    + '</div>';
            }
            const overlayFilter = body => {
                $('.ssis-overlay').fadeIn(200).addClass('show').html(body);
                $('.close-overlay').click(function () {
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

            $('.select-link').click(function (e) {
                e.preventDefault();
                $('#loading').modal('show');
                window.location = $(this).attr('href');
            });
        </script>
    <?php } ?>