<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| FORMAT HELPERS
|--------------------------------------------------------------------------
*/
$formatDate = function ($date) {
    if (empty($date)) {
        return '-';
    }
    return date('d/m/Y', strtotime($date));
};

$formatDateTime = function ($date) {
    if (empty($date)) {
        return '-';
    }
    return date('d/m/Y H:i', strtotime($date));
};

$formatMoney = function ($value) {
    if ($value === NULL || $value === '') {
        return '-';
    }
    return number_format((float) $value, 0, ',', '.');
};

$creditNotes = $credit_notes ?? [];
$filters = $filters ?? [
    'date_from' => '',
    'date_to' => '',
    'order_type' => '',
    'status' => '',
    'search' => ''
];

$totalRecords = count($creditNotes);

$exportUrl = base_url('reportcreditnote/export-excel');
$exportQuery = http_build_query(array_filter($filters, function ($value) {
    return $value !== '' && $value !== NULL;
}));
if ($exportQuery !== '') {
    $exportUrl .= '?' . $exportQuery;
}

$statusClass = function ($status) {
    switch (strtoupper(trim((string) $status))) {
        case 'PENDING':
            return 'status-pending';
        case 'APPROVED':
            return 'status-approved';
        case 'REJECTED':
            return 'status-rejected';
        case 'CANCELLED':
            return 'status-cancelled';
        default:
            return 'status-other';
    }
};

$typeLabel = function ($type) {
    switch (strtoupper((string) $type)) {
        case 'CUSTOMER':
            return 'CUSTOMER';
        case 'SUPPLIER':
            return 'SUPPLIER';
        case 'CUSTOMER_SUPPLIER':
            return 'CUSTOMER + SUPPLIER';
        default:
            return $type ?: '-';
    }
};
?>

<style>
.report-credit-note-page { color: #334155; }
.report-credit-note-page .page-heading { margin: 90px 0 20px; }
.report-credit-note-page .eyebrow {
    color: #64748b; font-size: 12px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase; margin-bottom: 5px;
}
.report-credit-note-page .page-title {
    font-size: 25px; font-weight: 750; color: #172554; margin: 0;
}
.report-credit-note-page .page-subtitle {
    color: #64748b; font-size: 13px; margin: 7px 0 0;
}
.report-credit-note-page .panel {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
}
.report-credit-note-page .panel-toolbar {
    padding: 18px; border-bottom: 1px solid #e2e8f0;
}
.report-credit-note-page .filter-grid,
.report-credit-note-page .filter-grid-second {
    display: grid; grid-template-columns: repeat(4, minmax(160px, 1fr)); gap: 13px;
}
.report-credit-note-page .filter-grid-second { margin-top: 13px; }
.report-credit-note-page .filter-label {
    display: block; font-size: 12px; font-weight: 650;
    color: #475569; margin-bottom: 6px;
}
.report-credit-note-page .form-control,
.report-credit-note-page .form-select {
    min-height: 39px; border-color: #dbe3ed; border-radius: 7px; font-size: 13px;
}
.report-credit-note-page .filter-actions {
    display: flex; gap: 8px; align-items: center; flex-wrap: wrap; min-height: 39px;
}
.report-credit-note-page .btn-filter {
    min-height: 38px; border-radius: 7px; font-size: 13px; padding: 7px 16px;
}
.report-credit-note-page .btn-export {
    display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    min-height: 38px; padding: 7px 15px; border-radius: 7px;
    background: #15803d; border: 1px solid #15803d; color: #fff;
    font-size: 13px; font-weight: 650; text-decoration: none;
}
.report-credit-note-page .btn-export:hover { background: #166534; color: #fff; }
.report-credit-note-page .table-area { padding: 0 16px 12px; }
.report-credit-note-page .table-responsive { overflow-x: auto; }
.report-credit-note-page table {
    min-width: 1900px; margin-bottom: 0; font-size: 12px;
}
.report-credit-note-page table thead th {
    background: #f1f5f9; color: #334155; font-weight: 700;
    white-space: nowrap; padding: 13px 10px; border-bottom: 1px solid #dbe3ed;
}
.report-credit-note-page table tbody td {
    padding: 12px 10px; border-bottom: 1px solid #edf2f7;
    vertical-align: middle; white-space: nowrap;
}
.report-credit-note-page table tbody tr:hover { background: #f8fafc; }
.report-credit-note-page .cn-code { color: #1d4ed8; font-weight: 700; }
.report-credit-note-page .party-name { font-weight: 600; color: #334155; }
.report-credit-note-page .type-badge,
.report-credit-note-page .status-badge {
    display: inline-block; padding: 4px 8px; border-radius: 5px;
    font-size: 10px; font-weight: 700;
}
.report-credit-note-page .type-customer { background: #eff6ff; color: #1d4ed8; }
.report-credit-note-page .type-supplier { background: #f0fdf4; color: #15803d; }
.report-credit-note-page .type-both { background: #f5f3ff; color: #6d28d9; }
.report-credit-note-page .status-pending { color: #b45309; background: #fffbeb; }
.report-credit-note-page .status-approved { color: #15803d; background: #f0fdf4; }
.report-credit-note-page .status-rejected { color: #b91c1c; background: #fef2f2; }
.report-credit-note-page .status-cancelled { color: #475569; background: #f1f5f9; }
.report-credit-note-page .status-other { color: #475569; background: #f1f5f9; }
.report-credit-note-page .number-cell {
    text-align: right; font-variant-numeric: tabular-nums;
}
.report-credit-note-page .empty-state {
    text-align: center; padding: 45px 15px !important; color: #94a3b8;
}
.report-credit-note-page .panel-footer {
    display: flex; justify-content: space-between; padding: 14px 18px;
    border-top: 1px solid #e2e8f0; color: #64748b; font-size: 12px;
}
@media (max-width: 992px) {
    .report-credit-note-page .filter-grid,
    .report-credit-note-page .filter-grid-second {
        grid-template-columns: repeat(2, minmax(140px, 1fr));
    }
}
@media (max-width: 576px) {
    .report-credit-note-page .filter-grid,
    .report-credit-note-page .filter-grid-second { grid-template-columns: 1fr; }
    .report-credit-note-page .filter-actions-wrapper { grid-column: span 1 !important; }
    .report-credit-note-page .filter-actions { height: auto; }
    .report-credit-note-page .panel-footer { flex-direction: column; gap: 8px; }
}
</style>

<div class="container-fluid report-credit-note-page px-2 px-lg-3 py-2">

    <div class="page-heading">
        <div class="eyebrow">
            <i class="ti ti-chart-bar me-1"></i>
            Reports
        </div>
        <h1 class="page-title">Report Credit Note</h1>
        <p class="page-subtitle">
            Combined report untuk Customer Credit Note, Supplier Credit Note,
            dan Customer + Supplier Credit Note.
        </p>
    </div>

    <?php if ($this->session->flashdata('report_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-1"></i>
            <?= html_escape($this->session->flashdata('report_error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <section class="panel">

        <form method="GET" action="<?= base_url('reportcreditnote'); ?>">
            <div class="panel-toolbar">

                <!-- ROW 1: DATE, TYPE, STATUS -->
                <div class="filter-grid">
                    <div>
                        <label class="filter-label" for="dateFrom">CN Date From</label>
                        <input type="date" id="dateFrom" name="date_from"
                               class="form-control"
                               value="<?= html_escape($filters['date_from'] ?? ''); ?>">
                    </div>

                    <div>
                        <label class="filter-label" for="dateTo">CN Date To</label>
                        <input type="date" id="dateTo" name="date_to"
                               class="form-control"
                               value="<?= html_escape($filters['date_to'] ?? ''); ?>">
                    </div>

                    <div>
                        <label class="filter-label" for="filterOrderType">Order Type</label>
                        <select id="filterOrderType" name="order_type"
                                class="form-select select2-filter">
                            <option value="">All Types</option>
                            <option value="CUSTOMER" <?= ($filters['order_type'] ?? '') === 'CUSTOMER' ? 'selected' : ''; ?>>
                                Customer Order
                            </option>
                            <option value="SUPPLIER" <?= ($filters['order_type'] ?? '') === 'SUPPLIER' ? 'selected' : ''; ?>>
                                Supplier Order
                            </option>
                            <option value="CUSTOMER_SUPPLIER" <?= ($filters['order_type'] ?? '') === 'CUSTOMER_SUPPLIER' ? 'selected' : ''; ?>>
                                Customer + Supplier Order
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="filter-label" for="filterStatus">Approval Status</label>
                        <select id="filterStatus" name="status" class="form-select select2-filter">
                            <option value="">All Status</option>
                            <?php foreach (['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED'] as $status): ?>
                                <option value="<?= $status; ?>"
                                    <?= ($filters['status'] ?? '') === $status ? 'selected' : ''; ?>>
                                    <?= $status; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- ROW 2: SEARCH AND ACTIONS -->
                <div class="filter-grid-second">
                    <div style="grid-column: span 2;">
                        <label class="filter-label" for="search">Search</label>
                        <input type="text" id="search" name="search" class="form-control"
                               placeholder="CN No. / Order No. / Customer / Supplier / Requester / Approver..."
                               value="<?= html_escape($filters['search'] ?? ''); ?>">
                    </div>

                    <div class="filter-actions-wrapper" style="grid-column: span 2;">
                        <label class="filter-label">&nbsp;</label>
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-filter">
                                <i class="ti ti-search me-1"></i> Search
                            </button>

                            <a href="<?= base_url('reportcreditnote'); ?>"
                               class="btn btn-outline-secondary btn-filter">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>

                            <a href="<?= html_escape($exportUrl); ?>" class="btn-export">
                                <i class="ti ti-file-spreadsheet"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <!-- REPORT TABLE -->
        <div class="table-area">
            <div class="table-responsive">
                <table class="table align-middle" id="reportCreditNoteTable">
                    <thead>
                        <tr>
                            <th>CN No.</th>
                            <th>CN Date</th>
                            <th>Order Type</th>
                            <th>Master Order No.</th>
                            <th>Customer Order No.</th>
                            <th>Supplier Order No.</th>
                            <th>Customer</th>
                            <th>Supplier</th>
                            <th class="text-end">CN Price</th>
                            <th class="text-end">CN Customer Total</th>
                            <th class="text-end">CN Supplier Total</th>
                            <th class="text-end">CN Total</th>
                            <th class="text-center">Approval Status</th>
                            <th>Requested By</th>
                            <th>Requested At</th>
                            <th>Approved By</th>
                            <th>Approved At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($creditNotes)): ?>
                            <?php foreach ($creditNotes as $row): ?>
                                <?php
                                    $type = strtoupper($row['ORDER_TYPE'] ?? '');
                                    $typeClass = 'type-both';
                                    if ($type === 'CUSTOMER') {
                                        $typeClass = 'type-customer';
                                    } elseif ($type === 'SUPPLIER') {
                                        $typeClass = 'type-supplier';
                                    }

                                    $status = strtoupper(trim((string) ($row['APPROVAL_STATUS'] ?? '')));
                                ?>
                                <tr>
                                    <td>
                                        <span class="cn-code">
                                            #<?= html_escape($row['CN_NO'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td><?= $formatDate($row['CN_DATE'] ?? NULL); ?></td>
                                    <td>
                                        <span class="type-badge <?= $typeClass; ?>">
                                            <?= html_escape($typeLabel($type)); ?>
                                        </span>
                                    </td>
                                    <td><?= html_escape($row['ORDER_NO'] ?? '-'); ?></td>
                                    <td><?= html_escape($row['ORDER_CUSTOMER_NO'] ?? '-'); ?></td>
                                    <td><?= html_escape($row['ORDER_SUPPLIER_NO'] ?? '-'); ?></td>
                                    <td>
                                        <span class="party-name">
                                            <?= html_escape($row['CUSTOMER_NAME'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="party-name">
                                            <?= html_escape($row['SUPPLIER_NAME'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td class="number-cell"><?= $formatMoney($row['CN_PRICE'] ?? NULL); ?></td>
                                    <td class="number-cell"><?= $formatMoney($row['CN_CUSTOMER_TOTAL'] ?? NULL); ?></td>
                                    <td class="number-cell"><?= $formatMoney($row['CN_SUPPLIER_TOTAL'] ?? NULL); ?></td>
                                    <td class="number-cell"><strong><?= $formatMoney($row['CN_TOTAL'] ?? NULL); ?></strong></td>
                                    <td class="text-center">
                                        <span class="status-badge <?= $statusClass($status); ?>">
                                            <?= html_escape($status ?: '-'); ?>
                                        </span>
                                    </td>
                                    <td><?= html_escape($row['REQUESTED_BY'] ?? '-'); ?></td>
                                    <td><?= $formatDateTime($row['REQUESTED_AT'] ?? NULL); ?></td>
                                    <td><?= html_escape($row['APPROVED_BY'] ?? '-'); ?></td>
                                    <td><?= $formatDateTime($row['APPROVED_AT'] ?? NULL); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="17" class="empty-state">
                                    <i class="ti ti-folder-open fs-2 d-block mb-2"></i>
                                    Tidak ada data Credit Note.
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
                data Credit Note
            </div>
            <div>
                Total Records: <strong><?= number_format($totalRecords); ?></strong>
            </div>
        </div>

    </section>
</div>

<script>
$(document).ready(function () {

    // SELECT2 FILTERS
    $('.report-credit-note-page .select2-filter').each(function () {
        var $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            width: '100%',
            placeholder: '-- PILIH --',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    });

    // VALIDATE DATE RANGE
    $('#dateFrom, #dateTo').on('change', function () {
        var dateFrom = $('#dateFrom').val();
        var dateTo = $('#dateTo').val();

        if (dateFrom && dateTo && dateFrom > dateTo) {
            $('#dateTo').val('');
        }
    });

});
</script>
