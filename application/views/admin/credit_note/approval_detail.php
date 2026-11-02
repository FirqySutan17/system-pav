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

$display_requester = $requested_by !== ''
    ? $requested_by
    : ($cn['REQUESTED_BY'] ?? '-');
$is_pending = strtoupper((string) ($a['APPROVAL_STATUS'] ?? '')) === 'PENDING';
$format_date = function ($value, $format = 'd M Y H:i') {
    if (empty($value)) return '-';
    $ts = strtotime($value);
    return $ts ? date($format, $ts) : '-';
};
$number = function ($value, $decimal = 2) { return number_format((float) $value, $decimal, ',', '.'); };
$type = $cn['ORDER_TYPE'] ?? '-';
$party = $type === 'CUSTOMER' ? ($cn['CUSTOMER_NAME'] ?? '-') : ($cn['SUPPLIER_NAME'] ?? '-');
?>
<style>
.cn-approval-detail{color:#374151}.cn-approval-detail .heading{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:22px}
.cn-approval-detail h4{font-size:22px;font-weight:750;color:#171717;margin:0 0 5px}.cn-approval-detail .muted{font-size:12px;color:#9ca3af}
.cn-approval-detail .back{display:inline-flex;gap:7px;align-items:center;padding:9px 13px;border:1px solid #e5e7eb;border-radius:8px;color:#374151;text-decoration:none;font-size:12px}
.cn-approval-detail .section{background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-bottom:16px;overflow:hidden}
.cn-approval-detail .section-head{padding:17px 21px;border-bottom:1px solid #eee}.cn-approval-detail .section-head h6{font-size:14px;font-weight:750;color:#171717;margin:0}
.cn-approval-detail .section-body{padding:20px}.cn-approval-detail .label{font-size:10px;color:#9ca3af;text-transform:uppercase;letter-spacing:.5px;font-weight:750;margin-bottom:5px}
.cn-approval-detail .value{font-size:13px;color:#171717;font-weight:650;overflow-wrap:anywhere}.cn-approval-detail .metric{background:#fafafa;border:1px solid #eee;border-radius:10px;padding:15px;height:100%}
.cn-approval-detail .metric strong{display:block;font-size:18px;color:#171717;margin-top:6px}
.cn-approval-detail .status{display:inline-block;padding:6px 10px;border-radius:20px;background:#fff7e6;color:#a16207;font-size:10px;font-weight:750}
.cn-approval-detail .decision{border-top:1px solid #eee;padding-top:20px;margin-top:5px}
</style>
<div class="container-fluid py-4 cn-approval-detail">
    <div class="heading">
        <div><h4>Review Credit Note</h4><p class="muted mb-0"><?= html_escape($cn['CN_NO'] ?? '-'); ?> · Approval #<?= (int) ($a['APPROVAL_ID'] ?? 0); ?></p></div>
        <a class="back" href="<?= site_url('credit_note/approval'); ?>"><i class="ti ti-arrow-left"></i> Approval List</a>
    </div>

    <?php if ($this->session->flashdata('credit_note_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= html_escape($this->session->flashdata('credit_note_error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('credit_note_success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= html_escape($this->session->flashdata('credit_note_success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="section">
        <div class="section-head d-flex justify-content-between align-items-center"><h6>Approval Information</h6><span class="status"><?= html_escape($a['APPROVAL_STATUS'] ?? '-'); ?></span></div>
        <div class="section-body"><div class="row g-4">
            <div class="col-md-3"><div class="label">Requested By</div><div class="value">
    <?= html_escape($display_requester); ?>
</div></div>
            <div class="col-md-3"><div class="label">Requested At</div><div class="value"><?= $format_date($a['REQUESTED_AT'] ?? null); ?></div></div>
            <div class="col-md-3"><div class="label">Approver</div><div class="value"><?= html_escape($a['APPROVED_BY'] ?? '-'); ?></div></div>
            <div class="col-md-3"><div class="label">Decision At</div><div class="value"><?= $format_date($a['APPROVED_AT'] ?? null); ?></div></div>
        </div></div>
    </div>

    <div class="section">
        <div class="section-head"><h6>Credit Note Information</h6></div>
        <div class="section-body">
            <div class="row g-4">
                <div class="col-md-3"><div class="label">Credit Note Number</div><div class="value"><?= html_escape($cn['CN_NO'] ?? '-'); ?></div></div>
                <div class="col-md-3"><div class="label">CN Date</div><div class="value"><?= $format_date($cn['CN_DATE'] ?? null, 'd M Y'); ?></div></div>
                <div class="col-md-3"><div class="label">Order Type</div><div class="value"><?= html_escape($type); ?></div></div>
                <div class="col-md-3"><div class="label">Order Detail No.</div><div class="value"><?= html_escape($cn['ORDER_DETAIL_NO'] ?? '-'); ?></div></div>
                <div class="col-md-6"><div class="label"><?= $type === 'CUSTOMER' ? 'Customer' : 'Supplier'; ?></div><div class="value"><?= html_escape($party); ?></div></div>
                <div class="col-md-6"><div class="label">Remark</div><div class="value"><?= nl2br(html_escape($cn['REMARK'] ?? '-')); ?></div></div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4"><div class="metric"><div class="label">Basis</div><strong><?= html_escape($cn['BASIS'] ?? '-'); ?></strong></div></div>
                <div class="col-md-4"><div class="metric"><div class="label">CN Price</div><strong>Rp <?= $number($cn['CN_PRICE'] ?? 0); ?></strong></div></div>
                <div class="col-md-4"><div class="metric"><div class="label">CN Total</div><strong>Rp <?= $number($cn['CN_TOTAL'] ?? 0); ?></strong></div></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-head"><h6>Referenced Master Order</h6></div>
        <div class="section-body"><div class="row g-4">
            <div class="col-md-3"><div class="label">Order Number</div><div class="value"><?= html_escape($o['ORDER_NO'] ?? '-'); ?></div></div>
            <div class="col-md-3"><div class="label">Order Date</div><div class="value"><?= $format_date($o['ORDER_DATE'] ?? null, 'd M Y'); ?></div></div>
            <div class="col-md-3"><div class="label">Plant</div><div class="value"><?= html_escape($o['PLANT'] ?? '-'); ?></div></div>
            <div class="col-md-3"><div class="label">Master Status (unchanged)</div><div class="value"><?= html_escape($o['STATUS'] ?? '-'); ?></div></div>
            <div class="col-md-6"><div class="label">Customer Code</div><div class="value"><?= html_escape($o['CUST'] ?? '-'); ?> · <?= html_escape($cn['CUSTOMER_NAME'] ?? '-'); ?></div></div>
            <div class="col-md-6"><div class="label">Supplier Code</div><div class="value"><?= html_escape($o['SUPPLIER'] ?? '-'); ?> · <?= html_escape($cn['SUPPLIER_NAME'] ?? '-'); ?></div></div>
        </div></div>
    </div>

    <?php if (!empty($a['APPROVAL_REMARK'])): ?>
        <div class="section"><div class="section-head"><h6>Previous Decision Remark</h6></div><div class="section-body"><?= nl2br(html_escape($a['APPROVAL_REMARK'])); ?></div></div>
    <?php endif; ?>

    <?php if ($is_pending && !$is_requester): ?>
        <div class="section">
            <div class="section-head"><h6>Approval Decision</h6></div>
            <div class="section-body">
                <form method="post" action="<?= site_url('credit_note/approval_action/' . (int) $a['APPROVAL_ID']); ?>" id="cnApprovalForm">
                    <?php if ($this->config->item('csrf_protection')): ?>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Approval Remark <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="remark" rows="4" maxlength="1000" required placeholder="Enter your reason for approval or rejection"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" name="decision" value="REJECTED" class="btn btn-outline-danger" onclick="return confirm('Reject this Credit Note?')"><i class="ti ti-x me-1"></i>Reject</button>
                        <button type="submit" name="decision" value="APPROVED" class="btn btn-dark" onclick="return confirm('Approve this Credit Note?')"><i class="ti ti-check me-1"></i>Approve</button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif ($is_requester && $is_pending): ?>
        <div class="alert alert-warning">Anda adalah requester Credit Note ini. Requester tidak dapat melakukan approval sendiri.</div>
    <?php endif; ?>
</div>
