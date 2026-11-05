
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$summary = $summary ?? [];
$filters = $filters ?? [];
$overdue_data = $overdue_data ?? [];
$customers = $customers ?? [];

$money = function ($value) {
    return number_format((float) $value, 2, ',', '.');
};

$overdue_bucket = function ($days) {
    $days = (int) $days;

    if ($days <= 30) {
        return [
            'label' => '1-30 DAYS',
            'class' => 'bg-warning text-dark'
        ];
    }

    if ($days <= 60) {
        return [
            'label' => '31-60 DAYS',
            'class' => 'bg-orange text-white'
        ];
    }

    if ($days <= 90) {
        return [
            'label' => '61-90 DAYS',
            'class' => 'bg-danger'
        ];
    }

    return [
        'label' => 'OVER 90 DAYS',
        'class' => 'bg-dark'
    ];
};

$total_overdue = (float) ($summary['TOTAL_OUTSTANDING'] ?? 0);

$total_transactions = (int) ($summary['TOTAL_RECORDS'] ?? 0);

$total_customers = (int) ($summary['TOTAL_CUSTOMERS'] ?? 0);

$max_overdue_days = (int) ($summary['MAX_OVERDUE_DAYS'] ?? 0);

$avg_overdue_days = (float) ($summary['AVG_OVERDUE_DAYS'] ?? 0);
?>

<style>
    .overdue-summary-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
        height: 100%;
    }

    .overdue-summary-card .card-body {
        padding: 20px;
    }

    .overdue-label {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .overdue-value {
        font-size: 23px;
        font-weight: 700;
        margin-top: 8px;
        color: #263238;
    }

    .overdue-subtitle {
        font-size: 12px;
        color: #87909a;
        margin-top: 5px;
    }

    .overdue-icon {
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

    .overdue-table {
        min-width: 1600px;
    }

    .overdue-table th {
        white-space: nowrap;
        font-size: 12px;
        vertical-align: middle;
    }

    .overdue-table td {
        vertical-align: middle;
        font-size: 13px;
    }

    .overdue-number {
        text-align: right;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .overdue-customer {
        font-weight: 600;
        color: #344054;
    }

    .overdue-order {
        font-weight: 600;
        color: #1769aa;
        white-space: nowrap;
    }

    .overdue-order-no {
        white-space: nowrap !important;
        min-width: 150px;
    }

    .overdue-days {
        font-weight: 700;
        color: #dc3545;
        white-space: nowrap;
    }

    .overdue-empty {
        padding: 45px 15px !important;
        text-align: center;
        color: #87909a;
    }

    .bg-orange {
        background-color: #fd7e14 !important;
    }

    .overdue-footer {
        background: #f8f9fa;
        font-weight: 700;
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
            <h4 class="mb-1">Customer Overdue Report</h4>

            <div class="text-muted small">
                Outstanding receivables past their due date
            </div>
        </div> -->

        <div class="page-heading">
            <div>
                <div class="eyebrow">
                    <i class="ti ti-chart-bar"></i>
                    Customer Overdue Report
                </div>

                <h1 class="page-title">Customer Overdue Report</h1>

                <p class="page-subtitle">
                    Outstanding receivables past their due date
                </p>
            </div>
        </div>

        <div>
            <a
                href="<?= base_url('reportcustomeroverdue/export-excel') . '?' . http_build_query($filters); ?>"
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

        <!-- TOTAL OVERDUE -->

        <div class="col-xl-3 col-md-6">
            <div class="card overdue-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="overdue-label">
                                Total Overdue
                            </div>

                            <div class="overdue-value text-danger">
                                Rp <?= $money($total_overdue); ?>
                            </div>

                            <div class="overdue-subtitle">
                                <?= number_format($total_transactions); ?>
                                transactions
                            </div>
                        </div>

                        <div class="overdue-icon bg-danger-subtle text-danger">
                            <i class="ti ti-alert-triangle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- TOTAL CUSTOMER -->

        <div class="col-xl-3 col-md-6">
            <div class="card overdue-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="overdue-label">
                                Total Customers
                            </div>

                            <div class="overdue-value">
                                <?= number_format($total_customers); ?>
                            </div>

                            <div class="overdue-subtitle">
                                Customers with outstanding overdue
                            </div>
                        </div>

                        <div class="overdue-icon bg-primary-subtle text-primary">
                            <i class="ti ti-users"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- MAX OVERDUE -->

        <div class="col-xl-3 col-md-6">
            <div class="card overdue-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="overdue-label">
                                Maximum Overdue
                            </div>

                            <div class="overdue-value text-danger">
                                <?= number_format($max_overdue_days); ?> Days
                            </div>

                            <div class="overdue-subtitle">
                                Longest overdue transaction
                            </div>
                        </div>

                        <div class="overdue-icon bg-warning-subtle text-warning">
                            <i class="ti ti-clock-exclamation"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        <!-- AVERAGE OVERDUE -->

        <div class="col-xl-3 col-md-6">
            <div class="card overdue-summary-card">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <div class="overdue-label">
                                Average Overdue
                            </div>

                            <div class="overdue-value">
                                <?= number_format($avg_overdue_days, 1, ',', '.'); ?> Days
                            </div>

                            <div class="overdue-subtitle">
                                Average overdue duration
                            </div>
                        </div>

                        <div class="overdue-icon bg-secondary-subtle text-secondary">
                            <i class="ti ti-calendar-stats"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- =========================================
         OVERDUE BREAKDOWN
    ========================================== -->

    <div class="card filter-card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="mb-0 fw-bold">
                    Overdue Breakdown
                </h6>

                <span class="text-muted small">
                    As of <?= !empty($filters['as_of_date'])
                        ? date('d M Y', strtotime($filters['as_of_date']))
                        : '-'; ?>
                </span>

            </div>

            <?php
            $breakdown = [
                [
                    'label' => '1-30 Days',
                    'amount' => $summary['TOTAL_1_30'] ?? 0,
                    'count' => $summary['COUNT_1_30'] ?? 0,
                    'color' => 'warning'
                ],
                [
                    'label' => '31-60 Days',
                    'amount' => $summary['TOTAL_31_60'] ?? 0,
                    'count' => $summary['COUNT_31_60'] ?? 0,
                    'color' => 'orange'
                ],
                [
                    'label' => '61-90 Days',
                    'amount' => $summary['TOTAL_61_90'] ?? 0,
                    'count' => $summary['COUNT_61_90'] ?? 0,
                    'color' => 'danger'
                ],
                [
                    'label' => 'Over 90 Days',
                    'amount' => $summary['TOTAL_OVER_90'] ?? 0,
                    'count' => $summary['COUNT_OVER_90'] ?? 0,
                    'color' => 'dark'
                ]
            ];
            ?>

            <div class="row g-3">

                <?php foreach ($breakdown as $item): ?>

                    <div class="col-xl-3 col-md-6 col-6">

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
                action="<?= base_url('reportcustomeroverdue'); ?>"
            >

                <div class="row g-3 align-items-end">

                    <!-- AS OF DATE -->

                    <div class="col-xl-3 col-md-4">

                        <label class="form-label">
                            As Of Date
                        </label>

                        <input
                            type="date"
                            name="as_of_date"
                            class="form-control"
                            value="<?= html_escape($filters['as_of_date'] ?? ''); ?>"
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
                                    <?= (($filters['customer'] ?? '') === $customer['CUST'])
                                        ? 'selected'
                                        : ''; ?>
                                >
                                    <?= html_escape(
                                        $customer['CUST'] . ' - ' .
                                        $customer['FULL_NAME']
                                    ); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- SEARCH -->

                    <div class="col-xl-4 col-md-8">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Order No, Customer Order No, Customer..."
                            value="<?= html_escape($filters['search'] ?? ''); ?>"
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
                                href="<?= base_url('reportcustomeroverdue'); ?>"
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
                        Customer Overdue Detail
                    </h6>

                    <div class="small text-muted">
                        <?= number_format(count($overdue_data)); ?>
                        records found
                    </div>
                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-bordered table-hover overdue-table mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">No</th>

                            <th>Customer</th>

                            <th>Master Order</th>

                            <th>Customer Order</th>

                            <th>Order Date</th>

                            <th>TOP</th>

                            <th>Due Date</th>

                            <th class="text-center">
                                Overdue Days
                            </th>

                            <th>Overdue Bucket</th>

                            <th class="text-end">Order Total</th>

                            <th class="text-end">Paid</th>

                            <th class="text-end">Total CN</th>

                            <th class="text-end">Outstanding</th>

                            <th>Payment Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($overdue_data)): ?>

                            <?php
                            $no = 1;

                            $total_order = 0;
                            $total_paid = 0;
                            $total_cn = 0;
                            $total_outstanding = 0;
                            ?>

                            <?php foreach ($overdue_data as $item): ?>

                                <?php
                                $order_total = (float) ($item['ORDER_TOTAL'] ?? 0);
                                $paid = (float) ($item['PAID'] ?? 0);
                                $cn = (float) ($item['TOTAL_CN'] ?? 0);
                                $outstanding = (float) ($item['OUTSTANDING'] ?? 0);

                                $total_order += $order_total;
                                $total_paid += $paid;
                                $total_cn += $cn;
                                $total_outstanding += $outstanding;

                                $bucket = $overdue_bucket($item['OVERDUE_DAYS']);
                                ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>

                                        <div class="overdue-customer">
                                            <?= html_escape($item['CUSTOMER_NAME']); ?>
                                        </div>

                                        <div class="small text-muted">
                                            <?= html_escape($item['CUST']); ?>
                                        </div>

                                    </td>

                                    <td>
                                        <span class="overdue-order">
                                            <?= html_escape($item['ORDER_NO']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="overdue-order-no">
                                            <?= html_escape($item['ORDER_CUSTOMER_NO']); ?>
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= !empty($item['ORDER_DATE'])
                                            ? date('d-m-Y', strtotime($item['ORDER_DATE']))
                                            : '-'; ?>
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
                                        <span class="overdue-days">
                                            <?= number_format((int) $item['OVERDUE_DAYS']); ?>
                                            Days
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge <?= $bucket['class']; ?>">
                                            <?= html_escape($bucket['label']); ?>
                                        </span>
                                    </td>

                                    <td class="overdue-number">
                                        <?= $money($order_total); ?>
                                    </td>

                                    <td class="overdue-number">
                                        <?= $money($paid); ?>
                                    </td>

                                    <td class="overdue-number">
                                        <?= $money($cn); ?>
                                    </td>

                                    <td class="overdue-number fw-bold text-danger">
                                        <?= $money($outstanding); ?>
                                    </td>

                                    <td>
                                        <?= html_escape($item['PAYMENT_STATUS'] ?? '-'); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>


                            <!-- GRAND TOTAL -->

                            <tr class="overdue-footer">

                                <td colspan="9" class="text-end">
                                    GRAND TOTAL
                                </td>

                                <td class="overdue-number">
                                    <?= $money($total_order); ?>
                                </td>

                                <td class="overdue-number">
                                    <?= $money($total_paid); ?>
                                </td>

                                <td class="overdue-number">
                                    <?= $money($total_cn); ?>
                                </td>

                                <td class="overdue-number">
                                    <?= $money($total_outstanding); ?>
                                </td>

                                <td></td>

                            </tr>

                        <?php else: ?>

                            <tr>

                                <td colspan="14" class="overdue-empty">

                                    <i class="ti ti-database-off fs-1 d-block mb-2"></i>

                                    No Customer Overdue data found.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
