
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$payment = $payment ?? [];
$supplier_orders = $supplier_orders ?? [];
$payment_details = $payment['details'] ?? [];

$initial_allocations = [];
foreach ($payment_details as $detail) {
    $initial_allocations[(int) $detail['ORDER_SUPPLIER_ID']] =
        (float) $detail['PAID_AMOUNT'];
}

$initial_total = (float) ($payment['TOTAL_PAYMENT'] ?? 0);
?>

<style>
/* =====================================================
   PAYMENT EDIT PAGE
===================================================== */

.payment-edit-page {
    color: #243247;
    padding-bottom: 30px;
}

/* PAGE HEADING */

.payment-edit-page .page-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin: 90px 0 22px;
}

.payment-edit-page .eyebrow {
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

.payment-edit-page .page-title {
    margin: 0;
    color: #172b4d;
    font-size: 1.55rem;
    font-weight: 750;
    letter-spacing: -.035em;
}

.payment-edit-page .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .92rem;
}

.payment-edit-page .breadcrumb {
    background: transparent;
    margin: 0;
    padding: 8px 0;
    font-size: .82rem;
}

.payment-edit-page .breadcrumb a {
    color: #526fe5;
    text-decoration: none;
}

/* FORM CARD */

.payment-edit-page .form-card {
    margin-bottom: 20px;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22, 42, 75, .055);
    overflow: visible;
}

.payment-edit-page .form-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf1f7;
    border-radius: 16px 16px 0 0;
    background: linear-gradient(180deg, #fff 0%, #fbfcff 100%);
}

.payment-edit-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 11px;
    font-size: 19px;
}

.payment-edit-page .icon-payment {
    color: #4965e8;
    background: #edf0ff;
}

.payment-edit-page .icon-outstanding {
    color: #087f5b;
    background: #e8f7f0;
}

.payment-edit-page .section-title {
    margin: 0;
    color: #243247;
    font-size: .98rem;
    font-weight: 750;
}

.payment-edit-page .section-description {
    margin: 3px 0 0;
    color: #8491a4;
    font-size: .76rem;
}

.payment-edit-page .form-card-body {
    padding: 22px;
}

/* FORM ELEMENTS */

.payment-edit-page .form-group {
    margin-bottom: 18px;
}

.payment-edit-page .form-label {
    display: block;
    margin-bottom: 7px;
    color: #64748b;
    font-size: .75rem;
    font-weight: 750;
    letter-spacing: .035em;
    text-transform: uppercase;
}

.payment-edit-page .required {
    color: #dc3545;
}

.payment-edit-page .form-control,
.payment-edit-page .form-select {
    width: 100%;
    min-width: 0;
    min-height: 44px;
    box-sizing: border-box;
    border: 1px solid #dfe6f0;
    border-radius: 10px;
    box-shadow: none;
    color: #334155;
    background-color: #fff;
    font-size: .86rem;
}

.payment-edit-page .form-control:focus,
.payment-edit-page .form-select:focus {
    border-color: #8aa4ff;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .12);
}

.payment-edit-page .form-control[readonly] {
    background: #f7f9fc;
    color: #64748b;
    font-weight: 600;
}

.payment-edit-page .form-control::placeholder {
    color: #a0acbd;
}

/* SELECT2 */

.payment-edit-page .select2-container {
    width: 100% !important;
    min-width: 0;
}

.payment-edit-page .select2-container--default
.select2-selection--single {
    height: 44px !important;
    border: 1px solid #dfe6f0 !important;
    border-radius: 10px !important;
    background: #fff !important;
    box-shadow: none !important;
}

.payment-edit-page .select2-container--default
.select2-selection--single
.select2-selection__rendered {
    height: 42px;
    line-height: 42px !important;
    padding-left: 13px !important;
    padding-right: 35px !important;
    color: #334155 !important;
    font-size: .86rem;
}

.payment-edit-page .select2-container--default
.select2-selection--single
.select2-selection__placeholder {
    color: #a0acbd !important;
}

.payment-edit-page .select2-container--default
.select2-selection--single
.select2-selection__arrow {
    height: 42px !important;
    right: 8px !important;
}

.payment-edit-page .select2-container--default.select2-container--focus
.select2-selection--single,
.payment-edit-page .select2-container--default.select2-container--open
.select2-selection--single {
    border-color: #8aa4ff !important;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .12) !important;
}

.payment-edit-page .select2-dropdown {
    border: 1px solid #dfe6f0 !important;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(22, 42, 75, .10);
    z-index: 9999;
}

.payment-edit-page .select2-results__option {
    padding: 9px 13px !important;
    color: #334155;
    font-size: .84rem;
}

/* SUMMARY */

.payment-edit-page .summary-card {
    height: 100%;
    min-height: 112px;
    padding: 17px 19px;
    border: 1px solid #e7edf5;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 6px 20px rgba(22, 42, 75, .045);
}

.payment-edit-page .summary-label {
    display: block;
    margin-bottom: 10px;
    color: #8491a4;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .045em;
    text-transform: uppercase;
}

.payment-edit-page .summary-value {
    display: block;
    color: #243247;
    font-size: 1.3rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.payment-edit-page .summary-outstanding {
    background: #f5f8ff;
    border-color: #dce7ff;
}

.payment-edit-page .summary-outstanding .summary-value {
    color: #4965e8;
}

.payment-edit-page .summary-payment {
    background: #f0faf5;
    border-color: #cceedd;
}

.payment-edit-page .summary-payment .summary-value {
    color: #087f5b;
}

.payment-edit-page .summary-unallocated {
    background: #fff9eb;
    border-color: #f4e3bd;
}

.payment-edit-page .summary-unallocated .summary-value {
    color: #a66b0a;
}

/* PAYMENT INPUT */

.payment-edit-page .payment-input-card {
    padding: 17px;
    border: 1px solid #e7edf5;
    border-radius: 12px;
    background: #fbfcff;
}

.payment-edit-page .allocation-info {
    display: flex;
    align-items: center;
    min-height: 44px;
    padding: 10px 13px;
    border: 1px solid #dce7ff;
    border-radius: 10px;
    background: #f5f8ff;
    color: #52658b;
    font-size: .81rem;
}

/* TABLE */

.payment-edit-page .table-responsive {
    border: 1px solid #e7edf5;
    border-radius: 12px;
}

.payment-edit-page .payment-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    font-size: .82rem;
}

.payment-edit-page .payment-table thead th {
    padding: 13px 12px;
    background: #f7f9fc;
    color: #64748b;
    border-bottom: 1px solid #e7edf5;
    font-size: .73rem;
    font-weight: 750;
    letter-spacing: .025em;
    white-space: nowrap;
}

.payment-edit-page .payment-table tbody td {
    padding: 12px;
    color: #475569;
    border-bottom: 1px solid #edf1f7;
    vertical-align: middle;
}

.payment-edit-page .payment-table tbody tr:last-child td {
    border-bottom: 0;
}

.payment-edit-page .payment-table tfoot th {
    padding: 13px 12px;
    background: #f5f8ff;
    color: #243247;
    border-top: 1px solid #dfe7fb;
    font-weight: 800;
    white-space: nowrap;
}

.payment-edit-page .payment-table .text-right {
    text-align: right;
    font-variant-numeric: tabular-nums;
}

.payment-edit-page .manual-allocation {
    min-width: 130px;
    min-height: 38px;
    text-align: right;
}

.payment-edit-page .fifo-allocation {
    display: block;
    min-width: 110px;
    color: #087f5b;
    font-weight: 750;
    font-variant-numeric: tabular-nums;
}

.payment-edit-page .order-count {
    display: inline-flex;
    align-items: center;
    padding: 6px 11px;
    border-radius: 20px;
    background: #edf0ff;
    color: #4965e8;
    font-size: .73rem;
    font-weight: 750;
}

/* INFO NOTE */

.payment-edit-page .info-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    margin-bottom: 18px;
    border: 1px solid #dce7ff;
    border-radius: 10px;
    background: #f5f8ff;
    color: #52658b;
    font-size: .8rem;
    line-height: 1.6;
}

.payment-edit-page .info-note i {
    margin-top: 2px;
    color: #5270df;
}

/* ACTION FOOTER */

.payment-edit-page .action-card {
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

.payment-edit-page .action-description {
    color: #8491a4;
    font-size: .8rem;
}

.payment-edit-page .action-buttons {
    display: flex;
    align-items: center;
    gap: 9px;
}

.payment-edit-page .btn-payment {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 44px;
    padding: 0 18px;
    border-radius: 10px;
    font-size: .84rem;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: all .15s;
}

.payment-edit-page .btn-back {
    border: 1px solid #dfe6f0;
    background: #fff;
    color: #64748b;
}

.payment-edit-page .btn-save {
    border: 0;
    background: linear-gradient(135deg, #627dff, #4965e8);
    color: #fff;
    box-shadow: 0 5px 12px rgba(79, 103, 232, .2);
}

.payment-edit-page .btn-save:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(79, 103, 232, .25);
}

.payment-edit-page .btn-save:disabled {
    opacity: .55;
    cursor: not-allowed;
    transform: none;
}

/* RESPONSIVE */

@media (max-width: 767px) {
    .payment-edit-page .page-heading {
        margin-top: 80px;
    }

    .payment-edit-page .page-title {
        font-size: 1.3rem;
    }

    .payment-edit-page .form-card-header {
        padding: 15px 16px;
    }

    .payment-edit-page .form-card-body {
        padding: 17px 16px;
    }

    .payment-edit-page .action-card {
        align-items: stretch;
        padding: 16px;
    }

    .payment-edit-page .action-buttons {
        width: 100%;
    }

    .payment-edit-page .action-buttons .btn-payment {
        flex: 1;
    }
}

@media (max-width: 480px) {
    .payment-edit-page .action-buttons {
        flex-direction: column-reverse;
    }

    .payment-edit-page .action-buttons .btn-payment {
        width: 100%;
    }
}
</style>

<div class="container-fluid payment-edit-page px-2 px-lg-3 py-2">

    <!-- PAGE HEADING -->
    <div class="page-heading">

        <div>
            <div class="eyebrow">
                <i class="ti ti-wallet"></i>
                Accounting
            </div>

            <h1 class="page-title">Edit Payment</h1>

            <p class="page-subtitle">
                Update supplier payment information and allocation to outstanding Supplier Orders.
            </p>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('payment'); ?>">
                        Payment Supplier
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Edit
                </li>
            </ol>
        </nav>

    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($this->session->flashdata('payment_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-1"></i>
            <?= $this->session->flashdata('payment_error'); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form
        id="paymentForm"
        method="POST"
        action="<?= base_url('payment/update/' . (int) $payment['PAYMENT_ID']); ?>"
        autocomplete="off"
    >

        <?php if (config_item('csrf_protection')): ?>
            <input
                type="hidden"
                name="<?= $this->security->get_csrf_token_name(); ?>"
                value="<?= $this->security->get_csrf_hash(); ?>"
            >
        <?php endif; ?>

        <input type="hidden" name="PAYMENT_ID" value="<?= (int) $payment['PAYMENT_ID']; ?>">

        <!-- 1. PAYMENT INFORMATION -->
        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-payment">
                    <i class="ti ti-wallet"></i>
                </div>

                <div>
                    <h3 class="section-title">Payment Information</h3>
                    <p class="section-description">
                        General information for supplier payment
                    </p>
                </div>

            </div>

            <div class="form-card-body">

                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Payment Number</label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= html_escape($payment['PAYMENT_NO']); ?>"
                                readonly
                            >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">
                                Payment Date <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                name="PAYMENT_DATE"
                                id="PAYMENT_DATE"
                                class="form-control"
                                value="<?= html_escape($payment['PAYMENT_DATE']); ?>"
                                required
                            >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Supplier <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= html_escape($payment['SUPPLIER'] . ' - ' . $payment['SUPPLIER_NAME']); ?>"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="SUPPLIER"
                                id="SUPPLIER"
                                value="<?= html_escape($payment['SUPPLIER']); ?>"
                            >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">
                                Payment Method <span class="required">*</span>
                            </label>

                            <select
                                name="PAYMENT_METHOD"
                                id="PAYMENT_METHOD"
                                class="form-control"
                                required
                            >
                                <option value="TRANSFER" <?= $payment['PAYMENT_METHOD'] === 'TRANSFER' ? 'selected' : ''; ?>>TRANSFER</option>
                                <option value="CASH" <?= $payment['PAYMENT_METHOD'] === 'CASH' ? 'selected' : ''; ?>>CASH</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">
                                Payment Type <span class="required">*</span>
                            </label>

                            <select
                                name="PAYMENT_TYPE"
                                id="PAYMENT_TYPE"
                                class="form-control"
                                required
                            >
                                <option value="FIFO" <?= $payment['PAYMENT_TYPE'] === 'FIFO' ? 'selected' : ''; ?>>FIFO</option>
                                <option value="MANUAL" <?= $payment['PAYMENT_TYPE'] === 'MANUAL' ? 'selected' : ''; ?>>MANUAL</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label class="form-label">Slip No</label>

                            <input
                                type="text"
                                name="SLIP_NO"
                                id="SLIP_NO"
                                class="form-control"
                                maxlength="100"
                                placeholder="Enter Slip Number"
                                value="<?= html_escape($payment['SLIP_NO'] ?? ''); ?>"
                            >
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group mb-md-0">
                            <label class="form-label">Remark</label>

                            <input
                                type="text"
                                name="REMARK"
                                id="REMARK"
                                class="form-control"
                                placeholder="Payment remark"
                                value="<?= html_escape($payment['REMARK'] ?? ''); ?>"
                            >
                        </div>
                    </div>

                </div>

            </div>

        </section>

        <!-- 2. PAYMENT SUMMARY -->
        <div class="row mb-3">

            <div class="col-md-4 mb-3 mb-md-0">
                <div class="summary-card summary-outstanding">
                    <span class="summary-label">
                        <i class="ti ti-file-invoice me-1"></i>
                        Total Outstanding
                    </span>

                    <span class="summary-value" id="summaryOutstanding">
                        0
                    </span>
                </div>
            </div>

            <div class="col-md-4 mb-3 mb-md-0">
                <div class="summary-card summary-payment">
                    <span class="summary-label">
                        <i class="ti ti-cash me-1"></i>
                        Total Payment
                    </span>

                    <span class="summary-value" id="summaryPayment">
                        0
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="summary-card summary-unallocated">
                    <span class="summary-label">
                        <i class="ti ti-calculator me-1"></i>
                        Unallocated Payment
                    </span>

                    <span class="summary-value" id="summaryAllocation">
                        0
                    </span>
                </div>
            </div>

        </div>

        <!-- 3. OUTSTANDING SUPPLIER ORDERS -->
        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-outstanding">
                    <i class="ti ti-list-details"></i>
                </div>

                <div class="flex-grow-1">
                    <h3 class="section-title">Outstanding Supplier Orders</h3>
                    <p class="section-description">
                        Select and allocate outstanding Supplier Orders for this payment
                    </p>
                </div>

                <span id="orderCount" class="order-count">
                    0 Orders
                </span>

            </div>

            <div class="form-card-body">

                <div class="row">

                    <div class="col-md-4">
                        <div class="payment-input-card mb-3 mb-md-0">
                            <div class="form-group mb-0">
                                <label class="form-label">
                                    Total Payment <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="TOTAL_PAYMENT_DISPLAY"
                                    class="form-control text-right"
                                    placeholder="0"
                                    autocomplete="off"
                                    value="<?= $payment['PAYMENT_TYPE'] === 'FIFO' ? number_format($initial_total, 0, ',', '.') : ''; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="TOTAL_PAYMENT"
                                    id="TOTAL_PAYMENT"
                                    value="<?= $initial_total; ?>"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="payment-input-card">
                            <label class="form-label">
                                Allocation Information
                            </label>

                            <div id="allocationInfo" class="allocation-info">
                                Select Supplier to load outstanding orders.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="info-note mt-3">
                    <i class="ti ti-info-circle"></i>

                    <div>
                        <strong>Payment Allocation</strong><br>

                        FIFO automatically allocates payment to the oldest
                        Supplier Order first. Manual allows you to enter
                        allocation amounts for each Supplier Order.
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="payment-table" id="supplierOrderTable">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Order Date</th>
                                <th>Master Order</th>
                                <th>Supplier Order</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">CN</th>
                                <th class="text-right">Paid</th>
                                <th class="text-right">Outstanding</th>
                                <th class="text-right">Allocation</th>
                            </tr>
                        </thead>

                        <tbody id="supplierOrderBody">
                            <tr>
                                <td colspan="9"
                                    class="text-center text-muted py-4">
                                    Select Supplier first.
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">TOTAL</th>
                                <th class="text-right" id="footerTotal">0</th>
                                <th class="text-right" id="footerCN">0</th>
                                <th class="text-right" id="footerPaid">0</th>
                                <th class="text-right" id="footerRemain">0</th>
                                <th class="text-right" id="footerAllocation">0</th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </section>

        <!-- 4. ACTION FOOTER -->
        <div class="action-card">

            <div class="action-description">
                <i class="ti ti-shield-check me-1"></i>
                Please verify the payment information and allocation before updating.
            </div>

            <div class="action-buttons">

                <a
                    href="<?= base_url('payment'); ?>"
                    class="btn-payment btn-back"
                    id="btnBack"
                >
                    <i class="ti ti-arrow-left"></i>
                    Back
                </a>

                <button
                    type="submit"
                    class="btn-payment btn-save"
                    id="btnSavePayment"
                    disabled
                >
                    <i class="ti ti-device-floppy"></i>
                    Update Payment
                </button>

            </div>

        </div>

    </form>

</div>

<script>
    window.PAYMENT_CONFIG = {
        updateUrl: <?= json_encode(base_url('payment/update/' . (int) $payment['PAYMENT_ID'])); ?>,
        listUrl: <?= json_encode(base_url('payment')); ?>,
        supplierOrders: <?= json_encode(
            $supplier_orders,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
        ); ?>,
        initialAllocations: <?= json_encode(
            $initial_allocations,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
        ); ?>
    };
</script>

<script>
    $(document).ready(function () {

        const config = window.PAYMENT_CONFIG;

        let supplierOrders = config.supplierOrders || [];
        let isLoadingOrders = false;
        let initialAllocations = config.initialAllocations || {};
        let isInitialRender = true;

        // =====================================================
        // FORMAT
        // =====================================================

        function formatRupiah(value) {
            value = Math.round(Number(value) || 0);

            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 0
            }).format(value);
        }

        function parseAmount(value) {
            if (value === null || value === undefined) {
                return 0;
            }

            let clean = String(value).replace(/[^\d]/g, '');

            return Number(clean || 0);
        }

        function formatSigned(value) {
            value = Math.round(Number(value) || 0);

            if (value < 0) {
                return '-' + formatRupiah(Math.abs(value));
            }

            return formatRupiah(value);
        }

        function escapeHtml(value) {
            return $('<div>').text(value == null ? '' : String(value)).html();
        }

        // =====================================================
        // SELECT2
        // =====================================================

        if ($.fn.select2) {
            $('.select2-order').select2({
                placeholder: 'Select Supplier',
                allowClear: true,
                width: '100%'
            });
        }

        if ($('#PAYMENT_TYPE').val() === 'MANUAL') {
            $('#TOTAL_PAYMENT_DISPLAY').val('').prop('readonly', true);
        } else {
            $('#TOTAL_PAYMENT_DISPLAY').prop('readonly', false);
        }

        // =====================================================
        // STATE
        // =====================================================

        function getPaymentType() {
            return $('#PAYMENT_TYPE').val();
        }

        function getTotalPayment() {
            // MANUAL: Total Payment berasal dari penjumlahan Allocation
            if (getPaymentType() === 'MANUAL') {
                return supplierOrders.reduce(function (sum, order) {
                    return sum + getAllocationAmount(order.ORDER_SUPPLIER_ID);
                }, 0);
            }

            // FIFO: Total Payment berasal dari input user
            return parseAmount($('#TOTAL_PAYMENT_DISPLAY').val());
        }

        function getAllocationAmount(orderId) {
            const input = $('.manual-allocation[data-order-id="' + orderId + '"]');

            return input.length ? parseAmount(input.val()) : 0;
        }

        function getCurrentAllocations() {
            const allocations = [];

            if (getPaymentType() === 'FIFO') {
                let remainingPayment = getTotalPayment();

                supplierOrders.forEach(function (order) {
                    if (remainingPayment <= 0) {
                        return;
                    }

                    const outstanding = Number(order.REMAIN) || 0;
                    const amount = Math.min(remainingPayment, outstanding);

                    if (amount > 0) {
                        allocations.push({
                            ORDER_SUPPLIER_ID: Number(order.ORDER_SUPPLIER_ID),
                            PAID_AMOUNT: amount
                        });

                        remainingPayment -= amount;
                    }
                });

            } else {
                supplierOrders.forEach(function (order) {
                    const amount = getAllocationAmount(order.ORDER_SUPPLIER_ID);

                    if (amount > 0) {
                        allocations.push({
                            ORDER_SUPPLIER_ID: Number(order.ORDER_SUPPLIER_ID),
                            PAID_AMOUNT: amount
                        });
                    }
                });
            }

            return allocations;
        }

        // =====================================================
        // SUMMARY
        // =====================================================

        function updateSummary() {
            const totalOutstanding = supplierOrders.reduce(
                function (sum, order) {
                    return sum + Number(order.REMAIN || 0);
                },
                0
            );

            const totalPayment = getTotalPayment();

            $('#TOTAL_PAYMENT').val(totalPayment);

            const allocations = getCurrentAllocations();

            const totalAllocated = allocations.reduce(
                function (sum, item) {
                    return sum + Number(item.PAID_AMOUNT || 0);
                },
                0
            );

            const unallocated = totalPayment - totalAllocated;

            $('#summaryOutstanding').text(
                formatRupiah(totalOutstanding)
            );

            $('#summaryPayment').text(
                formatRupiah(totalPayment)
            );

            $('#summaryAllocation').text(
                formatSigned(unallocated)
            );

            $('#footerAllocation').text(
                formatRupiah(totalAllocated)
            );

            $('#allocationInfo').text(
                getPaymentType() === 'FIFO'
                    ? 'FIFO: allocation otomatis berdasarkan Order Date tertua.'
                    : 'MANUAL: masukkan nominal allocation untuk setiap Supplier Order.'
            );

            // Save validation
            const valid =
                !isLoadingOrders &&
                supplierOrders.length > 0 &&
                totalPayment > 0 &&
                totalAllocated === totalPayment;

            $('#btnSavePayment').prop('disabled', !valid);
        }

        // =====================================================
        // RENDER TABLE
        // =====================================================

        function renderSupplierOrders() {
            const tbody = $('#supplierOrderBody');

            tbody.empty();

            if (!supplierOrders.length) {
                tbody.html(`
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            No outstanding Supplier Order found.
                        </td>
                    </tr>
                `);

                $('#orderCount').text('0 Orders');

                updateFooter();
                updateSummary();

                return;
            }

            let total = 0;
            let totalCN = 0;
            let paid = 0;
            let remain = 0;

            const paymentType = getPaymentType();

            supplierOrders.forEach(function (order, index) {

                const orderTotal = Number(order.TOTAL) || 0;
                const orderPaid = Number(order.PAID) || 0;
                const orderRemain = Number(order.REMAIN) || 0;
                const orderCN = Number(order.CN_TOTAL_AMOUNT) || 0;

                totalCN += orderCN;
                total += orderTotal;
                paid += orderPaid;
                remain += orderRemain;

                let allocationHtml = '';

                if (paymentType === 'FIFO') {
                    allocationHtml = `
                        <input
                            type="text"
                            class="form-control form-control-sm fifo-allocation text-right"
                            data-order-id="${Number(order.ORDER_SUPPLIER_ID)}"
                            value="0"
                            readonly
                            tabindex="-1"
                            style="background:#f7f9fc; color:#087f5b; font-weight:700;"
                        >
                    `;
                } else {
                    const initialAmount = isInitialRender
                        ? Number(initialAllocations[order.ORDER_SUPPLIER_ID] || 0)
                        : 0;

                    allocationHtml = `
                        <input
                            type="text"
                            class="form-control input-sm text-right manual-allocation"
                            data-order-id="${Number(order.ORDER_SUPPLIER_ID)}"
                            value="${initialAmount > 0 ? formatRupiah(initialAmount) : ''}"
                            placeholder="0"
                            autocomplete="off"
                        >
                    `;
                }

                tbody.append(`
                    <tr>
                        <td class="text-center">${index + 1}</td>

                        <td>${escapeHtml(order.ORDER_DATE)}</td>

                        <td>${escapeHtml(order.ORDER_NO)}</td>

                        <td>
                            <strong>${escapeHtml(order.ORDER_SUPPLIER_NO)}</strong>
                        </td>

                        <td class="text-right">
                            ${formatRupiah(orderTotal)}
                        </td>

                        <td class="text-right">
                            <span style="
                                color:${orderCN > 0 ? '#b7791f' : '#94a3b8'};
                                font-weight:${orderCN > 0 ? '700' : '400'};
                            ">
                                ${formatRupiah(orderCN)}
                            </span>
                            ${orderCN > 0
                                ? '<span class="badge" style="background:#fff5df;color:#a66b0a;margin-left:4px;">CN</span>'
                                : ''
                            }
                        </td>

                        <td class="text-right">
                            ${formatRupiah(orderPaid)}
                        </td>

                        <td class="text-right">
                            <strong>${formatRupiah(orderRemain)}</strong>
                        </td>

                        <td class="text-right">
                            ${allocationHtml}
                        </td>
                    </tr>
                `);
            });

            $('#orderCount').text(supplierOrders.length + ' Orders');

            $('#footerTotal').text(formatRupiah(total));
            $('#footerCN').text(formatRupiah(totalCN));
            $('#footerPaid').text(formatRupiah(paid));
            $('#footerRemain').text(formatRupiah(remain));

            updateFifoPreview();
            updateSummary();
        }

        function updateFooter() {
            $('#footerTotal').text('0');
            $('#footerCN').text('0');
            $('#footerPaid').text('0');
            $('#footerRemain').text('0');
            $('#footerAllocation').text('0');
        }

        // =====================================================
        // FIFO PREVIEW
        // =====================================================

        function updateFifoPreview() {
            if (getPaymentType() !== 'FIFO') {
                return;
            }

            let remainingPayment = getTotalPayment();

            supplierOrders.forEach(function (order) {

                const outstanding = Number(order.REMAIN) || 0;

                const allocation = Math.min(
                    remainingPayment,
                    outstanding
                );

                const orderId = Number(order.ORDER_SUPPLIER_ID);

                $('.fifo-allocation[data-order-id="' + orderId + '"]').val(formatRupiah(allocation));

                remainingPayment -= allocation;
            });
        }

        // Supplier Order sudah disediakan Controller dalam posisi outstanding virtual.
        // Supplier dikunci sehingga tidak perlu AJAX reload.

        // =====================================================
        // PAYMENT TYPE CHANGE
        // =====================================================

        $('#PAYMENT_TYPE').on('change', function () {

            const paymentType = $(this).val();

            if (paymentType === 'MANUAL') {

                // Kosongkan Total Payment ketika berpindah ke Manual
                $('#TOTAL_PAYMENT_DISPLAY').val('').prop('readonly', true);
                $('#TOTAL_PAYMENT').val(0);

            } else {

                // FIFO: Total Payment dapat diinput user
                $('#TOTAL_PAYMENT_DISPLAY').prop('readonly', false);

            }

            renderSupplierOrders();
            updateSummary();
        });

        // =====================================================
        // TOTAL PAYMENT INPUT
        // =====================================================

        $('#TOTAL_PAYMENT_DISPLAY').on('input', function () {
            const amount = parseAmount($(this).val());

            $(this).val(
                amount > 0 ? formatRupiah(amount) : ''
            );

            $('#TOTAL_PAYMENT').val(amount);

            updateFifoPreview();
            updateSummary();
        });

        // =====================================================
        // MANUAL ALLOCATION INPUT
        // =====================================================

        $(document).on('input', '.manual-allocation', function () {

            const orderId = Number($(this).attr('data-order-id'));

            const order = supplierOrders.find(function (item) {
                return Number(item.ORDER_SUPPLIER_ID) === orderId;
            });

            let amount = parseAmount($(this).val());

            if (order) {
                const outstanding = Number(order.REMAIN) || 0;

                if (amount > outstanding) {
                    amount = outstanding;
                }
            }

            $(this).val(
                amount > 0 ? formatRupiah(amount) : ''
            );

            updateSummary();
        });

        // =====================================================
        // SAVE PAYMENT
        // =====================================================

        $('#paymentForm').on('submit', function (e) {
            e.preventDefault();

            if (isLoadingOrders) {
                alert('Please wait until Supplier Orders finish loading.');
                return;
            }

            const supplier = $('#SUPPLIER').val();
            const totalPayment = getTotalPayment();
            const allocations = getCurrentAllocations();

            const totalAllocated = allocations.reduce(
                function (sum, item) {
                    return sum + Number(item.PAID_AMOUNT || 0);
                },
                0
            );

            if (!supplier) {
                alert('Please select Supplier.');
                return;
            }

            if (totalPayment <= 0) {
                alert('Total Payment harus lebih besar dari 0.');
                return;
            }

            if (totalAllocated !== totalPayment) {
                alert('Total allocation harus sama dengan Total Payment.');
                return;
            }

            if (!confirm('Are you sure you want to update this Payment?')) {
                return;
            }

            const formData = $(this).serializeArray();

            formData.push({
                name: 'allocations',
                value: getPaymentType() === 'MANUAL'
                    ? JSON.stringify(allocations)
                    : ''
            });

            $('#btnSavePayment')
                .prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: config.updateUrl,
                type: 'POST',
                data: $.param(formData),
                dataType: 'JSON',

                success: function (response) {

                    if (response.ok) {
                        alert(
                            response.message || 'Payment successfully updated.'
                        );

                        window.location.href = config.listUrl;
                        return;
                    }

                    alert(response.message || 'Failed to update Payment.');
                },

                error: function (xhr) {
                    const response = xhr.responseJSON;

                    alert(
                        response && response.message
                            ? response.message
                            : 'Failed to update Payment.'
                    );
                },

                complete: function () {
                    $('#btnSavePayment')
                        .prop('disabled', false)
                        .html('<i class="fa fa-save"></i> Update Payment');

                    updateSummary();
                }
            });
        });

        // =====================================================
        // INITIAL STATE
        // =====================================================

        renderSupplierOrders();
        isInitialRender = false;
        updateSummary();

    });
</script>
