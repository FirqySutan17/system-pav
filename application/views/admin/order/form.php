
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$today = date('Y-m-d');
?>

<style>
/* =====================================================
   ORDER FORM PAGE
===================================================== */

.order-create-page {
    color: #243247;
    padding-bottom: 30px;
}

/* PAGE HEADING */

.order-create-page .page-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin: 90px 0 22px;
}

.order-create-page .eyebrow {
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

.order-create-page .page-title {
    margin: 0;
    color: #172b4d;
    font-size: 1.55rem;
    font-weight: 750;
    letter-spacing: -.035em;
}

.order-create-page .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .92rem;
}

.order-create-page .breadcrumb {
    background: transparent;
    margin: 0;
    padding: 8px 0;
    font-size: .82rem;
}

.order-create-page .breadcrumb a {
    color: #526fe5;
    text-decoration: none;
}

/* =====================================================
   FORM CARD
===================================================== */

.order-create-page .form-card {
    margin-bottom: 20px;
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22, 42, 75, .055);
    overflow: visible;
}

.order-create-page .form-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 17px 22px;
    border-bottom: 1px solid #edf1f7;
    border-radius: 16px 16px 0 0;
    background: linear-gradient(180deg, #fff 0%, #fbfcff 100%);
}

.order-create-page .section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 11px;
    font-size: 19px;
}

.order-create-page .icon-master {
    color: #4965e8;
    background: #edf0ff;
}

.order-create-page .icon-customer {
    color: #087f5b;
    background: #e8f7f0;
}

.order-create-page .icon-supplier {
    color: #2875b9;
    background: #eaf4ff;
}

.order-create-page .icon-difference {
    color: #b7791f;
    background: #fff5df;
}

.order-create-page .section-title {
    margin: 0;
    color: #243247;
    font-size: .98rem;
    font-weight: 750;
}

.order-create-page .section-description {
    margin: 3px 0 0;
    color: #8491a4;
    font-size: .76rem;
}

.order-create-page .form-card-body {
    padding: 22px;
}

/* =====================================================
   FORM ELEMENTS
===================================================== */

.order-create-page .form-group {
    margin-bottom: 18px;
}

.order-create-page .form-label {
    display: block;
    margin-bottom: 7px;
    color: #64748b;
    font-size: .75rem;
    font-weight: 750;
    letter-spacing: .035em;
    text-transform: uppercase;
}

.order-create-page .required {
    color: #dc3545;
}

.order-create-page .form-control,
.order-create-page .form-select {
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

.order-create-page .form-control:focus,
.order-create-page .form-select:focus {
    border-color: #8aa4ff;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .12);
}

.order-create-page .form-control[readonly] {
    background: #f7f9fc;
    color: #64748b;
    font-weight: 600;
}

.order-create-page .form-control::placeholder {
    color: #a0acbd;
}

.order-create-page .input-hint {
    display: block;
    margin-top: 6px;
    color: #94a3b8;
    font-size: .73rem;
}

/* =====================================================
   SELECT2
===================================================== */

.order-create-page .select2-container {
    width: 100% !important;
    min-width: 0;
}

.order-create-page .select2-container--default
.select2-selection--single {
    height: 44px !important;
    border: 1px solid #dfe6f0 !important;
    border-radius: 10px !important;
    background: #fff !important;
    box-shadow: none !important;
}

.order-create-page .select2-container--default
.select2-selection--single
.select2-selection__rendered {
    height: 42px;
    line-height: 42px !important;
    padding-left: 13px !important;
    padding-right: 35px !important;
    color: #334155 !important;
    font-size: .86rem;
}

.order-create-page .select2-container--default
.select2-selection--single
.select2-selection__placeholder {
    color: #a0acbd !important;
}

.order-create-page .select2-container--default
.select2-selection--single
.select2-selection__arrow {
    height: 42px !important;
    right: 8px !important;
}

.order-create-page .select2-container--default.select2-container--focus
.select2-selection--single,
.order-create-page .select2-container--default.select2-container--open
.select2-selection--single {
    border-color: #8aa4ff !important;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .12) !important;
}

.order-create-page .select2-dropdown {
    border: 1px solid #dfe6f0 !important;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(22, 42, 75, .10);
    z-index: 9999;
}

.order-create-page .select2-results__option {
    padding: 9px 13px !important;
    color: #334155;
    font-size: .84rem;
}

.order-create-page .select2-container--default
.select2-results__option--highlighted.select2-results__option--selectable {
    background: #eef2ff !important;
    color: #3d58d8 !important;
}

.order-create-page .select2-container--default
.select2-results__option--selected {
    background: #f1f4ff !important;
    color: #3d58d8 !important;
}

.order-create-page .select2-search--dropdown {
    padding: 8px !important;
}

.order-create-page .select2-search--dropdown
.select2-search__field {
    min-height: 38px !important;
    padding: 7px 10px !important;
    border: 1px solid #dfe6f0 !important;
    border-radius: 8px !important;
    outline: none;
    font-size: .84rem;
}

.order-create-page .select2-search--dropdown
.select2-search__field:focus {
    border-color: #8aa4ff !important;
    box-shadow: 0 0 0 3px rgba(99, 126, 255, .10);
}

/* =====================================================
   CREDIT INFORMATION
===================================================== */

.order-create-page .credit-info-card {
    padding: 16px 18px;
    border: 1px solid #dfe7fb;
    border-radius: 12px;
    background: #f5f8ff;
    min-height: 105px;
}

.order-create-page .credit-balance-card {
    background: #f0faf5;
    border-color: #d3eddf;
}

.order-create-page .credit-info-label {
    display: block;
    margin-bottom: 9px;
    color: #718096;
    font-size: .73rem;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.order-create-page .credit-info-value {
    color: #3d58d8;
    font-size: 1.25rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.order-create-page .credit-balance-card .credit-info-value {
    color: #087f5b;
}

.order-create-page .credit-info-card small {
    display: block;
    margin-top: 4px;
    color: #94a3b8;
    font-size: .72rem;
}

.order-create-page .credit-status-message {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 12px 14px;
    margin-bottom: 18px;
    border: 1px solid #dce7ff;
    border-radius: 10px;
    background: #f5f8ff;
    color: #52658b;
    font-size: .8rem;
    line-height: 1.5;
}

.order-create-page .credit-status-message i {
    margin-top: 2px;
    font-size: 17px;
}

.order-create-page .credit-status-message.warning {
    border-color: #f4e3bd;
    background: #fff9eb;
    color: #946200;
}

.order-create-page .credit-status-message.error {
    border-color: #f5d0d0;
    background: #fff5f5;
    color: #b42318;
}

.order-create-page .credit-status-message.success {
    border-color: #cceedd;
    background: #f0faf5;
    color: #087f5b;
}

/* =====================================================
   CALCULATED FIELDS
===================================================== */

.order-create-page .calculated-field {
    background: #f7f9fc !important;
    color: #263fb7 !important;
    font-weight: 750 !important;
}

.order-create-page .total-field {
    background: #f0f4ff !important;
    border-color: #dce5ff !important;
    color: #3d58d8 !important;
    font-size: .95rem !important;
    font-weight: 750 !important;
}

.order-create-page .payment-type-field {
    background: #e8f7f0 !important;
    border-color: #cceedd !important;
    color: #087f5b !important;
    font-weight: 750 !important;
}

/* =====================================================
   INFORMATION NOTE
===================================================== */

.order-create-page .info-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 15px;
    border: 1px solid #dce7ff;
    border-radius: 10px;
    background: #f5f8ff;
    color: #52658b;
    font-size: .81rem;
    line-height: 1.6;
}

.order-create-page .info-note i {
    margin-top: 2px;
    color: #5270df;
    font-size: 17px;
}

/* =====================================================
   PRICE DIFFERENCE
===================================================== */

.order-create-page .difference-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.order-create-page .difference-item {
    padding: 17px;
    border: 1px solid #e7edf5;
    border-radius: 12px;
    background: #fbfcff;
}

.order-create-page .difference-label {
    display: block;
    margin-bottom: 9px;
    color: #8491a4;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.order-create-page .difference-value {
    display: block;
    color: #243247;
    font-size: 1.05rem;
    font-weight: 750;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.order-create-page .difference-item.highlight {
    background: #fff9eb;
    border-color: #f4e3bd;
}

.order-create-page .difference-item.highlight .difference-value {
    color: #a66b0a;
    font-size: 1.2rem;
}

.order-create-page .difference-value.text-success {
    color: #087f5b !important;
}

.order-create-page .difference-value.text-danger {
    color: #c03939 !important;
}

.order-create-page .difference-note {
    margin-top: 15px;
    color: #8491a4;
    font-size: .78rem;
    line-height: 1.6;
}

/* =====================================================
   ACTION FOOTER
===================================================== */

.order-create-page .action-card {
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

.order-create-page .action-description {
    color: #8491a4;
    font-size: .8rem;
}

.order-create-page .action-buttons {
    display: flex;
    align-items: center;
    gap: 9px;
}

.order-create-page .btn-order {
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

.order-create-page .btn-back {
    border: 1px solid #dfe6f0;
    background: #fff;
    color: #64748b;
}

.order-create-page .btn-back:hover {
    background: #f7f9fc;
    color: #334155;
}

.order-create-page .btn-save {
    border: 0;
    background: linear-gradient(135deg, #627dff, #4965e8);
    color: #fff;
    box-shadow: 0 5px 12px rgba(79, 103, 232, .2);
}

.order-create-page .btn-save:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(79, 103, 232, .25);
}

.order-create-page .btn-save:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
}

/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991px) {
    .order-create-page .difference-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767px) {
    .order-create-page .page-heading {
        margin-top: 80px;
    }

    .order-create-page .page-title {
        font-size: 1.3rem;
    }

    .order-create-page .form-card-header {
        padding: 15px 16px;
    }

    .order-create-page .form-card-body {
        padding: 17px 16px;
    }

    .order-create-page .difference-grid {
        grid-template-columns: 1fr;
    }

    .order-create-page .action-card {
        align-items: stretch;
        padding: 16px;
    }

    .order-create-page .action-buttons {
        width: 100%;
    }

    .order-create-page .action-buttons .btn-order {
        flex: 1;
    }
}

@media (max-width: 480px) {
    .order-create-page .action-buttons {
        flex-direction: column-reverse;
    }

    .order-create-page .action-buttons .btn-order {
        width: 100%;
    }
}
</style>


<div class="container-fluid order-create-page px-2 px-lg-3 py-2">

    <!-- =================================================
         PAGE HEADING
    ================================================== -->

    <div class="page-heading">

        <div>
            <div class="eyebrow">
                <i class="ti ti-clipboard-list"></i>
                Inventory
            </div>

            <h1 class="page-title">Create Master Order</h1>

            <p class="page-subtitle">
                Buat transaksi Master Order beserta Customer Order dan Supplier Order.
            </p>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('order'); ?>">
                        Order Management
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Create
                </li>
            </ol>
        </nav>

    </div>


    <!-- =================================================
         FLASH MESSAGE
    ================================================== -->

    <?php if ($this->session->flashdata('order_error')): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ti ti-alert-circle me-1"></i>

            <?= $this->session->flashdata('order_error'); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- =================================================
         FORM
    ================================================== -->

    <form
        id="orderForm"
        method="POST"
        action="<?= base_url('order/save'); ?>"
        autocomplete="off"
    >

        <?php if (config_item('csrf_protection')): ?>

            <input
                type="hidden"
                name="<?= $this->security->get_csrf_token_name(); ?>"
                value="<?= $this->security->get_csrf_hash(); ?>"
            >

        <?php endif; ?>


        <!-- =============================================
             1. MASTER ORDER
        ============================================== -->

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-master">
                    <i class="ti ti-file-text"></i>
                </div>

                <div>
                    <h3 class="section-title">
                        Master Order
                    </h3>

                    <p class="section-description">
                        Informasi utama transaksi Order
                    </p>
                </div>

            </div>


            <div class="form-card-body">

                <div class="row">

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Order Date
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                name="ORDER_DATE"
                                id="ORDER_DATE"
                                value="<?= html_escape(set_value('ORDER_DATE', $today)); ?>"
                                required
                            >

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Plant
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="0001"
                                readonly
                            >

                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                Sales <span class="required">*</span>
                            </label>

                            <select
                                name="SALES"
                                id="SALES"
                                class="form-control select2-order"
                                data-placeholder="Select Sales"
                                required
                            >
                                <option value="">Select Sales</option>

                                <?php foreach ($sales_list as $sales): ?>
                                    <option
                                        value="<?= html_escape($sales['SALES']); ?>"
                                        <?= set_select('SALES', $sales['SALES']); ?>
                                    >
                                        <?= html_escape(
                                            $sales['SALES'] . ' - ' . $sales['FULL_NAME']
                                        ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>

            </div>

        </section>


        <!-- =============================================
             2. CUSTOMER ORDER
        ============================================== -->

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-customer">
                    <i class="ti ti-shopping-cart"></i>
                </div>

                <div>
                    <h3 class="section-title">
                        Customer Order
                    </h3>

                    <p class="section-description">
                        Detail penjualan dan nilai piutang Customer
                    </p>
                </div>

            </div>


            <div class="form-card-body">


                <!-- CUSTOMER SELECTION -->

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">

                            <label class="form-label">
                                Customer
                                <span class="required">*</span>
                            </label>

                            <select
                                class="form-control select2-order"
                                name="CUST"
                                id="CUST"
                                required
                            >

                                <option value="">
                                    -- Select Customer --
                                </option>

                                <?php if (!empty($customers)): ?>

                                    <?php foreach ($customers as $row): ?>

                                        <option
                                            value="<?= html_escape($row['CUST']); ?>"
                                            <?= set_select('CUST', $row['CUST']); ?>
                                        >
                                            <?= html_escape(
                                                $row['CUST'] . ' - ' . $row['FULL_NAME']
                                            ); ?>
                                        </option>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- CREDIT INFORMATION -->

                <div class="row mb-3">

                    <div class="col-md-6">

                        <div class="credit-info-card">

                            <span class="credit-info-label">
                                Basic Credit
                            </span>

                            <div
                                class="credit-info-value"
                                id="BASIC_CREDIT"
                            >
                                0,00
                            </div>

                            <small id="BASIC_CREDIT_CURRENCY">
                                IDR
                            </small>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="credit-info-card credit-balance-card">

                            <span class="credit-info-label">
                                Credit Balance
                            </span>

                            <div
                                class="credit-info-value"
                                id="CREDIT_BALANCE"
                            >
                                0,00
                            </div>

                            <small id="CREDIT_BALANCE_CURRENCY">
                                IDR
                            </small>

                        </div>

                    </div>

                </div>


                <!-- CREDIT STATUS -->

                <div
                    class="credit-status-message"
                    id="CREDIT_STATUS_MESSAGE"
                    style="display:none;"
                >

                    <i class="ti ti-info-circle"></i>

                    <span id="CREDIT_STATUS_TEXT"></span>

                </div>


                <!-- CUSTOMER ORDER INPUTS -->

                <div class="row">


                    <!-- BASIS -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Basis
                                <span class="required">*</span>
                            </label>

                            <select
                                class="form-control order-basis"
                                name="CUSTOMER_BASIS"
                                id="CUSTOMER_BASIS"
                                required
                            >

                                <option value="EKOR">EKOR</option>
                                <option value="BW">BW</option>

                            </select>

                        </div>

                    </div>


                    <!-- EKOR -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Ekor
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control order-calc"
                                name="CUSTOMER_EKOR"
                                id="CUSTOMER_EKOR"
                                min="0.001"
                                step="0.001"
                                placeholder="0"
                                required
                            >

                        </div>

                    </div>


                    <!-- KG -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                KG
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control order-calc"
                                name="CUSTOMER_KG"
                                id="CUSTOMER_KG"
                                min="0.001"
                                step="0.001"
                                placeholder="0.000"
                                required
                            >

                        </div>

                    </div>


                    <!-- AVERAGE BW -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Average BW
                            </label>

                            <input
                                type="text"
                                class="form-control calculated-field"
                                id="CUSTOMER_AVG_BW"
                                value="0,0000"
                                readonly
                            >

                        </div>

                    </div>


                    <!-- UNIT PRICE -->

                    <div class="col-md-4">

                        <div class="form-group mb-md-0">

                            <label class="form-label">
                                Selling Unit Price
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control order-calc"
                                name="CUSTOMER_UNIT_PRICE"
                                id="CUSTOMER_UNIT_PRICE"
                                min="0.01"
                                step="0.01"
                                placeholder="0.00"
                                required
                            >

                        </div>

                    </div>


                    <!-- CUSTOMER TOTAL -->

                    <div class="col-md-4">

                        <div class="form-group mb-md-0">

                            <label class="form-label">
                                Total Customer Order
                            </label>

                            <input
                                type="text"
                                class="form-control total-field"
                                id="CUSTOMER_TOTAL_DISPLAY"
                                value="0,00"
                                readonly
                            >

                        </div>

                    </div>


                    <!-- PAYMENT TYPE -->

                    <div class="col-md-4">

                        <div class="form-group mb-md-0">

                            <label class="form-label">
                                Payment Type
                            </label>

                            <input
                                type="text"
                                class="form-control payment-type-field"
                                value="CREDIT"
                                readonly
                            >

                        </div>

                    </div>

                </div>


                <!-- CREDIT NOTE -->

                <div class="info-note mt-3">

                    <i class="ti ti-info-circle"></i>

                    <div>

                        <strong>Credit Transaction</strong>

                        <br>

                        Customer Order selalu menggunakan Credit.

                        Total dihitung berdasarkan Basis yang dipilih:
                        EKOR × Unit Price atau KG × Unit Price.

                    </div>

                </div>

            </div>

        </section>


        <!-- =============================================
             3. SUPPLIER ORDER
        ============================================== -->

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-supplier">
                    <i class="ti ti-truck-delivery"></i>
                </div>

                <div>
                    <h3 class="section-title">
                        Supplier Order
                    </h3>

                    <p class="section-description">
                        Detail pembelian dan nilai kewajiban Supplier
                    </p>
                </div>

            </div>


            <div class="form-card-body">


                <!-- SUPPLIER SELECTION -->

                <div class="row">

                    <div class="col-md-12">

                        <div class="form-group">

                            <label class="form-label">
                                Supplier
                                <span class="required">*</span>
                            </label>

                            <select
                                class="form-control select2-order"
                                name="SUPPLIER"
                                id="SUPPLIER"
                                required
                            >

                                <option value="">
                                    -- Select Supplier --
                                </option>

                                <?php if (!empty($suppliers)): ?>

                                    <?php foreach ($suppliers as $row): ?>

                                        <option
                                            value="<?= html_escape($row['CUST']); ?>"
                                            <?= set_select('SUPPLIER', $row['CUST']); ?>
                                        >
                                            <?= html_escape(
                                                $row['CUST'] . ' - ' . $row['FULL_NAME']
                                            ); ?>
                                        </option>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- SUPPLIER ORDER INPUTS -->

                <div class="row">


                    <!-- BASIS -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Basis
                                <span class="required">*</span>
                            </label>

                            <select
                                class="form-control order-basis"
                                name="SUPPLIER_BASIS"
                                id="SUPPLIER_BASIS"
                                required
                            >

                                <option value="EKOR">EKOR</option>
                                <option value="BW">BW</option>

                            </select>

                        </div>

                    </div>


                    <!-- EKOR -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Ekor
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control order-calc"
                                name="SUPPLIER_EKOR"
                                id="SUPPLIER_EKOR"
                                min="0.001"
                                step="0.001"
                                placeholder="0"
                                required
                            >

                        </div>

                    </div>


                    <!-- KG -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                KG
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control order-calc"
                                name="SUPPLIER_KG"
                                id="SUPPLIER_KG"
                                min="0.001"
                                step="0.001"
                                placeholder="0.000"
                                required
                            >

                        </div>

                    </div>


                    <!-- AVERAGE BW -->

                    <div class="col-md-3">

                        <div class="form-group">

                            <label class="form-label">
                                Average BW
                            </label>

                            <input
                                type="text"
                                class="form-control calculated-field"
                                id="SUPPLIER_AVG_BW"
                                value="0,0000"
                                readonly
                            >

                        </div>

                    </div>


                    <!-- UNIT PRICE -->

                    <div class="col-md-4">

                        <div class="form-group mb-md-0">

                            <label class="form-label">
                                Purchase Unit Price
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control order-calc"
                                name="SUPPLIER_UNIT_PRICE"
                                id="SUPPLIER_UNIT_PRICE"
                                min="0.01"
                                step="0.01"
                                placeholder="0.00"
                                required
                            >

                        </div>

                    </div>


                    <!-- SUPPLIER TOTAL -->

                    <div class="col-md-4">

                        <div class="form-group mb-md-0">

                            <label class="form-label">
                                Total Supplier Order
                            </label>

                            <input
                                type="text"
                                class="form-control total-field"
                                id="SUPPLIER_TOTAL_DISPLAY"
                                value="0,00"
                                readonly
                            >

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- =============================================
            4. DELIVERY INFORMATION
        ============================================== -->

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-supplier">
                    <i class="ti ti-truck-delivery"></i>
                </div>

                <div>
                    <h3 class="section-title">
                        Delivery Information
                    </h3>

                    <p class="section-description">
                        Informasi pengemudi dan kendaraan pengiriman
                    </p>
                </div>

            </div>

            <div class="form-card-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group mb-md-0">

                            <label class="form-label">
                                Driver
                            </label>

                            <input
                                type="text"
                                class="form-control text-uppercase"
                                name="DRIVER"
                                id="DRIVER"
                                maxlength="100"
                                placeholder="Enter driver name"
                                style="text-transform: uppercase;"
                            >

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-0">

                            <label class="form-label">
                                No. Mobil / Plat Nomor
                            </label>

                            <input
                                type="text"
                                class="form-control text-uppercase"
                                name="VEHICLE_NO"
                                id="VEHICLE_NO"
                                maxlength="30"
                                placeholder="Enter vehicle plate number"
                                style="text-transform: uppercase;"
                            >

                        </div>
                    </div>

                </div>

            </div>

        </section>

        <!-- =============================================
             4. PRICE DIFFERENCE
        ============================================== -->

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon icon-difference">
                    <i class="ti ti-calculator"></i>
                </div>

                <div>
                    <h3 class="section-title">
                        Price Difference
                    </h3>

                    <p class="section-description">
                        Ringkasan selisih harga jual dan harga beli
                    </p>
                </div>

            </div>


            <div class="form-card-body">

                <div class="difference-grid">


                    <div class="difference-item">

                        <span class="difference-label">
                            Selling Price
                        </span>

                        <span
                            class="difference-value"
                            id="DIFF_SELLING_PRICE"
                        >
                            0,00
                        </span>

                    </div>


                    <div class="difference-item">

                        <span class="difference-label">
                            Purchase Price
                        </span>

                        <span
                            class="difference-value"
                            id="DIFF_PURCHASE_PRICE"
                        >
                            0,00
                        </span>

                    </div>


                    <div class="difference-item">

                        <span class="difference-label">
                            Difference Price
                        </span>

                        <span
                            class="difference-value"
                            id="DIFF_PRICE"
                        >
                            0,00
                        </span>

                    </div>


                    <div class="difference-item">

                        <span class="difference-label">
                            Sales Total
                        </span>

                        <span
                            class="difference-value"
                            id="DIFF_SALES_TOTAL"
                        >
                            0,00
                        </span>

                    </div>


                    <div class="difference-item">

                        <span class="difference-label">
                            Purchase Total
                        </span>

                        <span
                            class="difference-value"
                            id="DIFF_PURCHASE_TOTAL"
                        >
                            0,00
                        </span>

                    </div>


                    <div class="difference-item highlight">

                        <span class="difference-label">
                            Total Difference
                        </span>

                        <span
                            class="difference-value"
                            id="DIFF_TOTAL"
                        >
                            0,00
                        </span>

                    </div>

                </div>


                <div class="difference-note">

                    <i class="ti ti-info-circle me-1"></i>

                    Total Difference merupakan selisih Sales Total dan
                    Purchase Total, bukan perhitungan net profit.

                </div>

            </div>

        </section>


        <!-- =============================================
             5. ACTION BUTTONS
        ============================================== -->

        <div class="action-card">

            <div class="action-description">

                <i class="ti ti-shield-check me-1"></i>

                Pastikan seluruh informasi Order sudah benar sebelum disimpan.

            </div>


            <div class="action-buttons">

                <a
                    href="<?= base_url('order'); ?>"
                    class="btn-order btn-back"
                    id="btnBack"
                >

                    <i class="ti ti-arrow-left"></i>

                    Back

                </a>


                <button
                    type="submit"
                    class="btn-order btn-save"
                    id="btnSaveOrder"
                >

                    <i class="ti ti-device-floppy"></i>

                    Save Master Order

                </button>

            </div>

        </div>

    </form>

</div>


<script>
$(document).ready(function () {

    $('#DRIVER, #VEHICLE_NO').on('input', function () {
        this.value = this.value.toUpperCase();
    });
    /* =================================================
       SELECT2 INITIALIZATION
    ================================================= */

    if ($.fn.select2) {

        $('.select2-order').select2({
            placeholder: 'Select option',
            allowClear: true,
            width: '100%'
        });

    }


    /* =================================================
       FORMAT NUMBER
    ================================================= */

    function formatNumber(value, decimal = 2) {

        value = Number(value) || 0;

        return value.toLocaleString('id-ID', {
            minimumFractionDigits: decimal,
            maximumFractionDigits: decimal
        });

    }


    /* =================================================
       GET NUMERIC INPUT
    ================================================= */

    function getNumber(id) {

        let value = parseFloat($('#' + id).val());

        return isNaN(value) ? 0 : value;

    }


    /* =================================================
       CREDIT INFORMATION
    ================================================= */

    function resetCustomerCredit() {

        $('#BASIC_CREDIT').text('0,00');

        $('#CREDIT_BALANCE').text('0,00');

        $('#BASIC_CREDIT_CURRENCY').text('IDR');

        $('#CREDIT_BALANCE_CURRENCY').text('IDR');

        $('#CREDIT_STATUS_MESSAGE')
            .hide()
            .removeClass('warning error success');

        $('#CREDIT_STATUS_TEXT').text('');

    }


    function loadCustomerCredit(cust) {

        resetCustomerCredit();

        if (!cust) {
            return;
        }

        $('#CREDIT_STATUS_MESSAGE')
            .show()
            .removeClass('warning error success');

        $('#CREDIT_STATUS_TEXT').text(
            'Loading Credit Information...'
        );

        $.ajax({

            url: "<?= base_url('order/customer_credit_info/'); ?>" +
                encodeURIComponent(cust),

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                if (!response.ok || !response.data) {

                    resetCustomerCredit();

                    $('#CREDIT_STATUS_MESSAGE')
                        .show()
                        .addClass('error');

                    $('#CREDIT_STATUS_TEXT').text(
                        response.message ||
                        'Gagal mengambil informasi Credit Limit.'
                    );

                    return;
                }

                let credit = response.data;

                let basicCredit =
                    Number(credit.basic_credit) || 0;

                let creditBalance =
                    Number(credit.credit_balance) || 0;

                let currency =
                    credit.currency || 'IDR';


                $('#BASIC_CREDIT').text(
                    formatNumber(basicCredit, 2)
                );

                $('#CREDIT_BALANCE').text(
                    formatNumber(creditBalance, 2)
                );

                $('#BASIC_CREDIT_CURRENCY').text(currency);

                $('#CREDIT_BALANCE_CURRENCY').text(currency);


                $('#CREDIT_STATUS_MESSAGE')
                    .show()
                    .removeClass('warning error success');


                if (!credit.has_credit_limit || !credit.is_active) {

                    $('#CREDIT_STATUS_MESSAGE')
                        .addClass('warning');

                } else {

                    $('#CREDIT_STATUS_MESSAGE')
                        .addClass('success');

                }


                $('#CREDIT_STATUS_TEXT').text(
                    credit.message || ''
                );

            },

            error: function (xhr) {

                resetCustomerCredit();

                let message =
                    'Gagal mengambil informasi Credit Limit.';

                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message = xhr.responseJSON.message;

                }

                $('#CREDIT_STATUS_MESSAGE')
                    .show()
                    .addClass('error');

                $('#CREDIT_STATUS_TEXT').text(message);

            }

        });

    }


    // Customer changed
    $('#CUST').on('change', function () {

        let cust = $(this).val();

        loadCustomerCredit(cust);

    });


    // Initial state
    resetCustomerCredit();


    /* =================================================
       CALCULATE CUSTOMER ORDER
    ================================================= */

    function calculateCustomerOrder() {

        let ekor = getNumber('CUSTOMER_EKOR');

        let kg = getNumber('CUSTOMER_KG');

        let price = getNumber('CUSTOMER_UNIT_PRICE');

        let basis = $('#CUSTOMER_BASIS').val();

        let avgBw = 0;

        let total = 0;


        if (ekor > 0 && kg > 0) {

            avgBw = kg / ekor;

        }


        if (basis === 'EKOR') {

            total = ekor * price;

        } else if (basis === 'BW') {

            total = kg * price;

        }


        $('#CUSTOMER_AVG_BW').val(
            formatNumber(avgBw, 4)
        );

        $('#CUSTOMER_TOTAL_DISPLAY').val(
            formatNumber(total, 2)
        );


        return {
            basis: basis,
            ekor: ekor,
            kg: kg,
            avgBw: avgBw,
            price: price,
            total: total
        };

    }


    /* =================================================
       CALCULATE SUPPLIER ORDER
    ================================================= */

    function calculateSupplierOrder() {

        let ekor = getNumber('SUPPLIER_EKOR');

        let kg = getNumber('SUPPLIER_KG');

        let price = getNumber('SUPPLIER_UNIT_PRICE');

        let basis = $('#SUPPLIER_BASIS').val();

        let avgBw = 0;

        let total = 0;


        if (ekor > 0 && kg > 0) {

            avgBw = kg / ekor;

        }


        if (basis === 'EKOR') {

            total = ekor * price;

        } else if (basis === 'BW') {

            total = kg * price;

        }


        $('#SUPPLIER_AVG_BW').val(
            formatNumber(avgBw, 4)
        );

        $('#SUPPLIER_TOTAL_DISPLAY').val(
            formatNumber(total, 2)
        );


        return {
            basis: basis,
            ekor: ekor,
            kg: kg,
            avgBw: avgBw,
            price: price,
            total: total
        };

    }


    /* =================================================
       CALCULATE PRICE DIFFERENCE
    ================================================= */

    function calculatePriceDifference() {

        let customer = calculateCustomerOrder();

        let supplier = calculateSupplierOrder();


        let differencePrice =
            customer.price - supplier.price;

        let totalDifference =
            customer.total - supplier.total;


        $('#DIFF_SELLING_PRICE').text(
            formatNumber(customer.price, 2)
        );

        $('#DIFF_PURCHASE_PRICE').text(
            formatNumber(supplier.price, 2)
        );

        $('#DIFF_PRICE').text(
            formatNumber(differencePrice, 2)
        );

        $('#DIFF_SALES_TOTAL').text(
            formatNumber(customer.total, 2)
        );

        $('#DIFF_PURCHASE_TOTAL').text(
            formatNumber(supplier.total, 2)
        );


        $('#DIFF_TOTAL')
            .text(formatNumber(totalDifference, 2))
            .removeClass('text-success text-danger');


        if (totalDifference > 0) {

            $('#DIFF_TOTAL').addClass('text-success');

        } else if (totalDifference < 0) {

            $('#DIFF_TOTAL').addClass('text-danger');

        }

    }


    /* =================================================
       SYNC BASIS
    ================================================= */

    $('#CUSTOMER_BASIS').on('change', function () {

        let basis = $(this).val();

        $('#SUPPLIER_BASIS').val(basis);

        calculatePriceDifference();

    });


    $('#SUPPLIER_BASIS').on('change', function () {

        let basis = $(this).val();

        $('#CUSTOMER_BASIS').val(basis);

        calculatePriceDifference();

    });


    /* =================================================
       INPUT EVENTS
    ================================================= */

    $('.order-calc').on('input change', function () {

        calculatePriceDifference();

    });


    /* =================================================
       FORM SUBMIT VALIDATION
    ================================================= */

    $('#orderForm').on('submit', function (e) {

        let customerCode = $('#CUST').val();

        let supplierCode = $('#SUPPLIER').val();
        let salesCode = $('#SALES').val();


        // Validate Customer
        if (!customerCode) {

            e.preventDefault();

            alert('Customer wajib dipilih.');

            $('#CUST').select2('open');

            return false;

        }


        // Validate Supplier
        if (!supplierCode) {

            e.preventDefault();

            alert('Supplier wajib dipilih.');

            $('#SUPPLIER').select2('open');

            return false;

        }

        if (!salesCode) {
            e.preventDefault();

            alert('Sales wajib dipilih.');

            $('#SALES').select2('open');

            return false;
        }


        // Validate Basis
        let customerBasis = $('#CUSTOMER_BASIS').val();

        let supplierBasis = $('#SUPPLIER_BASIS').val();


        if (customerBasis !== supplierBasis) {

            e.preventDefault();

            alert(
                'Basis Customer Order dan Supplier Order harus sama.'
            );

            return false;

        }


        // Calculate
        let customer = calculateCustomerOrder();

        let supplier = calculateSupplierOrder();


        // Validate Customer Order
        if (
            customer.ekor <= 0 ||
            customer.kg <= 0 ||
            customer.price <= 0
        ) {

            e.preventDefault();

            alert(
                'Customer Order: Ekor, KG, dan Unit Price wajib lebih dari 0.'
            );

            return false;

        }


        // Validate Supplier Order
        if (
            supplier.ekor <= 0 ||
            supplier.kg <= 0 ||
            supplier.price <= 0
        ) {

            e.preventDefault();

            alert(
                'Supplier Order: Ekor, KG, dan Unit Price wajib lebih dari 0.'
            );

            return false;

        }


        // Confirm
        if (
            !confirm(
                'Apakah Anda yakin ingin menyimpan Master Order ini?'
            )
        ) {

            e.preventDefault();

            return false;

        }


        // Prevent double submit
        $('#btnSaveOrder')
            .prop('disabled', true)
            .html(
                '<i class="ti ti-loader-2 ti-spin"></i> Saving...'
            );


        return true;

    });


    /* =================================================
       INITIAL CALCULATION
    ================================================= */

    calculatePriceDifference();

});
</script>
