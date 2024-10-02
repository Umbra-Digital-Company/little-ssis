<!-- switch -->
<div class="switch-layout-transactions mt-2" style="position: relative; z-index: 1;">
    <span class="switch-animation <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? '' : 'slide' ?>"></span>
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

<div class="transactions-content hide" id="payment-content">
    <?php include("for-payments.php"); ?>
</div>

<script>
    var activeParam = new URLSearchParams(window.location.search).get('active');
    if (activeParam === 'dispatch') {
        $('.transactions-option[href="#dispatch-content"]').addClass('active');
        $('#dispatch-content').addClass('active').fadeIn();
        $('#payment-content').removeClass('active').hide();
        $('.switch-animation').removeClass('slide'); 
    } else if (activeParam === 'payment') {
        $('.transactions-option[href="#payment-content"]').addClass('active');
        $('#payment-content').addClass('active').fadeIn();
        $('#dispatch-content').removeClass('active').hide();
        $('.switch-animation').addClass('slide'); 
    } else {
        $('.transactions-option[href="#dispatch-content"]').addClass('active');
        $('#dispatch-content').addClass('active').fadeIn();
        $('#payment-content').removeClass('active').hide();
        $('.switch-animation').removeClass('slide');
    }

    $(document).ready(function() {
        if ($('.transaction-navigation').length) {

            $('.transactions-option').on('click', function(e) {
                var target = $(this).attr('href');
                e.preventDefault();

                var url = new URL(window.location.href);
                url.searchParams.delete('active');

                if (target == '#dispatch-content') {
                    $('.switch-animation').removeClass('slide');
                    url.searchParams.set('active', 'dispatch');
                } else {
                    $('.switch-animation').addClass('slide');
                    url.searchParams.set('active', 'payment');
                }

                history.replaceState(null, '', url);

                $(this).addClass('active').siblings().removeClass('active');
                $(target).fadeIn().addClass('active').siblings('.transactions-content').hide().removeClass('active');
            });


        }
    });
</script>