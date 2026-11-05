<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formatMoney = function ($value) {
    return number_format((float) $value, 2, ',', '.');
};

$formatDate = function ($value) {
    if (empty($value)) return '-';

    $timestamp = strtotime($value);
    return $timestamp ? date('d/m/Y', $timestamp) : '-';
};

$statusConfig = function ($status) {
    $status = strtoupper(trim((string) $status));

    return $status === 'POSTED'
        ? ['class' => 'status-posted', 'label' => 'POSTED']
        : ['class' => 'status-void', 'label' => $status ?: '-'];
};

$summary = $summary ?: [];
$totalRecords = count($cash_ins);
?>

<style>
.report-cash-in { color:#243247; }

.report-cash-in .page-heading {
    display:flex; justify-content:space-between; align-items:flex-start;
    flex-wrap:wrap; gap:16px; margin:90px 0 22px;
}
.report-cash-in .eyebrow {
    color:#64748b; font-size:.76rem; font-weight:700;
    letter-spacing:.09em; text-transform:uppercase; margin-bottom:7px;
}
.report-cash-in .page-title {
    margin:0; font-size:1.55rem; font-weight:750;
    letter-spacing:-.035em; color:#172b4d;
}
.report-cash-in .page-subtitle {
    margin:7px 0 0; color:#718096; font-size:.92rem;
}
.report-cash-in .summary-grid {
    display:grid; grid-template-columns:repeat(4,minmax(0,1fr));
    gap:14px; margin-bottom:18px;
}
.report-cash-in .summary-card {
    background:#fff; border:1px solid #e7edf5; border-radius:14px;
    padding:18px 20px; box-shadow:0 5px 20px rgba(22,42,75,.04);
}
.report-cash-in .summary-label {
    color:#718096; font-size:.72rem; font-weight:750;
    letter-spacing:.05em; text-transform:uppercase;
}
.report-cash-in .summary-value {
    margin-top:9px; color:#172b4d; font-size:1.25rem;
    font-weight:750; font-variant-numeric:tabular-nums;
}
.report-cash-in .summary-note {
    margin-top:5px; color:#94a3b8; font-size:.76rem;
}
.report-cash-in .panel {
    border:1px solid #e7edf5; border-radius:16px;
    background:#fff; box-shadow:0 8px 28px rgba(22,42,75,.055);
    overflow:hidden;
}
.report-cash-in .panel-toolbar {
    padding:20px 22px; border-bottom:1px solid #edf1f7;
    background:linear-gradient(180deg,#fff 0%,#fbfcff 100%);
}
.report-cash-in .filter-grid {
    display:grid;
    grid-template-columns:repeat(6,minmax(0,1fr));
    gap:12px;
}
.report-cash-in .filter-field { min-width:0; }
.report-cash-in .filter-label {
    display:block; margin-bottom:6px; color:#718096;
    font-size:.72rem; font-weight:750; text-transform:uppercase;
}
.report-cash-in .form-control,
.report-cash-in .form-select {
    width:100%; min-width:0; min-height:43px;
    border:1px solid #dfe6f0; border-radius:9px;
    box-shadow:none; font-size:.84rem; color:#334155;
}
.report-cash-in .form-control:focus,
.report-cash-in .form-select:focus {
    border-color:#8aa4ff;
    box-shadow:0 0 0 3px rgba(99,126,255,.12);
}
.report-cash-in .search-wrap { position:relative; }
.report-cash-in .search-wrap i {
    position:absolute; left:13px; top:50%;
    transform:translateY(-50%); color:#94a3b8;
}
.report-cash-in .search-wrap input { padding-left:38px; }
.report-cash-in .filter-actions {
    display:flex; justify-content:space-between;
    align-items:center; gap:10px; margin-top:14px;
    flex-wrap:wrap;
}
.report-cash-in .action-left,
.report-cash-in .action-right {
    display:flex; align-items:center; gap:8px; flex-wrap:wrap;
}
.report-cash-in .btn-filter {
    min-height:41px; border-radius:9px; padding:0 15px;
    font-size:.84rem; font-weight:650;
}
.report-cash-in .btn-export {
    min-height:41px; display:inline-flex; align-items:center;
    gap:7px; padding:0 15px; border-radius:9px;
    background:#087f5b; color:#fff; text-decoration:none;
    font-size:.84rem; font-weight:650;
}
.report-cash-in .btn-export:hover { background:#066b4d; color:#fff; }
.report-cash-in .table-area { padding:10px 12px 0; }
.report-cash-in .table-responsive { width:100%; overflow-x:auto; }
.report-cash-in #mainTable {
    width:100%; margin:0; border-collapse:separate;
    border-spacing:0; white-space:nowrap;
}
.report-cash-in #mainTable thead th {
    padding:14px 13px; background:#f7f9fc;
    color:#66758b; font-size:.72rem; font-weight:750;
    letter-spacing:.05em; text-transform:uppercase;
    border-top:0; border-bottom:1px solid #e8edf4;
}
.report-cash-in #mainTable tbody td {
    padding:13px; border-bottom:1px solid #edf1f6;
    color:#43536a; vertical-align:middle; font-size:.83rem;
}
.report-cash-in #mainTable tbody tr:hover { background:#f8faff; }
.report-cash-in .cash-in-code {
    color:#3d58d8; font-weight:750; text-decoration:none;
}
.report-cash-in .cash-in-code:hover { color:#263fb7; }
.report-cash-in .customer-code {
    display:block; color:#243247; font-weight:700;
}
.report-cash-in .customer-name {
    display:block; margin-top:3px; color:#8491a4;
    font-size:.75rem; max-width:180px;
    overflow:hidden; text-overflow:ellipsis;
}
.report-cash-in .number-cell {
    text-align:right; font-weight:700;
    font-variant-numeric:tabular-nums;
}
.report-cash-in .badge-method,
.report-cash-in .badge-type {
    display:inline-flex; padding:6px 9px;
    border-radius:7px; font-size:.68rem; font-weight:750;
}
.report-cash-in .method-cash { color:#087f5b; background:#e8f7f0; }
.report-cash-in .method-transfer { color:#425bd4; background:#edf0ff; }
.report-cash-in .type-fifo { color:#087f5b; background:#e8f7f0; }
.report-cash-in .type-manual { color:#9a5a00; background:#fff5df; }
.report-cash-in .status-badge {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 10px; border-radius:999px;
    font-size:.69rem; font-weight:750;
}
.report-cash-in .status-badge:before {
    content:''; width:6px; height:6px;
    border-radius:50%; display:inline-block;
}
.report-cash-in .status-posted { color:#087f5b; background:#e8f7f0; }
.report-cash-in .status-posted:before { background:#0b9b6a; }
.report-cash-in .status-void { color:#c03939; background:#fff0f0; }
.report-cash-in .status-void:before { background:#dc3545; }
.report-cash-in .btn-detail {
    width:30px; height:30px; display:inline-flex;
    justify-content:center; align-items:center;
    border:1px solid #dfe6f0; border-radius:8px;
    background:#fff; color:#52658b;
}
.report-cash-in .btn-detail:hover {
    color:#3d58d8; border-color:#aab8ff; background:#f5f7ff;
}
.report-cash-in .allocation-row td {
    padding:0!important; background:#f8faff!important;
}
.report-cash-in .allocation-container {
    padding:16px 22px 18px 45px;
}
.report-cash-in .allocation-title {
    margin-bottom:10px; color:#52658b;
    font-size:.75rem; font-weight:750;
    text-transform:uppercase; letter-spacing:.04em;
}
.report-cash-in .allocation-table {
    width:100%; border-collapse:collapse; background:#fff;
    border:1px solid #e7edf5; border-radius:9px;
}
.report-cash-in .allocation-table th {
    padding:9px 11px; color:#718096; background:#f7f9fc;
    font-size:.69rem; text-transform:uppercase;
}
.report-cash-in .allocation-table td {
    padding:9px 11px!important; font-size:.78rem!important;
    border-bottom:1px solid #edf1f6!important;
}
.report-cash-in .empty-state {
    padding:38px 16px!important; text-align:center;
    color:#8491a4!important;
}
.report-cash-in .panel-footer {
    display:flex; justify-content:space-between;
    align-items:center; flex-wrap:wrap; gap:12px;
    padding:16px 22px 20px; color:#7b8798; font-size:.83rem;
}
.report-cash-in .select2-container { width:100%!important; }
.report-cash-in .select2-container--default .select2-selection--single {
    height:43px!important; border:1px solid #dfe6f0!important;
    border-radius:9px!important;
}
.report-cash-in .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height:41px!important; padding-left:12px!important;
    font-size:.84rem;
}
.report-cash-in .select2-container--default .select2-selection--single .select2-selection__arrow {
    height:41px!important;
}
.report-cash-in .select2-dropdown { z-index:9999; }

@media(max-width:1200px) {
    .report-cash-in .filter-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
}
@media(max-width:991px) {
    .report-cash-in .summary-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
}
@media(max-width:767px) {
    .report-cash-in .page-heading { margin-top:80px; }
    .report-cash-in .filter-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .report-cash-in .panel-toolbar { padding:16px; }
    .report-cash-in .allocation-container { padding:14px; }
}
@media(max-width:480px) {
    .report-cash-in .filter-grid,
    .report-cash-in .summary-grid { grid-template-columns:1fr; }
    .report-cash-in .filter-actions,
    .report-cash-in .action-left,
    .report-cash-in .action-right { width:100%; }
    .report-cash-in .action-left .btn,
    .report-cash-in .action-right .btn,
    .report-cash-in .btn-export { flex:1; justify-content:center; }
    .report-cash-in .panel-footer { flex-direction:column; align-items:flex-start; }
}
</style>

<div class="container-fluid report-cash-in px-2 px-lg-3 py-2">

    <div class="page-heading">
        <div>
            <div class="eyebrow">
                <i class="ti ti-chart-bar"></i> Accounting / Reports
            </div>
            <h1 class="page-title">Report Cash In</h1>
            <p class="page-subtitle">
                Monitoring penerimaan pembayaran Customer dan alokasi Customer Order.
            </p>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-label">Total Transactions</div>
            <div class="summary-value">
                <?= number_format((int) ($summary['TOTAL_RECORDS'] ?? 0)); ?>
            </div>
            <div class="summary-note">Jumlah transaksi sesuai filter</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Posted</div>
            <div class="summary-value">
                <?= $formatMoney($summary['TOTAL_POSTED'] ?? 0); ?>
            </div>
            <div class="summary-note">Total Cash In berstatus POSTED</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Void</div>
            <div class="summary-value">
                <?= $formatMoney($summary['TOTAL_VOID'] ?? 0); ?>
            </div>
            <div class="summary-note">Nilai transaksi berstatus VOID</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Allocation</div>
            <div class="summary-value">
                <?= $formatMoney($summary['TOTAL_ALLOCATION'] ?? 0); ?>
            </div>
            <div class="summary-note">Allocation dari transaksi POSTED</div>
        </div>
    </div>

    <section class="panel">

        <!-- FILTER -->
        <form method="GET" action="<?= base_url('reportcashin'); ?>">
            <div class="panel-toolbar">

                <div class="filter-grid">

                    <div class="filter-field">
                        <label class="filter-label">Date From</label>
                        <input type="date" name="date_from" class="form-control"
                            value="<?= html_escape($filters['date_from']); ?>">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Date To</label>
                        <input type="date" name="date_to" class="form-control"
                            value="<?= html_escape($filters['date_to']); ?>">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Customer</label>
                        <select name="customer" class="form-select select2-filter">
                            <option value="">All Customers</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= html_escape($customer['CUST']); ?>"
                                    <?= $filters['customer'] === $customer['CUST'] ? 'selected' : ''; ?>>
                                    <?= html_escape($customer['CUST'] . ' - ' . $customer['FULL_NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Cash In Method</label>
                        <select name="method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="CASH" <?= $filters['method'] === 'CASH' ? 'selected' : ''; ?>>CASH</option>
                            <option value="TRANSFER" <?= $filters['method'] === 'TRANSFER' ? 'selected' : ''; ?>>TRANSFER</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="POSTED" <?= $filters['status'] === 'POSTED' ? 'selected' : ''; ?>>POSTED</option>
                            <option value="VOID" <?= $filters['status'] === 'VOID' ? 'selected' : ''; ?>>VOID</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label">Search</label>
                        <div class="search-wrap">
                            <i class="ti ti-search"></i>
                            <input type="text" name="search" class="form-control"
                                placeholder="Cash In No, Slip No, Order..."
                                value="<?= html_escape($filters['search']); ?>">
                        </div>
                    </div>

                </div>

                <div class="filter-actions">
                    <div class="action-left">
                        <button type="submit" class="btn btn-primary btn-filter">
                            <i class="ti ti-search me-1"></i> Search
                        </button>
                        <a href="<?= base_url('reportcashin'); ?>"
                           class="btn btn-outline-secondary btn-filter">
                            Reset
                        </a>
                    </div>

                    <div class="action-right">
                        <a href="<?= base_url('reportcashin/export-excel?' . http_build_query($filters)); ?>"
                           class="btn-export">
                            <i class="ti ti-file-spreadsheet"></i> Export Excel
                        </a>
                    </div>
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
                            <th>Cash In No</th>
                            <th>Cash In Date</th>
                            <th>Customer</th>
                            <th class="text-center">Method</th>
                            <th class="text-center">Type</th>
                            <th>Slip No</th>
                            <th>Customer Orders</th>
                            <th class="text-end">Total Cash In</th>
                            <th class="text-end">Total Allocation</th>
                            <th class="text-center">Status</th>
                            <th>Registered By</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (!empty($cash_ins)): ?>
                        <?php foreach ($cash_ins as $row): ?>
                            <?php
                                $status = $statusConfig($row['STATUS']);
                                $method = strtoupper((string) $row['CASH_IN_METHOD']);
                                $type = strtoupper((string) $row['CASH_IN_TYPE']);
                            ?>

                            <tr>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn-detail btn-allocation"
                                        data-id="<?= (int) $row['CASH_IN_ID']; ?>"
                                        title="View Allocation">
                                        <i class="ti ti-chevron-down"></i>
                                    </button>
                                </td>

                                <td>
                                    <span class="cash-in-code">
                                        #<?= html_escape($row['CASH_IN_NO']); ?>
                                    </span>
                                </td>

                                <td><?= $formatDate($row['CASH_IN_DATE']); ?></td>

                                <td>
                                    <span class="customer-code">
                                        <?= html_escape($row['CUSTOMER']); ?>
                                    </span>
                                    <span class="customer-name"
                                        title="<?= html_escape($row['CUSTOMER_NAME'] ?: '-'); ?>">
                                        <?= html_escape($row['CUSTOMER_NAME'] ?: '-'); ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge-method <?= $method === 'CASH' ? 'method-cash' : 'method-transfer'; ?>">
                                        <?= html_escape($method); ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="badge-type <?= $type === 'MANUAL' ? 'type-manual' : 'type-fifo'; ?>">
                                        <?= html_escape($type); ?>
                                    </span>
                                </td>

                                <td><?= html_escape($row['SLIP_NO'] ?: '-'); ?></td>

                                <td style="white-space:normal;min-width:160px;max-width:230px;">
                                    <?= html_escape($row['ORDER_LIST'] ?: '-'); ?>
                                </td>

                                <td class="number-cell">
                                    <?= $formatMoney($row['TOTAL_CASH_IN']); ?>
                                </td>

                                <td class="number-cell">
                                    <?= $formatMoney($row['TOTAL_ALLOCATION']); ?>
                                </td>

                                <td class="text-center">
                                    <span class="status-badge <?= $status['class']; ?>">
                                        <?= html_escape($status['label']); ?>
                                    </span>
                                </td>

                                <td><?= html_escape($row['REG_EMP'] ?: '-'); ?></td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="empty-state">
                                <i class="ti ti-folder-open d-block fs-2 mb-2"></i>
                                Tidak ada data Cash In.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel-footer">
            <div>
                Menampilkan <strong><?= number_format($totalRecords); ?></strong>
                data Cash In
            </div>
            <div>
                Total Records: <strong><?= number_format($totalRecords); ?></strong>
            </div>
        </div>

    </section>
</div>

<script>
$(document).ready(function () {

    // Select2 Customer
    $('.report-cash-in .select2-filter').select2({
        width: '100%',
        placeholder: 'All Customers',
        allowClear: true
    });

    // Allocation detail toggle
    $(document).on('click', '.btn-allocation', function () {

        const $button = $(this);
        const cashInId = $button.data('id');
        const $row = $button.closest('tr');
        const $nextRow = $row.next('.allocation-row');

        // Close if already opened
        if ($nextRow.length) {
            $nextRow.remove();
            $button.find('i')
                .removeClass('ti-chevron-up')
                .addClass('ti-chevron-down');
            return;
        }

        // Close other opened details
        $('.allocation-row').remove();
        $('.btn-allocation i')
            .removeClass('ti-chevron-up')
            .addClass('ti-chevron-down');

        $button.prop('disabled', true);

        const colspan = $('#mainTable thead th').length;

        const $detailRow = $(`
            <tr class="allocation-row">
                <td colspan="${colspan}">
                    <div class="allocation-container">
                        <div class="allocation-title">
                            <i class="ti ti-list-details me-1"></i>
                            Customer Order Allocation
                        </div>
                        <div class="allocation-content">
                            <span class="text-muted small">Loading allocation...</span>
                        </div>
                    </div>
                </td>
            </tr>
        `);

        $row.after($detailRow);

        $.ajax({
            url: "<?= base_url('reportcashin/get-allocation/'); ?>" + cashInId,
            type: 'GET',
            dataType: 'json',

            success: function (response) {

                if (!response.ok || !response.data.length) {
                    $detailRow.find('.allocation-content').html(
                        '<div class="text-muted small">Tidak ada detail allocation.</div>'
                    );
                    return;
                }

                let html = `
                    <div class="table-responsive">
                        <table class="allocation-table">
                            <thead>
                                <tr>
                                    <th>Customer Order No</th>
                                    <th>Order No</th>
                                    <th>Order Date</th>
                                    <th class="text-end">Order Total</th>
                                    <th class="text-end">Allocation Amount</th>
                                    <th class="text-end">Remain</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.data.forEach(function (item) {

                    const orderDate = item.ORDER_DATE
                        ? formatDate(item.ORDER_DATE)
                        : '-';

                    html += `
                        <tr>
                            <td>
                                <strong>${escapeHtml(item.ORDER_CUSTOMER_NO || '-')}</strong>
                            </td>
                            <td>${escapeHtml(item.ORDER_NO || '-')}</td>
                            <td>${orderDate}</td>
                            <td class="text-end">
                                ${formatMoney(item.ORDER_TOTAL)}
                            </td>
                            <td class="text-end">
                                <strong>${formatMoney(item.PAID_AMOUNT)}</strong>
                            </td>
                            <td class="text-end">
                                <strong>${formatMoney(item.ORDER_REMAIN)}</strong>
                            </td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                $detailRow.find('.allocation-content').html(html);
            },

            error: function () {
                $detailRow.find('.allocation-content').html(
                    '<span class="text-danger small">Gagal mengambil detail allocation.</span>'
                );
            },

            complete: function () {
                $button.prop('disabled', false);
                $button.find('i')
                    .removeClass('ti-chevron-down')
                    .addClass('ti-chevron-up');
            }
        });
    });

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function formatMoney(value) {
        return Number(value || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function formatDate(value) {
        const date = new Date(value);
        if (isNaN(date.getTime())) return '-';

        return String(date.getDate()).padStart(2, '0') + '/' +
            String(date.getMonth() + 1).padStart(2, '0') + '/' +
            date.getFullYear();
    }
});
</script>