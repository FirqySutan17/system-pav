
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

$summary = $summary ?? [];

$totalTransactions = (int) ($summary['TOTAL_TRANSACTIONS'] ?? 0);
$totalPosted       = (int) ($summary['TOTAL_POSTED'] ?? 0);
$totalVoid         = (int) ($summary['TOTAL_VOID'] ?? 0);
$totalPayment      = (float) ($summary['TOTAL_PAYMENT_POSTED'] ?? 0);

$totalRecords = count($payments ?? []);
?>

<style>
/* =====================================================
   REPORT PAYMENT
===================================================== */

.report-payment {
    color: #243247;
}

.report-payment .page-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 15px;
    margin: 90px 0 22px;
}

.report-payment .eyebrow {
    color: #64748b;
    font-size: .76rem;
    font-weight: 700;
    letter-spacing: .09em;
    text-transform: uppercase;
    margin-bottom: 7px;
}

.report-payment .page-title {
    margin: 0;
    font-size: 1.55rem;
    font-weight: 750;
    color: #172b4d;
    letter-spacing: -.035em;
}

.report-payment .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .9rem;
}

/* SUMMARY */

.report-payment .summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.report-payment .summary-card {
    background: #fff;
    border: 1px solid #e7edf5;
    border-radius: 14px;
    padding: 19px 20px;
    box-shadow: 0 5px 20px rgba(22,42,75,.045);
    min-width: 0;
}

.report-payment .summary-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.report-payment .summary-label {
    color: #718096;
    font-size: .76rem;
    font-weight: 750;
    letter-spacing: .045em;
    text-transform: uppercase;
}

.report-payment .summary-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
}

.report-payment .icon-total {
    color: #4261dc;
    background: #eef2ff;
}

.report-payment .icon-posted {
    color: #087f5b;
    background: #e8f7f0;
}

.report-payment .icon-void {
    color: #c03939;
    background: #fff0f0;
}

.report-payment .icon-amount {
    color: #9a5a00;
    background: #fff5df;
}

.report-payment .summary-value {
    margin-top: 13px;
    font-size: 1.55rem;
    font-weight: 800;
    color: #172b4d;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.report-payment .summary-description {
    margin-top: 4px;
    font-size: .76rem;
    color: #94a3b8;
}

/* PANEL */

.report-payment .panel {
    background: #fff;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    box-shadow: 0 8px 28px rgba(22,42,75,.055);
    overflow: hidden;
}

.report-payment .panel-toolbar {
    padding: 20px 22px;
    border-bottom: 1px solid #edf1f7;
    background: linear-gradient(180deg, #fff, #fbfcff);
}

.report-payment .filter-grid {
    display: grid;
    grid-template-columns:
        minmax(0, 1.2fr)
        minmax(0, .9fr)
        minmax(0, .9fr)
        minmax(0, .8fr)
        minmax(0, .8fr)
        minmax(0, .8fr)
        minmax(0, 1.4fr);
    gap: 12px;
}

.report-payment .filter-field {
    min-width: 0;
}

.report-payment .filter-label {
    display: block;
    margin-bottom: 6px;
    color: #718096;
    font-size: .73rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.report-payment .form-control,
.report-payment .form-select {
    width: 100%;
    min-width: 0;
    min-height: 43px;
    border: 1px solid #dfe6f0;
    border-radius: 9px;
    box-shadow: none;
    color: #334155;
    font-size: .83rem;
}

.report-payment .form-control:focus,
.report-payment .form-select:focus {
    border-color: #8aa4ff;
    box-shadow: 0 0 0 3px rgba(99,126,255,.12);
}

.report-payment .filter-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.report-payment .filter-actions-left {
    display: flex;
    gap: 8px;
}

.report-payment .btn-filter {
    min-height: 41px;
    border-radius: 9px;
    padding: 0 15px;
    font-size: .83rem;
    font-weight: 650;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* TABLE */

.report-payment .table-area {
    padding: 10px 12px 0;
}

.report-payment .table-responsive {
    width: 100%;
    overflow-x: auto;
}

.report-payment #mainTable {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    white-space: nowrap;
}

.report-payment #mainTable thead th {
    padding: 14px;
    background: #f7f9fc;
    color: #66758b;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .05em;
    text-transform: uppercase;
    border-top: 0;
    border-bottom: 1px solid #e8edf4;
}

.report-payment #mainTable tbody td {
    padding: 9px 11px !important;
    font-size: .78rem !important;
    border-bottom: 1px solid #edf1f6 !important;
    color: #43536a;
    vertical-align: middle;
}

.report-payment #mainTable tbody tr:hover {
    background: #f8faff;
}

.report-payment .payment-code {
    color: #3d58d8;
    font-weight: 750;
    text-decoration: none;
}

.report-payment .party-code {
    display: block;
    color: #243247;
    font-weight: 700;
}

.report-payment .party-name {
    display: block;
    margin-top: 3px;
    color: #8491a4;
    font-size: .75rem;
    max-width: 190px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.report-payment .number-cell {
    text-align: right;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

.report-payment .order-list {
    max-width: 220px;
    white-space: normal;
    line-height: 1.65;
    color: #52658b;
    font-size: .77rem;
}

.report-payment .method-badge,
.report-payment .type-badge,
.report-payment .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 7px;
    font-size: .69rem;
    font-weight: 750;
    letter-spacing: .025em;
}

.report-payment .method-transfer {
    color: #425bd4;
    background: #edf0ff;
}

.report-payment .method-cash {
    color: #087f5b;
    background: #e8f7f0;
}

.report-payment .type-fifo {
    color: #087f5b;
    background: #e8f7f0;
}

.report-payment .type-manual {
    color: #9a5a00;
    background: #fff5df;
}

.report-payment .status-posted {
    color: #087f5b;
    background: #e8f7f0;
}

.report-payment .status-void {
    color: #c03939;
    background: #fff0f0;
}

.report-payment .btn-expand {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    color: #4261dc;
    background: #eef2ff;
}

.report-payment .btn-expand:hover {
    background: #dfe7ff;
}

.report-payment .detail-row td {
    padding: 0 !important;
    background: #f8faff;
}

.report-payment .detail-container {
    padding: 18px 22px;
}

.report-payment .detail-title {
    margin-bottom: 12px;
    color: #334155;
    font-weight: 750;
    font-size: .85rem;
}

.report-payment .detail-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid #e7edf5;
}

.report-payment .detail-table th {
    padding: 11px 12px;
    color: #66758b;
    background: #f7f9fc;
    border-bottom: 1px solid #e8edf4;
    font-size: .7rem;
    text-transform: uppercase;
    white-space: nowrap;
}

.report-payment .detail-table td {
    padding: 11px 12px;
    border-bottom: 1px solid #edf1f6;
    font-size: .8rem;
}

.report-payment .detail-table .text-end {
    text-align: right;
    font-variant-numeric: tabular-nums;
}

.report-payment .empty-state {
    padding: 35px 15px !important;
    text-align: center;
    color: #8491a4 !important;
}

.report-payment .empty-state i {
    display: block;
    font-size: 28px;
    margin-bottom: 9px;
    color: #c2ccda;
}

.report-payment .panel-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px 22px 20px;
    color: #7b8798;
    font-size: .82rem;
}

/* SELECT2 */

.report-payment .select2-container {
    width: 100% !important;
}

.report-payment .select2-container--default
.select2-selection--single {
    height: 43px !important;
    border: 1px solid #dfe6f0 !important;
    border-radius: 9px !important;
}

.report-payment .select2-container--default
.select2-selection--single
.select2-selection__rendered {
    line-height: 41px !important;
    padding-left: 12px !important;
    font-size: .83rem;
}

.report-payment .select2-container--default
.select2-selection--single
.select2-selection__arrow {
    height: 41px !important;
}

.report-payment .select2-dropdown {
    z-index: 9999;
    border-radius: 9px;
    overflow: hidden;
}

/* RESPONSIVE */

@media (max-width: 1400px) {
    .report-payment .filter-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

@media (max-width: 1100px) {
    .report-payment .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .report-payment .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767px) {
    .report-payment .page-heading {
        margin-top: 80px;
    }

    .report-payment .page-title {
        font-size: 1.3rem;
    }

    .report-payment .summary-grid {
        gap: 9px;
    }

    .report-payment .summary-card {
        padding: 14px;
    }

    .report-payment .summary-value {
        font-size: 1.15rem;
    }

    .report-payment .filter-grid {
        grid-template-columns: 1fr;
    }

    .report-payment .panel-toolbar {
        padding: 16px;
    }

    .report-payment .filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .report-payment .filter-actions-left {
        width: 100%;
    }

    .report-payment .filter-actions-left .btn {
        flex: 1;
    }

    .report-payment .panel-footer {
        padding: 14px 16px;
    }
}
</style>

<div class="container-fluid report-payment px-2 px-lg-3 py-2">

    <!-- PAGE HEADING -->
    <div class="page-heading">
        <div>
            <div class="eyebrow">
                <i class="ti ti-chart-bar"></i>
                Accounting / Reports
            </div>

            <h1 class="page-title">Report Payment</h1>

            <p class="page-subtitle">
                Monitoring transaksi pembayaran Supplier dan alokasi Supplier Order.
            </p>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-top">
                <div class="summary-label">Total Transactions</div>
                <div class="summary-icon icon-total">
                    <i class="ti ti-receipt"></i>
                </div>
            </div>
            <div class="summary-value">
                <?= number_format($totalTransactions); ?>
            </div>
            <div class="summary-description">
                Total transaksi sesuai filter
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-top">
                <div class="summary-label">Total Posted</div>
                <div class="summary-icon icon-posted">
                    <i class="ti ti-circle-check"></i>
                </div>
            </div>
            <div class="summary-value">
                <?= number_format($totalPosted); ?>
            </div>
            <div class="summary-description">
                Transaksi aktif
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-top">
                <div class="summary-label">Total Void</div>
                <div class="summary-icon icon-void">
                    <i class="ti ti-ban"></i>
                </div>
            </div>
            <div class="summary-value">
                <?= number_format($totalVoid); ?>
            </div>
            <div class="summary-description">
                Transaksi dibatalkan
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-top">
                <div class="summary-label">Total Payment Posted</div>
                <div class="summary-icon icon-amount">
                    <i class="ti ti-wallet"></i>
                </div>
            </div>
            <div class="summary-value" style="font-size:1.2rem;">
                <?= $formatMoney($totalPayment); ?>
            </div>
            <div class="summary-description">
                Nilai pembayaran POSTED
            </div>
        </div>

    </div>

    <!-- MAIN PANEL -->
    <section class="panel">

        <!-- FILTER -->
        <form method="GET" action="<?= base_url('reportpayment'); ?>">

            <div class="panel-toolbar">

                <div class="filter-grid">

                    <!-- SUPPLIER -->
                    <div class="filter-field">
                        <label class="filter-label">Supplier</label>

                        <select name="supplier" class="form-select select2-filter">
                            <option value="">All Suppliers</option>

                            <?php foreach (($suppliers ?? []) as $supplier): ?>
                                <option
                                    value="<?= html_escape($supplier['CUST']); ?>"
                                    <?= ($filters['supplier'] ?? '') === $supplier['CUST'] ? 'selected' : ''; ?>
                                >
                                    <?= html_escape(
                                        $supplier['CUST'] . ' - ' . $supplier['FULL_NAME']
                                    ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- DATE FROM -->
                    <div class="filter-field">
                        <label class="filter-label">Payment Date From</label>
                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="<?= html_escape($filters['date_from'] ?? ''); ?>"
                        >
                    </div>

                    <!-- DATE TO -->
                    <div class="filter-field">
                        <label class="filter-label">Payment Date To</label>
                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="<?= html_escape($filters['date_to'] ?? ''); ?>"
                        >
                    </div>

                    <!-- METHOD -->
                    <div class="filter-field">
                        <label class="filter-label">Payment Method</label>
                        <select name="method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="TRANSFER"
                                <?= ($filters['method'] ?? '') === 'TRANSFER' ? 'selected' : ''; ?>>
                                TRANSFER
                            </option>
                            <option value="CASH"
                                <?= ($filters['method'] ?? '') === 'CASH' ? 'selected' : ''; ?>>
                                CASH
                            </option>
                        </select>
                    </div>

                    <!-- TYPE -->
                    <div class="filter-field">
                        <label class="filter-label">Payment Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="FIFO"
                                <?= ($filters['type'] ?? '') === 'FIFO' ? 'selected' : ''; ?>>
                                FIFO
                            </option>
                            <option value="MANUAL"
                                <?= ($filters['type'] ?? '') === 'MANUAL' ? 'selected' : ''; ?>>
                                MANUAL
                            </option>
                        </select>
                    </div>

                    <!-- STATUS -->
                    <div class="filter-field">
                        <label class="filter-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="POSTED"
                                <?= ($filters['status'] ?? '') === 'POSTED' ? 'selected' : ''; ?>>
                                POSTED
                            </option>
                            <option value="VOID"
                                <?= ($filters['status'] ?? '') === 'VOID' ? 'selected' : ''; ?>>
                                VOID
                            </option>
                        </select>
                    </div>

                    <!-- SEARCH -->
                    <div class="filter-field">
                        <label class="filter-label">Search</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Payment No, Slip No, Supplier Order..."
                            value="<?= html_escape($filters['search'] ?? ''); ?>"
                        >
                    </div>

                </div>

                <div class="filter-actions">

                    <div class="filter-actions-left">

                        <button type="submit" class="btn btn-primary btn-filter">
                            <i class="ti ti-search me-1"></i>
                            Search
                        </button>

                        <a href="<?= base_url('reportpayment'); ?>"
                        class="btn btn-outline-secondary btn-filter">
                            Reset
                        </a>

                    </div>

                    <a href="<?= base_url('reportpayment/export-excel') . '?' . http_build_query($filters); ?>"
                    class="btn btn-success btn-filter">
                        <i class="ti ti-file-spreadsheet me-1"></i>
                        Export Excel
                    </a>

                </div>

            </div>

        </form>

        <!-- TABLE -->
        <div class="table-area">

            <div class="table-responsive">

                <table class="table align-middle" id="mainTable">

                    <thead>
                        <tr>
                            <th class="text-center">Detail</th>
                            <th>Payment Number</th>
                            <th>Payment Date</th>
                            <th>Supplier</th>
                            <th class="text-center">Method</th>
                            <th class="text-center">Type</th>
                            <th>Slip No</th>
                            <th>Supplier Orders</th>
                            <th class="text-end">Total Payment</th>
                            <th class="text-center">Status</th>
                            <th>Registered By</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (!empty($payments)): ?>

                        <?php foreach ($payments as $row): ?>

                            <?php
                                $paymentId = (int) $row['PAYMENT_ID'];

                                $status = strtoupper($row['STATUS']);
                                $method = strtoupper($row['PAYMENT_METHOD']);
                                $type = strtoupper($row['PAYMENT_TYPE']);

                                $statusClass = $status === 'POSTED'
                                    ? 'status-posted'
                                    : 'status-void';

                                $methodClass = $method === 'CASH'
                                    ? 'method-cash'
                                    : 'method-transfer';

                                $typeClass = $type === 'MANUAL'
                                    ? 'type-manual'
                                    : 'type-fifo';
                            ?>

                            <tr class="payment-row" data-id="<?= $paymentId; ?>">

                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn-expand"
                                        data-id="<?= $paymentId; ?>"
                                        title="Show Allocation Detail"
                                    >
                                        <i class="ti ti-chevron-down"></i>
                                    </button>
                                </td>

                                <td>
                                    <span class="payment-code">
                                        #<?= html_escape($row['PAYMENT_NO']); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $formatDate($row['PAYMENT_DATE']); ?>
                                </td>

                                <td>
                                    <span class="party-code">
                                        <?= html_escape($row['SUPPLIER']); ?>
                                    </span>
                                    <span class="party-name"
                                          title="<?= html_escape($row['SUPPLIER_NAME'] ?? '-'); ?>">
                                        <?= html_escape($row['SUPPLIER_NAME'] ?? '-'); ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="method-badge <?= $methodClass; ?>">
                                        <?= html_escape($method); ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="type-badge <?= $typeClass; ?>">
                                        <?= html_escape($type); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= html_escape($row['SLIP_NO'] ?: '-'); ?>
                                </td>

                                <td>
                                    <div class="order-list">
                                        <?= html_escape($row['ORDER_LIST'] ?: '-'); ?>
                                    </div>
                                </td>

                                <td class="number-cell">
                                    <?= $formatMoney($row['TOTAL_PAYMENT']); ?>
                                </td>

                                <td class="text-center">
                                    <span class="status-badge <?= $statusClass; ?>">
                                        <?= html_escape($status); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= html_escape($row['REG_EMP'] ?: '-'); ?>
                                    <span class="party-name">
                                        <?= $formatDate($row['REG_DATE']); ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="11" class="empty-state">
                                <i class="ti ti-folder-open"></i>
                                Tidak ada data Payment.
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
                <strong><?= number_format($totalRecords); ?></strong>
                data Payment
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
    // SELECT2
    // =====================================================

    $('.report-payment .select2-filter').each(function () {

        const $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            width: '100%',
            placeholder: 'Pilih Supplier',
            allowClear: true,
            minimumResultsForSearch: 0
        });

    });

    // =====================================================
    // FORMAT NUMBER
    // =====================================================

    function formatMoney(value) {

        return Number(value || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    }

    // =====================================================
    // ESCAPE HTML
    // =====================================================

    function escapeHtml(value) {

        return $('<div>').text(value ?? '').html();

    }

    // =====================================================
    // ALLOCATION DETAIL
    // =====================================================

    $(document).on('click', '.btn-expand', function () {

        const $button = $(this);
        const paymentId = Number($button.data('id'));
        const $mainRow = $button.closest('tr');

        const existingRow = $('#allocation-row-' + paymentId);

        // Close detail if already opened
        if (existingRow.length) {

            existingRow.remove();

            $button.find('i')
                .removeClass('ti-chevron-up')
                .addClass('ti-chevron-down');

            return;
        }

        // Close other open details
        $('.detail-row').remove();

        $('.btn-expand i')
            .removeClass('ti-chevron-up')
            .addClass('ti-chevron-down');

        $button.find('i')
            .removeClass('ti-chevron-down')
            .addClass('ti-chevron-up');

        const detailRow = `
            <tr class="detail-row" id="allocation-row-${paymentId}">
                <td colspan="11">
                    <div class="detail-container">
                        <div class="detail-title">
                            <i class="ti ti-list-details me-1"></i>
                            Supplier Order Allocation Detail
                        </div>
                        <div class="text-center py-3">
                            <span class="spinner-border spinner-border-sm"></span>
                            Loading allocation...
                        </div>
                    </div>
                </td>
            </tr>
        `;

        $mainRow.after(detailRow);

        $.ajax({

            url: "<?= base_url('reportpayment/get-allocation/'); ?>" + paymentId,
            type: 'GET',
            dataType: 'json',

            success: function (response) {

                if (!response.ok || !Array.isArray(response.data)) {

                    $('#allocation-row-' + paymentId)
                        .find('.detail-container')
                        .html('<div class="text-danger">Gagal mengambil allocation detail.</div>');

                    return;
                }

                const details = response.data;

                if (details.length === 0) {

                    $('#allocation-row-' + paymentId)
                        .find('.detail-container')
                        .html('<div class="text-muted">Tidak ada allocation detail.</div>');

                    return;
                }

                let rows = '';
                let totalAllocation = 0;

                details.forEach(function (item, index) {

                    const amount = Number(item.PAID_AMOUNT || 0);
                    totalAllocation += amount;

                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${escapeHtml(item.ORDER_SUPPLIER_NO || '-')}</td>
                            <td>${escapeHtml(item.ORDER_NO || '-')}</td>
                            <td>${escapeHtml(item.ORDER_DATE || '-')}</td>
                            <td class="text-end">${formatMoney(item.ORDER_TOTAL)}</td>
                            <td class="text-end fw-bold">${formatMoney(item.PAID_AMOUNT)}</td>
                            <td class="text-end">${formatMoney(item.CURRENT_PAID)}</td>
                            <td class="text-end">${formatMoney(item.CURRENT_REMAIN)}</td>
                        </tr>
                    `;

                });

                const html = `
                    <div class="detail-title">
                        <i class="ti ti-list-details me-1"></i>
                        Supplier Order Allocation Detail
                    </div>

                    <div class="table-responsive">
                        <table class="detail-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Supplier Order No</th>
                                    <th>Master Order No</th>
                                    <th>Order Date</th>
                                    <th class="text-end">Order Total</th>
                                    <th class="text-end">Allocation Amount</th>
                                    <th class="text-end">Current Paid</th>
                                    <th class="text-end">Current Remain</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${rows}
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total Allocation</th>
                                    <th class="text-end">${formatMoney(totalAllocation)}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-muted mt-2" style="font-size:.72rem;">
                        Current Paid dan Current Remain menunjukkan saldo
                        Supplier Order saat ini, bukan saldo historis saat Payment dibuat.
                    </div>
                `;

                $('#allocation-row-' + paymentId)
                    .find('.detail-container')
                    .html(html);

            },

            error: function () {

                $('#allocation-row-' + paymentId)
                    .find('.detail-container')
                    .html(`
                        <div class="text-danger">
                            Terjadi kesalahan saat mengambil Allocation Detail.
                        </div>
                    `);

            }

        });

    });

});
</script>
