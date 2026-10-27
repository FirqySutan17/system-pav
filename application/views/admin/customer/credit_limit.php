<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$row = isset($row) && is_array($row) ? $row : array();
$credit = isset($credit) && is_array($credit) ? $credit : array();
$history = isset($history) && is_array($history) ? $history : array();
$cust = isset($row['CUST']) ? $row['CUST'] : (isset($credit['CUST']) ? $credit['CUST'] : '');
$custName = isset($row['FULL_NAME']) ? $row['FULL_NAME'] : '';
$custKind = isset($row['CUST_KIND']) ? strtoupper($row['CUST_KIND']) : 'CUSTOMER';
$currency = isset($credit['CURRENCY']) ? $credit['CURRENCY'] : 'IDR';
$basicCredit = isset($credit['BASIC_CREDIT']) ? (float)$credit['BASIC_CREDIT'] : 0;
$basicCreditFrom = !empty($credit['BASIC_CREDIT_FROM_DATE']) ? $credit['BASIC_CREDIT_FROM_DATE'] : (!empty($credit['BASIC_CREDIT_DATE']) ? $credit['BASIC_CREDIT_DATE'] : date('Y-m-d'));
$basicCreditTo = isset($credit['BASIC_CREDIT_TO_DATE']) ? $credit['BASIC_CREDIT_TO_DATE'] : '';
$termsOfPayment = isset($credit['TERMS_OF_PAYMENT']) ? (string)$credit['TERMS_OF_PAYMENT'] : '';
?>
<style>
.credit-page{--p:#2864f0;--txt:#17233b;--muted:#66758f;--border:#dce4ef;color:var(--txt);padding-bottom:24px}
.credit-page .cp-breadcrumb{display:flex;align-items:center;flex-wrap:wrap;gap:9px;margin:4px 0 18px;color:var(--muted);font-size:13px}
.credit-page .cp-breadcrumb a{color:var(--muted);text-decoration:none}.credit-page .cp-breadcrumb a:hover{color:var(--p)}
.credit-page .cp-hero{display:flex;align-items:center;gap:16px;padding:22px 25px;border:1px solid #e5ebf6;margin-top:60px;border-radius:16px;background:linear-gradient(115deg,#fff,#f8faff 55%,#edf3ff);box-shadow:0 7px 24px #1b34600b;margin-bottom:16px}
.credit-page .cp-hero-icon{flex:0 0 54px;width:54px;height:54px;display:flex;align-items:center;justify-content:center;border-radius:14px;color:var(--p);background:#e6eeff;font-size:22px}
.credit-page .cp-hero h1{margin:0 0 5px;font-size:23px;font-weight:750;letter-spacing:-.45px}.credit-page .cp-hero p{margin:0;color:var(--muted);font-size:13px}
.credit-page .cp-customer{margin-left:auto;text-align:right}.credit-page .cp-customer strong{display:block;font-size:14px}.credit-page .cp-customer span{font-size:12px;color:var(--muted)}
.credit-page .cp-summary-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-bottom:16px}
.credit-page .cp-stat{background:#fff;border:1px solid #e5ebf4;border-radius:14px;padding:17px 19px;box-shadow:0 6px 20px #1b346008;min-width:0}
.credit-page .cp-stat-label{display:flex;align-items:center;gap:8px;color:var(--muted);font-size:12px;font-weight:600;margin-bottom:9px}.credit-page .cp-stat-label i{color:var(--p)}
.credit-page .cp-stat-value{font-size:23px;font-weight:750;letter-spacing:-.3px;overflow-wrap:anywhere}.credit-page .cp-stat-note{font-size:11.5px;color:var(--muted);margin-top:4px}
.credit-page .cp-card{background:#fff;border:1px solid #e5ebf4;border-radius:16px;box-shadow:0 9px 28px #1b34600e;overflow:hidden;margin-bottom:16px}
.credit-page .cp-card-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;border-bottom:1px solid #edf1f7;background:#fcfdff}
.credit-page .cp-card-head h2{font-size:16px;font-weight:750;margin:0 0 4px}.credit-page .cp-card-head p{font-size:12px;color:var(--muted);margin:0}
.credit-page .cp-card-body{padding:22px}
.credit-page .cp-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px 22px}
.credit-page .cp-field{min-width:0}.credit-page .cp-field label{display:block;margin:0 0 7px;font-size:12.5px;font-weight:650;color:#26364f}.credit-page .cp-required{color:#e34b5f}
.credit-page .cp-field .form-control{height:41px;border:1px solid var(--border);border-radius:8px;box-shadow:none;color:var(--txt);font-size:13px;padding:9px 12px}
.credit-page .cp-field textarea.form-control{height:auto;min-height:78px;resize:vertical}
.credit-page .cp-field .form-control:focus{border-color:#8aafff;box-shadow:0 0 0 3px #2864f017}
.credit-page .cp-help{display:block;margin-top:5px;font-size:11.5px;color:var(--muted)}
.credit-page .cp-select-wrap{position:relative}.credit-page .cp-select-wrap select{appearance:none;-webkit-appearance:none;padding-right:36px!important;cursor:pointer}
.credit-page .cp-select-wrap:after{content:"";position:absolute;right:15px;top:50%;width:8px;height:8px;border-right:2px solid #64748b;border-bottom:2px solid #64748b;transform:translateY(-70%) rotate(45deg);pointer-events:none}
.credit-page .cp-readonly{background:#f5f7fb!important;color:#586b89!important}
.credit-page .cp-status{display:inline-flex;align-items:center;gap:6px;padding:5px 9px;border-radius:20px;background:#fff5df;color:#976a08;font-size:11px;font-weight:700}
.credit-page .cp-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:15px 22px;background:#fbfcff;border-top:1px solid #edf1f7}
.credit-page .cp-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:40px;padding:9px 16px;border-radius:9px;font-size:13px;font-weight:650;text-decoration:none;cursor:pointer}
.credit-page .cp-btn-back{color:#40516c;border:1px solid #cfd9e8;background:#fff}.credit-page .cp-btn-save{color:#fff;border:1px solid var(--p);background:var(--p);box-shadow:0 5px 12px #2864f02e}.credit-page .cp-btn-save:disabled{opacity:.65;cursor:wait}
.credit-page .cp-table-wrap{overflow-x:auto}.credit-page table{width:100%;border-collapse:collapse;min-width:760px}.credit-page th{background:#f7f9fd;color:#63728b;font-size:11px;text-transform:uppercase;letter-spacing:.35px;font-weight:700;padding:12px 14px;border-bottom:1px solid #e7edf6;white-space:nowrap}
.credit-page td{padding:13px 14px;border-bottom:1px solid #edf1f7;font-size:12.5px;vertical-align:top}.credit-page tbody tr:last-child td{border-bottom:0}
.credit-page .cp-empty{text-align:center;padding:32px 14px;color:var(--muted)}.credit-page .cp-empty i{font-size:25px;color:#a7b4c8;display:block;margin-bottom:9px}
.credit-page .cp-number{font-variant-numeric:tabular-nums;white-space:nowrap}.credit-page .cp-change{font-weight:700;color:#1c8b56}.credit-page .cp-old{color:#7a879a}
.credit-page .cp-alert{border-radius:9px;font-size:13px}
@media(max-width:900px){.credit-page .cp-summary-grid{grid-template-columns:1fr 1fr}.credit-page .cp-customer{display:none}}
@media(max-width:767px){.credit-page .cp-hero{padding:18px}.credit-page .cp-hero h1{font-size:19px}.credit-page .cp-summary-grid,.credit-page .cp-grid{grid-template-columns:1fr}.credit-page .cp-card-body{padding:17px}.credit-page .cp-card-head,.credit-page .cp-footer{padding:14px 16px}.credit-page .cp-footer{align-items:stretch}.credit-page .cp-footer .cp-btn{flex:1}}
</style>

<div class="content-wrapper credit-page">
<section class="content pt-3"><div class="container-fluid">
<nav class="cp-breadcrumb" aria-label="breadcrumb">
<a href="<?= base_url(); ?>"><i class="fas fa-home"></i></a><i class="fas fa-chevron-right" style="font-size:10px"></i>
<span>Master Data</span><i class="fas fa-chevron-right" style="font-size:10px"></i>
<a href="<?= base_url('customer'); ?>">Customer / Supplier</a><i class="fas fa-chevron-right" style="font-size:10px"></i><strong>Credit Limit</strong>
</nav>

<div class="cp-hero">
<div class="cp-hero-icon"><i class="fas fa-wallet"></i></div>
<div><h1>Credit Limit</h1><p>Tetapkan batas kredit dan klasifikasi risiko Customer.</p></div>
<div class="cp-customer"><strong><?= html_escape($custName); ?></strong><span><?= html_escape($cust); ?></span></div>
</div>

<?php if (empty($row) || $custKind !== 'CUSTOMER'): ?>
<div class="alert alert-warning">Data Customer tidak ditemukan atau data ini bukan Customer. Credit Limit hanya tersedia untuk Customer.</div>
<a href="<?= base_url('customer'); ?>" class="cp-btn cp-btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
<?php else: ?>
<div class="cp-summary-grid">
<div class="cp-stat"><div class="cp-stat-label"><i class="fas fa-coins"></i> Batas Kredit Saat Ini</div><div class="cp-stat-value" id="summaryBasic"><?= html_escape(number_format((float)(isset($credit['BASIC_CREDIT']) ? $credit['BASIC_CREDIT'] : 0), 2, ',', '.')); ?></div><div class="cp-stat-note"><?= html_escape($currency); ?> · batas kredit Customer</div></div>
<div class="cp-stat"><div class="cp-stat-label"><i class="fas fa-shield-alt"></i> Risk Class</div><div class="cp-stat-value" id="summaryRisk"><?= html_escape(!empty($credit['RISK_CLASS']) ? $credit['RISK_CLASS'] : 'Belum diatur'); ?></div><div class="cp-stat-note">Klasifikasi risiko Customer</div></div>
</div>

<div class="cp-card">
<div class="cp-card-head"><div><h2><i class="fas fa-sliders-h" style="color:#2864f0;margin-right:7px"></i>Pengaturan Credit Limit</h2><p>Perubahan akan langsung berlaku setelah disimpan.</p></div></div>
<form id="formCreditLimit" autocomplete="off" novalidate>
<div class="cp-card-body"><div id="creditAlert" class="alert cp-alert d-none" role="alert"></div>
<input type="hidden" name="cust" value="<?= html_escape($cust); ?>">
<div class="cp-grid">
<div class="cp-field"><label for="basic_credit_display">Basic Credit <span class="cp-required">*</span></label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text">Rp</span></div><input type="text" inputmode="decimal" id="basic_credit_display" class="form-control" value="<?= html_escape(number_format($basicCredit, 2, ',', '.')); ?>" placeholder="0,00" required><input type="hidden" name="basic_credit" id="basic_credit" value="<?= html_escape(number_format($basicCredit, 2, '.', '')); ?>"></div><small class="cp-help">Format otomatis: 2.000.000,00. Nilai maksimum kredit yang diberikan kepada Customer.</small></div>
<div class="cp-field"><label for="basic_credit_from_date">Tanggal Berlaku Dari <span class="cp-required">*</span></label><input type="date" name="basic_credit_from_date" id="basic_credit_from_date" class="form-control" value="<?= html_escape($basicCreditFrom); ?>" required><small class="cp-help">Tanggal mulai berlakunya batas kredit.</small></div>
<div class="cp-field"><label for="basic_credit_to_date">Tanggal Berlaku Sampai</label><input type="date" name="basic_credit_to_date" id="basic_credit_to_date" class="form-control" value="<?= html_escape($basicCreditTo); ?>"><small class="cp-help">Opsional. Kosongkan jika tidak ada tanggal akhir.</small></div>
<div class="cp-field"><label for="currency">Currency <span class="cp-required">*</span></label><div class="cp-select-wrap"><select name="currency" id="currency" class="form-control" required><option value="IDR" <?= $currency === 'IDR' ? 'selected' : ''; ?>>IDR — Indonesian Rupiah</option><option value="USD" <?= $currency === 'USD' ? 'selected' : ''; ?>>USD — US Dollar</option><option value="EUR" <?= $currency === 'EUR' ? 'selected' : ''; ?>>EUR — Euro</option></select></div></div>
<div class="cp-field"><label for="risk_class">Risk Class</label><div class="cp-select-wrap"><select name="risk_class" id="risk_class" class="form-control"><option value="">-- Pilih Risk Class --</option><?php foreach (array('A','B','C','D','E') as $risk): ?><option value="<?= $risk; ?>" <?= (isset($credit['RISK_CLASS']) && strtoupper($credit['RISK_CLASS']) === $risk) ? 'selected' : ''; ?>><?= $risk; ?></option><?php endforeach; ?></select></div></div>
<div class="cp-field"><label for="terms_of_payment">Terms of Payment (hari)</label><div class="cp-select-wrap"><select name="terms_of_payment" id="terms_of_payment" class="form-control"><option value="">-- Pilih Terms of Payment --</option><?php for ($day = 1; $day <= 90; $day++): ?><option value="<?= $day; ?>" <?= $termsOfPayment === (string)$day ? 'selected' : ''; ?>><?= $day; ?> hari</option><?php endfor; ?></select></div></div>
<div class="cp-field" style="grid-column:1/-1"><label for="remark">Remark / Keterangan</label><textarea name="remark" id="remark" class="form-control" rows="3" placeholder="Tuliskan keterangan atau alasan penetapan/perubahan limit"><?= html_escape(isset($credit['REMARK']) ? $credit['REMARK'] : ''); ?></textarea></div>
</div>
<div class="alert alert-info mt-3 mb-0" style="font-size:12px;border-radius:9px"><i class="fas fa-info-circle mr-1"></i> Batas kredit dan Risk Class langsung berlaku setelah disimpan. Pemakaian kredit dan sisa limit akan dihitung melalui integrasi modul Sales dan CashIn/FIFO.</div>
</div>
<div class="cp-footer"><a href="<?= base_url('customer'); ?>" class="cp-btn cp-btn-back"><i class="fas fa-arrow-left"></i> Kembali</a><button type="submit" id="btnSaveCredit" class="cp-btn cp-btn-save"><i class="far fa-save"></i> Simpan Perubahan</button></div>
</form>
</div>

<div class="cp-card">
  <div class="cp-card-head">
    <div>
      <h2><i class="fas fa-history" style="color:#2864f0;margin-right:7px"></i>Riwayat Credit Limit Sebelumnya</h2>
      <p>Menampilkan nilai Credit Limit sebelum diperbarui.</p>
    </div>
    <?php
      $snapshotHistory = array();
      foreach ($history as $historyItem) {
          if (isset($historyItem['ACTION_TYPE']) && $historyItem['ACTION_TYPE'] === 'CREDIT_LIMIT_SNAPSHOT') {
              $snapshotHistory[] = $historyItem;
          }
      }
    ?>
    <span class="badge badge-light"><?= count($snapshotHistory); ?> riwayat</span>
  </div>
  <div class="cp-table-wrap">
    <table>
      <thead>
        <tr>
          <th>Waktu Perubahan</th>
          <th>Basic Credit Sebelumnya</th>
          <th>From Date</th>
          <th>To Date</th>
          <th>Remark Sebelumnya</th>
          <th>Diubah Oleh</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($snapshotHistory)): ?>
        <tr><td colspan="6"><div class="cp-empty"><i class="fas fa-history"></i>Belum ada riwayat limit sebelumnya. Riwayat akan muncul setelah Credit Limit yang sudah tersimpan diperbarui.</div></td></tr>
      <?php else: foreach ($snapshotHistory as $item): ?>
        <?php
          $snapshot = json_decode(isset($item['REMARK']) ? $item['REMARK'] : '', true);
          $snapshot = is_array($snapshot) ? $snapshot : array();
          $oldBasic = isset($snapshot['BASIC_CREDIT']) ? $snapshot['BASIC_CREDIT'] : (isset($item['OLD_VALUE']) ? $item['OLD_VALUE'] : null);
          $oldFrom = isset($snapshot['BASIC_CREDIT_FROM_DATE']) ? $snapshot['BASIC_CREDIT_FROM_DATE'] : null;
          $oldTo = isset($snapshot['BASIC_CREDIT_TO_DATE']) ? $snapshot['BASIC_CREDIT_TO_DATE'] : null;
          $oldRemark = isset($snapshot['REMARK']) ? $snapshot['REMARK'] : '';
        ?>
        <tr>
          <td><?= html_escape(isset($item['CHANGED_AT']) ? $item['CHANGED_AT'] : '-'); ?></td>
          <td class="cp-number"><?= $oldBasic !== null && $oldBasic !== '' ? html_escape(number_format((float)$oldBasic, 2, ',', '.')) : '-'; ?></td>
          <td><?= html_escape($oldFrom !== '' && $oldFrom !== null ? $oldFrom : '-'); ?></td>
          <td><?= html_escape($oldTo !== '' && $oldTo !== null ? $oldTo : '-'); ?></td>
          <td><?= nl2br(html_escape($oldRemark !== '' && $oldRemark !== null ? $oldRemark : '-')); ?></td>
          <td><?= html_escape(isset($item['CHANGED_BY']) && $item['CHANGED_BY'] !== '' ? $item['CHANGED_BY'] : '-'); ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
</div>
<?php endif; ?>
</div></section></div>

<script>
(function($){'use strict';
var saveUrl=<?= json_encode(base_url('customer/credit-limit/' . rawurlencode($cust))); ?>;
function alertCredit(type,message){$('#creditAlert').removeClass('d-none alert-success alert-danger alert-warning alert-info').addClass('alert-'+type).text(message);$('html,body').animate({scrollTop:$('#creditAlert').offset().top-100},180)}
function formatIDRFromDigits(value){
    value = String(value || '').replace(/[^0-9,]/g, '');
    var parts = value.split(',');
    var integerPart = (parts[0] || '').replace(/^0+(?=\d)/, '');
    if (integerPart === '') integerPart = '0';
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    var decimalPart = typeof parts[1] !== 'undefined' ? parts[1].substring(0, 2) : '';
    return integerPart + (value.indexOf(',') >= 0 ? ',' + decimalPart : '');
}
function parseIDR(value){
    value = String(value || '').replace(/\./g, '').replace(',', '.').replace(/[^0-9.]/g, '');
    var n = parseFloat(value);
    return Number.isFinite(n) ? n : NaN;
}
$('#basic_credit_display').on('input', function(){
    var start = this.selectionStart;
    var before = this.value;
    var formatted = formatIDRFromDigits(before);
    this.value = formatted;
    $('#basic_credit').val(String(parseIDR(formatted)));
    if (this.setSelectionRange) { var diff = formatted.length - before.length; var pos = Math.max(0, start + diff); this.setSelectionRange(pos, pos); }
});
$('#basic_credit_display').on('blur', function(){
    var amount = parseIDR(this.value);
    if (Number.isFinite(amount) && amount >= 0) {
        this.value = amount.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        $('#basic_credit').val(amount.toFixed(2));
    }
});
$('#formCreditLimit').on('submit',function(e){e.preventDefault();$('#creditAlert').addClass('d-none').text('');
var basic=parseIDR($('#basic_credit_display').val());
if(!Number.isFinite(basic)||basic<0){alertCredit('danger','Basic Credit harus berupa angka nol atau lebih.');$('#basic_credit_display').trigger('focus');return}
$('#basic_credit').val(basic.toFixed(2));
var fromDate=$('#basic_credit_from_date').val(),toDate=$('#basic_credit_to_date').val();
if(!fromDate){alertCredit('danger','Tanggal Berlaku Dari wajib diisi.');$('#basic_credit_from_date').trigger('focus');return}
if(toDate && toDate < fromDate){alertCredit('danger','Tanggal Berlaku Sampai tidak boleh lebih awal dari Tanggal Berlaku Dari.');$('#basic_credit_to_date').trigger('focus');return}
var $b=$('#btnSaveCredit'),old=$b.html();$b.prop('disabled',true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
$.ajax({url:saveUrl,type:'POST',data:$(this).serialize(),dataType:'json'}).done(function(r){if(r&&(r.success===true||r.status===true||r.status==='success')){alertCredit('success',r.message||'Batas kredit berhasil disimpan dan langsung berlaku.');setTimeout(function(){window.location.reload()},800);return}alertCredit('danger',r&&r.message?r.message:'Perubahan batas kredit gagal disimpan.');$b.prop('disabled',false).html(old)}).fail(function(xhr){var msg='Terjadi kesalahan saat menyimpan credit limit.';if(xhr.responseJSON&&xhr.responseJSON.message)msg=xhr.responseJSON.message;alertCredit('danger',msg);$b.prop('disabled',false).html(old)});
});
})(jQuery);
</script>
