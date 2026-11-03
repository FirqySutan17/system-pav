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

$displayValue = function ($value) {
    return !empty($value) ? html_escape($value) : '-';
};

$cash_in = $cash_in ?? [];
$details = $cash_in['details'] ?? [];

$status = strtoupper($cash_in['STATUS'] ?? '');
$method = strtoupper($cash_in['CASH_IN_METHOD'] ?? '');
$type = strtoupper($cash_in['CASH_IN_TYPE'] ?? '');

$statusClass = $status === 'POSTED' ? 'success' : 'danger';
$methodClass = $method === 'CASH' ? 'cash' : 'transfer';
$typeClass = $type === 'FIFO' ? 'fifo' : 'manual';

$totalAllocation = 0;

foreach ($details as $detail) {
    $totalAllocation += (float) $detail['PAID_AMOUNT'];
}
?>

<style>
.cash-in-detail-page {
    color: #243247;
    padding-bottom: 30px;
}

.cash-in-detail-page .page-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin: 90px 0 22px;
}

.cash-in-detail-page .eyebrow {
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

.cash-in-detail-page .page-title {
    margin: 0;
    color: #172b4d;
    font-size: 1.55rem;
    font-weight: 750;
    letter-spacing: -.035em;
}

.cash-in-detail-page .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .92rem;
}

.cash-in-detail-page .breadcrumb {
    background: transparent;
    margin: 0;
    padding: 8px 0;
    font-size: .82rem;
}

.cash-in-detail-page .breadcrumb a {
    color: #526fe5;
    text-decoration: none;
}

.cash-in-detail-page .detail-card {
    margin-bottom: 20px;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22, 42, 75, .055);
    overflow: hidden;
}

.cash-in-detail-page .detail-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf1f7;
    background: linear-gradient(180deg, #fff 0%, #fbfcff 100%);
}

.cash-in-detail-page .section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cash-in-detail-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 11px;
    font-size: 19px;
}

.cash-in-detail-page .icon-cashin {
    color: #4965e8;
    background: #edf0ff;
}

.cash-in-detail-page .icon-allocation {
    color: #087f5b;
    background: #e8f7f0;
}

.cash-in-detail-page .section-title {
    margin: 0;
    color: #243247;
    font-size: .98rem;
    font-weight: 750;
}

.cash-in-detail-page .section-description {
    margin: 3px 0 0;
    color: #8491a4;
    font-size: .76rem;
}

.cash-in-detail-page .detail-card-body {
    padding: 22px;
}

.cash-in-detail-page .detail-item {
    margin-bottom: 20px;
    min-width: 0;
}

.cash-in-detail-page .detail-label {
    display: block;
    margin-bottom: 7px;
    color: #8491a4;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.cash-in-detail-page .detail-value {
    color: #26354d;
    font-size: .9rem;
    font-weight: 650;
    overflow-wrap: anywhere;
}

.cash-in-detail-page .detail-value.large {
    font-size: 1.05rem;
    font-weight: 750;
}

.cash-in-detail-page .detail-value.primary {
    color: #3d58d8;
}

.cash-in-detail-page .detail-divider {
    height: 1px;
    margin: 5px 0 20px;
    background: #edf1f7;
}

.cash-in-detail-page .party-name {
    color: #26354d;
    font-weight: 750;
}

.cash-in-detail-page .party-code {
    color: #8491a4;
    font-size: .78rem;
    margin-top: 4px;
}

.cash-in-detail-page .cashin-status,
.cash-in-detail-page .cashin-method,
.cash-in-detail-page .cashin-type {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 7px;
    font-size: .7rem;
    font-weight: 750;
    letter-spacing: .025em;
}

.cash-in-detail-page .status-success,
.cash-in-detail-page .method-cash,
.cash-in-detail-page .type-fifo {
    color: #087f5b;
    background: #e8f7f0;
}

.cash-in-detail-page .status-danger {
    color: #c03939;
    background: #fff0f0;
}

.cash-in-detail-page .method-transfer {
    color: #425bd4;
    background: #edf0ff;
}

.cash-in-detail-page .type-manual {
    color: #9a5a00;
    background: #fff5df;
}

.cash-in-detail-page .summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.cash-in-detail-page .summary-item {
    padding: 16px 17px;
    border: 1px solid #e7edf5;
    border-radius: 12px;
    background: #fbfcff;
    min-width: 0;
}

.cash-in-detail-page .summary-item.highlight {
    background: #edf2ff;
    border-color: #dce5ff;
}

.cash-in-detail-page .summary-label {
    display: block;
    margin-bottom: 9px;
    color: #8491a4;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.cash-in-detail-page .summary-value {
    display: block;
    color: #26354d;
    font-size: 1.05rem;
    font-weight: 750;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.cash-in-detail-page .summary-item.highlight .summary-value {
    color: #3d58d8;
    font-size: 1.15rem;
}

.cash-in-detail-page .table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e7edf5;
    border-radius: 11px;
}

.cash-in-detail-page .detail-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    font-size: .81rem;
    white-space: nowrap;
}

.cash-in-detail-page .detail-table thead th {
    padding: 13px 14px;
    border: 0;
    border-bottom: 1px solid #e7edf5;
    background: #f7f9fc;
    color: #64748b;
    font-size: .7rem;
    font-weight: 750;
    letter-spacing: .035em;
    text-transform: uppercase;
    vertical-align: middle;
}

.cash-in-detail-page .detail-table tbody td {
    padding: 14px;
    border: 0;
    border-bottom: 1px solid #edf1f7;
    color: #475569;
    vertical-align: middle;
}

.cash-in-detail-page .detail-table tbody tr:last-child td {
    border-bottom: 0;
}

.cash-in-detail-page .detail-table tbody tr:hover {
    background: #fafbff;
}

.cash-in-detail-page .text-right {
    text-align: right;
    font-variant-numeric: tabular-nums;
}

.cash-in-detail-page .table-total {
    color: #263fb7 !important;
    font-weight: 750;
}

.cash-in-detail-page .table-empty {
    padding: 25px 15px !important;
    color: #94a3b8 !important;
    text-align: center;
}

.cash-in-detail-page .info-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 15px;
    border: 1px solid #dce7ff;
    border-radius: 10px;
    background: #f5f8ff;
    color: #52658b;
    font-size: .8rem;
    line-height: 1.6;
}

.cash-in-detail-page .info-note i {
    margin-top: 2px;
    color: #5270df;
    font-size: 17px;
}

.cash-in-detail-page .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 28px 15px;
    border: 1px dashed #dfe6f0;
    border-radius: 11px;
    background: #fbfcff;
    text-align: center;
}

.cash-in-detail-page .empty-state i {
    color: #a0acbd;
    font-size: 27px;
}

.cash-in-detail-page .empty-state strong {
    color: #64748b;
    font-size: .86rem;
}

.cash-in-detail-page .action-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    padding: 18px 22px;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22, 42, 75, .055);
}

.cash-in-detail-page .action-description {
    color: #8491a4;
    font-size: .8rem;
}

.cash-in-detail-page .btn-cashin {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 44px;
    padding: 0 18px;
    border: 1px solid #dfe6f0;
    border-radius: 10px;
    background: #fff;
    color: #64748b;
    font-size: .84rem;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.cash-in-detail-page .btn-cashin:hover {
    background: #f7f9fc;
    color: #334155;
}

@media (max-width: 991px) {
    .cash-in-detail-page .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767px) {
    .cash-in-detail-page .page-heading {
        margin-top: 80px;
    }

    .cash-in-detail-page .page-title {
        font-size: 1.3rem;
    }

    .cash-in-detail-page .detail-card-header {
        padding: 15px 16px;
    }

    .cash-in-detail-page .detail-card-body {
        padding: 17px 16px;
    }

    .cash-in-detail-page .summary-grid {
        grid-template-columns: 1fr;
    }

    .cash-in-detail-page .action-card {
        align-items: stretch;
        padding: 16px;
    }

    .cash-in-detail-page .action-card .btn-cashin {
        width: 100%;
    }
}
</style>

<div class="container-fluid cash-in-detail-page px-2 px-lg-3 py-2">

    <!-- PAGE HEADING -->
    <div class="page-heading">

        <div>
            <div class="eyebrow">
                <i class="ti ti-wallet"></i>
                Accounting
            </div>

            <h1 class="page-title">Cash In Detail</h1>

            <p class="page-subtitle">
                Informasi penerimaan pembayaran dan alokasi Customer Order.
            </p>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('cashin'); ?>">
                        Cash In Management
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    <?= html_escape($cash_in['CASH_IN_NO']); ?>
                </li>
            </ol>
        </nav>

    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('cash_in_success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti ti-check-circle me-2"></i>
            <?= html_escape($this->session->flashdata('cash_in_success')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('cash_in_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-2"></i>
            <?= html_escape($this->session->flashdata('cash_in_error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- 1. Cash In INFORMATION -->
    <section class="detail-card">

        <div class="detail-card-header">
            <div class="section-heading">
                <div class="section-icon icon-cashin">
                    <i class="ti ti-file-invoice"></i>
                </div>

                <div>
                    <h3 class="section-title">Cash In Information</h3>
                    <p class="section-description">
                        Informasi utama transaksi penerimaan Customer
                    </p>
                </div>
            </div>

            <span class="cashin-status status-<?= $statusClass; ?>">
                <?= html_escape($status ?: '-'); ?>
            </span>
        </div>

        <div class="detail-card-body">

            <div class="row">

                <div class="col-md-3">
                    <div class="detail-item">
                        <span class="detail-label">Cash In Number</span>
                        <div class="detail-value large primary">
                            <i class="ti ti-receipt me-1"></i>
                            <?= html_escape($cash_in['CASH_IN_NO']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="detail-item">
                        <span class="detail-label">Cash In Date</span>
                        <div class="detail-value">
                            <?= $formatDate($cash_in['CASH_IN_DATE']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="detail-item">
                        <span class="detail-label">Cash In Method</span>
                        <div class="detail-value">
                            <span class="cashin-method method-<?= $methodClass; ?>">
                                <?= html_escape($method ?: '-'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="detail-item">
                        <span class="detail-label">Cash In Type</span>
                        <div class="detail-value">
                            <span class="cashin-type type-<?= $typeClass; ?>">
                                <?= html_escape($type ?: '-'); ?>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="detail-divider"></div>

            <div class="row">

                <div class="col-md-6">
                    <div class="detail-item">
                        <span class="detail-label">Customer</span>

                        <div class="party-name">
                            <?= $displayValue($cash_in['CUSTOMER_NAME']); ?>
                        </div>

                        <div class="party-code">
                            <?= $displayValue($cash_in['CUSTOMER']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="detail-item">
                        <span class="detail-label">Slip Number</span>
                        <div class="detail-value">
                            <?= $displayValue($cash_in['SLIP_NO']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="detail-item">
                        <span class="detail-label">Created By</span>
                        <div class="detail-value">
                            <?= $displayValue($cash_in['REG_EMP']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="detail-item">
                        <span class="detail-label">Remark</span>
                        <div class="detail-value muted">
                            <?= nl2br($displayValue($cash_in['REMARK'])); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="summary-grid">

                <div class="summary-item">
                    <span class="summary-label">Total Cash In</span>
                    <span class="summary-value">
                        <?= $formatMoney($cash_in['TOTAL_CASH_IN']); ?>
                    </span>
                </div>

                <div class="summary-item">
                    <span class="summary-label">Total Allocated</span>
                    <span class="summary-value">
                        <?= $formatMoney($totalAllocation); ?>
                    </span>
                </div>

                <div class="summary-item highlight">
                    <span class="summary-label">Allocation Difference</span>
                    <span class="summary-value">
                        <?= $formatMoney(
                            (float) $cash_in['TOTAL_CASH_IN'] - $totalAllocation
                        ); ?>
                    </span>
                </div>

            </div>

        </div>
    </section>

    <!-- 2. Cash In ALLOCATION -->
    <section class="detail-card">

        <div class="detail-card-header">
            <div class="section-heading">
                <div class="section-icon icon-allocation">
                    <i class="ti ti-list-details"></i>
                </div>

                <div>
                    <h3 class="section-title">Cash In Allocation Detail</h3>
                    <p class="section-description">
                        Daftar Customer Order yang dialokasikan pada transaksi ini
                    </p>
                </div>
            </div>

            <span class="cashin-status status-success">
                <?= count($details); ?> Order(s)
            </span>
        </div>

        <div class="detail-card-body">

            <?php if (!empty($details)): ?>

                <div class="table-wrap">

                    <table class="detail-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Order Date</th>
                                <th>Master Order</th>
                                <th>Customer Order</th>
                                <th class="text-right">Order Total</th>
                                <th class="text-right">Credit Note</th>
                                <th class="text-right">Allocated Cash In</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($details as $index => $detail): ?>

                                <tr>
                                    <td><?= $index + 1; ?></td>

                                    <td>
                                        <?= $formatDate($detail['ORDER_DATE']); ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= $displayValue($detail['ORDER_NO']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <strong class="text-primary">
                                            <?= $displayValue($detail['ORDER_CUSTOMER_NO']); ?>
                                        </strong>
                                    </td>

                                    <td class="text-right">
                                        <?= $formatMoney($detail['ORDER_TOTAL']); ?>
                                    </td>

                                    <td class="text-right">
                                        <?= $formatMoney($detail['CN_TOTAL_AMOUNT']); ?>
                                    </td>

                                    <td class="text-right table-total">
                                        <?= $formatMoney($detail['PAID_AMOUNT']); ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-right">
                                    TOTAL ALLOCATION
                                </th>

                                <th class="text-right table-total">
                                    <?= $formatMoney($totalAllocation); ?>
                                </th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

                <div class="info-note mt-3">
                    <i class="ti ti-info-circle"></i>

                    <div>
                        Credit Note menampilkan total CN Customer yang sudah APPROVED
                        untuk masing-masing Customer Order. Nilai Allocated Cash In
                        merupakan nominal penerimaan yang dialokasikan oleh transaksi ini.
                    </div>
                </div>

            <?php else: ?>

                <div class="empty-state">
                    <i class="ti ti-folder-open"></i>
                    <strong>Tidak ada Cash In Allocation</strong>
                    <span>
                        Detail alokasi Cash In tidak ditemukan.
                    </span>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <!-- 3. ACTION FOOTER -->
    <div class="action-card">

        <div class="action-description">
            <i class="ti ti-info-circle me-1"></i>
            Cash In Detail:
            <?= html_escape($cash_in['CASH_IN_NO']); ?>
        </div>

        <a href="<?= base_url('cashin'); ?>" class="btn-cashin">
            <i class="ti ti-arrow-left"></i>
            Back to Cash In List
        </a>

    </div>

</div>