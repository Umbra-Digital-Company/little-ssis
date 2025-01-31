<!-- switch -->
<div class="switch-layout-transactions mt-2" style="position: relative; z-index: 1;">
    <span class="switch-animation <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? 'slide' : '' ?>"></span>
    <div class="transaction-navigation d-flex no-gutters">
        <a href="#dispatch-content" class="col-6 text-center transactions-option">Dispatch</a>
        <a href="#payment-content" class="col-6 text-center transactions-option">For Payment</a>
    </div>
</div>



<div class="transactions-content active" id="dispatch-content">
    <?php include("for-dispatch.php"); ?>
</div>

<div class="transactions-content hide" id="payment-content">
    <?php include("for-payments.php"); ?>
</div>

<script>
    $(document).ready(function() {
        const $dispatchContent = $('#dispatch-content');
        const $paymentContent = $('#payment-content');
        const $transactionsOption = $('.transactions-option');
        const $switchAnimation = $('.switch-animation');

        const activeParam = new URLSearchParams(window.location.search).get('active') || 'dispatch';

        $dispatchContent.hide();
        $paymentContent.hide();
        $transactionsOption.removeClass('active');

        if (activeParam === 'dispatch') {
            $transactionsOption.filter('[href="#dispatch-content"]').addClass('active');
            $dispatchContent.fadeIn();
            $switchAnimation.removeClass('slide');
        } else {
            $transactionsOption.filter('[href="#payment-content"]').addClass('active');
            $paymentContent.fadeIn();
            $switchAnimation.addClass('slide');
        }
    });


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