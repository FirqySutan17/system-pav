
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$salesCode = isset($sales->SALES) ? $sales->SALES : '';
$fullName  = isset($sales->FULL_NAME) ? $sales->FULL_NAME : '';
$phone     = isset($sales->PHONE) ? $sales->PHONE : '';
$email     = isset($sales->EMAIL) ? $sales->EMAIL : '';
$status    = isset($sales->STATUS) ? $sales->STATUS : 'ACTIVE';
?>

<style>
.master-sales-edit {
    --ms-primary: #2864f0;
    --ms-text: #17233b;
    --ms-muted: #66758f;
    --ms-border: #dce4ef;
    color: var(--ms-text);
    padding-bottom: 24px;
}

.master-sales-edit .ms-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin: 4px 0 18px;
    color: var(--ms-muted);
    font-size: 13px;
}

.master-sales-edit .ms-breadcrumb a {
    color: var(--ms-muted);
    text-decoration: none;
}

.master-sales-edit .ms-breadcrumb a:hover {
    color: var(--ms-primary);
}

.master-sales-edit .ms-hero {
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

.master-sales-edit .ms-hero-icon {
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

.master-sales-edit .ms-hero h1 {
    margin: 0 0 5px;
    font-size: 23px;
    font-weight: 750;
    letter-spacing: -.45px;
}

.master-sales-edit .ms-hero p {
    margin: 0;
    color: var(--ms-muted);
    font-size: 13px;
}

.master-sales-edit .ms-card {
    background: #fff;
    border: 1px solid #e5ebf4;
    border-radius: 16px;
    box-shadow: 0 9px 28px #1b34600e;
    overflow: hidden;
}

.master-sales-edit .ms-card-body {
    padding: 24px 25px 22px;
}

.master-sales-edit .ms-section {
    margin-bottom: 18px;
}

.master-sales-edit .ms-section-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.master-sales-edit .ms-section-head > div {
    flex: 1;
    min-width: 0;
}

.master-sales-edit .ms-step {
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

.master-sales-edit .ms-section-head h2 {
    font-size: 16px;
    font-weight: 750;
    margin: 1px 0 4px;
}

.master-sales-edit .ms-section-head p {
    margin: 0;
    color: var(--ms-muted);
    font-size: 12.5px;
}

.master-sales-edit .ms-info {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    padding: 10px 12px;
    border-radius: 10px;
    color: #426087;
    background: #f0f6ff;
    font-size: 12px;
    line-height: 1.5;
}

.master-sales-edit .ms-info i {
    color: #3887e8;
    margin-top: 2px;
}

.master-sales-edit .ms-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 24px;
}

.master-sales-edit .ms-field {
    min-width: 0;
}

.master-sales-edit .ms-field label {
    display: block;
    margin: 0 0 7px;
    font-size: 12.5px;
    font-weight: 650;
    color: #26364f;
}

.master-sales-edit .ms-required {
    color: #e34b5f;
}

.master-sales-edit .ms-input-wrap {
    display: flex;
    min-width: 0;
}

.master-sales-edit .ms-input-icon {
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

.master-sales-edit .ms-input-wrap .form-control {
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

.master-sales-edit .ms-input-wrap .form-control:focus {
    border-color: #8aafff;
    box-shadow: 0 0 0 3px #2864f017;
    outline: none;
}

.master-sales-edit .ms-input-wrap:focus-within .ms-input-icon {
    border-color: #8aafff;
    color: var(--ms-primary);
}

.master-sales-edit .ms-code-wrap .form-control {
    background: #f5f7fb;
    color: #586b89;
    border-radius: 0 8px 8px 0;
    font-weight: 650;
    letter-spacing: .5px;
    cursor: not-allowed;
}

.master-sales-edit .ms-help {
    display: block;
    margin-top: 5px;
    font-size: 11.5px;
    line-height: 1.4;
    color: var(--ms-muted);
}

.master-sales-edit .ms-help i {
    color: #3990e8;
    margin-right: 4px;
}

.master-sales-edit .ms-status-active {
    background: #f2fbf5 !important;
    border-color: #a6dfb8 !important;
}

.master-sales-edit .ms-status-inactive {
    background: #fff8f0 !important;
    border-color: #f1d0a6 !important;
}

.master-sales-edit .ms-divider {
    border: 0;
    border-top: 1px solid #e8edf5;
    margin: 22px 0 20px;
}

.master-sales-edit .ms-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 15px 25px;
    background: #fbfcff;
    border-top: 1px solid #edf1f7;
}

.master-sales-edit .ms-btn {
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

.master-sales-edit .ms-btn-back {
    color: #40516c;
    border: 1px solid #cfd9e8;
    background: #fff;
}

.master-sales-edit .ms-btn-back:hover {
    color: var(--ms-primary);
    border-color: #9eb8f8;
    background: #f8faff;
}

.master-sales-edit .ms-btn-save {
    color: #fff;
    border: 1px solid var(--ms-primary);
    background: var(--ms-primary);
    box-shadow: 0 5px 12px #2864f02e;
}

.master-sales-edit .ms-btn-save:hover {
    background: #1e4fd0;
    border-color: #1e4fd0;
    color: #fff;
}

.master-sales-edit .ms-btn-save:disabled {
    opacity: .7;
    cursor: wait;
}

.master-sales-edit .ms-alert {
    border-radius: 9px;
    font-size: 13px;
}

@media(max-width:767px) {
    .master-sales-edit .ms-hero {
        padding: 18px;
    }

    .master-sales-edit .ms-hero h1 {
        font-size: 19px;
    }

    .master-sales-edit .ms-card-body {
        padding: 19px 16px;
    }

    .master-sales-edit .ms-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .master-sales-edit .ms-footer {
        padding: 13px 16px;
    }
}
</style>

<div class="content-wrapper master-sales-edit">

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
                <strong>Edit Data</strong>
            </nav>

            <!-- Hero -->
            <div class="ms-hero">
                <div class="ms-hero-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h1>Edit Master Sales</h1>
                    <p>Perbarui informasi dan status Sales.</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="ms-card">

                <form id="formMasterSalesEdit" autocomplete="off" novalidate>

                    <div class="ms-card-body">

                        <div id="msEditAlert"
                             class="alert ms-alert d-none"
                             role="alert"></div>

                        <!-- Section 1 -->
                        <section class="ms-section">

                            <div class="ms-section-head">

                                <span class="ms-step">1</span>

                                <div>
                                    <h2>Informasi Utama</h2>
                                    <p>Sales Code bersifat permanen dan tidak dapat diubah.</p>
                                </div>

                            </div>

                            <div class="ms-grid">

                                <!-- Sales Code -->
                                <div class="ms-field">

                                    <label for="sales_code">Sales Code</label>

                                    <div class="ms-input-wrap ms-code-wrap">

                                        <span class="ms-input-icon">
                                            <i class="fas fa-fingerprint"></i>
                                        </span>

                                        <input type="text"
                                               id="sales_code"
                                               class="form-control"
                                               value="<?= html_escape($salesCode); ?>"
                                               readonly>

                                    </div>

                                    <small class="ms-help">
                                        <i class="fas fa-lock"></i>
                                        Sales Code tidak dapat diubah.
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
                                                class="form-control"
                                                required>

                                            <option value="ACTIVE"
                                                <?= $status === 'ACTIVE' ? 'selected' : ''; ?>>
                                                Active
                                            </option>

                                            <option value="INACTIVE"
                                                <?= $status === 'INACTIVE' ? 'selected' : ''; ?>>
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
                                    <p>Perbarui identitas dan informasi kontak Sales.</p>
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
                                               value="<?= html_escape($fullName); ?>"
                                               placeholder="Masukkan nama lengkap Sales"
                                               required>

                                    </div>

                                </div>

                                <!-- Phone -->
                                <div class="ms-field">

                                    <label for="phone">Phone</label>

                                    <div class="ms-input-wrap">

                                        <span class="ms-input-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </span>

                                        <input type="text"
                                               name="PHONE"
                                               id="phone"
                                               class="form-control"
                                               maxlength="50"
                                               value="<?= html_escape($phone); ?>"
                                               placeholder="Contoh: 08123456789">

                                    </div>

                                    <small class="ms-help">
                                        Nomor telepon atau HP Sales.
                                    </small>

                                </div>

                                <!-- Email -->
                                <div class="ms-field">

                                    <label for="email">Email</label>

                                    <div class="ms-input-wrap">

                                        <span class="ms-input-icon">
                                            <i class="far fa-envelope"></i>
                                        </span>

                                        <input type="email"
                                               name="EMAIL"
                                               id="email"
                                               class="form-control"
                                               maxlength="150"
                                               value="<?= html_escape($email); ?>"
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
                                id="btnUpdateMasterSales"
                                class="ms-btn ms-btn-save">

                            <i class="fas fa-save"></i>
                            Simpan Perubahan

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

    var updateUrl = <?= json_encode(
        base_url('master_sales/update/' . rawurlencode($salesCode))
    ); ?>;

    var listUrl = <?= json_encode(base_url('master_sales')); ?>;

    function showAlert(type, message) {

        $('#msEditAlert')
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert-' + type)
            .text(message);

        $('html, body').animate({
            scrollTop: $('#msEditAlert').offset().top - 100
        }, 180);

    }

    function hideAlert() {

        $('#msEditAlert')
            .addClass('d-none')
            .text('');

    }

    // Status Styling
    function updateStatusStyle() {

        var isActive = $('#sales_status').val() === 'ACTIVE';

        $('#sales_status')
            .toggleClass('ms-status-active', isActive)
            .toggleClass('ms-status-inactive', !isActive);

        $('#salesStatusIcon')
            .toggleClass('fa-check-circle', isActive)
            .toggleClass('fa-exclamation-circle', !isActive);

    }

    $('#sales_status').on('change', updateStatusStyle);

    // Submit Form
    $('#formMasterSalesEdit').on('submit', function(event) {

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

        var $button = $('#btnUpdateMasterSales');
        var originalHtml = $button.html();

        $button
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({

            url: updateUrl,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json'

        }).done(function(response) {

            if (response && response.status === true) {

                showAlert(
                    'success',
                    response.message || 'Sales berhasil diperbarui.'
                );

                setTimeout(function() {
                    window.location.href = listUrl;
                }, 900);

                return;

            }

            showAlert(
                'danger',
                response && response.message
                    ? response.message
                    : 'Data gagal diperbarui.'
            );

            $button
                .prop('disabled', false)
                .html(originalHtml);

        }).fail(function(xhr) {

            var message = 'Terjadi kesalahan saat memperbarui data.';

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

    $(function() {
        updateStatusStyle();
    });

})(jQuery);
</script>
