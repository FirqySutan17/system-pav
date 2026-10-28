<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_model extends CI_Model
{
    private $header_table = 'pav_tr_po';
    private $detail_table = 'pav_tr_po_detail';

    private $allowed_order = [
        'PO_NO'         => 'po.PO_NO',
        'PO_DATE'       => 'po.PO_DATE',
        'SUPPLIER_NAME' => 'supplier.FULL_NAME',
        'TOTAL_EKOR'    => 'TOTAL_EKOR',
        'TOTAL_BW'      => 'TOTAL_BW',
        'TOTAL_AMOUNT'  => 'TOTAL_AMOUNT',
        'STATUS'        => 'po.STATUS'
    ];

    public function get_supplier_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from('pav_cd_customer')
            ->where('CUST_KIND', 'SUPPLIER')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }

    private function apply_filter($filters = [])
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $supplier = trim((string) ($filters['supplier'] ?? ''));
        $date_from = trim((string) ($filters['date_from'] ?? ''));
        $date_to = trim((string) ($filters['date_to'] ?? ''));

        if ($search !== '') {
            $this->db->group_start()
                ->like('po.PO_NO', $search)
                ->or_like('supplier.FULL_NAME', $search)
                ->or_like('po.SUPPLIER', $search)
                ->group_end();
        }

        if ($supplier !== '') {
            $this->db->where('po.SUPPLIER', $supplier);
        }
        if ($this->is_valid_date($date_from)) {
            $this->db->where('po.PO_DATE >=', $date_from);
        }
        if ($this->is_valid_date($date_to)) {
            $this->db->where('po.PO_DATE <=', $date_to);
        }
    }

    private function base_list_query()
    {
        $this->db->from($this->header_table . ' po');
        $this->db->join('pav_cd_customer supplier', 'supplier.CUST = po.SUPPLIER AND supplier.CUST_KIND = "SUPPLIER"', 'left', FALSE);
        $this->db->join(
            '(SELECT PO_NO, SUM(QTY_EKOR) AS TOTAL_EKOR, SUM(TOTAL_BW) AS TOTAL_BW, SUM(SUBTOTAL) AS TOTAL_AMOUNT FROM ' . $this->detail_table . ' GROUP BY PO_NO) detail_totals',
            'detail_totals.PO_NO = po.PO_NO',
            'left',
            FALSE
        );
    }

    public function get_data($limit = 10, $offset = 0, $filters = [], $order = 'PO_DATE', $dir = 'DESC')
    {
        $limit = max(1, min(100, (int) $limit));
        $offset = max(0, (int) $offset);
        $order_key = strtoupper((string) $order);
        $order_col = $this->allowed_order[$order_key] ?? 'po.PO_DATE';
        $dir = strtoupper((string) $dir) === 'ASC' ? 'ASC' : 'DESC';

        $this->base_list_query();
        $this->apply_filter($filters);
        $this->db->select([
            'po.PO_NO', 'po.PO_DATE', 'po.PLANT', 'po.SUPPLIER',
            'supplier.FULL_NAME AS SUPPLIER_NAME', 'po.STATUS',
            'COALESCE(detail_totals.TOTAL_EKOR, 0) AS TOTAL_EKOR',
            'COALESCE(detail_totals.TOTAL_BW, 0) AS TOTAL_BW',
            'COALESCE(detail_totals.TOTAL_AMOUNT, 0) AS TOTAL_AMOUNT'
        ], FALSE);
        $this->db->order_by($order_col, $dir);
        $this->db->order_by('po.PO_NO', 'DESC');
        return $this->db->get('', $limit, $offset)->result_array();
    }

    public function count_data($filters = [])
    {
        $this->base_list_query();
        $this->apply_filter($filters);
        return (int) $this->db->count_all_results();
    }

    public function get_by_pk($po_no)
    {
        $header = $this->db
            ->select('po.*, supplier.FULL_NAME AS SUPPLIER_NAME')
            ->from($this->header_table . ' po')
            ->join('pav_cd_customer supplier', 'supplier.CUST = po.SUPPLIER AND supplier.CUST_KIND = "SUPPLIER"', 'left', FALSE)
            ->where('po.PO_NO', $po_no)
            ->get()->row_array();

        if (!$header) {
            return NULL;
        }

        $header['DETAILS'] = $this->db
            ->where('PO_NO', $po_no)
            ->order_by('LINE_NO', 'ASC')
            ->get($this->detail_table)
            ->result_array();

        return $header;
    }


    public function save_po($header, $details, $po_no = NULL)
    {
        $this->db->trans_begin();
        $now = date('Y-m-d H:i:s');
        if ($po_no === NULL) {
            $po_no = $this->generate_po_no();
            if (!$po_no) { $this->db->trans_rollback(); return ['ok'=>FALSE,'message'=>'Nomor PO tidak dapat dibuat.']; }
            $header['PO_NO'] = $po_no;
            $header['STATUS'] = 'OPEN';
            $header['REG_DATE'] = $now;
            $this->db->insert($this->header_table, $header);
        } else {
            $header['MOD_DATE'] = $now;
            $this->db->where('PO_NO', $po_no)->update($this->header_table, $header);
            $this->db->where('PO_NO', $po_no)->delete($this->detail_table);
        }
        $line = 1;
        foreach ($details as $d) {
            $qty = (int)$d['QTY_EKOR'];
            $bw = (float)$d['TOTAL_BW'];
            $price = (float)$d['PRICE'];
            $basis = $d['CALCULATION_BASIS'];
            $subtotal = $basis === 'BW' ? $bw * $price : $qty * $price;
            $this->db->insert($this->detail_table, [
                'PO_NO'=>$po_no, 'LINE_NO'=>$line++, 'DESCRIPTION'=>$d['DESCRIPTION'],
                'CALCULATION_BASIS'=>$basis, 'QTY_EKOR'=>$qty, 'TOTAL_BW'=>$bw,
                'PRICE'=>$price, 'SUBTOTAL'=>round($subtotal,2), 'REMARK'=>$d['REMARK']
            ]);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['ok'=>FALSE,'message'=>'Gagal menyimpan data PO. Periksa struktur tabel dan data input.'];
        }
        $this->db->trans_commit();
        return ['ok'=>TRUE,'po_no'=>$po_no];
    }

    public function get_supplier_by_code($code)
    {
        return $this->db->where('CUST', $code)->where('CUST_KIND', 'SUPPLIER')->get('pav_cd_customer')->row_array();
    }

    public function generate_po_no()
    {
        $prefix = 'PO' . date('Ym');
        $row = $this->db->select('PO_NO')
            ->from($this->header_table)
            ->like('PO_NO', $prefix, 'after')
            ->order_by('PO_NO', 'DESC')
            ->limit(1)->get()->row_array();

        $last = 0;
        if ($row && preg_match('/^' . preg_quote($prefix, '/') . '(\d{5})$/', $row['PO_NO'], $matches)) {
            $last = (int) $matches[1];
        }
        if ($last >= 99999) {
            return FALSE;
        }
        return $prefix . str_pad((string) ($last + 1), 5, '0', STR_PAD_LEFT);
    }

    private function is_valid_date($date)
    {
        if ($date === '') return FALSE;
        $dt = DateTime::createFromFormat('!Y-m-d', $date);
        return $dt && $dt->format('Y-m-d') === $date;
    }
}
