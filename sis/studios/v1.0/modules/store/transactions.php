<script>
    $(document).ready(function() {
        if ($('.transaction-navigation').length) {


            $('.transactions-option').on('click', function(e) {
                var target = $(this).attr('href');
                e.preventDefault();


                if (target == '#dispatch-content') {
                    $('.switch-animation').removeClass('slide');
                } else {
                    $('.switch-animation').addClass('slide');
                }

                $(this).addClass('active').siblings().removeClass('active');
                $(target).fadeIn().addClass('active').siblings('.transactions-content').hide().removeClass('active');
            });
        }
    });
</script>


<!-- switch -->
<div class="switch-layout-transactions mt-2" style="position: relative; z-index: 1;">
    <span class="switch-animation <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? 'slide' : '' ?>"></span>
    <div class="transaction-navigation d-flex no-gutters">
        <a href="#dispatch-content" class="col-6 text-center transactions-option <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? '' : 'active' ?>">Dispatch</a>
        <a href="#payment-content" class="col-6 text-center transactions-option <?= (!isset($_SESSION['customer_id']) && !isset($_SESSION['temp_data'])) ? '' : '' ?>">For Payment</a>
    </div>
</div>

<div class="transactions-content active" id="dispatch-content">
    <!-- Dispatch -->
    <?php include("for-dispatch.php"); ?>
    <!-- End Dispatch -->
</div>

<div id="payment-content" class="transactions-content hide">
    <?php include("for-payments.php"); ?>
</div>