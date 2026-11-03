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

$statusConfig = function ($status) {
    $status = strtoupper(trim((string) $status));

    $config = [
        'POSTED' => ['class' => 'status-posted', 'label' => 'POSTED'],
        'VOID'   => ['class' => 'status-void', 'label' => 'VOID']
    ];

    return $config[$status] ?? [
        'class' => 'status-void',
        'label' => $status ?: '-'
    ];
};

$totalRecords = count($cash_ins);
?>

<style>
/* =====================================================
   CASH IN LIST PAGE
===================================================== */
.cash-in-page { color: #243247; }

.cash-in-page .page-heading {
    display:flex; align-items:flex-start; justify-content:space-between;
    gap:16px; flex-wrap:wrap; margin:90px 0 22px;
}
.cash-in-page .eyebrow {
    display:inline-flex; align-items:center; gap:7px; color:#64748b;
    font-size:.76rem; font-weight:700; letter-spacing:.09em;
    text-transform:uppercase; margin-bottom:7px;
}
.cash-in-page .page-title {
    margin:0; font-size:1.55rem; font-weight:750;
    letter-spacing:-.035em; color:#172b4d;
}
.cash-in-page .page-subtitle { margin:7px 0 0; color:#718096; font-size:.92rem; }

.cash-in-page .panel {
    border:1px solid #e7edf5; border-radius:16px; background:#fff;
    box-shadow:0 8px 28px rgba(22,42,75,.055); overflow:hidden;
}
.cash-in-page .panel-toolbar {
    display:grid; grid-template-columns:minmax(0,1fr) auto;
    align-items:end; gap:16px; padding:20px 22px;
    border-bottom:1px solid #edf1f7;
    background:linear-gradient(180deg,#fff 0%,#fbfcff 100%);
}
.cash-in-page .filter-grid {
    grid-column:1/-1; display:grid;
    grid-template-columns:minmax(0,1.3fr) minmax(0,.9fr)
        minmax(0,.9fr) minmax(0,.85fr) minmax(0,.8fr) minmax(0,1.5fr);
    gap:12px; width:100%; min-width:0;
}
.cash-in-page .filter-field { min-width:0; width:100%; }
.cash-in-page .filter-label {
    display:block; margin:0 0 6px; color:#718096;
    font-size:.73rem; font-weight:750; letter-spacing:.04em; text-transform:uppercase;
}
.cash-in-page .form-select, .cash-in-page .form-control {
    width:100%; min-width:0; min-height:44px; box-sizing:border-box;
    border:1px solid #dfe6f0; border-radius:10px; box-shadow:none;
    color:#334155; background-color:#fff; font-size:.84rem;
}
.cash-in-page .form-select:focus, .cash-in-page .form-control:focus {
    border-color:#8aa4ff; box-shadow:0 0 0 3px rgba(99,126,255,.12);
}
.cash-in-page .search-wrap { position:relative; width:100%; min-width:0; }
.cash-in-page .search-wrap .search-icon {
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    color:#94a3b8; pointer-events:none;
}
.cash-in-page .search-wrap .form-control { padding-left:40px; }
.cash-in-page .filter-actions {
    grid-column:1; grid-row:2; display:flex; align-items:center;
    justify-content:flex-start; gap:8px; min-width:0;
}
.cash-in-page .btn-filter {
    min-height:44px; border-radius:10px; padding:0 15px;
    font-weight:650; white-space:nowrap; display:inline-flex;
    align-items:center; justify-content:center;
}
.cash-in-page .btn-add {
    grid-column:2; grid-row:2; display:inline-flex; align-items:center;
    justify-content:center; gap:8px; justify-self:end; align-self:center;
    min-height:44px; padding:0 17px; border:0; border-radius:10px;
    background:linear-gradient(135deg,#627dff,#4965e8); color:#fff;
    font-weight:650; box-shadow:0 5px 12px rgba(79,103,232,.2);
    text-decoration:none; white-space:nowrap; transition:transform .15s,box-shadow .15s;
}
.cash-in-page .btn-add:hover {
    color:#fff; transform:translateY(-1px);
    box-shadow:0 8px 16px rgba(79,103,232,.25);
}
.cash-in-page .table-area { padding:10px 12px 0; }
.cash-in-page .table-responsive { width:100%; overflow-x:auto; }
.cash-in-page #mainTable {
    width:100%; margin:0; border-collapse:separate; border-spacing:0; white-space:nowrap;
}
.cash-in-page #mainTable thead th {
    padding:15px 14px; background:#f7f9fc; color:#66758b;
    font-size:.73rem; text-transform:uppercase; letter-spacing:.055em;
    font-weight:750; white-space:nowrap; border-top:0; border-bottom:1px solid #e8edf4;
}
.cash-in-page #mainTable tbody td {
    padding:15px 14px; border-bottom:1px solid #edf1f6;
    color:#43536a; vertical-align:middle; font-size:.86rem;
}
.cash-in-page #mainTable tbody tr:hover { background:#f8faff; }
.cash-in-page #mainTable tbody tr:last-child td { border-bottom:0; }
.cash-in-page .cash-in-code { font-weight:750; color:#3d58d8; text-decoration:none; }
.cash-in-page .cash-in-code:hover { color:#263fb7; }
.cash-in-page .party-code { display:block; color:#243247; font-weight:650; }
.cash-in-page .party-name {
    display:block; margin-top:4px; color:#8491a4; font-size:.76rem;
    max-width:190px; overflow:hidden; text-overflow:ellipsis;
}
.cash-in-page .number-cell {
    font-variant-numeric:tabular-nums; text-align:right; font-weight:650;
}
.cash-in-page .order-list {
    max-width:230px; white-space:normal; line-height:1.7;
    color:#52658b; font-size:.78rem;
}
.cash-in-page .method-badge, .cash-in-page .type-badge {
    display:inline-flex; align-items:center; padding:6px 9px;
    border-radius:7px; font-size:.69rem; font-weight:750; letter-spacing:.025em;
}
.cash-in-page .method-transfer { color:#425bd4; background:#edf0ff; }
.cash-in-page .method-cash { color:#087f5b; background:#e8f7f0; }
.cash-in-page .type-fifo { color:#087f5b; background:#e8f7f0; }
.cash-in-page .type-manual { color:#9a5a00; background:#fff5df; }
.cash-in-page .status-badge {
    display:inline-flex; align-items:center; gap:6px; padding:6px 10px;
    border-radius:999px; font-size:.7rem; line-height:1; font-weight:750;
    letter-spacing:.025em;
}
.cash-in-page .status-badge:before {
    content:''; width:6px; height:6px; border-radius:50%; display:inline-block;
}
.cash-in-page .status-posted { color:#087f5b; background:#e8f7f0; }
.cash-in-page .status-posted:before { background:#0b9b6a; }
.cash-in-page .status-void { color:#c03939; background:#fff0f0; }
.cash-in-page .status-void:before { background:#dc3545; }
.cash-in-page .action-cell { width:1%; white-space:nowrap; }
.cash-in-page .action-group {
    display:flex; justify-content:center; align-items:center; gap:5px;
}
.cash-in-page .panel-footer {
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; flex-wrap:wrap; padding:16px 22px 20px;
    color:#7b8798; font-size:.84rem;
}
.cash-in-page .empty-state { padding:38px 16px!important; text-align:center; color:#8491a4!important; }
.cash-in-page .empty-state i {
    display:block; font-size:30px; margin-bottom:10px; color:#c2ccda;
}
.cash-in-page .select2-container { width:100%!important; min-width:0; }
.cash-in-page .select2-container--default .select2-selection--single {
    height:44px!important; border:1px solid #dfe6f0!important;
    border-radius:10px!important; background:#fff!important; box-shadow:none!important;
}
.cash-in-page .select2-container--default .select2-selection--single .select2-selection__rendered {
    height:42px; line-height:42px!important; padding-left:13px!important;
    padding-right:35px!important; color:#334155!important; font-size:.84rem;
}
.cash-in-page .select2-container--default .select2-selection--single .select2-selection__arrow {
    height:42px!important; right:8px!important;
}
.cash-in-page .select2-dropdown {
    border:1px solid #dfe6f0!important; border-radius:10px!important;
    overflow:hidden; box-shadow:0 8px 24px rgba(22,42,75,.10); z-index:9999;
}
.cash-in-page .select2-results__option { padding:9px 13px!important; font-size:.84rem; color:#334155; }

@media (max-width:1199px) { .cash-in-page .filter-grid { grid-template-columns:repeat(3,minmax(0,1fr)); } }
@media (max-width:991px) { .cash-in-page .filter-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media (max-width:767px) {
    .cash-in-page .page-heading { margin-top:80px; }
    .cash-in-page .page-title { font-size:1.3rem; }
    .cash-in-page .panel-toolbar { grid-template-columns:minmax(0,1fr); padding:16px; }
    .cash-in-page .filter-grid { grid-column:1; grid-template-columns:1fr; }
    .cash-in-page .filter-actions { grid-column:1; grid-row:auto; width:100%; }
    .cash-in-page .filter-actions .btn { flex:1; }
    .cash-in-page .btn-add { grid-column:1; grid-row:auto; justify-self:stretch; width:100%; }
    .cash-in-page .table-area { padding:0 8px; }
    .cash-in-page .panel-footer { padding:14px 16px; }
}
@media (max-width:480px) {
    .cash-in-page .filter-actions { flex-direction:column; align-items:stretch; }
    .cash-in-page .filter-actions .btn { width:100%; }
    .cash-in-page .panel-footer { flex-direction:column; align-items:flex-start; }
}
</style>

<div class="container-fluid cash-in-page px-2 px-lg-3 py-2">

    <div class="page-heading">
        <div>
            <div class="eyebrow"><i class="ti ti-wallet"></i> Accounting</div>
            <h1 class="page-title">Cash In Management</h1>
            <p class="page-subtitle">
                Kelola penerimaan pembayaran Customer dan monitoring alokasi Customer Order.
            </p>
        </div>
    </div>

    <?php if ($this->session->flashdata('cash_in_success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti ti-circle-check me-1"></i>
            <?= html_escape($this->session->flashdata('cash_in_success')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('cash_in_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-1"></i>
            <?= html_escape($this->session->flashdata('cash_in_error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <section class="panel">
        <form method="GET" action="<?= base_url('cashin'); ?>">
            <div class="panel-toolbar">
                <div class="filter-grid">

                    <div class="filter-field">
                        <label class="filter-label" for="filterCustomer">Customer</label>
                        <select id="filterCustomer" name="customer" class="form-select select2-filter">
                            <option value="">Semua Customer</option>
                            <?php if (!empty($customers)): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= html_escape($customer['CUST']); ?>"
                                        <?= $filters['customer'] === $customer['CUST'] ? 'selected' : ''; ?>>
                                        <?= html_escape($customer['CUST'] . ' - ' . $customer['FULL_NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="dateFrom">Cash In Date From</label>
                        <input type="date" id="dateFrom" name="date_from" class="form-control"
                            value="<?= html_escape($filters['date_from']); ?>">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="dateTo">Cash In Date To</label>
                        <input type="date" id="dateTo" name="date_to" class="form-control"
                            value="<?= html_escape($filters['date_to']); ?>">
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="filterMethod">Cash In Method</label>
                        <select id="filterMethod" name="method" class="form-select">
                            <option value="">All Methods</option>
                            <option value="TRANSFER" <?= $filters['method'] === 'TRANSFER' ? 'selected' : ''; ?>>TRANSFER</option>
                            <option value="CASH" <?= $filters['method'] === 'CASH' ? 'selected' : ''; ?>>CASH</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="filterStatus">Status</label>
                        <select id="filterStatus" name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="POSTED" <?= $filters['status'] === 'POSTED' ? 'selected' : ''; ?>>POSTED</option>
                            <option value="VOID" <?= $filters['status'] === 'VOID' ? 'selected' : ''; ?>>VOID</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label class="filter-label" for="search">Search</label>
                        <div class="search-wrap">
                            <i class="ti ti-search search-icon"></i>
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Cash In No, Slip No, Customer Order..."
                                value="<?= html_escape($filters['search']); ?>"
                                aria-label="Search Cash In">
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary btn-filter">
                        <i class="ti ti-search me-1"></i> Search
                    </button>
                    <a href="<?= base_url('cashin'); ?>" class="btn btn-outline-secondary btn-filter">Reset</a>
                </div>

                <a href="<?= base_url('cashin/create'); ?>" class="btn-add">
                    <i class="ti ti-plus"></i> Create Cash In
                </a>
            </div>
        </form>

        <div class="table-area">
            <div class="table-responsive">
                <table class="table align-middle" id="mainTable">
                    <thead>
                        <tr>
                            <th>Cash In Number</th>
                            <th>Cash In Date</th>
                            <th>Customer</th>
                            <th class="text-center">Method</th>
                            <th class="text-center">Type</th>
                            <th>Slip No</th>
                            <th>Customer Orders</th>
                            <th class="text-end">Total Cash In</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                    <?php if (!empty($cash_ins)): ?>
                        <?php foreach ($cash_ins as $row): ?>
                            <?php
                                $status = $statusConfig($row['STATUS']);
                                $method = strtoupper($row['CASH_IN_METHOD']);
                                $type = strtoupper($row['CASH_IN_TYPE']);
                                $methodClass = $method === 'CASH' ? 'method-cash' : 'method-transfer';
                                $typeClass = $type === 'MANUAL' ? 'type-manual' : 'type-fifo';
                            ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('cashin/detail/' . (int) $row['CASH_IN_ID']); ?>"
                                       class="cash-in-code">
                                        #<?= html_escape($row['CASH_IN_NO']); ?>
                                    </a>
                                </td>
                                <td><?= $formatDate($row['CASH_IN_DATE']); ?></td>
                                <td>
                                    <span class="party-code"><?= html_escape($row['CUSTOMER']); ?></span>
                                    <span class="party-name"
                                          title="<?= html_escape($row['CUSTOMER_NAME'] ?: '-'); ?>">
                                        <?= html_escape($row['CUSTOMER_NAME'] ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="method-badge <?= $methodClass; ?>"><?= html_escape($method); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="type-badge <?= $typeClass; ?>"><?= html_escape($type); ?></span>
                                </td>
                                <td><?= html_escape($row['SLIP_NO'] ?: '-'); ?></td>
                                <td><div class="order-list">#<?= html_escape($row['ORDER_LIST'] ?: '-'); ?></div></td>
                                <td class="number-cell"><?= $formatMoney($row['TOTAL_CASH_IN']); ?></td>
                                <td class="text-center">
                                    <span class="status-badge <?= $status['class']; ?>">
                                        <?= html_escape($status['label']); ?>
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-group">
                                        <a href="<?= base_url('cashin/detail/' . (int) $row['CASH_IN_ID']); ?>"
                                           class="btn btn-info btn-sm" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <?php if ($row['STATUS'] === 'POSTED' && empty($row['HAS_LATER_CASH_IN'])): ?>
                                            <a href="<?= base_url('cashin/edit/' . (int) $row['CASH_IN_ID']); ?>"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                class="btn btn-danger btn-sm btn-void-cash-in"
                                                data-id="<?= (int) $row['CASH_IN_ID']; ?>"
                                                title="Void">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="empty-state">
                                <i class="ti ti-folder-open"></i>
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
                dari <strong><?= number_format($totalRecords); ?></strong> data Cash In
            </div>
            <div>Total Records: <strong><?= number_format($totalRecords); ?></strong></div>
        </div>
    </section>
</div>

<script>
$(document).ready(function () {
    $('.cash-in-page .select2-filter').each(function () {
        const $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.select2({
            width: '100%',
            placeholder: 'Pilih...',
            allowClear: true,
            minimumResultsForSearch: 0
        });
    });

    $(document).on('click', '.btn-void-cash-in', function () {
        const cashInId = $(this).data('id');

        if (!cashInId) {
            alert('Cash In ID tidak valid.');
            return;
        }

        if (!confirm('Apakah Anda yakin ingin melakukan Void Cash In ini?')) {
            return;
        }

        const $button = $(this);
        $button.prop('disabled', true);

        $.ajax({
            url: "<?= base_url('cashin/void/'); ?>" + cashInId,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.ok) {
                    alert(response.message || 'Cash In berhasil di-VOID.');
                    window.location.reload();
                } else {
                    alert(response.message || 'Gagal melakukan Void Cash In.');
                    $button.prop('disabled', false);
                }
            },
            error: function (xhr) {
                let message = 'Terjadi kesalahan saat melakukan Void Cash In.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
                $button.prop('disabled', false);
            }
        });
    });
});
</script>
