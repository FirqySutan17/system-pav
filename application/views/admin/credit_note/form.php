<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
/* =====================================================
   CREDIT NOTE FORM - MODERN MODAL UI
   Scoped styles: does not affect other modules
===================================================== */

#modalCreditNote,
#modalSelectOrder {
    color: #243247;
}

#modalCreditNote .modal-dialog,
#modalSelectOrder .modal-dialog {
    max-width: 1100px;
}

#modalCreditNote .modal-content,
#modalSelectOrder .modal-content {
    border: 1px solid #e7edf5;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 18px 55px rgba(15, 23, 42, .18);
}

#modalCreditNote .modal-header,
#modalSelectOrder .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    background: linear-gradient(135deg, #172b4d 0%, #263f70 100%);
    border-bottom: 0;
    color: #fff;
}

#modalCreditNote .modal-header .modal-title,
#modalSelectOrder .modal-header .modal-title {
    margin: 0;
    color: #fff;
    font-size: 1.12rem;
    font-weight: 750;
    letter-spacing: -.02em;
}

#modalCreditNote .modal-header small {
    display: block;
    margin-top: 4px;
    color: rgba(255,255,255,.72);
    font-size: .78rem;
}

#modalCreditNote .modal-header .close,
#modalSelectOrder .modal-header .close {
    color: #fff;
    opacity: .85;
    text-shadow: none;
    font-size: 1.7rem;
    font-weight: 400;
    outline: none;
}

#modalCreditNote .modal-header .close:hover,
#modalSelectOrder .modal-header .close:hover {
    color: #fff;
    opacity: 1;
}

#modalCreditNote .modal-body,
#modalSelectOrder .modal-body {
    padding: 24px;
    background: #f8faff;
}

#modalCreditNote .cn-form-section {
    margin-bottom: 18px;
    border: 1px solid #e6ecf4;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(22,42,75,.035);
    overflow: hidden;
}

#modalCreditNote .cn-section-header {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 15px 18px;
    border-bottom: 1px solid #edf1f7;
}

#modalCreditNote .cn-section-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #eef2ff;
    color: #4b63d3;
    font-size: 17px;
}

#modalCreditNote .cn-section-title {
    margin: 0;
    color: #243247;
    font-size: .91rem;
    font-weight: 750;
}

#modalCreditNote .cn-section-subtitle {
    display: block;
    margin-top: 3px;
    color: #94a3b8;
    font-size: .74rem;
}

#modalCreditNote .cn-section-body {
    padding: 19px;
}

#modalCreditNote .cn-field {
    margin-bottom: 16px;
}

#modalCreditNote .cn-field:last-child {
    margin-bottom: 0;
}

#modalCreditNote .cn-label {
    display: block;
    margin-bottom: 7px;
    color: #64748b;
    font-size: .74rem;
    font-weight: 750;
    letter-spacing: .035em;
    text-transform: uppercase;
}

#modalCreditNote .cn-required {
    color: #dc3545;
    margin-left: 2px;
}

#modalCreditNote .form-control,
#modalSelectOrder .form-control {
    min-height: 43px;
    border: 1px solid #dfe6f0;
    border-radius: 9px;
    box-shadow: none;
    color: #334155;
    font-size: .86rem;
}

#modalCreditNote textarea.form-control {
    min-height: 90px;
    resize: vertical;
}

#modalCreditNote .form-control:focus,
#modalSelectOrder .form-control:focus {
    border-color: #8aa4ff;
    box-shadow: 0 0 0 3px rgba(99,126,255,.12);
}

#modalCreditNote .form-control[readonly] {
    background: #f7f9fc;
    color: #475569;
}

#modalCreditNote .cn-auto-field {
    position: relative;
}

#modalCreditNote .cn-auto-field:after {
    content: "AUTO";
    position: absolute;
    right: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    background: #e9eef6;
    border-radius: 5px;
    padding: 3px 6px;
    font-size: .58rem;
    font-weight: 800;
    letter-spacing: .06em;
    pointer-events: none;
}

#modalCreditNote #cn_number {
    padding-right: 55px;
    font-weight: 700;
    color: #5266c9;
}

#modalCreditNote .cn-order-select {
    display: flex;
    gap: 9px;
    align-items: stretch;
}

#modalCreditNote .cn-order-select .form-control {
    flex: 1 1 auto;
    min-width: 0;
}

#modalCreditNote #btnSelectOrder {
    flex: 0 0 auto;
    min-height: 43px;
    padding: 0 16px;
    border-radius: 9px;
    font-size: .82rem;
    font-weight: 700;
}

#modalCreditNote #btnSelectOrder:disabled {
    cursor: not-allowed;
    opacity: .55;
}

#modalCreditNote .cn-help {
    display: block;
    margin-top: 7px;
    color: #94a3b8;
    font-size: .74rem;
}

/* Selected order summary */
#modalCreditNote #cnOrderPanel {
    border: 1px solid #dfe7f4;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(22,42,75,.035);
    overflow: hidden;
    margin-bottom: 18px;
}

#modalCreditNote .cn-order-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 19px;
    background: #f7f9ff;
    border-bottom: 1px solid #e8edf7;
}

#modalCreditNote .cn-order-heading i {
    color: #4b63d3;
    font-size: 18px;
}

#modalCreditNote .cn-order-heading h6 {
    margin: 0;
    color: #243247;
    font-size: .9rem;
    font-weight: 750;
}

#modalCreditNote .cn-order-body {
    padding: 20px;
}

#modalCreditNote .cn-detail-item {
    min-width: 0;
    margin-bottom: 18px;
}

#modalCreditNote .cn-detail-label {
    display: block;
    margin-bottom: 5px;
    color: #94a3b8;
    font-size: .69rem;
    font-weight: 750;
    letter-spacing: .045em;
    text-transform: uppercase;
}

#modalCreditNote .cn-detail-value {
    display: block;
    color: #334155;
    font-size: .87rem;
    font-weight: 700;
    overflow-wrap: anywhere;
}

#modalCreditNote .cn-order-totals {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 12px;
    padding-top: 17px;
    border-top: 1px solid #edf1f7;
}

#modalCreditNote .cn-total-box {
    padding: 14px 16px;
    border: 1px solid #e7edf5;
    border-radius: 11px;
    background: #fbfcff;
}

#modalCreditNote .cn-total-box.primary {
    background: #f0f3ff;
    border-color: #dce3ff;
}

#modalCreditNote .cn-total-label {
    display: block;
    margin-bottom: 6px;
    color: #718096;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}

#modalCreditNote .cn-total-value {
    display: block;
    color: #243247;
    font-size: 1.12rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}

#modalCreditNote .cn-total-box.primary .cn-total-value {
    color: #425bd4;
}

/* Calculation */
#modalCreditNote #cnCalculationPanel {
    border: 1px solid #dce4ff;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(22,42,75,.035);
    overflow: hidden;
    margin-bottom: 18px;
}

#modalCreditNote .cn-calculation-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 19px;
    background: #f4f6ff;
    border-bottom: 1px solid #e5eaff;
}

#modalCreditNote .cn-calculation-header i {
    color: #4b63d3;
    font-size: 18px;
}

#modalCreditNote .cn-calculation-header h6 {
    margin: 0;
    color: #243247;
    font-size: .9rem;
    font-weight: 750;
}

#modalCreditNote .cn-calculation-body {
    padding: 20px;
}

#modalCreditNote #cn_price {
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

#modalCreditNote #cn_total_display {
    min-height: 48px;
    border: 1px solid #dce3ff;
    background: #f4f6ff;
    color: #425bd4;
    font-size: 1.05rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}

#modalCreditNote #cnFormulaText {
    display: block;
    margin-top: 7px;
    color: #718096;
    font-size: .75rem;
}

#modalCreditNote #cnLimitMessage {
    margin: 15px 0 0;
    padding: 11px 14px;
    border: 1px solid #dce7f8;
    border-radius: 9px;
    font-size: .8rem;
}

/* Form error */
#modalCreditNote #cnFormError {
    border-radius: 9px;
    font-size: .82rem;
}

/* Footer */
#modalCreditNote .modal-footer,
#modalSelectOrder .modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    padding: 16px 24px;
    background: #fff;
    border-top: 1px solid #edf1f7;
}

#modalCreditNote .modal-footer .btn,
#modalSelectOrder .modal-footer .btn {
    min-height: 40px;
    padding: 0 17px;
    border-radius: 9px;
    font-size: .82rem;
    font-weight: 700;
}

#modalCreditNote #btnSaveCN {
    border: 0;
    background: linear-gradient(135deg,#627dff,#4965e8);
    box-shadow: 0 5px 12px rgba(79,103,232,.2);
}

#modalCreditNote #btnSaveCN:hover {
    background: linear-gradient(135deg,#536fe9,#3e58d2);
}

/* Order selector modal */
#modalSelectOrder .modal-dialog {
    max-width: 1200px;
}

#modalSelectOrder .modal-body {
    padding: 22px;
    background: #f8faff;
}

#modalSelectOrder .order-search-panel {
    padding: 15px;
    margin-bottom: 16px;
    border: 1px solid #e6ecf4;
    border-radius: 12px;
    background: #fff;
}

#modalSelectOrder .order-search-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

#modalSelectOrder #btnSearchOrder {
    min-height: 43px;
    padding: 0 18px;
    border-radius: 9px;
    font-size: .82rem;
    font-weight: 700;
    white-space: nowrap;
}

#modalSelectOrder .table-responsive {
    border: 1px solid #e7edf5;
    border-radius: 11px;
    background: #fff;
}

#modalSelectOrder table {
    margin-bottom: 0;
    white-space: nowrap;
}

#modalSelectOrder table thead th {
    padding: 13px 12px;
    background: #f2f5fa;
    color: #66758b;
    border-top: 0;
    border-bottom: 1px solid #e7edf5;
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
}

#modalSelectOrder table tbody td {
    padding: 12px;
    color: #43536a;
    font-size: .8rem;
    vertical-align: middle;
}

#modalSelectOrder table tbody tr:hover {
    background: #f8faff;
}

#modalSelectOrder .btn-pick-order {
    min-width: 75px;
    border-radius: 7px;
    font-size: .76rem;
    font-weight: 700;
}

/* Responsive */
@media(max-width:767px) {
    #modalCreditNote .modal-dialog,
    #modalSelectOrder .modal-dialog {
        margin: 10px;
    }

    #modalCreditNote .modal-header,
    #modalSelectOrder .modal-header {
        padding: 16px 18px;
    }

    #modalCreditNote .modal-body,
    #modalSelectOrder .modal-body {
        padding: 15px;
    }

    #modalCreditNote .cn-section-body,
    #modalCreditNote .cn-order-body,
    #modalCreditNote .cn-calculation-body {
        padding: 15px;
    }

    #modalCreditNote .cn-order-totals {
        grid-template-columns: 1fr;
    }

    #modalCreditNote .modal-footer,
    #modalSelectOrder .modal-footer {
        padding: 13px 15px;
    }

    #modalSelectOrder .order-search-row {
        flex-direction: column;
        align-items: stretch;
    }

    #modalSelectOrder #btnSearchOrder {
        width: 100%;
    }
}

@media(max-width:420px) {
    #modalCreditNote .cn-order-select {
        flex-direction: column;
    }

    #modalCreditNote #btnSelectOrder {
        width: 100%;
    }
}
</style>

<!-- =========================================================
     CREATE / EDIT CREDIT NOTE
========================================================= -->

<div class="modal fade" id="modalCreditNote" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <form id="formCreditNote">
            <input type="hidden" name="credit_note_id" id="cn_id">
            <input type="hidden" name="order_detail_id" id="cn_order_detail_id">

            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="cnModalTitle">Create Credit Note</h5>
                        <small>Credit Note transaction</small>
                    </div>

                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <!-- CREDIT NOTE INFORMATION -->
                    <section class="cn-form-section">
                        <div class="cn-section-header">
                            <span class="cn-section-icon"><i class="ti ti-file-invoice"></i></span>
                            <div>
                                <h6 class="cn-section-title">Credit Note Information</h6>
                                <span class="cn-section-subtitle">Enter the Credit Note date and select its source Order.</span>
                            </div>
                        </div>

                        <div class="cn-section-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 cn-field">
                                    <label class="cn-label" for="cn_number">Credit Note No</label>
                                    <div class="cn-auto-field">
                                        <input type="text" class="form-control" id="cn_number"
                                               value="Auto Generate" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 cn-field">
                                    <label class="cn-label" for="cn_date">
                                        CN Date <span class="cn-required">*</span>
                                    </label>
                                    <input type="date" class="form-control" name="cn_date"
                                           id="cn_date" required>
                                </div>

                                <div class="col-lg-4 col-md-12 cn-field">
                                    <label class="cn-label" for="cn_order_type">
                                        Order Type <span class="cn-required">*</span>
                                    </label>
                                    <select class="form-control" name="order_type" id="cn_order_type" required>
                                        <option value="">-- Select Order Type --</option>
                                        <option value="CUSTOMER">Customer Order</option>
                                        <option value="SUPPLIER">Supplier Order</option>
                                    </select>
                                </div>
                            </div>

                            <div class="cn-field">
                                <label class="cn-label" for="cn_order_display">
                                    Selected Order <span class="cn-required">*</span>
                                </label>

                                <div class="cn-order-select">
                                    <input type="text" class="form-control" id="cn_order_display"
                                           placeholder="Select Customer / Supplier Order" readonly>

                                    <button type="button" class="btn btn-outline-primary"
                                            id="btnSelectOrder" disabled>
                                        <i class="ti ti-search mr-1"></i>
                                        Select Order
                                    </button>
                                </div>

                                <small class="cn-help">
                                    One Credit Note can only reference one Order. Orders already linked to another Credit Note are unavailable.
                                </small>
                            </div>
                        </div>
                    </section>

                    <!-- SELECTED ORDER DETAILS -->
                    <section id="cnOrderPanel" style="display:none;">
                        <div class="cn-order-heading">
                            <i class="ti ti-clipboard-list"></i>
                            <h6>Selected Order Details</h6>
                        </div>

                        <div class="cn-order-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Order No</span>
                                    <strong class="cn-detail-value" id="cn_detail_order_no">-</strong>
                                </div>

                                <div class="col-lg-3 col-md-4 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Order Detail No</span>
                                    <strong class="cn-detail-value" id="cn_detail_no">-</strong>
                                </div>

                                <div class="col-lg-2 col-md-4 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Order Date</span>
                                    <strong class="cn-detail-value" id="cn_detail_date">-</strong>
                                </div>

                                <div class="col-lg-2 col-md-4 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Plant</span>
                                    <strong class="cn-detail-value" id="cn_detail_plant">-</strong>
                                </div>

                                <div class="col-lg-2 col-md-4 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Basis</span>
                                    <strong class="cn-detail-value" id="cn_detail_basis">-</strong>
                                </div>

                                <div class="col-lg-4 col-md-6 col-12 cn-detail-item">
                                    <span class="cn-detail-label">Customer / Supplier</span>
                                    <strong class="cn-detail-value" id="cn_detail_party">-</strong>
                                </div>

                                <div class="col-lg-2 col-md-3 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Ekor</span>
                                    <strong class="cn-detail-value" id="cn_detail_ekor">0</strong>
                                </div>

                                <div class="col-lg-2 col-md-3 col-6 cn-detail-item">
                                    <span class="cn-detail-label">KG</span>
                                    <strong class="cn-detail-value" id="cn_detail_kg">0</strong>
                                </div>

                                <div class="col-lg-2 col-md-3 col-6 cn-detail-item">
                                    <span class="cn-detail-label">Average BW</span>
                                    <strong class="cn-detail-value" id="cn_detail_avg_bw">0</strong>
                                </div>
                            </div>

                            <div class="cn-order-totals">
                                <div class="cn-total-box">
                                    <span class="cn-total-label">Order Price</span>
                                    <strong class="cn-total-value" id="cn_detail_order_price">0</strong>
                                </div>

                                <div class="cn-total-box primary">
                                    <span class="cn-total-label">Order Total</span>
                                    <strong class="cn-total-value" id="cn_detail_order_total">0</strong>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- CREDIT NOTE CALCULATION -->
                    <section id="cnCalculationPanel" style="display:none;">
                        <div class="cn-calculation-header">
                            <i class="ti ti-calculator"></i>
                            <h6>Credit Note Calculation</h6>
                        </div>

                        <div class="cn-calculation-body">
                            <div class="row align-items-end">
                                <div class="col-md-6 cn-field">
                                    <label class="cn-label" for="cn_price">
                                        CN Price <span class="cn-required">*</span>
                                    </label>

                                    <input type="number" class="form-control" id="cn_price"
                                           name="cn_price" min="0" step="0.01"
                                           placeholder="Enter CN Price" required>

                                    <small id="cnFormulaText">
                                        Formula: CN Price × Quantity
                                    </small>
                                </div>

                                <div class="col-md-6 cn-field">
                                    <label class="cn-label" for="cn_total_display">CN Total</label>

                                    <input type="text" class="form-control" id="cn_total_display"
                                           value="0" readonly>

                                    <small class="cn-help">Automatically calculated based on Order basis and quantity.</small>
                                </div>
                            </div>

                            <div class="alert alert-info mb-0" id="cnLimitMessage">
                                CN Total must not exceed Order Total.
                            </div>
                        </div>
                    </section>

                    <!-- REMARK -->
                    <section class="cn-form-section">
                        <div class="cn-section-header">
                            <span class="cn-section-icon"><i class="ti ti-message-square"></i></span>
                            <div>
                                <h6 class="cn-section-title">Additional Information</h6>
                                <span class="cn-section-subtitle">Add an optional note for this Credit Note.</span>
                            </div>
                        </div>

                        <div class="cn-section-body">
                            <div class="cn-field">
                                <label class="cn-label" for="cn_remark">Remark</label>
                                <textarea class="form-control" name="remark" id="cn_remark"
                                          rows="3" placeholder="Enter remark (optional)"></textarea>
                            </div>
                        </div>
                    </section>

                    <div class="alert alert-warning d-none" id="cnFormError"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary" id="btnSaveCN">
                        <i class="ti ti-device-floppy mr-1"></i>
                        Save Credit Note
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>


<!-- =========================================================
     ORDER SELECTION MODAL
========================================================= -->

<div class="modal fade" id="modalSelectOrder" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title">Select Order</h5>
                    <small>Choose an available Customer or Supplier Order.</small>
                </div>

                <button type="button" class="close"
                        data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="order-search-panel">
                    <div class="order-search-row">
                        <input type="text" class="form-control" id="orderSearch"
                               placeholder="Search Order No / Customer / Supplier">

                        <button type="button" class="btn btn-primary" id="btnSearchOrder">
                            <i class="ti ti-search mr-1"></i>
                            Search
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Order Detail No</th>
                                <th>Date</th>
                                <th>Customer / Supplier</th>
                                <th>Basis</th>
                                <th class="text-right">Ekor</th>
                                <th class="text-right">KG</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody id="orderSelectionTable">
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Select Order Type first.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light"
                        data-dismiss="modal" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
