
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| FORMAT HELPERS
|--------------------------------------------------------------------------
*/

$formatDate = function ($date) {
    if (empty($date)) {
        return '-';
    }

    return date('d/m/Y', strtotime($date));
};

$formatNumber = function ($value, $decimal = 2) {
    if ($value === NULL || $value === '') {
        return '-';
    }

    return number_format((float) $value, $decimal, ',', '.');
};

$formatMoney = function ($value) {
    if ($value === NULL || $value === '') {
        return '-';
    }

    return number_format((float) $value, 0, ',', '.');
};

$totalRecords = count($orders ?? []);

$exportUrl = base_url('reportorder/export-excel');

$exportQuery = http_build_query(array_filter(
    $filters,
    function ($value) {
        return $value !== '';
    }
));

if ($exportQuery !== '') {
    $exportUrl .= '?' . $exportQuery;
}
?>

<style>
.report-order-page {
    color: #334155;
}

.report-order-page .page-heading {
    margin-bottom: 20px;
    margin-top: 90px
}

.report-order-page .eyebrow {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.report-order-page .page-title {
    font-size: 25px;
    font-weight: 750;
    color: #172554;
    margin: 0;
}

.report-order-page .page-subtitle {
    color: #64748b;
    font-size: 13px;
    margin-top: 7px;
    margin-bottom: 0;
}

.report-order-page .panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
}

.report-order-page .panel-toolbar {
    padding: 18px;
    border-bottom: 1px solid #e2e8f0;
}

.report-order-page .filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(160px, 1fr));
    gap: 13px;
}

.report-order-page .filter-grid-second {
    display: grid;
    grid-template-columns: repeat(4, minmax(160px, 1fr));
    gap: 13px;
    margin-top: 13px;
}

.report-order-page .filter-label {
    display: block;
    font-size: 12px;
    font-weight: 650;
    color: #475569;
    margin-bottom: 6px;
}

.report-order-page .form-control,
.report-order-page .form-select {
    min-height: 39px;
    border-color: #dbe3ed;
    border-radius: 7px;
    font-size: 13px;
}

.report-order-page .filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
    height: 39px;
}

.report-order-page .btn-filter {
    min-height: 38px;
    border-radius: 7px;
    font-size: 13px;
    padding: 7px 16px;
}

.report-order-page .btn-export {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 38px;
    padding: 7px 15px;
    border-radius: 7px;
    background: #15803d;
    border: 1px solid #15803d;
    color: #fff;
    font-size: 13px;
    font-weight: 650;
    text-decoration: none;
}

.report-order-page .btn-export:hover {
    background: #166534;
    color: #fff;
}

.report-order-page .table-area {
    padding: 0 16px 12px;
}

.report-order-page .table-responsive {
    overflow-x: auto;
}

.report-order-page table {
    min-width: 1550px;
    margin-bottom: 0;
    font-size: 12px;
}

.report-order-page table thead th {
    background: #f1f5f9;
    color: #334155;
    font-weight: 700;
    white-space: nowrap;
    padding: 13px 10px;
    border-bottom: 1px solid #dbe3ed;
}

.report-order-page table tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid #edf2f7;
    vertical-align: middle;
    white-space: nowrap;
}

.report-order-page table tbody tr:hover {
    background: #f8fafc;
}

.report-order-page .order-code {
    color: #1d4ed8;
    font-weight: 700;
}

.report-order-page .party-name {
    font-weight: 600;
    color: #334155;
}

.report-order-page .transaction-type {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 10px;
    font-weight: 700;
}

.report-order-page .type-customer {
    background: #eff6ff;
    color: #1d4ed8;
}

.report-order-page .type-supplier {
    background: #f0fdf4;
    color: #15803d;
}

.report-order-page .status-badge {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
}

.report-order-page .status-open {
    color: #1d4ed8;
    background: #eff6ff;
}

.report-order-page .status-partial {
    color: #b45309;
    background: #fffbeb;
}

.report-order-page .status-paid {
    color: #15803d;
    background: #f0fdf4;
}

.report-order-page .status-other {
    color: #475569;
    background: #f1f5f9;
}

.report-order-page .number-cell {
    text-align: right;
    font-variant-numeric: tabular-nums;
}

.report-order-page .merged-cell {
    vertical-align: middle !important;
    background: #fff;
}

.report-order-page .empty-state {
    text-align: center;
    padding: 45px 15px !important;
    color: #94a3b8;
}

.report-order-page .panel-footer {
    display: flex;
    justify-content: space-between;
    padding: 14px 18px;
    border-top: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 12px;
}

@media (max-width: 992px) {
    .report-order-page .filter-grid,
    .report-order-page .filter-grid-second {
        grid-template-columns: repeat(2, minmax(140px, 1fr));
    }
}

@media (max-width: 576px) {
    .report-order-page .filter-grid,
    .report-order-page .filter-grid-second {
        grid-template-columns: 1fr;
    }

    .report-order-page .filter-actions-wrapper {
        grid-column: span 1 !important;
    }

    .report-order-page .filter-actions {
        height: auto;
    }

    .report-order-page .panel-footer {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<div class="container-fluid report-order-page px-2 px-lg-3 py-2">

    <!-- PAGE HEADING -->
    <div class="page-heading">

        <div class="eyebrow">
            <i class="ti ti-chart-bar me-1"></i>
            Reports
        </div>

        <h1 class="page-title">Report Order</h1>

        <p class="page-subtitle">
            Combined report untuk Customer Order dan Supplier Order.
        </p>

    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('report_error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ti ti-alert-circle me-1"></i>

            <?= html_escape($this->session->flashdata('report_error')); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>

    <section class="panel">

        <!-- FILTER -->
        <form method="GET" action="<?= base_url('reportorder'); ?>">

            <div class="panel-toolbar">

                <!-- ROW 1: DATE FROM, DATE TO, CUSTOMER, SUPPLIER -->
                <div class="filter-grid">

                    <div>
                        <label class="filter-label" for="dateFrom">Date From</label>
                        <input
                            type="date"
                            id="dateFrom"
                            name="date_from"
                            class="form-control"
                            value="<?= html_escape($filters['date_from']); ?>"
                        >
                    </div>

                    <div>
                        <label class="filter-label" for="dateTo">Date To</label>
                        <input
                            type="date"
                            id="dateTo"
                            name="date_to"
                            class="form-control"
                            value="<?= html_escape($filters['date_to']); ?>"
                        >
                    </div>

                    <div>
                        <label class="filter-label" for="filterCustomer">Customer</label>
                        <select
                            id="filterCustomer"
                            name="cust"
                            class="form-select select2-filter"
                        >
                            <option value="">All Customers</option>
                            <?php foreach ($customers as $customer): ?>
                                <option
                                    value="<?= html_escape($customer['CUST']); ?>"
                                    <?= $filters['cust'] === $customer['CUST'] ? 'selected' : ''; ?>
                                >
                                    <?= html_escape($customer['CUST'] . ' - ' . $customer['FULL_NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="filter-label" for="filterSupplier">Supplier</label>
                        <select
                            id="filterSupplier"
                            name="supplier"
                            class="form-select select2-filter"
                        >
                            <option value="">All Suppliers</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option
                                    value="<?= html_escape($supplier['CUST']); ?>"
                                    <?= $filters['supplier'] === $supplier['CUST'] ? 'selected' : ''; ?>
                                >
                                    <?= html_escape($supplier['CUST'] . ' - ' . $supplier['FULL_NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!-- ROW 2: STATUS, SEARCH, ACTIONS -->
                <div class="filter-grid-second">

                    <div>
                        <label class="filter-label" for="filterStatus">Order Status</label>
                        <select
                            id="filterStatus"
                            name="status"
                            class="form-select select2-filter"
                        >
                            <option value="">All Status</option>
                            <option value="OPEN" <?= $filters['status'] === 'OPEN' ? 'selected' : ''; ?>>OPEN</option>
                            <option value="PENDING APPROVAL" <?= $filters['status'] === 'PENDING APPROVAL' ? 'selected' : ''; ?>>PENDING APPROVAL</option>
                            <option value="CANCELLED" <?= $filters['status'] === 'CANCELLED' ? 'selected' : ''; ?>>CANCELLED</option>
                        </select>
                    </div>

                    <div>
                        <label class="filter-label" for="search">Search</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            placeholder="Order No. / Customer / Supplier..."
                            value="<?= html_escape($filters['search']); ?>"
                        >
                    </div>

                    <div class="filter-actions-wrapper" style="grid-column: span 2;">
                        <label class="filter-label">&nbsp;</label>
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-filter">
                                <i class="ti ti-search me-1"></i> Search
                            </button>

                            <a href="<?= base_url('reportorder'); ?>"
                               class="btn btn-outline-secondary btn-filter">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>

                            <a href="<?= html_escape($exportUrl); ?>" class="btn-export">
                                <i class="ti ti-file-spreadsheet"></i> Export Excel
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </form>

        <!-- REPORT TABLE -->
        <div class="table-area">

            <div class="table-responsive">

                <table class="table align-middle" id="reportOrderTable">

                    <thead>

                        <tr>

                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>No. Sales/Supplier</th>
                            <th>Customer/Supplier</th>
                            <th>Basis</th>
                            <th class="text-end">Ekor</th>
                            <th class="text-end">BW</th>
                            <th class="text-end">Avg. BW</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Diff Price</th>
                            <th class="text-end">Total Diff Price</th>
                            <th class="text-end">Remain</th>
                            <th class="text-center">Payment Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($orders)): ?>

                            <?php foreach ($orders as $index => $row): ?>

                                <?php

                                $type = strtoupper(
                                    $row['ORDER_TYPE'] ?? ''
                                );

                                /*
                                |--------------------------------------------------------------------------
                                | CHECK CUSTOMER - SUPPLIER PAIR
                                |--------------------------------------------------------------------------
                                |
                                | Satu Master Order memiliki:
                                | 1 Customer Order
                                | 1 Supplier Order
                                |
                                | Customer row ditampilkan lebih dahulu.
                                | Supplier row digunakan untuk mengambil
                                | DIFF_PRICE dan TOTAL_DIFF_PRICE.
                                |
                                */

                                $nextRow = $orders[$index + 1] ?? NULL;

                                $hasPair = (
                                    $type === 'CUSTOMER' &&
                                    $nextRow !== NULL &&
                                    strtoupper($nextRow['ORDER_TYPE'] ?? '') === 'SUPPLIER' &&
                                    (string) $nextRow['ORDER_ID'] === (string) $row['ORDER_ID']
                                );

                                /*
                                |--------------------------------------------------------------------------
                                | DIFF PRICE SOURCE
                                |--------------------------------------------------------------------------
                                */

                                $diffPrice = $hasPair
                                    ? $nextRow['DIFF_PRICE']
                                    : NULL;

                                $totalDiffPrice = $hasPair
                                    ? $nextRow['TOTAL_DIFF_PRICE']
                                    : NULL;

                                /*
                                |--------------------------------------------------------------------------
                                | PAYMENT STATUS
                                |--------------------------------------------------------------------------
                                */

                                $paymentStatus = strtoupper(
                                    trim((string) ($row['PAYMENT_STATUS'] ?? ''))
                                );

                                $statusClass = 'status-other';

                                if ($paymentStatus === 'OPEN') {

                                    $statusClass = 'status-open';

                                } elseif (
                                    in_array($paymentStatus, [
                                        'PARTIAL',
                                        'PARTIALLY PAID'
                                    ], TRUE)
                                ) {

                                    $statusClass = 'status-partial';

                                } elseif (
                                    in_array($paymentStatus, [
                                        'PAID',
                                        'CLOSED'
                                    ], TRUE)
                                ) {

                                    $statusClass = 'status-paid';

                                }

                                ?>

                                <tr>

                                    <?php if ($type === 'CUSTOMER'): ?>

                                        <!-- MERGED: NO ORDER -->
                                        <td
                                            rowspan="<?= $hasPair ? 2 : 1; ?>"
                                            class="merged-cell"
                                        >

                                            <span class="order-code">
                                                #<?= html_escape($row['ORDER_NO']); ?>
                                            </span>

                                        </td>

                                        <!-- MERGED: ORDER DATE -->
                                        <td
                                            rowspan="<?= $hasPair ? 2 : 1; ?>"
                                            class="merged-cell"
                                        >

                                            <?= $formatDate($row['ORDER_DATE']); ?>

                                        </td>

                                    <?php endif; ?>

                                    <!-- TRANSACTION NUMBER -->
                                    <td>

                                        <div class="d-flex flex-column gap-1">

                                            <span class="transaction-type <?= $type === 'CUSTOMER'
                                                ? 'type-customer'
                                                : 'type-supplier'; ?>">

                                                <?= html_escape($type); ?>

                                            </span>

                                            <span><strong>
                                                #<?= html_escape($row['TRANSACTION_NO']); ?>
                                            </strong></span>

                                        </div>

                                    </td>

                                    <!-- CUSTOMER / SUPPLIER -->
                                    <td>

                                        <span class="party-name">

                                            <?= html_escape(
                                                $row['PARTY_NAME'] ?: $row['PARTY_CODE']
                                            ); ?>

                                        </span>

                                    </td>

                                    <!-- BASIS -->
                                    <td>
                                        <?= html_escape($row['BASIS'] ?? '-'); ?>
                                    </td>

                                    <!-- EKOR -->
                                    <td class="number-cell">
                                        <?= $formatNumber($row['EKOR'], 0); ?>
                                    </td>

                                    <!-- BW -->
                                    <td class="number-cell">
                                        <?= $formatNumber($row['KG'], 2); ?>
                                    </td>

                                    <!-- AVG BW -->
                                    <td class="number-cell">
                                        <?= $formatNumber($row['AVG_BW'], 2); ?>
                                    </td>

                                    <!-- PRICE -->
                                    <td class="number-cell">
                                        <?= $formatMoney($row['PRICE']); ?>
                                    </td>

                                    <!-- TOTAL -->
                                    <td class="number-cell">
                                        <?= $formatMoney($row['TOTAL']); ?>
                                    </td>

                                    <?php if ($type === 'CUSTOMER'): ?>

                                        <!-- MERGED: DIFF PRICE -->
                                        <td
                                            rowspan="<?= $hasPair ? 2 : 1; ?>"
                                            class="number-cell merged-cell"
                                        >

                                            <?= $formatMoney($diffPrice); ?>

                                        </td>

                                        <!-- MERGED: TOTAL DIFF PRICE -->
                                        <td
                                            rowspan="<?= $hasPair ? 2 : 1; ?>"
                                            class="number-cell merged-cell"
                                        >

                                            <?= $formatMoney($totalDiffPrice); ?>

                                        </td>

                                    <?php endif; ?>

                                    <!-- REMAIN -->
                                    <td class="number-cell">
                                        <?= $formatMoney($row['REMAIN']); ?>
                                    </td>

                                    <!-- PAYMENT STATUS -->
                                    <td class="text-center">

                                        <span class="status-badge <?= $statusClass; ?>">

                                            <?= html_escape(
                                                $paymentStatus ?: '-'
                                            ); ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="14" class="empty-state">

                                    <i class="ti ti-folder-open fs-2 d-block mb-2"></i>

                                    Tidak ada data Order.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="panel-footer">

            <div>

                Menampilkan

                <strong>
                    <?= number_format($totalRecords); ?>
                </strong>

                data transaksi Order

            </div>

            <div>

                Total Records:

                <strong>
                    <?= number_format($totalRecords); ?>
                </strong>

            </div>

        </div>

    </section>

</div>

<script>
$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | SELECT2 FILTERS
    |--------------------------------------------------------------------------
    */

    $('.report-order-page .select2-filter').each(function () {

        var $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            width: '100%',
            placeholder: '-- PILIH --',
            allowClear: true,
            minimumResultsForSearch: 0
        });

    });


    /*
    |--------------------------------------------------------------------------
    | VALIDATE DATE RANGE
    |--------------------------------------------------------------------------
    */

    $('#dateFrom, #dateTo').on('change', function () {

        var dateFrom = $('#dateFrom').val();
        var dateTo = $('#dateTo').val();

        if (dateFrom && dateTo && dateFrom > dateTo) {
            $('#dateTo').val('');
        }

    });

});
</script>
