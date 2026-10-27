<style>
/* Customer & Supplier - modern list */
.customer-page { color: #243247; }
.customer-page .page-heading {
    display:flex; align-items:flex-start; justify-content:space-between;
    gap:16px; flex-wrap:wrap; margin-bottom:22px; margin-top: 90px
}
.customer-page .eyebrow {
    display:inline-flex; align-items:center; gap:7px; color:#64748b;
    font-size:.76rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
    margin-bottom:7px;
}
.customer-page .page-title { margin:0; font-size:1.55rem; font-weight:750; letter-spacing:-.035em; color:#172b4d; }
.customer-page .page-subtitle { margin:7px 0 0; color:#718096; font-size:.92rem; }
.customer-page .panel {
    border:1px solid #e7edf5; border-radius:16px; background:#fff;
    box-shadow:0 8px 28px rgba(22,42,75,.055); overflow:hidden;
}
.customer-page .panel-toolbar {
    display:flex; justify-content:space-between; align-items:center; gap:14px;
    flex-wrap:wrap; padding:20px 22px; border-bottom:1px solid #edf1f7;
    background:linear-gradient(180deg,#fff 0%,#fbfcff 100%);
}
.customer-page .toolbar-left { display:flex; gap:12px; flex:1; min-width:min(100%,420px); flex-wrap:wrap; }
.customer-page .filter-wrap { width:180px; max-width:100%; }
.customer-page .search-wrap { position:relative; flex:1; min-width:220px; }
.customer-page .search-wrap .search-icon {
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    color:#94a3b8; pointer-events:none;
}
.customer-page .form-select, .customer-page .form-control {
    min-height:44px; border:1px solid #dfe6f0; border-radius:10px;
    box-shadow:none; color:#334155; background-color:#fff;
}
.customer-page .search-wrap .form-control { padding-left:40px; }
.customer-page .form-select:focus, .customer-page .form-control:focus {
    border-color:#8aa4ff; box-shadow:0 0 0 3px rgba(99,126,255,.12);
}
.customer-page .btn-add {
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    min-height:44px; padding:0 17px; border:0; border-radius:10px;
    background:linear-gradient(135deg,#627dff,#4965e8); color:#fff;
    font-weight:650; box-shadow:0 5px 12px rgba(79,103,232,.2);
    text-decoration:none; transition:transform .15s,box-shadow .15s;
}
.customer-page .btn-add:hover { color:#fff; transform:translateY(-1px); box-shadow:0 8px 16px rgba(79,103,232,.25); }
.customer-page .table-area { padding:10px 12px 0; }
.customer-page #mainTable { margin:0; border-collapse:separate; border-spacing:0; }
.customer-page #mainTable thead th {
    padding:15px 14px; background:#f7f9fc; color:#66758b; font-size:.73rem;
    text-transform:uppercase; letter-spacing:.055em; font-weight:750; white-space:nowrap;
    border-top:0; border-bottom:1px solid #e8edf4;
}
.customer-page #mainTable thead th:first-child { border-radius:9px 0 0 0; }
.customer-page #mainTable thead th:last-child { border-radius:0 9px 0 0; }
.customer-page #mainTable th[data-order] { cursor:pointer; user-select:none; }
.customer-page #mainTable th[data-order]:hover { color:#4e68e8; }
.customer-page #mainTable tbody td {
    padding:16px 14px; border-bottom:1px solid #edf1f6; color:#43536a;
    vertical-align:middle; font-size:.88rem;
}
.customer-page #mainTable tbody tr { transition:background .15s; }
.customer-page #mainTable tbody tr:hover { background:#f8faff; }
.customer-page #mainTable tbody tr:last-child td { border-bottom:0; }
.customer-page .cust-code { font-weight:700; color:#3d58d8; white-space:nowrap; }
.customer-page .company-name { color:#243247; font-weight:650; min-width:190px; }

.customer-page .badge.bg-primary { color:#425bd4!important; background:#edf0ff!important; }
.customer-page .badge.bg-info { color:#087e8b!important; background:#e4f8f8!important; }
.customer-page .badge.bg-success { color:#087f5b!important; background:#e4f8ef!important; }
.customer-page .badge.bg-secondary { color:#64748b!important; background:#eef2f6!important; }
.customer-page #mainTable .btn-info { color:#4261dc; background:#eef2ff; border-color:#e0e7ff; }
.customer-page #mainTable .btn-warning { color:#9a5a00; background:#fff5df; border-color:#ffebbd; }
.customer-page #mainTable .btn-primary { color:#fff; background:#536de9; border-color:#536de9; }
.customer-page #mainTable .btn {
    border-radius:7px; font-weight:650; padding:6px 9px;
    font-size:.75rem; line-height:1.2; margin:0!important;
}
.customer-page #mainTable td.action-cell { width:1%; white-space:nowrap; }
.customer-page #mainTable .action-group {
    display:flex; justify-content:center; align-items:center; gap:4px;
}
.customer-page #mainTable .action-group .btn { flex:0 0 auto; }
.customer-page #mainTable .btn:hover { filter:brightness(.97); }
.customer-page .kind-badge, .customer-page .status-badge {
    display:inline-flex; align-items:center; gap:6px; padding:6px 10px;
    border-radius:999px; font-size:.72rem; line-height:1; font-weight:750; letter-spacing:.025em;
}
.customer-page .kind-customer { color:#425bd4; background:#edf0ff; }
.customer-page .kind-supplier { color:#087e8b; background:#e4f8f8; }
.customer-page .status-active { color:#087f5b; background:#e4f8ef; }
.customer-page .status-other { color:#64748b; background:#eef2f6; }
.customer-page .actions { display:flex; justify-content:center; align-items:center; gap:7px; }
.customer-page .btn-action {
    display:inline-flex; align-items:center; justify-content:center; gap:5px;
    border:1px solid transparent; border-radius:8px; padding:7px 10px;
    font-size:.78rem; font-weight:650; line-height:1.25; text-decoration:none; white-space:nowrap;
    transition:all .15s;
}
.customer-page .btn-detail { color:#4261dc; background:#eef2ff; border-color:#e0e7ff; }
.customer-page .btn-detail:hover { color:#304bc0; background:#e1e8ff; }
.customer-page .btn-edit { color:#9a5a00; background:#fff5df; border-color:#ffebbd; }
.customer-page .btn-edit:hover { color:#7a4600; background:#ffedc4; }
.customer-page .btn-credit { color:#fff; background:#536de9; border-color:#536de9; }
.customer-page .btn-credit:hover { color:#fff; background:#4058d1; }
.customer-page .panel-footer {
    display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
    padding:16px 22px 20px; color:#7b8798; font-size:.84rem;
}
.customer-page #pagination ul { margin:0; }
.customer-page #pagination .pagination { gap:5px; }
.customer-page #pagination .page-link {
    border:1px solid #e2e8f0; border-radius:8px !important; color:#58677c;
    padding:7px 11px; box-shadow:none;
}
.customer-page #pagination .page-item.active .page-link { background:#536de9; border-color:#536de9; color:#fff; }
.customer-page .empty-state { padding:38px 16px!important; text-align:center; color:#8491a4!important; }
.customer-page .modal-content { border:0; border-radius:16px; overflow:hidden; box-shadow:0 20px 60px rgba(20,35,70,.2); }
.customer-page .modal-header { padding:18px 22px; border-bottom:1px solid #edf1f6; }
.customer-page .modal-title { font-weight:750; color:#243247; }
.customer-page .modal-body { padding:22px; }
.customer-page .modal-footer { border-top:1px solid #edf1f6; padding:14px 22px; }

/* Detail modal - aligned with modern create/edit forms */
.customer-page .detail-modal-dialog { max-width: 900px; }
.customer-page .detail-modal-content {
    border: 1px solid #e8edf5;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 24px 70px rgba(20, 35, 70, .22);
}
.customer-page .detail-modal-header {
    padding: 22px 26px;
    background: linear-gradient(135deg, #ffffff 0%, #f7f9ff 100%);
    border-bottom: 1px solid #edf1f7;
}
.customer-page .detail-modal-kicker {
    display: flex; align-items: center; gap: 7px;
    margin-bottom: 6px; color: #64748b; font-size: .72rem;
    font-weight: 750; letter-spacing: .09em; text-transform: uppercase;
}
.customer-page .detail-modal-title {
    margin: 0; color: #172b4d; font-size: 1.22rem; font-weight: 750;
    letter-spacing: -.025em;
}
.customer-page .detail-modal-subtitle {
    margin: 5px 0 0; color: #8491a4; font-size: .86rem;
}
.customer-page .detail-modal-body { padding: 24px 26px; background:#fff; }
.customer-page .detail-section {
    margin-bottom: 20px; padding: 17px; border: 1px solid #e9eef6;
    border-radius: 13px; background: #fff;
}
.customer-page .detail-section:last-child { margin-bottom: 0; }
.customer-page .detail-section-heading {
    display:flex; align-items:center; gap:9px; margin:0 0 14px;
    color:#263b5b; font-size:.9rem; font-weight:750;
}
.customer-page .detail-section-icon {
    display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px; border-radius:9px;
    color:#526bea; background:#eef1ff; font-size:1rem;
}
.customer-page .detail-grid {
    display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px;
}
.customer-page .detail-field {
    min-width:0; padding:12px 13px; border:1px solid #edf1f6;
    border-radius:10px; background:#f9fbfe;
}
.customer-page .detail-field.detail-field-wide { grid-column:1 / -1; }
.customer-page .detail-label {
    margin-bottom:5px; color:#8793a6; font-size:.71rem; font-weight:750;
    letter-spacing:.055em; text-transform:uppercase;
}
.customer-page .detail-value {
    color:#2d3e58; font-size:.9rem; font-weight:550;
    overflow-wrap:anywhere; white-space:pre-wrap;
}
.customer-page .detail-value-code {
    color:#405bd2; font-weight:800; letter-spacing:.02em;
}
.customer-page .detail-status {
    display:inline-flex; align-items:center; gap:6px; padding:5px 9px;
    border-radius:999px; background:#e4f8ef; color:#087f5b;
    font-size:.74rem; font-weight:750; height: 35px;
}
.customer-page .detail-status.is-inactive {
    background:#eef2f6; color:#64748b;
}
.customer-page .detail-modal-footer {
    padding:15px 26px; border-top:1px solid #edf1f7; background:#fbfcff;
}
.customer-page .detail-close-btn {
    padding:9px 17px; border:1px solid #dfe6f0; border-radius:9px;
    color:#46566e; background:#fff; font-size:.84rem; font-weight:650;
}
.customer-page .detail-close-btn:hover { background:#f2f5fa; }
@media (max-width: 575px) {
    .customer-page .detail-modal-header,
    .customer-page .detail-modal-body { padding:18px; }
    .customer-page .detail-modal-footer { padding:13px 18px; }
    .customer-page .detail-grid { grid-template-columns:1fr; }
    .customer-page .detail-field.detail-field-wide { grid-column:auto; }
    .customer-page .detail-section { padding:13px; }
}

@media (max-width:767px) {
    .customer-page .page-title { font-size:1.3rem; }
    .customer-page .panel-toolbar { padding:16px; }
    .customer-page .toolbar-left { min-width:100%; }
    .customer-page .filter-wrap { width:100%; }
    .customer-page .search-wrap { min-width:100%; }
    .customer-page .btn-add { width:100%; }
    .customer-page .table-area { padding:0 8px; }
    .customer-page #mainTable .action-group { justify-content:flex-start; gap:4px; }
    .customer-page #mainTable .btn { padding:6px 8px; }
    .customer-page .panel-footer { padding:14px 16px; }
}
</style>

<div class="container-fluid customer-page px-2 px-lg-3 py-2">
    <div class="page-heading">
        <div>
            <div class="eyebrow"><i class="ti ti-database"></i> Master Data</div>
            <h1 class="page-title">Customer &amp; Supplier</h1>
            <p class="page-subtitle">Kelola data pelanggan dan pemasok dalam satu halaman.</p>
        </div>
    </div>

    <section class="panel">
        <div class="panel-toolbar">
            <div class="toolbar-left">
                <div class="filter-wrap">
                    <select id="filterKind" class="form-select" aria-label="Filter jenis">
                        <option value="ALL">Semua Jenis</option>
                        <option value="CUSTOMER">Customer</option>
                        <option value="SUPPLIER">Supplier</option>
                    </select>
                </div>
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>
                    <input type="text" id="search" class="form-control"
                           placeholder="Cari kode, nama perusahaan, penanggung jawab..." aria-label="Cari data">
                </div>
            </div>
            <a href="<?= base_url('customer/create'); ?>" class="btn-add">
                <i class="ti ti-plus"></i> Tambah Data
            </a>
        </div>

        <div class="table-area">
            <div class="table-responsive">
                <table class="table align-middle" id="mainTable">
                    <thead>
                        <tr>
                            <th data-order="CUST">Kode</th>
                            <th data-order="FULL_NAME">Nama Perusahaan</th>
                            <th class="text-center" data-order="CUST_KIND">Type</th>
                            <th class="text-center" data-order="CHIEF_NAME">PIC</th>
                            <th class="text-center" data-order="STATUS">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <tr><td colspan="6" class="empty-state"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Memuat data...</td></tr>
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

<!-- DETAIL MODAL -->
<div class="modal fade customer-page" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable detail-modal-dialog">
        <div class="modal-content detail-modal-content">
            <div class="modal-header detail-modal-header">
                <div>
                    <div class="detail-modal-kicker"><i class="ti ti-database"></i> Master Data</div>
                    <h5 class="detail-modal-title">Detail Customer / Supplier</h5>
                    <p class="detail-modal-subtitle">Informasi lengkap data pelanggan atau pemasok.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body detail-modal-body" id="detailBody">
                <div class="text-center text-muted py-4">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Memuat detail data...
                </div>
            </div>
            <div class="modal-footer detail-modal-footer">
                <button type="button" class="btn detail-close-btn" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>

(function ($) {

    'use strict';



    const URL = {

        load: '<?= base_url("customer/load_data"); ?>',

        detail: '<?= base_url("customer/detail"); ?>',

        edit: '<?= base_url("customer/edit"); ?>',

        creditLimit: '<?= base_url("customer/credit-limit"); ?>'

    };



    let state = {

        page: 1,

        limit: 10,

        order: 'CUST',

        dir: 'ASC',

        search: '',

        cust_kind: 'ALL'

    };



    let searchTimer = null;

    const modalElement = document.getElementById('modalDetail');

    let detailModal = null;



    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {

        detailModal = bootstrap.Modal.getOrCreateInstance(modalElement);

    }



    $(function () {

        loadPage(1);



        $('#search').on('input', function () {

            clearTimeout(searchTimer);

            searchTimer = setTimeout(function () {

                state.search = $('#search').val().trim();

                loadPage(1);

            }, 350);

        });



        $('#filterKind').on('change', function () {

            state.cust_kind = $(this).val();

            loadPage(1);

        });



        $('#mainTable thead').on('click', 'th[data-order]', function () {

            const col = $(this).data('order');

            if (state.order === col) {

                state.dir = state.dir === 'ASC' ? 'DESC' : 'ASC';

            } else {

                state.order = col;

                state.dir = 'ASC';

            }

            loadPage(1);

        });

    });



    window.loadPage = function (page) {

        state.page = Math.max(1, parseInt(page, 10) || 1);

        $('#table-body').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');



        $.getJSON(URL.load, state)

            .done(function (res) {

                renderTable(res.rows || []);

                $('#pagination').html(res.pagination || '');

                $('#info').text(

                    'Menampilkan ' + ((res.rows || []).length) +

                    ' dari ' + (res.total || 0) + ' data'

                );

            })

            .fail(function (xhr) {

                console.error(xhr.responseText);

                $('#table-body').html(

                    '<tr><td colspan="6" class="text-center text-danger">' +

                    'Gagal memuat data. Periksa controller dan koneksi database.' +

                    '</td></tr>'

                );

                $('#pagination, #info').empty();

            });

    };



    function renderTable(rows) {

        if (!rows.length) {

            $('#table-body').html(

                '<tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>'

            );

            return;

        }



        let html = '';

        rows.forEach(function (r) {

            const cust = String(r.CUST || '');

            const kind = String(r.CUST_KIND || '').toUpperCase();

            const status = String(r.STATUS || '').toUpperCase();

            const badgeKind = kind === 'CUSTOMER' ? 'bg-primary' : 'bg-info text-dark';

            const badgeStatus = status === 'ACTIVE' ? 'bg-success' : 'bg-secondary';

            const editUrl = URL.edit + '/' + encodeURIComponent(cust);

            const creditUrl = URL.creditLimit + '/' + encodeURIComponent(cust);



            html += `
                <tr>
                    <td style="width:12%"><span class="cust-code">#${escapeHtml(cust)}</span></td>
                    <td style="width:30%"><span class="company-name">${escapeHtml(r.FULL_NAME || '')}</span></td>
                    <td class="text-center" style="width:12%"><span class="badge ${badgeKind}">${escapeHtml(kind || '-')}</span></td>
                    <td class="text-center" style="width:14%">${escapeHtml(r.CHIEF_NAME || '-')}</td>
                    <td class="text-center" style="width:10%"><span class="badge ${badgeStatus}">${escapeHtml(status || '-')}</span></td>
                    <td class="action-cell">
                        <div class="action-group">
                            <button type="button" class="btn btn-sm btn-info"
                                    onclick="customerDetail('${escapeJs(cust)}')">Detail</button>
                            <a href="${escapeHtml(editUrl)}" class="btn btn-sm btn-warning">Edit</a>
                            ${kind === 'CUSTOMER'
                                ? `<a href="${escapeHtml(creditUrl)}" class="btn btn-sm btn-primary">Credit Limit</a>`
                                : ''}
                        </div>
                    </td>
                </tr>`;

        });



        $('#table-body').html(html);

    }



    window.customerDetail = function (cust) {

        $('#detailBody').html('Loading...');

        if (detailModal) {

            detailModal.show();

        } else if (typeof $().modal === 'function') {

            $('#modalDetail').modal('show');

        } else {

            console.error('Bootstrap Modal belum dimuat.');

            return;

        }



        $.getJSON(URL.detail, { cust: cust })

            .done(function (res) {

                if (!res.status || !res.data) {

                    $('#detailBody').html(

                        '<div class="alert alert-danger">' +

                        escapeHtml(res.message || 'Data tidak ditemukan.') + '</div>'

                    );

                    return;

                }



                const d = res.data;

                const sections = [
                    {
                        title: 'Informasi Perusahaan',
                        icon: 'ti-building',
                        fields: [
                            ['Kode Customer / Supplier', d.CUST, 'code'],
                            ['Jenis Data', d.CUST_KIND, ''],
                            ['Nama Perusahaan', d.FULL_NAME, 'wide'],
                            ['Nama Singkat', d.SHORT_NAME, ''],
                            ['Status', d.STATUS, 'status']
                        ]
                    },
                    {
                        title: 'Informasi Kontak',
                        icon: 'ti-address-book',
                        fields: [
                            ['Nama Penanggung Jawab', d.CHIEF_NAME, ''],
                            ['NPWP', d.TAX_NO, ''],
                            ['Nomor Telepon', d.TELEPHONE_NO, ''],
                            ['Nomor HP', d.MOBILE_PHONE, ''],
                            ['Alamat', d.ADDRESS2, 'wide'],
                            ['Plant', d.PLANT, '']
                        ]
                    },
                    {
                        title: 'Informasi Audit',
                        icon: 'ti-history',
                        fields: [
                            ['Didaftarkan Oleh', d.REG_EMP, ''],
                            ['Tanggal Registrasi', d.REG_DATE, ''],
                            ['Diubah Oleh', d.MOD_EMP, ''],
                            ['Tanggal Perubahan', d.MOD_DATE, '']
                        ]
                    }
                ];

                function displayValue(value) {
                    return value == null || String(value).trim() === ''
                        ? '-' : String(value);
                }

                let html = '';
                sections.forEach(function (section) {
                    html += `<section class="detail-section">
                        <h6 class="detail-section-heading">
                            <span class="detail-section-icon"><i class="ti ${section.icon}"></i></span>
                            ${escapeHtml(section.title)}
                        </h6>
                        <div class="detail-grid">`;

                    section.fields.forEach(function (field) {
                        const label = field[0];
                        const value = displayValue(field[1]);
                        const type = field[2] || '';
                        const wideClass = type === 'wide' ? ' detail-field-wide' : '';
                        let valueHtml = escapeHtml(value);

                        if (type === 'status') {
                            const active = String(value).toUpperCase() === 'ACTIVE';
                            valueHtml = `<span class="detail-status${active ? '' : ' is-inactive'}">
                                <i class="ti ${active ? 'ti-circle-check' : 'ti-circle-minus'}"></i>
                                ${escapeHtml(value)}
                            </span>`;
                        } else if (type === 'code') {
                            valueHtml = `<span class="detail-value-code">${escapeHtml(value)}</span>`;
                        }

                        html += `<div class="detail-field${wideClass}">
                            <div class="detail-label">${escapeHtml(label)}</div>
                            <div class="detail-value">${valueHtml}</div>
                        </div>`;
                    });
                    html += '</div></section>';
                });

                $('#detailBody').html(html);

            })

            .fail(function (xhr) {

                console.error(xhr.responseText);

                $('#detailBody').html(

                    '<div class="alert alert-danger">Gagal mengambil detail data.</div>'

                );

            });

    };



    function escapeHtml(value) {

        return $('<div>').text(value == null ? '' : String(value)).html();

    }



    function escapeJs(value) {
        return String(value).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    }

})(jQuery);

</script>
