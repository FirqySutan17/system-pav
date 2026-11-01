
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formatMoney = function ($value) {
    return number_format((float) $value, 2, ',', '.');
};

$formatDate = function ($value) {
    if (empty($value)) {
        return '-';
    }

    $timestamp = strtotime($value);

    return $timestamp ? date('d/m/Y', $timestamp) : '-';
};

$statusConfig = function ($status) {

    $status = strtoupper(trim((string) $status));

    $config = [
        'OPEN' => [
            'class' => 'status-open',
            'label' => 'OPEN'
        ],
        'PENDING APPROVAL' => [
            'class' => 'status-pending',
            'label' => 'PENDING APPROVAL'
        ],
        'CANCELLED' => [
            'class' => 'status-cancelled',
            'label' => 'CANCELLED'
        ],
        'CLOSED' => [
            'class' => 'status-closed',
            'label' => 'CLOSED'
        ]
    ];

    return $config[$status] ?? [
        'class' => 'status-closed',
        'label' => $status ?: '-'
    ];
};

$totalRecords = count($orders);
?>

<style>
/* =====================================================
   ORDER PAGE
===================================================== */

.order-page {
    color: #243247;
}

/* =====================================================
   PAGE HEADING
===================================================== */

.order-page .page-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin: 90px 0 22px;
}

.order-page .eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #64748b;
    font-size: .76rem;
    font-weight: 700;
    letter-spacing: .09em;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.order-page .page-title {
    margin: 0;
    font-size: 1.55rem;
    font-weight: 750;
    letter-spacing: -.035em;
    color: #172b4d;
}

.order-page .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .92rem;
}

/* =====================================================
   MAIN PANEL
===================================================== */

.order-page .panel {
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22, 42, 75, .055);
    overflow: hidden;
}

/* =====================================================
   FILTER TOOLBAR
===================================================== */

.order-page .panel-toolbar {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: end;
    gap: 16px;
    padding: 20px 22px;
    border-bottom: 1px solid #edf1f7;
    background: linear-gradient(
        180deg,
        #fff 0%,
        #fbfcff 100%
    );
}

/* FILTER GRID */

.order-page .filter-grid {
    grid-column: 1 / -1;

    display: grid;
    grid-template-columns:
        minmax(0, 1.1fr)
        minmax(0, 1.1fr)
        minmax(0, .9fr)
        minmax(0, .9fr)
        minmax(0, .9fr)
        minmax(0, 1.5fr);

    gap: 12px;
    width: 100%;
    min-width: 0;
}

.order-page .filter-field {
    min-width: 0;
    width: 100%;
}

/* FILTER LABEL */

.order-page .filter-label {
    display: block;
    margin: 0 0 6px;
    color: #718096;
    font-size: .73rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

/* FILTER INPUT */

.order-page .form-select,
.order-page .form-control {
    width: 100%;
    min-width: 0;
    min-height: 44px;
    box-sizing: border-box;

    border: 1px solid #dfe6f0;
    border-radius: 10px;
    box-shadow: none;

    color: #334155;
    background-color: #fff;
    font-size: .84rem;
}

.order-page .form-select:focus,
.order-page .form-control:focus {
    border-color: #8aa4ff;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .12);
}

/* SEARCH INPUT */

.order-page .search-wrap {
    position: relative;
    width: 100%;
    min-width: 0;
}

.order-page .search-wrap .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);

    color: #94a3b8;
    pointer-events: none;
}

.order-page .search-wrap .form-control {
    padding-left: 40px;
}

/* =====================================================
   FILTER ACTION BUTTONS
===================================================== */

.order-page .filter-actions {
    grid-column: 1;
    grid-row: 2;

    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;

    min-width: 0;
}

.order-page .btn-filter {
    min-height: 44px;
    border-radius: 10px;
    padding: 0 15px;
    font-weight: 650;
    white-space: nowrap;
}

/* =====================================================
   ADD ORDER BUTTON
===================================================== */

.order-page .btn-add {
    grid-column: 2;
    grid-row: 2;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    justify-self: end;
    align-self: center;

    min-height: 44px;
    padding: 0 17px;

    border: 0;
    border-radius: 10px;

    background: linear-gradient(
        135deg,
        #627dff,
        #4965e8
    );

    color: #fff;
    font-weight: 650;

    box-shadow: 0 5px 12px rgba(79, 103, 232, .2);

    text-decoration: none;
    white-space: nowrap;

    transition: transform .15s, box-shadow .15s;
}

.order-page .btn-add:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(79, 103, 232, .25);
}

/* =====================================================
   TABLE
===================================================== */

.order-page .table-area {
    padding: 10px 12px 0;
}

.order-page .table-responsive {
    width: 100%;
    overflow-x: auto;
}

.order-page #mainTable {
    width: 100%;
    margin: 0;

    border-collapse: separate;
    border-spacing: 0;

    white-space: nowrap;
}

/* TABLE HEADER */

.order-page #mainTable thead th {
    padding: 15px 14px;

    background: #f7f9fc;
    color: #66758b;

    font-size: .73rem;
    text-transform: uppercase;
    letter-spacing: .055em;
    font-weight: 750;

    white-space: nowrap;

    border-top: 0;
    border-bottom: 1px solid #e8edf4;
}

.order-page #mainTable thead th:first-child {
    border-radius: 9px 0 0 0;
}

.order-page #mainTable thead th:last-child {
    border-radius: 0 9px 0 0;
}

/* TABLE BODY */

.order-page #mainTable tbody td {
    padding: 15px 14px;

    border-bottom: 1px solid #edf1f6;

    color: #43536a;
    vertical-align: middle;
    font-size: .86rem;
}

.order-page #mainTable tbody tr {
    transition: background .15s;
}

.order-page #mainTable tbody tr:hover {
    background: #f8faff;
}

.order-page #mainTable tbody tr:last-child td {
    border-bottom: 0;
}

/* =====================================================
   ORDER INFORMATION
===================================================== */

.order-page .order-code {
    font-weight: 750;
    color: #3d58d8;
    white-space: nowrap;
    text-decoration: none;
}

.order-page .order-code:hover {
    color: #263fb7;
    text-decoration: none;
}

.order-page .child-order {
    display: block;
    margin-top: 4px;

    color: #94a3b8;
    font-size: .72rem;
    line-height: 1.4;
}

/* CUSTOMER & SUPPLIER */

.order-page .party-code {
    display: block;
    color: #243247;
    font-weight: 650;
    min-width: 130px;
}

.order-page .party-name {
    display: block;
    margin-top: 4px;

    color: #8491a4;
    font-size: .76rem;

    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* NUMBER */

.order-page .number-cell {
    font-variant-numeric: tabular-nums;
    text-align: right;
    font-weight: 600;
}

.order-page .difference-positive {
    color: #087f5b;
    font-weight: 750;
}

.order-page .difference-negative {
    color: #c03939;
    font-weight: 750;
}

/* =====================================================
   STATUS
===================================================== */

.order-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 6px 10px;
    border-radius: 999px;

    font-size: .7rem;
    line-height: 1;
    font-weight: 750;
    letter-spacing: .025em;
}

.order-page .status-badge:before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

/* OPEN */

.order-page .status-open {
    color: #425bd4;
    background: #edf0ff;
}

.order-page .status-open:before {
    background: #536de9;
}

/* PENDING APPROVAL */

.order-page .status-pending {
    color: #9a5a00;
    background: #fff5df;
}

.order-page .status-pending:before {
    background: #e3a008;
}

/* CANCELLED */

.order-page .status-cancelled {
    color: #c03939;
    background: #fff0f0;
}

.order-page .status-cancelled:before {
    background: #dc3545;
}

/* CLOSED */

.order-page .status-closed {
    color: #64748b;
    background: #eef2f6;
}

.order-page .status-closed:before {
    background: #94a3b8;
}

/* =====================================================
   ACTION
===================================================== */

.order-page .action-cell {
    width: 1%;
    white-space: nowrap;
}

.order-page .action-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
}

.order-page .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    border: 1px solid transparent;
    border-radius: 8px;

    padding: 7px 10px;

    font-size: .76rem;
    font-weight: 650;
    line-height: 1.2;

    text-decoration: none;
    white-space: nowrap;

    transition: all .15s;
}

.order-page .btn-detail {
    color: #4261dc;
    background: #eef2ff;
    border-color: #e0e7ff;
}

.order-page .btn-action:hover {
    filter: brightness(.97);
}

/* =====================================================
   PANEL FOOTER
===================================================== */

.order-page .panel-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 12px;
    flex-wrap: wrap;

    padding: 16px 22px 20px;

    color: #7b8798;
    font-size: .84rem;
}

/* =====================================================
   EMPTY STATE
===================================================== */

.order-page .empty-state {
    padding: 38px 16px !important;
    text-align: center;
    color: #8491a4 !important;
}

.order-page .empty-state i {
    display: block;
    font-size: 30px;
    margin-bottom: 10px;
    color: #c2ccda;
}

/* =====================================================
   SELECT2 FILTER
===================================================== */

.order-page .select2-container {
    width: 100% !important;
    min-width: 0;
}

.order-page .select2-container--default
.select2-selection--single {
    height: 44px !important;
    border: 1px solid #dfe6f0 !important;
    border-radius: 10px !important;
    background-color: #fff !important;
    box-shadow: none !important;
    outline: none;
}

.order-page .select2-container--default
.select2-selection--single
.select2-selection__rendered {
    height: 42px;
    line-height: 42px !important;
    padding-left: 13px !important;
    padding-right: 35px !important;

    color: #334155 !important;
    font-size: .84rem;
}

.order-page .select2-container--default
.select2-selection--single
.select2-selection__placeholder {
    color: #94a3b8 !important;
}

.order-page .select2-container--default
.select2-selection--single
.select2-selection__arrow {
    height: 42px !important;
    right: 8px !important;
}

.order-page .select2-container--default.select2-container--focus
.select2-selection--single,
.order-page .select2-container--default.select2-container--open
.select2-selection--single {
    border-color: #8aa4ff !important;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .12) !important;
}

/* DROPDOWN */

.order-page .select2-dropdown {
    border: 1px solid #dfe6f0 !important;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(22, 42, 75, .10);
    z-index: 9999;
}

.order-page .select2-results__option {
    padding: 9px 13px !important;
    font-size: .84rem;
    color: #334155;
}

.order-page .select2-container--default
.select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #eef2ff !important;
    color: #3d58d8 !important;
}

.order-page .select2-container--default
.select2-results__option--selected {
    background-color: #f1f4ff !important;
    color: #3d58d8 !important;
}

/* SEARCH BOX IN DROPDOWN */

.order-page .select2-search--dropdown {
    padding: 8px !important;
}

.order-page .select2-search--dropdown
.select2-search__field {
    min-height: 38px !important;
    padding: 7px 10px !important;

    border: 1px solid #dfe6f0 !important;
    border-radius: 8px !important;

    outline: none;
    font-size: .84rem;
}

.order-page .select2-search--dropdown
.select2-search__field:focus {
    border-color: #8aa4ff !important;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .10);
}

/* NO RESULTS */

.order-page .select2-results__message {
    padding: 12px !important;
    color: #8491a4;
    font-size: .82rem;
}

/* CLEAR BUTTON */

.order-page .select2-container--default
.select2-selection--single
.select2-selection__clear {
    color: #94a3b8;
    font-size: 16px;
    margin-right: 5px;
}

/* =====================================================
   RESPONSIVE - LAPTOP
===================================================== */

@media (max-width: 1199px) {

    .order-page .filter-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

}

/* =====================================================
   RESPONSIVE - TABLET
===================================================== */

@media (max-width: 991px) {

    .order-page .panel-toolbar {
        grid-template-columns: minmax(0, 1fr) auto;
    }

    .order-page .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

/* =====================================================
   RESPONSIVE - MOBILE
===================================================== */

@media (max-width: 767px) {

    .order-page .page-heading {
        margin-top: 80px;
    }

    .order-page .page-title {
        font-size: 1.3rem;
    }

    .order-page .panel-toolbar {
        grid-template-columns: minmax(0, 1fr);
        padding: 16px;
    }

    .order-page .filter-grid {
        grid-column: 1;
        grid-template-columns: 1fr;
    }

    .order-page .filter-actions {
        grid-column: 1;
        grid-row: auto;
        width: 100%;
    }

    .order-page .filter-actions .btn {
        flex: 1;
    }

    .order-page .btn-add {
        grid-column: 1;
        grid-row: auto;

        justify-self: stretch;
        width: 100%;
    }

    .order-page .table-area {
        padding: 0 8px;
    }

    .order-page .panel-footer {
        padding: 14px 16px;
    }

}

/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 480px) {

    .order-page .filter-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .order-page .filter-actions .btn {
        width: 100%;
    }

    .order-page .panel-footer {
        flex-direction: column;
        align-items: flex-start;
    }

}
</style>


<div class="container-fluid order-page px-2 px-lg-3 py-2">

    <!-- =================================================
         PAGE HEADING
    ================================================== -->

    <div class="page-heading">

        <div>

            <div class="eyebrow">
                <i class="ti ti-clipboard-list"></i>
                Inventory
            </div>

            <h1 class="page-title">
                Order Management
            </h1>

            <p class="page-subtitle">
                Kelola Master Order, Customer Order, Supplier Order,
                dan monitoring nilai transaksi.
            </p>

        </div>

    </div>


    <!-- =================================================
         FLASH MESSAGE
    ================================================== -->

    <?php if ($this->session->flashdata('order_success')): ?>

        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti ti-circle-check me-1"></i>

            <?= html_escape($this->session->flashdata('order_success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('order_error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-1"></i>

            <?= html_escape($this->session->flashdata('order_error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>


    <!-- =================================================
         MAIN PANEL
    ================================================== -->

    <section class="panel">

        <!-- FILTER TOOLBAR -->

        <form method="GET" action="<?= base_url('order'); ?>">

            <div class="panel-toolbar">

                <div class="filter-grid">

                    <!-- CUSTOMER -->

                    <div class="filter-field">

                        <label class="filter-label" for="filterCustomer">
                            Customer
                        </label>

                        <select
                            id="filterCustomer"
                            name="cust"
                            class="form-select select2-filter"
                        >
                            <option value="">Semua Customer</option>

                            <?php if (isset($customers) && is_array($customers)): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <option
                                        value="<?= html_escape($customer['CUST']); ?>"
                                        <?= $filters['cust'] === $customer['CUST'] ? 'selected' : ''; ?>
                                    >
                                        <?= html_escape($customer['CUST'] . ' - ' . $customer['FULL_NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                    </div>


                    <!-- SUPPLIER -->

                    <div class="filter-field">

                        <label class="filter-label" for="filterSupplier">
                            Supplier
                        </label>

                        <select
                            id="filterSupplier"
                            name="supplier"
                            class="form-select select2-filter"
                        >
                            <option value="">Semua Supplier</option>

                            <?php if (isset($suppliers) && is_array($suppliers)): ?>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option
                                        value="<?= html_escape($supplier['CUST']); ?>"
                                        <?= $filters['supplier'] === $supplier['CUST'] ? 'selected' : ''; ?>
                                    >
                                        <?= html_escape($supplier['CUST'] . ' - ' . $supplier['FULL_NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                    </div>


                    <!-- DATE FROM -->

                    <div class="filter-field">

                        <label class="filter-label" for="dateFrom">
                            Order Date From
                        </label>

                        <input
                            type="date"
                            id="dateFrom"
                            name="date_from"
                            class="form-control"
                            value="<?= html_escape($filters['date_from']); ?>"
                        >

                    </div>


                    <!-- DATE TO -->

                    <div class="filter-field">

                        <label class="filter-label" for="dateTo">
                            Order Date To
                        </label>

                        <input
                            type="date"
                            id="dateTo"
                            name="date_to"
                            class="form-control"
                            value="<?= html_escape($filters['date_to']); ?>"
                        >

                    </div>


                    <!-- STATUS -->

                    <div class="filter-field">

                        <label class="filter-label" for="filterStatus">
                            Status
                        </label>

                        <select
                            id="filterStatus"
                            name="status"
                            class="form-select select2-filter"
                        >
                            <option value="">Semua Status</option>

                            <option
                                value="OPEN"
                                <?= $filters['status'] === 'OPEN' ? 'selected' : ''; ?>
                            >
                                OPEN
                            </option>

                            <option
                                value="PENDING APPROVAL"
                                <?= $filters['status'] === 'PENDING APPROVAL' ? 'selected' : ''; ?>
                            >
                                PENDING APPROVAL
                            </option>

                            <option
                                value="CANCELLED"
                                <?= $filters['status'] === 'CANCELLED' ? 'selected' : ''; ?>
                            >
                                CANCELLED
                            </option>

                        </select>

                    </div>


                    <!-- SEARCH -->

                    <div class="filter-field">

                        <label class="filter-label" for="search">
                            Search
                        </label>

                        <div class="search-wrap">

                            <i class="ti ti-search search-icon"></i>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                placeholder="Cari nomor Order, Customer..."
                                value="<?= html_escape($filters['search']); ?>"
                                aria-label="Cari Order"
                            >

                        </div>

                    </div>

                </div>


                <!-- FILTER ACTIONS -->

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn btn-primary btn-filter"
                    >
                        <i class="ti ti-search me-1"></i>
                        Search
                    </button>

                    <a
                        href="<?= base_url('order'); ?>"
                        class="btn btn-outline-secondary btn-filter"
                    >
                        Reset
                    </a>

                </div>


                <!-- ADD ORDER -->

                <a
                    href="<?= base_url('order/create'); ?>"
                    class="btn-add"
                >
                    <i class="ti ti-plus"></i>
                    Add Order
                </a>

            </div>

        </form>


        <!-- =================================================
             TABLE
        ================================================== -->

        <div class="table-area">

            <div class="table-responsive">

                <table class="table align-middle" id="mainTable">

                    <thead>

                        <tr>

                            <th>Order Number</th>

                            <th>Order Date</th>

                            <th>Customer</th>

                            <th>Supplier</th>

                            <th class="text-end">
                                Sales Total
                            </th>

                            <th class="text-end">
                                Purchase Total
                            </th>

                            <th class="text-end">
                                Difference
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody id="table-body">

                        <?php if (!empty($orders)): ?>

                            <?php foreach ($orders as $row): ?>

                                <?php
                                    $status = $statusConfig($row['STATUS']);

                                    $differenceClass =
                                        ((float) $row['TOTAL_DIFFERENCE'] < 0)
                                            ? 'difference-negative'
                                            : 'difference-positive';
                                ?>

                                <tr>

                                    <!-- ORDER NUMBER -->

                                    <td>

                                        <a
                                            href="<?= base_url('order/detail/' . rawurlencode($row['ORDER_NO'])); ?>"
                                            class="order-code"
                                        >
                                            <?= html_escape($row['ORDER_NO']); ?>
                                        </a>

                                        <span class="child-order">
                                            CO:
                                            <?= html_escape($row['ORDER_CUSTOMER_NO'] ?: '-'); ?>
                                        </span>

                                        <span class="child-order">
                                            OS:
                                            <?= html_escape($row['ORDER_SUPPLIER_NO'] ?: '-'); ?>
                                        </span>

                                    </td>


                                    <!-- DATE -->

                                    <td>
                                        <?= $formatDate($row['ORDER_DATE']); ?>
                                    </td>


                                    <!-- CUSTOMER -->

                                    <td>

                                        <span class="party-code">
                                            <?= html_escape($row['CUST']); ?>
                                        </span>

                                        <span
                                            class="party-name"
                                            title="<?= html_escape($row['CUSTOMER_NAME'] ?: '-'); ?>"
                                        >
                                            <?= html_escape($row['CUSTOMER_NAME'] ?: '-'); ?>
                                        </span>

                                    </td>


                                    <!-- SUPPLIER -->

                                    <td>

                                        <span class="party-code">
                                            <?= html_escape($row['SUPPLIER']); ?>
                                        </span>

                                        <span
                                            class="party-name"
                                            title="<?= html_escape($row['SUPPLIER_NAME'] ?: '-'); ?>"
                                        >
                                            <?= html_escape($row['SUPPLIER_NAME'] ?: '-'); ?>
                                        </span>

                                    </td>


                                    <!-- SALES TOTAL -->

                                    <td class="number-cell">
                                        <?= $formatMoney($row['SALES_TOTAL']); ?>
                                    </td>


                                    <!-- PURCHASE TOTAL -->

                                    <td class="number-cell">
                                        <?= $formatMoney($row['PURCHASE_TOTAL']); ?>
                                    </td>


                                    <!-- DIFFERENCE -->

                                    <td class="number-cell <?= $differenceClass; ?>">
                                        <?= $formatMoney($row['TOTAL_DIFFERENCE']); ?>
                                    </td>


                                    <!-- STATUS -->

                                    <td class="text-center">

                                        <span class="status-badge <?= $status['class']; ?>">
                                            <?= html_escape($status['label']); ?>
                                        </span>

                                    </td>


                                    <!-- ACTION -->

                                    <td class="action-cell">

                                        <div class="action-group">

                                            <a
                                                class="btn-action btn-detail"
                                                href="<?= base_url('order/detail/' . rawurlencode($row['ORDER_NO'])); ?>"
                                                title="View Order Detail"
                                            >
                                                <i class="ti ti-eye"></i>
                                                Detail
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="9" class="empty-state">

                                    <i class="ti ti-folder-open"></i>

                                    Tidak ada data Order.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="panel-footer">

            <div id="info">
                Menampilkan
                <strong><?= number_format($totalRecords); ?></strong>
                dari
                <strong><?= number_format($totalRecords); ?></strong>
                data Order
            </div>

            <div>
                Total Records:
                <strong><?= number_format($totalRecords); ?></strong>
            </div>

        </div>

    </section>

</div>

<script>
    $(document).ready(function () {

        // =====================================================
        // SELECT2 FILTER ORDER
        // =====================================================

        $('.order-page .select2-filter').each(function () {

            var $select = $(this);

            // Prevent double initialization
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                width: '100%',
                placeholder: 'Pilih...',
                allowClear: true,
                minimumResultsForSearch: 0
            });

        });

    });
</script>
