<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$a = $approval_data['approval'] ?? [];
$cn = $approval_data['credit_note'] ?? [];
$o = $approval_data['master'] ?? [];

$requested_by = trim((string) ($a['REQUESTED_BY'] ?? ''));
$current_user = trim((string) ($current_user ?? ''));

$is_requester = (
    $requested_by !== '' &&
    $current_user !== '' &&
    strtolower($requested_by) === strtolower($current_user)
);

$display_requester = $requested_by !== '' ? $requested_by : ($cn['REQUESTED_BY'] ?? '-');
$is_pending = strtoupper((string) ($a['APPROVAL_STATUS'] ?? '')) === 'PENDING';

$type = strtoupper((string) ($cn['ORDER_TYPE'] ?? '-'));
$is_customer = in_array($type, ['CUSTOMER', 'CUSTOMER_SUPPLIER'], TRUE);
$is_supplier = in_array($type, ['SUPPLIER', 'CUSTOMER_SUPPLIER'], TRUE);

$format_date = function ($value, $format = 'd M Y H:i') {
    if (empty($value)) return '-';
    $ts = strtotime($value);
    return $ts ? date($format, $ts) : '-';
};
$number = function ($value, $decimal = 2) {
    return number_format((float) $value, $decimal, ',', '.');
};
$currency = function ($value) use ($number) {
    return 'Rp ' . $number($value, 2);
};

$customer_basis = strtoupper((string) ($cn['CUSTOMER_BASIS'] ?? ''));
$supplier_basis = strtoupper((string) ($cn['SUPPLIER_BASIS'] ?? ''));

$customer_quantity = $customer_basis === 'EKOR'
    ? ($cn['CUSTOMER_EKOR'] ?? 0)
    : ($cn['CUSTOMER_KG'] ?? 0);
$supplier_quantity = $supplier_basis === 'EKOR'
    ? ($cn['SUPPLIER_EKOR'] ?? 0)
    : ($cn['SUPPLIER_KG'] ?? 0);

$customer_total = (float) ($cn['CN_CUSTOMER_TOTAL'] ?? 0);
$supplier_total = (float) ($cn['CN_SUPPLIER_TOTAL'] ?? 0);
$cn_total = (float) ($cn['CN_TOTAL'] ?? 0);
?>
<style>
.cn-approval-detail{color:#374151}
.cn-approval-detail .heading{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:22px}
.cn-approval-detail h4{font-size:22px;font-weight:750;color:#171717;margin:0 0 5px}
.cn-approval-detail .muted{font-size:12px;color:#9ca3af}
.cn-approval-detail .back{display:inline-flex;gap:7px;align-items:center;padding:9px 13px;border:1px solid #e5e7eb;border-radius:8px;color:#374151;text-decoration:none;font-size:12px}
.cn-approval-detail .section{background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;overflow:hidden}
.cn-approval-detail .section-head{padding:17px 21px;border-bottom:1px solid #eee}
.cn-approval-detail .section-head h6{font-size:14px;font-weight:750;color:#171717;margin:0}
.cn-approval-detail .section-body{padding:20px}
.cn-approval-detail .label{font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;font-weight:750;margin-bottom:5px}
.cn-approval-detail .value{font-size:13px;color:#171717;font-weight:650;overflow-wrap:anywhere}
.cn-approval-detail .metric{background:#fafafa;border:1px solid #eee;border-radius:10px;padding:15px;height:100%}
.cn-approval-detail .metric strong{display:block;font-size:18px;color:#171717;margin-top:6px}
.cn-approval-detail .status{display:inline-block;padding:6px 10px;border-radius:20px;background:#fff7e6;color:#a16207;font-size:10px;font-weight:750}
.cn-approval-detail .decision{border-top:1px solid #eee;padding-top:20px;margin-top:5px}
.cn-approval-detail .order-panel{border:1px solid #e5e7eb;border-radius:12px;padding:16px;height:100%;background:#fff}
.cn-approval-detail .order-panel h6{font-size:13px;font-weight:750;color:#171717;margin:0 0 16px}
.cn-approval-detail .grand-total{background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:18px}
.cn-approval-detail .grand-total strong{display:block;color:#c2410c;font-size:25px;margin-top:5px}
@media(max-width:767px){.cn-approval-detail .heading{align-items:flex-start;flex-direction:column}}
</style>

<div class="container-fluid py-4 cn-approval-detail">
    <div class="heading">
        <div>
            <h4>Review Credit Note</h4>
            <p class="muted mb-0"><?= html_escape($cn['CN_NO'] ?? '-'); ?> · Approval #<?= (int) ($a['APPROVAL_ID'] ?? 0); ?></p>
        </div>
        <a class="back" href="<?= site_url('credit_note/approval'); ?>"><i class="ti ti-arrow-left"></i> Approval List</a>
    </div>

    <?php if ($this->session->flashdata('credit_note_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('credit_note_error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('credit_note_success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= html_escape($this->session->flashdata('credit_note_success')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-head d-flex justify-content-between align-items-center">
            <h6>Approval Information</h6>
            <span class="status"><?= html_escape($a['APPROVAL_STATUS'] ?? '-'); ?></span>
        </div>
        <div class="section-body">
            <div class="row g-4">
                <div class="col-md-3"><div class="label">Requested By</div><div class="value"><?= html_escape($display_requester); ?></div></div>
                <div class="col-md-3"><div class="label">Requested At</div><div class="value"><?= $format_date($a['REQUESTED_AT'] ?? NULL); ?></div></div>
                <div class="col-md-3"><div class="label">Approver</div><div class="value"><?= html_escape($a['APPROVED_BY'] ?? '-'); ?></div></div>
                <div class="col-md-3"><div class="label">Decision At</div><div class="value"><?= $format_date($a['APPROVED_AT'] ?? NULL); ?></div></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-head"><h6>Credit Note Information</h6></div>
        <div class="section-body">
            <div class="row g-4">
                <div class="col-md-3"><div class="label">Credit Note Number</div><div class="value"><?= html_escape($cn['CN_NO'] ?? '-'); ?></div></div>
                <div class="col-md-3"><div class="label">CN Date</div><div class="value"><?= $format_date($cn['CN_DATE'] ?? NULL, 'd M Y'); ?></div></div>
                <div class="col-md-3"><div class="label">Order Type</div><div class="value"><?= html_escape($type); ?></div></div>
                <div class="col-md-3"><div class="label">CN Price</div><div class="value"><?= $currency($cn['CN_PRICE'] ?? 0); ?></div></div>

                <?php if ($is_customer): ?>
                    <div class="col-md-6"><div class="label">Customer</div><div class="value"><?= html_escape($cn['CUSTOMER_NAME'] ?? '-'); ?> · <?= html_escape($cn['CUST'] ?? '-'); ?></div></div>
                <?php endif; ?>

                <?php if ($is_supplier): ?>
                    <div class="col-md-6"><div class="label">Supplier</div><div class="value"><?= html_escape($cn['SUPPLIER_NAME'] ?? '-'); ?> · <?= html_escape($cn['SUPPLIER'] ?? '-'); ?></div></div>
                <?php endif; ?>

                <div class="col-12"><div class="label">Remark</div><div class="value"><?= nl2br(html_escape($cn['REMARK'] ?? '-')); ?></div></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-head"><h6>Referenced Orders</h6></div>
        <div class="section-body">
            <div class="row g-3">
                <?php if ($is_customer): ?>
                    <div class="<?= $is_supplier ? 'col-lg-6' : 'col-12'; ?>">
                        <div class="order-panel">
                            <h6><i class="ti ti-user me-1"></i>Customer Order</h6>
                            <div class="row g-3">
                                <div class="col-sm-6"><div class="label">Order Detail Number</div><div class="value"><?= html_escape($cn['ORDER_CUSTOMER_NO'] ?? '-'); ?></div></div>
                                <div class="col-sm-6"><div class="label">Basis</div><div class="value"><?= html_escape($cn['CUSTOMER_BASIS'] ?? '-'); ?></div></div>
                                <div class="col-sm-6"><div class="label">Ekor</div><div class="value"><?= $number($cn['CUSTOMER_EKOR'] ?? 0, 0); ?></div></div>
                                <div class="col-sm-6"><div class="label">KG</div><div class="value"><?= $number($cn['CUSTOMER_KG'] ?? 0, 2); ?></div></div>
                                <div class="col-sm-6"><div class="label">Order Total</div><div class="value"><?= $currency($cn['CUSTOMER_ORDER_TOTAL'] ?? 0); ?></div></div>
                                <div class="col-sm-6"><div class="label">Current Remaining</div><div class="value"><?= $currency($cn['CUSTOMER_REMAIN'] ?? 0); ?></div></div>
                                <div class="col-12"><div class="label">Customer CN Total</div><div class="value text-primary"><?= $currency($customer_total); ?></div></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($is_supplier): ?>
                    <div class="<?= $is_customer ? 'col-lg-6' : 'col-12'; ?>">
                        <div class="order-panel">
                            <h6><i class="ti ti-truck me-1"></i>Supplier Order</h6>
                            <div class="row g-3">
                                <div class="col-sm-6"><div class="label">Order Detail Number</div><div class="value"><?= html_escape($cn['ORDER_SUPPLIER_NO'] ?? '-'); ?></div></div>
                                <div class="col-sm-6"><div class="label">Basis</div><div class="value"><?= html_escape($cn['SUPPLIER_BASIS'] ?? '-'); ?></div></div>
                                <div class="col-sm-6"><div class="label">Ekor</div><div class="value"><?= $number($cn['SUPPLIER_EKOR'] ?? 0, 0); ?></div></div>
                                <div class="col-sm-6"><div class="label">KG</div><div class="value"><?= $number($cn['SUPPLIER_KG'] ?? 0, 2); ?></div></div>
                                <div class="col-sm-6"><div class="label">Order Total</div><div class="value"><?= $currency($cn['SUPPLIER_ORDER_TOTAL'] ?? 0); ?></div></div>
                                <div class="col-sm-6"><div class="label">Current Remaining</div><div class="value"><?= $currency($cn['SUPPLIER_REMAIN'] ?? 0); ?></div></div>
                                <div class="col-12"><div class="label">Supplier CN Total</div><div class="value text-success"><?= $currency($supplier_total); ?></div></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-3"><div class="label">Master Order Number</div><div class="value"><?= html_escape($o['ORDER_NO'] ?? '-'); ?></div></div>
                <div class="col-md-3"><div class="label">Master Order Date</div><div class="value"><?= $format_date($o['ORDER_DATE'] ?? NULL, 'd M Y'); ?></div></div>
                <div class="col-md-3"><div class="label">Plant</div><div class="value"><?= html_escape($o['PLANT'] ?? '-'); ?></div></div>
                <div class="col-md-3"><div class="label">Master Status</div><div class="value"><?= html_escape($o['STATUS'] ?? '-'); ?></div></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-head"><h6>Credit Note Calculation</h6></div>
        <div class="section-body">
            <div class="row g-3">
                <?php if ($is_customer): ?>
                    <div class="<?= $is_supplier ? 'col-md-6' : 'col-12'; ?>">
                        <div class="metric">
                            <div class="label">Customer Calculation</div>
                            <div class="value mt-2">
                                <?= html_escape($customer_basis ?: '-'); ?>
                                × <?= $number($customer_quantity, 2); ?>
                                × <?= $currency($cn['CN_PRICE'] ?? 0); ?>
                            </div>
                            <strong><?= $currency($customer_total); ?></strong>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($is_supplier): ?>
                    <div class="<?= $is_customer ? 'col-md-6' : 'col-12'; ?>">
                        <div class="metric">
                            <div class="label">Supplier Calculation</div>
                            <div class="value mt-2">
                                <?= html_escape($supplier_basis ?: '-'); ?>
                                × <?= $number($supplier_quantity, 2); ?>
                                × <?= $currency($cn['CN_PRICE'] ?? 0); ?>
                            </div>
                            <strong><?= $currency($supplier_total); ?></strong>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="grand-total">
                        <div class="label">Grand Total Credit Note</div>
                        <strong><?= $currency($cn_total); ?></strong>
                        <div class="muted mt-2">
                            Customer CN Total (<?= $currency($customer_total); ?>)
                            + Supplier CN Total (<?= $currency($supplier_total); ?>)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($a['APPROVAL_REMARK'])): ?>
        <div class="section">
            <div class="section-head"><h6>Previous Decision Remark</h6></div>
            <div class="section-body"><?= nl2br(html_escape($a['APPROVAL_REMARK'])); ?></div>
        </div>
    <?php endif; ?>

    <?php if ($is_pending && !$is_requester): ?>
        <div class="section">
            <div class="section-head"><h6>Approval Decision</h6></div>
            <div class="section-body">
                <form method="post" action="<?= site_url('credit_note/approval_action/' . (int) ($a['APPROVAL_ID'] ?? 0)); ?>" id="cnApprovalForm">
                    <?php if ($this->config->item('csrf_protection')): ?>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Approval Remark <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="remark" rows="4" maxlength="1000" required placeholder="Enter your reason for approval or rejection"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" name="decision" value="REJECTED" class="btn btn-outline-danger" onclick="return confirm('Reject this Credit Note?')">
                            <i class="ti ti-x me-1"></i>Reject
                        </button>
                        <button type="submit" name="decision" value="APPROVED" class="btn btn-dark" onclick="return confirm('Approve this Credit Note?')">
                            <i class="ti ti-check me-1"></i>Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif ($is_requester && $is_pending): ?>
        <div class="alert alert-warning">Anda adalah requester Credit Note ini. Requester tidak dapat melakukan approval sendiri.</div>
    <?php endif; ?>
</div>
