<?php include "./modules/includes/grab_product_studios.php"; ?>
<?php 

if(!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
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

                if(strstr(strtolower($arrCart[$i]['item_description']),'paper bag')){

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

            <div class=" d-flex justify-content-end mt-4" >
                <div class="card order-total">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 text-center">
                                <p><b>Total:</b></p>
                            </div>
                            <div class="col-8 text-center">
                                <p><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= number_format($total_price, 2) ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-5" style="display: none !important;">
                <div>
                   <p style="font-size:18px; font-weight: bold;" id='btn-paperbag' data-toggle="collapse" data-target="#paperbag-set-list">Add Paper Bag</p>
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
                .count_num{
                    width: 50px;
                    border-left: none;
                    border-right: none;
                    text-align: center;
                    }
                </style>
                <div id="paperbag-set-list" class="collapse pl-2 pr-2 pt-1" style="">
                    <?php for($i = 0; $i< count($arrPaperBag); $i++) { ?>

                    <div class="card mt-2" style="background-color: #E8E8E4 !important">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-between" style="text-align:left">
                                    <div style="text-align:left;">
                                        <!-- <img src="././images/studios/front.png" class="img-responsive" width="160" height="50" > -->
                                        <input type="hidden" value="<?= $arrPaperBag[$i]["item_name"] ?>">
                                        <p style="font-size: 12px;" class="mt-2"><?= $arrPaperBag[$i]["item_name"] ?></p>
                                    </div>
                                </div>

                                <?php

                                    $arrPaperBagSelected = [];

                                    for($b = 0; $b< count($arrCart); $b++) {

                                        if($arrPaperBag[$i]["product_code"] == $arrCart[$b]["product_upgrade"]){

                                            $arrPaperBagSelected = explode(",",$arrCart[$b]["group_orders_specs_id"]);                                
                                            break;

                                        }

                                    }

                                ?>
                                
                                <div style="text-align: right;">
                                    <div class="d-flex justify-content-start mt-2 count_item">
                                        <span><input type="button" class="form-control minus_count_decrement" group-orders-specs-id="<?= implode(",", $arrPaperBagSelected) ?>" product-code="<?= $arrPaperBag[$i]["product_code"] ?>" value="-"></span>
                                        <input type="text" class="form-control count_num" value="<?= count($arrPaperBagSelected) ?>" readonly>
                                        <span><input type="button" class="form-control add_count_increment" group-orders-specs-id="<?= implode(",", $arrPaperBagSelected) ?>" product-code="<?= $arrPaperBag[$i]["product_code"] ?>" value="+"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } ?>
                </div>

            <?php if(isset($_SESSION['guest_customer']) && $_SESSION['guest_customer']) { ?>

            <div class="row d-flex justify-content-center mt-5" >
                <div class="col-12">
                    <p class="mb-2" style="font-size:17px; font-weight: bold; text-align: center;">Would you like to sign up?</p>
                </div>
                <div class="col-12 mt-1">
                    <input type="button" class="btn" style="background-color: #bad2d8; color: #fff;" id="btn-signup" value="Sign up">
                </div>
            </div>

            <?php } ?>

            <div class="row d-flex justify-content-center mt-5" >
                <div class="col-6" style="display: none;">
                    <a href="?page=select-merch"><input type="button" class="btn btn-primary" value="Add Merch"></a>
                </div>
                <div class="col-12">

                    <?php $textSend = (trim($_SESSION['store_type']) == 'ns') ? 'Send To Pos' : 'Dispatch Order'; ?>
                    <a href="/sis/studios/func/process/order_payment.php?path_loc=v1.0"><input type="button" class="btn btn-black" value="<?= $textSend ?>"></a>
                </div>
            </div>            
        </form>
    </section>
</div>
<script>
    let total_count = <?= $total_count ?>;
    $(document).ready(function(){
        $(this).on('click', '.add_count_increment', function(){

            let count_num_val = 0;
            $('.count_num').each(function(){
                count_num_val += parseInt($(this).val());
            });

            if(count_num_val >= total_count){
                if(confirm('Your about to exceed the total count of frames.')){
           
                    _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: _this.attr('product-code'), paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num').val();
                        _this.parent().parent().find('.count_num').val(parseInt(current_value) + 1);
                    });
                }
            }else{
                 _this = $(this);
                    arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

                    processItem = '';
                    attr = $(this).attr('merch_item');

                    // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
                    $.post("/sis/studios/func/process/add_to_bag_merch.php",{studios_product_code: _this.attr('product-code'),  paper_bag:true}, function(result){
                        //console.log(result);
                        arrOrdersSpescId.push(result);
                        arrOrdersSpescId = arrOrdersSpescId.join(",");
                        _this.attr('group-orders-specs-id', arrOrdersSpescId);
                        _this.parent().parent().find('span').eq(0).find('.minus_count_decrement').attr('group-orders-specs-id', arrOrdersSpescId);
                        current_value =  _this.parent().parent().find('.count_num').val();
                        _this.parent().parent().find('.count_num').val(parseInt(current_value) + 1);
                    });
            }
        });

        $(this).on('click', '.minus_count_decrement ', function(){
            _this = $(this);
            current_value = $(this).parent().parent().find('.count_num').val();
           if(current_value > 0){
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                $.post("/sis/studios/func/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
                    arrOrdersSpescId.pop();
                    arrOrdersSpescId = arrOrdersSpescId.join(",");
                    _this.attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('span').eq(1).find('.add_count_increment').attr('group-orders-specs-id', arrOrdersSpescId);
                    _this.parent().parent().find('.count_num').val(parseInt(current_value) - 1);
                });
               
           }
        });
        $(this).on('click', '.minus_count_decrement', function(){
            current_value = $(this).parent().parent().find('.count_num').val();
            if(current_value > 0){
                $(this).parent().parent().find('.count_num').val(parseInt(current_value) - 1);
            }
        });
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