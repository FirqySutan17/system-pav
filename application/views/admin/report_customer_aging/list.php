
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$summary = $summary ?? [];

$filters = $filters ?? [];

$aging_data = $aging_data ?? [];

$customers = $customers ?? [];

$money = function ($value) {
    return number_format((float) $value, 2, ',', '.');
};

$bucket_class = function ($bucket) {
    switch ($bucket) {
        case 'CURRENT':
            return 'bg-success';

        case '1-30 DAYS':
            return 'bg-warning text-dark';

        case '31-60 DAYS':
            return 'bg-orange text-white';

        case '61-90 DAYS':
            return 'bg-danger';

        case 'OVER 90 DAYS':
            return 'bg-dark';

        default:
            return 'bg-secondary';
    }
};

$overdue_total =
    (float) ($summary['TOTAL_1_30'] ?? 0) +
    (float) ($summary['TOTAL_31_60'] ?? 0) +
    (float) ($summary['TOTAL_61_90'] ?? 0) +
    (float) ($summary['TOTAL_OVER_90'] ?? 0);

$overdue_count =
    (int) ($summary['COUNT_1_30'] ?? 0) +
    (int) ($summary['COUNT_31_60'] ?? 0) +
    (int) ($summary['COUNT_61_90'] ?? 0) +
    (int) ($summary['COUNT_OVER_90'] ?? 0);
?>

<style>
    .aging-summary-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        height: 100%;
    }

    .aging-summary-card .card-body {
        padding: 20px;
    }

    .aging-label {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .aging-value {
        font-size: 23px;
        font-weight: 700;
        margin-top: 8px;
        color: #263238;
    }

    .aging-subtitle {
        font-size: 12px;
        color: #87909a;
        margin-top: 5px;
    }

    .aging-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 20px;
    }

    .filter-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .filter-card .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #495057;
    }

    .aging-table th {
        white-space: nowrap;
        font-size: 12px;
        vertical-align: middle;
    }

    .aging-table td {
        vertical-align: middle;
        font-size: 13px;
    }

    .aging-number {
        text-align: right;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .aging-customer {
        font-weight: 600;
        color: #344054;
    }

    .aging-order {
        font-weight: 600;
        color: #1769aa;
    }

    .aging-empty {
        padding: 45px 15px !important;
        text-align: center;
        color: #87909a;
    }

    .bg-orange {
        background-color: #fd7e14 !important;
    }

    .aging-footer {
        background: #f8f9fa;
        font-weight: 700;
    }
    .aging-order-no {
        white-space: nowrap !important;
        min-width: 150px;
    }

    .aging-table {
        min-width: 1600px;
    }
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 15px;
        margin: 10px 0 0px;
    }
    .eyebrow {
        color: #64748b;
        font-size: .76rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        margin-bottom: 7px;
    }

    .page-title {
        margin: 0;
        font-size: 1.55rem;
        font-weight: 750;
        color: #172b4d;
        letter-spacing: -.035em;
    }

    .page-subtitle {
        margin: 7px 0 0;
        color: #718096;
        font-size: .9rem;
    }
</style>

<div class="container-fluid">

    <!-- =========================================
         PAGE HEADER
    ========================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <!-- <div>
            <h4 class="mb-1">Customer Aging Report</h4>

            <div class="text-muted small">
                Outstanding receivables by aging period
            </div>
        </div> -->

        <div class="page-heading">
            <div>
                <div class="eyebrow">
                    <i class="ti ti-chart-bar"></i>
                    Customer Aging Report
                </div>

                <h1 class="page-title">Customer Aging Report</h1>

                <p class="page-subtitle">
                    Outstanding receivables by aging period
                </p>
            </div>
        </div>

        <div>
            <a
                href="<?= base_url('reportcustomeraging/export-excel') . '?' . http_build_query($filters); ?>"
                class="btn btn-success"
            >
                <i class="ti ti-file-spreadsheet me-1"></i>
                Export Excel
            </a>
        </div>

    </div>


    <!-- =========================================
         SUMMARY CARDS
    ========================================== -->

    <div class="row g-3 mb-4">

        <!-- TOTAL OUTSTANDING -->

        <div class="col-xl-3 col-md-6">
            <div class="card aging-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="aging-label">
                                Total Outstanding
                            </div>

                            <div class="aging-value">
                                Rp <?= $money($summary['TOTAL_OUTSTANDING'] ?? 0); ?>
                            </div>

                            <div class="aging-subtitle">
                                <?= number_format((int) ($summary['TOTAL_RECORDS'] ?? 0)); ?>
                                transactions
                            </div>
                        </div>

                        <div class="aging-icon bg-primary-subtle text-primary">
                            <i class="ti ti-wallet"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- CURRENT -->

        <div class="col-xl-3 col-md-6">
            <div class="card aging-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="aging-label">
                                Current
                            </div>

                            <div class="aging-value text-success">
                                Rp <?= $money($summary['TOTAL_CURRENT'] ?? 0); ?>
                            </div>

                            <div class="aging-subtitle">
                                <?= number_format((int) ($summary['COUNT_CURRENT'] ?? 0)); ?>
                                transactions
                            </div>
                        </div>

                        <div class="aging-icon bg-success-subtle text-success">
                            <i class="ti ti-check-circle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- OVERDUE -->

        <div class="col-xl-3 col-md-6">
            <div class="card aging-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="aging-label">
                                Total Overdue
                            </div>

                            <div class="aging-value text-danger">
                                Rp <?= $money($overdue_total); ?>
                            </div>

                            <div class="aging-subtitle">
                                <?= number_format($overdue_count); ?>
                                transactions
                            </div>
                        </div>

                        <div class="aging-icon bg-danger-subtle text-danger">
                            <i class="ti ti-alert-triangle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- UNCLASSIFIED -->

        <div class="col-xl-3 col-md-6">
            <div class="card aging-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="aging-label">
                                Unclassified
                            </div>

                            <div class="aging-value text-secondary">
                                Rp <?= $money($summary['TOTAL_UNCLASSIFIED'] ?? 0); ?>
                            </div>

                            <div class="aging-subtitle">
                                <?= number_format((int) ($summary['COUNT_UNCLASSIFIED'] ?? 0)); ?>
                                transactions
                            </div>
                        </div>

                        <div class="aging-icon bg-secondary-subtle text-secondary">
                            <i class="ti ti-help-circle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- =========================================
         AGING BREAKDOWN
    ========================================== -->

    <div class="card filter-card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="mb-0 fw-bold">
                    Aging Breakdown
                </h6>

                <span class="text-muted small">
                    As of <?= date('d M Y', strtotime($filters['as_of_date'])); ?>
                </span>

            </div>

            <div class="row g-3">

                <?php
                $breakdown = [
                    [
                        'label' => 'Current',
                        'amount' => $summary['TOTAL_CURRENT'] ?? 0,
                        'count' => $summary['COUNT_CURRENT'] ?? 0,
                        'color' => 'success'
                    ],
                    [
                        'label' => '1–30 Days',
                        'amount' => $summary['TOTAL_1_30'] ?? 0,
                        'count' => $summary['COUNT_1_30'] ?? 0,
                        'color' => 'warning'
                    ],
                    [
                        'label' => '31–60 Days',
                        'amount' => $summary['TOTAL_31_60'] ?? 0,
                        'count' => $summary['COUNT_31_60'] ?? 0,
                        'color' => 'orange'
                    ],
                    [
                        'label' => '61–90 Days',
                        'amount' => $summary['TOTAL_61_90'] ?? 0,
                        'count' => $summary['COUNT_61_90'] ?? 0,
                        'color' => 'danger'
                    ],
                    [
                        'label' => 'Over 90 Days',
                        'amount' => $summary['TOTAL_OVER_90'] ?? 0,
                        'count' => $summary['COUNT_OVER_90'] ?? 0,
                        'color' => 'dark'
                    ],
                    [
                        'label' => 'Unclassified',
                        'amount' => $summary['TOTAL_UNCLASSIFIED'] ?? 0,
                        'count' => $summary['COUNT_UNCLASSIFIED'] ?? 0,
                        'color' => 'secondary'
                    ]
                ];
                ?>

                <?php foreach ($breakdown as $item): ?>

                    <div class="col-xl-2 col-md-4 col-6">

                        <div class="border rounded p-3 h-100">

                            <div class="small text-muted mb-2">
                                <?= $item['label']; ?>
                            </div>

                            <div class="fw-bold">
                                Rp <?= $money($item['amount']); ?>
                            </div>

                            <div class="small text-muted mt-1">
                                <?= number_format((int) $item['count']); ?>
                                transactions
                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>


    <!-- =========================================
         FILTER
    ========================================== -->

    <div class="card filter-card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="<?= base_url('reportcustomeraging'); ?>"
            >

                <div class="row g-3 align-items-end">

                    <!-- AS OF DATE -->

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">
                            As Of Date
                        </label>

                        <input
                            type="date"
                            name="as_of_date"
                            class="form-control"
                            value="<?= html_escape($filters['as_of_date']); ?>"
                            required
                        >

                    </div>


                    <!-- CUSTOMER -->

                    <div class="col-xl-3 col-md-4">

                        <label class="form-label">
                            Customer
                        </label>

                        <select
                            name="customer"
                            class="form-select select2"
                        >

                            <option value="">
                                All Customers
                            </option>

                            <?php foreach ($customers as $customer): ?>

                                <option
                                    value="<?= html_escape($customer['CUST']); ?>"
                                    <?= ($filters['customer'] === $customer['CUST']) ? 'selected' : ''; ?>
                                >
                                    <?= html_escape(
                                        $customer['CUST'] . ' - ' .
                                        $customer['FULL_NAME']
                                    ); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- AGING BUCKET -->

                    <div class="col-xl-2 col-md-4">

                        <label class="form-label">
                            Aging Bucket
                        </label>

                        <select
                            name="aging_bucket"
                            class="form-select"
                        >

                            <option value="">
                                All Buckets
                            </option>

                            <?php
                            $buckets = [
                                'CURRENT' => 'Current',
                                '1-30 DAYS' => '1–30 Days',
                                '31-60 DAYS' => '31–60 Days',
                                '61-90 DAYS' => '61–90 Days',
                                'OVER 90 DAYS' => 'Over 90 Days',
                                'UNCLASSIFIED' => 'Unclassified'
                            ];
                            ?>

                            <?php foreach ($buckets as $value => $label): ?>

                                <option
                                    value="<?= $value; ?>"
                                    <?= ($filters['aging_bucket'] === $value) ? 'selected' : ''; ?>
                                >
                                    <?= $label; ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- SEARCH -->

                    <div class="col-xl-3 col-md-8">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Order No, Customer Order No, Customer..."
                            value="<?= html_escape($filters['search']); ?>"
                        >

                    </div>


                    <!-- ACTION -->

                    <div class="col-xl-2 col-md-4">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-fill"
                            >
                                <i class="ti ti-filter me-1"></i>
                                Filter
                            </button>

                            <a
                                href="<?= base_url('reportcustomeraging'); ?>"
                                class="btn btn-light"
                                title="Reset Filter"
                            >
                                <i class="ti ti-refresh"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- =========================================
         DETAIL TABLE
    ========================================== -->

    <div class="card filter-card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                <div>
                    <h6 class="fw-bold mb-1">
                        Customer Aging Detail
                    </h6>

                    <div class="small text-muted">
                        <?= number_format(count($aging_data)); ?>
                        records found
                    </div>
                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-hover aging-table mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="text-center">No</th>
                            <th>Customer</th>
                            <th>Master Order</th>
                            <th>Customer Order</th>
                            <th>Order Date</th>
                            <th>TOP</th>
                            <th>Due Date</th>
                            <th class="text-center">Aging Days</th>
                            <th>Aging Bucket</th>
                            <th class="text-end">Order Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Total CN</th>
                            <th class="text-end">Outstanding</th>
                            <th>Payment Status</th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($aging_data)): ?>

                            <?php
                            $no = 1;

                            $total_order = 0;
                            $total_paid = 0;
                            $total_cn = 0;
                            $total_outstanding = 0;
                            ?>

                            <?php foreach ($aging_data as $item): ?>

                                <?php
                                $total_order += (float) $item['ORDER_TOTAL'];
                                $total_paid += (float) $item['PAID'];
                                $total_cn += (float) ($item['TOTAL_CN'] ?? 0);
                                $total_outstanding += (float) $item['OUTSTANDING'];
                                ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <div class="aging-customer">
                                            <?= html_escape($item['CUSTOMER_NAME']); ?>
                                        </div>

                                        <div class="small text-muted">
                                            <?= html_escape($item['CUST']); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="aging-order aging-order-no">
                                            <?= html_escape($item['ORDER_NO']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="aging-order-no">
                                            <?= html_escape($item['ORDER_CUSTOMER_NO']); ?>
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= date(
                                            'd-m-Y',
                                            strtotime($item['ORDER_DATE'])
                                        ); ?>
                                    </td>

                                    <td class="text-center">
                                        <?= $item['TERMS_OF_PAYMENT'] !== NULL
                                            ? number_format((int) $item['TERMS_OF_PAYMENT']) . ' Days'
                                            : '-'; ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= !empty($item['DUE_DATE'])
                                            ? date('d-m-Y', strtotime($item['DUE_DATE']))
                                            : '-'; ?>
                                    </td>

                                    <td class="text-center">

                                        <?= $item['AGING_DAYS'] !== NULL
                                            ? number_format((int) $item['AGING_DAYS'])
                                            : '-'; ?>

                                    </td>

                                    <td>

                                        <span class="badge <?= $bucket_class($item['AGING_BUCKET']); ?>">
                                            <?= html_escape($item['AGING_BUCKET']); ?>
                                        </span>

                                    </td>

                                    <td class="aging-number">
                                        <?= $money($item['ORDER_TOTAL']); ?>
                                    </td>

                                    <td class="aging-number">
                                        <?= $money($item['PAID']); ?>
                                    </td>

                                    <td class="aging-number">
                                        <?= $money($item['TOTAL_CN'] ?? 0); ?>
                                    </td>

                                    <td class="aging-number fw-bold">
                                        <?= $money($item['OUTSTANDING']); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($item['PAYMENT_STATUS']); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            <tr class="aging-footer">

                                <td colspan="9" class="text-end">
                                    GRAND TOTAL
                                </td>

                                <td class="aging-number">
                                    <?= $money($total_order); ?>
                                </td>

                                <td class="aging-number">
                                    <?= $money($total_paid); ?>
                                </td>

                                <td class="aging-number">
                                    <?= $money($total_cn); ?>
                                </td>

                                <td class="aging-number">
                                    <?= $money($total_outstanding); ?>
                                </td>

                                <td></td>

                            </tr>

                        <?php else: ?>

                            <tr>
                                <td colspan="14" class="aging-empty">

                                    <i class="ti ti-database-off fs-1 d-block mb-2"></i>

                                    No Customer Aging data found.

                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
