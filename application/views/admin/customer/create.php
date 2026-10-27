<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .customer-create-page {
        --p: #2864f0;
        --txt: #17233b;
        --muted: #66758f;
        --border: #dce4ef;
        color: var(--txt);
        padding-bottom: 24px;
    }

    .customer-create-page .cc-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin: 4px 0 18px;
        color: var(--muted);
        font-size: 13px;
    }

    .customer-create-page .cc-breadcrumb a {
        color: var(--muted);
        text-decoration: none;
    }

    .customer-create-page .cc-breadcrumb a:hover {
        color: var(--p);
    }

    .customer-create-page .cc-hero {
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 22px 25px;
        border: 1px solid #e5ebf6;
        margin-top: 60px;
        border-radius: 16px;
        background: linear-gradient(115deg, #fff, #f8faff 55%, #edf3ff);
        box-shadow: 0 7px 24px #1b34600b;
        margin-bottom: 16px;
    }

    .customer-create-page .cc-hero-icon {
        flex: 0 0 54px;
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: var(--p);
        background: #e6eeff;
        font-size: 22px;
    }

    .customer-create-page .cc-hero h1 {
        margin: 0 0 5px;
        font-size: 23px;
        font-weight: 750;
        letter-spacing: -.45px;
    }

    .customer-create-page .cc-hero p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .customer-create-page .cc-card {
        background: #fff;
        border: 1px solid #e5ebf4;
        border-radius: 16px;
        box-shadow: 0 9px 28px #1b34600e;
        overflow: hidden;
    }

    .customer-create-page .cc-card-body {
        padding: 24px 25px 19px;
    }

    .customer-create-page .cc-section {
        margin-bottom: 22px;
    }

    .customer-create-page .cc-section-head {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 19px;
    }

    .customer-create-page .cc-step {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border-radius: 50%;
        background: var(--p);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 10px #2864f033;
    }

    .customer-create-page .cc-section-head h2 {
        font-size: 16px;
        font-weight: 750;
        margin: 1px 0 4px;
    }

    .customer-create-page .cc-section-head p {
        margin: 0;
        color: var(--muted);
        font-size: 12.5px;
    }

    .customer-create-page .cc-info {
        margin-left: auto;
        max-width: 360px;
        display: flex;
        gap: 9px;
        align-items: flex-start;
        padding: 10px 12px;
        border-radius: 10px;
        color: #426087;
        background: #f0f6ff;
        font-size: 12px;
        line-height: 1.5;
    }

    .customer-create-page .cc-info i {
        color: #3887e8;
        margin-top: 2px;
    }

    .customer-create-page .cc-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px 22px;
    }

    .customer-create-page .cc-grid-three {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px 22px;
    }

    .customer-create-page .cc-field {
        min-width: 0;
    }

    .customer-create-page .cc-field label {
        display: block;
        margin: 0 0 7px;
        font-size: 12.5px;
        font-weight: 650;
        color: #26364f;
    }

    .customer-create-page .cc-required {
        color: #e34b5f;
    }

    .customer-create-page .cc-input-wrap {
        display: flex;
        min-width: 0;
    }

    .customer-create-page .cc-input-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 41px;
        flex: 0 0 41px;
        color: #5f708a;
        background: #f8faff;
        border: 1px solid var(--border);
        border-right: 0;
        border-radius: 8px 0 0 8px;
    }

    .customer-create-page .cc-input-wrap .form-control {
        min-width: 0;
        height: 41px;
        border: 1px solid var(--border);
        border-radius: 0 8px 8px 0;
        box-shadow: none;
        color: var(--txt);
        font-size: 13px;
        padding: 9px 12px;
        transition: border-color .15s, box-shadow .15s;
    }

    .customer-create-page .cc-field > .form-control {
        height: 41px;
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: none;
        color: var(--txt);
        font-size: 13px;
        padding: 9px 12px;
    }

    .customer-create-page .cc-input-wrap .form-control:focus,
    .customer-create-page .cc-field > .form-control:focus {
        border-color: #8aafff;
        box-shadow: 0 0 0 3px #2864f017;
        outline: none;
    }

    .customer-create-page .cc-input-wrap:focus-within .cc-input-icon {
        border-color: #8aafff;
        color: var(--p);
    }

    .customer-create-page .cc-code-wrap .form-control {
        background: #f5f7fb;
        color: #586b89;
        border-radius: 0;
    }

    .customer-create-page .cc-code-copy {
        width: 43px;
        flex: 0 0 43px;
        border: 1px solid var(--border);
        border-left: 0;
        border-radius: 0 8px 8px 0;
        background: #f2f5fb;
        color: #58708f;
    }

    .customer-create-page .cc-code-copy:hover {
        color: var(--p);
        background: #eaf0ff;
    }

    .customer-create-page .cc-help {
        display: block;
        margin-top: 5px;
        font-size: 11.5px;
        line-height: 1.4;
        color: var(--muted);
    }

    .customer-create-page .cc-help i {
        color: #3990e8;
        margin-right: 4px;
    }

    .customer-create-page .cc-status-active {
        background: #f2fbf5 !important;
        border-color: #a6dfb8 !important;
    }

    .customer-create-page .cc-status-inactive {
        background: #fff8f0 !important;
        border-color: #f1d0a6 !important;
    }

    .customer-create-page .cc-divider {
        border: 0;
        border-top: 1px solid #e8edf5;
        margin: 22px 0 20px;
    }

    .customer-create-page .cc-address-count {
        text-align: right;
        color: #8491a6;
        font-size: 11.5px;
        margin-top: 4px;
    }

    .customer-create-page .cc-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 25px;
        background: #fbfcff;
        border-top: 1px solid #edf1f7;
    }

    .customer-create-page .cc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 41px;
        padding: 10px 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 650;
        transition: all .15s;
        text-decoration: none;
    }

    .customer-create-page .cc-btn-back {
        color: #40516c;
        border: 1px solid #cfd9e8;
        background: #fff;
    }

    .customer-create-page .cc-btn-back:hover {
        color: var(--p);
        border-color: #9eb8f8;
        background: #f8faff;
    }

    .customer-create-page .cc-btn-save {
        color: #fff;
        border: 1px solid var(--p);
        background: var(--p);
        box-shadow: 0 5px 12px #2864f02e;
    }

    .customer-create-page .cc-btn-save:hover {
        background: #1e4fd0;
        border-color: #1e4fd0;
        color: #fff;
    }

    .customer-create-page .cc-btn-save:disabled {
        opacity: .7;
        cursor: wait;
    }

    .customer-create-page .cc-alert {
        border-radius: 9px;
        font-size: 13px;
    }

    @media (max-width: 991px) {
        .customer-create-page .cc-grid-three {
            grid-template-columns: 1fr 1fr;
        }

        .customer-create-page .cc-info {
            max-width: 270px;
        }
    }

    @media (max-width: 767px) {
        .customer-create-page .cc-hero {
            padding: 18px;
        }

        .customer-create-page .cc-hero h1 {
            font-size: 19px;
        }

        .customer-create-page .cc-card-body {
            padding: 19px 16px;
        }

        .customer-create-page .cc-grid,
        .customer-create-page .cc-grid-three {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .customer-create-page .cc-section-head {
            flex-wrap: wrap;
        }

        .customer-create-page .cc-info {
            margin-left: 46px;
            max-width: none;
            width: 100%;
        }

        .customer-create-page .cc-footer {
            padding: 13px 16px;
        }
    }

    /* Dropdown indicator: keep a visible chevron for native selects */
    .customer-create-page .cc-select-wrap {
        position: relative;
    }

    .customer-create-page .cc-select-wrap select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 38px !important;
        cursor: pointer;
        background-image: none !important;
    }

    .customer-create-page .cc-select-wrap::after {
        content: "";
        position: absolute;
        right: 15px;
        top: 50%;
        width: 8px;
        height: 8px;
        border-right: 2px solid #64748b;
        border-bottom: 2px solid #64748b;
        transform: translateY(-70%) rotate(45deg);
        pointer-events: none;
    }

    .customer-create-page .cc-select-wrap:focus-within::after {
        border-color: #2864f0;
    }
</style>

<div class="content-wrapper customer-create-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <nav class="cc-breadcrumb" aria-label="breadcrumb">
                <a href="<?= base_url(); ?>"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <span>Master Data</span>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <a href="<?= base_url('customer'); ?>">Customer / Supplier</a>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <strong>Tambah Data</strong>
            </nav>

            <div class="cc-hero">
                <div class="cc-hero-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h1>Tambah Customer / Supplier</h1>
                    <p>Registrasi data Customer atau Supplier baru ke dalam sistem.</p>
                </div>
            </div>

            <div class="cc-card">
                <form id="formCustomerCreate" autocomplete="off" novalidate>
                    <div class="cc-card-body">
                        <div id="formAlert" class="alert cc-alert d-none" role="alert"></div>

                        <section class="cc-section">
                            <div class="cc-section-head">
                                <span class="cc-step">1</span>
                                <div>
                                    <h2>Informasi Utama</h2>
                                    <p>Lengkapi jenis registrasi, kode, dan status data.</p>
                                </div>
                                <div class="cc-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Kode Customer diawali CUS, Supplier diawali SUP. Kode final dibuat otomatis oleh sistem saat data disimpan.</span>
                                </div>
                            </div>

                            <div class="cc-grid cc-grid-three">
                                <div class="cc-field">
                                    <label for="cust_kind">Jenis Registrasi <span class="cc-required">*</span></label>
                                    <div class="cc-input-wrap cc-select-wrap">
                                        <span class="cc-input-icon">
                                            <i class="fas fa-user-friends"></i>
                                        </span>
                                        <select name="cust_kind" id="cust_kind" class="form-control" required>
                                            <option value="CUSTOMER">Customer</option>
                                            <option value="SUPPLIER">Supplier</option>
                                        </select>
                                    </div>
                                    <small class="cc-help">Pilih tipe master data yang akan dibuat.</small>
                                </div>

                                <div class="cc-field">
                                    <label for="cust_preview">Kode (Auto Generate)</label>
                                    <div class="cc-input-wrap cc-code-wrap">
                                        <span class="cc-input-icon">
                                            <i class="fas fa-fingerprint"></i>
                                        </span>
                                        <input type="text" id="cust_preview" class="form-control" value="Loading..." readonly>
                                        <button type="button" class="cc-code-copy" id="btnCopyCode" title="Salin kode preview" aria-label="Salin kode preview">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                    <small class="cc-help">
                                        <i class="fas fa-info-circle"></i>Kode ini adalah preview. Kode final dibuat saat data disimpan.
                                    </small>
                                </div>

                                <div class="cc-field">
                                    <label for="status">Status <span class="cc-required">*</span></label>
                                    <div class="cc-input-wrap cc-select-wrap">
                                        <span class="cc-input-icon">
                                            <i class="fas fa-check-circle" id="statusIcon"></i>
                                        </span>
                                        <select name="status" id="status" class="form-control cc-status-active" required>
                                            <option value="ACTIVE" selected>Active</option>
                                            <option value="INACTIVE">Inactive</option>
                                        </select>
                                    </div>
                                    <small class="cc-help">Menentukan apakah data dapat digunakan.</small>
                                </div>
                            </div>
                        </section>

                        <hr class="cc-divider">

                        <section class="cc-section mb-0">
                            <div class="cc-section-head">
                                <span class="cc-step">2</span>
                                <div>
                                    <h2>Informasi Perusahaan</h2>
                                    <p>Lengkapi data perusahaan dan penanggung jawab.</p>
                                </div>
                            </div>

                            <div class="cc-grid">
                                <div class="cc-field">
                                    <label for="full_name">Nama Perusahaan <span class="cc-required">*</span></label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon">
                                            <i class="far fa-building"></i>
                                        </span>
                                        <input type="text" name="full_name" id="full_name" class="form-control" maxlength="255" placeholder="Masukkan nama perusahaan" required>
                                    </div>
                                </div>

                                <div class="cc-field">
                                    <label for="short_name">Nama Singkat</label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon">
                                            <i class="far fa-address-card"></i>
                                        </span>
                                        <input type="text" name="short_name" id="short_name" class="form-control" maxlength="255" placeholder="Nama singkat / alias perusahaan">
                                    </div>
                                </div>

                                <div class="cc-field">
                                    <label for="chief_name">Penanggung Jawab (PIC)</label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon">
                                            <i class="far fa-user"></i>
                                        </span>
                                        <input type="text" name="chief_name" id="chief_name" class="form-control" maxlength="255" placeholder="Nama PIC / Contact Person">
                                    </div>
                                </div>

                                <div class="cc-field">
                                    <label for="tax_no">Nomor Pajak (NPWP)</label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon">
                                            <i class="far fa-file-alt"></i>
                                        </span>
                                        <input type="text" name="tax_no" id="tax_no" class="form-control" maxlength="255" placeholder="Masukkan nomor pajak">
                                    </div>
                                </div>

                                <div class="cc-field">
                                    <label for="telephone_no">Telepon</label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </span>
                                        <input type="text" name="telephone_no" id="telephone_no" class="form-control" maxlength="255" placeholder="Contoh: 021-1234567">
                                    </div>
                                </div>

                                <div class="cc-field">
                                    <label for="mobile_phone">Nomor HP</label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </span>
                                        <input type="text" name="mobile_phone" id="mobile_phone" class="form-control" maxlength="255" placeholder="Contoh: 08123456789">
                                    </div>
                                </div>

                                <div class="cc-field" style="grid-column:1/-1">
                                    <label for="address2">Alamat</label>
                                    <div class="cc-input-wrap">
                                        <span class="cc-input-icon" style="align-items:flex-start;padding-top:13px">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <textarea name="address2" id="address2" class="form-control" rows="3" maxlength="500" placeholder="Masukkan alamat lengkap perusahaan" style="height:auto;min-height:78px;border-radius:0 8px 8px 0"></textarea>
                                    </div>
                                    <div class="cc-address-count">
                                        <span id="addressCount">0</span> / 500
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="cc-footer">
                        <a href="<?= base_url('customer'); ?>" class="cc-btn cc-btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" id="btnSaveCustomer" class="cc-btn cc-btn-save">
                            <i class="far fa-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
    (function ($) {
        'use strict';

        var previewUrl = <?= json_encode(base_url('customer/preview_code')); ?>;
        var saveUrl = <?= json_encode(base_url('customer/create')); ?>;
        var listUrl = <?= json_encode(base_url('customer')); ?>;

        function showAlert(type, message) {
            $('#formAlert')
                .removeClass('d-none alert-success alert-danger alert-warning alert-info')
                .addClass('alert-' + type)
                .text(message);

            $('html,body').animate({
                scrollTop: $('#formAlert').offset().top - 100
            }, 180);
        }

        function hideAlert() {
            $('#formAlert').addClass('d-none').text('');
        }

        function loadCodePreview() {
            var kind = $('#cust_kind').val();
            $('#cust_preview').val('Memuat...');

            $.ajax({
                url: previewUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    cust_kind: kind
                }
            }).done(function (r) {
                var code = '';

                if (r && r.code) {
                    code = r.code;
                } else if (r && r.data && r.data.code) {
                    code = r.data.code;
                } else if (r && r.data && typeof r.data === 'string') {
                    code = r.data;
                } else if (r && r.preview) {
                    code = r.preview;
                }

                $('#cust_preview').val(code || (kind === 'SUPPLIER' ? 'SUP000001' : 'CUS000001'));
            }).fail(function () {
                $('#cust_preview').val(kind === 'SUPPLIER' ? 'SUP000001' : 'CUS000001');
            });
        }

        $('#cust_kind').on('change', function () {
            hideAlert();
            loadCodePreview();
        });

        $('#status').on('change', function () {
            var isActive = $(this).val() === 'ACTIVE';

            $(this)
                .toggleClass('cc-status-active', isActive)
                .toggleClass('cc-status-inactive', !isActive);

            $('#statusIcon')
                .toggleClass('fa-check-circle', isActive)
                .toggleClass('fa-exclamation-circle', !isActive);
        });

        $('#address2').on('input', function () {
            $('#addressCount').text($(this).val().length);
        });

        $('#btnCopyCode').on('click', function () {
            var value = $('#cust_preview').val();

            if (!value || value === 'Memuat...') {
                return;
            }

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(value).then(function () {
                    var icon = $('#btnCopyCode i');

                    icon.removeClass('far fa-copy').addClass('fas fa-check');
                    setTimeout(function () {
                        icon.removeClass('fas fa-check').addClass('far fa-copy');
                    }, 1000);
                });
            } else {
                $('#cust_preview').trigger('focus').trigger('select');

                try {
                    document.execCommand('copy');
                } catch (e) {
                }
            }
        });

        $('#formCustomerCreate').on('submit', function (e) {
            e.preventDefault();
            hideAlert();

            if (!$.trim($('#full_name').val())) {
                showAlert('danger', 'Nama Perusahaan wajib diisi.');
                $('#full_name').trigger('focus');
                return;
            }

            var button = $('#btnSaveCustomer');
            var originalButtonContent = button.html();

            button
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: saveUrl,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json'
            }).done(function (r) {
                if (r && (r.success === true || r.status === 'success')) {
                    var code = r.code || r.cust || (r.data && r.data.CUST) || '';
                    var successMessage = r.message || 'Data berhasil disimpan.';

                    if (code) {
                        successMessage += ' Kode: ' + code;
                    }

                    showAlert('success', successMessage);
                    setTimeout(function () {
                        window.location.href = listUrl;
                    }, 900);
                    return;
                }

                var errorMessage = r && r.message ? r.message : 'Data gagal disimpan.';

                if (r && r.errors) {
                    if (typeof r.errors === 'string') {
                        errorMessage += ' ' + r.errors;
                    } else {
                        errorMessage += ' ' + Object.values(r.errors).join(' ');
                    }
                }

                showAlert('danger', errorMessage);
                button.prop('disabled', false).html(originalButtonContent);
            }).fail(function (xhr) {
                var errorMessage = 'Terjadi kesalahan saat menyimpan data.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);

                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                    }
                }

                showAlert('danger', errorMessage);
                button.prop('disabled', false).html(originalButtonContent);
            });
        });

        $(function () {
            loadCodePreview();
        });
    })(jQuery);
</script>
