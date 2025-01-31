<?php 
   include "./modules/includes/grab_product_studios.php";
?>
<?php 
if(!isset($_SESSION['customer_id'])) {?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=health-declaration-form"><button class="btn btn-primary">go to step 1</button></a>
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
  
}
  
</style>
<div class="packages-list ">
  
    <section class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel" id="product-panel">
    <a href="./?page=<?= $_GET['bpage']?>" class="exit-frame-selection">
            <div class="d-flex align-items-start mb-3">
                <img src="/ssis/assets/images/icons/icon-left-arrow.png" alt="back" class="img-fluid" title="back to shopping" style="padding-left: 20px;"><p class="mt-2" style="margin-left: 5px;">Back</p>
            </div></a>
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
                    for($i = 0; $i< count($arrCart); $i++) {
                        if(strstr(strtolower($arrCart[$i]['item_description']),'paper bag')){
                            continue;
                        }
                        if((strstr(strtolower($arrCart[$i]['item_description']),'hardcase') || strstr(strtolower($arrCart[$i]['item_description']),'agenda') ) && !$merchItem){
                            $merchItem = true;
                        }
                        // $curImageURL = "././images/studios/front.png";
                        $curStyle = $arrCart[$i]['style'];
                        $curColor = str_replace(" ", "-", trim($arrCart[$i]['color']));
                        $curColor = str_replace("-f", "-full", $curColor);
                        $curColor = str_replace("-m", "-mirror", $curColor);
                        $curColor = str_replace("-gdt", "-g", $curColor);
                        $curImageURL = "././images/studios/".$curStyle."/".$curColor."/front.png";

                ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-between" style="text-align:left">
                                    <div style="text-align:left;">
                                        <img src="<?= $curImageURL ?>" class="img-responsive" width="160" height="50" >
                                    
                                        <input type="hidden" name="orders_specs_id[]"  value="<?= $arrCart[$i]['orders_specs_id'] ?>">
                                        <p style="font-size: 12px;" class="mt-2"><?= $arrCart[$i]['item_description'] ?></p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <p><strong>Qty: </strong><?= $arrCart[$i]['count'] ?></p>
                                    <p  class="mt-1">(<strong>₱</strong><?= number_format($arrCart[$i]['price'], 2) ?> x <?= $arrCart[$i]['count'] ?>)</p>
                                    <?php
                                        $total = $arrCart[$i]['count'] * $arrCart[$i]['price'];
                                        $total_price += $total;
                                    ?>
                                     <p class="mt-1"><strong>Total: ₱</strong><?= number_format( $total, 2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <div class=" d-flex justify-content-end mt-2" >
                <div class="card">
                    <div class="card-body">
                        <p><strong>Total Price: ₱</strong><?= number_format($total_price, 2) ?> </p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-2" >
                <div>
                   <p style="font-size:18px; font-weight: bold;">Paper Bag</p>
                </div>
            </div>
            <style>
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
            </style>
            <?php
                for($i = 0; $i< count($arrPaperBag); $i++) {    
            ?>
                <div class="card mt-2">
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
            <div class=" d-flex justify-content-center mt-4" >
                    <div class="col-xs-12">
                        <a href="?page=select-merch"><input type="button" class="btn btn-primary" value="Add Merch"></a>
                    </div>
                    <div class="col-xs-12">
                        <a href="modules/process/order_payment.php"><input type="button" class="btn btn-primary" value="Send To Cashier"></a>
                    </div>
                </div>
            </form>
    </section>
</div>
<script>
    $(document).ready(function(){
        $(this).on('click', '.add_count_increment', function(){
            _this = $(this);
            arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");

            processItem = '';
            attr = $(this).attr('merch_item');

            // For some browsers, `attr` is undefined; for others, `attr` is false. Check for both.
            $.post("modules/process/add_to_bag_merch.php",{studios_product_code: _this.attr('product-code')}, function(result){
                //console.log(result);
                arrOrdersSpescId.push(result);
                arrOrdersSpescId = arrOrdersSpescId.join(",");
                _this.attr('group-orders-specs-id', arrOrdersSpescId);
                _this.parent().parent().find('span').eq(0).find('.minus_count_decrement').attr('group-orders-specs-id', arrOrdersSpescId);
                current_value =  _this.parent().parent().find('.count_num').val();
                _this.parent().parent().find('.count_num').val(parseInt(current_value) + 1);
            });
        });

        $(this).on('click', '.minus_count_decrement ', function(){
            _this = $(this);
            current_value = $(this).parent().parent().find('.count_num').val();
           if(current_value > 0){
                arrOrdersSpescId = $(this).attr('group-orders-specs-id').split(",");
                arrOrdersSpescIdRemove = arrOrdersSpescId[arrOrdersSpescId.length - 1];

                $.post("modules/process/remove_item.php",{orders_specs_id: arrOrdersSpescIdRemove}, function(){
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
    });

</script>
 <?php } ?>