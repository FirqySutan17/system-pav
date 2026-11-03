
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_note_model extends CI_Model
{
    private $table = 'pav_tr_order_credit_note';
    private $customer_order_table = 'pav_tr_order_customer';
    private $supplier_order_table = 'pav_tr_order_supplier';
    private $order_table = 'pav_tr_order';

    /*
    |--------------------------------------------------------------------------
    | CREDIT NOTE LIST
    |--------------------------------------------------------------------------
    */

    public function get_list($filters = [])
    {
        $this->db->select("
            cn.*,
            COALESCE(oc.ORDER_CUSTOMER_NO, os.ORDER_SUPPLIER_NO) AS ORDER_DETAIL_NO,
            oc.ORDER_CUSTOMER_NO,
            os.ORDER_SUPPLIER_NO,
            o.ORDER_NO,
            o.ORDER_DATE,
            o.PLANT,
            o.CUST,
            o.SUPPLIER,
            c.FULL_NAME AS CUSTOMER_NAME,
            s.FULL_NAME AS SUPPLIER_NAME,
            CASE
                WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL AND cn.ORDER_SUPPLIER_ID IS NOT NULL THEN 'CUSTOMER_SUPPLIER'
                WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL THEN 'CUSTOMER'
                ELSE 'SUPPLIER'
            END AS ORDER_TYPE
        ", FALSE);

        $this->db->from($this->table . ' cn');
        $this->db->join(
            $this->customer_order_table . ' oc',
            'oc.ORDER_CUSTOMER_ID = cn.ORDER_CUSTOMER_ID',
            'left'
        );
        $this->db->join(
            $this->supplier_order_table . ' os',
            'os.ORDER_SUPPLIER_ID = cn.ORDER_SUPPLIER_ID',
            'left'
        );
        $this->db->join(
            $this->order_table . ' o',
            'o.ORDER_ID = COALESCE(oc.ORDER_ID, os.ORDER_ID)',
            'left',
            FALSE
        );
        $this->db->join(
            'pav_cd_customer c',
            'c.CUST = o.CUST',
            'left'
        );
        $this->db->join(
            'pav_cd_customer s',
            's.CUST = o.SUPPLIER',
            'left'
        );

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $this->db->group_start()
                ->like('cn.CN_NO', $search)
                ->or_like('o.ORDER_NO', $search)
                ->or_like('oc.ORDER_CUSTOMER_NO', $search)
                ->or_like('os.ORDER_SUPPLIER_NO', $search)
                ->or_like('c.FULL_NAME', $search)
                ->or_like('s.FULL_NAME', $search)
                ->group_end();
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('cn.CN_DATE >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('cn.CN_DATE <=', $filters['date_to']);
        }

        if (!empty($filters['order_type'])) {
            if ($filters['order_type'] === 'CUSTOMER') {
                $this->db->where('cn.ORDER_CUSTOMER_ID IS NOT NULL', NULL, FALSE)
                    ->where('cn.ORDER_SUPPLIER_ID IS NULL', NULL, FALSE);
            } elseif ($filters['order_type'] === 'SUPPLIER') {
                $this->db->where('cn.ORDER_SUPPLIER_ID IS NOT NULL', NULL, FALSE)
                    ->where('cn.ORDER_CUSTOMER_ID IS NULL', NULL, FALSE);
            } elseif ($filters['order_type'] === 'CUSTOMER_SUPPLIER') {
                $this->db->where('cn.ORDER_CUSTOMER_ID IS NOT NULL', NULL, FALSE)
                    ->where('cn.ORDER_SUPPLIER_ID IS NOT NULL', NULL, FALSE);
            }
        }

        if (!empty($filters['status'])) {
            $this->db->where('cn.APPROVAL_STATUS', $filters['status']);
        }

        $this->db->order_by('cn.CREDIT_NOTE_ID', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        $this->db->select("
            cn.*,
            COALESCE(oc.ORDER_CUSTOMER_NO, os.ORDER_SUPPLIER_NO) AS ORDER_DETAIL_NO,
            oc.ORDER_CUSTOMER_NO,
            os.ORDER_SUPPLIER_NO,
            oc.BASIS AS CUSTOMER_BASIS,
            oc.EKOR AS CUSTOMER_EKOR,
            oc.KG AS CUSTOMER_KG,
            oc.TOTAL AS CUSTOMER_ORDER_TOTAL,
            oc.REMAIN AS CUSTOMER_REMAIN,
            os.BASIS AS SUPPLIER_BASIS,
            os.EKOR AS SUPPLIER_EKOR,
            os.KG AS SUPPLIER_KG,
            os.TOTAL AS SUPPLIER_ORDER_TOTAL,
            os.REMAIN AS SUPPLIER_REMAIN,
            o.ORDER_NO,
            o.ORDER_DATE,
            o.PLANT,
            o.CUST,
            o.SUPPLIER,
            c.FULL_NAME AS CUSTOMER_NAME,
            s.FULL_NAME AS SUPPLIER_NAME,
            COALESCE(oc.BASIS, os.BASIS) AS BASIS,
            COALESCE(oc.EKOR, os.EKOR) AS EKOR,
            COALESCE(oc.KG, os.KG) AS KG,
            COALESCE(oc.AVG_BW, os.AVG_BW) AS AVG_BW,
            COALESCE(oc.UNIT_PRICE, os.UNIT_PRICE) AS ORDER_PRICE,
            COALESCE(oc.TOTAL, os.TOTAL) AS ORDER_TOTAL,
            CASE
                WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL AND cn.ORDER_SUPPLIER_ID IS NOT NULL THEN 'CUSTOMER_SUPPLIER'
                WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL THEN 'CUSTOMER'
                ELSE 'SUPPLIER'
            END AS ORDER_TYPE
        ", FALSE);

        $this->db->from($this->table . ' cn');
        $this->db->join($this->customer_order_table . ' oc', 'oc.ORDER_CUSTOMER_ID = cn.ORDER_CUSTOMER_ID', 'left');
        $this->db->join($this->supplier_order_table . ' os', 'os.ORDER_SUPPLIER_ID = cn.ORDER_SUPPLIER_ID', 'left');
        $this->db->join($this->order_table . ' o', 'o.ORDER_ID = COALESCE(oc.ORDER_ID, os.ORDER_ID)', 'left', FALSE);
        $this->db->join('pav_cd_customer c', 'c.CUST = o.CUST', 'left');
        $this->db->join('pav_cd_customer s', 's.CUST = o.SUPPLIER', 'left');

        return $this->db
            ->where('cn.CREDIT_NOTE_ID', $id)
            ->get()
            ->row_array();
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER SELECTION
    |--------------------------------------------------------------------------
    */

    public function get_order_options($type, $search = '')
    {
        if (!in_array($type, ['CUSTOMER', 'SUPPLIER'], TRUE)) {
            return [];
        }

        if ($type === 'CUSTOMER') {
            $detail_table = $this->customer_order_table;
            $detail_id = 'ORDER_CUSTOMER_ID';
            $detail_no = 'ORDER_CUSTOMER_NO';
            $customer_join = 'LEFT JOIN pav_cd_customer c ON c.CUST = o.CUST';
            $name_field = 'c.FULL_NAME';
            $existing_cn = 'cn.ORDER_CUSTOMER_ID = d.ORDER_CUSTOMER_ID';
        } else {
            $detail_table = $this->supplier_order_table;
            $detail_id = 'ORDER_SUPPLIER_ID';
            $detail_no = 'ORDER_SUPPLIER_NO';
            $customer_join = 'LEFT JOIN pav_cd_customer c ON c.CUST = o.SUPPLIER';
            $name_field = 'c.FULL_NAME';
            $existing_cn = 'cn.ORDER_SUPPLIER_ID = d.ORDER_SUPPLIER_ID';
        }

        $sql = "
            SELECT
                d.{$detail_id} AS ORDER_DETAIL_ID,
                d.{$detail_no} AS ORDER_DETAIL_NO,
                d.BASIS,
                d.EKOR,
                d.KG,
                d.AVG_BW,
                d.UNIT_PRICE,
                d.TOTAL,
                o.ORDER_ID,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                {$name_field} AS PARTY_NAME
            FROM {$detail_table} d
            INNER JOIN {$this->order_table} o
                ON o.ORDER_ID = d.ORDER_ID
            {$customer_join}
            LEFT JOIN {$this->table} cn
                ON {$existing_cn}
            WHERE cn.CREDIT_NOTE_ID IS NULL
        ";

        $params = [];

        if ($search !== '') {
            $sql .= "
                AND (
                    o.ORDER_NO LIKE ?
                    OR d.{$detail_no} LIKE ?
                    OR {$name_field} LIKE ?
                )
            ";

            $keyword = '%' . $search . '%';
            $params = [$keyword, $keyword, $keyword];
        }

        $sql .= " ORDER BY o.ORDER_DATE DESC, o.ORDER_ID DESC LIMIT 100";

        return $this->db->query($sql, $params)->result_array();
    }

    public function get_order_detail($type, $detail_id)
    {
        if ($type === 'CUSTOMER') {
            $table = $this->customer_order_table;
            $id_field = 'ORDER_CUSTOMER_ID';
            $number_field = 'ORDER_CUSTOMER_NO';
            $party_join = 'LEFT JOIN pav_cd_customer c ON c.CUST = o.CUST';
            $party_field = 'c.FULL_NAME';
        } elseif ($type === 'SUPPLIER') {
            $table = $this->supplier_order_table;
            $id_field = 'ORDER_SUPPLIER_ID';
            $number_field = 'ORDER_SUPPLIER_NO';
            $party_join = 'LEFT JOIN pav_cd_customer c ON c.CUST = o.SUPPLIER';
            $party_field = 'c.FULL_NAME';
        } else {
            return NULL;
        }

        $sql = "
            SELECT
                d.{$id_field} AS ORDER_DETAIL_ID,
                d.{$number_field} AS ORDER_DETAIL_NO,
                d.BASIS,
                d.EKOR,
                d.KG,
                d.AVG_BW,
                d.UNIT_PRICE,
                d.TOTAL,
                o.ORDER_ID,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                {$party_field} AS PARTY_NAME
            FROM {$table} d
            INNER JOIN {$this->order_table} o
                ON o.ORDER_ID = d.ORDER_ID
            {$party_join}
            WHERE d.{$id_field} = ?
            LIMIT 1
        ";

        return $this->db->query($sql, [$detail_id])->row_array();
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE / UPDATE
    |--------------------------------------------------------------------------
    */
    
    public function save($data, $id = NULL, $user_code = NULL)
    {
        $type = strtoupper(trim($data['ORDER_TYPE'] ?? ''));
        $cn_date = trim($data['CN_DATE'] ?? '');
        $cn_price = isset($data['CN_PRICE']) ? (float) $data['CN_PRICE'] : -1;
        $remark = trim($data['REMARK'] ?? '');

        $customer_id = (int) ($data['ORDER_CUSTOMER_ID'] ?? 0);
        $supplier_id = (int) ($data['ORDER_SUPPLIER_ID'] ?? 0);

        // Backward compatibility for the previous single-order form.
        if ($type === 'CUSTOMER' && !$customer_id) {
            $customer_id = (int) ($data['ORDER_DETAIL_ID'] ?? 0);
        }

        if ($type === 'SUPPLIER' && !$supplier_id) {
            $supplier_id = (int) ($data['ORDER_DETAIL_ID'] ?? 0);
        }

        if (!in_array($type, ['CUSTOMER', 'SUPPLIER', 'CUSTOMER_SUPPLIER'], TRUE)) {
            return ['ok' => FALSE, 'message' => 'Tipe Order tidak valid.'];
        }

        if (
            ($type === 'CUSTOMER' && (!$customer_id || $supplier_id)) ||
            ($type === 'SUPPLIER' && (!$supplier_id || $customer_id)) ||
            ($type === 'CUSTOMER_SUPPLIER' && (!$customer_id || !$supplier_id))
        ) {
            return ['ok' => FALSE, 'message' => 'Pilihan Order belum lengkap atau tidak sesuai tipe.'];
        }

        if (!$this->is_valid_date($cn_date)) {
            return ['ok' => FALSE, 'message' => 'Tanggal Credit Note tidak valid.'];
        }

        if ($cn_price < 0) {
            return ['ok' => FALSE, 'message' => 'CN Price tidak boleh negatif.'];
        }

        $this->db->trans_begin();

        $customer = NULL;
        $supplier = NULL;

        // ==========================================
        // GET CUSTOMER ORDER
        // ==========================================
        if ($customer_id) {
            $customer = $this->db->query(
                "SELECT *
                FROM {$this->customer_order_table}
                WHERE ORDER_CUSTOMER_ID = ?
                LIMIT 1 FOR UPDATE",
                [$customer_id]
            )->row_array();

            if (!$customer) {
                $this->db->trans_rollback();
                return ['ok' => FALSE, 'message' => 'Customer Order tidak ditemukan.'];
            }
        }

        // ==========================================
        // GET SUPPLIER ORDER
        // ==========================================
        if ($supplier_id) {
            $supplier = $this->db->query(
                "SELECT *
                FROM {$this->supplier_order_table}
                WHERE ORDER_SUPPLIER_ID = ?
                LIMIT 1 FOR UPDATE",
                [$supplier_id]
            )->row_array();

            if (!$supplier) {
                $this->db->trans_rollback();
                return ['ok' => FALSE, 'message' => 'Supplier Order tidak ditemukan.'];
            }
        }

        // ==========================================
        // VALIDATE MASTER ORDER
        // ==========================================
        $master_order_id = (int) (
            $customer['ORDER_ID'] ?? ($supplier['ORDER_ID'] ?? 0)
        );

        if (
            $customer &&
            $supplier &&
            (int) $customer['ORDER_ID'] !== (int) $supplier['ORDER_ID']
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Customer Order dan Supplier Order harus berasal dari Master Order yang sama.'
            ];
        }

        if ($master_order_id <= 0) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Master Order tidak valid.'];
        }

        // ==========================================
        // VALIDATE EXISTING CREDIT NOTE
        // ==========================================
        if ($id) {
            $existing = $this->db->query(
                "SELECT *
                FROM {$this->table}
                WHERE CREDIT_NOTE_ID = ?
                LIMIT 1 FOR UPDATE",
                [(int) $id]
            )->row_array();

            if (!$existing) {
                $this->db->trans_rollback();
                return ['ok' => FALSE, 'message' => 'Credit Note tidak ditemukan.'];
            }

            if (strtoupper($existing['APPROVAL_STATUS']) !== 'PENDING') {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Hanya Credit Note berstatus PENDING yang dapat diedit.'
                ];
            }
        }

        // ==========================================
        // CHECK DUPLICATE CREDIT NOTE
        // ==========================================
        foreach (
            [
                [$customer_id, 'ORDER_CUSTOMER_ID'],
                [$supplier_id, 'ORDER_SUPPLIER_ID']
            ] as $check
        ) {
            if (!$check[0]) {
                continue;
            }

            $this->db->from($this->table)
                ->where($check[1], $check[0]);

            if ($id) {
                $this->db->where('CREDIT_NOTE_ID !=', (int) $id);
            }

            if ($this->db->count_all_results() > 0) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Order yang dipilih sudah memiliki Credit Note.'
                ];
            }
        }

        // ==========================================
        // CALCULATE CREDIT NOTE TOTAL
        // ==========================================
        $calc = function ($order) use ($cn_price) {
            if (!$order) {
                return 0.00;
            }

            $basis = strtoupper(trim($order['BASIS'] ?? ''));

            if ($basis === 'EKOR') {
                $qty = (float) ($order['EKOR'] ?? 0);
            } elseif ($basis === 'BW') {
                $qty = (float) ($order['KG'] ?? 0);
            } else {
                return NULL;
            }

            return round($cn_price * $qty, 2);
        };

        $customer_total = $calc($customer);
        $supplier_total = $calc($supplier);

        if ($customer_total === NULL || $supplier_total === NULL) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Basis Order tidak dikenal.'];
        }

        // ==========================================
        // VALIDATE CUSTOMER REMAIN
        // ==========================================
        if ($customer) {
            $limit = ($customer['REMAIN'] !== NULL)
                ? (float) $customer['REMAIN']
                : (float) $customer['TOTAL'];

            if ($customer_total > $limit + 0.00001) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'CN Total Customer melebihi sisa Customer Order.'
                ];
            }
        }

        // ==========================================
        // VALIDATE SUPPLIER REMAIN
        // ==========================================
        if ($supplier) {
            $limit = ($supplier['REMAIN'] !== NULL)
                ? (float) $supplier['REMAIN']
                : (float) $supplier['TOTAL'];

            if ($supplier_total > $limit + 0.00001) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'CN Total Supplier melebihi sisa Supplier Order.'
                ];
            }
        }

        // ==========================================
        // CREDIT NOTE PAYLOAD
        // ==========================================
        $now = date('Y-m-d H:i:s');

        $payload = [
            'ORDER_CUSTOMER_ID' => $customer_id ?: NULL,
            'ORDER_SUPPLIER_ID' => $supplier_id ?: NULL,
            'CN_DATE' => $cn_date,
            'CN_PRICE' => $cn_price,
            'CN_CUSTOMER_TOTAL' => $customer_total,
            'CN_SUPPLIER_TOTAL' => $supplier_total,
            'CN_TOTAL' => round($customer_total + $supplier_total, 2),
            'REMARK' => $remark,
            'APPROVAL_STATUS' => 'PENDING',
            'REQUESTED_BY' => $user_code,
            'REQUESTED_AT' => $now
        ];

        // ==========================================
        // INSERT / UPDATE CREDIT NOTE
        // ==========================================
        if ($id) {

            // UPDATE EXISTING CREDIT NOTE
            $credit_note_id = (int) $id;

            $this->db->where('CREDIT_NOTE_ID', $credit_note_id)
                ->update($this->table, $payload);

        } else {

            // INSERT NEW CREDIT NOTE WITH TEMPORARY NUMBER
            $payload['CN_NO'] = 'TMP-' . strtoupper(bin2hex(random_bytes(8)));

            $insert_result = $this->db->insert($this->table, $payload);
            $insert_error = $this->db->error();

            if (!$insert_result) {
                log_message(
                    'error',
                    '[CREDIT NOTE INSERT] Failed: ' .
                    json_encode($insert_error) .
                    ' | Query: ' . $this->db->last_query()
                );

                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Gagal insert Credit Note. Periksa application/logs.'
                ];
            }

            // GET AUTO INCREMENT ID
            $credit_note_id = (int) $this->db->insert_id();

            if ($credit_note_id <= 0) {
                $db_error = $this->db->error();

                log_message(
                    'error',
                    '[CREDIT NOTE ID] Invalid insert_id: ' .
                    json_encode($db_error) .
                    ' | Query: ' . $this->db->last_query()
                );

                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Credit Note ID tidak berhasil dibuat. Periksa application/logs.'
                ];
            }

            // ==========================================
            // GENERATE FINAL CREDIT NOTE NUMBER
            // FORMAT: CN-YYYYMM-XXXXXX
            // ==========================================
            $cn_no = 'CN-' .
                date('Ym', strtotime($cn_date)) .
                '-' .
                str_pad($credit_note_id, 6, '0', STR_PAD_LEFT);

            $number_update_result = $this->db
                ->where('CREDIT_NOTE_ID', $credit_note_id)
                ->update($this->table, [
                    'CN_NO' => $cn_no
                ]);

            if (!$number_update_result) {
                $db_error = $this->db->error();

                log_message(
                    'error',
                    '[CREDIT NOTE NUMBER] Failed: ' .
                    json_encode($db_error) .
                    ' | Query: ' . $this->db->last_query()
                );

                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Gagal membuat nomor Credit Note. Periksa application/logs.'
                ];
            }
        }

        // ==========================================
        // VALIDATE CREDIT NOTE ID
        // ==========================================
        if ($credit_note_id <= 0) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Credit Note ID tidak valid.'
            ];
        }

        // ==========================================
        // GET EXISTING APPROVAL
        // ==========================================
        $approval = $this->db
            ->where('APPROVAL_TYPE', 'CREDIT_NOTE')
            ->where('REFERENCE_ID', $credit_note_id)
            ->limit(1)
            ->get('pav_tr_order_approval')
            ->row_array();

        // ==========================================
        // APPROVAL PAYLOAD
        // ==========================================
        $approval_payload = [
            'ORDER_ID' => $master_order_id,
            'APPROVAL_TYPE' => 'CREDIT_NOTE',
            'REFERENCE_ID' => $credit_note_id,
            'APPROVAL_STATUS' => 'PENDING',
            'REQUESTED_BY' => $user_code,
            'REQUESTED_AT' => $now,
            'APPROVED_BY' => NULL,
            'APPROVED_AT' => NULL,
            'APPROVAL_REMARK' => NULL
        ];

        if ($approval) {

            $this->db
                ->where('APPROVAL_ID', (int) $approval['APPROVAL_ID'])
                ->update('pav_tr_order_approval', $approval_payload);

        } else {

            $this->db->insert('pav_tr_order_approval', $approval_payload);
        }

        // ==========================================
        // FINAL TRANSACTION VALIDATION
        // ==========================================
        if ($this->db->trans_status() === FALSE) {
            $db_error = $this->db->error();

            log_message(
                'error',
                '[CREDIT NOTE TRANSACTION] Failed: ' .
                json_encode($db_error) .
                ' | Query: ' . $this->db->last_query()
            );

            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Credit Note dan approval request. Periksa application/logs.'
            ];
        }

        // ==========================================
        // COMMIT
        // ==========================================
        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'credit_note_id' => $credit_note_id,
            'message' => $id
                ? 'Credit Note berhasil diperbarui.'
                : 'Credit Note berhasil dibuat.'
        ];
    }

    public function get_pending_approvals()
    {
        return $this->db
            ->select("
                a.APPROVAL_ID,
                a.ORDER_ID,
                a.REFERENCE_ID,
                a.APPROVAL_TYPE,
                a.APPROVAL_STATUS,
                a.REQUESTED_BY,
                a.REQUESTED_AT,

                cn.CREDIT_NOTE_ID,
                cn.CN_NO,
                cn.CN_DATE,
                cn.CN_PRICE,
                cn.CN_CUSTOMER_TOTAL,
                cn.CN_SUPPLIER_TOTAL,
                cn.CN_TOTAL,

                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                o.CUST,
                o.SUPPLIER,

                CASE
                    WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL
                        AND cn.ORDER_SUPPLIER_ID IS NOT NULL
                        THEN 'CUSTOMER_SUPPLIER'
                    WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL
                        THEN 'CUSTOMER'
                    ELSE 'SUPPLIER'
                END AS ORDER_TYPE,

                oc.ORDER_CUSTOMER_NO,
                os.ORDER_SUPPLIER_NO,

                COALESCE(c.FULL_NAME, '') AS CUSTOMER_NAME,
                COALESCE(s.FULL_NAME, '') AS SUPPLIER_NAME

            ", FALSE)
            ->from('pav_tr_order_approval a')
            ->join(
                $this->table . ' cn',
                'cn.CREDIT_NOTE_ID = a.REFERENCE_ID',
                'inner'
            )
            ->join(
                $this->customer_order_table . ' oc',
                'oc.ORDER_CUSTOMER_ID = cn.ORDER_CUSTOMER_ID',
                'left'
            )
            ->join(
                $this->supplier_order_table . ' os',
                'os.ORDER_SUPPLIER_ID = cn.ORDER_SUPPLIER_ID',
                'left'
            )
            ->join(
                $this->order_table . ' o',
                'o.ORDER_ID = a.ORDER_ID',
                'inner'
            )
            ->join(
                'pav_cd_customer c',
                'c.CUST = o.CUST',
                'left'
            )
            ->join(
                'pav_cd_customer s',
                's.CUST = o.SUPPLIER',
                'left'
            )
            ->where('a.APPROVAL_TYPE', 'CREDIT_NOTE')
            ->where('a.APPROVAL_STATUS', 'PENDING')
            ->order_by('a.REQUESTED_AT', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_approval_detail($approval_id)
    {
        $approval = $this->db
            ->where('APPROVAL_ID', (int) $approval_id)
            ->where('APPROVAL_TYPE', 'CREDIT_NOTE')
            ->get('pav_tr_order_approval')
            ->row_array();

        if (!$approval) {
            return NULL;
        }

        $cn = $this->get_by_id((int) $approval['REFERENCE_ID']);
        if (!$cn) {
            return NULL;
        }

        $master = $this->db
            ->where('ORDER_ID', (int) $approval['ORDER_ID'])
            ->get($this->order_table)
            ->row_array();

        if (!$master) {
            return NULL;
        }

        return [
            'approval' => $approval,
            'credit_note' => $cn,
            'master' => $master
        ];
    }

    public function process_approval($approval_id, $decision, $remark, $user_code)
    {
        $approval_id = (int) $approval_id;
        $decision = strtoupper(trim($decision));
        $remark = trim((string) $remark);
        $user_code = trim((string) $user_code);

        if ($approval_id <= 0) {
            return ['ok' => FALSE, 'message' => 'Approval ID tidak valid.'];
        }

        if (!in_array($decision, ['APPROVED', 'REJECTED'], TRUE)) {
            return ['ok' => FALSE, 'message' => 'Keputusan approval tidak valid.'];
        }

        if ($remark === '') {
            return ['ok' => FALSE, 'message' => 'Remark wajib diisi.'];
        }

        // if (!in_array(strtolower($user_code), ['pavamir', 'pavthomas'], TRUE)) {
        //     return ['ok' => FALSE, 'message' => 'Anda tidak memiliki akses untuk approval Credit Note.'];
        // }

        $this->db->trans_begin();

        // Lock the approval row so two approvers cannot decide simultaneously.
        $approval = $this->db->query(
            "SELECT * FROM pav_tr_order_approval
             WHERE APPROVAL_ID = ? AND APPROVAL_TYPE = 'CREDIT_NOTE'
             LIMIT 1 FOR UPDATE",
            [$approval_id]
        )->row_array();

        if (!$approval) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Approval Credit Note tidak ditemukan.'];
        }

        if (strtoupper($approval['APPROVAL_STATUS']) !== 'PENDING') {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Approval ini sudah diproses sebelumnya.'];
        }

        // if (strtolower((string) $approval['REQUESTED_BY']) === strtolower($user_code)) {
        //     $this->db->trans_rollback();
        //     return ['ok' => FALSE, 'message' => 'Requester tidak boleh menyetujui atau menolak request sendiri.'];
        // }

        $cn = $this->db->query(
            "SELECT * FROM {$this->table}
             WHERE CREDIT_NOTE_ID = ? LIMIT 1 FOR UPDATE",
            [(int) $approval['REFERENCE_ID']]
        )->row_array();

        if (!$cn || strtoupper($cn['APPROVAL_STATUS']) !== 'PENDING') {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Credit Note tidak ditemukan atau statusnya bukan PENDING.'];
        }

        $now = date('Y-m-d H:i:s');

        // Apply each side's own CN amount only after approval.
        if ($decision === 'APPROVED') {
            if (!empty($cn['ORDER_CUSTOMER_ID'])) {
                $this->db->set('REMAIN', 'GREATEST(COALESCE(REMAIN, TOTAL, 0) - ' . (float) $cn['CN_CUSTOMER_TOTAL'] . ', 0)', FALSE)
                    ->where('ORDER_CUSTOMER_ID', (int) $cn['ORDER_CUSTOMER_ID'])
                    ->update($this->customer_order_table);
            }
            if (!empty($cn['ORDER_SUPPLIER_ID'])) {
                $this->db->set('REMAIN', 'GREATEST(COALESCE(REMAIN, TOTAL, 0) - ' . (float) $cn['CN_SUPPLIER_TOTAL'] . ', 0)', FALSE)
                    ->where('ORDER_SUPPLIER_ID', (int) $cn['ORDER_SUPPLIER_ID'])
                    ->update($this->supplier_order_table);
            }
        }

        $old_data = [
            'CREDIT_NOTE' => $cn,
            'APPROVAL' => [
                'APPROVAL_STATUS' => $approval['APPROVAL_STATUS'],
                'REQUESTED_BY' => $approval['REQUESTED_BY'],
                'REQUESTED_AT' => $approval['REQUESTED_AT']
            ]
        ];

        $this->db
            ->where('CREDIT_NOTE_ID', (int) $cn['CREDIT_NOTE_ID'])
            ->update($this->table, [
                'APPROVAL_STATUS' => $decision,
                'APPROVED_BY' => $user_code,
                'APPROVED_AT' => $now,
                'APPROVAL_REMARK' => $remark
            ]);

        $this->db
            ->where('APPROVAL_ID', $approval_id)
            ->where('APPROVAL_STATUS', 'PENDING')
            ->update('pav_tr_order_approval', [
                'APPROVAL_STATUS' => $decision,
                'APPROVED_BY' => $user_code,
                'APPROVED_AT' => $now,
                'APPROVAL_REMARK' => $remark
            ]);

        $new_data = [
            'CREDIT_NOTE_ID' => (int) $cn['CREDIT_NOTE_ID'],
            'CN_NO' => $cn['CN_NO'],
            'APPROVAL_STATUS' => $decision,
            'APPROVED_BY' => $user_code,
            'APPROVED_AT' => $now,
            'APPROVAL_REMARK' => $remark
        ];

        $this->db->insert('pav_tr_order_history', [
            'ORDER_ID' => (int) $approval['ORDER_ID'],
            'REFERENCE_TYPE' => 'CREDIT_NOTE',
            'REFERENCE_ID' => (int) $cn['CREDIT_NOTE_ID'],
            'ACTION_TYPE' => 'CREDIT_NOTE_' . $decision,
            'OLD_DATA' => json_encode($old_data, JSON_UNESCAPED_UNICODE),
            'NEW_DATA' => json_encode($new_data, JSON_UNESCAPED_UNICODE),
            'REMARK' => $remark,
            'ACTION_BY' => $user_code,
            'ACTION_DATE' => $now
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Gagal memproses approval Credit Note.'];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'message' => $decision === 'APPROVED'
                ? 'Credit Note berhasil di-approve.'
                : 'Credit Note berhasil di-reject.'
        ];
    }

    private function is_valid_date($date)
    {
        if (!$date) {
            return FALSE;
        }

        $dt = DateTime::createFromFormat('!Y-m-d', $date);

        return $dt && $dt->format('Y-m-d') === $date;
    }
}
