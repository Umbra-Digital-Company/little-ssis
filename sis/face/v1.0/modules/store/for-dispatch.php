<style>
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
        box-shadow: 0 1px 3px 0 rgba(54, 72, 46, 0.3);
    }

    .search-input-container {
        position: relative;
        width: 100%;
    }

    .search-input {
        background-color: #ffffff;
        height: 48px;
        color: #111827;
        font-size: 1.125rem;
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
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        background-color: #956e46;
        border-radius: 50%;
        border: none;
    }

    .search-button:hover {
        background-color: #956e46;
        cursor: pointer;
    }

    .search-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px #93c5fd;
    }



    .pagination-select {
        color: #342C29;
        border: none;
        background: transparent;
        cursor: pointer;
    }




    th {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        text-align: left;
        font-size: 1rem;
        font-weight: 300;
        color: #919191;
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


    tr {
        border-top: 1px solid #e5e7eb;
    }

    tr:first-child {
        border-top: none;
    }

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





    .status-badge {
        padding: 0.25rem 0.625rem;
        color: #342C29;
        border-radius: 9999px;
        display: inline-block;
        font-size: 0.875rem;
        font-weight: 500;
    }


    .badge-unpaid {
        background-color: #D84E42;
        color: #ffffff;
    }

    .badge-paid {
        background-color: #9DE356;
    }

    .badge-cancelled {
        background-color: #DCDCDC;
    }



    /* Item category badge style */
    .item-category {
        padding: 0.25rem 0.625rem;
        font-size: 14px;
        font-weight: 500;
        color: #ffffff;
        border-radius: 9999px;
        display: inline-block;
    }

    .badge-antirad {
        background-color: #0B5894;
    }

    .badge-sunnies {
        background-color: #46797A;
    }

    .badge-readers {
        background-color: #D26F4B;
    }

    .badge-merch {
        color: #342C29;
        background-color: #CED880;
    }



    .cancel-button {
        padding: 0.5rem 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        border-radius: 9999px;
        border: 2px solid #D84E42;
        color: #D84E42;
        background-color: transparent;
        transition: background-color 0.3s, color 0.3s;
    }

    .cancel-button:hover {
        background-color: #D84E42;
        color: #ffffff;
        cursor: pointer;
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

<?php
include("./modules/includes/grab_dispatch_order.php");
?>

<div class="mx-2 mt-4">
    <!-- Search Bar -->
    <form class="search-form" id="search-form" method="GET" action="">
        <div class="search-input-container">
            <input type="search" id="simple-search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="search-input" placeholder="Search" oninput="toggleClearButton();" />
            <button type="button" class="clearable__clear" onclick="clearSearch();" style="display: none;">
                &times;
            </button>
        </div>

        <button
            type="submit" class="search-button">
            <img style="width: 24px; height: 24px" src="<?= get_url('images/icons') ?>/icon-search.png" alt="Search">
        </button>
    </form>



    <!-- Total Orders and Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <div class="custom-subtitle">
            Total orders <span class="custom-title" style="margin-left: 5px;"> <?= $totalResults ?> </span>
        </div>



        <?php if (!empty($arrCustomer)): ?>
            <select class="pagination-select custom-subtitle" onchange="location = this.value;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <option value="?page=transactions&active=dispatch&dpage=<?= $i ?>" <?= $i == $pagination ? 'selected' : '' ?>>Page <?= $i ?> of <?= $totalPages ?></option>
                <?php endfor; ?>
            </select>
        <?php endif; ?>

    </div>



    <?php if (empty($arrCustomer)): ?>
        <div class="no-orders-message" style="text-align: center; margin-top: 5rem">
            <img src="/sis/face/assets/images/icons/party-popper.svg" class="btn-custom-svg mb-3" style="height: 30px; width: auto" alt="No Pending Orders">
            <h1 style="color: #B7B7B7;">No Pending Orders</h1>
        </div>
    <?php else: ?>

        <div class="table-responsive mb-5">
            <table id="myTable" class="">
                <thead>
                    <tr>
                        <th class="text-nowrap">Customer & Order ID</th>
                        <th>Payment</th>
                        <th>Item</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arrCustomer as $customer): ?>

                        <tr>

                            <td>
                                <span class="custom-title d-block mb-2 underline" style="text-decoration: underline">
                                    <?= htmlspecialchars(ucwords($customer['fullname'])) ?>
                                </span>
                                <span class="custom-sub-subtitle d-block " style="color: #919191">
                                    <?php echo htmlspecialchars($customer['order_id']) ?>
                                </span>
                            </td>

                            <td>
                                <span class="custom-sub-subtitle d-block mb-2" style="color: #919191">
                                    <?= htmlspecialchars((new DateTime($customer['status_date']))->format('d M Y h:i A')); ?>
                                </span>

                                <?php
                                $customerStatus = htmlspecialchars($customer['status']);
                                $badgeClass = 'badge-paid';
                                $displayText = 'Paid';

                                switch ($customerStatus) {
                                    case 'cancelled':
                                        $badgeClass = 'badge-cancelled';
                                        $displayText = 'Cancelled';
                                        break;
                                    case 'for payment':
                                        $badgeClass = 'badge-unpaid';
                                        $displayText = 'Unpaid';
                                        break;
                                    case 'returned':
                                        $badgeClass = 'badge-cancelled';
                                        $displayText = 'Returned';
                                        break;
                                        // You can add more cases as needed
                                }
                                ?>
                                <span class="status-badge <?= $badgeClass; ?>">
                                    <?= $displayText; ?>
                                </span>
                            </td>

                            <td>
                                <span class="custom-sub-subtitle d-block mb-2"><?= htmlspecialchars($customer['item_name']); ?></span>
                                <?php

                                $storeType = htmlspecialchars($customer['store_type']);
                                $badgeClass = 'badge-merch';
                                $displayText = 'Merch';


                                switch ($storeType) {
                                    case 'DCGC0028':
                                        $badgeClass = 'badge-antirad';
                                        $displayText = 'Anti-Rad';
                                        break;
                                    case 'DCGC0003':
                                        $badgeClass = 'badge-sunnies';
                                        $displayText = 'Sunnies Studios';
                                        break;
                                    case 'DCGC0034':
                                        $badgeClass = 'badge-readers';
                                        $displayText = 'Readers';
                                        break;
                                }
                                ?>
                                <span class="item-category <?= $badgeClass; ?>">
                                    <?= $displayText; ?>
                                </span>
                            </td>
                            <td>
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($customer['order_id']) ?>">
                                <input type="hidden" name="po_number" value="<?= htmlspecialchars($customer['po_number']) ?>">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($customer['id']) ?>">
                                <button type="submit" class="cancel-this-order cancel-button">
                                    Cancel order
                                </button>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- <div class="alert alert-warning alert-dismissible fade show text-center border-0 mb-0" role="alert" style="background-color: #9DE356; color: #342C29; font-size: 18px; border-radius: 16px 16px 0 0; margin-top: 6rem;">
            Order has successfully been sent to Cashier
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <svg style="height: 24px; width: 24px" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                    <path fill="none" stroke="#342C29" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div> -->




    <?php endif; ?>




</div>


<script>
    $(document).ready(function() {
        const $searchForm = $('#search-form');
        const $searchInput = $('#simple-search');
        const $clearButton = $('.clearable__clear');
        const baseUrl = window.location.origin + window.location.pathname;

        $searchForm.on('submit', function(e) {
            e.preventDefault();
            const searchTerm = $searchInput.val().trim();
            window.location.href = `${baseUrl}?page=transactions&active=dispatch${searchTerm ? `&search=${encodeURIComponent(searchTerm)}` : ''}`;
        });

        const toggleClearButton = () => $clearButton.toggle($searchInput.val().length > 0);

        window.clearSearch = () => window.location.href = `${baseUrl}?page=transactions&active=dispatch`;

        $searchInput.on('input', toggleClearButton);
        toggleClearButton();


        $('.cancel-this-order').click(function(e) {
            e.preventDefault();

            var order_id = $(this).closest('tr').find('input[name="order_id"]').val();
            var po_number = $(this).closest('tr').find('input[name="po_number"]').val();
            var id = $(this).closest('tr').find('input[name="id"]').val();
            console.log(order_id, po_number, id);

            $.ajax({
                data: {
                    order_id: order_id,
                    po_number: po_number,
                    id: id
                },
                type: "POST",
                url: "/sis/face/v1.0/modules/includes/cancel_order.php",
                success: function(data) {
                    location.reload();
                    // console.log(data);

                }

            });
        });
    });
</script>