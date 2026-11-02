<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
/* =====================================================
   CREDIT NOTE PAGE - aligned with Order Management UI
===================================================== */
.credit-note-page { color:#243247; padding:0 12px 20px; }
.credit-note-page .page-heading { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin:20px 0 22px; }
.credit-note-page .eyebrow { display:inline-flex; align-items:center; gap:7px; color:#64748b; font-size:.76rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase; margin-bottom:7px; }
.credit-note-page .page-title { margin:0; font-size:1.55rem; font-weight:750; letter-spacing:-.035em; color:#172b4d; }
.credit-note-page .page-subtitle { margin:7px 0 0; color:#718096; font-size:.92rem; }
.credit-note-page .panel { border:1px solid #e7edf5; border-radius:16px; background:#fff; box-shadow:0 8px 28px rgba(22,42,75,.055); overflow:hidden; }
.credit-note-page .panel-toolbar { display:grid; grid-template-columns:minmax(0,1fr) auto; align-items:end; gap:16px; padding:20px 22px; border-bottom:1px solid #edf1f7; background:linear-gradient(180deg,#fff 0%,#fbfcff 100%); }
.credit-note-page .filter-grid { grid-column:1/-1; display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr) minmax(0,1fr) minmax(0,1fr) minmax(0,1.5fr); gap:12px; width:100%; min-width:0; }
.credit-note-page .filter-field { min-width:0; width:100%; }
.credit-note-page .filter-label { display:block; margin:0 0 6px; color:#718096; font-size:.73rem; font-weight:750; letter-spacing:.04em; text-transform:uppercase; }
.credit-note-page .form-control,.credit-note-page .form-select { width:100%; min-width:0; min-height:44px; box-sizing:border-box; border:1px solid #dfe6f0; border-radius:10px; box-shadow:none; color:#334155; background-color:#fff; font-size:.84rem; }
.credit-note-page .form-control:focus,.credit-note-page .form-select:focus { border-color:#8aa4ff; box-shadow:0 0 0 3px rgba(99,126,255,.12); }
.credit-note-page .search-wrap { position:relative; width:100%; min-width:0; }
.credit-note-page .search-wrap .search-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none; }
.credit-note-page .search-wrap .form-control { padding-left:40px; }
.credit-note-page .filter-actions { grid-column:1; grid-row:2; display:flex; align-items:center; justify-content:flex-start; gap:8px; min-width:0; }
.credit-note-page .btn-filter { min-height:44px; border-radius:10px; padding:0 15px; font-weight:650; white-space:nowrap; }
.credit-note-page .btn-add { grid-column:2; grid-row:2; display:inline-flex; align-items:center; justify-content:center; gap:8px; justify-self:end; align-self:center; min-height:44px; padding:0 17px; border:0; border-radius:10px; background:linear-gradient(135deg,#627dff,#4965e8); color:#fff; font-weight:650; box-shadow:0 5px 12px rgba(79,103,232,.2); white-space:nowrap; transition:transform .15s,box-shadow .15s; }
.credit-note-page .btn-add:hover { color:#fff; transform:translateY(-1px); box-shadow:0 8px 16px rgba(79,103,232,.25); }
.credit-note-page .table-area { padding:10px 12px 0; }
.credit-note-page .table-responsive { width:100%; overflow-x:auto; }
.credit-note-page #creditNoteMainTable { width:100%; margin:0; border-collapse:separate; border-spacing:0; white-space:nowrap; }
.credit-note-page #creditNoteMainTable thead th { padding:15px 14px; background:#f7f9fc; color:#66758b; font-size:.73rem; text-transform:uppercase; letter-spacing:.055em; font-weight:750; white-space:nowrap; border-top:0; border-bottom:1px solid #e8edf4; }
.credit-note-page #creditNoteMainTable thead th:first-child { border-radius:9px 0 0 0; }
.credit-note-page #creditNoteMainTable thead th:last-child { border-radius:0 9px 0 0; }
.credit-note-page #creditNoteMainTable tbody td { padding:15px 14px; border-bottom:1px solid #edf1f6; color:#43536a; vertical-align:middle; font-size:.84rem; }
.credit-note-page #creditNoteMainTable tbody tr { transition:background .15s; }
.credit-note-page #creditNoteMainTable tbody tr:hover { background:#f8faff; }
.credit-note-page #creditNoteMainTable tbody tr:last-child td { border-bottom:0; }
.credit-note-page .cn-no { display:block; font-weight:750; color:#3d58d8; white-space:nowrap; }
.credit-note-page .cn-subtext { display:block; margin-top:4px; color:#94a3b8; font-size:.72rem; }
.credit-note-page .cn-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:.7rem; line-height:1; font-weight:750; letter-spacing:.025em; }
.credit-note-page .cn-type { color:#475569; background:#eef2f6; }
.credit-note-page .cn-pending { color:#9a5a00; background:#fff5df; }
.credit-note-page .cn-approved { color:#087f5b; background:#e8f8f0; }
.credit-note-page .cn-rejected { color:#c03939; background:#fff0f0; }
.credit-note-page .cn-cancelled { color:#64748b; background:#eef2f6; }
.credit-note-page .number-cell { text-align:right; font-variant-numeric:tabular-nums; font-weight:600; white-space:nowrap; }
.credit-note-page .number-cell.total { color:#243247; font-weight:750; }
.credit-note-page .action-cell { width:1%; white-space:nowrap; }
.credit-note-page .action-group { display:flex; justify-content:center; align-items:center; gap:5px; }
.credit-note-page .btn-action { display:inline-flex; align-items:center; justify-content:center; gap:5px; border:1px solid transparent; border-radius:8px; padding:7px 10px; font-size:.76rem; font-weight:650; line-height:1.2; text-decoration:none; white-space:nowrap; transition:all .15s; }
.credit-note-page .btn-edit-cn { color:#4261dc; background:#eef2ff; border-color:#e0e7ff; }
.credit-note-page .btn-edit-cn:hover { background:#e0e7ff; color:#3049bd; }
.credit-note-page .empty-state { padding:38px 16px !important; text-align:center; color:#8491a4 !important; }
.credit-note-page .empty-state i { display:block; font-size:30px; margin-bottom:10px; color:#c2ccda; }
.credit-note-page .panel-footer { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding:16px 22px 20px; color:#7b8798; font-size:.84rem; border-top:1px solid #edf1f6; }
.credit-note-page .pagination-controls { display:flex; align-items:center; gap:8px; }
.credit-note-page .pagination-controls .btn { border-radius:8px; }
@media(max-width:1199px) { .credit-note-page .filter-grid { grid-template-columns:repeat(3,minmax(0,1fr)); } }
@media(max-width:991px) { .credit-note-page .filter-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media(max-width:767px) {
    .credit-note-page { padding:0 8px 16px; }
    .credit-note-page .page-heading { margin-top:80px; }
    .credit-note-page .page-title { font-size:1.3rem; }
    .credit-note-page .panel-toolbar { grid-template-columns:1fr; padding:16px; }
    .credit-note-page .filter-grid { grid-column:1; grid-template-columns:1fr; }
    .credit-note-page .filter-actions { grid-column:1; grid-row:auto; width:100%; }
    .credit-note-page .filter-actions .btn { flex:1; }
    .credit-note-page .btn-add { grid-column:1; grid-row:auto; justify-self:stretch; width:100%; }
    .credit-note-page .table-area { padding:0 8px; }
    .credit-note-page .panel-footer { padding:14px 16px; }
}
@media(max-width:480px) {
    .credit-note-page .filter-actions { flex-direction:column; align-items:stretch; }
    .credit-note-page .filter-actions .btn { width:100%; }
    .credit-note-page .panel-footer { flex-direction:column; align-items:flex-start; }
}
</style>

<div class="container-fluid credit-note-page">

    <div class="page-heading">
        <div>
            <div class="eyebrow">
                <i class="ti ti-file-invoice"></i>
                Transaction
            </div>
            <h1 class="page-title">Credit Note Management</h1>
            <p class="page-subtitle">
                Manage customer and supplier credit notes and monitor their approval status.
            </p>
        </div>
    </div>

    <section class="panel">

        <div class="panel-toolbar">
            <div class="filter-grid">

                <div class="filter-field">
                    <label class="filter-label" for="filterType">Order Type</label>
                    <select class="form-control" id="filterType">
                        <option value="">All Types</option>
                        <option value="CUSTOMER">Customer Order</option>
                        <option value="SUPPLIER">Supplier Order</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label class="filter-label" for="filterStatus">Approval Status</label>
                    <select class="form-control" id="filterStatus">
                        <option value="">All Status</option>
                        <option value="PENDING">Pending</option>
                        <option value="APPROVED">Approved</option>
                        <option value="REJECTED">Rejected</option>
                        <option value="CANCELLED">Cancelled</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label class="filter-label" for="filterDateFrom">CN Date From</label>
                    <input type="date" class="form-control" id="filterDateFrom">
                </div>

                <div class="filter-field">
                    <label class="filter-label" for="filterDateTo">CN Date To</label>
                    <input type="date" class="form-control" id="filterDateTo">
                </div>

                <div class="filter-field">
                    <label class="filter-label" for="filterSearch">Search</label>
                    <div class="search-wrap">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" class="form-control" id="filterSearch"
                               placeholder="Search CN No, Order No, Customer...">
                    </div>
                </div>

            </div>

            <div class="filter-actions">
                <button type="button" class="btn btn-primary btn-filter" id="btnFilterCN">
                    <i class="ti ti-search me-1"></i> Search
                </button>
                <button type="button" class="btn btn-outline-secondary btn-filter" id="btnResetCN">
                    <i class="ti ti-refresh me-1"></i> Reset
                </button>
            </div>

            <button type="button" class="btn-add" id="btnCreateCN">
                <i class="ti ti-plus"></i> Create Credit Note
            </button>
        </div>

        <div class="table-area">
            <div class="table-responsive">
                <table class="table align-middle" id="creditNoteMainTable">
                    <thead>
                        <tr>
                            <th>Credit Note Number</th>
                            <th>CN Date</th>
                            <th>Type</th>
                            <th>Order Number</th>
                            <th>Customer / Supplier</th>
                            <th class="text-end">CN Price</th>
                            <th class="text-end">CN Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="creditNoteTable">
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="ti ti-loader"></i> Loading Credit Notes...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel-footer">
            <div id="cnRecordInfo">Showing 0 records</div>
            <div class="pagination-controls">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnPrevPage">
                    <i class="ti ti-chevron-left"></i> Previous
                </button>
                <span class="small" id="cnPageInfo">Page 1</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnNextPage">
                    Next <i class="ti ti-chevron-right"></i>
                </button>
            </div>
        </div>

    </section>
</div>

<?php $this->load->view('admin/credit_note/form'); ?>

<script>
window.CREDIT_NOTE_CONFIG = {
    baseUrl: <?= json_encode(base_url()) ?>,
    endpoints: {
        list: <?= json_encode(site_url('credit_note/get_list')) ?>,
        orderOptions: <?= json_encode(site_url('credit_note/get_order_options')) ?>,
        orderDetail: <?= json_encode(site_url('credit_note/get_order_detail')) ?>,
        detail: <?= json_encode(site_url('credit_note/get_detail')) ?>,
        save: <?= json_encode(site_url('credit_note/save')) ?>
    }
};
</script>

<script>
(function ($) {
    'use strict';

    const cfg = window.CREDIT_NOTE_CONFIG || {};
    const ep = cfg.endpoints || {};

    let currentPage = 1;
    const pageSize = 10;
    let allRows = [];
    let selectedOrder = null;
    let isEdit = false;

    /* =====================================================
       UTILITIES
    ===================================================== */

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function numberValue(value) {
        if (value === null || value === undefined || value === '') return 0;
        const n = Number(value);
        return Number.isFinite(n) ? n : 0;
    }

    function formatNumber(value, decimals = 2) {
        return numberValue(value).toLocaleString('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    function formatCurrency(value) {
        return 'Rp ' + formatNumber(value, 2);
    }

    function formatDate(value) {
        if (!value) return '-';
        const parts = String(value).substring(0, 10).split('-');
        if (parts.length !== 3) return value;
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function showAlert(message, type = 'warning') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: type, text: message });
        } else {
            alert(message);
        }
    }

    function ajaxPost(url, data) {
        return $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json'
        });
    }

    function showModal(selector) {
        const element = document.querySelector(selector);
        if (!element) return;

        if (window.bootstrap && window.bootstrap.Modal) {
            window.bootstrap.Modal.getOrCreateInstance(element).show();
        } else if ($.fn.modal) {
            $(selector).modal('show');
        } else {
            showAlert('Bootstrap modal belum tersedia.', 'error');
        }
    }

    function hideModal(selector) {
        const element = document.querySelector(selector);
        if (!element) return;

        if (window.bootstrap && window.bootstrap.Modal) {
            const instance = window.bootstrap.Modal.getInstance(element);
            if (instance) instance.hide();
        } else if ($.fn.modal) {
            $(selector).modal('hide');
        }
    }

    function setLoading($button, loading, text) {
        if (loading) {
            $button.data('original-html', $button.html());
            $button.prop('disabled', true);
            $button.html('<span class="spinner-border spinner-border-sm mr-1"></span> ' + text);
        } else {
            $button.prop('disabled', false);
            $button.html($button.data('original-html') || text);
        }
    }

    /* =====================================================
       LIST
    ===================================================== */

    function loadCreditNotes() {
        const filters = {
            search: $('#filterSearch').val(),
            order_type: $('#filterType').val(),
            status: $('#filterStatus').val(),
            date_from: $('#filterDateFrom').val(),
            date_to: $('#filterDateTo').val()
        };

        $('#creditNoteTable').html(
            '<tr><td colspan="9" class="empty-state">Loading Credit Notes...</td></tr>'
        );

        ajaxPost(ep.list, filters)
            .done(function (response) {
                if (!response || !response.success) {
                    allRows = [];
                    renderCreditNotes();
                    showAlert(response && response.message ? response.message : 'Failed to load Credit Notes.');
                    return;
                }

                allRows = Array.isArray(response.data) ? response.data : [];
                currentPage = 1;
                renderCreditNotes();
            })
            .fail(function (xhr) {
                allRows = [];
                renderCreditNotes();
                console.error(xhr.responseText);
                showAlert('Terjadi kesalahan saat mengambil data Credit Note.', 'error');
            });
    }

    function getFilteredRows() {
        const search = String($('#filterSearch').val() || '').toLowerCase().trim();
        const type = $('#filterType').val();
        const status = $('#filterStatus').val();
        const dateFrom = $('#filterDateFrom').val();
        const dateTo = $('#filterDateTo').val();

        return allRows.filter(function (row) {
            const text = [
                row.CN_NO,
                row.ORDER_NO,
                row.ORDER_DETAIL_NO,
                row.CUSTOMER_NAME,
                row.SUPPLIER_NAME
            ].join(' ').toLowerCase();

            if (search && text.indexOf(search) === -1) return false;
            if (type && row.ORDER_TYPE !== type) return false;
            if (status && row.APPROVAL_STATUS !== status) return false;

            const date = String(row.CN_DATE || '').substring(0, 10);
            if (dateFrom && date < dateFrom) return false;
            if (dateTo && date > dateTo) return false;

            return true;
        });
    }

    function statusBadge(status) {
        const value = String(status || 'PENDING').toUpperCase();
        const classes = {
            PENDING: 'cn-pending',
            APPROVED: 'cn-approved',
            REJECTED: 'cn-rejected',
            CANCELLED: 'cn-cancelled'
        };

        return '<span class="cn-badge ' + (classes[value] || 'cn-cancelled') + '">' +
            escapeHtml(value) + '</span>';
    }

    function renderCreditNotes() {
        const rows = getFilteredRows();
        const total = rows.length;
        const pages = Math.max(1, Math.ceil(total / pageSize));

        if (currentPage > pages) currentPage = pages;

        const start = (currentPage - 1) * pageSize;
        const pageRows = rows.slice(start, start + pageSize);

        let html = '';

        if (!pageRows.length) {
            html = `
                <tr>
                    <td colspan="9" class="empty-state">
                        <i class="ti ti-file-off"></i>
                        No Credit Note found.
                    </td>
                </tr>`;
        } else {
            pageRows.forEach(function (row, index) {
                const party = row.ORDER_TYPE === 'CUSTOMER'
                    ? row.CUSTOMER_NAME
                    : row.SUPPLIER_NAME;

                const canEdit = row.APPROVAL_STATUS === 'PENDING';

                html += `
                    <tr>
                        <td>
                            <span class="cn-no">${escapeHtml(row.CN_NO)}</span>
                        </td>
                        <td>${formatDate(row.CN_DATE)}</td>
                        <td>
                            <span class="cn-badge cn-type">${escapeHtml(row.ORDER_TYPE)}</span>
                        </td>
                        <td>
                            <strong>${escapeHtml(row.ORDER_NO || '-')}</strong>
                            <span class="cn-subtext">${escapeHtml(row.ORDER_DETAIL_NO || '')}</span>
                        </td>
                        <td>${escapeHtml(party || '-')}</td>
                        <td class="number-cell">${formatCurrency(row.CN_PRICE)}</td>
                        <td class="number-cell total">${formatCurrency(row.CN_TOTAL)}</td>
                        <td class="text-center">${statusBadge(row.APPROVAL_STATUS)}</td>
                        <td class="action-cell">
                            <div class="action-group">
                                ${
                                    canEdit
                                    ? `<button type="button"
                                               class="btn-action btn-edit-cn"
                                               data-id="${Number(row.CREDIT_NOTE_ID)}"
                                               title="Edit Credit Note">
                                            <i class="ti ti-edit"></i> Edit
                                       </button>`
                                    : `<span class="text-muted small">-</span>`
                                }
                            </div>
                        </td>
                    </tr>`;
            });
        }

        $('#creditNoteTable').html(html);

        $('#cnRecordInfo').text(
            total
                ? 'Showing ' + (start + 1) + ' - ' + Math.min(start + pageSize, total) + ' of ' + total + ' records'
                : 'Showing 0 records'
        );

        $('#cnPageInfo').text('Page ' + currentPage + ' of ' + pages);
        $('#btnPrevPage').prop('disabled', currentPage <= 1);
        $('#btnNextPage').prop('disabled', currentPage >= pages);
    }

    /* =====================================================
       FORM RESET / CREATE
    ===================================================== */

    function resetForm() {
        const form = $('#formCreditNote')[0];
        if (form) form.reset();

        $('#cn_id').val('');
        $('#cn_order_detail_id').val('');
        $('#cn_number').val('Auto Generate');

        $('#cn_order_display').val('');
        $('#cn_order_type').prop('disabled', false);
        $('#btnSelectOrder').prop('disabled', true);

        $('#cnOrderPanel').hide();
        $('#cnCalculationPanel').hide();

        $('#cnFormError').addClass('d-none').text('');
        $('#cn_total_display').val('0');

        selectedOrder = null;
        isEdit = false;

        $('#cnModalTitle').text('Create Credit Note');
        $('#btnSaveCN')
            .prop('disabled', false)
            .html('<i class="fas fa-save mr-1"></i> Save Credit Note');
    }

    function openCreateForm() {
        resetForm();

        const today = new Date();
        const localDate = new Date(today.getTime() - today.getTimezoneOffset() * 60000)
            .toISOString().slice(0, 10);

        $('#cn_date').val(localDate);
        showModal('#modalCreditNote');
    }

    /* =====================================================
       ORDER SELECTION
    ===================================================== */

    function loadOrderOptions() {
        const type = $('#cn_order_type').val();

        if (!type) {
            $('#orderSelectionTable').html(
                '<tr><td colspan="10" class="text-center text-muted py-4">Select Order Type first.</td></tr>'
            );
            return;
        }

        $('#orderSelectionTable').html(
            '<tr><td colspan="10" class="text-center py-4">Loading...</td></tr>'
        );

        ajaxPost(ep.orderOptions, {
            order_type: type,
            search: $('#orderSearch').val()
        })
        .done(function (response) {
            if (!response || !response.success) {
                $('#orderSelectionTable').html(
                    '<tr><td colspan="10" class="text-center text-danger py-4">Failed to load Order.</td></tr>'
                );
                return;
            }

            const rows = response.data || [];

            if (!rows.length) {
                $('#orderSelectionTable').html(
                    '<tr><td colspan="10" class="text-center text-muted py-4">No available Order found.</td></tr>'
                );
                return;
            }

            let html = '';

            rows.forEach(function (row) {
                html += `
                    <tr>
                        <td><strong>${escapeHtml(row.ORDER_NO)}</strong></td>
                        <td>${escapeHtml(row.ORDER_DETAIL_NO)}</td>
                        <td>${formatDate(row.ORDER_DATE)}</td>
                        <td>${escapeHtml(row.PARTY_NAME || '-')}</td>
                        <td>${escapeHtml(row.BASIS)}</td>
                        <td class="text-right">${formatNumber(row.EKOR, 3)}</td>
                        <td class="text-right">${formatNumber(row.KG, 3)}</td>
                        <td class="text-right">${formatCurrency(row.UNIT_PRICE)}</td>
                        <td class="text-right font-weight-bold">${formatCurrency(row.TOTAL)}</td>
                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-sm btn-primary btn-pick-order"
                                    data-id="${Number(row.ORDER_DETAIL_ID)}">
                                Select
                            </button>
                        </td>
                    </tr>`;
            });

            $('#orderSelectionTable').html(html);
        })
        .fail(function (xhr) {
            console.error(xhr.responseText);
            $('#orderSelectionTable').html(
                '<tr><td colspan="10" class="text-center text-danger py-4">Server error.</td></tr>'
            );
        });
    }

    function openOrderModal() {
        if (!$('#cn_order_type').val()) {
            showAlert('Please select Order Type first.');
            return;
        }

        $('#orderSearch').val('');
        loadOrderOptions();
        showModal('#modalSelectOrder');
    }

    function selectOrder(detailId) {
        const type = $('#cn_order_type').val();

        ajaxPost(ep.orderDetail, {
            order_type: type,
            order_detail_id: detailId
        })
        .done(function (response) {
            if (!response || !response.success) {
                showAlert(response && response.message ? response.message : 'Order not found.');
                return;
            }

            selectedOrder = response.data;

            $('#cn_order_detail_id').val(selectedOrder.ORDER_DETAIL_ID);
            $('#cn_order_display').val(
                selectedOrder.ORDER_NO + ' / ' + selectedOrder.ORDER_DETAIL_NO
            );

            renderSelectedOrder();
            hideModal('#modalSelectOrder');
        })
        .fail(function (xhr) {
            console.error(xhr.responseText);
            showAlert('Failed to get Order detail.', 'error');
        });
    }

    function renderSelectedOrder() {
        if (!selectedOrder) return;

        $('#cnOrderPanel').show();
        $('#cnCalculationPanel').show();

        $('#cn_detail_order_no').text(selectedOrder.ORDER_NO || '-');
        $('#cn_detail_no').text(selectedOrder.ORDER_DETAIL_NO || '-');
        $('#cn_detail_date').text(formatDate(selectedOrder.ORDER_DATE));
        $('#cn_detail_plant').text(selectedOrder.PLANT || '-');
        $('#cn_detail_party').text(selectedOrder.PARTY_NAME || '-');

        $('#cn_detail_basis').text(selectedOrder.BASIS || '-');
        $('#cn_detail_ekor').text(formatNumber(selectedOrder.EKOR, 3));
        $('#cn_detail_kg').text(formatNumber(selectedOrder.KG, 3));
        $('#cn_detail_avg_bw').text(formatNumber(selectedOrder.AVG_BW, 4));

        $('#cn_detail_order_price').text(formatCurrency(selectedOrder.UNIT_PRICE));
        $('#cn_detail_order_total').text(formatCurrency(selectedOrder.TOTAL));

        const basis = String(selectedOrder.BASIS || '').toUpperCase();

        if (basis === 'EKOR') {
            $('#cnFormulaText').text(
                'Formula: CN Price × Ekor (' + formatNumber(selectedOrder.EKOR, 3) + ')'
            );
        } else if (basis === 'BW') {
            $('#cnFormulaText').text(
                'Formula: CN Price × KG (' + formatNumber(selectedOrder.KG, 3) + ')'
            );
        }

        calculateCNTotal();
    }

    /* =====================================================
       CN CALCULATION
    ===================================================== */

    function calculateCNTotal() {
        if (!selectedOrder) {
            $('#cn_total_display').val('0');
            return;
        }

        const price = numberValue($('#cn_price').val());
        const basis = String(selectedOrder.BASIS || '').toUpperCase();

        let quantity = 0;
        if (basis === 'EKOR') {
            quantity = numberValue(selectedOrder.EKOR);
        } else if (basis === 'BW') {
            quantity = numberValue(selectedOrder.KG);
        }

        const total = Math.round((price * quantity + Number.EPSILON) * 100) / 100;
        const orderTotal = numberValue(selectedOrder.TOTAL);
        const exceeds = total > orderTotal + 0.00001;

        $('#cn_total_display').val(formatCurrency(total));

        if (exceeds) {
            $('#cnLimitMessage')
                .removeClass('alert-info alert-success')
                .addClass('alert-danger')
                .text('CN Total (' + formatCurrency(total) + ') exceeds Order Total (' + formatCurrency(orderTotal) + ').');

            $('#btnSaveCN').prop('disabled', true);
        } else {
            $('#cnLimitMessage')
                .removeClass('alert-info alert-danger')
                .addClass('alert-success')
                .text('CN Total is within the Order Total limit.');

            $('#btnSaveCN').prop('disabled', false);
        }

        return total;
    }

    /* =====================================================
       EDIT
    ===================================================== */

    function openEditForm(id) {
        resetForm();
        isEdit = true;

        $('#cnModalTitle').text('Edit Credit Note');
        $('#btnSaveCN').html('<i class="fas fa-save mr-1"></i> Update Credit Note');

        $.ajax({
            url: ep.detail + '/' + encodeURIComponent(id),
            type: 'GET',
            dataType: 'json'
        })
        .done(function (response) {
            if (!response || !response.success) {
                showAlert(response && response.message ? response.message : 'Credit Note not found.');
                return;
            }

            const row = response.data;

            if (row.APPROVAL_STATUS !== 'PENDING') {
                showAlert('Only PENDING Credit Notes can be edited.');
                return;
            }

            $('#cn_id').val(row.CREDIT_NOTE_ID);
            $('#cn_number').val(row.CN_NO);
            $('#cn_date').val(String(row.CN_DATE).substring(0, 10));
            $('#cn_order_type').val(row.ORDER_TYPE).prop('disabled', true);
            $('#cn_remark').val(row.REMARK || '');
            $('#cn_price').val(numberValue(row.CN_PRICE));

            const detailId = row.ORDER_TYPE === 'CUSTOMER'
                ? row.ORDER_CUSTOMER_ID
                : row.ORDER_SUPPLIER_ID;

            ajaxPost(ep.orderDetail, {
                order_type: row.ORDER_TYPE,
                order_detail_id: detailId
            })
            .done(function (orderResponse) {
                if (!orderResponse || !orderResponse.success) {
                    showAlert('Order detail for this Credit Note could not be loaded.');
                    return;
                }

                selectedOrder = orderResponse.data;

                $('#cn_order_detail_id').val(detailId);
                $('#cn_order_display').val(
                    selectedOrder.ORDER_NO + ' / ' + selectedOrder.ORDER_DETAIL_NO
                );

                renderSelectedOrder();
                calculateCNTotal();
                showModal('#modalCreditNote');
            })
            .fail(function (xhr) {
                console.error(xhr.responseText);
                showAlert('Failed to load Order detail.', 'error');
            });
        })
        .fail(function (xhr) {
            console.error(xhr.responseText);
            showAlert('Failed to load Credit Note.', 'error');
        });
    }

    /* =====================================================
       SAVE
    ===================================================== */

    function saveCreditNote(event) {
        event.preventDefault();

        if (!selectedOrder || !$('#cn_order_detail_id').val()) {
            showAlert('Please select an Order.');
            return;
        }

        if (!$('#cn_date').val()) {
            showAlert('CN Date is required.');
            return;
        }

        const price = $('#cn_price').val();

        if (price === '' || numberValue(price) < 0) {
            showAlert('CN Price must be zero or greater.');
            return;
        }

        const total = calculateCNTotal();

        if (total === undefined || total > numberValue(selectedOrder.TOTAL) + 0.00001) {
            showAlert('CN Total cannot exceed Order Total.');
            return;
        }

        const $button = $('#btnSaveCN');
        setLoading($button, true, 'Saving...');

        ajaxPost(ep.save, {
            credit_note_id: $('#cn_id').val(),
            order_type: $('#cn_order_type').val(),
            order_detail_id: $('#cn_order_detail_id').val(),
            cn_date: $('#cn_date').val(),
            cn_price: price,
            remark: $('#cn_remark').val()
        })
        .done(function (response) {
            if (!response || !response.success) {
                showAlert(response && response.message ? response.message : 'Failed to save Credit Note.');
                return;
            }

            hideModal('#modalCreditNote');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Credit Note saved successfully.'
                });
            }

            loadCreditNotes();
        })
        .fail(function (xhr) {
            console.error(xhr.responseText);
            showAlert('Server error while saving Credit Note.', 'error');
        })
        .always(function () {
            setLoading($button, false, 'Save Credit Note');
        });
    }

    /* =====================================================
       EVENTS
    ===================================================== */

    $(document).ready(function () {
        loadCreditNotes();

        $('#btnCreateCN').on('click', openCreateForm);
        $('#btnFilterCN').on('click', loadCreditNotes);

        $('#btnResetCN').on('click', function () {
            $('#filterSearch, #filterType, #filterStatus, #filterDateFrom, #filterDateTo').val('');
            loadCreditNotes();
        });

        $('#filterSearch').on('keyup', function (e) {
            if (e.key === 'Enter') loadCreditNotes();
        });

        $('#btnPrevPage').on('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderCreditNotes();
            }
        });

        $('#btnNextPage').on('click', function () {
            const pages = Math.max(1, Math.ceil(getFilteredRows().length / pageSize));
            if (currentPage < pages) {
                currentPage++;
                renderCreditNotes();
            }
        });

        $('#cn_order_type').on('change', function () {
            selectedOrder = null;
            $('#cn_order_detail_id').val('');
            $('#cn_order_display').val('');
            $('#cnOrderPanel, #cnCalculationPanel').hide();
            $('#cn_price').val('');
            $('#cn_total_display').val('0');
            $('#btnSelectOrder').prop('disabled', !this.value);
        });

        $('#btnSelectOrder').on('click', openOrderModal);
        $('#btnSearchOrder').on('click', loadOrderOptions);

        $('#orderSearch').on('keyup', function (e) {
            if (e.key === 'Enter') loadOrderOptions();
        });

        $(document).on('click', '.btn-pick-order', function () {
            selectOrder($(this).data('id'));
        });

        $('#cn_price').on('input', calculateCNTotal);
        $('#formCreditNote').on('submit', saveCreditNote);

        $(document).on('click', '.btn-edit-cn', function () {
            openEditForm($(this).data('id'));
        });

        $('#modalSelectOrder').on('hidden.bs.modal', function () {
            if (!$('#modalCreditNote').hasClass('show') &&
                $('#cn_order_detail_id').val()) {
                showModal('#modalCreditNote');
            }
        });
    });

})(jQuery);
</script>
