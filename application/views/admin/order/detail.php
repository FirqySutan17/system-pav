
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$master   = $order['master'];
$customer = $order['customer_order'] ?? NULL;
$supplier = $order['supplier_order'] ?? NULL;

$approvals = $order['approvals'] ?? [];
$histories = $order['histories'] ?? [];

// =====================================================
// EDIT PERMISSION (FRONTEND VISIBILITY ONLY)
// Backend/model tetap wajib memvalidasi ulang seluruh kondisi.
// =====================================================

$hasPendingCustomerEdit = FALSE;

foreach ($approvals as $approvalItem) {
    if (
        strtoupper(trim((string) ($approvalItem['APPROVAL_TYPE'] ?? ''))) === 'CUSTOMER_ORDER_EDIT' &&
        strtoupper(trim((string) ($approvalItem['APPROVAL_STATUS'] ?? ''))) === 'PENDING'
    ) {
        $hasPendingCustomerEdit = TRUE;
        break;
    }
}

$canEditCustomer = (
    $customer &&
    strtoupper((string) ($master['STATUS'] ?? '')) === 'OPEN' &&
    (float) ($customer['PAID'] ?? 0) <= 0 &&
    strtoupper((string) ($customer['PAYMENT_STATUS'] ?? '')) === 'OPEN' &&
    in_array(
        strtoupper((string) ($customer['APPROVAL_STATUS'] ?? '')),
        ['NOT_REQUIRED', 'APPROVED'],
        TRUE
    ) &&
    !$hasPendingCustomerEdit
);

$canEditSupplier = (
    $supplier &&
    strtoupper((string) ($master['STATUS'] ?? '')) === 'OPEN' &&
    (float) ($supplier['PAID'] ?? 0) <= 0 &&
    strtoupper((string) ($supplier['PAYMENT_STATUS'] ?? '')) === 'OPEN'
);

$formatMoney = function ($value) {
    return number_format((float) $value, 2, ',', '.');
};

$formatDate = function ($value) {
    if (empty($value)) {
        return '-';
    }

    $timestamp = strtotime($value);

    return $timestamp
        ? date('d/m/Y', $timestamp)
        : html_escape($value);
};

$statusBadge = function ($status) {

    $status = strtoupper(trim((string) $status));

    $classes = [
        'OPEN'             => 'success',
        'PENDING APPROVAL' => 'warning',
        'PENDING'          => 'warning',
        'NOT_REQUIRED'     => 'secondary',
        'APPROVED'         => 'success',
        'REJECTED'         => 'danger',
        'CANCELLED'        => 'danger',
        'ACTIVE'           => 'success',
        'INACTIVE'         => 'secondary',
        'PARTIAL'          => 'info',
        'PAID'             => 'success'
    ];

    $class = $classes[$status] ?? 'secondary';

    return '<span class="order-status status-' . $class . '">' .
        html_escape($status !== '' ? $status : '-') .
        '</span>';
};

$displayValue = function ($value) {
    return !empty($value) ? html_escape($value) : '-';
};

$totalDifference = (float) ($master['TOTAL_DIFFERENCE'] ?? 0);
?>

<style>
/* =====================================================
   ORDER DETAIL PAGE
===================================================== */

.order-detail-page {
    color: #243247;
    padding-bottom: 30px;
}

/* PAGE HEADING */

.order-detail-page .page-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin: 90px 0 22px;
}

.order-detail-page .eyebrow {
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

.order-detail-page .page-title {
    margin: 0;
    color: #172b4d;
    font-size: 1.55rem;
    font-weight: 750;
    letter-spacing: -.035em;
}

.order-detail-page .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .92rem;
}

.order-detail-page .breadcrumb {
    background: transparent;
    margin: 0;
    padding: 8px 0;
    font-size: .82rem;
}

.order-detail-page .breadcrumb a {
    color: #526fe5;
    text-decoration: none;
}

/* =====================================================
   DETAIL CARDS
===================================================== */

.order-detail-page .detail-card {
    margin-bottom: 20px;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22, 42, 75, .055);
    overflow: hidden;
}

.order-detail-page .detail-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf1f7;
    background: linear-gradient(180deg, #fff 0%, #fbfcff 100%);
}

.order-detail-page .section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.order-detail-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 11px;
    font-size: 19px;
}

.order-detail-page .icon-master {
    color: #4965e8;
    background: #edf0ff;
}

.order-detail-page .icon-customer {
    color: #087f5b;
    background: #e8f7f0;
}

.order-detail-page .icon-supplier {
    color: #2875b9;
    background: #eaf4ff;
}

.order-detail-page .icon-difference {
    color: #b7791f;
    background: #fff5df;
}

.order-detail-page .icon-approval {
    color: #7651bd;
    background: #f2edff;
}

.order-detail-page .icon-history {
    color: #64748b;
    background: #edf1f6;
}

.order-detail-page .section-title {
    margin: 0;
    color: #243247;
    font-size: .98rem;
    font-weight: 750;
}

.order-detail-page .section-description {
    margin: 3px 0 0;
    color: #8491a4;
    font-size: .76rem;
}

.order-detail-page .detail-card-body {
    padding: 22px;
}

/* =====================================================
   DETAIL INFORMATION
===================================================== */

.order-detail-page .detail-item {
    margin-bottom: 20px;
    min-width: 0;
}

.order-detail-page .detail-item:last-child {
    margin-bottom: 0;
}

.order-detail-page .detail-label {
    display: block;
    margin-bottom: 7px;
    color: #8491a4;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.order-detail-page .detail-value {
    color: #26354d;
    font-size: .9rem;
    font-weight: 650;
    overflow-wrap: anywhere;
}

.order-detail-page .detail-value.large {
    font-size: 1.05rem;
    font-weight: 750;
}

.order-detail-page .detail-value.primary {
    color: #3d58d8;
}

.order-detail-page .detail-value.muted {
    color: #8491a4;
    font-weight: 500;
}

.order-detail-page .detail-divider {
    height: 1px;
    margin: 5px 0 20px;
    background: #edf1f7;
}

/* =====================================================
   STATUS BADGES
===================================================== */

.order-detail-page .order-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 7px;
    font-size: .7rem;
    font-weight: 750;
    line-height: 1.2;
    letter-spacing: .025em;
    white-space: nowrap;
}

.order-detail-page .status-success {
    color: #087f5b;
    background: #e8f7f0;
}

.order-detail-page .status-warning {
    color: #a66b0a;
    background: #fff5df;
}

.order-detail-page .status-danger {
    color: #c03939;
    background: #fff0f0;
}

.order-detail-page .status-info {
    color: #2875b9;
    background: #eaf4ff;
}

.order-detail-page .status-secondary {
    color: #64748b;
    background: #edf1f6;
}

/* =====================================================
   ORDER NUMBER / REFERENCE
===================================================== */

.order-detail-page .order-number {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #4965e8;
    font-weight: 750;
    font-size: .9rem;
}

.order-detail-page .party-name {
    color: #26354d;
    font-weight: 750;
}

.order-detail-page .party-code {
    color: #8491a4;
    font-size: .78rem;
    margin-top: 4px;
}

/* =====================================================
   SUMMARY BOX
===================================================== */

.order-detail-page .summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.order-detail-page .summary-item {
    padding: 16px 17px;
    border: 1px solid #e7edf5;
    border-radius: 12px;
    background: #fbfcff;
    min-width: 0;
}

.order-detail-page .summary-label {
    display: block;
    margin-bottom: 9px;
    color: #8491a4;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.order-detail-page .summary-value {
    display: block;
    color: #26354d;
    font-size: 1.05rem;
    font-weight: 750;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.order-detail-page .summary-item.highlight {
    background: #fff9eb;
    border-color: #f4e3bd;
}

.order-detail-page .summary-item.highlight .summary-value {
    color: #a66b0a;
    font-size: 1.15rem;
}

.order-detail-page .summary-item.positive .summary-value {
    color: #087f5b;
}

.order-detail-page .summary-item.negative .summary-value {
    color: #c03939;
}

/* =====================================================
   TABLE
===================================================== */

.order-detail-page .table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e7edf5;
    border-radius: 11px;
}

.order-detail-page .detail-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    font-size: .81rem;
    white-space: nowrap;
}

.order-detail-page .detail-table thead th {
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

.order-detail-page .detail-table tbody td {
    padding: 14px;
    border: 0;
    border-bottom: 1px solid #edf1f7;
    color: #475569;
    vertical-align: middle;
}

.order-detail-page .detail-table tbody tr:last-child td {
    border-bottom: 0;
}

.order-detail-page .detail-table tbody tr:hover {
    background: #fafbff;
}

.order-detail-page .detail-table .text-right {
    text-align: right;
    font-variant-numeric: tabular-nums;
}

.order-detail-page .table-total {
    color: #263fb7 !important;
    font-weight: 750;
}

.order-detail-page .table-empty {
    padding: 25px 15px !important;
    color: #94a3b8 !important;
    text-align: center;
}

/* =====================================================
   INFO NOTE
===================================================== */

.order-detail-page .info-note {
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

.order-detail-page .info-note i {
    margin-top: 2px;
    color: #5270df;
    font-size: 17px;
}

/* =====================================================
   EMPTY STATE
===================================================== */

.order-detail-page .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 28px 15px;
    border: 1px dashed #dfe6f0;
    border-radius: 11px;
    background: #fbfcff;
    text-align: center;
}

.order-detail-page .empty-state i {
    color: #a0acbd;
    font-size: 27px;
}

.order-detail-page .empty-state strong {
    color: #64748b;
    font-size: .86rem;
}

.order-detail-page .empty-state span {
    color: #94a3b8;
    font-size: .76rem;
}

/* =====================================================
   BACK ACTION
===================================================== */

.order-detail-page .action-card {
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

.order-detail-page .action-description {
    color: #8491a4;
    font-size: .8rem;
}

.order-detail-page .btn-order {
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
    transition: all .15s;
}

.order-detail-page .btn-order:hover {
    background: #f7f9fc;
    color: #334155;
    border-color: #cbd5e1;
}

/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991px) {
    .order-detail-page .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767px) {
    .order-detail-page .page-heading {
        margin-top: 80px;
    }

    .order-detail-page .page-title {
        font-size: 1.3rem;
    }

    .order-detail-page .detail-card-header {
        padding: 15px 16px;
    }

    .order-detail-page .detail-card-body {
        padding: 17px 16px;
    }

    .order-detail-page .summary-grid {
        grid-template-columns: 1fr;
    }

    .order-detail-page .action-card {
        align-items: stretch;
        padding: 16px;
    }

    .order-detail-page .action-card .btn-order {
        width: 100%;
    }
}
</style>


<div class="container-fluid order-detail-page px-2 px-lg-3 py-2">

    <?php if ($this->session->flashdata('order_success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            <?= $this->session->flashdata('order_success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('order_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <?= $this->session->flashdata('order_error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

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
                Order Detail
            </h1>

            <p class="page-subtitle">
                Informasi lengkap Master Order, Customer Order,
                Supplier Order, dan riwayat transaksi.
            </p>

        </div>


        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="<?= base_url('order'); ?>">
                        Order Management
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    <?= html_escape($master['ORDER_NO']); ?>
                </li>

            </ol>

        </nav>

    </div>


    <!-- =================================================
         1. MASTER ORDER
    ================================================== -->

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-heading">

                <div class="section-icon icon-master">
                    <i class="ti ti-file-text"></i>
                </div>

                <div>

                    <h3 class="section-title">
                        Master Order
                    </h3>

                    <p class="section-description">
                        Informasi utama transaksi Order
                    </p>

                </div>

            </div>

            <div>
                <?= $statusBadge($master['STATUS']); ?>
            </div>

        </div>


        <div class="detail-card-body">

            <!-- MASTER INFORMATION -->

            <div class="row">

                <div class="col-md-3">

                    <div class="detail-item">

                        <span class="detail-label">
                            Order Number
                        </span>

                        <div class="detail-value large primary">
                            <i class="ti ti-file-invoice me-1"></i>
                            <?= html_escape($master['ORDER_NO']); ?>
                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="detail-item">

                        <span class="detail-label">
                            Order Date
                        </span>

                        <div class="detail-value">
                            <?= $formatDate($master['ORDER_DATE']); ?>
                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="detail-item">

                        <span class="detail-label">
                            Plant
                        </span>

                        <div class="detail-value">
                            <?= html_escape($master['PLANT']); ?>
                        </div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="detail-item">

                        <span class="detail-label">
                            Created By
                        </span>

                        <div class="detail-value">
                            <?= $displayValue($master['REG_EMP']); ?>
                        </div>

                    </div>

                </div>

            </div>


            <div class="detail-divider"></div>


            <!-- CUSTOMER AND SUPPLIER -->

            <div class="row">

                <div class="col-md-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Customer
                        </span>

                        <div class="party-name">
                            <?= html_escape($master['CUSTOMER_NAME'] ?: '-'); ?>
                        </div>

                        <div class="party-code">
                            <?= html_escape($master['CUST']); ?>
                        </div>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="detail-item">

                        <span class="detail-label">
                            Supplier
                        </span>

                        <div class="party-name">
                            <?= html_escape($master['SUPPLIER_NAME'] ?: '-'); ?>
                        </div>

                        <div class="party-code">
                            <?= html_escape($master['SUPPLIER']); ?>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =================================================
         2. CUSTOMER ORDER
    ================================================== -->

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-heading">

                <div class="section-icon icon-customer">
                    <i class="ti ti-shopping-cart"></i>
                </div>

                <div>

                    <h3 class="section-title">
                        Customer Order
                    </h3>

                    <p class="section-description">
                        Detail penjualan dan nilai piutang Customer
                    </p>

                </div>

            </div>

            <?php if ($canEditCustomer): ?>
                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editCustomerOrderModal"
                >
                    <i class="ti ti-edit me-1"></i>
                    Edit Customer Order
                </button>
            <?php endif; ?>

        </div>


        <div class="detail-card-body">

            <?php if ($customer): ?>

                <!-- CUSTOMER ORDER HEADER -->

                <div class="row">

                    <div class="col-md-3">

                        <div class="detail-item">

                            <span class="detail-label">
                                Customer Order Number
                            </span>

                            <div class="detail-value primary large">
                                <?= html_escape($customer['ORDER_CUSTOMER_NO']); ?>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="detail-item">

                            <span class="detail-label">
                                Basis
                            </span>

                            <div class="detail-value">
                                <?= html_escape($customer['BASIS']); ?>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="detail-item">

                            <span class="detail-label">
                                Approval Status
                            </span>

                            <div class="detail-value">
                                <?= $statusBadge($customer['APPROVAL_STATUS']); ?>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="detail-item">

                            <span class="detail-label">
                                Payment Status
                            </span>

                            <div class="detail-value">
                                <?= $statusBadge($customer['PAYMENT_STATUS']); ?>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="detail-divider"></div>


                <!-- CUSTOMER ORDER QUANTITY TABLE -->

                <div class="table-wrap">

                    <table class="detail-table">

                        <thead>

                            <tr>
                                <th class="text-right">Ekor</th>
                                <th class="text-right">KG</th>
                                <th class="text-right">Average BW</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Total</th>
                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <td class="text-right">
                                    <?= $formatMoney($customer['EKOR']); ?>
                                </td>

                                <td class="text-right">
                                    <?= $formatMoney($customer['KG']); ?>
                                </td>

                                <td class="text-right">
                                    <?= $formatMoney($customer['AVG_BW']); ?>
                                </td>

                                <td class="text-right">
                                    <?= $formatMoney($customer['UNIT_PRICE']); ?>
                                </td>

                                <td class="text-right table-total">
                                    <?= $formatMoney($customer['TOTAL']); ?>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- CUSTOMER PAYMENT SUMMARY -->

                <div class="summary-grid mt-3">

                    <div class="summary-item">

                        <span class="summary-label">
                            Total Order
                        </span>

                        <span class="summary-value">
                            <?= $formatMoney($customer['TOTAL']); ?>
                        </span>

                    </div>


                    <div class="summary-item positive">

                        <span class="summary-label">
                            Paid
                        </span>

                        <span class="summary-value">
                            <?= $formatMoney($customer['PAID']); ?>
                        </span>

                    </div>


                    <div class="summary-item highlight">

                        <span class="summary-label">
                            Remaining
                        </span>

                        <span class="summary-value">
                            <?= $formatMoney($customer['REMAIN']); ?>
                        </span>

                    </div>

                </div>


                <div class="info-note mt-3">

                    <i class="ti ti-info-circle"></i>

                    <div>
                        Customer Order merupakan transaksi penjualan
                        dengan metode pembayaran Credit.
                    </div>

                </div>


            <?php else: ?>

                <div class="empty-state">

                    <i class="ti ti-alert-circle"></i>

                    <strong>Customer Order tidak ditemukan</strong>

                    <span>
                        Data Customer Order tidak tersedia untuk Master Order ini.
                    </span>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =================================================
         3. SUPPLIER ORDER
    ================================================== -->

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-heading">

                <div class="section-icon icon-supplier">
                    <i class="ti ti-truck-delivery"></i>
                </div>

                <div>

                    <h3 class="section-title">
                        Supplier Order
                    </h3>

                    <p class="section-description">
                        Detail pembelian dan nilai kewajiban Supplier
                    </p>

                </div>

            </div>

            <?php if ($canEditSupplier): ?>
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editSupplierOrderModal"
                >
                    <i class="ti ti-edit me-1"></i>
                    Edit Supplier Order
                </button>
            <?php endif; ?>

        </div>


        <div class="detail-card-body">

            <?php if ($supplier): ?>

                <!-- SUPPLIER ORDER HEADER -->

                <div class="row">

                    <div class="col-md-4">

                        <div class="detail-item">

                            <span class="detail-label">
                                Supplier Order Number
                            </span>

                            <div class="detail-value primary large">
                                <?= html_escape($supplier['ORDER_SUPPLIER_NO']); ?>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="detail-item">

                            <span class="detail-label">
                                Basis
                            </span>

                            <div class="detail-value">
                                <?= html_escape($supplier['BASIS']); ?>
                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="detail-item">

                            <span class="detail-label">
                                Payment Status
                            </span>

                            <div class="detail-value">
                                <?= $statusBadge($supplier['PAYMENT_STATUS']); ?>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="detail-divider"></div>


                <!-- SUPPLIER ORDER QUANTITY TABLE -->

                <div class="table-wrap">

                    <table class="detail-table">

                        <thead>

                            <tr>
                                <th class="text-right">Ekor</th>
                                <th class="text-right">KG</th>
                                <th class="text-right">Average BW</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Total</th>
                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <td class="text-right">
                                    <?= $formatMoney($supplier['EKOR']); ?>
                                </td>

                                <td class="text-right">
                                    <?= $formatMoney($supplier['KG']); ?>
                                </td>

                                <td class="text-right">
                                    <?= $formatMoney($supplier['AVG_BW']); ?>
                                </td>

                                <td class="text-right">
                                    <?= $formatMoney($supplier['UNIT_PRICE']); ?>
                                </td>

                                <td class="text-right table-total">
                                    <?= $formatMoney($supplier['TOTAL']); ?>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <!-- SUPPLIER PAYMENT SUMMARY -->

                <div class="summary-grid mt-3">

                    <div class="summary-item">

                        <span class="summary-label">
                            Total Order
                        </span>

                        <span class="summary-value">
                            <?= $formatMoney($supplier['TOTAL']); ?>
                        </span>

                    </div>


                    <div class="summary-item positive">

                        <span class="summary-label">
                            Paid
                        </span>

                        <span class="summary-value">
                            <?= $formatMoney($supplier['PAID']); ?>
                        </span>

                    </div>


                    <div class="summary-item highlight">

                        <span class="summary-label">
                            Remaining
                        </span>

                        <span class="summary-value">
                            <?= $formatMoney($supplier['REMAIN']); ?>
                        </span>

                    </div>

                </div>


            <?php else: ?>

                <div class="empty-state">

                    <i class="ti ti-alert-circle"></i>

                    <strong>Supplier Order tidak ditemukan</strong>

                    <span>
                        Data Supplier Order tidak tersedia untuk Master Order ini.
                    </span>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =================================================
         4. PRICE DIFFERENCE
    ================================================== -->

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-heading">

                <div class="section-icon icon-difference">
                    <i class="ti ti-calculator"></i>
                </div>

                <div>

                    <h3 class="section-title">
                        Price Difference
                    </h3>

                    <p class="section-description">
                        Ringkasan selisih harga jual dan harga beli
                    </p>

                </div>

            </div>

        </div>


        <div class="detail-card-body">

            <div class="summary-grid">


                <div class="summary-item">

                    <span class="summary-label">
                        Selling Price
                    </span>

                    <span class="summary-value">
                        <?= $formatMoney($master['SELLING_PRICE']); ?>
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Purchase Price
                    </span>

                    <span class="summary-value">
                        <?= $formatMoney($master['PURCHASE_PRICE']); ?>
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Difference Price
                    </span>

                    <span class="summary-value">
                        <?= $formatMoney($master['DIFFERENCE_PRICE']); ?>
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Sales Total
                    </span>

                    <span class="summary-value">
                        <?= $formatMoney($master['SALES_TOTAL']); ?>
                    </span>

                </div>


                <div class="summary-item">

                    <span class="summary-label">
                        Purchase Total
                    </span>

                    <span class="summary-value">
                        <?= $formatMoney($master['PURCHASE_TOTAL']); ?>
                    </span>

                </div>


                <div class="summary-item highlight <?= $totalDifference > 0 ? 'positive' : ($totalDifference < 0 ? 'negative' : ''); ?>">

                    <span class="summary-label">
                        Total Difference
                    </span>

                    <span class="summary-value">
                        <?= $formatMoney($master['TOTAL_DIFFERENCE']); ?>
                    </span>

                </div>

            </div>


            <div class="difference-note mt-3">

                <i class="ti ti-info-circle me-1"></i>

                Total Difference merupakan selisih Sales Total
                dan Purchase Total, bukan perhitungan net profit.

            </div>

        </div>

    </section>


    <!-- =================================================
         5. APPROVAL INFORMATION
    ================================================== -->

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-heading">

                <div class="section-icon icon-approval">
                    <i class="ti ti-shield-check"></i>
                </div>

                <div>

                    <h3 class="section-title">
                        Approval Information
                    </h3>

                    <p class="section-description">
                        Informasi proses dan status persetujuan Order
                    </p>

                </div>

            </div>

            <span class="order-status status-secondary">
                <?= count($approvals); ?> Record(s)
            </span>

        </div>


        <div class="detail-card-body">

            <?php if (!empty($approvals)): ?>

                <div class="table-wrap">

                    <table class="detail-table">

                        <thead>

                            <tr>
                                <th>Approval Type</th>
                                <th>Reference ID</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th>Requested At</th>
                                <th>Approved By</th>
                                <th>Approved At</th>
                                <th>Remark</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($approvals as $approval): ?>

                                <tr>

                                    <td>
                                        <strong>
                                            <?= html_escape($approval['APPROVAL_TYPE']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= $displayValue($approval['REFERENCE_ID']); ?>
                                    </td>

                                    <td>
                                        <?= $statusBadge($approval['APPROVAL_STATUS']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($approval['REQUESTED_BY']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($approval['REQUESTED_AT']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($approval['APPROVED_BY']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($approval['APPROVED_AT']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($approval['APPROVAL_REMARK']); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="empty-state">

                    <i class="ti ti-check-circle"></i>

                    <strong>Tidak ada approval</strong>

                    <span>
                        Tidak ada approval yang diperlukan untuk Order ini.
                    </span>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =================================================
         6. ORDER HISTORY
    ================================================== -->

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-heading">

                <div class="section-icon icon-history">
                    <i class="ti ti-history"></i>
                </div>

                <div>

                    <h3 class="section-title">
                        Order History
                    </h3>

                    <p class="section-description">
                        Riwayat perubahan dan aktivitas transaksi Order
                    </p>

                </div>

            </div>

            <span class="order-status status-secondary">
                <?= count($histories); ?> Record(s)
            </span>

        </div>


        <div class="detail-card-body">

            <?php if (!empty($histories)): ?>

                <div class="table-wrap">

                    <table class="detail-table">

                        <thead>

                            <tr>
                                <th>Date</th>
                                <th>Reference Type</th>
                                <th>Reference ID</th>
                                <th>Action</th>
                                <th>Remark</th>
                                <th>Action By</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($histories as $history): ?>

                                <tr>

                                    <td>
                                        <?= $displayValue($history['ACTION_DATE']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($history['REFERENCE_TYPE']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($history['REFERENCE_ID']); ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?= $displayValue($history['ACTION_TYPE']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= $displayValue($history['REMARK']); ?>
                                    </td>

                                    <td>
                                        <?= $displayValue($history['ACTION_BY']); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="empty-state">

                    <i class="ti ti-history"></i>

                    <strong>Belum ada history</strong>

                    <span>
                        Belum ada riwayat aktivitas untuk Order ini.
                    </span>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- =================================================
         7. ACTION FOOTER
    ================================================== -->
    
    <div class="action-card">

        <div class="action-description">
            <i class="ti ti-info-circle me-1"></i>
            Detail transaksi
            <?= html_escape($master['ORDER_NO']); ?>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <?php if (in_array($master['STATUS'], [
                'OPEN',
                'PENDING APPROVAL'
            ], TRUE)): ?>

                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#cancelOrderModal"
                >
                    <i class="ti ti-x-circle me-1"></i>
                    Cancel Order
                </button>

            <?php endif; ?>

            <a
                href="<?= base_url('order'); ?>"
                class="btn-order"
            >
                <i class="ti ti-arrow-left"></i>
                Back to Order List
            </a>

        </div>

    </div>


    <?php if (in_array($master['STATUS'], [
        'OPEN',
        'PENDING APPROVAL'
    ], TRUE)): ?>

    <div
        class="modal fade"
        id="cancelOrderModal"
        tabindex="-1"
        aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">
                        <i class="ti ti-alert-triangle text-danger me-2"></i>
                        Cancel Master Order
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <?= form_open(
                    'order/cancel/' . rawurlencode($master['ORDER_NO'])
                ); ?>

                    <div class="modal-body">

                        <div class="alert alert-warning">
                            <strong>Attention!</strong>
                            <p class="mb-0 mt-1">
                                Order yang dibatalkan tidak dapat diproses kembali.
                                Pastikan Customer Order dan Supplier Order
                                belum memiliki pembayaran.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Order Number
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= html_escape($master['ORDER_NO']); ?>"
                                readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Cancellation Reason
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="remark"
                                class="form-control"
                                rows="4"
                                maxlength="1000"
                                placeholder="Enter reason for cancellation..."
                                required
                            ></textarea>

                            <small class="text-muted">
                                Maximum 1000 characters.
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this Master Order?');"
                        >
                            <i class="ti ti-x-circle me-1"></i>
                            Confirm Cancellation
                        </button>

                    </div>

                <?= form_close(); ?>

            </div>

        </div>
    </div>



    <!-- =================================================
         8. EDIT CUSTOMER ORDER MODAL
    ================================================== -->

    <?php if ($canEditCustomer): ?>
    <div
        class="modal fade"
        id="editCustomerOrderModal"
        tabindex="-1"
        aria-labelledby="editCustomerOrderModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerOrderModalLabel">
                        <i class="ti ti-shopping-cart text-success me-2"></i>
                        Edit Customer Order
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <?= form_open(
                    'order/edit_customer_action/' . rawurlencode($master['ORDER_NO']),
                    ['id' => 'formEditCustomerOrder']
                ); ?>
                    <div class="modal-body">

                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-1"></i>
                            Jika Credit Limit mencukupi, perubahan akan langsung disimpan.
                            Jika tidak mencukupi, perubahan akan diajukan untuk approval.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order Number</label>
                            <input type="text" class="form-control"
                                   value="<?= html_escape($master['ORDER_NO']); ?>" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Basis <span class="text-danger">*</span></label>
                                <select name="BASIS" id="customer_edit_basis" class="form-select" required>
                                    <option value="EKOR" <?= strtoupper($customer['BASIS']) === 'EKOR' ? 'selected' : ''; ?>>EKOR</option>
                                    <option value="BW" <?= strtoupper($customer['BASIS']) === 'BW' ? 'selected' : ''; ?>>BW</option>
                                </select>
                                <small class="text-muted">Harus sama dengan Basis Supplier Order.</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Ekor <span class="text-danger">*</span></label>
                                <input type="number" name="EKOR" id="customer_edit_ekor"
                                       class="form-control" min="0.0001" step="any"
                                       value="<?= html_escape($customer['EKOR']); ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">KG <span class="text-danger">*</span></label>
                                <input type="number" name="KG" id="customer_edit_kg"
                                       class="form-control" min="0.0001" step="any"
                                       value="<?= html_escape($customer['KG']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Average BW</label>
                                <input type="text" id="customer_edit_avg_bw" class="form-control"
                                       value="<?= html_escape($customer['AVG_BW']); ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" name="UNIT_PRICE" id="customer_edit_unit_price"
                                       class="form-control" min="0.01" step="any"
                                       value="<?= html_escape($customer['UNIT_PRICE']); ?>" required>
                            </div>
                        </div>

                        <div class="summary-item highlight mb-3">
                            <span class="summary-label">New Total</span>
                            <span class="summary-value" id="customer_edit_total">
                                <?= $formatMoney($customer['TOTAL']); ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Change Remark <span class="text-danger">*</span>
                            </label>
                            <textarea name="remark" class="form-control" rows="3" maxlength="1000"
                                      placeholder="Enter reason for changing Customer Order..." required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-device-floppy me-1"></i> Submit Changes
                        </button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <!-- =================================================
         9. EDIT SUPPLIER ORDER MODAL
    ================================================== -->

    <?php if ($canEditSupplier): ?>
    <div
        class="modal fade"
        id="editSupplierOrderModal"
        tabindex="-1"
        aria-labelledby="editSupplierOrderModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">

                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierOrderModalLabel">
                        <i class="ti ti-truck-delivery text-primary me-2"></i>
                        Edit Supplier Order
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <?= form_open(
                    'order/edit_supplier_action/' . rawurlencode($master['ORDER_NO']),
                    ['id' => 'formEditSupplierOrder']
                ); ?>
                    <div class="modal-body">

                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-1"></i>
                            Perubahan Supplier Order akan langsung disimpan setelah validasi berhasil.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order Number</label>
                            <input type="text" class="form-control"
                                   value="<?= html_escape($master['ORDER_NO']); ?>" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Basis <span class="text-danger">*</span></label>
                                <select name="BASIS" id="supplier_edit_basis" class="form-select" required>
                                    <option value="EKOR" <?= strtoupper($supplier['BASIS']) === 'EKOR' ? 'selected' : ''; ?>>EKOR</option>
                                    <option value="BW" <?= strtoupper($supplier['BASIS']) === 'BW' ? 'selected' : ''; ?>>BW</option>
                                </select>
                                <small class="text-muted">Harus sama dengan Basis Customer Order.</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Ekor <span class="text-danger">*</span></label>
                                <input type="number" name="EKOR" id="supplier_edit_ekor"
                                       class="form-control" min="0.0001" step="any"
                                       value="<?= html_escape($supplier['EKOR']); ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">KG <span class="text-danger">*</span></label>
                                <input type="number" name="KG" id="supplier_edit_kg"
                                       class="form-control" min="0.0001" step="any"
                                       value="<?= html_escape($supplier['KG']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Average BW</label>
                                <input type="text" id="supplier_edit_avg_bw" class="form-control"
                                       value="<?= html_escape($supplier['AVG_BW']); ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" name="UNIT_PRICE" id="supplier_edit_unit_price"
                                       class="form-control" min="0.01" step="any"
                                       value="<?= html_escape($supplier['UNIT_PRICE']); ?>" required>
                            </div>
                        </div>

                        <div class="summary-item highlight mb-3">
                            <span class="summary-label">New Total</span>
                            <span class="summary-value" id="supplier_edit_total">
                                <?= $formatMoney($supplier['TOTAL']); ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Change Remark <span class="text-danger">*</span>
                            </label>
                            <textarea name="remark" class="form-control" rows="3" maxlength="1000"
                                      placeholder="Enter reason for changing Supplier Order..." required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Submit Changes
                        </button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <!-- =================================================
         10. INLINE JAVASCRIPT
    ================================================== -->

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        function formatOrderMoney(value) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(Number.isFinite(value) ? value : 0);
        }

        function formatOrderDecimal(value) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 4
            }).format(Number.isFinite(value) ? value : 0);
        }

        function calculateOrder(config) {
            const basis = document.getElementById(config.basis);
            const ekor = document.getElementById(config.ekor);
            const kg = document.getElementById(config.kg);
            const unitPrice = document.getElementById(config.unitPrice);
            const avgBW = document.getElementById(config.avgBW);
            const total = document.getElementById(config.total);

            if (!basis || !ekor || !kg || !unitPrice || !avgBW || !total) {
                return;
            }

            const ekorValue = parseFloat(ekor.value) || 0;
            const kgValue = parseFloat(kg.value) || 0;
            const priceValue = parseFloat(unitPrice.value) || 0;
            const avgValue = ekorValue > 0 ? kgValue / ekorValue : 0;
            const totalValue = basis.value === 'EKOR'
                ? ekorValue * priceValue
                : kgValue * priceValue;

            avgBW.value = formatOrderDecimal(avgValue);
            total.textContent = formatOrderMoney(totalValue);
        }

        const orderCalculations = [
            {
                form: 'formEditCustomerOrder',
                basis: 'customer_edit_basis',
                ekor: 'customer_edit_ekor',
                kg: 'customer_edit_kg',
                unitPrice: 'customer_edit_unit_price',
                avgBW: 'customer_edit_avg_bw',
                total: 'customer_edit_total',
                label: 'Customer Order'
            },
            {
                form: 'formEditSupplierOrder',
                basis: 'supplier_edit_basis',
                ekor: 'supplier_edit_ekor',
                kg: 'supplier_edit_kg',
                unitPrice: 'supplier_edit_unit_price',
                avgBW: 'supplier_edit_avg_bw',
                total: 'supplier_edit_total',
                label: 'Supplier Order'
            }
        ];

        orderCalculations.forEach(function (config) {
            const form = document.getElementById(config.form);
            if (!form) return;

            [config.basis, config.ekor, config.kg, config.unitPrice].forEach(function (id) {
                const field = document.getElementById(id);
                if (!field) return;
                field.addEventListener('input', function () {
                    calculateOrder(config);
                });
                field.addEventListener('change', function () {
                    calculateOrder(config);
                });
            });

            calculateOrder(config);

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    form.reportValidity();
                    return;
                }

                const confirmed = window.confirm(
                    'Are you sure you want to submit ' + config.label + ' changes?'
                );

                if (!confirmed) {
                    event.preventDefault();
                }
            });
        });
    });
    </script>
    <?php endif; ?>


</div>
