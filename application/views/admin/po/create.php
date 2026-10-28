<div class="container-fluid po-page py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div><h3 class="mb-1">Tambah Purchase Order</h3><div class="text-muted small">Purchase Order Ayam</div></div>
    <a href="<?= site_url('po') ?>" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
  </div>
  <?php if ($this->session->flashdata('po_error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('po_error')) ?></div><?php endif; ?>
  <?php if (validation_errors()): ?><div class="alert alert-danger"><?= validation_errors() ?></div><?php endif; ?>
  <form method="post" action="<?= site_url('po/create') ?>" id="po-form" autocomplete="off">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <div class="card mb-3"><div class="card-header font-weight-bold">Informasi Purchase Order</div><div class="card-body"><div class="row">
      <div class="form-group col-md-3"><label>No. PO</label><input class="form-control" value="<?= html_escape($header['PO_NO'] ?? 'Dibuat otomatis saat disimpan') ?>" readonly></div>
      <div class="form-group col-md-3"><label>Tanggal PO <span class="text-danger">*</span></label><input type="date" name="PO_DATE" class="form-control" required value="<?= html_escape($header['PO_DATE'] ?? date('Y-m-d')) ?>"></div>
      <div class="form-group col-md-6"><label>Supplier <span class="text-danger">*</span></label><select name="SUPPLIER" class="form-control" required><option value="">-- Pilih Supplier --</option><?php foreach ($suppliers as $s): ?><option value="<?= html_escape($s['CUST']) ?>" <?= (($header['SUPPLIER'] ?? '') === $s['CUST']) ? 'selected' : '' ?>><?= html_escape($s['FULL_NAME']) ?></option><?php endforeach; ?></select></div>
      <div class="form-group col-md-3"><label>Plant</label><input class="form-control" value="0001" readonly><input type="hidden" name="PLANT" value="0001"></div>
      <div class="form-group col-md-3"><label>No. Truck <span class="text-danger">*</span></label><input type="text" name="TRUCK_NO" id="truck-no" class="form-control text-uppercase" maxlength="20" placeholder="Contoh: B 9123 XYZ" required value="<?= html_escape($header['TRUCK_NO'] ?? '') ?>"><small class="text-muted">Format plat Indonesia, contoh B 9123 XYZ.</small></div>
      <div class="form-group col-md-6"><label>Nama Driver <span class="text-danger">*</span></label><input type="text" name="DRIVER_NAME" class="form-control" maxlength="100" required value="<?= html_escape($header['DRIVER_NAME'] ?? '') ?>"></div>
      <div class="form-group col-md-12"><label>Remark</label><textarea name="REMARK" class="form-control" rows="2"><?= html_escape($header['REMARK'] ?? '') ?></textarea></div>
    </div></div></div>
    <div class="card mb-3"><div class="card-header d-flex justify-content-between align-items-center"><strong>Detail Ayam</strong><button type="button" class="btn btn-sm btn-primary" id="add-row"><i class="fa fa-plus"></i> Tambah Baris</button></div><div class="card-body p-2"><div class="table-responsive"><table class="table table-bordered table-sm mb-0" id="detail-table"><thead class="thead-light"><tr><th style="min-width:120px">Basis Harga</th><th style="min-width:160px">Deskripsi</th><th style="min-width:105px">Ekor</th><th style="min-width:105px">Total BW (kg)</th><th style="min-width:110px">Avg BW</th><th style="min-width:140px">Harga</th><th style="min-width:150px">Subtotal</th><th>Remark</th><th></th></tr></thead><tbody id="detail-body"></tbody><tfoot><tr><th colspan="2" class="text-right">TOTAL</th><th id="sum-ekor">0</th><th id="sum-bw">0.000</th><th></th><th></th><th id="sum-total">0</th><th colspan="2"></th></tr></tfoot></table></div></div></div>
    <div class="d-flex justify-content-end mb-4"><a href="<?= site_url('po') ?>" class="btn btn-light mr-2">Batal</a><button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan PO</button></div>
  </form>
</div>
<style>.po-page .card{border:1px solid #e5e7eb;border-radius:8px}.po-page .card-header{background:#f8fafc}.po-page th{white-space:nowrap;font-size:12px}.po-page td{vertical-align:middle}.po-page .form-control{min-width:0}</style>
<script>
(function($){
 const existing = <?= json_encode($details ?? [], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>;
 function num(v){ if(typeof v==='number') return v; v=String(v||'').trim().replace(/\s/g,''); if(v.indexOf(',')>=0 && v.indexOf('.')>=0) v=v.replace(/\./g,'').replace(',','.'); else v=v.replace(',','.'); let n=parseFloat(v); return Number.isFinite(n)?n:0; }
 function fmt(n,d=2){return Number(n||0).toLocaleString('id-ID',{minimumFractionDigits:d,maximumFractionDigits:d});}
 function row(d={}) {
  const basis=d.CALCULATION_BASIS||'EKOR';
  const html=`<tr>
  <td><select class="form-control form-control-sm basis" name="CALCULATION_BASIS[]"><option value="EKOR" ${basis==='EKOR'?'selected':''}>Per Ekor</option><option value="BW" ${basis==='BW'?'selected':''}>Per BW</option></select></td>
  <td><input class="form-control form-control-sm" name="DESCRIPTION[]" maxlength="255" required value="${esc(d.DESCRIPTION||'Ayam')}"></td>
  <td><input type="number" min="1" step="1" class="form-control form-control-sm qty" name="QTY_EKOR[]" required value="${d.QTY_EKOR??''}"></td>
  <td><input type="number" min="0.001" step="0.001" class="form-control form-control-sm bw" name="TOTAL_BW[]" required value="${d.TOTAL_BW??''}"></td>
  <td class="avg text-right">0.000</td>
  <td><input type="number" min="0" step="0.01" class="form-control form-control-sm price" name="PRICE[]" required value="${d.PRICE??''}"></td>
  <td class="subtotal text-right">0</td><td><input class="form-control form-control-sm" name="DETAIL_REMARK[]" maxlength="500" value="${esc(d.REMARK||'')}"></td>
  <td><button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Hapus"><i class="fa fa-trash"></i></button></td></tr>`;
  $('#detail-body').append(html); calculate($('#detail-body tr').last());
 }
 function esc(s){return String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));}
 function calculate($tr){
  let q=num($tr.find('.qty').val()), bw=num($tr.find('.bw').val()), p=num($tr.find('.price').val()), basis=$tr.find('.basis').val();
  let subtotal=basis==='BW'?bw*p:q*p;
  $tr.find('.avg').text(q>0?fmt(bw/q,3):'0.000');
  $tr.find('.subtotal').text(fmt(subtotal,2));
  let tq=0,tb=0,tt=0;$('#detail-body tr').each(function(){tq+=num($(this).find('.qty').val());tb+=num($(this).find('.bw').val());tt+=num($(this).find('.subtotal').text().replace(/\./g,'').replace(',','.'));});
  $('#sum-ekor').text(fmt(tq,0));$('#sum-bw').text(fmt(tb,3));$('#sum-total').text(fmt(tt,2));
 }
 $('#add-row').on('click',()=>row()); $('#detail-body').on('input change','.qty,.bw,.price,.basis',function(){calculate($(this).closest('tr'));});
 $('#detail-body').on('click','.remove-row',function(){if($('#detail-body tr').length>1){$(this).closest('tr').remove();calculate($('#detail-body tr').first());}});
 $('#truck-no').on('input',function(){let v=this.value.toUpperCase().replace(/[^A-Z0-9 ]/g,'').replace(/\s+/g,' ').trim();this.value=v;});
 $('#po-form').on('submit',function(e){
   let plate=$('#truck-no').val().toUpperCase().replace(/\s+/g,' ').trim();
   if(!/^[A-Z]{1,2}\s+\d{1,4}\s+[A-Z]{1,3}$/.test(plate)){e.preventDefault();alert('Format No. Truck belum sesuai. Contoh: B 9123 XYZ.');$('#truck-no').focus();return false;}
   if($('#detail-body tr').length<1){e.preventDefault();alert('Tambahkan minimal satu detail PO.');return false;}
   $('#truck-no').val(plate);
 });
 if(existing.length){existing.forEach(row);} else {row();}
})(jQuery);
</script>
