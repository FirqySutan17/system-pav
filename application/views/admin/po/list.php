<style>
/* Purchase Order - aligned with Customer list UI */
.po-page{color:#243247}
.po-page .page-heading{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin:90px 0 22px}
.po-page .eyebrow{display:inline-flex;align-items:center;gap:7px;color:#64748b;font-size:.76rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;margin-bottom:7px}
.po-page .page-title{margin:0;font-size:1.55rem;font-weight:750;letter-spacing:-.035em;color:#172b4d}
.po-page .page-subtitle{margin:7px 0 0;color:#718096;font-size:.92rem}
.po-page .panel{border:1px solid #e7edf5;border-radius:16px;background:#fff;box-shadow:0 8px 28px rgba(22,42,75,.055);overflow:hidden}
.po-page .panel-toolbar{display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap;padding:20px 22px;border-bottom:1px solid #edf1f7;background:linear-gradient(180deg,#fff 0%,#fbfcff 100%)}
.po-page .filter-grid{display:grid;grid-template-columns:minmax(170px,1fr) repeat(2,minmax(145px,.8fr)) minmax(220px,1.4fr);gap:12px;flex:1;min-width:min(100%,600px)}
.po-page .filter-field{min-width:0}.po-page .filter-label{display:block;margin:0 0 6px;color:#718096;font-size:.73rem;font-weight:750;letter-spacing:.04em;text-transform:uppercase}
.po-page .form-select,.po-page .form-control{min-height:44px;border:1px solid #dfe6f0;border-radius:10px;box-shadow:none;color:#334155;background-color:#fff}
.po-page .form-select:focus,.po-page .form-control:focus{border-color:#8aa4ff;box-shadow:0 0 0 3px rgba(99,126,255,.12)}
.po-page .search-wrap{position:relative}.po-page .search-wrap .search-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none}.po-page .search-wrap .form-control{padding-left:40px}
.po-page .btn-add{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:44px;padding:0 17px;border:0;border-radius:10px;background:linear-gradient(135deg,#627dff,#4965e8);color:#fff;font-weight:650;box-shadow:0 5px 12px rgba(79,103,232,.2);text-decoration:none;white-space:nowrap;transition:transform .15s,box-shadow .15s}
.po-page .btn-add:hover{color:#fff;transform:translateY(-1px);box-shadow:0 8px 16px rgba(79,103,232,.25)}
.po-page .filter-actions{display:flex;align-items:flex-end;gap:8px}.po-page .btn-filter{min-height:44px;border-radius:10px;padding:0 15px;font-weight:650}
.po-page .table-area{padding:10px 12px 0}.po-page #mainTable{margin:0;border-collapse:separate;border-spacing:0;white-space:nowrap}
.po-page #mainTable thead th{padding:15px 14px;background:#f7f9fc;color:#66758b;font-size:.73rem;text-transform:uppercase;letter-spacing:.055em;font-weight:750;white-space:nowrap;border-top:0;border-bottom:1px solid #e8edf4}
.po-page #mainTable thead th:first-child{border-radius:9px 0 0 0}.po-page #mainTable thead th:last-child{border-radius:0 9px 0 0}.po-page #mainTable th[data-order]{cursor:pointer;user-select:none}.po-page #mainTable th[data-order]:hover{color:#4e68e8}
.po-page #mainTable tbody td{padding:15px 14px;border-bottom:1px solid #edf1f6;color:#43536a;vertical-align:middle;font-size:.86rem}.po-page #mainTable tbody tr{transition:background .15s}.po-page #mainTable tbody tr:hover{background:#f8faff}.po-page #mainTable tbody tr:last-child td{border-bottom:0}
.po-page .po-code{font-weight:750;color:#3d58d8;white-space:nowrap}.po-page .supplier-name{color:#243247;font-weight:650;min-width:170px}.po-page .number-cell{font-variant-numeric:tabular-nums;text-align:right}.po-page .status-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:.7rem;line-height:1;font-weight:750;letter-spacing:.025em}.po-page .status-open{color:#425bd4;background:#edf0ff}.po-page .status-partial{color:#9a5a00;background:#fff5df}.po-page .status-completed{color:#087f5b;background:#e4f8ef}.po-page .status-cancelled{color:#64748b;background:#eef2f6}
.po-page .action-cell{width:1%;white-space:nowrap}.po-page .action-group{display:flex;justify-content:center;align-items:center;gap:5px}.po-page .btn-action{display:inline-flex;align-items:center;justify-content:center;gap:5px;border:1px solid transparent;border-radius:8px;padding:7px 10px;font-size:.76rem;font-weight:650;line-height:1.2;text-decoration:none;white-space:nowrap;transition:all .15s}.po-page .btn-detail{color:#4261dc;background:#eef2ff;border-color:#e0e7ff}.po-page .btn-edit{color:#9a5a00;background:#fff5df;border-color:#ffebbd}.po-page .btn-action:hover{filter:brightness(.97)}
.po-page .panel-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:16px 22px 20px;color:#7b8798;font-size:.84rem}.po-page #pagination ul{margin:0}.po-page #pagination .pagination{gap:5px}.po-page #pagination .page-link{border:1px solid #e2e8f0;border-radius:8px!important;color:#58677c;padding:7px 11px;box-shadow:none}.po-page #pagination .page-item.active .page-link{background:#536de9;border-color:#536de9;color:#fff}.po-page .empty-state{padding:38px 16px!important;text-align:center;color:#8491a4!important}
@media(max-width:991px){.po-page .filter-grid{grid-template-columns:repeat(2,minmax(0,1fr));min-width:100%}.po-page .panel-toolbar{align-items:stretch}}
@media(max-width:767px){.po-page .page-title{font-size:1.3rem}.po-page .page-heading{margin-top:80px}.po-page .panel-toolbar{padding:16px}.po-page .filter-grid{grid-template-columns:1fr}.po-page .filter-actions{width:100%}.po-page .filter-actions .btn{flex:1}.po-page .btn-add{width:100%}.po-page .table-area{padding:0 8px}.po-page .panel-footer{padding:14px 16px}}
</style>

<div class="container-fluid po-page px-2 px-lg-3 py-2">
    <div class="page-heading">
        <div>
            <div class="eyebrow"><i class="ti ti-clipboard-list"></i> Inventory</div>
            <h1 class="page-title">Purchase Order</h1>
            <p class="page-subtitle">Kelola pesanan pembelian ayam, jumlah ekor, berat badan (BW), dan nilai pembelian.</p>
        </div>
    </div>

    <section class="panel">
        <div class="panel-toolbar">
            <div class="filter-grid">
                <div class="filter-field">
                    <label class="filter-label" for="filterSupplier">Supplier</label>
                    <select id="filterSupplier" class="form-select" aria-label="Filter supplier">
                        <option value="">Semua Supplier</option>
                        <?php if (isset($suppliers) && is_array($suppliers)): ?>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= html_escape($supplier['CUST']); ?>"><?= html_escape($supplier['CUST'] . ' - ' . $supplier['FULL_NAME']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="filter-field">
                    <label class="filter-label" for="dateFrom">PO Date From</label>
                    <input type="date" id="dateFrom" class="form-control" aria-label="Tanggal PO dari">
                </div>
                <div class="filter-field">
                    <label class="filter-label" for="dateTo">PO Date To</label>
                    <input type="date" id="dateTo" class="form-control" aria-label="Tanggal PO sampai">
                </div>
                <div class="filter-field">
                    <label class="filter-label" for="search">Search</label>
                    <div class="search-wrap">
                        <i class="ti ti-search search-icon"></i>
                        <input type="text" id="search" class="form-control" placeholder="Cari nomor PO atau supplier..." aria-label="Cari Purchase Order">
                    </div>
                </div>
            </div>
            <div class="filter-actions">
                <button type="button" id="btnSearch" class="btn btn-primary btn-filter"><i class="ti ti-search me-1"></i> Search</button>
                <button type="button" id="btnReset" class="btn btn-outline-secondary btn-filter">Reset</button>
            </div>
            <a href="<?= base_url('po/create'); ?>" class="btn-add"><i class="ti ti-plus"></i> Add PO</a>
        </div>

        <div class="table-area">
            <div class="table-responsive">
                <table class="table align-middle" id="mainTable">
                    <thead>
                        <tr>
                            <th data-order="PO_NO">PO Number</th>
                            <th data-order="PO_DATE">PO Date</th>
                            <th data-order="SUPPLIER_NAME">Supplier</th>
                            <th class="text-end" data-order="TOTAL_EKOR">Total Ekor</th>
                            <th class="text-end" data-order="TOTAL_BW">Total BW (kg)</th>
                            <th class="text-end" data-order="TOTAL_AMOUNT">Total Amount</th>
                            <th class="text-center" data-order="STATUS">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr><td colspan="8" class="empty-state"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <div id="info">Memuat data...</div>
            <div id="pagination"></div>
        </div>
    </section>
</div>

<script>
(function ($) {
    'use strict';

    const URL = {
        load: '<?= base_url("po/load_data"); ?>',
        detail: '<?= base_url("po/detail"); ?>',
        edit: '<?= base_url("po/edit"); ?>'
    };

    const state = {
        page: 1,
        limit: 10,
        order: 'PO_DATE',
        dir: 'DESC',
        search: '',
        supplier: '',
        date_from: '',
        date_to: ''
    };
    let searchTimer = null;

    $(function () {
        loadPage(1);

        $('#search').on('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                state.search = $('#search').val().trim();
                loadPage(1);
            }, 350);
        });

        $('#btnSearch').on('click', function () { applyFilters(); });
        $('#filterSupplier, #dateFrom, #dateTo').on('change', function () { applyFilters(); });
        $('#btnReset').on('click', function () {
            $('#filterSupplier').val('');
            $('#dateFrom, #dateTo, #search').val('');
            state.supplier = '';
            state.date_from = '';
            state.date_to = '';
            state.search = '';
            loadPage(1);
        });

        $('#mainTable thead').on('click', 'th[data-order]', function () {
            const col = String($(this).data('order') || 'PO_DATE');
            if (state.order === col) {
                state.dir = state.dir === 'ASC' ? 'DESC' : 'ASC';
            } else {
                state.order = col;
                state.dir = 'ASC';
            }
            loadPage(1);
        });
    });

    function applyFilters() {
        state.supplier = $('#filterSupplier').val() || '';
        state.date_from = $('#dateFrom').val() || '';
        state.date_to = $('#dateTo').val() || '';
        state.search = $('#search').val().trim();
        loadPage(1);
    }

    window.loadPage = function (page) {
        state.page = Math.max(1, parseInt(page, 10) || 1);
        $('#table-body').html('<tr><td colspan="8" class="empty-state"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Memuat data...</td></tr>');

        $.getJSON(URL.load, state)
            .done(function (res) {
                renderTable(res.rows || []);
                $('#pagination').html(res.pagination || '');
                $('#info').text('Menampilkan ' + ((res.rows || []).length) + ' dari ' + (res.total || 0) + ' data');
            })
            .fail(function (xhr) {
                console.error(xhr.responseText);
                $('#table-body').html('<tr><td colspan="8" class="text-center text-danger py-4">Gagal memuat data PO. Periksa controller dan koneksi database.</td></tr>');
                $('#pagination, #info').empty();
            });
    };

    function renderTable(rows) {
        if (!rows.length) {
            $('#table-body').html('<tr><td colspan="8" class="empty-state">Tidak ada data Purchase Order.</td></tr>');
            return;
        }
        let html = '';
        rows.forEach(function (r) {
            const poNo = String(r.PO_NO || '');
            const status = String(r.STATUS || 'OPEN').toUpperCase();
            const statusClass = status === 'COMPLETED' ? 'status-completed' : (status === 'PARTIAL' || status === 'PARTIAL_RECEIVE' ? 'status-partial' : (status === 'CANCELLED' ? 'status-cancelled' : 'status-open'));
            const statusLabel = status === 'PARTIAL_RECEIVE' ? 'PARTIAL RECEIVE' : status;
            const detailUrl = URL.detail + '/' + encodeURIComponent(poNo);
            const editUrl = URL.edit + '/' + encodeURIComponent(poNo);
            html += '<tr>' +
                '<td><span class="po-code">#' + escapeHtml(poNo) + '</span></td>' +
                '<td>' + escapeHtml(formatDate(r.PO_DATE)) + '</td>' +
                '<td><span class="supplier-name">' + escapeHtml(r.SUPPLIER_NAME || r.SUPPLIER || '-') + '</span></td>' +
                '<td class="number-cell">' + escapeHtml(formatNumber(r.TOTAL_EKOR, 0)) + '</td>' +
                '<td class="number-cell">' + escapeHtml(formatNumber(r.TOTAL_BW, 2)) + '</td>' +
                '<td class="number-cell">' + escapeHtml(formatRupiah(r.TOTAL_AMOUNT)) + '</td>' +
                '<td class="text-center"><span class="status-badge ' + statusClass + '">' + escapeHtml(statusLabel || 'OPEN') + '</span></td>' +
                '<td class="action-cell"><div class="action-group">' +
                    '<a class="btn-action btn-detail" href="' + escapeHtml(detailUrl) + '">Detail</a>' +
                    (status === 'CANCELLED' || status === 'COMPLETED' ? '' : '<a class="btn-action btn-edit" href="' + escapeHtml(editUrl) + '">Edit</a>') +
                '</div></td>' +
                '</tr>';
        });
        $('#table-body').html(html);
    }

    function formatDate(value) {
        if (!value) return '-';
        const s = String(value).substring(0, 10);
        const p = s.split('-');
        return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : s;
    }
    function formatNumber(value, decimals) {
        const n = Number(value || 0);
        return Number.isFinite(n) ? n.toLocaleString('id-ID', {minimumFractionDigits: decimals, maximumFractionDigits: decimals}) : '0';
    }
    function formatRupiah(value) {
        return 'Rp ' + formatNumber(value, 2);
    }
    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }
})(jQuery);
</script>
