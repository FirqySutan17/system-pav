
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.master-sales-create {
    --ms-primary: #2864f0;
    --ms-text: #17233b;
    --ms-muted: #66758f;
    --ms-border: #dce4ef;
    color: var(--ms-text);
    padding-bottom: 24px;
}

.master-sales-create .ms-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin: 4px 0 18px;
    color: var(--ms-muted);
    font-size: 13px;
}

.master-sales-create .ms-breadcrumb a {
    color: var(--ms-muted);
    text-decoration: none;
}

.master-sales-create .ms-breadcrumb a:hover {
    color: var(--ms-primary);
}

.master-sales-create .ms-hero {
    display: flex;
    align-items: center;
    gap: 17px;
    padding: 22px 25px;
    border: 1px solid #e5ebf6;
    border-radius: 16px;
    background: linear-gradient(115deg, #fff, #f8faff 55%, #edf3ff);
    box-shadow: 0 7px 24px #1b34600b;
    margin-bottom: 16px;
}

.master-sales-create .ms-hero-icon {
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    color: var(--ms-primary);
    background: #e6eeff;
    font-size: 22px;
}

.master-sales-create .ms-hero h1 {
    margin: 0 0 5px;
    font-size: 23px;
    font-weight: 750;
    letter-spacing: -.45px;
}

.master-sales-create .ms-hero p {
    margin: 0;
    color: var(--ms-muted);
    font-size: 13px;
}

.master-sales-create .ms-card {
    background: #fff;
    border: 1px solid #e5ebf4;
    border-radius: 16px;
    box-shadow: 0 9px 28px #1b34600e;
    overflow: hidden;
}

.master-sales-create .ms-card-body {
    padding: 24px 25px 19px;
}

.master-sales-create .ms-section {
    margin-bottom: 18px;
}

.master-sales-create .ms-section-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.master-sales-create .ms-section-head > div:not(.ms-info) {
    flex: 1;
    min-width: 0;
}

.master-sales-create .ms-info {
    margin-left: auto;
    flex: 0 1 360px;
    max-width: 360px;
}

.master-sales-create .ms-divider {
    margin: 18px 0;
}

.master-sales-create .ms-step {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    border-radius: 50%;
    background: var(--ms-primary);
    color: #fff;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 4px 10px #2864f033;
}

.master-sales-create .ms-section-head h2 {
    font-size: 16px;
    font-weight: 750;
    margin: 1px 0 4px;
}

.master-sales-create .ms-section-head p {
    margin: 0;
    color: var(--ms-muted);
    font-size: 12.5px;
}

.master-sales-create .ms-info {
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

.master-sales-create .ms-info i {
    color: #3887e8;
    margin-top: 2px;
}

.master-sales-create .ms-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 24px;
}

/* Main information: two columns */
.master-sales-create .ms-grid-three {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.master-sales-create .ms-field {
    min-width: 0;
}

.master-sales-create .ms-field label {
    display: block;
    margin: 0 0 7px;
    font-size: 12.5px;
    font-weight: 650;
    color: #26364f;
}

.master-sales-create .ms-required {
    color: #e34b5f;
}

.master-sales-create .ms-input-wrap {
    display: flex;
    min-width: 0;
}

.master-sales-create .ms-input-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 41px;
    flex: 0 0 41px;
    color: #5f708a;
    background: #f8faff;
    border: 1px solid var(--ms-border);
    border-right: 0;
    border-radius: 8px 0 0 8px;
}

.master-sales-create .ms-input-wrap .form-control {
    min-width: 0;
    height: 41px;
    border: 1px solid var(--ms-border);
    border-radius: 0 8px 8px 0;
    box-shadow: none;
    color: var(--ms-text);
    font-size: 13px;
    padding: 9px 12px;
    transition: border-color .15s, box-shadow .15s;
}

.master-sales-create .ms-input-wrap .form-control:focus {
    border-color: #8aafff;
    box-shadow: 0 0 0 3px #2864f017;
    outline: none;
}

.master-sales-create .ms-input-wrap:focus-within .ms-input-icon {
    border-color: #8aafff;
    color: var(--ms-primary);
}

.master-sales-create .ms-code-wrap .form-control {
    background: #f5f7fb;
    color: #586b89;
    border-radius: 0;
    font-weight: 650;
    letter-spacing: .5px;
}

.master-sales-create .ms-code-copy {
    width: 43px;
    flex: 0 0 43px;
    border: 1px solid var(--ms-border);
    border-left: 0;
    border-radius: 0 8px 8px 0;
    background: #f2f5fb;
    color: #58708f;
}

.master-sales-create .ms-code-copy:hover {
    color: var(--ms-primary);
    background: #eaf0ff;
}

.master-sales-create .ms-help {
    display: block;
    margin-top: 5px;
    font-size: 11.5px;
    line-height: 1.4;
    color: var(--ms-muted);
}

.master-sales-create .ms-help i {
    color: #3990e8;
    margin-right: 4px;
}

.master-sales-create .ms-status-active {
    background: #f2fbf5 !important;
    border-color: #a6dfb8 !important;
}

.master-sales-create .ms-status-inactive {
    background: #fff8f0 !important;
    border-color: #f1d0a6 !important;
}

.master-sales-create .ms-divider {
    border: 0;
    border-top: 1px solid #e8edf5;
    margin: 22px 0 20px;
}

.master-sales-create .ms-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 15px 25px;
    background: #fbfcff;
    border-top: 1px solid #edf1f7;
}

.master-sales-create .ms-btn {
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

.master-sales-create .ms-btn-back {
    color: #40516c;
    border: 1px solid #cfd9e8;
    background: #fff;
}

.master-sales-create .ms-btn-back:hover {
    color: var(--ms-primary);
    border-color: #9eb8f8;
    background: #f8faff;
}

.master-sales-create .ms-btn-save {
    color: #fff;
    border: 1px solid var(--ms-primary);
    background: var(--ms-primary);
    box-shadow: 0 5px 12px #2864f02e;
}

.master-sales-create .ms-btn-save:hover {
    background: #1e4fd0;
    border-color: #1e4fd0;
    color: #fff;
}

.master-sales-create .ms-btn-save:disabled {
    opacity: .7;
    cursor: wait;
}

.master-sales-create .ms-alert {
    border-radius: 9px;
    font-size: 13px;
}

@media(max-width:991px) {
    .master-sales-create .ms-grid-three {
        grid-template-columns: 1fr 1fr;
    }

    .master-sales-create .ms-info {
        max-width: 270px;
    }
}

@media(max-width:767px) {
    .master-sales-create .ms-hero {
        padding: 18px;
    }

    .master-sales-create .ms-hero h1 {
        font-size: 19px;
    }

    .master-sales-create .ms-card-body {
        padding: 19px 16px;
    }

    .master-sales-create .ms-grid,
    .master-sales-create .ms-grid-three {
        grid-template-columns: 1fr;
        gap: 13px;
    }

    .master-sales-create .ms-section-head {
        flex-wrap: wrap;
    }

    .master-sales-create .ms-info {
        margin-left: 46px;
        flex: 1 1 100%;
        max-width: none;
    }

    .master-sales-create .ms-footer {
        padding: 13px 16px;
    }
    .master-sales-create .ms-grid,
    .master-sales-create .ms-grid-three {
        grid-template-columns: 1fr;
        gap: 14px;
    }
}
</style>

<div class="content-wrapper master-sales-create">

    <section class="content pt-3">
        <div class="container-fluid">

            <!-- Breadcrumb -->
            <nav class="ms-breadcrumb" aria-label="breadcrumb" style="margin-top: 40px">
                <a href="<?= base_url(); ?>">
                    <i class="fas fa-home"></i>
                </a>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <span>Master Data</span>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <a href="<?= base_url('master_sales'); ?>">Master Sales</a>
                <i class="fas fa-chevron-right" style="font-size:10px"></i>
                <strong>Tambah Data</strong>
            </nav>

            <!-- Hero -->
            <div class="ms-hero">
                <div class="ms-hero-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div>
                    <h1>Tambah Master Sales</h1>
                    <p>Registrasi data Sales baru ke dalam sistem.</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="ms-card">

                <form id="formMasterSalesCreate" autocomplete="off" novalidate>

                    <div class="ms-card-body">

                        <div id="msFormAlert"
                             class="alert ms-alert d-none"
                             role="alert"></div>

                        <!-- Section 1 -->
                        <section class="ms-section">

                            <div class="ms-section-head">

                                <span class="ms-step">1</span>

                                <div>
                                    <h2>Informasi Utama</h2>
                                    <p>Lengkapi kode Sales dan status data.</p>
                                </div>

                                <div class="ms-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>
                                        Sales Code dibuat otomatis oleh sistem
                                        dengan format SLS000001. Kode final
                                        ditentukan kembali saat data disimpan.
                                    </span>
                                </div>

                            </div>

                            <div class="ms-grid ms-grid-three">

                                <!-- Sales Code -->
                                <div class="ms-field">

                                    <label for="sales_preview">
                                        Sales Code
                                    </label>

                                    <div class="ms-input-wrap ms-code-wrap">

                                        <span class="ms-input-icon">
                                            <i class="fas fa-fingerprint"></i>
                                        </span>

                                        <input type="text"
                                               id="sales_preview"
                                               class="form-control"
                                               value="Memuat..."
                                               readonly>

                                        <button type="button"
                                                class="ms-code-copy"
                                                id="btnCopySalesCode"
                                                title="Salin Sales Code"
                                                aria-label="Salin Sales Code">
                                            <i class="far fa-copy"></i>
                                        </button>

                                    </div>

                                    <small class="ms-help">
                                        <i class="fas fa-info-circle"></i>
                                        Preview kode. Kode final dibuat saat
                                        data disimpan.
                                    </small>

                                </div>

                                <!-- Status -->
                                <div class="ms-field">

                                    <label for="sales_status">
                                        Status <span class="ms-required">*</span>
                                    </label>

                                    <div class="ms-input-wrap">

                                        <span class="ms-input-icon">
                                            <i class="fas fa-check-circle"
                                               id="salesStatusIcon"></i>
                                        </span>

                                        <select name="STATUS"
                                                id="sales_status"
                                                class="form-control ms-status-active"
                                                required>

                                            <option value="ACTIVE" selected>
                                                Active
                                            </option>

                                            <option value="INACTIVE">
                                                Inactive
                                            </option>

                                        </select>

                                    </div>

                                    <small class="ms-help">
                                        Menentukan apakah Sales dapat digunakan
                                        dalam transaksi.
                                    </small>

                                </div>

                            </div>

                        </section>

                        <hr class="ms-divider">

                        <!-- Section 2 -->
                        <section class="ms-section mb-0">

                            <div class="ms-section-head">

                                <span class="ms-step">2</span>

                                <div>
                                    <h2>Informasi Sales</h2>
                                    <p>Lengkapi informasi identitas dan kontak Sales.</p>
                                </div>

                            </div>

                            <div class="ms-grid">

                                <!-- Full Name -->
                                <div class="ms-field" style="grid-column: 1 / -1;">

                                    <label for="full_name">
                                        Full Name
                                        <span class="ms-required">*</span>
                                    </label>

                                    <div class="ms-input-wrap">

                                        <span class="ms-input-icon">
                                            <i class="far fa-user"></i>
                                        </span>

                                        <input type="text"
                                               name="FULL_NAME"
                                               id="full_name"
                                               class="form-control"
                                               maxlength="255"
                                               placeholder="Masukkan nama lengkap Sales"
                                               required>

                                    </div>

                                </div>

                                <!-- Phone -->
                                <div class="ms-field">

                                    <label for="phone">
                                        Phone
                                    </label>

                                    <div class="ms-input-wrap">

                                        <span class="ms-input-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </span>

                                        <input type="text"
                                               name="PHONE"
                                               id="phone"
                                               class="form-control"
                                               maxlength="50"
                                               placeholder="Contoh: 08123456789">

                                    </div>

                                    <small class="ms-help">
                                        Nomor telepon atau HP Sales.
                                    </small>

                                </div>

                                <!-- Email -->
                                <div class="ms-field">

                                    <label for="email">
                                        Email
                                    </label>

                                    <div class="ms-input-wrap">

                                        <span class="ms-input-icon">
                                            <i class="far fa-envelope"></i>
                                        </span>

                                        <input type="email"
                                               name="EMAIL"
                                               id="email"
                                               class="form-control"
                                               maxlength="150"
                                               placeholder="Contoh: sales@company.com">

                                    </div>

                                    <small class="ms-help">
                                        Masukkan alamat email yang valid.
                                    </small>

                                </div>

                            </div>

                        </section>

                    </div>

                    <!-- Footer -->
                    <div class="ms-footer">

                        <a href="<?= base_url('master_sales'); ?>"
                           class="ms-btn ms-btn-back">

                            <i class="fas fa-arrow-left"></i>
                            Kembali

                        </a>

                        <button type="submit"
                                id="btnSaveMasterSales"
                                class="ms-btn ms-btn-save">

                            <i class="far fa-save"></i>
                            Simpan Data

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </section>

</div>

<script>
(function($) {

    'use strict';

    var previewUrl = <?= json_encode(base_url('master_sales/preview_code')); ?>;
    var saveUrl    = <?= json_encode(base_url('master_sales/store')); ?>;
    var listUrl    = <?= json_encode(base_url('master_sales')); ?>;

    function showAlert(type, message) {

        $('#msFormAlert')
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert-' + type)
            .text(message);

        $('html, body').animate({
            scrollTop: $('#msFormAlert').offset().top - 100
        }, 180);

    }

    function hideAlert() {

        $('#msFormAlert')
            .addClass('d-none')
            .text('');

    }

    // Load Sales Code Preview
    function loadSalesCodePreview() {

        $('#sales_preview').val('Memuat...');

        $.ajax({
            url: previewUrl,
            type: 'GET',
            dataType: 'json'

        }).done(function(response) {

            var code = '';

            if (response && response.sales) {
                code = response.sales;
            } else if (response && response.data && response.data.sales) {
                code = response.data.sales;
            }

            $('#sales_preview').val(code || 'SLS000001');

        }).fail(function() {

            // Preview is informational only.
            // Final code is generated by backend during save.
            $('#sales_preview').val('Tidak tersedia');

        });

    }

    // Status Styling
    $('#sales_status').on('change', function() {

        var isActive = $(this).val() === 'ACTIVE';

        $(this)
            .toggleClass('ms-status-active', isActive)
            .toggleClass('ms-status-inactive', !isActive);

        $('#salesStatusIcon')
            .toggleClass('fa-check-circle', isActive)
            .toggleClass('fa-exclamation-circle', !isActive);

    });

    // Copy Sales Code
    $('#btnCopySalesCode').on('click', function() {

        var value = $('#sales_preview').val();

        if (!value || value === 'Memuat...' || value === 'Tidak tersedia') {
            return;
        }

        var $icon = $(this).find('i');

        function copied() {

            $icon.removeClass('far fa-copy')
                 .addClass('fas fa-check');

            setTimeout(function() {

                $icon.removeClass('fas fa-check')
                     .addClass('far fa-copy');

            }, 1000);

        }

        if (navigator.clipboard && navigator.clipboard.writeText) {

            navigator.clipboard.writeText(value)
                .then(copied)
                .catch(function() {
                    fallbackCopy(value, copied);
                });

        } else {

            fallbackCopy(value, copied);

        }

    });

    function fallbackCopy(value, callback) {

        var $temp = $('<textarea>')
            .val(value)
            .css({
                position: 'absolute',
                left: '-9999px'
            })
            .appendTo('body');

        $temp[0].select();

        try {

            if (document.execCommand('copy')) {
                callback();
            }

        } catch (error) {
            console.error('Copy failed:', error);
        }

        $temp.remove();

    }

    // Submit Form
    $('#formMasterSalesCreate').on('submit', function(event) {

        event.preventDefault();

        hideAlert();

        var fullName = $.trim($('#full_name').val());
        var email = $.trim($('#email').val());

        if (!fullName) {

            showAlert('danger', 'Full Name wajib diisi.');

            $('#full_name').trigger('focus');

            return;

        }

        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {

            showAlert('danger', 'Format Email tidak valid.');

            $('#email').trigger('focus');

            return;

        }

        var $button = $('#btnSaveMasterSales');
        var originalHtml = $button.html();

        $button
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({

            url: saveUrl,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json'

        }).done(function(response) {

            if (response && response.status === true) {

                var savedCode = response.sales || '';

                var message = response.message || 'Data berhasil disimpan.';

                if (savedCode) {
                    message += ' Sales Code: ' + savedCode;
                }

                showAlert('success', message);

                setTimeout(function() {

                    window.location.href = listUrl;

                }, 900);

                return;

            }

            var message = response && response.message
                ? response.message
                : 'Data gagal disimpan.';

            if (response && response.errors) {

                if (typeof response.errors === 'string') {
                    message += ' ' + response.errors;
                } else {
                    message += ' ' + Object.values(response.errors).join(' ');
                }

            }

            showAlert('danger', message);

            $button
                .prop('disabled', false)
                .html(originalHtml);

        }).fail(function(xhr) {

            var message = 'Terjadi kesalahan saat menyimpan data.';

            if (xhr.responseJSON && xhr.responseJSON.message) {

                message = xhr.responseJSON.message;

            } else if (xhr.responseText) {

                try {

                    var parsed = JSON.parse(xhr.responseText);

                    if (parsed.message) {
                        message = parsed.message;
                    }

                } catch (error) {
                    console.error('Invalid response:', error);
                }

            }

            showAlert('danger', message);

            $button
                .prop('disabled', false)
                .html(originalHtml);

        });

    });

    // Initial Load
    $(function() {
        loadSalesCodePreview();
    });

})(jQuery);
</script>
