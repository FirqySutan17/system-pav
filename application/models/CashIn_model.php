<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class CashIn_model extends CI_Model

{

    private $cash_in_table = 'pav_tr_cash_in';

    private $detail_table  = 'pav_tr_cash_in_detail';

    private $order_table   = 'pav_tr_order';

    private $customer_order_table = 'pav_tr_order_customer';

    private $sequence_table = 'pav_tr_order_sequence';



    // =====================================================

    // CUSTOMER OPTIONS

    // =====================================================

    public function get_customer_options()

    {

        return $this->db

            ->select('CUST, FULL_NAME')

            ->from('pav_cd_customer')

            ->where('CUST_KIND', 'CUSTOMER')

            ->where('STATUS', 'ACTIVE')

            ->order_by('FULL_NAME', 'ASC')

            ->get()->result_array();

    }



    // =====================================================

    // ELIGIBLE CUSTOMER ORDERS - NO PLANT FILTER

    // =====================================================

    public function get_customer_orders($customer, $lock = FALSE)

    {

        $sql = "

            SELECT

                oc.ORDER_CUSTOMER_ID,

                oc.ORDER_ID,

                oc.ORDER_CUSTOMER_NO,

                oc.BASIS,

                oc.EKOR,

                oc.KG,

                oc.AVG_BW,

                oc.UNIT_PRICE,

                oc.TOTAL,

                oc.PAID,

                oc.REMAIN,

                oc.PAYMENT_STATUS,

                oc.APPROVAL_STATUS,

                COALESCE(cn.CN_TOTAL_AMOUNT, 0) AS CN_TOTAL_AMOUNT,

                o.ORDER_NO,

                o.ORDER_DATE,

                o.CUST

            FROM {$this->customer_order_table} oc

            INNER JOIN {$this->order_table} o ON o.ORDER_ID = oc.ORDER_ID

            LEFT JOIN (

                SELECT

                    ORDER_CUSTOMER_ID,

                    SUM(CN_CUSTOMER_TOTAL) AS CN_TOTAL_AMOUNT

                FROM pav_tr_order_credit_note

                WHERE APPROVAL_STATUS = 'APPROVED'

                GROUP BY ORDER_CUSTOMER_ID

            ) cn ON cn.ORDER_CUSTOMER_ID = oc.ORDER_CUSTOMER_ID

            WHERE o.CUST = ?

              AND o.STATUS = 'OPEN'

              AND oc.PAYMENT_STATUS IN ('OPEN', 'PARTIAL')

              AND oc.APPROVAL_STATUS IN ('NOT_REQUIRED', 'APPROVED')

              AND oc.REMAIN > 0

            ORDER BY o.ORDER_DATE ASC, oc.ORDER_CUSTOMER_ID ASC

        ";

        if ($lock) $sql .= ' FOR UPDATE';

        return $this->db->query($sql, [$customer])->result_array();

    }



    // =====================================================

    // GENERATE CASH IN NUMBER: CI-YYYYMM-0001

    // =====================================================

    private function generate_cash_in_number($cash_in_date)

    {

        $period = date('Ym', strtotime($cash_in_date));

        $sql = "

            INSERT INTO {$this->sequence_table} (DOC_TYPE, PERIOD, LAST_NUMBER)

            VALUES ('CI', ?, 1)

            ON DUPLICATE KEY UPDATE LAST_NUMBER = LAST_NUMBER + 1

        ";

        if (!$this->db->query($sql, [$period])) return FALSE;



        $row = $this->db->where('DOC_TYPE', 'CI')

            ->where('PERIOD', $period)

            ->get($this->sequence_table)->row_array();

        if (!$row) return FALSE;



        return 'CI-' . $period . '-' . str_pad($row['LAST_NUMBER'], 4, '0', STR_PAD_LEFT);

    }



    // =====================================================

    // CASH IN LIST

    // =====================================================

    public function get_cash_in_list($filters = [])

    {

        $this->db

            ->select("ci.*,

                c.FULL_NAME AS CUSTOMER_NAME,

                GROUP_CONCAT(DISTINCT oc.ORDER_CUSTOMER_NO ORDER BY oc.ORDER_CUSTOMER_NO SEPARATOR ', ') AS ORDER_LIST,

                CASE WHEN EXISTS (

                    SELECT 1

                    FROM pav_tr_cash_in_detail cd_current

                    INNER JOIN pav_tr_cash_in_detail cd_later

                        ON cd_later.ORDER_CUSTOMER_ID = cd_current.ORDER_CUSTOMER_ID

                    INNER JOIN pav_tr_cash_in ci_later

                        ON ci_later.CASH_IN_ID = cd_later.CASH_IN_ID

                    WHERE cd_current.CASH_IN_ID = ci.CASH_IN_ID

                      AND ci_later.CASH_IN_ID > ci.CASH_IN_ID

                      AND ci_later.STATUS = 'POSTED'

                ) THEN 1 ELSE 0 END AS HAS_LATER_CASH_IN", FALSE)

            ->from($this->cash_in_table . ' ci')

            ->join('pav_cd_customer c', 'c.CUST = ci.CUSTOMER', 'left')

            ->join($this->detail_table . ' cid', 'cid.CASH_IN_ID = ci.CASH_IN_ID', 'left')

            ->join($this->customer_order_table . ' oc', 'oc.ORDER_CUSTOMER_ID = cid.ORDER_CUSTOMER_ID', 'left');



        if (!empty($filters['date_from'])) $this->db->where('ci.CASH_IN_DATE >=', $filters['date_from']);

        if (!empty($filters['date_to'])) $this->db->where('ci.CASH_IN_DATE <=', $filters['date_to']);

        if (!empty($filters['customer'])) $this->db->where('ci.CUSTOMER', $filters['customer']);

        if (!empty($filters['method'])) $this->db->where('ci.CASH_IN_METHOD', $filters['method']);

        if (!empty($filters['status'])) $this->db->where('ci.STATUS', $filters['status']);

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $this->db->group_start()

                ->like('ci.CASH_IN_NO', $search)

                ->or_like('ci.SLIP_NO', $search)

                ->or_like('ci.CUSTOMER', $search)

                ->or_like('c.FULL_NAME', $search)

                ->or_like('oc.ORDER_CUSTOMER_NO', $search)

                ->group_end();

        }



        return $this->db->group_by('ci.CASH_IN_ID')

            ->order_by('ci.CASH_IN_DATE', 'DESC')

            ->order_by('ci.CASH_IN_ID', 'DESC')

            ->get()->result_array();

    }



    // =====================================================

    // CASH IN DETAIL

    // =====================================================

    public function get_cash_in_detail($cash_in_id)

    {

        $header = $this->db

            ->select('ci.*, c.FULL_NAME AS CUSTOMER_NAME')

            ->from($this->cash_in_table . ' ci')

            ->join('pav_cd_customer c', 'c.CUST = ci.CUSTOMER', 'left')

            ->where('ci.CASH_IN_ID', (int) $cash_in_id)

            ->get()->row_array();



        if (!$header) {

            return FALSE;

        }



        $header['details'] = $this->db

            ->select("

                cid.*,

                oc.ORDER_CUSTOMER_NO,

                oc.ORDER_ID,

                oc.TOTAL AS ORDER_TOTAL,

                oc.PAID AS CURRENT_PAID,

                oc.REMAIN AS CURRENT_REMAIN,

                oc.PAYMENT_STATUS,

                oc.APPROVAL_STATUS,

                o.ORDER_NO,

                o.ORDER_DATE,

                COALESCE(cn.CN_TOTAL_AMOUNT, 0) AS CN_TOTAL_AMOUNT

            ", FALSE)

            ->from($this->detail_table . ' cid')

            ->join(

                $this->customer_order_table . ' oc',

                'oc.ORDER_CUSTOMER_ID = cid.ORDER_CUSTOMER_ID'

            )

            ->join(

                $this->order_table . ' o',

                'o.ORDER_ID = oc.ORDER_ID'

            )

            ->join(

                "(

                    SELECT

                        ORDER_CUSTOMER_ID,

                        SUM(CN_CUSTOMER_TOTAL) AS CN_TOTAL_AMOUNT

                    FROM pav_tr_order_credit_note

                    WHERE APPROVAL_STATUS = 'APPROVED'

                    GROUP BY ORDER_CUSTOMER_ID

                ) cn",

                'cn.ORDER_CUSTOMER_ID = oc.ORDER_CUSTOMER_ID',

                'left',

                FALSE

            )

            ->where('cid.CASH_IN_ID', (int) $cash_in_id)

            ->order_by('o.ORDER_DATE', 'ASC')

            ->order_by('oc.ORDER_CUSTOMER_ID', 'ASC')

            ->get()->result_array();



        return $header;

    }



    // =====================================================

    // LATER POSTED CASH IN CHECK

    // =====================================================

    public function has_later_posted_cash_in($cash_in_id)

    {

        $sql = "

            SELECT COUNT(*) AS TOTAL

            FROM {$this->detail_table} cd_current

            INNER JOIN {$this->detail_table} cd_later

                ON cd_later.ORDER_CUSTOMER_ID = cd_current.ORDER_CUSTOMER_ID

            INNER JOIN {$this->cash_in_table} ci_later

                ON ci_later.CASH_IN_ID = cd_later.CASH_IN_ID

            WHERE cd_current.CASH_IN_ID = ?

              AND ci_later.CASH_IN_ID > ?

              AND ci_later.STATUS = 'POSTED'

        ";

        $row = $this->db->query($sql, [(int) $cash_in_id, (int) $cash_in_id])->row_array();

        return $row && (int) $row['TOTAL'] > 0;

    }



    // =====================================================

    // BUILD / VALIDATE ALLOCATIONS

    // =====================================================

    private function build_allocations($allocations, $orders, $total_cash_in)

    {

        if (!is_array($allocations) || empty($allocations)) {

            return ['ok' => FALSE, 'message' => 'Allocation Customer Order wajib diisi.'];

        }



        $order_map = [];

        foreach ($orders as $order) $order_map[(int) $order['ORDER_CUSTOMER_ID']] = $order;



        $result = [];

        $seen = [];

        $sum = 0;

        foreach ($allocations as $allocation) {

            $order_id = isset($allocation['ORDER_CUSTOMER_ID']) ? (int) $allocation['ORDER_CUSTOMER_ID'] : 0;

            $amount = isset($allocation['PAID_AMOUNT']) ? (float) $allocation['PAID_AMOUNT'] : 0;

            if ($order_id <= 0 || isset($seen[$order_id])) {

                return ['ok' => FALSE, 'message' => 'Customer Order tidak valid atau terduplikasi.'];

            }

            if (!isset($order_map[$order_id])) {

                return ['ok' => FALSE, 'message' => 'Customer Order tidak eligible untuk Cash In.'];

            }

            if (!is_finite($amount) || $amount <= 0) {

                return ['ok' => FALSE, 'message' => 'Nilai allocation harus lebih besar dari 0.'];

            }

            $remain = (float) $order_map[$order_id]['REMAIN'];

            if ($amount > $remain + 0.00001) {

                return ['ok' => FALSE, 'message' => 'Allocation melebihi sisa tagihan Customer Order ' . $order_map[$order_id]['ORDER_CUSTOMER_NO'] . '.'];

            }

            $seen[$order_id] = TRUE;

            $result[] = ['ORDER_CUSTOMER_ID' => $order_id, 'PAID_AMOUNT' => round($amount, 2)];

            $sum += $amount;

        }



        if (abs($sum - (float) $total_cash_in) > 0.01) {

            return ['ok' => FALSE, 'message' => 'Total allocation harus sama dengan Total Cash In.'];

        }

        return ['ok' => TRUE, 'data' => $result];

    }



    private function update_customer_order_balance($order_customer_id, $amount, $add_paid)

    {

        $amount = round((float) $amount, 2);

        if ($add_paid) {

            $sql = "UPDATE {$this->customer_order_table}

                    SET PAID = PAID + ?, REMAIN = REMAIN - ?,

                        PAYMENT_STATUS = CASE

                            WHEN ROUND(REMAIN - ?, 2) <= 0 THEN 'PAID'

                            WHEN ROUND(PAID + ?, 2) > 0 THEN 'PARTIAL'

                            ELSE 'OPEN' END,

                        UPD_DATE = NOW()

                    WHERE ORDER_CUSTOMER_ID = ? AND REMAIN >= ?";

            return $this->db->query($sql, [$amount, $amount, $amount, $amount, $order_customer_id, $amount]);

        }



        $sql = "UPDATE {$this->customer_order_table}

                SET PAID = GREATEST(PAID - ?, 0), REMAIN = REMAIN + ?,

                    PAYMENT_STATUS = CASE

                        WHEN ROUND(PAID - ?, 2) <= 0 THEN 'OPEN'

                        WHEN ROUND(REMAIN + ?, 2) > 0 THEN 'PARTIAL'

                        ELSE 'PAID' END,

                    UPD_DATE = NOW()

                WHERE ORDER_CUSTOMER_ID = ?";

        return $this->db->query($sql, [$amount, $amount, $amount, $amount, $order_customer_id]);

    }



    // =====================================================

    // SAVE CASH IN

    // =====================================================

    public function save_cash_in($data, $allocations, $user_code)

    {

        $date = isset($data['CASH_IN_DATE']) ? trim($data['CASH_IN_DATE']) : '';

        $customer = isset($data['CUSTOMER']) ? trim($data['CUSTOMER']) : '';

        $method = isset($data['CASH_IN_METHOD']) ? $data['CASH_IN_METHOD'] : '';

        $type = isset($data['CASH_IN_TYPE']) ? $data['CASH_IN_TYPE'] : '';

        $total = isset($data['TOTAL_CASH_IN']) ? (float) $data['TOTAL_CASH_IN'] : 0;



        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4)))

            return ['ok' => FALSE, 'message' => 'Tanggal Cash In tidak valid.'];

        if ($customer === '') return ['ok' => FALSE, 'message' => 'Customer wajib dipilih.'];

        if (!in_array($method, ['CASH', 'TRANSFER'], TRUE)) return ['ok' => FALSE, 'message' => 'Cash In Method tidak valid.'];

        if (!in_array($type, ['FIFO', 'MANUAL'], TRUE)) return ['ok' => FALSE, 'message' => 'Cash In Type tidak valid.'];

        if (!is_finite($total) || $total <= 0) return ['ok' => FALSE, 'message' => 'Total Cash In harus lebih besar dari 0.'];



        $this->db->trans_begin();

        try {

            $customer_row = $this->db->where('CUST', $customer)->where('CUST_KIND', 'CUSTOMER')->where('STATUS', 'ACTIVE')->get('pav_cd_customer')->row_array();

            if (!$customer_row) throw new Exception('Customer tidak ditemukan atau tidak aktif.');



            $orders = $this->get_customer_orders($customer, TRUE);

            $built = $this->build_allocations($allocations, $orders, $total);

            if (!$built['ok']) throw new Exception($built['message']);



            $cash_in_no = $this->generate_cash_in_number($date);

            if (!$cash_in_no) throw new Exception('Gagal membuat nomor Cash In.');



            $header = [

                'CASH_IN_NO' => $cash_in_no,

                'CASH_IN_DATE' => $date,

                'CUSTOMER' => $customer,

                'CASH_IN_METHOD' => $method,

                'CASH_IN_TYPE' => $type,

                'SLIP_NO' => isset($data['SLIP_NO']) ? trim($data['SLIP_NO']) : NULL,

                'TOTAL_CASH_IN' => round($total, 2),

                'REMARK' => isset($data['REMARK']) ? trim($data['REMARK']) : NULL,

                'STATUS' => 'POSTED',

                'REG_EMP' => $user_code,

                'REG_DATE' => date('Y-m-d H:i:s')

            ];

            if (!$this->db->insert($this->cash_in_table, $header)) throw new Exception('Gagal menyimpan Cash In.');

            $cash_in_id = $this->db->insert_id();



            foreach ($built['data'] as $allocation) {

                $detail = [

                    'CASH_IN_ID' => $cash_in_id,

                    'ORDER_CUSTOMER_ID' => $allocation['ORDER_CUSTOMER_ID'],

                    'PAID_AMOUNT' => $allocation['PAID_AMOUNT'],

                    'REG_EMP' => $user_code,

                    'REG_DATE' => date('Y-m-d H:i:s')

                ];

                if (!$this->db->insert($this->detail_table, $detail)) throw new Exception('Gagal menyimpan detail Cash In.');

                if (!$this->update_customer_order_balance($allocation['ORDER_CUSTOMER_ID'], $allocation['PAID_AMOUNT'], TRUE))

                    throw new Exception('Gagal memperbarui saldo Customer Order.');

                if ($this->db->affected_rows() !== 1) throw new Exception('Saldo Customer Order berubah. Silakan refresh dan coba kembali.');

            }



            if ($this->db->trans_status() === FALSE) throw new Exception('Transaksi database gagal.');

            $this->db->trans_commit();

            return ['ok' => TRUE, 'message' => 'Cash In berhasil disimpan.', 'cash_in_id' => $cash_in_id, 'cash_in_no' => $cash_in_no];

        } catch (Exception $e) {

            $this->db->trans_rollback();

            return ['ok' => FALSE, 'message' => $e->getMessage()];

        }

    }



    // =====================================================

    // EDIT ORDER OPTIONS

    // Includes this Cash In's own allocations after virtual reversal.

    // =====================================================

    public function get_edit_customer_orders($cash_in_id)

    {

        $sql = "

            SELECT

                oc.ORDER_CUSTOMER_ID, oc.ORDER_ID, oc.ORDER_CUSTOMER_NO,

                oc.BASIS, oc.EKOR, oc.KG, oc.AVG_BW, oc.UNIT_PRICE, oc.TOTAL,

                (oc.PAID - COALESCE(own.OWN_PAID, 0)) AS PAID,

                (oc.REMAIN + COALESCE(own.OWN_PAID, 0)) AS REMAIN,

                CASE

                    WHEN ROUND(oc.PAID - COALESCE(own.OWN_PAID, 0), 2) <= 0 THEN 'OPEN'

                    WHEN ROUND(oc.REMAIN + COALESCE(own.OWN_PAID, 0), 2) > 0 THEN 'PARTIAL'

                    ELSE 'PAID'

                END AS PAYMENT_STATUS,

                oc.APPROVAL_STATUS, o.ORDER_NO, o.ORDER_DATE, o.CUST,

                COALESCE((

                    SELECT SUM(cn.CN_CUSTOMER_TOTAL)

                    FROM pav_tr_order_credit_note cn

                    WHERE cn.ORDER_CUSTOMER_ID = oc.ORDER_CUSTOMER_ID

                      AND cn.APPROVAL_STATUS = 'APPROVED'

                ), 0) AS CN_TOTAL_AMOUNT

            FROM {$this->customer_order_table} oc

            INNER JOIN {$this->order_table} o ON o.ORDER_ID = oc.ORDER_ID

            INNER JOIN {$this->cash_in_table} ci ON ci.CASH_IN_ID = ? AND ci.CUSTOMER = o.CUST

            LEFT JOIN (

                SELECT ORDER_CUSTOMER_ID, SUM(PAID_AMOUNT) AS OWN_PAID

                FROM {$this->detail_table}

                WHERE CASH_IN_ID = ?

                GROUP BY ORDER_CUSTOMER_ID

            ) own ON own.ORDER_CUSTOMER_ID = oc.ORDER_CUSTOMER_ID

            WHERE o.STATUS = 'OPEN'

              AND oc.APPROVAL_STATUS IN ('NOT_REQUIRED', 'APPROVED')

              AND (
                  oc.PAYMENT_STATUS IN ('OPEN', 'PARTIAL')
                  OR own.OWN_PAID IS NOT NULL
              )

              AND (oc.REMAIN + COALESCE(own.OWN_PAID, 0)) > 0

            ORDER BY o.ORDER_DATE ASC, oc.ORDER_CUSTOMER_ID ASC

            FOR UPDATE

        ";

        return $this->db->query($sql, [(int) $cash_in_id, (int) $cash_in_id])->result_array();

    }



    // =====================================================

    // EDIT CASH IN (customer is immutable)

    // =====================================================

    public function edit_cash_in($cash_in_id, $data, $allocations, $user_code)

    {

        $cash_in_id = (int) $cash_in_id;

        $this->db->trans_begin();

        try {

            $header = $this->db->query("SELECT * FROM {$this->cash_in_table} WHERE CASH_IN_ID = ? FOR UPDATE", [$cash_in_id])->row_array();

            if (!$header || $header['STATUS'] !== 'POSTED') throw new Exception('Cash In tidak ditemukan atau sudah VOID.');

            if ($this->has_later_posted_cash_in($cash_in_id)) throw new Exception('Cash In tidak dapat diedit karena terdapat Cash In POSTED yang lebih baru pada Customer Order terkait.');

            if (isset($data['CUSTOMER']) && trim($data['CUSTOMER']) !== $header['CUSTOMER']) throw new Exception('Customer tidak dapat diubah saat edit Cash In.');



            $date = isset($data['CASH_IN_DATE']) ? trim($data['CASH_IN_DATE']) : '';

            $method = isset($data['CASH_IN_METHOD']) ? $data['CASH_IN_METHOD'] : '';

            $type = isset($data['CASH_IN_TYPE']) ? $data['CASH_IN_TYPE'] : '';

            $total = isset($data['TOTAL_CASH_IN']) ? (float) $data['TOTAL_CASH_IN'] : 0;

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !checkdate((int) substr($date, 5, 2), (int) substr($date, 8, 2), (int) substr($date, 0, 4))) throw new Exception('Tanggal Cash In tidak valid.');

            if (!in_array($method, ['CASH', 'TRANSFER'], TRUE)) throw new Exception('Cash In Method tidak valid.');

            if (!in_array($type, ['FIFO', 'MANUAL'], TRUE)) throw new Exception('Cash In Type tidak valid.');

            if (!is_finite($total) || $total <= 0) throw new Exception('Total Cash In harus lebih besar dari 0.');



            $old_details = $this->db->query(

                "SELECT *

                FROM {$this->detail_table}

                WHERE CASH_IN_ID = ?

                ORDER BY ORDER_CUSTOMER_ID ASC

                FOR UPDATE",

                [$cash_in_id]

            )->result_array();



            // 1. Reversal allocation lama

            foreach ($old_details as $detail) {

                if (!$this->update_customer_order_balance(

                    $detail['ORDER_CUSTOMER_ID'],

                    $detail['PAID_AMOUNT'],

                    FALSE

                )) {

                    throw new Exception('Gagal melakukan reversal allocation lama.');

                }

            }



            // 2. Hapus detail lama di dalam transaction

            if (!$this->db

                ->where('CASH_IN_ID', $cash_in_id)

                ->delete($this->detail_table)

            ) {

                throw new Exception('Gagal menghapus allocation lama.');

            }



            // 3. Ambil saldo setelah reversal.

            // Detail lama sudah dihapus sehingga tidak terjadi virtual reversal kedua.

            $orders = $this->get_edit_customer_orders($cash_in_id);



            // 4. Validasi allocation baru

            $built = $this->build_allocations(

                $allocations,

                $orders,

                $total

            );



            if (!$built['ok']) {

                throw new Exception($built['message']);

            }



            foreach ($built['data'] as $allocation) {

                $detail = [

                    'CASH_IN_ID' => $cash_in_id,

                    'ORDER_CUSTOMER_ID' => $allocation['ORDER_CUSTOMER_ID'],

                    'PAID_AMOUNT' => $allocation['PAID_AMOUNT'],

                    'REG_EMP' => $user_code,

                    'REG_DATE' => date('Y-m-d H:i:s')

                ];

                if (!$this->db->insert($this->detail_table, $detail)) throw new Exception('Gagal menyimpan allocation baru.');

                if (!$this->update_customer_order_balance($allocation['ORDER_CUSTOMER_ID'], $allocation['PAID_AMOUNT'], TRUE)) throw new Exception('Gagal memperbarui saldo Customer Order.');

                if ($this->db->affected_rows() !== 1) throw new Exception('Saldo Customer Order berubah. Silakan refresh dan coba kembali.');

            }



            $update = [

                'CASH_IN_DATE' => $date,

                'CASH_IN_METHOD' => $method,

                'CASH_IN_TYPE' => $type,

                'SLIP_NO' => isset($data['SLIP_NO']) ? trim($data['SLIP_NO']) : NULL,

                'TOTAL_CASH_IN' => round($total, 2),

                'REMARK' => isset($data['REMARK']) ? trim($data['REMARK']) : NULL,

                'UPD_EMP' => $user_code,

                'UPD_DATE' => date('Y-m-d H:i:s')

            ];

            if (!$this->db->where('CASH_IN_ID', $cash_in_id)->update($this->cash_in_table, $update)) throw new Exception('Gagal memperbarui Cash In.');

            if ($this->db->trans_status() === FALSE) throw new Exception('Transaksi database gagal.');

            $this->db->trans_commit();

            return ['ok' => TRUE, 'message' => 'Cash In berhasil diperbarui.'];

        } catch (Exception $e) {

            $this->db->trans_rollback();

            return ['ok' => FALSE, 'message' => $e->getMessage()];

        }

    }



    // =====================================================

    // VOID CASH IN - KEEP DETAIL HISTORY

    // =====================================================

    public function void_cash_in($cash_in_id, $user_code)

    {

        $cash_in_id = (int) $cash_in_id;

        $this->db->trans_begin();

        try {

            $header = $this->db->query("SELECT * FROM {$this->cash_in_table} WHERE CASH_IN_ID = ? FOR UPDATE", [$cash_in_id])->row_array();

            if (!$header || $header['STATUS'] !== 'POSTED') throw new Exception('Cash In tidak ditemukan atau sudah VOID.');

            if ($this->has_later_posted_cash_in($cash_in_id)) throw new Exception('Cash In tidak dapat di-VOID karena terdapat Cash In POSTED yang lebih baru pada Customer Order terkait.');



            $details = $this->db->query("SELECT * FROM {$this->detail_table} WHERE CASH_IN_ID = ? ORDER BY ORDER_CUSTOMER_ID ASC FOR UPDATE", [$cash_in_id])->result_array();

            foreach ($details as $detail) {

                $this->db->query("SELECT ORDER_CUSTOMER_ID FROM {$this->customer_order_table} WHERE ORDER_CUSTOMER_ID = ? FOR UPDATE", [$detail['ORDER_CUSTOMER_ID']]);

                if (!$this->update_customer_order_balance($detail['ORDER_CUSTOMER_ID'], $detail['PAID_AMOUNT'], FALSE)) throw new Exception('Gagal mengembalikan saldo Customer Order.');

            }



            $updated = $this->db->where('CASH_IN_ID', $cash_in_id)->where('STATUS', 'POSTED')->update($this->cash_in_table, [

                'STATUS' => 'VOID', 'UPD_EMP' => $user_code, 'UPD_DATE' => date('Y-m-d H:i:s')

            ]);

            if (!$updated || $this->db->affected_rows() !== 1) throw new Exception('Gagal mengubah status Cash In menjadi VOID.');

            if ($this->db->trans_status() === FALSE) throw new Exception('Transaksi database gagal.');

            $this->db->trans_commit();

            return ['ok' => TRUE, 'message' => 'Cash In berhasil di-VOID.'];

        } catch (Exception $e) {

            $this->db->trans_rollback();

            return ['ok' => FALSE, 'message' => $e->getMessage()];

        }

    }

}
