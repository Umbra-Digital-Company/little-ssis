<?php
include("./modules/includes/grab_dispatch_order.php");
?>

<div class="container">
    <!-- Search Bar -->
    <form class="search-form" id="search-form" method="GET" action="">

        <!-- <input type="search" id="simple-search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="search-input" placeholder="Search" />
        <button class="clearable__clear">&times;</button> -->
        <div class="search-input-container">
            <input type="search" id="simple-search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="search-input" placeholder="Search" />

            <!-- <button class="clearable__clear" style="display: none;">&times;</button> -->

        </div>

        <button
            type="submit" class="search-button">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
        </button>
    </form>



    <!-- Total Orders and Pagination -->
    <div class="order-summary">
        <div class="order-info">
            Total orders: <?= count($arrCustomer) ?>
        </div>
        <div class="order-info">
            Page 1 of 3
        </div>

    </div>

    <?php if (empty($arrCustomer)): ?>
        <div class="no-orders-message">
            <img class="img-fluid header-icon" src="/images/icons/white-dispatch-icon.png" style="filter: grayscale(0) brightness(0.5);" />
            <h1 style="color: #B7B7B7;">No Pending Orders</h1>
        </div>
    <?php else: ?>
        <div style=" display: flex;  flex-direction: column;">
            <div style="overflow-x: auto; ">
                <div style="padding: 0.375rem; min-width: 100%; display: inline-block; vertical-align: middle;">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Customer & Order ID</th>
                                <th>Payment</th>
                                <th>Item</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php #var_dump($arrCustomer); 
                            ?>
                            <?php foreach ($arrCustomer as $customer): ?>
                                <tr>
                                    <td>
                                        <span class="customer-name">
                                            <?= htmlspecialchars($customer['fullname']) ?>
                                        </span>
                                        <span class="order-id">
                                            1000<?php echo htmlspecialchars($customer['order_id']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="order-date">
                                            <?= htmlspecialchars((new DateTime($customer['status_date']))->format('d M Y h:i A')); ?>
                                        </span>

                                        <span class="status-badge <?= htmlspecialchars($customer['status']) === 'cancelled' ? 'badge-cancelled' : (htmlspecialchars($customer['status']) === 'for payment' ? 'badge-payment' : 'badge-confirmation') ?>">
                                            <?= htmlspecialchars($customer['status']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="item-name"> <?= htmlspecialchars($customer['item_name']); ?></span>
                                        <span class="item-category">Sunnies Studios</span>
                                    </td>
                                    <td>
                                        <button type="button" class="cancel-button">
                                            Cancel Order
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>




</div>


<script>
    $(document).ready(function() {


        $('#search-form').on('submit', function(e) {
            e.preventDefault();

            var searchTerm = $('#simple-search').val(); // Get the search term from the input field
            var baseUrl = window.location.origin + window.location.pathname; // Get the base URL (without query params)

            // var newUrl = baseUrl + "?page=transactions&search=" + encodeURIComponent(searchTerm);
            var newUrl = searchTerm ? baseUrl + "?page=transactions&search=" + encodeURIComponent(searchTerm) : baseUrl + "?page=transactions";

            window.location.href = newUrl;

        });


    });
</script>




<style>
    .container {
        max-width: 715px;
        max-height: 100%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 1.5rem;

    }

    .search-form {
        display: flex;
        align-items: center;
        height: 80px;
        margin: 0 auto;
        padding: 0.75rem;
        background-color: #ffffff;
        border-radius: 1rem;
        gap: 0.625rem;
        margin-bottom: 1.5rem;
    }

    .search-input-container {
        position: relative;
        width: 100%;
    }

    .search-input {
        background-color: #ffffff;
        height: 48px;
        color: #111827;
        font-size: 0.875rem;
        display: block;
        width: 100%;
        padding: 0.625rem 0;
        border: none;
        border-bottom: 1px solid #d1d5db;
        outline: none !important;
    }

    .clearable__clear {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        cursor: pointer;
    }


    .search-button {
        width: 48px;
        height: 48px;
        padding: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 500;
        color: #ffffff;
        background-color: #0B5893;
        border-radius: 9999px;
        border: none;
    }

    .search-button:hover {
        background-color: #0B5893;
        cursor: pointer;
    }

    .search-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px #93c5fd;
    }

    .search-button svg {
        width: 1rem;
        height: 1rem;
    }

    .search-button svg path {
        stroke: white;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 2;
    }


    .order-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .order-info {
        font-size: 0.875rem;
        font-weight: 500;
        color: #4b5563;
    }



    .no-orders-message {
        color: #B7B7B7;
        font-size: 0.875rem;
        text-align: center;
    }


    th {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
    }

    tbody {
        border-collapse: separate;
        border-spacing: 0 1rem;
        background-color: #ffffff;
    }


    tbody td {
        padding-right: 1rem;
    }

    td {
        height: 1px;
        white-space: nowrap;
    }


    /* Table row styles */
    tr {
        border-top: 1px solid #e5e7eb;
    }

    tr:first-child {
        border-top: none;
    }

    /* Table cell styles */
    td {
        padding: 1.5rem 1.5rem;
        white-space: nowrap;
    }

    tr:first-child td:first-child {
        border-top-left-radius: 16px;
    }

    tr:first-child td:last-child {
        border-top-right-radius: 16px;
    }

    tr:last-child td:first-child {
        border-bottom-left-radius: 16px;
    }

    tr:last-child td:last-child {
        border-bottom-right-radius: 16px;
    }

    /* Customer name style */
    .customer-name {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: underline;
        margin-bottom: 0.5rem;
    }

    /* Order ID style */
    .order-id {
        display: block;
        font-size: 0.75rem;
        color: #919191;
    }

    /* Date style */
    .order-date {
        font-size: 0.75rem;
        color: #919191;
        display: block;
        margin-bottom: 0.5rem;
    }

    /* Status badge style */
    .status-badge {
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        background-color: #9DE356;
        color: #342C29;
        border-radius: 9999px;
        display: inline-block;
    }

    .badge-payment {
        background-color: #D84E42;
    }

    .badge-confirmation {
        background-color: #9DE356;
    }

    .badge-cancelled {
        background-color: #DCDCDC;
    }

    /* Item name style */
    .item-name {
        font-size: 0.75rem;
        color: #342C29;
        display: block;
        margin-bottom: 0.5rem;
    }

    /* Item category badge style */
    .item-category {
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        background-color: #46797A;
        color: #ffffff;
        border-radius: 9999px;
        display: inline-block;
    }

    .cancel-button {
        padding: 0.5rem 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        border-radius: 9999px;
        border: 2px solid #ef4444;
        color: #dc2626;
        background-color: transparent;
        transition: background-color 0.3s, color 0.3s;
    }

    .cancel-button:hover {
        background-color: #ef4444;
        color: #ffffff;
    }

    .cancel-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px #ef4444;
    }

    .cancel-button:disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>