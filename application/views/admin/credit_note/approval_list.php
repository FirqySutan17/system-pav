<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$approval_count = !empty($approvals) ? count($approvals) : 0;
$customer_count = 0;
$supplier_count = 0;
$combined_count = 0;
$total_cn = 0;

if (!empty($approvals)) {
    foreach ($approvals as $row) {
        $total_cn += (float) ($row['CN_TOTAL'] ?? 0);
        $type = strtoupper($row['ORDER_TYPE'] ?? '');

        if ($type === 'CUSTOMER') {
            $customer_count++;
        } elseif ($type === 'SUPPLIER') {
            $supplier_count++;
        } elseif ($type === 'CUSTOMER_SUPPLIER') {
            $combined_count++;
        }
    }
}

$format_currency = function ($value) {
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
};

$format_date = function ($value, $format = 'd M Y H:i') {
    if (empty($value)) return '-';
    $timestamp = strtotime($value);
    return $timestamp ? date($format, $timestamp) : '-';
};
?>

<style>
.approval-page{color:#374151}
.approval-page .page-heading{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px}
.approval-page .page-title{color:#171717;font-size:22px;font-weight:750;margin:0 0 5px}
.approval-page .page-subtitle{color:#9ca3af;font-size:13px;margin:0}
.approval-page .summary-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:18px 20px;height:100%}
.approval-page .summary-label{color:#9ca3af;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase}
.approval-page .summary-value{color:#171717;font-size:25px;font-weight:750;margin-top:7px}
.approval-page .summary-icon{width:42px;height:42px;display:flex;align-items:center;justify-content:center;border-radius:12px;background:#fff0e5;color:#f97316;font-size:21px;flex-shrink:0}
.approval-page .approval-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden}
.approval-page .card-heading{padding:19px 22px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;gap:12px}
.approval-page .card-heading h6{color:#171717;font-size:14px;font-weight:750;margin:0}
.approval-page .card-heading small{color:#9ca3af;font-size:12px}
.approval-page .table{margin-bottom:0;min-width:1250px}
.approval-page .table thead th{background:#fafafa;color:#9ca3af;font-size:10px;font-weight:800;letter-spacing:.6px;text-transform:uppercase;padding:14px 16px;border-bottom:1px solid #e5e7eb;white-space:nowrap}
.approval-page .table tbody td{padding:16px;border-color:#f1f1f1;vertical-align:middle;font-size:12px}
.approval-page .table tbody tr:hover{background:#fffaf6}
.approval-page .order-number{color:#171717;font-size:13px;font-weight:750}
.approval-page .sub-text{color:#9ca3af;font-size:11px;margin-top:4px}
.approval-page .party-name{color:#374151;font-weight:650;font-size:12px}
.approval-page .amount{color:#171717;font-weight:750;white-space:nowrap;font-variant-numeric:tabular-nums}
.approval-page .pending-badge{display:inline-flex;align-items:center;gap:5px;padding:6px 10px;border-radius:20px;background:#fff7e6;color:#b7791f;font-size:10px;font-weight:750;white-space:nowrap}
.approval-page .type-badge{display:inline-block;padding:6px 10px;border-radius:20px;font-size:10px;font-weight:750;white-space:nowrap}
.approval-page .type-customer{background:#eaf3ff;color:#2563eb}
.approval-page .type-supplier{background:#ecfdf3;color:#15803d}
.approval-page .type-combined{background:#f3e8ff;color:#7e22ce}
.approval-page .review-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:8px 13px;border:0;border-radius:8px;background:#171717;color:#fff;text-decoration:none;font-size:11px;font-weight:700;white-space:nowrap;transition:.2s ease}
.approval-page .review-btn:hover{background:#f97316;color:#fff;transform:translateY(-1px)}
.approval-page .empty-state{padding:65px 20px;text-align:center}
.approval-page .empty-icon{width:65px;height:65px;margin:0 auto 15px;display:flex;align-items:center;justify-content:center;border-radius:18px;background:#f3f4f6;color:#9ca3af;font-size:30px}
.approval-page .empty-title{color:#374151;font-weight:750;font-size:14px;margin-bottom:5px}
.approval-page .empty-description{color:#9ca3af;font-size:12px}
@media(max-width:767px){.approval-page .page-heading{align-items:flex-start}.approval-page .page-title{font-size:19px}.approval-page .summary-value{font-size:22px}}
</style>

<div class="container-fluid py-4 approval-page">
    <div class="page-heading">
        <div>
            <h4 class="page-title">Approval Credit Note</h4>
            <p class="page-subtitle">Review and manage Credit Notes waiting for approval.</p>
        </div>
        <span class="pending-badge"><i class="ti ti-clock"></i><?= number_format($approval_count); ?> Pending</span>
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

    <div class="row g-3 mb-4">
        <div class="col-xl col-md-4 col-sm-6">
            <div class="summary-card"><div class="d-flex justify-content-between align-items-center">
                <div><div class="summary-label">Pending Approval</div><div class="summary-value"><?= number_format($approval_count); ?></div></div>
                <div class="summary-icon"><i class="ti ti-clipboard-check"></i></div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-sm-6">
            <div class="summary-card"><div class="d-flex justify-content-between align-items-center">
                <div><div class="summary-label">Customer CN</div><div class="summary-value"><?= number_format($customer_count); ?></div></div>
                <div class="summary-icon"><i class="ti ti-user"></i></div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-sm-6">
            <div class="summary-card"><div class="d-flex justify-content-between align-items-center">
                <div><div class="summary-label">Supplier CN</div><div class="summary-value"><?= number_format($supplier_count); ?></div></div>
                <div class="summary-icon"><i class="ti ti-truck"></i></div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-sm-6">
            <div class="summary-card"><div class="d-flex justify-content-between align-items-center">
                <div><div class="summary-label">Combined CN</div><div class="summary-value"><?= number_format($combined_count); ?></div></div>
                <div class="summary-icon"><i class="ti ti-arrows-left-right"></i></div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-sm-6">
            <div class="summary-card"><div class="d-flex justify-content-between align-items-center">
                <div><div class="summary-label">Total CN Pending</div><div class="summary-value" style="font-size:18px;"><?= $format_currency($total_cn); ?></div></div>
                <div class="summary-icon"><i class="ti ti-file-invoice"></i></div>
            </div></div>
        </div>
    </div>

    <div class="approval-card">
        <div class="card-heading">
            <div><h6>Pending Approval List</h6><small>Credit Notes requiring your review and decision.</small></div>
            <span class="text-muted" style="font-size:11px;"><i class="ti ti-list-details me-1"></i><?= number_format($approval_count); ?> Records</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width:45px;">No.</th>
                        <th>Credit Note Number</th>
                        <th>Master Order</th>
                        <th>Order Type</th>
                        <th>Customer Order</th>
                        <th>Supplier Order</th>
                        <th>Customer / Supplier</th>
                        <th class="text-end">CN Total</th>
                        <th>Requested By</th>
                        <th>Requested At</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($approvals)): ?>
                    <?php foreach ($approvals as $i => $row): ?>
                        <?php
                        $order_type = strtoupper($row['ORDER_TYPE'] ?? '');
                        $has_customer = in_array($order_type, ['CUSTOMER', 'CUSTOMER_SUPPLIER'], TRUE);
                        $has_supplier = in_array($order_type, ['SUPPLIER', 'CUSTOMER_SUPPLIER'], TRUE);
                        ?>
                        <tr>
                            <td class="text-muted"><?= $i + 1; ?></td>
                            <td>
                                <div class="order-number"><?= html_escape($row['CN_NO'] ?? '-'); ?></div>
                                <div class="sub-text">CN Date: <?= $format_date($row['CN_DATE'] ?? NULL, 'd M Y'); ?></div>
                            </td>
                            <td>
                                <div class="order-number"><?= html_escape($row['ORDER_NO'] ?? '-'); ?></div>
                                <div class="sub-text">Plant: <?= html_escape($row['PLANT'] ?? '-'); ?> · <?= $format_date($row['ORDER_DATE'] ?? NULL, 'd M Y'); ?></div>
                            </td>
                            <td>
                                <?php if ($order_type === 'CUSTOMER'): ?>
                                    <span class="type-badge type-customer"><i class="ti ti-user me-1"></i>Customer</span>
                                <?php elseif ($order_type === 'SUPPLIER'): ?>
                                    <span class="type-badge type-supplier"><i class="ti ti-truck me-1"></i>Supplier</span>
                                <?php else: ?>
                                    <span class="type-badge type-combined"><i class="ti ti-arrows-left-right me-1"></i>Customer + Supplier</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($has_customer): ?>
                                    <div class="order-number"><?= html_escape($row['ORDER_CUSTOMER_NO'] ?? '-'); ?></div>
                                    <div class="sub-text">Customer Order</div>
                                <?php else: ?>-<?php endif; ?>
                            </td>
                            <td>
                                <?php if ($has_supplier): ?>
                                    <div class="order-number"><?= html_escape($row['ORDER_SUPPLIER_NO'] ?? '-'); ?></div>
                                    <div class="sub-text">Supplier Order</div>
                                <?php else: ?>-<?php endif; ?>
                            </td>
                            <td>
                                <?php if ($has_customer): ?>
                                    <div class="party-name"><?= html_escape($row['CUSTOMER_NAME'] ?? '-'); ?></div>
                                    <div class="sub-text">Customer: <?= html_escape($row['CUST'] ?? '-'); ?></div>
                                <?php endif; ?>
                                <?php if ($has_supplier): ?>
                                    <div class="party-name mt-1"><?= html_escape($row['SUPPLIER_NAME'] ?? '-'); ?></div>
                                    <div class="sub-text">Supplier: <?= html_escape($row['SUPPLIER'] ?? '-'); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><span class="amount"><?= $format_currency($row['CN_TOTAL'] ?? 0); ?></span></td>
                            <td><div class="party-name"><?= html_escape($row['REQUESTED_BY'] ?? '-'); ?></div></td>
                            <td><?= $format_date($row['REQUESTED_AT'] ?? NULL, 'd M Y'); ?><div class="sub-text"><?= $format_date($row['REQUESTED_AT'] ?? NULL, 'H:i'); ?></div></td>
                            <td class="text-center"><span class="pending-badge"><i class="ti ti-clock"></i>Pending</span></td>
                            <td class="text-center">
                                <a href="<?= site_url('credit_note/approval_detail/' . (int) ($row['APPROVAL_ID'] ?? 0)); ?>" class="review-btn">
                                    <i class="ti ti-eye"></i>Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="12"><div class="empty-state">
                        <div class="empty-icon"><i class="ti ti-clipboard-check"></i></div>
                        <div class="empty-title">All Caught Up!</div>
                        <div class="empty-description">There are currently no Credit Notes waiting for approval.</div>
                    </div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
