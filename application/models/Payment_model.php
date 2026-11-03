
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model
{
    private $payment_table = 'pav_tr_payment';
    private $detail_table  = 'pav_tr_payment_detail';
    private $order_table   = 'pav_tr_order';
    private $supplier_order_table = 'pav_tr_order_supplier';
    private $sequence_table = 'pav_tr_order_sequence';

    // =====================================================
    // SUPPLIER OPTIONS
    // =====================================================

    public function get_supplier_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from('pav_cd_customer')
            ->where('CUST_KIND', 'SUPPLIER')
            ->where('STATUS', 'ACTIVE')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }

    // =====================================================
    // GET ELIGIBLE SUPPLIER ORDERS
    // No Plant filter
    // =====================================================

    public function get_supplier_orders($supplier, $lock = FALSE)
    {
        $sql = "
            SELECT
                os.ORDER_SUPPLIER_ID,
                os.ORDER_ID,
                os.ORDER_SUPPLIER_NO,
                os.PAID,
                os.REMAIN,
                os.PAYMENT_STATUS,
                os.TOTAL,

                COALESCE(cn.CN_TOTAL_AMOUNT, 0) AS CN_TOTAL_AMOUNT,

                o.ORDER_NO,
                o.ORDER_DATE,
                o.SUPPLIER

            FROM pav_tr_order_supplier os

            INNER JOIN pav_tr_order o
                ON o.ORDER_ID = os.ORDER_ID

            LEFT JOIN (
                SELECT
                    ORDER_SUPPLIER_ID,
                    SUM(CN_SUPPLIER_TOTAL) AS CN_TOTAL_AMOUNT
                FROM pav_tr_order_credit_note
                WHERE APPROVAL_STATUS = 'APPROVED'
                GROUP BY ORDER_SUPPLIER_ID
            ) cn
                ON cn.ORDER_SUPPLIER_ID = os.ORDER_SUPPLIER_ID

            WHERE o.SUPPLIER = ?
            AND o.STATUS = 'OPEN'
            AND os.REMAIN > 0

            ORDER BY
                o.ORDER_DATE ASC,
                os.ORDER_SUPPLIER_ID ASC
        ";

        if ($lock) {
            $sql .= " FOR UPDATE";
        }

        return $this->db->query($sql, [$supplier])->result_array();
    }

    // =====================================================
    // GENERATE PAYMENT NUMBER
    // PM-YYYYMM-0001
    // =====================================================

    private function generate_payment_number($payment_date)
    {
        $period = date('Ym', strtotime($payment_date));

        $sql = "
            INSERT INTO {$this->sequence_table}
                (DOC_TYPE, PERIOD, LAST_NUMBER)
            VALUES ('PM', ?, 1)
            ON DUPLICATE KEY UPDATE
                LAST_NUMBER = LAST_NUMBER + 1
        ";

        if (!$this->db->query($sql, [$period])) {
            return FALSE;
        }

        $row = $this->db
            ->where('DOC_TYPE', 'PM')
            ->where('PERIOD', $period)
            ->get($this->sequence_table)
            ->row_array();

        if (!$row) {
            return FALSE;
        }

        return 'PM-' . $period . '-' .
            str_pad($row['LAST_NUMBER'], 4, '0', STR_PAD_LEFT);
    }

    // =====================================================
    // PAYMENT LIST
    // =====================================================

    public function get_payment_list($filters = [])
    {
        $this->db
            ->select("
                p.*,
                c.FULL_NAME AS SUPPLIER_NAME,

                GROUP_CONCAT(
                    DISTINCT os.ORDER_SUPPLIER_NO
                    ORDER BY os.ORDER_SUPPLIER_NO
                    SEPARATOR ', '
                ) AS ORDER_LIST,

                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM pav_tr_payment_detail pd_current

                        INNER JOIN pav_tr_payment_detail pd_later
                            ON pd_later.ORDER_SUPPLIER_ID = pd_current.ORDER_SUPPLIER_ID

                        INNER JOIN pav_tr_payment p_later
                            ON p_later.PAYMENT_ID = pd_later.PAYMENT_ID

                        WHERE pd_current.PAYMENT_ID = p.PAYMENT_ID
                        AND p_later.PAYMENT_ID > p.PAYMENT_ID
                        AND p_later.STATUS = 'POSTED'
                    )
                    THEN 1
                    ELSE 0
                END AS HAS_LATER_PAYMENT
            ", FALSE)
            ->from($this->payment_table . ' p')
            ->join(
                'pav_cd_customer c',
                'c.CUST = p.SUPPLIER',
                'left'
            )
            ->join(
                $this->detail_table . ' pd',
                'pd.PAYMENT_ID = p.PAYMENT_ID',
                'left'
            )
            ->join(
                $this->supplier_order_table . ' os',
                'os.ORDER_SUPPLIER_ID = pd.ORDER_SUPPLIER_ID',
                'left'
            );

        if (!empty($filters['date_from'])) {
            $this->db->where(
                'p.PAYMENT_DATE >=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {
            $this->db->where(
                'p.PAYMENT_DATE <=',
                $filters['date_to']
            );
        }

        if (!empty($filters['supplier'])) {
            $this->db->where('p.SUPPLIER', $filters['supplier']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('p.STATUS', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $this->db->group_start()
                ->like('p.PAYMENT_NO', $search)
                ->or_like('p.SLIP_NO', $search)
                ->or_like('p.SUPPLIER', $search)
                ->or_like('c.FULL_NAME', $search)
                ->or_like('os.ORDER_SUPPLIER_NO', $search)
                ->group_end();
        }

        return $this->db
            ->group_by('p.PAYMENT_ID')
            ->order_by('p.PAYMENT_DATE', 'DESC')
            ->order_by('p.PAYMENT_ID', 'DESC')
            ->get()
            ->result_array();
    }

    // =====================================================
    // GET PAYMENT DETAIL
    // =====================================================

    public function get_payment_detail($payment_id)
    {
        // =========================
        // PAYMENT HEADER
        // =========================
        $header = $this->db
            ->select('
                p.*,
                c.FULL_NAME AS SUPPLIER_NAME
            ')
            ->from($this->payment_table . ' p')
            ->join(
                'pav_cd_customer c',
                'c.CUST = p.SUPPLIER',
                'left'
            )
            ->where('p.PAYMENT_ID', $payment_id)
            ->get()
            ->row_array();

        if (!$header) {
            return NULL;
        }

        // =========================
        // PAYMENT DETAIL
        // =========================
        $header['details'] = $this->db
            ->select("
                pd.PAYMENT_DETAIL_ID,
                pd.PAYMENT_ID,
                pd.ORDER_SUPPLIER_ID,
                pd.PAID_AMOUNT,

                os.ORDER_SUPPLIER_NO,
                os.ORDER_ID,
                os.TOTAL AS ORDER_TOTAL,
                os.PAID AS CURRENT_PAID,
                os.REMAIN AS CURRENT_REMAIN,
                os.PAYMENT_STATUS,

                COALESCE(cn.CN_TOTAL_AMOUNT, 0) AS CN_TOTAL_AMOUNT,

                o.ORDER_NO,
                o.ORDER_DATE
            ", FALSE)
            ->from($this->detail_table . ' pd')
            ->join(
                $this->supplier_order_table . ' os',
                'os.ORDER_SUPPLIER_ID = pd.ORDER_SUPPLIER_ID',
                'inner'
            )
            ->join(
                $this->order_table . ' o',
                'o.ORDER_ID = os.ORDER_ID',
                'inner'
            )
            ->join(
                "(
                    SELECT
                        ORDER_SUPPLIER_ID,
                        SUM(CN_SUPPLIER_TOTAL) AS CN_TOTAL_AMOUNT
                    FROM pav_tr_order_credit_note
                    WHERE APPROVAL_STATUS = 'APPROVED'
                    GROUP BY ORDER_SUPPLIER_ID
                ) cn",
                'cn.ORDER_SUPPLIER_ID = os.ORDER_SUPPLIER_ID',
                'left',
                FALSE
            )
            ->where('pd.PAYMENT_ID', $payment_id)
            ->order_by('o.ORDER_DATE', 'ASC')
            ->order_by('os.ORDER_SUPPLIER_ID', 'ASC')
            ->get()
            ->result_array();

        return $header;
    }

    // =====================================================
    // BUILD ALLOCATION
    // =====================================================

    private function build_allocations(
        $payment_type,
        $total_payment,
        $orders,
        $manual_allocations = []
    ) {
        $total_payment = round((float) $total_payment, 2);

        if ($total_payment <= 0) {
            return [
                'ok' => FALSE,
                'message' => 'Total Payment harus lebih besar dari 0.'
            ];
        }

        $order_map = [];

        foreach ($orders as $order) {
            $order_map[(int) $order['ORDER_SUPPLIER_ID']] = $order;
        }

        $allocations = [];

        // FIFO
        if ($payment_type === 'FIFO') {
            $remaining_payment = $total_payment;

            foreach ($orders as $order) {
                if ($remaining_payment <= 0) {
                    break;
                }

                $remain = round((float) $order['REMAIN'], 2);
                $amount = min($remaining_payment, $remain);
                $amount = round($amount, 2);

                if ($amount > 0) {
                    $allocations[] = [
                        'ORDER_SUPPLIER_ID' =>
                            (int) $order['ORDER_SUPPLIER_ID'],
                        'PAID_AMOUNT' => $amount
                    ];

                    $remaining_payment = round(
                        $remaining_payment - $amount,
                        2
                    );
                }
            }

            if ($remaining_payment > 0) {
                return [
                    'ok' => FALSE,
                    'message' =>
                        'Total Payment melebihi total outstanding Supplier Order.'
                ];
            }
        }

        // MANUAL
        else {
            if (empty($manual_allocations) ||
                !is_array($manual_allocations)) {
                return [
                    'ok' => FALSE,
                    'message' => 'Allocation Manual belum diisi.'
                ];
            }

            $used_ids = [];
            $allocation_total = 0;

            foreach ($manual_allocations as $item) {
                $order_id = isset($item['ORDER_SUPPLIER_ID'])
                    ? (int) $item['ORDER_SUPPLIER_ID']
                    : 0;

                $amount = isset($item['PAID_AMOUNT'])
                    ? $item['PAID_AMOUNT']
                    : NULL;

                if (
                    $order_id <= 0 ||
                    !is_numeric($amount) ||
                    (float) $amount <= 0
                ) {
                    return [
                        'ok' => FALSE,
                        'message' => 'Allocation Manual tidak valid.'
                    ];
                }

                if (isset($used_ids[$order_id])) {
                    return [
                        'ok' => FALSE,
                        'message' => 'Supplier Order tidak boleh dipilih lebih dari satu kali.'
                    ];
                }

                if (!isset($order_map[$order_id])) {
                    return [
                        'ok' => FALSE,
                        'message' => 'Supplier Order tidak valid atau sudah tidak outstanding.'
                    ];
                }

                $amount = round((float) $amount, 2);
                $remain = round(
                    (float) $order_map[$order_id]['REMAIN'],
                    2
                );

                if ($amount > $remain) {
                    return [
                        'ok' => FALSE,
                        'message' => 'Allocation melebihi outstanding Supplier Order.'
                    ];
                }

                $used_ids[$order_id] = TRUE;
                $allocation_total = round(
                    $allocation_total + $amount,
                    2
                );

                $allocations[] = [
                    'ORDER_SUPPLIER_ID' => $order_id,
                    'PAID_AMOUNT' => $amount
                ];
            }

            if ($allocation_total !== $total_payment) {
                return [
                    'ok' => FALSE,
                    'message' => 'Total allocation harus sama dengan Total Payment.'
                ];
            }
        }

        return [
            'ok' => TRUE,
            'allocations' => $allocations
        ];
    }

    // =====================================================
    // SAVE PAYMENT
    // =====================================================

    public function save_payment($data, $manual_allocations, $user_code)
    {
        $payment_date = trim($data['PAYMENT_DATE']);
        $supplier = trim($data['SUPPLIER']);
        $payment_method = strtoupper(trim($data['PAYMENT_METHOD']));
        $payment_type = strtoupper(trim($data['PAYMENT_TYPE']));
        $total_payment = round((float) $data['TOTAL_PAYMENT'], 2);

        if (!in_array($payment_method, ['CASH', 'TRANSFER'], TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Payment Method tidak valid.'
            ];
        }

        if (!in_array($payment_type, ['FIFO', 'MANUAL'], TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Payment Type tidak valid.'
            ];
        }

        if ($total_payment <= 0) {
            return [
                'ok' => FALSE,
                'message' => 'Total Payment harus lebih besar dari 0.'
            ];
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $payment_date)) {
            return [
                'ok' => FALSE,
                'message' => 'Payment Date tidak valid.'
            ];
        }

        $this->db->trans_begin();

        // Lock and validate active supplier
        $supplier_row = $this->db->query(
            "SELECT CUST
             FROM pav_cd_customer
             WHERE CUST = ?
               AND CUST_KIND = 'SUPPLIER'
               AND STATUS = 'ACTIVE'
             LIMIT 1 FOR UPDATE",
            [$supplier]
        )->row_array();

        if (!$supplier_row) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Supplier tidak ditemukan atau tidak aktif.'
            ];
        }

        // Lock eligible Supplier Orders
        $orders = $this->get_supplier_orders($supplier, TRUE);

        if (empty($orders)) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Tidak ada Supplier Order outstanding dengan status Master Order OPEN.'
            ];
        }

        $allocation_result = $this->build_allocations(
            $payment_type,
            $total_payment,
            $orders,
            $manual_allocations
        );

        if (!$allocation_result['ok']) {
            $this->db->trans_rollback();
            return $allocation_result;
        }

        // Generate PM number
        $payment_no = $this->generate_payment_number($payment_date);

        if (!$payment_no) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal membuat Payment Number.'
            ];
        }

        $now = date('Y-m-d H:i:s');

        // Insert Payment Header
        $this->db->insert($this->payment_table, [
            'PAYMENT_NO' => $payment_no,
            'PAYMENT_DATE' => $payment_date,
            'SUPPLIER' => $supplier,
            'PAYMENT_METHOD' => $payment_method,
            'PAYMENT_TYPE' => $payment_type,
            'SLIP_NO' => !empty($data['SLIP_NO'])
                ? trim($data['SLIP_NO'])
                : NULL,
            'TOTAL_PAYMENT' => $total_payment,
            'REMARK' => !empty($data['REMARK'])
                ? trim($data['REMARK'])
                : NULL,
            'STATUS' => 'POSTED',
            'REG_EMP' => $user_code,
            'REG_DATE' => $now
        ]);

        $payment_id = $this->db->insert_id();

        if (!$payment_id) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Payment Header.'
            ];
        }

        // Insert Detail and update Supplier Order
        foreach ($allocation_result['allocations'] as $allocation) {
            $order_id = (int) $allocation['ORDER_SUPPLIER_ID'];
            $amount = round((float) $allocation['PAID_AMOUNT'], 2);

            $order = $orders_by_id = NULL;

            foreach ($orders as $row) {
                if ((int) $row['ORDER_SUPPLIER_ID'] === $order_id) {
                    $order = $row;
                    break;
                }
            }

            if (!$order) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Supplier Order tidak ditemukan.'
                ];
            }

            $new_paid = round((float) $order['PAID'] + $amount, 2);
            $new_remain = round((float) $order['REMAIN'] - $amount, 2);

            if ($new_remain < 0) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Outstanding Supplier Order tidak mencukupi.'
                ];
            }

            $new_status = ($new_remain <= 0)
                ? 'PAID'
                : (($new_paid > 0) ? 'PARTIAL' : 'OPEN');

            $this->db->insert($this->detail_table, [
                'PAYMENT_ID' => $payment_id,
                'ORDER_SUPPLIER_ID' => $order_id,
                'PAID_AMOUNT' => $amount,
                'REG_EMP' => $user_code,
                'REG_DATE' => $now
            ]);

            $this->db
                ->where('ORDER_SUPPLIER_ID', $order_id)
                ->update($this->supplier_order_table, [
                    'PAID' => $new_paid,
                    'REMAIN' => $new_remain,
                    'PAYMENT_STATUS' => $new_status,
                    'UPD_EMP' => $user_code,
                    'UPD_DATE' => $now
                ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Payment. Seluruh perubahan dibatalkan.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'payment_id' => $payment_id,
            'payment_no' => $payment_no,
            'message' => 'Payment berhasil disimpan.'
        ];
    }

    // =====================================================
    // CHECK LATER POSTED PAYMENT
    // =====================================================

    public function has_later_posted_payment($payment_id)
    {
        $sql = "
            SELECT COUNT(*) AS TOTAL
            FROM {$this->detail_table} current_detail
            INNER JOIN {$this->detail_table} later_detail
                ON later_detail.ORDER_SUPPLIER_ID =
                current_detail.ORDER_SUPPLIER_ID
            INNER JOIN {$this->payment_table} later_payment
                ON later_payment.PAYMENT_ID =
                later_detail.PAYMENT_ID
            WHERE current_detail.PAYMENT_ID = ?
            AND later_payment.PAYMENT_ID > ?
            AND later_payment.STATUS = 'POSTED'
        ";

        $row = $this->db
            ->query($sql, [$payment_id, $payment_id])
            ->row_array();

        return $row && (int) $row['TOTAL'] > 0;
    }

    // =====================================================
    // EDIT PAYMENT
    // =====================================================

    public function edit_payment(
        $payment_id,
        $data,
        $manual_allocations,
        $user_code
    ) {
        $payment_id = (int) $payment_id;

        $payment_date = trim($data['PAYMENT_DATE']);
        $supplier = trim($data['SUPPLIER']);
        $payment_method = strtoupper(trim($data['PAYMENT_METHOD']));
        $payment_type = strtoupper(trim($data['PAYMENT_TYPE']));
        $total_payment = round((float) $data['TOTAL_PAYMENT'], 2);

        if ($payment_id <= 0) {
            return [
                'ok' => FALSE,
                'message' => 'Payment ID tidak valid.'
            ];
        }

        if (!in_array($payment_method, ['CASH', 'TRANSFER'], TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Payment Method tidak valid.'
            ];
        }

        if (!in_array($payment_type, ['FIFO', 'MANUAL'], TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Payment Type tidak valid.'
            ];
        }

        if ($total_payment <= 0 || !is_numeric($data['TOTAL_PAYMENT'])) {
            return [
                'ok' => FALSE,
                'message' => 'Total Payment tidak valid.'
            ];
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $payment_date)) {
            return [
                'ok' => FALSE,
                'message' => 'Payment Date tidak valid.'
            ];
        }

        $this->db->trans_begin();

        // Lock Payment Header
        $payment = $this->db->query(
            "SELECT *
            FROM {$this->payment_table}
            WHERE PAYMENT_ID = ?
            LIMIT 1 FOR UPDATE",
            [$payment_id]
        )->row_array();

        if (!$payment) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Payment tidak ditemukan.'
            ];
        }

        if ($payment['STATUS'] !== 'POSTED') {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Payment yang sudah VOID tidak dapat diedit.'
            ];
        }

        // Supplier tidak boleh diganti
        if ($supplier !== $payment['SUPPLIER']) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Supplier Payment tidak dapat diganti.'
            ];
        }

        // Prevent edit if newer posted payment exists
        if ($this->has_later_posted_payment($payment_id)) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Payment tidak dapat diedit karena sudah terdapat Payment POSTED yang lebih baru pada Supplier Order terkait.'
            ];
        }

        // Get old detail
        $old_details = $this->db
            ->where('PAYMENT_ID', $payment_id)
            ->order_by('ORDER_SUPPLIER_ID', 'ASC')
            ->get($this->detail_table)
            ->result_array();

        if (empty($old_details)) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Detail Payment tidak ditemukan.'
            ];
        }

        // Lock all affected Supplier Orders
        $old_order_ids = [];

        foreach ($old_details as $detail) {
            $old_order_ids[] = (int) $detail['ORDER_SUPPLIER_ID'];
        }

        $old_order_ids = array_values(array_unique($old_order_ids));
        sort($old_order_ids);

        $locked_orders = [];

        if (!empty($old_order_ids)) {
            $ids = implode(',', array_map('intval', $old_order_ids));

            $locked_orders = $this->db->query(
                "SELECT *
                FROM {$this->supplier_order_table}
                WHERE ORDER_SUPPLIER_ID IN ({$ids})
                ORDER BY ORDER_SUPPLIER_ID ASC
                FOR UPDATE"
            )->result_array();
        }

        $locked_map = [];

        // =====================================================
        // RECHECK LATER PAYMENT AFTER LOCKING SUPPLIER ORDERS
        // =====================================================

        if ($this->has_later_posted_payment($payment_id)) {

            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Payment tidak dapat diedit karena terdapat Payment POSTED yang lebih baru pada Supplier Order terkait.'
            ];
        }

        foreach ($locked_orders as $row) {
            $locked_map[(int) $row['ORDER_SUPPLIER_ID']] = $row;
        }

        // Restore old allocations
        $now = date('Y-m-d H:i:s');

        foreach ($old_details as $detail) {
            $order_id = (int) $detail['ORDER_SUPPLIER_ID'];
            $amount = round((float) $detail['PAID_AMOUNT'], 2);

            if (!isset($locked_map[$order_id])) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Supplier Order pada Payment lama tidak ditemukan.'
                ];
            }

            $order = $locked_map[$order_id];

            $new_paid = round((float) $order['PAID'] - $amount, 2);
            $new_remain = round((float) $order['REMAIN'] + $amount, 2);

            if ($new_paid < 0) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Saldo PAID Supplier Order tidak konsisten.'
                ];
            }

            $new_status = ($new_remain <= 0)
                ? 'PAID'
                : (($new_paid > 0) ? 'PARTIAL' : 'OPEN');

            $this->db
                ->where('ORDER_SUPPLIER_ID', $order_id)
                ->update($this->supplier_order_table, [
                    'PAID' => $new_paid,
                    'REMAIN' => $new_remain,
                    'PAYMENT_STATUS' => $new_status,
                    'UPD_EMP' => $user_code,
                    'UPD_DATE' => $now
                ]);
        }

        // Remove old allocation details
        $this->db
            ->where('PAYMENT_ID', $payment_id)
            ->delete($this->detail_table);

        // Re-read eligible Supplier Orders after restoration
        $orders = $this->get_supplier_orders($supplier, TRUE);

        if (empty($orders)) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Tidak ada Supplier Order yang eligible untuk Payment ini.'
            ];
        }

        // Build new allocation
        $allocation_result = $this->build_allocations(
            $payment_type,
            $total_payment,
            $orders,
            $manual_allocations
        );

        if (!$allocation_result['ok']) {
            $this->db->trans_rollback();
            return $allocation_result;
        }

        // Insert new allocation details and update balances
        foreach ($allocation_result['allocations'] as $allocation) {
            $order_id = (int) $allocation['ORDER_SUPPLIER_ID'];
            $amount = round((float) $allocation['PAID_AMOUNT'], 2);

            $order = NULL;

            foreach ($orders as $row) {
                if ((int) $row['ORDER_SUPPLIER_ID'] === $order_id) {
                    $order = $row;
                    break;
                }
            }

            if (!$order) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Supplier Order tidak ditemukan.'
                ];
            }

            $new_paid = round((float) $order['PAID'] + $amount, 2);
            $new_remain = round((float) $order['REMAIN'] - $amount, 2);

            if ($new_remain < 0) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Allocation melebihi outstanding Supplier Order.'
                ];
            }

            $new_status = ($new_remain <= 0)
                ? 'PAID'
                : (($new_paid > 0) ? 'PARTIAL' : 'OPEN');

            $this->db->insert($this->detail_table, [
                'PAYMENT_ID' => $payment_id,
                'ORDER_SUPPLIER_ID' => $order_id,
                'PAID_AMOUNT' => $amount,
                'REG_EMP' => $user_code,
                'REG_DATE' => $now
            ]);

            $this->db
                ->where('ORDER_SUPPLIER_ID', $order_id)
                ->update($this->supplier_order_table, [
                    'PAID' => $new_paid,
                    'REMAIN' => $new_remain,
                    'PAYMENT_STATUS' => $new_status,
                    'UPD_EMP' => $user_code,
                    'UPD_DATE' => $now
                ]);
        }

        // Update Payment Header
        $this->db
            ->where('PAYMENT_ID', $payment_id)
            ->update($this->payment_table, [
                'PAYMENT_DATE' => $payment_date,
                'PAYMENT_METHOD' => $payment_method,
                'PAYMENT_TYPE' => $payment_type,
                'SLIP_NO' => !empty($data['SLIP_NO'])
                    ? trim($data['SLIP_NO'])
                    : NULL,
                'TOTAL_PAYMENT' => $total_payment,
                'REMARK' => !empty($data['REMARK'])
                    ? trim($data['REMARK'])
                    : NULL,
                'UPD_EMP' => $user_code,
                'UPD_DATE' => $now
            ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal memperbarui Payment. Seluruh perubahan dibatalkan.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'message' => 'Payment berhasil diperbarui.'
        ];
    }

    public function get_edit_supplier_orders($payment_id)
    {
        $payment_id = (int) $payment_id;

        return $this->db
            ->select("
                os.ORDER_SUPPLIER_ID,
                os.ORDER_ID,
                os.ORDER_SUPPLIER_NO,

                (os.PAID - COALESCE(pd.PAID_AMOUNT, 0)) AS PAID,

                (os.REMAIN + COALESCE(pd.PAID_AMOUNT, 0)) AS REMAIN,

                os.PAYMENT_STATUS,
                os.TOTAL,

                o.ORDER_NO,
                o.ORDER_DATE,
                o.SUPPLIER,

                COALESCE(cn.CN_TOTAL_AMOUNT, 0) AS CN_TOTAL_AMOUNT
            ", FALSE)

            ->from($this->supplier_order_table . ' os')

            ->join(
                $this->order_table . ' o',
                'o.ORDER_ID = os.ORDER_ID',
                'inner'
            )

            ->join(
                $this->payment_table . ' p',
                'p.SUPPLIER = o.SUPPLIER',
                'inner'
            )

            ->join(
                $this->detail_table . ' pd',
                'pd.ORDER_SUPPLIER_ID = os.ORDER_SUPPLIER_ID
                AND pd.PAYMENT_ID = p.PAYMENT_ID',
                'left',
                FALSE
            )

            ->join("
                (
                    SELECT
                        ORDER_SUPPLIER_ID,
                        SUM(CN_SUPPLIER_TOTAL) AS CN_TOTAL_AMOUNT
                    FROM pav_tr_order_credit_note
                    WHERE APPROVAL_STATUS = 'APPROVED'
                    GROUP BY ORDER_SUPPLIER_ID
                ) cn
            ", 'cn.ORDER_SUPPLIER_ID = os.ORDER_SUPPLIER_ID', 'left', FALSE)

            ->where('p.PAYMENT_ID', $payment_id)
            ->where('o.STATUS', 'OPEN')

            ->group_start()
                ->where('os.REMAIN >', 0)
                ->or_where('pd.PAYMENT_DETAIL_ID IS NOT NULL', NULL, FALSE)
            ->group_end()

            ->order_by('o.ORDER_DATE', 'ASC')
            ->order_by('os.ORDER_SUPPLIER_ID', 'ASC')

            ->get()
            ->result_array();
    }

    // =====================================================
    // VOID PAYMENT
    // =====================================================

    public function void_payment($payment_id, $user_code)
    {
        $payment_id = (int) $payment_id;

        if ($payment_id <= 0) {
            return [
                'ok' => FALSE,
                'message' => 'Payment ID tidak valid.'
            ];
        }

        $this->db->trans_begin();

        try {

            // =====================================
            // 1. LOCK PAYMENT HEADER
            // =====================================
            $payment = $this->db->query(
                "SELECT *
                FROM {$this->payment_table}
                WHERE PAYMENT_ID = ?
                LIMIT 1
                FOR UPDATE",
                [$payment_id]
            )->row_array();

            if (!$payment) {
                throw new Exception('Payment tidak ditemukan.');
            }

            if ($payment['STATUS'] !== 'POSTED') {
                throw new Exception('Payment sudah VOID.');
            }

            // =====================================
            // 2. GET PAYMENT DETAILS
            // =====================================
            $details = $this->db
                ->where('PAYMENT_ID', $payment_id)
                ->order_by('ORDER_SUPPLIER_ID', 'ASC')
                ->get($this->detail_table)
                ->result_array();

            if (empty($details)) {
                throw new Exception('Detail Payment tidak ditemukan.');
            }

            $order_ids = [];

            foreach ($details as $detail) {
                $order_ids[] = (int) $detail['ORDER_SUPPLIER_ID'];
            }

            $order_ids = array_values(array_unique($order_ids));
            sort($order_ids);

            $ids = implode(',', array_map('intval', $order_ids));

            // =====================================
            // 3. LOCK SUPPLIER ORDERS
            // =====================================
            $orders = $this->db->query(
                "SELECT *
                FROM {$this->supplier_order_table}
                WHERE ORDER_SUPPLIER_ID IN ({$ids})
                ORDER BY ORDER_SUPPLIER_ID ASC
                FOR UPDATE"
            )->result_array();

            $order_map = [];

            foreach ($orders as $order) {
                $order_map[(int) $order['ORDER_SUPPLIER_ID']] = $order;
            }

            if (count($order_map) !== count($order_ids)) {
                throw new Exception('Supplier Order tidak ditemukan.');
            }

            // =====================================
            // 4. CHECK LATER PAYMENT
            // =====================================
            if ($this->has_later_posted_payment($payment_id)) {
                throw new Exception(
                    'Payment tidak dapat di-VOID karena sudah terdapat Payment POSTED yang lebih baru pada Supplier Order terkait.'
                );
            }

            $now = date('Y-m-d H:i:s');

            // =====================================
            // 5. REVERSE PAYMENT ALLOCATIONS
            // =====================================
            foreach ($details as $detail) {

                $order_id = (int) $detail['ORDER_SUPPLIER_ID'];
                $amount = round((float) $detail['PAID_AMOUNT'], 2);

                $order = $order_map[$order_id];

                $current_paid = round((float) $order['PAID'], 2);
                $current_remain = round((float) $order['REMAIN'], 2);
                $total = round((float) $order['TOTAL'], 2);

                $new_paid = round($current_paid - $amount, 2);
                $new_remain = round($current_remain + $amount, 2);

                // Validate PAID
                if ($new_paid < 0) {
                    throw new Exception(
                        'Saldo PAID Supplier Order tidak konsisten. Void dibatalkan.'
                    );
                }

                // Validate REMAIN
                if ($new_remain > $total) {
                    throw new Exception(
                        'Saldo REMAIN melebihi TOTAL Supplier Order. Void dibatalkan.'
                    );
                }

                // Calculate status
                $new_status = ($new_remain <= 0)
                    ? 'PAID'
                    : (($new_paid > 0) ? 'PARTIAL' : 'OPEN');

                $this->db
                    ->where('ORDER_SUPPLIER_ID', $order_id)
                    ->update($this->supplier_order_table, [
                        'PAID' => $new_paid,
                        'REMAIN' => $new_remain,
                        'PAYMENT_STATUS' => $new_status,
                        'UPD_EMP' => $user_code,
                        'UPD_DATE' => $now
                    ]);

                if ($this->db->affected_rows() < 0) {
                    throw new Exception(
                        'Gagal memperbarui saldo Supplier Order.'
                    );
                }
            }

            // =====================================
            // 6. MARK PAYMENT AS VOID
            // =====================================
            $this->db
                ->where('PAYMENT_ID', $payment_id)
                ->where('STATUS', 'POSTED')
                ->update($this->payment_table, [
                    'STATUS' => 'VOID',
                    'UPD_EMP' => $user_code,
                    'UPD_DATE' => $now
                ]);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception(
                    'Gagal melakukan Void Payment.'
                );
            }

            // =====================================
            // 7. COMMIT
            // =====================================
            $this->db->trans_commit();

            return [
                'ok' => TRUE,
                'message' => 'Payment berhasil di-VOID dan saldo Supplier Order telah dikembalikan.'
            ];

        } catch (Exception $e) {

            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => $e->getMessage()
            ];
        }
    }

}
