
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$master   = $order['master'] ?? [];
$customer = $order['customer_order'] ?? [];
$supplier = $order['supplier_order'] ?? [];
$credit   = $order['credit_summary'] ?? [];
$approval = $order['pending_approval'] ?? [];
$edit     = $order['edit_request'] ?? [];

$approval_type = $approval['APPROVAL_TYPE'] ?? '';

$is_customer_edit = (
    $approval_type === 'CUSTOMER_ORDER_EDIT'
);

$old_data = $edit['OLD_DATA'] ?? [];
$new_data = $edit['NEW_DATA'] ?? [];

$currency = !empty($credit['currency'])
    ? $credit['currency']
    : 'IDR';

$format_number = function ($value, $decimal = 2) {
    return number_format((float) $value, $decimal, ',', '.');
};

$format_date = function ($value, $format = 'd M Y H:i') {
    if (empty($value)) {
        return '-';
    }

    $timestamp = strtotime($value);

    return $timestamp ? date($format, $timestamp) : '-';
};

$currency_format = function ($value) use ($currency, $format_number) {
    return $currency . ' ' . $format_number($value);
};

$credit_active = !empty($credit['is_active']);

$flash_error = $this->session->flashdata('order_error');
$flash_success = $this->session->flashdata('order_success');

$comparison_fields = [
    'BASIS' => 'Basis',
    'EKOR' => 'Ekor',
    'KG' => 'KG',
    'AVG_BW' => 'Average BW',
    'UNIT_PRICE' => 'Unit Price',
    'TOTAL' => 'Total'
];
?>

<style>
    .approval-detail-page {
        color: #374151;
    }

    .approval-detail-page .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 24px;
    }

    .approval-detail-page .page-title {
        color: #171717;
        font-size: 22px;
        font-weight: 750;
        margin: 0 0 5px;
    }

    .approval-detail-page .page-subtitle {
        color: #9ca3af;
        font-size: 13px;
        margin: 0;
    }

    .approval-detail-page .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        color: #4b5563;
        background: #fff;
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
    }

    .approval-detail-page .back-btn:hover {
        border-color: #f97316;
        color: #f97316;
    }

    .approval-detail-page .section-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .approval-detail-page .section-heading {
        padding: 17px 21px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .approval-detail-page .section-heading h6 {
        color: #171717;
        font-size: 13px;
        font-weight: 750;
        margin: 0;
    }

    .approval-detail-page .section-heading small {
        color: #9ca3af;
        font-size: 11px;
    }

    .approval-detail-page .section-body {
        padding: 21px;
    }

    .approval-detail-page .info-label {
        color: #9ca3af;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .5px;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .approval-detail-page .info-value {
        color: #171717;
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .approval-detail-page .info-sub {
        color: #9ca3af;
        font-size: 11px;
        margin-top: 4px;
    }

    .approval-detail-page .status-pending {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 20px;
        background: #fff7e6;
        color: #b7791f;
        font-size: 10px;
        font-weight: 750;
    }

    .approval-detail-page .type-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 750;
    }

    .approval-detail-page .type-credit {
        background: #fff7e6;
        color: #b7791f;
    }

    .approval-detail-page .type-edit {
        background: #eaf3ff;
        color: #2563eb;
    }

    .approval-detail-page .summary-card {
        height: 100%;
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
    }

    .approval-detail-page .summary-label {
        color: #9ca3af;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .approval-detail-page .summary-value {
        color: #171717;
        font-size: 19px;
        font-weight: 800;
        margin-top: 8px;
        overflow-wrap: anywhere;
        font-variant-numeric: tabular-nums;
    }

    .approval-detail-page .summary-note {
        color: #9ca3af;
        font-size: 10px;
        margin-top: 5px;
    }

    .approval-detail-page .order-table {
        margin: 0;
    }

    .approval-detail-page .order-table th {
        width: 22%;
        padding: 13px 15px;
        background: #fafafa;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        border-color: #eeeeee;
        vertical-align: middle;
    }

    .approval-detail-page .order-table td {
        padding: 13px 15px;
        color: #171717;
        font-size: 12px;
        font-weight: 650;
        border-color: #eeeeee;
        vertical-align: middle;
    }

    .approval-detail-page .order-total {
        background: #fffaf5;
        color: #171717;
        font-size: 14px;
        font-weight: 800;
    }

    .approval-detail-page .credit-warning {
        padding: 13px 15px;
        border: 1px solid #fde6bd;
        border-radius: 10px;
        background: #fffbeb;
        color: #92400e;
        font-size: 12px;
    }

    .approval-detail-page .credit-info {
        padding: 13px 15px;
        border: 1px solid #d1fae5;
        border-radius: 10px;
        background: #f0fdf4;
        color: #166534;
        font-size: 12px;
    }

    .approval-detail-page .comparison-table {
        margin: 0;
    }

    .approval-detail-page .comparison-table th {
        background: #fafafa;
        color: #6b7280;
        font-size: 11px;
        padding: 13px;
        white-space: nowrap;
    }

    .approval-detail-page .comparison-table td {
        padding: 13px;
        font-size: 12px;
        vertical-align: middle;
    }

    .approval-detail-page .new-value {
        color: #166534;
        font-weight: 750;
    }

    .approval-detail-page .approval-panel {
        border: 1px solid #f4d7c4;
        border-radius: 14px;
        background: #fff;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .approval-detail-page .approval-panel-header {
        padding: 17px 21px;
        background: #fffaf5;
        border-bottom: 1px solid #f4e6dc;
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .approval-detail-page .approval-panel-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fff0e5;
        color: #f97316;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .approval-detail-page .approval-panel-header h6 {
        color: #171717;
        font-size: 13px;
        font-weight: 750;
        margin: 0 0 4px;
    }

    .approval-detail-page .approval-panel-header small {
        color: #9ca3af;
        font-size: 11px;
    }

    .approval-detail-page .approval-panel-body {
        padding: 21px;
    }

    .approval-detail-page .decision-note {
        color: #9ca3af;
        font-size: 11px;
    }

    .approval-detail-page .decision-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 17px;
        border: 0;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 750;
    }

    .approval-detail-page .btn-reject {
        background: #fee2e2;
        color: #b91c1c;
    }

    .approval-detail-page .btn-reject:hover {
        background: #dc2626;
        color: #fff;
    }

    .approval-detail-page .btn-approve {
        background: #16a34a;
        color: #fff;
    }

    .approval-detail-page .btn-approve:hover {
        background: #15803d;
    }

    @media(max-width: 767px) {
        .approval-detail-page .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .approval-detail-page .section-body {
            padding: 15px;
        }
    }
</style>

<div class="container-fluid py-4 approval-detail-page">

    <!-- PAGE HEADER -->
    <div class="page-heading">

        <div>
            <h4 class="page-title">
                Approval Order Detail
            </h4>

            <p class="page-subtitle">
                Review order information before submitting your decision.
            </p>
        </div>

        <a href="<?= site_url('order/approval'); ?>"
           class="back-btn">
            <i class="ti ti-arrow-left"></i>
            Back to Approval List
        </a>

    </div>

    <!-- FLASH MESSAGE -->
    <?php if ($flash_error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= html_escape($flash_error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($flash_success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= html_escape($flash_success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- APPROVAL INFORMATION -->
    <div class="section-card">

        <div class="section-heading">
            <div>
                <h6>Approval Information</h6>
                <small>Approval request details</small>
            </div>

            <span class="status-pending">
                <i class="ti ti-clock"></i>
                Pending
            </span>
        </div>

        <div class="section-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="info-label">Approval ID</div>
                    <div class="info-value">
                        #<?= (int) ($approval['APPROVAL_ID'] ?? 0); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Approval Type</div>

                    <?php if ($approval_type === 'CREDIT_LIMIT'): ?>

                        <span class="type-badge type-credit">
                            Credit Limit
                        </span>

                    <?php elseif ($is_customer_edit): ?>

                        <span class="type-badge type-edit">
                            Customer Order Edit
                        </span>

                    <?php else: ?>

                        <span class="type-badge type-credit">
                            <?= html_escape($approval_type); ?>
                        </span>

                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Requested By</div>
                    <div class="info-value">
                        <?= html_escape($approval['REQUESTED_BY'] ?? '-'); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Requested At</div>
                    <div class="info-value">
                        <?= $format_date($approval['REQUESTED_AT'] ?? NULL); ?>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- MASTER ORDER INFORMATION -->
    <div class="section-card">

        <div class="section-heading">
            <div>
                <h6>Master Order Information</h6>
                <small>Transaction overview</small>
            </div>
        </div>

        <div class="section-body">

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="info-label">Master Order Number</div>
                    <div class="info-value">
                        <?= html_escape($master['ORDER_NO'] ?? '-'); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Order Date</div>
                    <div class="info-value">
                        <?= $format_date($master['ORDER_DATE'] ?? NULL, 'd M Y'); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Master Status</div>
                    <div class="info-value">
                        <?= html_escape($master['STATUS'] ?? '-'); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Plant</div>
                    <div class="info-value">
                        <?= html_escape($master['PLANT'] ?? '-'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Customer</div>
                    <div class="info-value">
                        <?= html_escape($master['CUST'] ?? '-'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Supplier</div>
                    <div class="info-value">
                        <?= html_escape($master['SUPPLIER'] ?? '-'); ?>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- CREDIT LIMIT INFORMATION -->
    <?php if (!$is_customer_edit): ?>

        <div class="section-card">

            <div class="section-heading">
                <div>
                    <h6>Customer Credit Information</h6>
                    <small>Credit Limit overview</small>
                </div>
            </div>

            <div class="section-body">

                <?php if (!empty($credit)): ?>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-label">
                                    Basic Credit
                                </div>

                                <div class="summary-value">
                                    <?= $currency_format($credit['basic_credit'] ?? 0); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-label">
                                    Outstanding
                                </div>

                                <div class="summary-value">
                                    <?= $currency_format($credit['outstanding'] ?? 0); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-label">
                                    Credit Balance
                                </div>

                                <div class="summary-value">
                                    <?= $currency_format($credit['credit_balance'] ?? 0); ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3">

                        <?php if ($credit_active): ?>

                            <div class="credit-info">
                                <i class="ti ti-check-circle me-1"></i>
                                Credit Limit is currently active.
                            </div>

                        <?php else: ?>

                            <div class="credit-warning">
                                <i class="ti ti-alert-triangle me-1"></i>
                                Credit Limit is inactive, unavailable, or outside its validity period.
                            </div>

                        <?php endif; ?>

                    </div>

                <?php else: ?>

                    <div class="credit-warning">
                        <i class="ti ti-alert-triangle me-1"></i>
                        Customer does not have an available Credit Limit record.
                    </div>

                <?php endif; ?>

            </div>
        </div>

    <?php endif; ?>

    <!-- CUSTOMER ORDER -->
    <div class="section-card">

        <div class="section-heading">
            <div>
                <h6>Customer Order</h6>
                <small>Customer transaction information</small>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table order-table">

                <tbody>

                    <tr>
                        <th>Customer Order No.</th>
                        <td>
                            <?= html_escape($customer['ORDER_CUSTOMER_NO'] ?? '-'); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Basis</th>
                        <td>
                            <?= html_escape($customer['BASIS'] ?? '-'); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Ekor</th>
                        <td>
                            <?= $format_number($customer['EKOR'] ?? 0, 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>KG</th>
                        <td>
                            <?= $format_number($customer['KG'] ?? 0, 2); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Average BW</th>
                        <td>
                            <?= $format_number($customer['AVG_BW'] ?? 0, 4); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Unit Price</th>
                        <td>
                            <?= $currency_format($customer['UNIT_PRICE'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <td class="order-total">
                            <?= $currency_format($customer['TOTAL'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Paid</th>
                        <td>
                            <?= $currency_format($customer['PAID'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Remain</th>
                        <td>
                            <?= $currency_format($customer['REMAIN'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Payment Status</th>
                        <td>
                            <?= html_escape($customer['PAYMENT_STATUS'] ?? '-'); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Approval Status</th>
                        <td>
                            <?= html_escape($customer['APPROVAL_STATUS'] ?? '-'); ?>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>
    </div>

    <!-- CUSTOMER ORDER EDIT COMPARISON -->
    <?php if ($is_customer_edit): ?>

        <div class="section-card">

            <div class="section-heading">
                <div>
                    <h6>Customer Order Change Comparison</h6>
                    <small>Compare current data with proposed changes</small>
                </div>
            </div>

            <div class="section-body">

                <div class="table-responsive">

                    <table class="table table-bordered comparison-table">

                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Current Data</th>
                                <th>Proposed Data</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($comparison_fields as $key => $label): ?>

                                <?php
                                $old_value = $old_data[$key] ?? '-';
                                $new_value = $new_data[$key] ?? '-';

                                if (in_array($key, [
                                    'EKOR',
                                    'KG',
                                    'AVG_BW',
                                    'UNIT_PRICE',
                                    'TOTAL'
                                ], TRUE)) {
                                    $old_value = is_numeric($old_value)
                                        ? $format_number($old_value)
                                        : $old_value;

                                    $new_value = is_numeric($new_value)
                                        ? $format_number($new_value)
                                        : $new_value;
                                }
                                ?>

                                <tr>

                                    <td>
                                        <strong>
                                            <?= html_escape($label); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= html_escape((string) $old_value); ?>
                                    </td>

                                    <td class="new-value">
                                        <?= html_escape((string) $new_value); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <div class="mt-3">
                    <div class="info-label">
                        Edit Remark
                    </div>

                    <div class="info-value">
                        <?= nl2br(html_escape($edit['REMARK'] ?? '-')); ?>
                    </div>
                </div>

            </div>
        </div>

    <?php endif; ?>

    <!-- SUPPLIER ORDER -->
    <div class="section-card">

        <div class="section-heading">
            <div>
                <h6>Supplier Order</h6>
                <small>Supplier transaction information</small>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table order-table">

                <tbody>

                    <tr>
                        <th>Supplier Order No.</th>
                        <td>
                            <?= html_escape($supplier['ORDER_SUPPLIER_NO'] ?? '-'); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Basis</th>
                        <td>
                            <?= html_escape($supplier['BASIS'] ?? '-'); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Ekor</th>
                        <td>
                            <?= $format_number($supplier['EKOR'] ?? 0, 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>KG</th>
                        <td>
                            <?= $format_number($supplier['KG'] ?? 0, 2); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Average BW</th>
                        <td>
                            <?= $format_number($supplier['AVG_BW'] ?? 0, 4); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Unit Price</th>
                        <td>
                            <?= $currency_format($supplier['UNIT_PRICE'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <td class="order-total">
                            <?= $currency_format($supplier['TOTAL'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Paid</th>
                        <td>
                            <?= $currency_format($supplier['PAID'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Remain</th>
                        <td>
                            <?= $currency_format($supplier['REMAIN'] ?? 0); ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Payment Status</th>
                        <td>
                            <?= html_escape($supplier['PAYMENT_STATUS'] ?? '-'); ?>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>
    </div>

    <!-- MASTER FINANCIAL SUMMARY -->
    <div class="section-card">

        <div class="section-heading">
            <div>
                <h6>Order Financial Summary</h6>
                <small>Master Order financial calculation</small>
            </div>
        </div>

        <div class="section-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-label">
                            Selling Price
                        </div>

                        <div class="summary-value">
                            <?= $currency_format($master['SELLING_PRICE'] ?? 0); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-label">
                            Purchase Price
                        </div>

                        <div class="summary-value">
                            <?= $currency_format($master['PURCHASE_PRICE'] ?? 0); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-label">
                            Difference Price
                        </div>

                        <div class="summary-value">
                            <?= $currency_format($master['DIFFERENCE_PRICE'] ?? 0); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-label">
                            Sales Total
                        </div>

                        <div class="summary-value">
                            <?= $currency_format($master['SALES_TOTAL'] ?? 0); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-label">
                            Purchase Total
                        </div>

                        <div class="summary-value">
                            <?= $currency_format($master['PURCHASE_TOTAL'] ?? 0); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="summary-card">
                        <div class="summary-label">
                            Total Difference
                        </div>

                        <div class="summary-value">
                            <?= $currency_format($master['TOTAL_DIFFERENCE'] ?? 0); ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- APPROVAL DECISION -->
    <div class="approval-panel">

        <div class="approval-panel-header">

            <div class="approval-panel-icon">
                <i class="ti ti-clipboard-check"></i>
            </div>

            <div>
                <h6>Approval Decision</h6>

                <small>
                    Review all information before submitting your decision.
                </small>
            </div>

        </div>

        <div class="approval-panel-body">

            <?= form_open(
                'order/approval_action/' . (int) ($approval['APPROVAL_ID'] ?? 0)
            ); ?>

                <div class="mb-3">

                    <label class="form-label">
                        Approval Remark
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="remark"
                        class="form-control"
                        rows="4"
                        required
                        maxlength="1000"
                        placeholder="Enter your approval or rejection remark..."
                    ></textarea>

                    <div class="d-flex justify-content-between mt-2">

                        <small class="decision-note">
                            Remark is mandatory for both Approve and Reject.
                        </small>

                        <small class="decision-note">
                            Maximum 1,000 characters.
                        </small>

                    </div>

                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                    <div class="decision-note">
                        <i class="ti ti-info-circle me-1"></i>
                        This action cannot be reversed after submission.
                    </div>

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            name="decision"
                            value="REJECTED"
                            class="decision-btn btn-reject"
                            onclick="return confirm('Are you sure you want to reject this request?');"
                        >
                            <i class="ti ti-x"></i>

                            <?= $is_customer_edit
                                ? 'Reject Changes'
                                : 'Reject Order'; ?>
                        </button>

                        <button
                            type="submit"
                            name="decision"
                            value="APPROVED"
                            class="decision-btn btn-approve"
                            onclick="return confirm('Are you sure you want to approve this request?');"
                        >
                            <i class="ti ti-check"></i>

                            <?= $is_customer_edit
                                ? 'Approve Changes'
                                : 'Approve Order'; ?>
                        </button>

                    </div>

                </div>

            <?= form_close(); ?>

        </div>
    </div>

</div>
