
<style>
.master-sales-page {
    color: #243247;
}

.master-sales-page .page-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 22px;
    margin-top: 90px;
}

.master-sales-page .eyebrow {
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

.master-sales-page .page-title {
    margin: 0;
    font-size: 1.55rem;
    font-weight: 750;
    letter-spacing: -.035em;
    color: #172b4d;
}

.master-sales-page .page-subtitle {
    margin: 7px 0 0;
    color: #718096;
    font-size: .92rem;
}

.master-sales-page .panel {
    border: 1px solid #e7edf5;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(22,42,75,.055);
    overflow: hidden;
}

.master-sales-page .panel-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    padding: 20px 22px;
    border-bottom: 1px solid #edf1f7;
    background: linear-gradient(180deg,#fff 0%,#fbfcff 100%);
}

.master-sales-page .toolbar-left {
    display: flex;
    gap: 12px;
    flex: 1;
    min-width: min(100%,420px);
    flex-wrap: wrap;
}

.master-sales-page .search-wrap {
    position: relative;
    flex: 1;
    min-width: 220px;
}

.master-sales-page .search-wrap .search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

.master-sales-page .form-control {
    min-height: 44px;
    border: 1px solid #dfe6f0;
    border-radius: 10px;
    box-shadow: none;
    color: #334155;
    background-color: #fff;
}

.master-sales-page .search-wrap .form-control {
    padding-left: 40px;
}

.master-sales-page .form-control:focus {
    border-color: #8aa4ff;
    box-shadow: 0 0 0 3px rgba(99,126,255,.12);
}

.master-sales-page .btn-add {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 17px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(135deg,#627dff,#4965e8);
    color: #fff;
    font-weight: 650;
    box-shadow: 0 5px 12px rgba(79,103,232,.2);
    text-decoration: none;
    transition: transform .15s,box-shadow .15s;
}

.master-sales-page .btn-add:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(79,103,232,.25);
}

.master-sales-page .table-area {
    padding: 10px 12px 0;
}

.master-sales-page #mainTable {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.master-sales-page #mainTable thead th {
    padding: 15px 14px;
    background: #f7f9fc;
    color: #66758b;
    font-size: .73rem;
    text-transform: uppercase;
    letter-spacing: .055em;
    font-weight: 750;
    white-space: nowrap;
    border-top: 0;
    border-bottom: 1px solid #e8edf4;
}

.master-sales-page #mainTable thead th:first-child {
    border-radius: 9px 0 0 0;
}

.master-sales-page #mainTable thead th:last-child {
    border-radius: 0 9px 0 0;
}

.master-sales-page #mainTable th[data-order] {
    cursor: pointer;
    user-select: none;
}

.master-sales-page #mainTable th[data-order]:hover {
    color: #4e68e8;
}

.master-sales-page #mainTable tbody td {
    padding: 16px 14px;
    border-bottom: 1px solid #edf1f6;
    color: #43536a;
    vertical-align: middle;
    font-size: .88rem;
}

.master-sales-page #mainTable tbody tr:hover {
    background: #f8faff;
}

.master-sales-page #mainTable tbody tr:last-child td {
    border-bottom: 0;
}

.master-sales-page .sales-code {
    font-weight: 700;
    color: #3d58d8;
    white-space: nowrap;
}

.master-sales-page .sales-name {
    color: #243247;
    font-weight: 650;
    min-width: 180px;
}

.master-sales-page .sales-contact {
    color: #64748b;
    min-width: 130px;
}

.master-sales-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: .72rem;
    line-height: 1;
    font-weight: 750;
    letter-spacing: .025em;
}

.master-sales-page .status-active {
    color: #087f5b;
    background: #e4f8ef;
}

.master-sales-page .status-inactive {
    color: #64748b;
    background: #eef2f6;
}

.master-sales-page .actions {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 7px;
}

.master-sales-page .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    border: 1px solid transparent;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: .78rem;
    font-weight: 650;
    line-height: 1.25;
    text-decoration: none;
    white-space: nowrap;
    transition: all .15s;
}

.master-sales-page .btn-detail {
    color: #4261dc;
    background: #eef2ff;
    border-color: #e0e7ff;
}

.master-sales-page .btn-detail:hover {
    color: #304bc0;
    background: #e1e8ff;
}

.master-sales-page .btn-edit {
    color: #9a5a00;
    background: #fff5df;
    border-color: #ffebbd;
}

.master-sales-page .btn-edit:hover {
    color: #7a4600;
    background: #ffedc4;
}

.master-sales-page .panel-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 22px 20px;
    color: #7b8798;
    font-size: .84rem;
}

.master-sales-page #pagination ul {
    margin: 0;
}

.master-sales-page #pagination .pagination {
    gap: 5px;
}

.master-sales-page #pagination .page-link {
    border: 1px solid #e2e8f0;
    border-radius: 8px !important;
    color: #58677c;
    padding: 7px 11px;
    box-shadow: none;
    cursor: pointer;
}

.master-sales-page #pagination .page-item.active .page-link {
    background: #536de9;
    border-color: #536de9;
    color: #fff;
}

.master-sales-page #pagination .page-item.disabled .page-link {
    color: #aab4c2;
    cursor: not-allowed;
}

.master-sales-page .empty-state {
    padding: 38px 16px !important;
    text-align: center;
    color: #8491a4 !important;
}

.master-sales-page .detail-modal-dialog {
    max-width: 800px;
}

.master-sales-page .detail-modal-content {
    border: 1px solid #e8edf5;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 24px 70px rgba(20,35,70,.22);
}

.master-sales-page .detail-modal-header {
    padding: 22px 26px;
    background: linear-gradient(135deg,#fff 0%,#f7f9ff 100%);
    border-bottom: 1px solid #edf1f7;
}

.master-sales-page .detail-modal-kicker {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 6px;
    color: #64748b;
    font-size: .72rem;
    font-weight: 750;
    letter-spacing: .09em;
    text-transform: uppercase;
}

.master-sales-page .detail-modal-title {
    margin: 0;
    color: #172b4d;
    font-size: 1.22rem;
    font-weight: 750;
    letter-spacing: -.025em;
}

.master-sales-page .detail-modal-subtitle {
    margin: 5px 0 0;
    color: #8491a4;
    font-size: .86rem;
}

.master-sales-page .detail-modal-body {
    padding: 24px 26px;
    background: #fff;
}

.master-sales-page .detail-section {
    margin-bottom: 20px;
    padding: 17px;
    border: 1px solid #e9eef6;
    border-radius: 13px;
    background: #fff;
}

.master-sales-page .detail-section:last-child {
    margin-bottom: 0;
}

.master-sales-page .detail-section-heading {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0 0 14px;
    color: #263b5b;
    font-size: .9rem;
    font-weight: 750;
}

.master-sales-page .detail-section-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 9px;
    color: #526bea;
    background: #eef1ff;
    font-size: 1rem;
}

.master-sales-page .detail-grid {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 12px;
}

.master-sales-page .detail-field {
    min-width: 0;
    padding: 12px 13px;
    border: 1px solid #edf1f6;
    border-radius: 10px;
    background: #f9fbfe;
}

.master-sales-page .detail-field-wide {
    grid-column: 1 / -1;
}

.master-sales-page .detail-label {
    margin-bottom: 5px;
    color: #8793a6;
    font-size: .71rem;
    font-weight: 750;
    letter-spacing: .055em;
    text-transform: uppercase;
}

.master-sales-page .detail-value {
    color: #2d3e58;
    font-size: .9rem;
    font-weight: 550;
    overflow-wrap: anywhere;
}

.master-sales-page .detail-value-code {
    color: #405bd2;
    font-weight: 800;
    letter-spacing: .02em;
}

.master-sales-page .detail-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 999px;
    background: #e4f8ef;
    color: #087f5b;
    font-size: .74rem;
    font-weight: 750;
}

.master-sales-page .detail-status.is-inactive {
    background: #eef2f6;
    color: #64748b;
}

.master-sales-page .detail-modal-footer {
    padding: 15px 26px;
    border-top: 1px solid #edf1f7;
    background: #fbfcff;
}

.master-sales-page .detail-close-btn {
    padding: 9px 17px;
    border: 1px solid #dfe6f0;
    border-radius: 9px;
    color: #46566e;
    background: #fff;
    font-size: .84rem;
    font-weight: 650;
}

.master-sales-page .detail-close-btn:hover {
    background: #f2f5fa;
}

@media (max-width: 767px) {
    .master-sales-page .page-title {
        font-size: 1.3rem;
    }

    .master-sales-page .panel-toolbar {
        padding: 16px;
    }

    .master-sales-page .toolbar-left {
        min-width: 100%;
    }

    .master-sales-page .search-wrap {
        min-width: 100%;
    }

    .master-sales-page .btn-add {
        width: 100%;
    }

    .master-sales-page .table-area {
        padding: 0 8px;
    }

    .master-sales-page .panel-footer {
        padding: 14px 16px;
    }
}

@media (max-width: 575px) {
    .master-sales-page .detail-modal-header,
    .master-sales-page .detail-modal-body {
        padding: 18px;
    }

    .master-sales-page .detail-modal-footer {
        padding: 13px 18px;
    }

    .master-sales-page .detail-grid {
        grid-template-columns: 1fr;
    }

    .master-sales-page .detail-field-wide {
        grid-column: auto;
    }

    .master-sales-page .detail-section {
        padding: 13px;
    }
}
</style>

<div class="container-fluid master-sales-page px-2 px-lg-3 py-2">

    <div class="page-heading">
        <div>
            <div class="eyebrow">
                <i class="ti ti-database"></i> Master Data
            </div>

            <h1 class="page-title">Master Sales</h1>

            <p class="page-subtitle">
                Kelola data Sales yang digunakan dalam Customer Order.
            </p>
        </div>
    </div>

    <section class="panel">

        <div class="panel-toolbar">

            <div class="toolbar-left">
                <div class="search-wrap">
                    <i class="ti ti-search search-icon"></i>

                    <input
                        type="text"
                        id="search"
                        class="form-control"
                        placeholder="Cari Sales Code, nama, phone, atau email..."
                        aria-label="Cari Sales"
                    >
                </div>
            </div>

            <a href="<?= base_url('master_sales/create'); ?>" class="btn-add">
                <i class="ti ti-plus"></i>
                Tambah Sales
            </a>

        </div>

        <div class="table-area">
            <div class="table-responsive">

                <table class="table align-middle" id="mainTable">

                    <thead>
                        <tr>
                            <th data-order="SALES">Sales Code</th>
                            <th data-order="FULL_NAME">Full Name</th>
                            <th data-order="PHONE">Phone</th>
                            <th data-order="EMAIL">Email</th>
                            <th class="text-center" data-order="STATUS">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="table-body">
                        <tr>
                            <td colspan="6" class="empty-state">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Memuat data...
                            </td>
                        </tr>
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
<div class="modal fade master-sales-page" id="modalDetail" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable detail-modal-dialog">

        <div class="modal-content detail-modal-content">

            <div class="modal-header detail-modal-header">

                <div>
                    <div class="detail-modal-kicker">
                        <i class="ti ti-database"></i> Master Data
                    </div>

                    <h5 class="detail-modal-title">Detail Sales</h5>

                    <p class="detail-modal-subtitle">
                        Informasi lengkap Sales.
                    </p>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                </button>

            </div>

            <div class="modal-body detail-modal-body" id="detailBody">
                <div class="text-center text-muted py-4">
                    <span class="spinner-border spinner-border-sm me-2"></span>
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
        load: '<?= base_url("master_sales/load_data"); ?>',
        detail: '<?= base_url("master_sales/detail"); ?>',
        edit: '<?= base_url("master_sales/edit"); ?>'
    };

    let state = {
        page: 1,
        limit: 10,
        order: 'SALES',
        dir: 'ASC',
        search: ''
    };

    let searchTimer = null;
    let detailModal = null;

    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('modalDetail');

        if (window.bootstrap && window.bootstrap.Modal && modalElement) {
            detailModal = new bootstrap.Modal(modalElement);
            console.log('Master Sales Detail Modal initialized.');
        } else {
            console.error('Modal initialization failed:', {
                bootstrap: window.bootstrap,
                modalElement: modalElement
            });
        }
    });

    $(function () {
        loadPage(1);

        $('#search').on('input', function () {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(function () {
                state.search = $('#search').val().trim();
                loadPage(1);
            }, 350);
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

        $('#table-body').html(
            '<tr><td colspan="6" class="empty-state">' +
            '<span class="spinner-border spinner-border-sm me-2"></span>' +
            'Memuat data...</td></tr>'
        );

        $.getJSON(URL.load, state)
            .done(function (res) {
                renderTable(res.rows || []);
                renderPagination(res.page || 1, res.pages || 1);

                const rows = res.rows || [];
                const total = parseInt(res.total, 10) || 0;

                $('#info').text(
                    total > 0
                        ? 'Menampilkan ' +
                          (((res.page - 1) * res.limit) + 1) +
                          ' - ' +
                          Math.min(res.page * res.limit, total) +
                          ' dari ' + total + ' data'
                        : 'Tidak ada data'
                );
            })
            .fail(function (xhr) {
                console.error(xhr.responseText);

                $('#table-body').html(
                    '<tr><td colspan="6" class="text-center text-danger py-4">' +
                    'Gagal memuat data. Periksa controller dan koneksi database.' +
                    '</td></tr>'
                );

                $('#pagination, #info').empty();
            });
    };

    function renderTable(rows) {
        if (!rows.length) {
            $('#table-body').html(
                '<tr><td colspan="6" class="empty-state">' +
                'Tidak ada data Sales.</td></tr>'
            );
            return;
        }

        let html = '';

        rows.forEach(function (r) {
            const sales = String(r.SALES || '');
            const name = String(r.FULL_NAME || '');
            const phone = String(r.PHONE || '');
            const email = String(r.EMAIL || '');
            const status = String(r.STATUS || '').toUpperCase();

            const statusClass = status === 'ACTIVE'
                ? 'status-active'
                : 'status-inactive';

            const editUrl = URL.edit + '/' + encodeURIComponent(sales);

            html += `
                <tr>
                    <td>
                        <span class="sales-code">#${escapeHtml(sales)}</span>
                    </td>

                    <td>
                        <div class="sales-name">${escapeHtml(name)}</div>
                    </td>

                    <td>
                        <span class="sales-contact">
                            ${phone ? escapeHtml(phone) : '-'}
                        </span>
                    </td>

                    <td>
                        <span class="sales-contact">
                            ${email ? escapeHtml(email) : '-'}
                        </span>
                    </td>

                    <td class="text-center">
                        <span class="status-badge ${statusClass}">
                            <i class="ti ti-circle-filled"></i>
                            ${escapeHtml(status || '-')}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="actions">
                            <button
                                type="button"
                                class="btn-action btn-detail"
                                data-sales="${escapeHtml(sales)}">
                                <i class="ti ti-eye"></i>
                                Detail
                            </button>

                            <a
                                href="${escapeHtml(editUrl)}"
                                class="btn-action btn-edit">
                                <i class="ti ti-edit"></i>
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });

        $('#table-body').html(html);
    }

    function renderPagination(currentPage, totalPages) {
        currentPage = parseInt(currentPage, 10) || 1;
        totalPages = parseInt(totalPages, 10) || 1;

        if (totalPages <= 1) {
            $('#pagination').empty();
            return;
        }

        let html = '<ul class="pagination pagination-sm">';

        html += `
            <li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">
                    <i class="ti ti-chevron-left"></i>
                </a>
            </li>
        `;

        let start = Math.max(1, currentPage - 2);
        let end = Math.min(totalPages, currentPage + 2);

        if (currentPage <= 3) {
            end = Math.min(5, totalPages);
        }

        if (currentPage >= totalPages - 2) {
            start = Math.max(1, totalPages - 4);
        }

        for (let i = start; i <= end; i++) {
            html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">
                        ${i}
                    </a>
                </li>
            `;
        }

        html += `
            <li class="page-item ${currentPage >= totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">
                    <i class="ti ti-chevron-right"></i>
                </a>
            </li>
        `;

        html += '</ul>';

        $('#pagination').html(html);
    }

    $('#pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        const page = parseInt($(this).data('page'), 10);

        if (!page || page < 1) {
            return;
        }

        loadPage(page);
    });

    // Detail button
    
    // Detail button
    $('#table-body').on('click', '.btn-detail', function () {
        const sales = $(this).attr('data-sales');

        console.log('Sales Code:', sales);

        if (!sales) {
            console.error('Sales Code tidak ditemukan.');
            return;
        }

        // Initialize Bootstrap Modal saat dibutuhkan
        const modalElement = document.getElementById('modalDetail');

        if (!window.bootstrap || !window.bootstrap.Modal || !modalElement) {
            console.error('Bootstrap Modal atau elemen modal tidak tersedia.');
            return;
        }

        const detailModal = bootstrap.Modal.getOrCreateInstance(modalElement);

        // Loading state
        $('#detailBody').html(`
            <div class="text-center text-muted py-4">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Memuat detail data...
            </div>
        `);

        // Tampilkan modal
        detailModal.show();

        // Request detail
        $.ajax({
            url: URL.detail,
            type: 'GET',
            data: {
                sales: sales
            },
            dataType: 'json',

            success: function (res) {
                console.log('AJAX SUCCESS:', res);

                if (res.status && res.data) {
                    renderDetail(res.data);
                } else {
                    showDetailError(res.message || 'Data tidak ditemukan.');
                }
            },

            error: function (xhr, status, error) {
                console.error('AJAX ERROR:', {
                    status: xhr.status,
                    textStatus: status,
                    error: error,
                    response: xhr.responseText
                });

                showDetailError('Gagal mengambil detail Sales.');
            }
        });
    });

    function renderDetail(r) {
        const status = String(r.STATUS || '').toUpperCase();
        const isActive = status === 'ACTIVE';
        const createdBy = r.REG_EMP_NAME || '-';
        const modifiedBy = r.MOD_EMP_NAME || '-';

        const statusHtml = `
            <span class="detail-status ${isActive ? '' : 'is-inactive'}">
                <i class="ti ti-circle-filled"></i>
                ${escapeHtml(status || '-')}
            </span>
        `;

        const html = `
            <div class="detail-section">

                <h6 class="detail-section-heading">
                    <span class="detail-section-icon">
                        <i class="ti ti-user"></i>
                    </span>
                    Sales Information
                </h6>

                <div class="detail-grid">

                    <div class="detail-field">
                        <div class="detail-label">Sales Code</div>
                        <div class="detail-value detail-value-code">
                            #${escapeHtml(r.SALES || '-')}
                        </div>
                    </div>

                    <div class="detail-field">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">${statusHtml}</div>
                    </div>

                    <div class="detail-field detail-field-wide">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">
                            ${escapeHtml(r.FULL_NAME || '-')}
                        </div>
                    </div>

                    <div class="detail-field">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value">
                            ${escapeHtml(r.PHONE || '-')}
                        </div>
                    </div>

                    <div class="detail-field">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">
                            ${escapeHtml(r.EMAIL || '-')}
                        </div>
                    </div>

                </div>
            </div>

            <div class="detail-section">

                <h6 class="detail-section-heading">
                    <span class="detail-section-icon">
                        <i class="ti ti-history"></i>
                    </span>
                    Audit Information
                </h6>

                <div class="detail-grid">

                    <div class="detail-field">
                        <div class="detail-label">Created By</div>
                        <div class="detail-value">
                            ${escapeHtml(createdBy)}
                        </div>
                    </div>

                    <div class="detail-field">
                        <div class="detail-label">Created Date</div>
                        <div class="detail-value">
                            ${escapeHtml(r.REG_DATE || '-')}
                        </div>
                    </div>

                    <div class="detail-field">
                        <div class="detail-label">Modified By</div>
                        <div class="detail-value">
                            ${escapeHtml(modifiedBy)}
                        </div>
                    </div>

                    <div class="detail-field">
                        <div class="detail-label">Modified Date</div>
                        <div class="detail-value">
                            ${escapeHtml(r.MOD_DATE || '-')}
                        </div>
                    </div>

                </div>
            </div>
        `;

        $('#detailBody').html(html);
    }

    function showDetailError(message) {
        $('#detailBody').html(`
            <div class="text-center text-danger py-4">
                <i class="ti ti-alert-circle fs-3"></i>
                <div class="mt-2">${escapeHtml(message)}</div>
            </div>
        `);
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

})(jQuery);
</script>
