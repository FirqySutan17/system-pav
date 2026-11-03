<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$today = date('Y-m-d');
?>

<style>
.cash-in-create-page{color:#243247;padding-bottom:30px}
.cash-in-create-page .page-heading{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin:90px 0 22px}
.cash-in-create-page .eyebrow{display:inline-flex;align-items:center;gap:7px;color:#64748b;font-size:.76rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;margin-bottom:7px}
.cash-in-create-page .page-title{margin:0;color:#172b4d;font-size:1.55rem;font-weight:750;letter-spacing:-.035em}
.cash-in-create-page .page-subtitle{margin:7px 0 0;color:#718096;font-size:.92rem}
.cash-in-create-page .breadcrumb{background:transparent;margin:0;padding:8px 0;font-size:.82rem}
.cash-in-create-page .breadcrumb a{color:#526fe5;text-decoration:none}
.cash-in-create-page .form-card{margin-bottom:20px;border:1px solid #e7edf5;border-radius:16px;background:#fff;box-shadow:0 8px 28px rgba(22,42,75,.055);overflow:visible}
.cash-in-create-page .form-card-header{display:flex;align-items:center;gap:12px;padding:17px 22px;border-bottom:1px solid #edf1f7;border-radius:16px 16px 0 0;background:linear-gradient(180deg,#fff 0%,#fbfcff 100%)}
.cash-in-create-page .section-icon{display:flex;align-items:center;justify-content:center;width:38px;height:38px;flex-shrink:0;border-radius:11px;font-size:19px}
.cash-in-create-page .icon-cashin{color:#4965e8;background:#edf0ff}
.cash-in-create-page .icon-outstanding{color:#087f5b;background:#e8f7f0}
.cash-in-create-page .section-title{margin:0;color:#243247;font-size:.98rem;font-weight:750}
.cash-in-create-page .section-description{margin:3px 0 0;color:#8491a4;font-size:.76rem}
.cash-in-create-page .form-card-body{padding:22px}
.cash-in-create-page .form-group{margin-bottom:18px}
.cash-in-create-page .form-label{display:block;margin-bottom:7px;color:#64748b;font-size:.75rem;font-weight:750;letter-spacing:.035em;text-transform:uppercase}
.cash-in-create-page .required{color:#dc3545}
.cash-in-create-page .form-control,.cash-in-create-page .form-select{width:100%;min-width:0;min-height:44px;box-sizing:border-box;border:1px solid #dfe6f0;border-radius:10px;box-shadow:none;color:#334155;background:#fff;font-size:.86rem}
.cash-in-create-page .form-control:focus,.cash-in-create-page .form-select:focus{border-color:#8aa4ff;box-shadow:0 0 0 3px rgba(99,126,255,.12)}
.cash-in-create-page .select2-container{width:100%!important;min-width:0}
.cash-in-create-page .select2-container--default .select2-selection--single{height:44px!important;border:1px solid #dfe6f0!important;border-radius:10px!important;background:#fff!important;box-shadow:none!important}
.cash-in-create-page .select2-container--default .select2-selection--single .select2-selection__rendered{height:42px;line-height:42px!important;padding-left:13px!important;padding-right:35px!important;color:#334155!important;font-size:.86rem}
.cash-in-create-page .select2-container--default .select2-selection--single .select2-selection__placeholder{color:#a0acbd!important}
.cash-in-create-page .select2-container--default .select2-selection--single .select2-selection__arrow{height:42px!important;right:8px!important}
.cash-in-create-page .select2-dropdown{border:1px solid #dfe6f0!important;border-radius:10px!important;overflow:hidden;box-shadow:0 8px 24px rgba(22,42,75,.10);z-index:9999}
.cash-in-create-page .select2-results__option{padding:9px 13px!important;color:#334155;font-size:.84rem}
.cash-in-create-page .summary-card{height:100%;min-height:112px;padding:17px 19px;border:1px solid #e7edf5;border-radius:14px;background:#fff;box-shadow:0 6px 20px rgba(22,42,75,.045)}
.cash-in-create-page .summary-label{display:block;margin-bottom:10px;color:#8491a4;font-size:.72rem;font-weight:750;letter-spacing:.045em;text-transform:uppercase}
.cash-in-create-page .summary-value{display:block;color:#243247;font-size:1.3rem;font-weight:800;font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.cash-in-create-page .summary-outstanding{background:#f5f8ff;border-color:#dce7ff}
.cash-in-create-page .summary-outstanding .summary-value{color:#4965e8}
.cash-in-create-page .summary-payment{background:#f0faf5;border-color:#cceedd}
.cash-in-create-page .summary-payment .summary-value{color:#087f5b}
.cash-in-create-page .summary-unallocated{background:#fff9eb;border-color:#f4e3bd}
.cash-in-create-page .summary-unallocated .summary-value{color:#a66b0a}
.cash-in-create-page .payment-input-card{padding:17px;border:1px solid #e7edf5;border-radius:12px;background:#fbfcff}
.cash-in-create-page .allocation-info{display:flex;align-items:center;min-height:44px;padding:10px 13px;border:1px solid #dce7ff;border-radius:10px;background:#f5f8ff;color:#52658b;font-size:.81rem}
.cash-in-create-page .table-responsive{border:1px solid #e7edf5;border-radius:12px}
.cash-in-create-page .cash-in-table{width:100%;margin-bottom:0;border-collapse:separate;border-spacing:0;font-size:.82rem}
.cash-in-create-page .cash-in-table thead th{padding:13px 12px;background:#f7f9fc;color:#64748b;border-bottom:1px solid #e7edf5;font-size:.73rem;font-weight:750;letter-spacing:.025em;white-space:nowrap}
.cash-in-create-page .cash-in-table tbody td{padding:12px;color:#475569;border-bottom:1px solid #edf1f7;vertical-align:middle}
.cash-in-create-page .cash-in-table tbody tr:last-child td{border-bottom:0}
.cash-in-create-page .cash-in-table tfoot th{padding:13px 12px;background:#f5f8ff;color:#243247;border-top:1px solid #dfe7fb;font-weight:800;white-space:nowrap}
.cash-in-create-page .text-right{text-align:right;font-variant-numeric:tabular-nums}
.cash-in-create-page .manual-allocation{min-width:130px;min-height:38px;text-align:right}
.cash-in-create-page .fifo-allocation{display:block;min-width:110px;color:#087f5b;font-weight:750;font-variant-numeric:tabular-nums}
.cash-in-create-page .order-count{display:inline-flex;align-items:center;padding:6px 11px;border-radius:20px;background:#edf0ff;color:#4965e8;font-size:.73rem;font-weight:750}
.cash-in-create-page .info-note{display:flex;align-items:flex-start;gap:10px;padding:12px 14px;margin-bottom:18px;border:1px solid #dce7ff;border-radius:10px;background:#f5f8ff;color:#52658b;font-size:.8rem;line-height:1.6}
.cash-in-create-page .info-note i{margin-top:2px;color:#5270df}
.cash-in-create-page .action-card{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;padding:18px 22px;border:1px solid #e7edf5;border-radius:16px;background:#fff;box-shadow:0 8px 28px rgba(22,42,75,.055)}
.cash-in-create-page .action-description{color:#8491a4;font-size:.8rem}
.cash-in-create-page .action-buttons{display:flex;align-items:center;gap:9px}
.cash-in-create-page .btn-cash-in{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:44px;padding:0 18px;border-radius:10px;font-size:.84rem;font-weight:700;text-decoration:none;white-space:nowrap;transition:all .15s}
.cash-in-create-page .btn-back{border:1px solid #dfe6f0;background:#fff;color:#64748b}
.cash-in-create-page .btn-save{border:0;background:linear-gradient(135deg,#627dff,#4965e8);color:#fff;box-shadow:0 5px 12px rgba(79,103,232,.2)}
.cash-in-create-page .btn-save:hover{color:#fff;transform:translateY(-1px);box-shadow:0 8px 16px rgba(79,103,232,.25)}
.cash-in-create-page .btn-save:disabled{opacity:.55;cursor:not-allowed;transform:none}
@media(max-width:767px){.cash-in-create-page .page-heading{margin-top:80px}.cash-in-create-page .page-title{font-size:1.3rem}.cash-in-create-page .form-card-header{padding:15px 16px}.cash-in-create-page .form-card-body{padding:17px 16px}.cash-in-create-page .action-card{align-items:stretch;padding:16px}.cash-in-create-page .action-buttons{width:100%}.cash-in-create-page .action-buttons .btn-cash-in{flex:1}}
@media(max-width:480px){.cash-in-create-page .action-buttons{flex-direction:column-reverse}.cash-in-create-page .action-buttons .btn-cash-in{width:100%}}
</style>

<div class="container-fluid cash-in-create-page px-2 px-lg-3 py-2">
    <div class="page-heading">
        <div>
            <div class="eyebrow"><i class="ti ti-wallet"></i> Accounting</div>
            <h1 class="page-title">Create Cash In</h1>
            <p class="page-subtitle">Record customer receipts and allocate them to outstanding Customer Orders.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('cashin'); ?>">Cash In Customer</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>

    <?php if ($this->session->flashdata('cash_in_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-1"></i>
            <?= html_escape($this->session->flashdata('cash_in_error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form id="cashInForm" method="POST" action="<?= base_url('cashin/save'); ?>" autocomplete="off">
        <?php if (config_item('csrf_protection')): ?>
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                   value="<?= $this->security->get_csrf_hash(); ?>">
        <?php endif; ?>

        <section class="form-card">
            <div class="form-card-header">
                <div class="section-icon icon-cashin"><i class="ti ti-wallet"></i></div>
                <div>
                    <h3 class="section-title">Cash In Information</h3>
                    <p class="section-description">General information for customer receipt</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Cash In Date <span class="required">*</span></label>
                            <input type="date" name="CASH_IN_DATE" id="CASH_IN_DATE"
                                   class="form-control" value="<?= html_escape($today); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">Customer <span class="required">*</span></label>
                            <select name="CUSTOMER" id="CUSTOMER" class="form-control select2-customer" required>
                                <option value="">-- Select Customer --</option>
                                <?php if (!empty($customers)): ?>
                                    <?php foreach ($customers as $row): ?>
                                        <option value="<?= html_escape($row['CUST']); ?>">
                                            <?= html_escape($row['CUST'] . ' - ' . $row['FULL_NAME']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Cash In Method <span class="required">*</span></label>
                            <select name="CASH_IN_METHOD" id="CASH_IN_METHOD" class="form-control" required>
                                <option value="TRANSFER">TRANSFER</option>
                                <option value="CASH">CASH</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Cash In Type <span class="required">*</span></label>
                            <select name="CASH_IN_TYPE" id="CASH_IN_TYPE" class="form-control" required>
                                <option value="FIFO">FIFO</option>
                                <option value="MANUAL">MANUAL</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label class="form-label">Slip No</label>
                            <input type="text" name="SLIP_NO" id="SLIP_NO" class="form-control"
                                   maxlength="100" placeholder="Enter Slip Number">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-md-0">
                            <label class="form-label">Remark</label>
                            <input type="text" name="REMARK" id="REMARK" class="form-control"
                                   placeholder="Cash In remark">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="row mb-3">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="summary-card summary-outstanding">
                    <span class="summary-label"><i class="ti ti-file-invoice me-1"></i> Total Outstanding</span>
                    <span class="summary-value" id="summaryOutstanding">0</span>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="summary-card summary-payment">
                    <span class="summary-label"><i class="ti ti-cash me-1"></i> Total Cash In</span>
                    <span class="summary-value" id="summaryCashIn">0</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card summary-unallocated">
                    <span class="summary-label"><i class="ti ti-calculator me-1"></i> Unallocated Cash In</span>
                    <span class="summary-value" id="summaryAllocation">0</span>
                </div>
            </div>
        </div>

        <section class="form-card">
            <div class="form-card-header">
                <div class="section-icon icon-outstanding"><i class="ti ti-list-details"></i></div>
                <div class="flex-grow-1">
                    <h3 class="section-title">Outstanding Customer Orders</h3>
                    <p class="section-description">Select and allocate outstanding Customer Orders for this receipt</p>
                </div>
                <span id="orderCount" class="order-count">0 Orders</span>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="payment-input-card mb-3 mb-md-0">
                            <div class="form-group mb-0">
                                <label class="form-label">Total Cash In <span class="required">*</span></label>
                                <input type="text" id="TOTAL_CASH_IN_DISPLAY" class="form-control text-right"
                                       placeholder="0" autocomplete="off">
                                <input type="hidden" name="TOTAL_CASH_IN" id="TOTAL_CASH_IN" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="payment-input-card">
                            <label class="form-label">Allocation Information</label>
                            <div id="allocationInfo" class="allocation-info">
                                Select Customer to load outstanding Customer Orders.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-note mt-3">
                    <i class="ti ti-info-circle"></i>
                    <div>
                        <strong>Cash In Allocation</strong><br>
                        FIFO automatically allocates the receipt to the oldest eligible Customer Order first.
                        Manual allows you to enter allocation amounts for each Customer Order.
                        Only OPEN and approved/approval-not-required Customer Orders are available.
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="cash-in-table" id="customerOrderTable">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Order Date</th>
                                <th>Master Order</th>
                                <th>Customer Order</th>
                                <th class="text-right">Total</th>
                                <th class="text-right">CN</th>
                                <th class="text-right">Paid</th>
                                <th class="text-right">Outstanding</th>
                                <th class="text-right">Allocation</th>
                            </tr>
                        </thead>
                        <tbody id="customerOrderBody">
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Select Customer first.</td>
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

        <div class="action-card">
            <div class="action-description">
                <i class="ti ti-shield-check me-1"></i>
                Please verify the cash in information and allocation before saving.
            </div>
            <div class="action-buttons">
                <a href="<?= base_url('cashin'); ?>" class="btn-cash-in btn-back" id="btnBack">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn-cash-in btn-save" id="btnSaveCashIn" disabled>
                    <i class="ti ti-device-floppy"></i> Save Cash In
                </button>
            </div>
        </div>
    </form>
</div>

<script>
window.CASH_IN_CONFIG = {
    customerOrdersUrl: <?= json_encode(base_url('cashin/customer_orders/')); ?>,
    saveUrl: <?= json_encode(base_url('cashin/save')); ?>,
    listUrl: <?= json_encode(base_url('cashin')); ?>
};

$(document).ready(function () {
    const config = window.CASH_IN_CONFIG;
    let customerOrders = [];
    let isLoadingOrders = false;
    let requestToken = 0;

    function formatAmount(value) {
        value = Math.round(Number(value) || 0);
        return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(value);
    }

    function parseAmount(value) {
        if (value === null || value === undefined) return 0;
        return Number(String(value).replace(/[^\d]/g, '') || 0);
    }

    function formatSigned(value) {
        value = Math.round(Number(value) || 0);
        return value < 0 ? '-' + formatAmount(Math.abs(value)) : formatAmount(value);
    }

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    if ($.fn.select2) {
        $('#CUSTOMER').select2({
            placeholder: 'Select Customer',
            allowClear: true,
            width: '100%'
        });
    }

    function getCashInType() {
        return $('#CASH_IN_TYPE').val();
    }

    function getTotalCashIn() {
        if (getCashInType() === 'MANUAL') {
            return customerOrders.reduce(function (sum, order) {
                return sum + getAllocationAmount(order.ORDER_CUSTOMER_ID);
            }, 0);
        }
        return parseAmount($('#TOTAL_CASH_IN_DISPLAY').val());
    }

    function getAllocationAmount(orderId) {
        const input = $('.manual-allocation[data-order-id="' + orderId + '"]');
        return input.length ? parseAmount(input.val()) : 0;
    }

    function getCurrentAllocations() {
        const allocations = [];

        if (getCashInType() === 'FIFO') {
            let remainingCashIn = getTotalCashIn();

            customerOrders.forEach(function (order) {
                if (remainingCashIn <= 0) return;

                const outstanding = Number(order.REMAIN) || 0;
                const amount = Math.min(remainingCashIn, outstanding);

                if (amount > 0) {
                    allocations.push({
                        ORDER_CUSTOMER_ID: Number(order.ORDER_CUSTOMER_ID),
                        PAID_AMOUNT: amount
                    });
                    remainingCashIn -= amount;
                }
            });
        } else {
            customerOrders.forEach(function (order) {
                const amount = getAllocationAmount(order.ORDER_CUSTOMER_ID);
                if (amount > 0) {
                    allocations.push({
                        ORDER_CUSTOMER_ID: Number(order.ORDER_CUSTOMER_ID),
                        PAID_AMOUNT: amount
                    });
                }
            });
        }

        return allocations;
    }

    function updateSummary() {
        const totalOutstanding = customerOrders.reduce(function (sum, order) {
            return sum + Number(order.REMAIN || 0);
        }, 0);

        const totalCashIn = getTotalCashIn();
        $('#TOTAL_CASH_IN').val(totalCashIn);

        const allocations = getCurrentAllocations();
        const totalAllocated = allocations.reduce(function (sum, item) {
            return sum + Number(item.PAID_AMOUNT || 0);
        }, 0);

        const unallocated = totalCashIn - totalAllocated;

        $('#summaryOutstanding').text(formatAmount(totalOutstanding));
        $('#summaryCashIn').text(formatAmount(totalCashIn));
        $('#summaryAllocation').text(formatSigned(unallocated));
        $('#footerAllocation').text(formatAmount(totalAllocated));

        $('#allocationInfo').text(
            getCashInType() === 'FIFO'
                ? 'FIFO: allocation otomatis berdasarkan Order Date tertua.'
                : 'MANUAL: masukkan nominal allocation untuk setiap Customer Order.'
        );

        const valid = !isLoadingOrders &&
            customerOrders.length > 0 &&
            totalCashIn > 0 &&
            totalAllocated === totalCashIn;

        $('#btnSaveCashIn').prop('disabled', !valid);
    }

    function updateFooter() {
        $('#footerTotal,#footerCN,#footerPaid,#footerRemain,#footerAllocation').text('0');
    }

    function updateFifoPreview() {
        if (getCashInType() !== 'FIFO') return;

        let remainingCashIn = getTotalCashIn();

        customerOrders.forEach(function (order) {
            const outstanding = Number(order.REMAIN) || 0;
            const allocation = Math.max(0, Math.min(remainingCashIn, outstanding));
            const orderId = Number(order.ORDER_CUSTOMER_ID);

            $('.fifo-allocation[data-order-id="' + orderId + '"]')
                .val(formatAmount(allocation));

            remainingCashIn -= allocation;
        });
    }

    function renderCustomerOrders() {
        const tbody = $('#customerOrderBody');
        tbody.empty();

        if (!customerOrders.length) {
            tbody.html(
                '<tr><td colspan="9" class="text-center text-muted py-4">' +
                'No eligible outstanding Customer Order found.</td></tr>'
            );
            $('#orderCount').text('0 Orders');
            updateFooter();
            updateSummary();
            return;
        }

        let total = 0;
        let cnTotal = 0;
        let paid = 0;
        let remain = 0;

        customerOrders.forEach(function (order, index) {
            const orderTotal = Number(order.TOTAL) || 0;
            const orderCN = Number(order.CN_TOTAL_AMOUNT) || 0;
            const orderPaid = Number(order.PAID) || 0;
            const orderRemain = Number(order.REMAIN) || 0;

            total += orderTotal;
            cnTotal += orderCN;
            paid += orderPaid;
            remain += orderRemain;

            let allocationHtml = '';
            if (getCashInType() === 'FIFO') {
                allocationHtml = `
                    <input type="text"
                        class="form-control form-control-sm fifo-allocation text-right"
                        data-order-id="${Number(order.ORDER_CUSTOMER_ID)}"
                        value="0" readonly tabindex="-1"
                        style="background:#f7f9fc;color:#087f5b;font-weight:700;">
                `;
            } else {
                allocationHtml = `
                    <input type="text"
                        class="form-control form-control-sm manual-allocation text-right"
                        data-order-id="${Number(order.ORDER_CUSTOMER_ID)}"
                        value="" placeholder="0" autocomplete="off">
                `;
            }

            tbody.append(`
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${escapeHtml(order.ORDER_DATE)}</td>
                    <td>${escapeHtml(order.ORDER_NO)}</td>
                    <td><strong>${escapeHtml(order.ORDER_CUSTOMER_NO)}</strong></td>
                    <td class="text-right">${formatAmount(orderTotal)}</td>
                    <td class="text-right">${formatAmount(orderCN)}</td>
                    <td class="text-right">${formatAmount(orderPaid)}</td>
                    <td class="text-right"><strong>${formatAmount(orderRemain)}</strong></td>
                    <td class="text-right">${allocationHtml}</td>
                </tr>
            `);
        });

        $('#orderCount').text(customerOrders.length + ' Orders');
        $('#footerTotal').text(formatAmount(total));
        $('#footerCN').text(formatAmount(cnTotal));
        $('#footerPaid').text(formatAmount(paid));
        $('#footerRemain').text(formatAmount(remain));

        updateFifoPreview();
        updateSummary();
    }

    function loadCustomerOrders(customer) {
        const token = ++requestToken;
        customerOrders = [];
        isLoadingOrders = false;

        if (!customer) {
            $('#customerOrderBody').html(
                '<tr><td colspan="9" class="text-center text-muted py-4">Select Customer first.</td></tr>'
            );
            $('#orderCount').text('0 Orders');
            updateFooter();
            updateSummary();
            return;
        }

        isLoadingOrders = true;
        $('#btnSaveCashIn').prop('disabled', true);
        $('#customerOrderBody').html(
            '<tr><td colspan="9" class="text-center py-4">' +
            '<i class="fa fa-spinner fa-spin"></i> Loading Customer Orders...</td></tr>'
        );
        updateSummary();

        $.ajax({
            url: config.customerOrdersUrl + encodeURIComponent(customer),
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (token !== requestToken) return;

                if (!response.ok) {
                    customerOrders = [];
                    alert(response.message || 'Failed to load Customer Orders.');
                    return;
                }

                customerOrders = response.data || [];
            },
            error: function (xhr) {
                if (token !== requestToken) return;

                customerOrders = [];
                const response = xhr.responseJSON;
                alert(response && response.message
                    ? response.message
                    : 'Failed to load Customer Orders.');
            },
            complete: function () {
                if (token !== requestToken) return;

                isLoadingOrders = false;
                renderCustomerOrders();
            }
        });
    }

    $('#CUSTOMER').on('change', function () {
        loadCustomerOrders($(this).val());
    });

    $('#CASH_IN_TYPE').on('change', function () {
        if (getCashInType() === 'MANUAL') {
            $('#TOTAL_CASH_IN_DISPLAY').val('').prop('readonly', true);
            $('#TOTAL_CASH_IN').val(0);
        } else {
            $('#TOTAL_CASH_IN_DISPLAY').prop('readonly', false);
        }

        renderCustomerOrders();
        updateSummary();
    });

    $('#TOTAL_CASH_IN_DISPLAY').on('input', function () {
        const amount = parseAmount($(this).val());
        $(this).val(amount > 0 ? formatAmount(amount) : '');
        $('#TOTAL_CASH_IN').val(amount);

        updateFifoPreview();
        updateSummary();
    });

    $(document).on('input', '.manual-allocation', function () {
        const orderId = Number($(this).attr('data-order-id'));
        const order = customerOrders.find(function (item) {
            return Number(item.ORDER_CUSTOMER_ID) === orderId;
        });

        let amount = parseAmount($(this).val());

        if (order) {
            const outstanding = Number(order.REMAIN) || 0;
            if (amount > outstanding) amount = outstanding;
        }

        $(this).val(amount > 0 ? formatAmount(amount) : '');
        updateSummary();
    });

    $('#cashInForm').on('submit', function (e) {
        e.preventDefault();

        if (isLoadingOrders) {
            alert('Please wait until Customer Orders finish loading.');
            return;
        }

        const customer = $('#CUSTOMER').val();
        const totalCashIn = getTotalCashIn();
        const allocations = getCurrentAllocations();

        const totalAllocated = allocations.reduce(function (sum, item) {
            return sum + Number(item.PAID_AMOUNT || 0);
        }, 0);

        if (!customer) {
            alert('Please select Customer.');
            return;
        }

        if (totalCashIn <= 0) {
            alert('Total Cash In harus lebih besar dari 0.');
            return;
        }

        if (totalAllocated !== totalCashIn) {
            alert('Total allocation harus sama dengan Total Cash In.');
            return;
        }

        if (!confirm('Are you sure you want to save this Cash In?')) return;

        const formData = $(this).serializeArray();
        formData.push({
            name: 'allocations',
            value: JSON.stringify(allocations)
        });

        const $button = $('#btnSaveCashIn');
        $button.prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: config.saveUrl,
            type: 'POST',
            data: $.param(formData),
            dataType: 'json',
            success: function (response) {
                if (response.ok) {
                    alert(
                        (response.message || 'Cash In berhasil disimpan.') +
                        (response.cash_in_no ? '\nCash In No: ' + response.cash_in_no : '')
                    );
                    window.location.href = config.listUrl;
                    return;
                }

                alert(response.message || 'Failed to save Cash In.');
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                alert(response && response.message
                    ? response.message
                    : 'Failed to save Cash In.');
            },
            complete: function () {
                $button.prop('disabled', false)
                    .html('<i class="ti ti-device-floppy"></i> Save Cash In');
                updateSummary();
            }
        });
    });

    $('#TOTAL_CASH_IN').val(0);
    updateFooter();
    updateSummary();
});
</script>
