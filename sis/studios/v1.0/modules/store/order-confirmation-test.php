<?php include "./modules/includes/grab_product_studios.php"; ?>
<?php 
include "./modules/includes/products/packaging_list.php";
if(!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3"><?= $arrTranslate['Complete step 1 to proceed'] ?></p>
        <div class="text-center mt-4">
            <a href="/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary"><?= $arrTranslate['Go to step 1'] ?></button></a>
        </div>    
    </div>

<?php }elseif(count($arrCart) == 0) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 2 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=select-store-studios"><button class="btn btn-primary">go to step 2</button></a>
        </div>    
    </div>

<?php }else{ ?>

<style>

    .btn {
        border-radius: 10px;
        width: 100%;
        height: 45px;
    }
    .btn-black {
        background: #000000;
        color: #ffffff;
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
    #product-list .card{
        border-radius: 10px;
        cursor: pointer;
    }
    #cart{
        padding: 5px 5px 0px 5px;
        vertical-align: center;
        border-radius: 30px;
        cursor: pointer;
    }
    .count{
        background-color: #FFD5C6; padding: 0px 7px; border-radius: 15px;
    }

    .card{
        border-radius: 15px;
    }

    .order-confirmation {
        background-color: white !Important;
    }

    .order-total {
        width: 100%;
    }
    .order-total * {
        color: #000;
        text-transform: uppercase;
    }
  
}
  
</style>
<div class="packages-list ">
  
    <section class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel" id="product-panel">
        <!-- <a href="./?page=<?= $_GET['bpage']?>" class="exit-frame-selection">
            <div class="d-flex align-items-start mb-3">
                <img src="<?= get_url('images') ?>/icons/icon-left-arrow.png" alt="back" class="img-fluid" title="back to shopping" style="padding-left: 20px;"><p class="mt-2" style="margin-left: 5px;">Back</p>
            </div>
        </a> -->
        <form action="#" method="post">

        <?php

            $merchItem = false;
            $paperBag = false;

            for($i = 0; $i< count($arrCart); $i++) {

                if(strstr(strtolower($arrCart[$i]['item_description']),'paper bag') && !$paperBag){

                    $paperBag = true;

                }

            }

            $total_price = 0;
            $total_count = 0;

            for($i = 0; $i< count($arrCart); $i++) {
                if($arrCart[$i]['price'] > 0){}
                elseif(strstr(strtolower($arrCart[$i]['item_description']),'paper bag') || strstr(strtolower($arrCart[$i]['item_description']),'sac') || strstr(strtolower($arrCart[$i]['item_description']),'receipt')){

                    continue;

                }

                if($arrCart[$i]['dispatch_type'] == 'packaging'){
                    continue;
                }

                if((strstr(strtolower($arrCart[$i]['item_description']),'hardcase') || strstr(strtolower($arrCart[$i]['item_description']),'agenda') ) && !$merchItem){

                    $merchItem = true;

                }

                // $curImageURL = "././images/studios/front.png";
                $curStyle = $arrCart[$i]['style'];
                $curColor = trim($arrCart[$i]['color']);
                // $curColor = str_replace(" ", "-", trim($arrCart[$i]['color']));
                // $curColor = str_replace("-f", "-full", $curColor);
                // $curColor = str_replace("-m", "-mirror", $curColor);
                // $curColor = str_replace("-gdt", "-g", $curColor);
                // $curImageURL = "././images/studios/".$curStyle."/".$curColor."/front.png";
                $curImageURL = $arrCart[$i]['image_url'];
                $width = '';
                // if($curImageURL == null) {

                //     $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';
                //     $width = 'style = "max-width:500px; height: 100%; "';
                // }
                //  $curImageURL = (@getimagesize($curImageURL)) ?  $curImageURL : '/sis/studios/assets/images/defaults/no_specs_frame_available_b.png';
                $total_count += $arrCart[$i]['count'];

        ?>

            <div class="card mt-4">
                <div class="card-body cart-item">
                    <div class="d-flex justify-content-between">
                        <div class="col-6">
                            <div class="justify-content-center">
                                 <div class="image-wrapper" style="height: 100px; width: 100%; background-image: url(<?= $curImageURL ?>); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>                            
                                <input type="hidden" name="orders_specs_id[]"  value="<?= $arrCart[$i]['orders_specs_id'] ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row no-gutters d-flex justify-content-start mt-5 mt-xs-0">
                                <h2 style="text-transform: uppercase; font-size: 18px;" class="mt-2 product-title">
                                    <?= $curStyle ?> 
                                    <br>
                                    <span style="font-size: 12px;"><?= $curColor ?></span>
                                    <br>
                                    <span  class="mt-1" style="color: #000 !Important;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= number_format($arrCart[$i]['price'], 2) ?> X <?= $arrCart[$i]['count'] ?></span>    
                                </h2>                                
                            </div>                            
                            <div class="row no-gutters d-flex justify-content-start mt-3">
                                
                            </div>

                            <?php

                                $total = $arrCart[$i]['count'] * $arrCart[$i]['price'];
                                $total_price += $total;

                            ?>

                            <div class="row no-gutters d-flex justify-content-start mt-3">
                                <p class="mt-1"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= number_format( $total, 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

            <div class="d-flex justify-content-center mt-5">
                <div>
                   <p style="font-size:18px; font-weight: bold;" id='btn-paperbag' data-toggle="collapse" data-target="#paperbag-set-list"><?= $arrTranslate['Packaging'] ?></p>
                </div>
            </div>

            <style>
                .count_item .form-control{
                    padding: 1px 10px;
                    height: 25px;
                    border-radius: 0px;
                    cursor: pointer;
                }
                #btn-paperbag{
                    cursor: pointer;
                }
                .count_item .form-control:hover{
                    background-color: #E4DBDB;
                }
                .count_item .form-control:active{
                    background-color: #C1C1C1;
                }
                .count_num_pbag,.count_num_sac,.count_num_others{
                    width: 50px;
                    border-left: none;
                    border-right: none;
                    text-align: center;
                }

                .add_paper_bag, .add_paper_sac, .add_paper_others{
                    width: 40vh;
                }
                @media only screen and (max-width: 650px) {
                  .add_paper_bag, .add_paper_sac, .add_paper_others{
                    width: 15vh;
                  }
                }


                </style>
                <div id="paperbag-set-list" class="pl-2 pr-2 pt-1" style="">
                    <div class="card mt-2 card-paper-bag" style="background-color: #E8E8E4 !important">

                        <?php
                            $arrPaperBagSelected = [];
                            $arrPaperBag = paperBagList();

                            $arrExistPBag = [];
                             for($i = 0; $i< count($arrPaperBag); $i++) {
                                $selected = '';
                                for($b = 0; $b< count($arrCart); $b++) {
                                    if(trim($arrPaperBag[$i]["product_code"]) == trim($arrCart[$b]["product_upgrade"])){

                                        $arrExistPBag[$arrPaperBag[$i]["product_code"]] = $arrCart[$b]["group_orders_specs_id"];
                          
                                        break;
                                    }
                                }
                            }

                            // echo '<pre>';
                            // print_r($arrExistPBag);
                        ?>
                            <div class="card-body pt-3 pb-1 pl-1">
                                <?php 

                                    if(count($arrExistPBag) == 0){
                                ?>
                                        <div class="product-section d-flex justify-content-between">
                                            <div class="d-flex justify-content-between" style="text-align:left">
                                                <div style="text-align:left;" class="form-group col-12">
                                                    <select class="form-control add_paper_bag" id="add_paper_bag">
                                                        <option value="">-</option>
                                                        <?php for ($i=0; $i < count($arrPaperBag); $i++) { ?>
                                                            <option value="<?= $arrPaperBag[$i]['product_code'] ?>"><?= $arrPaperBag[$i]['item_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <label class="placeholder" style="background-color: #e8e8e4;">Paper Bag</label>
                                                </div>
                                            </div>
                                            
                                            <div style="text-align: right;">
                                                <div class="d-flex justify-content-start mt-2 count_item">
                                                    <span><input type="button" class="form-control minus_count_decrement_pbag" group-orders-specs-id="" value="-"></span>
                                                    <input type="text" class="form-control count_num_pbag" value="0" readonly>
                                                    <span><input type="button" class="form-control add_count_increment_pbag" group-orders-specs-id="" value="+"></span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                <?php    }else{

                                        foreach ($arrExistPBag as $key => $value) {
                                            $groupSelected = explode(',',$value);
                                            $countSelected = count($groupSelected);

                                            for ($i=0; $i < count($arrPaperBag); $i++) { 

                                                if($key == $arrPaperBag[$i]['product_code']){
                                                    $product_name = $arrPaperBag[$i]['item_name'];
                                                    break;
                                                }
                                            }
                                ?>
                                            <div class="product-section d-flex justify-content-between">
                                                <div class="d-flex justify-content-between" style="text-align:left">
                                                    <div style="text-align:left;" class="form-group col-12">
                                                        <select class="form-control add_paper_bag" id="add_paper_bag">
                                                                <option value="<?= $key ?>"><?= $product_name ?></option>
                                                        </select>
                                                        <label class="placeholder"  style="background-color: #e8e8e4;">Paper Bag</label>
                                                    </div>
                                                </div>
                                                
                                                <div style="text-align: right;">
                                                    <div class="d-flex justify-content-start mt-2 count_item">
                                                        <span><input type="button" class="form-control minus_count_decrement_pbag" group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="-"></span>
                                                        <input type="text" class="form-control count_num_pbag" value="<?= $countSelected ?>" readonly>
                                                        <span><input type="button" class="form-control add_count_increment_pbag" group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="+"></span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                <?php   }
                                    }
                                 ?>
                            </div>
                         <div>
                            <input type="button" value="<?= $arrTranslate['Add Additional Paper Bag'] ?>" id="add_section_paper_bag" class="btn btn-primary">
                        </div>
                    </div>

                    <div class="card mt-3 card-sac" style="background-color: #E8E8E4 !important">

                        <?php
                            $arrPaperBagSelected = [];
                            $arrPaperBag = sacList();

                            $arrExistPBag = [];
                             for($i = 0; $i< count($arrPaperBag); $i++) {
                                $selected = '';
                                for($b = 0; $b< count($arrCart); $b++) {
                                    if(trim($arrPaperBag[$i]["product_code"]) == trim($arrCart[$b]["product_upgrade"])){

                                        $arrExistPBag[$arrPaperBag[$i]["product_code"]] = $arrCart[$b]["group_orders_specs_id"];
                          
                                        break;
                                    }
                                }
                            }

                            // echo '<pre>';
                            // print_r($arrExistPBag);
                        ?>
                            <div class="card-body pt-3 pb-1 pl-1">
                                <?php 

                                    if(count($arrExistPBag) == 0){
                                ?>
                                        <div class="product-section d-flex justify-content-between">
                                            <div class="d-flex justify-content-between" style="text-align:left">
                                                <div style="text-align:left;" class="form-group col-12">
                                                    <select class="form-control add_paper_sac" id="add_paper_sac">
                                                        <option value="">-</option>
                                                        <?php for ($i=0; $i < count($arrPaperBag); $i++) { ?>
                                                            <option value="<?= $arrPaperBag[$i]['product_code'] ?>"><?= $arrPaperBag[$i]['item_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <label class="placeholder" style="background-color: #e8e8e4;">Sac</label>
                                                </div>
                                            </div>
                                            
                                            <div style="text-align: right;">
                                                <div class="d-flex justify-content-start mt-2 count_item">
                                                    <span><input type="button" class="form-control minus_count_decrement_sac" group-orders-specs-id="" value="-"></span>
                                                    <input type="text" class="form-control count_num_sac" value="0" readonly>
                                                    <span><input type="button" class="form-control add_count_increment_sac" group-orders-specs-id="" value="+"></span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                <?php    }else{

                                        foreach ($arrExistPBag as $key => $value) {
                                            $groupSelected = explode(',',$value);
                                            $countSelected = count($groupSelected);

                                            for ($i=0; $i < count($arrPaperBag); $i++) { 

                                                if($key == $arrPaperBag[$i]['product_code']){
                                                    $product_name = $arrPaperBag[$i]['item_name'];
                                                    break;
                                                }
                                            }
                                ?>
                                            <div class="product-section d-flex justify-content-between">
                                                <div class="d-flex justify-content-between" style="text-align:left">
                                                    <div style="text-align:left;" class="form-group col-12">
                                                        <select class="form-control add_paper_sac" id="add_paper_sac">
                                                                <option value="<?= $key ?>"><?= $product_name ?></option>
                                                        </select>
                                                        <label class="placeholder"  style="background-color: #e8e8e4;">Sac</label>
                                                    </div>
                                                </div>
                                                
                                                <div style="text-align: right;">
                                                    <div class="d-flex justify-content-start mt-2 count_item">
                                                        <span><input type="button" class="form-control minus_count_decrement_sac" group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="-"></span>
                                                        <input type="text" class="form-control count_num_sac" value="<?= $countSelected ?>" readonly>
                                                        <span><input type="button" class="form-control add_count_increment_sac" group-orders-specs-id="<?= implode(",", $groupSelected) ?>" value="+"></span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                <?php   }
                                    }
                                 ?>
                            </div>
                         <div>
                            <input type="button" value="<?= $arrTranslate['Add Additional Sac'] ?>" id="add_section_sac" class="btn btn-primary">
                        </div>
                    </div>

                    <div class="card mt-3 card-others" style="background-color: #E8E8E4 !important">

                        <?php
                            $arrPaperBagSelected = [];
                            $arrPaperBag = othersList();

                            $arrExistPBag = [];
                            $orders_specs_id_selected = '';
                             for($i = 0; $i< count($arrPaperBag); $i++) {
                                $selected = '';
                                for($b = 0; $b< count($arrCart); $b++) {
                                    if(trim($arrPaperBag[$i]["product_code"]) == trim($arrCart[$b]["product_upgrade"])){

                                        $arrExistPBag[$arrPaperBag[$i]["product_code"]] = $arrCart[$b]["group_orders_specs_id"];
                                        $orders_specs_id_selected = $arrCart[$b]["group_orders_specs_id"];
                                        break;
                                    }
                                }
                            }
                        ?>
                            <div class="card-body pt-3 pb-1 pl-1">
                               
                                <div class="d-flex justify-content-between">
                                    <div class="col mr-4">
                                        <p style="font-size: 12px;"><?= $arrTranslate['Does this order include a Receipt Holder?'] ?></p>
                                    </div>
                                    <div class="d-flex justify-content-between pb-3">
                                        <div class="d-flex align-items-center radio">
                                            <input type="radio" name="receipt_holder" id="receipt_yes" class="sr-only set_receipt_holder checkbox" orders-specs-id ="<?= $orders_specs_id_selected ?>" product-code="<?= $arrPaperBag[0]['product_code'] ?>" value="yes" <?= $orders_specs_id_selected != '' ? 'checked' : '' ?>>
                                            <label for="receipt_yes" class="custom_checkbox"></label>
                                            <label for="receipt_yes"><?= $arrTranslate['Yes'] ?></label>
                                        </div>
                                        <div class="d-flex align-items-center radio ml-3">
                                            <input type="radio" name="receipt_holder" id="receipt_no" class="sr-only set_receipt_holder checkbox" orders-specs-id ="<?= $orders_specs_id_selected ?>"  product-code="<?= $arrPaperBag[0]['product_code'] ?>" value="no">
                                            <label for="receipt_no" class="custom_checkbox doctor"></label>
                                            <label for="receipt_no"><?= $arrTranslate['No'] ?></label>
                                        </div>
                                    </div>
                                </div>
                              
                            </div>
                    </div>
                </div>
                <style>
                    .radio label, .checkbox label {
                        padding-left: 5px !important;
                    }
                </style>

                <p class="text-uppercase text-primary font-bold mb-3 mt-4">Promo Code/Voucher Code : </p>
                <?php if(isset($arrCart[0]['promo_code']) && $arrCart[0]['promo_code']!=''){ ?>
                    <p class="text-uppercase text-primary font-bold mb-3 mt-4"> <?= $arrCart[0]['promo_code'] ?></p>
                <?php }else{ ?> 
                    <input type="button" class="btn btn-info mb-3 mt-4 check-promo-code" id="btn-check-reward" style="border-radius: 10px;" value="CHECK PROMO">
                <?php } ?>


            <div class=" d-flex justify-content-end mt-4" >
                <div class="card order-total">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 text-center">
                                <p><b><?= $arrTranslate['Total'] ?>:</b></p>
                            </div>
                            <div class="col-8 text-center">
                                <p><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= number_format($total_price, 2) ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(isset($_SESSION['guest_customer']) && $_SESSION['guest_customer']) { ?>

            <div class="row d-flex justify-content-center mt-5" >
                <div class="col-12">
                    <p class="mb-2" style="font-size:17px; font-weight: bold; text-align: center;"><?= $arrTranslate['Would you like to sign up?'] ?></p>
                </div>
                <div class="col-12 mt-1">
                    <input type="button" class="btn" style="background-color: #bad2d8; color: #fff;" id="btn-signup" value="<?= $arrTranslate['Sign Up'] ?>">
                </div>
            </div>

            <?php } ?>

            <div class="row d-flex justify-content-center mt-5" >
                <div class="col-6" style="display: none;">
                    <a href="?page=select-merch"><input type="button" class="btn btn-primary" value="Add Merch"></a>
                </div>
                <div class="col-12">

                    <?php $textSend = (trim($_SESSION['store_type']) == 'ns') ? 'Send To Pos' : $arrTranslate['Dispatch Order']; ?>
                    <a href="/sis/studios/func/process/order_payment.php?path_loc=v1.0" id="send-order"><input type="button" class="btn btn-black" value="<?= $textSend ?>"></a>
                </div>
            </div>            
        </form>
    </section>
</div>

<script>
    let total_count = <?= $total_count ?>;
    $(document).ready(function(){
        $(".use_code").hide();
        $('.check-promo-code').click(function(){
            
        
            // var modal = document.getElementById("myModal");
            // var modalData = document.getElementById("modal-data");
            //     console.log(modalData); // Debugging statement
            
                // modal.style.display = "block";
            $("#myVoucher").modal("show");
            $('input[name=email]').val('<?= $arrCart[0]['email_address'] ?>');
            $('#email_address_text').text('<?= $arrCart[0]['email_address'] ?>');
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
                            }

                            $("#check-promo-message").text(response.message);
                            $("#check-promo-message").css("color",color);

                    },
                    error: function(){

                    }

               });//END :: AJAX

        });

        $(this).on('click', '.add_count_increment_pbag', function(){

            let count_num_val = 0;
            $('.count_num_pbag').each(function(){
                count_num_val += parseInt($(this).val());
            });
            productSelected = $(this).parents('.product-section').find('select').val();
            if(productSelected == ''){
                alert('Please select paper bag');
                return false;
            }
            $('#loading').modal('show');
            if(count_num_val >= total_count){
                if(confirm('Your about to exceed the total count of frames.')){
           
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: productSelected, paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num_pbag').val();
                        _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) + 1);

                        _this.parents('.product-section').find('select option').each(function(){
                            if($(this).val() != productSelected){
                                $(this).remove();
                            }
                        });
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
                }else{
                    setTimeout(()=>{
                        $('#loading').modal('hide');
                    },50);
                }
            }else{
                 _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: productSelected,  paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num_pbag').val();
                        _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function(){
                            if($(this).val() != productSelected){
                                $(this).remove();
                            }
                        });
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
            }
        });

        $(this).on('click', '.minus_count_decrement_pbag', function(){
            $('#loading').modal('show');
            _this = $(this);
            current_value = $(this).parent().parent().find('.count_num_pbag').val();
           if(current_value > 0){
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                $.post("/sis/studios/func/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
                    arrOrdersSpescId.pop();
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    _this.attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('span').eq(1).find('.add_count_increment_pbag').attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('.count_num_pbag').val(parseInt(current_value) - 1);
                    setTimeout(()=>{
                        $('#loading').modal('hide');
                    },200);
                });
               
           }
        });

        $(this).on('click','#add_section_paper_bag',function(){
            $.get('/sis/studios/v1.0/modules/includes/products/add_paper_bag_section.php', function(result){
                $('.card-paper-bag .card-body').append(result);
            });
        });

        $(this).on('click', '.add_count_increment_sac', function(){
            
            let count_num_val = 0;
            $('.count_num_sac').each(function(){
                count_num_val += parseInt($(this).val());
            });
            productSelected = $(this).parents('.product-section').find('select').val();
            if(productSelected == ''){
                alert('Please select sac');
                return false;
            }
            $('#loading').modal('show');
            if(count_num_val >= total_count){
                if(confirm('Your about to exceed the total count of frames.')){
           
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: productSelected, paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num_sac').val();
                        _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) + 1);

                        _this.parents('.product-section').find('select option').each(function(){
                            if($(this).val() != productSelected){
                                $(this).remove();
                            }
                        });
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
                }else{
                    setTimeout(()=>{
                        $('#loading').modal('hide');
                    },50);
                }
            }else{
                 _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: productSelected,  paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num_sac').val();
                        _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function(){
                            if($(this).val() != productSelected){
                                $(this).remove();
                            }
                        });
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
            }
        });

        $(this).on('click', '.minus_count_decrement_sac', function(){
            $('#loading').modal('show');
            _this = $(this);
            current_value = $(this).parent().parent().find('.count_num_sac').val();
           if(current_value > 0){
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                $.post("/sis/studios/func/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
                    arrOrdersSpescId.pop();
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    _this.attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('span').eq(1).find('.add_count_increment_sac').attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('.count_num_sac').val(parseInt(current_value) - 1);
                    setTimeout(()=>{
                        $('#loading').modal('hide');
                    },200);
                });
               
           }
        });

        $(this).on('click','#add_section_sac',function(){
            $.get('/sis/studios/v1.0/modules/includes/products/add_sac_section.php', function(result){
                $('.card-sac .card-body').append(result);
            });
        });

        //others

        bool_receipt_holder = ($('.set_receipt_holder').attr('orders-specs-id') == '') ? false : true;

        $(this).on('click','.set_receipt_holder',function(){
            bool_receipt_holder = true;
            if($(this).val() == 'yes'){
                if($(this).attr('orders-specs-id') == ''){
                    $('#loading').modal('show');
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: $(this).attr('product-code'), paper_bag:true}, function(result){
                        $('.set_receipt_holder').attr('orders-specs-id', result);
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
                }
            }else if($(this).val() == 'no'){
                if($(this).attr('orders-specs-id') != ''){
                    $('#loading').modal('show');
                     $.post("/sis/studios/func/process/remove_item.php",{orders_specs_id: $(this).attr('orders-specs-id')}, function(){
                        $('.set_receipt_holder').attr('orders-specs-id', '');
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
                }
            }
        });

        $('#send-order').click(function(e){
            if(!bool_receipt_holder){
                e.preventDefault();
                alert('Select Yes/No Does this order include a Receipt Holder?');
            }
        });


        $(this).on('click', '.add_count_increment_others', function(){
            
            let count_num_val = 0;
            $('.count_num_others').each(function(){
                count_num_val += parseInt($(this).val());
            });
            productSelected = $(this).parents('.product-section').find('select').val();
            if(productSelected == ''){
                alert('Please select others');
                return false;
            }
            $('#loading').modal('show');
            if(count_num_val >= total_count){
                if(confirm('Your about to exceed the total count of frames.')){
           
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: productSelected, paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_others').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num_others').val();
                        _this.parent().parent().find('.count_num_others').val(parseInt(current_value) + 1);

                        _this.parents('.product-section').find('select option').each(function(){
                            if($(this).val() != productSelected){
                                $(this).remove();
                            }
                        });
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
                }else{
                    setTimeout(()=>{
                        $('#loading').modal('hide');
                    },50);
                }
            }else{
                 _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: productSelected,  paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement_others').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num_others').val();
                        _this.parent().parent().find('.count_num_others').val(parseInt(current_value) + 1);
                        _this.parents('.product-section').find('select option').each(function(){
                            if($(this).val() != productSelected){
                                $(this).remove();
                            }
                        });
                        setTimeout(()=>{
                            $('#loading').modal('hide');
                        },200);
                    });
            }
        });

        $(this).on('click', '.minus_count_decrement_others', function(){
            $('#loading').modal('show');
            _this = $(this);
            current_value = $(this).parent().parent().find('.count_num_others').val();
           if(current_value > 0){
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                $.post("/sis/studios/func/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
                    arrOrdersSpescId.pop();
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    _this.attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('span').eq(1).find('.add_count_increment_others').attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('.count_num_others').val(parseInt(current_value) - 1);
                    setTimeout(()=>{
                        $('#loading').modal('hide');
                    },200);
                });
               
           }
        });

        $(this).on('click','#add_section_others',function(){
            $.get('/sis/studios/v1.0/modules/includes/products/add_others_section.php', function(result){
                $('.card-others .card-body').append(result);
            });
        });
        // $(this).on('click', '.minus_count_decrement', function(){
        //     current_value = $(this).parent().parent().find('.count_num').val();
        //     if(current_value > 0){
        //         $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
        //     }
        // });
        $('#btn-signup').click(function(){
            $('#modal-signup').modal('show');
        });

         $('#update_guest_account').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: "/sis/studios/func/process/signup-guest-account.php",
                type: "post",
                data: $(this).serialize() ,
                success: function (response) {
                    alert(response);
                    if(response.indexOf('done') > -1){
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

        $('#mnum').on('blur', function() {
            if (/^[0-9]/.test(this.value)) {
                this.value = this.value.replace(/^0/, "");
                formatNumber(this);
            }
        });
    });
</script>
    

<?php } ?>