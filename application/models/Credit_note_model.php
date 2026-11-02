
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
            o.ORDER_NO,
            o.ORDER_DATE,
            o.PLANT,
            o.CUST,
            o.SUPPLIER,
            c.FULL_NAME AS CUSTOMER_NAME,
            s.FULL_NAME AS SUPPLIER_NAME,
            CASE
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
                $this->db->where('cn.ORDER_CUSTOMER_ID IS NOT NULL', NULL, FALSE);
            } elseif ($filters['order_type'] === 'SUPPLIER') {
                $this->db->where('cn.ORDER_SUPPLIER_ID IS NOT NULL', NULL, FALSE);
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
        $type = isset($data['ORDER_TYPE']) ? strtoupper(trim($data['ORDER_TYPE'])) : '';
        $detail_id = isset($data['ORDER_DETAIL_ID']) ? (int) $data['ORDER_DETAIL_ID'] : 0;
        $cn_date = isset($data['CN_DATE']) ? trim($data['CN_DATE']) : '';
        $cn_price = isset($data['CN_PRICE']) ? (float) $data['CN_PRICE'] : -1;
        $remark = isset($data['REMARK']) ? trim($data['REMARK']) : '';

        if (!in_array($type, ['CUSTOMER', 'SUPPLIER'], TRUE)) {
            return ['ok' => FALSE, 'message' => 'Tipe Order tidak valid.'];
        }

        if ($detail_id <= 0) {
            return ['ok' => FALSE, 'message' => 'Order wajib dipilih.'];
        }

        if (!$this->is_valid_date($cn_date)) {
            return ['ok' => FALSE, 'message' => 'Tanggal Credit Note tidak valid.'];
        }

        if ($cn_price < 0) {
            return ['ok' => FALSE, 'message' => 'CN Price tidak boleh negatif.'];
        }

        $this->db->trans_begin();

        // Lock selected Order row to prevent concurrent CN creation.
        if ($type === 'CUSTOMER') {
            $table = $this->customer_order_table;
            $id_field = 'ORDER_CUSTOMER_ID';
        } else {
            $table = $this->supplier_order_table;
            $id_field = 'ORDER_SUPPLIER_ID';
        }

        $order = $this->db->query(
            "SELECT * FROM {$table} WHERE {$id_field} = ? LIMIT 1 FOR UPDATE",
            [$detail_id]
        )->row_array();

        if (!$order) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Order tidak ditemukan.'];
        }

        // Recheck existing CN, excluding the current record during edit.
        $this->db->from($this->table);

        if ($type === 'CUSTOMER') {
            $this->db->where('ORDER_CUSTOMER_ID', $detail_id);
        } else {
            $this->db->where('ORDER_SUPPLIER_ID', $detail_id);
        }

        if ($id) {
            $this->db->where('CREDIT_NOTE_ID !=', (int) $id);
        }

        if ($this->db->count_all_results() > 0) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Order tersebut sudah memiliki Credit Note.'];
        }

        $basis = strtoupper(trim($order['BASIS']));

        if ($basis === 'EKOR') {
            $cn_total = $cn_price * (float) $order['EKOR'];
        } elseif ($basis === 'BW') {
            $cn_total = $cn_price * (float) $order['KG'];
        } else {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Basis Order tidak dikenal.'];
        }

        $order_total = (float) $order['TOTAL'];

        if ($cn_total > $order_total + 0.00001) {
            $this->db->trans_rollback();
            return [
                'ok' => FALSE,
                'message' => 'CN Total tidak boleh melebihi Order Total.'
            ];
        }

        $payload = [
            'ORDER_CUSTOMER_ID' => ($type === 'CUSTOMER') ? $detail_id : NULL,
            'ORDER_SUPPLIER_ID' => ($type === 'SUPPLIER') ? $detail_id : NULL,
            'CN_DATE' => $cn_date,
            'CN_PRICE' => $cn_price,
            'CN_TOTAL' => round($cn_total, 2),
            'REMARK' => $remark
        ];

        if ($id) {
            $existing = $this->db->query(
                "SELECT * FROM {$this->table} WHERE CREDIT_NOTE_ID = ? LIMIT 1 FOR UPDATE",
                [(int) $id]
            )->row_array();

            if (!$existing) {
                $this->db->trans_rollback();
                return ['ok' => FALSE, 'message' => 'Credit Note tidak ditemukan.'];
            }

            if ($existing['APPROVAL_STATUS'] !== 'PENDING') {
                $this->db->trans_rollback();
                return ['ok' => FALSE, 'message' => 'Hanya Credit Note berstatus PENDING yang dapat diedit.'];
            }

            $payload['APPROVAL_STATUS'] = 'PENDING';
            $payload['REQUESTED_BY'] = $user_code;
            $payload['REQUESTED_AT'] = date('Y-m-d H:i:s');

            $this->db
                ->where('CREDIT_NOTE_ID', (int) $id)
                ->update($this->table, $payload);

            $credit_note_id = (int) $id;
        } else {
            $payload['CN_NO'] = 'TMP-' . strtoupper(bin2hex(random_bytes(8)));
            $payload['APPROVAL_STATUS'] = 'PENDING';
            $payload['REQUESTED_BY'] = $user_code;
            $payload['REQUESTED_AT'] = date('Y-m-d H:i:s');

            $this->db->insert($this->table, $payload);
            $credit_note_id = (int) $this->db->insert_id();

            if ($credit_note_id > 0) {
                $cn_no = 'CN-' . date('Ym', strtotime($cn_date)) . '-' .
                    str_pad((string) $credit_note_id, 6, '0', STR_PAD_LEFT);

                $this->db
                    ->where('CREDIT_NOTE_ID', $credit_note_id)
                    ->update($this->table, ['CN_NO' => $cn_no]);
            }
        }

        if ($credit_note_id <= 0) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Gagal menyimpan Credit Note.'];
        }

        // Create or refresh the central approval queue entry.
        $master_order_id = (int) $order['ORDER_ID'];
        $approval = $this->db
            ->where('APPROVAL_TYPE', 'CREDIT_NOTE')
            ->where('REFERENCE_ID', $credit_note_id)
            ->limit(1)
            ->get('pav_tr_order_approval')
            ->row_array();

        $approval_payload = [
            'ORDER_ID'        => $master_order_id,
            'APPROVAL_TYPE'   => 'CREDIT_NOTE',
            'REFERENCE_ID'   => $credit_note_id,
            'APPROVAL_STATUS' => 'PENDING',
            'REQUESTED_BY'    => $user_code,
            'REQUESTED_AT'    => date('Y-m-d H:i:s'),
            'APPROVED_BY'     => NULL,
            'APPROVED_AT'     => NULL,
            'APPROVAL_REMARK' => NULL
        ];

        if ($approval) {
            // An editable CN is PENDING; refresh its pending approval request.
            $this->db->where('APPROVAL_ID', (int) $approval['APPROVAL_ID'])
                ->update('pav_tr_order_approval', $approval_payload);
        } else {
            $this->db->insert('pav_tr_order_approval', $approval_payload);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'message' => 'Gagal menyimpan Credit Note dan approval request.'];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'credit_note_id' => $credit_note_id,
            'message' => $id ? 'Credit Note berhasil diperbarui.' : 'Credit Note berhasil dibuat.'
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CREDIT NOTE APPROVAL
    |--------------------------------------------------------------------------
    */

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
                cn.CN_NO,
                cn.CN_DATE,
                cn.CN_PRICE,
                cn.CN_TOTAL,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                o.CUST,
                o.SUPPLIER,
                COALESCE(c.FULL_NAME, s.FULL_NAME) AS PARTY_NAME,
                CASE
                    WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL THEN 'CUSTOMER'
                    ELSE 'SUPPLIER'
                END AS ORDER_TYPE,
                COALESCE(oc.ORDER_CUSTOMER_NO, os.ORDER_SUPPLIER_NO) AS ORDER_DETAIL_NO
            ", FALSE)
            ->from('pav_tr_order_approval a')
            ->join($this->table . ' cn', 'cn.CREDIT_NOTE_ID = a.REFERENCE_ID', 'inner')
            ->join($this->customer_order_table . ' oc', 'oc.ORDER_CUSTOMER_ID = cn.ORDER_CUSTOMER_ID', 'left')
            ->join($this->supplier_order_table . ' os', 'os.ORDER_SUPPLIER_ID = cn.ORDER_SUPPLIER_ID', 'left')
            ->join($this->order_table . ' o', 'o.ORDER_ID = a.ORDER_ID', 'inner')
            ->join('pav_cd_customer c', 'c.CUST = o.CUST', 'left')
            ->join('pav_cd_customer s', 's.CUST = o.SUPPLIER', 'left')
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
