<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$row = isset($row) && is_array($row) ? $row : array();
$cust = isset($row['CUST']) ? $row['CUST'] : '';
$custKind = isset($row['CUST_KIND']) ? strtoupper($row['CUST_KIND']) : 'CUSTOMER';
$status = isset($row['STATUS']) ? strtoupper($row['STATUS']) : 'ACTIVE';
?>
<style>
    .customer-edit-page {
        --p: #2864f0;
        --txt: #17233b;
        --muted: #66758f;
        --border: #dce4ef;
        color: var(--txt);
        padding-bottom: 24px;
    }

    .customer-edit-page .cc-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin: 4px 0 18px;
        color: var(--muted);
        font-size: 13px;
    }

    .customer-edit-page .cc-breadcrumb a {
        color: var(--muted);
        text-decoration: none;
    }

    .customer-edit-page .cc-breadcrumb a:hover {
        color: var(--p);
    }

    .customer-edit-page .cc-hero {
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

    .customer-edit-page .cc-hero-icon {
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

    .customer-edit-page .cc-hero h1 {
        margin: 0 0 5px;
        font-size: 23px;
        font-weight: 750;
        letter-spacing: -.45px;
    }

    .customer-edit-page .cc-hero p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .customer-edit-page .cc-card {
        background: #fff;
        border: 1px solid #e5ebf4;
        border-radius: 16px;
        box-shadow: 0 9px 28px #1b34600e;
        overflow: hidden;
    }

    .customer-edit-page .cc-card-body {
        padding: 24px 25px 19px;
    }

    .customer-edit-page .cc-section {
        margin-bottom: 22px;
    }

    .customer-edit-page .cc-section-head {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 19px;
    }

    .customer-edit-page .cc-step {
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

    .customer-edit-page .cc-section-head h2 {
        font-size: 16px;
        font-weight: 750;
        margin: 1px 0 4px;
    }

    .customer-edit-page .cc-section-head p {
        margin: 0;
        color: var(--muted);
        font-size: 12.5px;
    }

    .customer-edit-page .cc-info {
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

    .customer-edit-page .cc-info i {
        color: #3887e8;
        margin-top: 2px;
    }

    .customer-edit-page .cc-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px 22px;
    }

    .customer-edit-page .cc-grid-three {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px 22px;
    }

    .customer-edit-page .cc-field {
        min-width: 0;
    }

    .customer-edit-page .cc-field label {
        display: block;
        margin: 0 0 7px;
        font-size: 12.5px;
        font-weight: 650;
        color: #26364f;
    }

    .customer-edit-page .cc-required {
        color: #e34b5f;
    }

    .customer-edit-page .cc-input-wrap {
        display: flex;
        min-width: 0;
        position: relative;
    }

    .customer-edit-page .cc-input-icon {
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

    .customer-edit-page .cc-input-wrap .form-control {
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

    .customer-edit-page .cc-field > .form-control {
        height: 41px;
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: none;
        color: var(--txt);
        font-size: 13px;
        padding: 9px 12px;
    }

    .customer-edit-page .cc-input-wrap .form-control:focus,
    .customer-edit-page .cc-field > .form-control:focus {
        border-color: #8aafff;
        box-shadow: 0 0 0 3px #2864f017;
        outline: none;
    }

    .customer-edit-page .cc-input-wrap:focus-within .cc-input-icon {
        border-color: #8aafff;
        color: var(--p);
    }

    .customer-edit-page .cc-readonly .form-control {
        background: #f5f7fb;
        color: #586b89;
        cursor: not-allowed;
    }

    .customer-edit-page .cc-select-wrap select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 38px !important;
        cursor: pointer;
        background-image: none !important;
    }

    .customer-edit-page .cc-select-wrap:after {
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

    .customer-edit-page .cc-status-active {
        background: #f2fbf5 !important;
        border-color: #a6dfb8 !important;
    }

    .customer-edit-page .cc-status-inactive {
        background: #fff8f0 !important;
        border-color: #f1d0a6 !important;
    }

    .customer-edit-page .cc-help {
        display: block;
        margin-top: 5px;
        font-size: 11.5px;
        line-height: 1.4;
        color: var(--muted);
    }

    .customer-edit-page .cc-help i {
        color: #3990e8;
        margin-right: 4px;
    }

    .customer-edit-page .cc-divider {
        border: 0;
        border-top: 1px solid #e8edf5;
        margin: 22px 0 20px;
    }

    .customer-edit-page .cc-address-count {
        text-align: right;
        color: #8491a6;
        font-size: 11.5px;
        margin-top: 4px;
    }

    .customer-edit-page .cc-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 25px;
        background: #fbfcff;
        border-top: 1px solid #edf1f7;
    }

    .customer-edit-page .cc-btn {
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

    .customer-edit-page .cc-btn-back {
        color: #40516c;
        border: 1px solid #cfd9e8;
        background: #fff;
    }

    .customer-edit-page .cc-btn-back:hover {
        color: var(--p);
        border-color: #9eb8f8;
        background: #f8faff;
    }

    .customer-edit-page .cc-btn-save {
        color: #fff;
        border: 1px solid var(--p);
        background: var(--p);
        box-shadow: 0 5px 12px #2864f02e;
    }

    .customer-edit-page .cc-btn-save:hover {
        background: #1e4fd0;
        border-color: #1e4fd0;
        color: #fff;
    }

    .customer-edit-page .cc-btn-save:disabled {
        opacity: .7;
        cursor: wait;
    }

    .customer-edit-page .cc-alert {
        border-radius: 9px;
        font-size: 13px;
    }

    @media (max-width: 991px) {
        .customer-edit-page .cc-grid-three {
            grid-template-columns: 1fr 1fr;
        }

        .customer-edit-page .cc-info {
            max-width: 270px;
        }
    }

    @media (max-width: 767px) {
        .customer-edit-page .cc-hero {
            padding: 18px;
        }

        .customer-edit-page .cc-hero h1 {
            font-size: 19px;
        }

        .customer-edit-page .cc-card-body {
            padding: 19px 16px;
        }

        .customer-edit-page .cc-grid,
        .customer-edit-page .cc-grid-three {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .customer-edit-page .cc-section-head {
            flex-wrap: wrap;
        }

        .customer-edit-page .cc-info {
            margin-left: 46px;
            max-width: none;
            width: 100%;
        }

        .customer-edit-page .cc-footer {
            padding: 13px 16px;
        }
    }
</style>

<div class="content-wrapper customer-edit-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <nav class="cc-breadcrumb" aria-label="breadcrumb">
                <a href="<?= base_url(); ?>"><i class="fas fa-home"></i></a>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <span>Master Data</span>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <a href="<?= base_url('customer'); ?>">Customer / Supplier</a>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <strong>Edit Data</strong>
            </nav>

            <div class="cc-hero">
                <div class="cc-hero-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h1>Edit Customer / Supplier</h1>
                    <p>Perbarui informasi master data tanpa mengubah kode dan jenis registrasi.</p>
                </div>
            </div>

            <?php if (empty($cust)): ?>
                <div class="alert alert-danger">
                    Data tidak ditemukan. Silakan kembali ke halaman daftar dan pilih data yang akan diedit.
                </div>
                <a href="<?= base_url('customer'); ?>" class="cc-btn cc-btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            <?php else: ?>
                <div class="cc-card">
                    <form id="formCustomerEdit" autocomplete="off" novalidate>
                        <input type="hidden" name="orig_cust" value="<?= html_escape($cust); ?>">

                        <div class="cc-card-body">
                            <div id="formAlert" class="alert cc-alert d-none" role="alert"></div>

                            <section class="cc-section">
                                <div class="cc-section-head">
                                    <span class="cc-step">1</span>
                                    <div>
                                        <h2>Informasi Utama</h2>
                                        <p>Kode dan jenis registrasi tidak dapat diubah dari halaman ini.</p>
                                    </div>
                                    <div class="cc-info">
                                        <i class="fas fa-lock"></i>
                                        <span>Kode master adalah identitas unik. Untuk menjaga konsistensi data, kode dan jenis registrasi dikunci.</span>
                                    </div>
                                </div>

                                <div class="cc-grid cc-grid-three">
                                    <div class="cc-field">
                                        <label>Kode Master</label>
                                        <div class="cc-input-wrap cc-readonly">
                                            <span class="cc-input-icon">
                                                <i class="fas fa-fingerprint"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?= html_escape($cust); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label>Jenis Registrasi</label>
                                        <div class="cc-input-wrap cc-readonly">
                                            <span class="cc-input-icon">
                                                <i class="fas fa-user-friends"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?= html_escape(ucfirst(strtolower($custKind))); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label for="status">Status <span class="cc-required">*</span></label>
                                        <div class="cc-input-wrap cc-select-wrap">
                                            <span class="cc-input-icon">
                                                <i class="fas <?= $status === 'ACTIVE' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>" id="statusIcon"></i>
                                            </span>
                                            <select name="status" id="status" class="form-control <?= $status === 'ACTIVE' ? 'cc-status-active' : 'cc-status-inactive'; ?>" required>
                                                <option value="ACTIVE" <?= $status === 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                                <option value="INACTIVE" <?= $status === 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
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
                                        <p>Periksa dan perbarui data kontak perusahaan.</p>
                                    </div>
                                </div>

                                <div class="cc-grid">
                                    <div class="cc-field">
                                        <label for="full_name">Nama Perusahaan <span class="cc-required">*</span></label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon">
                                                <i class="far fa-building"></i>
                                            </span>
                                            <input type="text" name="full_name" id="full_name" class="form-control" maxlength="255" placeholder="Masukkan nama perusahaan" value="<?= html_escape(isset($row['FULL_NAME']) ? $row['FULL_NAME'] : ''); ?>" required>
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label for="short_name">Nama Singkat</label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon">
                                                <i class="far fa-address-card"></i>
                                            </span>
                                            <input type="text" name="short_name" id="short_name" class="form-control" maxlength="255" placeholder="Nama singkat / alias perusahaan" value="<?= html_escape(isset($row['SHORT_NAME']) ? $row['SHORT_NAME'] : ''); ?>">
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label for="chief_name">Penanggung Jawab (PIC)</label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon">
                                                <i class="far fa-user"></i>
                                            </span>
                                            <input type="text" name="chief_name" id="chief_name" class="form-control" maxlength="255" placeholder="Nama PIC / Contact Person" value="<?= html_escape(isset($row['CHIEF_NAME']) ? $row['CHIEF_NAME'] : ''); ?>">
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label for="tax_no">Nomor Pajak (NPWP)</label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon">
                                                <i class="far fa-file-alt"></i>
                                            </span>
                                            <input type="text" name="tax_no" id="tax_no" class="form-control" maxlength="255" placeholder="Masukkan nomor pajak" value="<?= html_escape(isset($row['TAX_NO']) ? $row['TAX_NO'] : ''); ?>">
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label for="telephone_no">Telepon</label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon">
                                                <i class="fas fa-phone-alt"></i>
                                            </span>
                                            <input type="text" name="telephone_no" id="telephone_no" class="form-control" maxlength="255" placeholder="Contoh: 021-1234567" value="<?= html_escape(isset($row['TELEPHONE_NO']) ? $row['TELEPHONE_NO'] : ''); ?>">
                                        </div>
                                    </div>

                                    <div class="cc-field">
                                        <label for="mobile_phone">Nomor HP</label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon">
                                                <i class="fas fa-mobile-alt"></i>
                                            </span>
                                            <input type="text" name="mobile_phone" id="mobile_phone" class="form-control" maxlength="255" placeholder="Contoh: 08123456789" value="<?= html_escape(isset($row['MOBILE_PHONE']) ? $row['MOBILE_PHONE'] : ''); ?>">
                                        </div>
                                    </div>

                                    <div class="cc-field" style="grid-column:1/-1">
                                        <label for="address2">Alamat</label>
                                        <div class="cc-input-wrap">
                                            <span class="cc-input-icon" style="align-items:flex-start;padding-top:13px">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <textarea name="address2" id="address2" class="form-control" rows="3" maxlength="500" placeholder="Masukkan alamat lengkap perusahaan" style="height:auto;min-height:78px;border-radius:0 8px 8px 0"><?= html_escape(isset($row['ADDRESS2']) ? $row['ADDRESS2'] : ''); ?></textarea>
                                        </div>
                                        <div class="cc-address-count">
                                            <span id="addressCount"><?= strlen(isset($row['ADDRESS2']) ? $row['ADDRESS2'] : ''); ?></span> / 500
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
                                <i class="far fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
    (function ($) {
        'use strict';

        var listUrl = <?= json_encode(base_url('customer')); ?>;
        var editUrl = <?= json_encode(base_url('customer/edit/' . rawurlencode($cust))); ?>;

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

        $('#formCustomerEdit').on('submit', function (e) {
            e.preventDefault();
            hideAlert();

            if (!$.trim($('#full_name').val())) {
                showAlert('danger', 'Nama Perusahaan wajib diisi.');
                $('#full_name').trigger('focus');
                return;
            }

            var $button = $('#btnSaveCustomer');
            var originalButtonContent = $button.html();

            $button
                .prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: editUrl,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json'
            }).done(function (r) {
                if (r && (
                    r.success === true ||
                    r.status === true ||
                    r.status === 'success'
                )) {
                    showAlert('success', r.message || 'Data berhasil diperbarui.');
                    setTimeout(function () {
                        window.location.href = listUrl;
                    }, 900);
                    return;
                }

                var errorMessage = r && r.message ? r.message : 'Data gagal diperbarui.';

                if (r && r.errors) {
                    if (typeof r.errors === 'string') {
                        errorMessage += ' ' + r.errors;
                    } else {
                        errorMessage += ' ' + Object.values(r.errors).join(' ');
                    }
                }

                showAlert('danger', errorMessage);
                $button.prop('disabled', false).html(originalButtonContent);
            }).fail(function(xhr) {
                var msg = 'Terjadi kesalahan saat memperbarui data.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var parsed = JSON.parse(xhr.responseText);
                        if (parsed.message) {
                            msg = parsed.message;
                        } else {
                            console.error('Respons server:', xhr.responseText);
                        }
                    } catch (err) {
                        console.error('Respons server:', xhr.responseText);
                    }
                }

                console.error('HTTP Status:', xhr.status);
                console.error('Response:', xhr.responseText);

                showAlert('danger', msg);
                $button.prop('disabled', false).html(originalButtonContent);
            });
        });
    })(jQuery);
</script>
