
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model
{
    private $order_table = 'pav_tr_order';
    private $customer_table = 'pav_tr_order_customer';
    private $supplier_table = 'pav_tr_order_supplier';
    private $credit_limit_table = 'pav_tr_credit_limit';
    private $sequence_table = 'pav_tr_order_sequence';
    private $edit_request_table = 'pav_tr_order_edit_request';
    private $approval_table = 'pav_tr_order_approval';
    private $history_table = 'pav_tr_order_history';

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
            ->get()
            ->result_array();
    }

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

    public function get_customer_by_code($code)
    {
        $code = trim((string) $code);

        if ($code === '') {
            return NULL;
        }

        return $this->db
            ->where('CUST', $code)
            ->where('CUST_KIND', 'CUSTOMER')
            ->where('STATUS', 'ACTIVE')
            ->get('pav_cd_customer')
            ->row_array();
    }

    public function get_supplier_by_code($code)
    {
        $code = trim((string) $code);

        if ($code === '') {
            return NULL;
        }

        return $this->db
            ->where('CUST', $code)
            ->where('CUST_KIND', 'SUPPLIER')
            ->where('STATUS', 'ACTIVE')
            ->get('pav_cd_customer')
            ->row_array();
    }

    // =====================================================
    // CALCULATE ORDER
    // =====================================================

    public function calculate_order($basis, $ekor, $kg, $unit_price)
    {
        $basis = strtoupper(trim((string) $basis));

        if (!in_array($basis, ['EKOR', 'BW'], TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Basis harus EKOR atau BW.'
            ];
        }

        if (
            !is_numeric($ekor) ||
            !is_numeric($kg) ||
            !is_numeric($unit_price)
        ) {
            return [
                'ok' => FALSE,
                'message' => 'Ekor, KG, dan harga harus berupa angka.'
            ];
        }

        $ekor = (float) $ekor;
        $kg = (float) $kg;
        $unit_price = (float) $unit_price;

        if ($ekor <= 0 || $kg <= 0) {
            return [
                'ok' => FALSE,
                'message' => 'Ekor dan KG wajib lebih besar dari 0.'
            ];
        }

        if ($unit_price <= 0) {
            return [
                'ok' => FALSE,
                'message' => 'Harga satuan wajib lebih besar dari 0.'
            ];
        }

        $avg_bw = round($kg / $ekor, 4);

        $total = ($basis === 'EKOR')
            ? $ekor * $unit_price
            : $kg * $unit_price;

        return [
            'ok' => TRUE,
            'data' => [
                'BASIS' => $basis,
                'EKOR' => $ekor,
                'KG' => $kg,
                'AVG_BW' => $avg_bw,
                'UNIT_PRICE' => round($unit_price, 2),
                'TOTAL' => round($total, 2)
            ]
        ];
    }

    public function generate_number($doc_type, $order_date)
    {
        $prefixes = [
            'ORD' => 'ORD',
            'CO'  => 'CO',
            'OS'  => 'OS',
            'CN'  => 'CN'
        ];

        $doc_type = strtoupper(trim((string) $doc_type));

        if (!isset($prefixes[$doc_type])) {
            return FALSE;
        }

        if (!$this->is_valid_date($order_date)) {
            return FALSE;
        }

        $period = date('Ym', strtotime($order_date));

        $sql = "
            INSERT INTO {$this->sequence_table}
                (DOC_TYPE, PERIOD, LAST_NUMBER)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE
                LAST_NUMBER = LAST_NUMBER + 1
        ";

        $query = $this->db->query($sql, [
            $doc_type,
            $period
        ]);

        if (!$query || $this->db->trans_status() === FALSE) {
            return FALSE;
        }

        $row = $this->db
            ->select('LAST_NUMBER')
            ->from($this->sequence_table)
            ->where('DOC_TYPE', $doc_type)
            ->where('PERIOD', $period)
            ->get()
            ->row_array();

        if (!$row) {
            return FALSE;
        }

        $number = (int) $row['LAST_NUMBER'];

        if ($number < 1 || $number > 9999) {
            return FALSE;
        }

        return $prefixes[$doc_type] . '-' .
            $period . '-' .
            str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    public function get_outstanding_credit(
        $cust,
        $exclude_order_customer_id = NULL,
        $opening_effective_date = NULL
    ) {
        $this->db
            ->select('COALESCE(SUM(oc.REMAIN), 0) AS OUTSTANDING', FALSE)
            ->from($this->customer_table . ' oc')
            ->join(
                $this->order_table . ' o',
                'o.ORDER_ID = oc.ORDER_ID',
                'inner'
            )
            ->where('o.CUST', $cust)
            ->where('o.STATUS', 'OPEN')
            ->where_in('oc.APPROVAL_STATUS', [
                'NOT_REQUIRED',
                'APPROVED'
            ])
            ->where_in('oc.PAYMENT_STATUS', [
                'OPEN',
                'PARTIAL'
            ]);

        // Hanya menghitung Order mulai Opening Effective Date.
        if (!empty($opening_effective_date)) {
            $this->db->where(
                'o.ORDER_DATE >=',
                $opening_effective_date
            );
        }

        // Untuk validasi ketika Customer Order diedit.
        if ($exclude_order_customer_id !== NULL) {
            $this->db->where(
                'oc.ORDER_CUSTOMER_ID !=',
                $exclude_order_customer_id
            );
        }

        $row = $this->db
            ->get()
            ->row_array();

        return $row ? (float) $row['OUTSTANDING'] : 0;
    }

    // =====================================================
    // GET CUSTOMER CREDIT SUMMARY
    // =====================================================

    public function get_customer_credit_summary($cust)
    {
        $cust = trim((string) $cust);

        if ($cust === '') {
            return [
                'ok' => FALSE,
                'message' => 'Customer tidak valid.'
            ];
        }

        $customer = $this->get_customer_by_code($cust);

        if (!$customer) {
            return [
                'ok' => FALSE,
                'message' => 'Customer tidak ditemukan atau tidak aktif.'
            ];
        }

        $credit = $this->db
            ->select('
                BASIC_CREDIT,
                BASIC_CREDIT_FROM_DATE,
                BASIC_CREDIT_TO_DATE,
                OPENING_AVAILABLE_CREDIT,
                OPENING_EFFECTIVE_DATE,
                CURRENCY,
                STATUS
            ')
            ->where('CUST', $cust)
            ->limit(1)
            ->get($this->credit_limit_table)
            ->row_array();

        if (!$credit) {
            return [
                'ok' => TRUE,
                'data' => [
                    'has_credit_limit' => FALSE,
                    'is_active' => FALSE,
                    'basic_credit' => 0,
                    'opening_available_credit' => 0,
                    'outstanding' => 0,
                    'credit_balance' => 0,
                    'currency' => 'IDR',
                    'message' => 'Customer belum memiliki Credit Limit. Approval diperlukan.'
                ]
            ];
        }

        $today = date('Y-m-d');

        $basic_credit = (float) $credit['BASIC_CREDIT'];
        $opening_available_credit = (float) $credit['OPENING_AVAILABLE_CREDIT'];

        $opening_effective_date = $credit['OPENING_EFFECTIVE_DATE'];

        $outstanding = $this->get_outstanding_credit(
            $cust,
            NULL,
            $opening_effective_date
        );

        $is_active =
            strtoupper($credit['STATUS']) === 'ACTIVE' &&
            !empty($credit['BASIC_CREDIT_FROM_DATE']) &&
            $credit['BASIC_CREDIT_FROM_DATE'] <= $today &&
            (
                empty($credit['BASIC_CREDIT_TO_DATE']) ||
                $credit['BASIC_CREDIT_TO_DATE'] >= $today
            );

        $credit_balance = $is_active
            ? $opening_available_credit - $outstanding
            : 0;

        return [
            'ok' => TRUE,
            'data' => [
                'has_credit_limit' => TRUE,
                'is_active' => $is_active,
                'basic_credit' => $basic_credit,
                'opening_available_credit' => $opening_available_credit,
                'opening_effective_date' => $opening_effective_date,
                'outstanding' => $outstanding,
                'credit_balance' => $credit_balance,
                'currency' => $credit['CURRENCY'] ?: 'IDR',
                'message' => $is_active
                    ? 'Credit Limit aktif.'
                    : 'Credit Limit tidak aktif atau di luar periode. Approval diperlukan.'
            ]
        ];
    }

    public function validate_credit_limit(
        $cust,
        $order_total,
        $exclude_order_customer_id = NULL
    ) {
        // Lock Credit Limit record selama transaksi berjalan.
        $sql = "
            SELECT *
            FROM {$this->credit_limit_table}
            WHERE CUST = ?
            LIMIT 1
            FOR UPDATE
        ";

        $query = $this->db->query($sql, [$cust]);

        if (!$query || $this->db->trans_status() === FALSE) {
            return [
                'ok' => FALSE,
                'message' => 'Gagal membaca Credit Limit.'
            ];
        }

        $credit = $query->row_array();

        // Customer belum memiliki Credit Limit.
        if (!$credit) {
            return [
                'ok' => TRUE,
                'approval_required' => TRUE,
                'reason' => 'CUSTOMER_HAS_NO_CREDIT_LIMIT',
                'basic_credit' => 0,
                'opening_available_credit' => 0,
                'outstanding' => 0,
                'credit_balance' => 0,
                'terms_of_payment' => NULL
            ];
        }

        $today = date('Y-m-d');

        $is_active =
            strtoupper($credit['STATUS']) === 'ACTIVE' &&
            !empty($credit['BASIC_CREDIT_FROM_DATE']) &&
            $credit['BASIC_CREDIT_FROM_DATE'] <= $today &&
            (
                empty($credit['BASIC_CREDIT_TO_DATE']) ||
                $credit['BASIC_CREDIT_TO_DATE'] >= $today
            );

        $basic_credit = (float) $credit['BASIC_CREDIT'];
        $opening_available_credit = (float) $credit['OPENING_AVAILABLE_CREDIT'];

        // Credit Limit tidak aktif atau di luar periode.
        if (!$is_active) {
            return [
                'ok' => TRUE,
                'approval_required' => TRUE,
                'reason' => 'CREDIT_LIMIT_INACTIVE',
                'basic_credit' => $basic_credit,
                'opening_available_credit' => $opening_available_credit,
                'outstanding' => 0,
                'credit_balance' => 0,
                'terms_of_payment' => $credit['TERMS_OF_PAYMENT'] ?? NULL
            ];
        }

        $opening_effective_date = $credit['OPENING_EFFECTIVE_DATE'];

        // Hitung Outstanding setelah Credit Limit berhasil dikunci.
        $outstanding = $this->get_outstanding_credit(
            $cust,
            $exclude_order_customer_id,
            $opening_effective_date
        );

        if ($this->db->trans_status() === FALSE) {
            return [
                'ok' => FALSE,
                'message' => 'Gagal menghitung Outstanding Credit.'
            ];
        }

        // Available Credit menggunakan Opening Available Credit.
        $credit_balance = $opening_available_credit - $outstanding;

        $approval_required = ((float) $order_total > $credit_balance);

        return [
            'ok' => TRUE,
            'approval_required' => $approval_required,
            'reason' => $approval_required
                ? 'INSUFFICIENT_CREDIT_BALANCE'
                : 'CREDIT_BALANCE_AVAILABLE',
            'basic_credit' => $basic_credit,
            'opening_available_credit' => $opening_available_credit,
            'opening_effective_date' => $opening_effective_date,
            'outstanding' => $outstanding,
            'credit_balance' => $credit_balance,
            'terms_of_payment' => $credit['TERMS_OF_PAYMENT'] ?? NULL
        ];
    }

    // =====================================================
    // GET PENDING ORDER APPROVAL LIST
    // =====================================================

    public function get_pending_order_approvals()
    {
        return $this->db
            ->select("
                o.ORDER_ID,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.CUST,
                c.FULL_NAME AS CUSTOMER_NAME,
                o.SUPPLIER,
                s.FULL_NAME AS SUPPLIER_NAME,
                o.SALES_TOTAL,
                o.PURCHASE_TOTAL,
                o.TOTAL_DIFFERENCE,

                a.APPROVAL_ID,
                a.APPROVAL_TYPE,
                a.REFERENCE_ID,
                a.REQUESTED_BY,
                a.REQUESTED_AT,

                oc.ORDER_CUSTOMER_ID,
                oc.ORDER_CUSTOMER_NO,
                oc.BASIS,
                oc.EKOR,
                oc.KG,
                oc.AVG_BW,
                oc.UNIT_PRICE,
                oc.TOTAL,
                oc.APPROVAL_STATUS,

                er.EDIT_REQUEST_ID,
                er.OLD_DATA,
                er.NEW_DATA,
                er.REMARK AS EDIT_REMARK
            ", FALSE)
            ->from($this->order_table . ' o')
            ->join(
                'pav_tr_order_approval a',
                'a.ORDER_ID = o.ORDER_ID',
                'inner'
            )
            ->join(
                $this->edit_request_table . ' er',
                "er.EDIT_REQUEST_ID = a.REFERENCE_ID
                AND a.APPROVAL_TYPE = 'CUSTOMER_ORDER_EDIT'",
                'left',
                FALSE
            )
            ->join(
                $this->customer_table . ' oc',
                "oc.ORDER_ID = o.ORDER_ID
                AND oc.ORDER_CUSTOMER_ID =
                    CASE
                        WHEN a.APPROVAL_TYPE = 'CREDIT_LIMIT'
                            THEN a.REFERENCE_ID
                        WHEN a.APPROVAL_TYPE = 'CUSTOMER_ORDER_EDIT'
                            THEN er.REFERENCE_ID
                    END",
                'inner',
                FALSE
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
            ->where('a.APPROVAL_STATUS', 'PENDING')
            ->group_start()
                ->group_start()
                    ->where('a.APPROVAL_TYPE', 'CREDIT_LIMIT')
                    ->where('o.STATUS', 'PENDING APPROVAL')
                ->group_end()
                ->or_group_start()
                    ->where('a.APPROVAL_TYPE', 'CUSTOMER_ORDER_EDIT')
                    ->where('o.STATUS', 'OPEN')
                    ->where('er.APPROVAL_STATUS', 'PENDING')
                ->group_end()
            ->group_end()
            ->order_by('a.REQUESTED_AT', 'ASC')
            ->get()
            ->result_array();
    }

    // =====================================================
    // GET ORDER APPROVAL DETAIL
    // =====================================================

    public function get_order_approval_detail($approval_id)
    {
        $approval_id = (int) $approval_id;

        if ($approval_id <= 0) {
            return NULL;
        }

        $approval = $this->db
            ->where('APPROVAL_ID', $approval_id)
            ->where('APPROVAL_STATUS', 'PENDING')
            ->limit(1)
            ->get('pav_tr_order_approval')
            ->row_array();

        if (!$approval) {
            return NULL;
        }

        $master = $this->db
            ->where('ORDER_ID', $approval['ORDER_ID'])
            ->limit(1)
            ->get($this->order_table)
            ->row_array();

        if (!$master) {
            return NULL;
        }

        $type = strtoupper(trim($approval['APPROVAL_TYPE']));

        if (
            ($type === 'CREDIT_LIMIT' &&
                $master['STATUS'] !== 'PENDING APPROVAL') ||
            ($type === 'CUSTOMER_ORDER_EDIT' &&
                $master['STATUS'] !== 'OPEN')
        ) {
            return NULL;
        }

        $order = $this->get_order_detail_by_no($master['ORDER_NO']);

        if (!$order) {
            return NULL;
        }

        $order['pending_approval'] = $approval;
        $order['edit_request'] = NULL;
        $order['credit_summary'] = NULL;

        // Credit Limit Approval
        if ($type === 'CREDIT_LIMIT') {
            $credit = $this->get_customer_credit_summary(
                $master['CUST']
            );

            $order['credit_summary'] = $credit['ok']
                ? $credit['data']
                : NULL;
        }

        // Customer Order Edit Approval
        if ($type === 'CUSTOMER_ORDER_EDIT') {
            $edit_request = $this->db
                ->where('EDIT_REQUEST_ID', $approval['REFERENCE_ID'])
                ->where('ORDER_ID', $master['ORDER_ID'])
                ->where('APPROVAL_STATUS', 'PENDING')
                ->limit(1)
                ->get($this->edit_request_table)
                ->row_array();

            if (!$edit_request) {
                return NULL;
            }

            $edit_request['OLD_DATA'] = json_decode(
                $edit_request['OLD_DATA'],
                TRUE
            );

            $edit_request['NEW_DATA'] = json_decode(
                $edit_request['NEW_DATA'],
                TRUE
            );

            $order['edit_request'] = $edit_request;
        }

        return $order;
    }

    // =====================================================
    // GET ORDER LIST
    // =====================================================

    public function get_order_list($filters = [])
    {
        $this->db
            ->select("
                o.ORDER_ID,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                o.CUST,
                c.FULL_NAME AS CUSTOMER_NAME,
                o.SUPPLIER,
                s.FULL_NAME AS SUPPLIER_NAME,
                o.SALES_TOTAL,
                o.PURCHASE_TOTAL,
                o.TOTAL_DIFFERENCE,
                o.STATUS,
                oc.ORDER_CUSTOMER_NO,
                oc.APPROVAL_STATUS,
                oc.PAYMENT_STATUS AS CUSTOMER_PAYMENT_STATUS,
                os.ORDER_SUPPLIER_NO,
                os.PAYMENT_STATUS AS SUPPLIER_PAYMENT_STATUS
            ", FALSE)
            ->from($this->order_table . ' o')
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
            ->join(
                $this->customer_table . ' oc',
                'oc.ORDER_ID = o.ORDER_ID',
                'left'
            )
            ->join(
                $this->supplier_table . ' os',
                'os.ORDER_ID = o.ORDER_ID',
                'left'
            );

        // ---------------------------------------------
        // FILTER ORDER DATE FROM
        // ---------------------------------------------

        if (!empty($filters['date_from'])) {
            $this->db->where(
                'o.ORDER_DATE >=',
                $filters['date_from']
            );
        }

        // ---------------------------------------------
        // FILTER ORDER DATE TO
        // ---------------------------------------------

        if (!empty($filters['date_to'])) {
            $this->db->where(
                'o.ORDER_DATE <=',
                $filters['date_to']
            );
        }

        // ---------------------------------------------
        // FILTER CUSTOMER
        // ---------------------------------------------

        if (!empty($filters['cust'])) {
            $this->db->where(
                'o.CUST',
                $filters['cust']
            );
        }

        // ---------------------------------------------
        // FILTER SUPPLIER
        // ---------------------------------------------

        if (!empty($filters['supplier'])) {
            $this->db->where(
                'o.SUPPLIER',
                $filters['supplier']
            );
        }

        // ---------------------------------------------
        // FILTER STATUS
        // ---------------------------------------------

        if (!empty($filters['status'])) {
            $this->db->where(
                'o.STATUS',
                $filters['status']
            );
        }

        // ---------------------------------------------
        // SEARCH
        // ---------------------------------------------

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $this->db->group_start()
                ->like('o.ORDER_NO', $search)
                ->or_like('oc.ORDER_CUSTOMER_NO', $search)
                ->or_like('os.ORDER_SUPPLIER_NO', $search)
                ->or_like('o.CUST', $search)
                ->or_like('c.FULL_NAME', $search)
                ->or_like('o.SUPPLIER', $search)
                ->or_like('s.FULL_NAME', $search)
                ->group_end();
        }

        $this->db->order_by('o.ORDER_DATE', 'DESC');
        $this->db->order_by('o.ORDER_ID', 'DESC');

        return $this->db
            ->get()
            ->result_array();
    }

    // =====================================================
    // GET MASTER ORDER DETAIL BY ORDER NUMBER
    // =====================================================

    public function get_order_detail_by_no($order_no)
    {
        $order_no = trim((string) $order_no);

        if ($order_no === '') {
            return NULL;
        }

        // ---------------------------------------------
        // MASTER ORDER
        // ---------------------------------------------

        $master = $this->db
            ->select('
                o.ORDER_ID,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                o.CUST,
                c.FULL_NAME AS CUSTOMER_NAME,
                o.SUPPLIER,
                s.FULL_NAME AS SUPPLIER_NAME,
                o.SALES,
                sl.FULL_NAME AS SALES_NAME,
                o.DRIVER,
                o.VEHICLE_NO,
                o.SELLING_PRICE,
                o.PURCHASE_PRICE,
                o.DIFFERENCE_PRICE,
                o.SALES_TOTAL,
                o.PURCHASE_TOTAL,
                o.TOTAL_DIFFERENCE,
                o.STATUS,
                o.REG_EMP,
                o.REG_DATE,
                o.UPD_EMP,
                o.UPD_DATE
            ')
            ->from($this->order_table . ' o')
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
            ->join(
                'pav_cd_sales sl',
                'sl.SALES = o.SALES',
                'left'
            )
            ->where('o.ORDER_NO', $order_no)
            ->limit(1)
            ->get()
            ->row_array();

        if (!$master) {
            return NULL;
        }

        $order_id = $master['ORDER_ID'];

        // ---------------------------------------------
        // CUSTOMER ORDER
        // ---------------------------------------------

        $customer_order = $this->db
            ->where('ORDER_ID', $order_id)
            ->limit(1)
            ->get($this->customer_table)
            ->row_array();

        // ---------------------------------------------
        // SUPPLIER ORDER
        // ---------------------------------------------

        $supplier_order = $this->db
            ->where('ORDER_ID', $order_id)
            ->limit(1)
            ->get($this->supplier_table)
            ->row_array();

        // ---------------------------------------------
        // APPROVAL HISTORY
        // ---------------------------------------------

        $approvals = $this->db
            ->where('ORDER_ID', $order_id)
            ->order_by('APPROVAL_ID', 'DESC')
            ->get('pav_tr_order_approval')
            ->result_array();

        // ---------------------------------------------
        // ORDER HISTORY
        // ---------------------------------------------

        $histories = $this->db
            ->where('ORDER_ID', $order_id)
            ->order_by('ACTION_DATE', 'DESC')
            ->order_by('HISTORY_ID', 'DESC')
            ->get('pav_tr_order_history')
            ->result_array();

        return [
            'master' => $master,
            'customer_order' => $customer_order,
            'supplier_order' => $supplier_order,
            'approvals' => $approvals,
            'histories' => $histories
        ];
    }

    // =====================================================
    // PROCESS ORDER APPROVAL
    // =====================================================

    public function process_order_approval(
        $approval_id,
        $decision,
        $remark,
        $user_code
    ) {
        $approval_id = (int) $approval_id;
        $decision = strtoupper(trim((string) $decision));
        $remark = trim((string) $remark);
        $user_code = trim((string) $user_code);

        $allowed_approvers = ['pavamir', 'pavthomas'];

        if (!in_array(strtolower($user_code), $allowed_approvers, TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Anda tidak memiliki akses untuk melakukan approval.'
            ];
        }

        if (!in_array($decision, ['APPROVED', 'REJECTED'], TRUE)) {
            return [
                'ok' => FALSE,
                'message' => 'Keputusan approval tidak valid.'
            ];
        }

        if ($approval_id <= 0 || $remark === '' || $user_code === '') {
            return [
                'ok' => FALSE,
                'message' => 'Approval ID, remark, dan user wajib tersedia.'
            ];
        }

        $this->db->trans_begin();

        // =================================================
        // 1. Baca Approval tanpa lock untuk mendapatkan ORDER_ID
        // =================================================

        $approval_ref_query = $this->db->query(
            "
                SELECT ORDER_ID
                FROM pav_tr_order_approval
                WHERE APPROVAL_ID = ?
                LIMIT 1
            ",
            [$approval_id]
        );

        $approval_ref = $approval_ref_query
            ? $approval_ref_query->row_array()
            : NULL;

        if (!$approval_ref) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Approval tidak ditemukan.'
            ];
        }

        // =================================================
        // 2. Lock Master Order terlebih dahulu
        // =================================================

        $master_query = $this->db->query(
            "
                SELECT *
                FROM {$this->order_table}
                WHERE ORDER_ID = ?
                LIMIT 1
                FOR UPDATE
            ",
            [$approval_ref['ORDER_ID']]
        );

        $master = $master_query
            ? $master_query->row_array()
            : NULL;

        if (!$master) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Master Order tidak ditemukan.'
            ];
        }

        // =================================================
        // 3. Lock Approval setelah Master Order
        // =================================================

        $approval_query = $this->db->query(
            "
                SELECT *
                FROM pav_tr_order_approval
                WHERE APPROVAL_ID = ?
                AND ORDER_ID = ?
                LIMIT 1
                FOR UPDATE
            ",
            [$approval_id, $master['ORDER_ID']]
        );

        $approval = $approval_query
            ? $approval_query->row_array()
            : NULL;

        if (!$approval || $approval['APPROVAL_STATUS'] !== 'PENDING') {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Approval sudah diproses atau tidak ditemukan.'
            ];
        }

        $order_id = (int) $master['ORDER_ID'];
        $approval_type = strtoupper(trim($approval['APPROVAL_TYPE']));

        // 3. Validasi status Master sesuai tipe approval.
        if (
            ($approval_type === 'CREDIT_LIMIT' &&
                $master['STATUS'] !== 'PENDING APPROVAL') ||
            ($approval_type === 'CUSTOMER_ORDER_EDIT' &&
                $master['STATUS'] !== 'OPEN')
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Status Master Order tidak sesuai dengan approval yang diproses.'
            ];
        }

        // 4. Cegah requester melakukan self-approval.
        if (
            strtolower(trim($approval['REQUESTED_BY'])) ===
            strtolower($user_code)
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Requester tidak diperbolehkan melakukan approval atas request sendiri.'
            ];
        }

        $now = date('Y-m-d H:i:s');

        // =================================================
        // CASE A: CREDIT LIMIT APPROVAL
        // =================================================

        if ($approval_type === 'CREDIT_LIMIT') {

            $customer_query = $this->db->query(
                "
                    SELECT *
                    FROM {$this->customer_table}
                    WHERE ORDER_CUSTOMER_ID = ?
                    AND ORDER_ID = ?
                    LIMIT 1
                    FOR UPDATE
                ",
                [$approval['REFERENCE_ID'], $order_id]
            );

            $customer_order = $customer_query
                ? $customer_query->row_array()
                : NULL;

            if (!$customer_order) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Customer Order tidak ditemukan.'
                ];
            }

            $master_status = ($decision === 'APPROVED')
                ? 'OPEN'
                : 'CANCELLED';

            $this->db
                ->where('ORDER_CUSTOMER_ID', $customer_order['ORDER_CUSTOMER_ID'])
                ->where('ORDER_ID', $order_id)
                ->update($this->customer_table, [
                    'APPROVAL_STATUS' => $decision,
                    'UPD_EMP' => $user_code,
                    'UPD_DATE' => $now
                ]);

            $this->db
                ->where('ORDER_ID', $order_id)
                ->where('STATUS', 'PENDING APPROVAL')
                ->update($this->order_table, [
                    'STATUS' => $master_status,
                    'UPD_EMP' => $user_code,
                    'UPD_DATE' => $now
                ]);

            $this->db->insert($this->history_table, [
                'ORDER_ID' => $order_id,
                'REFERENCE_TYPE' => 'CUSTOMER_ORDER',
                'REFERENCE_ID' => $customer_order['ORDER_CUSTOMER_ID'],
                'ACTION_TYPE' => $decision,
                'OLD_DATA' => json_encode([
                    'MASTER_STATUS' => $master['STATUS'],
                    'APPROVAL_STATUS' => $customer_order['APPROVAL_STATUS']
                ]),
                'NEW_DATA' => json_encode([
                    'MASTER_STATUS' => $master_status,
                    'APPROVAL_STATUS' => $decision,
                    'APPROVED_BY' => $user_code,
                    'APPROVED_AT' => $now,
                    'APPROVAL_REMARK' => $remark
                ]),
                'REMARK' => $remark,
                'ACTION_BY' => $user_code,
                'ACTION_DATE' => $now
            ]);

        // =================================================
        // CASE B: CUSTOMER ORDER EDIT APPROVAL
        // =================================================

        } elseif ($approval_type === 'CUSTOMER_ORDER_EDIT') {

            // Reference ID pada approval adalah EDIT_REQUEST_ID.
            $request_query = $this->db->query(
                "
                    SELECT *
                    FROM {$this->edit_request_table}
                    WHERE EDIT_REQUEST_ID = ?
                    AND ORDER_ID = ?
                    LIMIT 1
                    FOR UPDATE
                ",
                [$approval['REFERENCE_ID'], $order_id]
            );

            $edit_request = $request_query
                ? $request_query->row_array()
                : NULL;

            if (
                !$edit_request ||
                $edit_request['APPROVAL_STATUS'] !== 'PENDING' ||
                $edit_request['REFERENCE_TYPE'] !== 'CUSTOMER_ORDER'
            ) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Edit request tidak ditemukan atau sudah diproses.'
                ];
            }

            // Reference ID pada edit request adalah ORDER_CUSTOMER_ID.
            $customer_query = $this->db->query(
                "
                    SELECT *
                    FROM {$this->customer_table}
                    WHERE ORDER_CUSTOMER_ID = ?
                    AND ORDER_ID = ?
                    LIMIT 1
                    FOR UPDATE
                ",
                [$edit_request['REFERENCE_ID'], $order_id]
            );

            $customer_order = $customer_query
                ? $customer_query->row_array()
                : NULL;

            if (!$customer_order) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Customer Order tidak ditemukan.'
                ];
            }

            // Pastikan belum ada pembayaran.
            if (
                (float) $customer_order['PAID'] > 0 ||
                strtoupper($customer_order['PAYMENT_STATUS']) !== 'OPEN'
            ) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Customer Order sudah memiliki pembayaran dan tidak dapat diubah.'
                ];
            }

            // Ambil Supplier Order untuk validasi BASIS.
            $supplier_query = $this->db->query(
                "
                    SELECT *
                    FROM {$this->supplier_table}
                    WHERE ORDER_ID = ?
                    LIMIT 1
                    FOR UPDATE
                ",
                [$order_id]
            );

            $supplier_order = $supplier_query
                ? $supplier_query->row_array()
                : NULL;

            if (!$supplier_order) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Supplier Order tidak ditemukan.'
                ];
            }

            $old_data = json_decode($edit_request['OLD_DATA'], TRUE);
            $new_data = json_decode($edit_request['NEW_DATA'], TRUE);

            if (!is_array($old_data) || !is_array($new_data)) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Data perubahan tidak valid.'
                ];
            }

            // Cegah request lama menimpa data yang sudah berubah.
            $fields_to_compare = [
                'BASIS',
                'EKOR',
                'KG',
                'AVG_BW',
                'UNIT_PRICE',
                'TOTAL'
            ];

            foreach ($fields_to_compare as $field) {
                if (!array_key_exists($field, $old_data)) {
                    continue;
                }

                $old_value = $old_data[$field];
                $current_value = $customer_order[$field];

                if (is_numeric($old_value) && is_numeric($current_value)) {
                    $same = abs((float) $old_value - (float) $current_value) < 0.0001;
                } else {
                    $same = (string) $old_value === (string) $current_value;
                }

                if (!$same) {
                    $this->db->trans_rollback();

                    return [
                        'ok' => FALSE,
                        'message' => 'Data Customer Order sudah berubah sejak edit request dibuat. Approval tidak dapat dilanjutkan.'
                    ];
                }
            }

            if ($decision === 'APPROVED') {

                // Hitung ulang di server, jangan percaya nilai TOTAL dari NEW_DATA.
                $calculation = $this->calculate_order(
                    $new_data['BASIS'] ?? '',
                    $new_data['EKOR'] ?? 0,
                    $new_data['KG'] ?? 0,
                    $new_data['UNIT_PRICE'] ?? 0
                );

                if (!$calculation['ok']) {
                    $this->db->trans_rollback();
                    return $calculation;
                }

                $calculated = $calculation['data'];

                if ($calculated['BASIS'] !== $supplier_order['BASIS']) {
                    $this->db->trans_rollback();

                    return [
                        'ok' => FALSE,
                        'message' => 'Basis Customer Order harus sama dengan Supplier Order.'
                    ];
                }

                $this->db
                    ->where('ORDER_CUSTOMER_ID', $customer_order['ORDER_CUSTOMER_ID'])
                    ->where('ORDER_ID', $order_id)
                    ->update($this->customer_table, array_merge(
                        $calculated,
                        [
                            'REMAIN' => $calculated['TOTAL'],
                            'UPD_EMP' => $user_code,
                            'UPD_DATE' => $now
                        ]
                    ));

                // Recalculate Master Order financial summary.
                // Recalculate Master Order financial summary.
                if (!$this->update_master_financial(
                    $order_id,
                    $calculated,
                    $supplier_order,
                    $user_code
                )) {
                    $this->db->trans_rollback();

                    return [
                        'ok' => FALSE,
                        'message' => 'Gagal memperbarui nilai Master Order setelah approval.'
                    ];
                }
            }

            // Update edit request decision.
            $this->db
                ->where('EDIT_REQUEST_ID', $edit_request['EDIT_REQUEST_ID'])
                ->where('APPROVAL_STATUS', 'PENDING')
                ->update($this->edit_request_table, [
                    'APPROVAL_STATUS' => $decision,
                    'APPROVED_BY' => $user_code,
                    'APPROVED_AT' => $now,
                    'APPROVAL_REMARK' => $remark
                ]);

            // Master Order tetap OPEN untuk edit approval.
            $this->db->insert($this->history_table, [
                'ORDER_ID' => $order_id,
                'REFERENCE_TYPE' => 'CUSTOMER_ORDER',
                'REFERENCE_ID' => $customer_order['ORDER_CUSTOMER_ID'],
                'ACTION_TYPE' => ($decision === 'APPROVED')
                    ? 'EDIT_APPROVED'
                    : 'EDIT_REJECTED',
                'OLD_DATA' => json_encode($old_data),
                'NEW_DATA' => json_encode([
                    'PROPOSED_DATA' => $new_data,
                    'DECISION' => $decision,
                    'APPROVED_BY' => $user_code,
                    'APPROVED_AT' => $now,
                    'APPROVAL_REMARK' => $remark
                ]),
                'REMARK' => $remark,
                'ACTION_BY' => $user_code,
                'ACTION_DATE' => $now
            ]);

            $master_status = 'OPEN';

        } else {

            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Tipe approval belum didukung: ' . $approval_type
            ];
        }

        // =================================================
        // UPDATE APPROVAL RECORD
        // =================================================

        $this->db
            ->where('APPROVAL_ID', $approval_id)
            ->where('APPROVAL_STATUS', 'PENDING')
            ->update('pav_tr_order_approval', [
                'APPROVAL_STATUS' => $decision,
                'APPROVED_BY' => $user_code,
                'APPROVED_AT' => $now,
                'APPROVAL_REMARK' => $remark
            ]);

        if (
            $this->db->affected_rows() !== 1 ||
            $this->db->trans_status() === FALSE
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Approval gagal diproses atau sudah diproses oleh user lain.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'message' => ($decision === 'APPROVED')
                ? 'Approval berhasil disetujui.'
                : 'Approval berhasil ditolak.',
            'status' => $master_status
        ];
    }

    // =====================================================
    // DATE VALIDATION
    // =====================================================

    private function is_valid_date($date)
    {
        if ($date === '') {
            return FALSE;
        }

        $dt = DateTime::createFromFormat('!Y-m-d', $date);

        return $dt && $dt->format('Y-m-d') === $date;
    }


    // =====================================================
    // SAVE MASTER ORDER + CUSTOMER ORDER + SUPPLIER ORDER
    // =====================================================

    public function save_order($header, $customer, $supplier, $user_code)
    {
        $order_date = trim((string) ($header['ORDER_DATE'] ?? ''));
        $cust = trim((string) ($header['CUST'] ?? ''));
        $supplier_code = trim((string) ($header['SUPPLIER'] ?? ''));

        $driver = strtoupper(trim(
            (string) ($header['DRIVER'] ?? '')
        ));

        $vehicle_no = strtoupper(trim(
            (string) ($header['VEHICLE_NO'] ?? '')
        ));

        // ---------------------------------------------
        // VALIDATE HEADER
        // ---------------------------------------------

        if (!$this->is_valid_date($order_date)) {
            return [
                'ok' => FALSE,
                'message' => 'Tanggal Order tidak valid.'
            ];
        }

        if ($cust === '' || $supplier_code === '') {
            return [
                'ok' => FALSE,
                'message' => 'Customer dan Supplier wajib dipilih.'
            ];
        }

        // ---------------------------------------------
        // VALIDATE CUSTOMER & SUPPLIER
        // ---------------------------------------------

        $customer_master = $this->get_customer_by_code($cust);

        if (!$customer_master) {
            return [
                'ok' => FALSE,
                'message' => 'Customer tidak valid.'
            ];
        }

        $supplier_master = $this->get_supplier_by_code($supplier_code);

        if (!$supplier_master) {
            return [
                'ok' => FALSE,
                'message' => 'Supplier tidak valid.'
            ];
        }

        // ---------------------------------------------
        // VALIDATE CUSTOMER ORDER
        // ---------------------------------------------

        $customer_calc = $this->calculate_order(
            $customer['BASIS'] ?? '',
            $customer['EKOR'] ?? 0,
            $customer['KG'] ?? 0,
            $customer['UNIT_PRICE'] ?? 0
        );

        if (!$customer_calc['ok']) {
            return $customer_calc;
        }

        $customer_data = $customer_calc['data'];

        // ---------------------------------------------
        // VALIDATE SUPPLIER ORDER
        // ---------------------------------------------

        $supplier_calc = $this->calculate_order(
            $supplier['BASIS'] ?? '',
            $supplier['EKOR'] ?? 0,
            $supplier['KG'] ?? 0,
            $supplier['UNIT_PRICE'] ?? 0
        );

        if (!$supplier_calc['ok']) {
            return $supplier_calc;
        }

        $supplier_data = $supplier_calc['data'];

        $sales_code = trim(
            (string) ($header['SALES'] ?? '')
        );

        if ($sales_code === '') {
            return [
                'ok' => FALSE,
                'message' => 'Sales wajib dipilih.'
            ];
        }

        $sales_master = $this->db
            ->where('SALES', $sales_code)
            ->where('STATUS', 'ACTIVE')
            ->limit(1)
            ->get('pav_cd_sales')
            ->row_array();

        if (!$sales_master) {
            return [
                'ok' => FALSE,
                'message' => 'Sales tidak valid atau sudah tidak aktif.'
            ];
        }

        // ---------------------------------------------
        // BASIS MUST BE THE SAME
        // ---------------------------------------------

        if ($customer_data['BASIS'] !== $supplier_data['BASIS']) {
            return [
                'ok' => FALSE,
                'message' => 'Basis Customer Order dan Supplier Order harus sama.'
            ];
        }

        // ---------------------------------------------
        // CALCULATE PRICE DIFFERENCE
        // ---------------------------------------------

        $selling_price = $customer_data['UNIT_PRICE'];
        $purchase_price = $supplier_data['UNIT_PRICE'];

        $difference_price = round(
            $selling_price - $purchase_price,
            2
        );

        $sales_total = $customer_data['TOTAL'];
        $purchase_total = $supplier_data['TOTAL'];

        $total_difference = round(
            $sales_total - $purchase_total,
            2
        );

        // ---------------------------------------------
        // BEGIN DATABASE TRANSACTION
        // ---------------------------------------------

        $this->db->trans_begin();

        // ---------------------------------------------
        // VALIDATE CREDIT LIMIT
        // ---------------------------------------------

        $credit = $this->validate_credit_limit(
            $cust,
            $sales_total
        );

        if (!$credit['ok']) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal memvalidasi Credit Limit.'
            ];
        }

        $approval_required = $credit['approval_required'];

        // =============================================
        // SNAPSHOT TERMS OF PAYMENT
        // =============================================

        $terms_raw = trim(
            (string) ($credit['terms_of_payment'] ?? '')
        );

        // Hanya menerima angka hari bulat.
        // Contoh valid: 0, 14, 30, 45.
        // Nilai kosong atau format tidak valid = NULL.
        $terms_of_payment = (
            $terms_raw !== '' &&
            preg_match('/^\d+$/', $terms_raw)
        )
            ? (int) $terms_raw
            : NULL;

        $master_status = $approval_required
            ? 'PENDING APPROVAL'
            : 'OPEN';

        $customer_approval_status = $approval_required
            ? 'PENDING'
            : 'NOT_REQUIRED';

        $now = date('Y-m-d H:i:s');

        // ---------------------------------------------
        // GENERATE TRANSACTION NUMBERS
        // ---------------------------------------------

        $order_no = $this->generate_number('ORD', $order_date);
        $customer_order_no = $this->generate_number('CO', $order_date);
        $supplier_order_no = $this->generate_number('OS', $order_date);

        if (!$order_no || !$customer_order_no || !$supplier_order_no) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal membuat nomor transaksi.'
            ];
        }

        // ---------------------------------------------
        // INSERT MASTER ORDER
        // ---------------------------------------------

        $master_data = [
            'ORDER_NO' => $order_no,
            'ORDER_DATE' => $order_date,
            'PLANT' => '0001',
            'SALES' => $sales_code,

            'CUST' => $cust,
            'SUPPLIER' => $supplier_code,

            'DRIVER' => $driver !== '' ? $driver : NULL,
            'VEHICLE_NO' => $vehicle_no !== '' ? $vehicle_no : NULL,

            'SELLING_PRICE' => $selling_price,
            'PURCHASE_PRICE' => $purchase_price,
            'DIFFERENCE_PRICE' => $difference_price,

            'SALES_TOTAL' => $sales_total,
            'PURCHASE_TOTAL' => $purchase_total,
            'TOTAL_DIFFERENCE' => $total_difference,

            'STATUS' => $master_status,

            'REG_EMP' => $user_code,
            'REG_DATE' => $now
        ];

        $this->db->insert($this->order_table, $master_data);

        $order_id = $this->db->insert_id();

        if (!$order_id) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Master Order.'
            ];
        }

        // ---------------------------------------------
        // INSERT CUSTOMER ORDER
        // ---------------------------------------------

        $customer_data = array_merge($customer_data, [
            'ORDER_ID' => $order_id,
            'ORDER_CUSTOMER_NO' => $customer_order_no,

            // Snapshot TOP ketika Order dibuat
            'TERMS_OF_PAYMENT' => $terms_of_payment,

            'PAID' => 0,
            'REMAIN' => $sales_total,

            'PAYMENT_STATUS' => 'OPEN',
            'APPROVAL_STATUS' => $customer_approval_status,

            'REG_EMP' => $user_code,
            'REG_DATE' => $now
        ]);

        $this->db->insert(
            $this->customer_table,
            $customer_data
        );

        $customer_order_id = $this->db->insert_id();

        if (!$customer_order_id) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Customer Order.'
            ];
        }

        // ---------------------------------------------
        // INSERT SUPPLIER ORDER
        // ---------------------------------------------

        $supplier_data = array_merge($supplier_data, [
            'ORDER_ID' => $order_id,
            'ORDER_SUPPLIER_NO' => $supplier_order_no,

            'PAID' => 0,
            'REMAIN' => $purchase_total,

            'PAYMENT_STATUS' => 'OPEN',

            'REG_EMP' => $user_code,
            'REG_DATE' => $now
        ]);

        $this->db->insert(
            $this->supplier_table,
            $supplier_data
        );

        $supplier_order_id = $this->db->insert_id();

        if (!$supplier_order_id) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Supplier Order.'
            ];
        }

        // ---------------------------------------------
        // INSERT APPROVAL IF REQUIRED
        // ---------------------------------------------

        if ($approval_required) {

            $this->db->insert('pav_tr_order_approval', [
                'ORDER_ID' => $order_id,
                'APPROVAL_TYPE' => 'CREDIT_LIMIT',
                'REFERENCE_ID' => $customer_order_id,
                'APPROVAL_STATUS' => 'PENDING',
                'REQUESTED_BY' => $user_code,
                'REQUESTED_AT' => $now
            ]);
        }

        // ---------------------------------------------
        // INSERT INITIAL HISTORY
        // ---------------------------------------------

        $history_data = [
            'MASTER_ORDER' => $master_data,
            'CUSTOMER_ORDER' => $customer_data,
            'SUPPLIER_ORDER' => $supplier_data
        ];

        $this->db->insert('pav_tr_order_history', [
            'ORDER_ID' => $order_id,
            'REFERENCE_TYPE' => 'MASTER_ORDER',
            'REFERENCE_ID' => $order_id,
            'ACTION_TYPE' => 'CREATE',
            'OLD_DATA' => NULL,
            'NEW_DATA' => json_encode($history_data),
            'REMARK' => 'Create Master Order',
            'ACTION_BY' => $user_code,
            'ACTION_DATE' => $now
        ]);

        // ---------------------------------------------
        // CHECK TRANSACTION
        // ---------------------------------------------

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan Order. Periksa kembali struktur tabel dan data.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'order_id' => $order_id,
            'order_no' => $order_no,
            'customer_order_no' => $customer_order_no,
            'supplier_order_no' => $supplier_order_no,
            'status' => $master_status,
            'approval_required' => $approval_required
        ];
    }


    // =====================================================
    // CANCEL MASTER ORDER
    // =====================================================

    public function cancel_order($order_no, $remark, $user_code)
    {
        $order_no = trim((string) $order_no);
        $remark = trim((string) $remark);
        $user_code = trim((string) $user_code);

        if ($order_no === '' || $remark === '' || $user_code === '') {
            return [
                'ok' => FALSE,
                'message' => 'Order Number, alasan pembatalan, dan user wajib tersedia.'
            ];
        }

        $this->db->trans_begin();

        // ---------------------------------------------
        // LOCK MASTER ORDER
        // ---------------------------------------------

        $master_query = $this->db->query(
            "
                SELECT *
                FROM {$this->order_table}
                WHERE ORDER_NO = ?
                LIMIT 1
                FOR UPDATE
            ",
            [$order_no]
        );

        if (!$master_query || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal membaca Master Order.'
            ];
        }

        $master = $master_query->row_array();

        if (!$master) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Master Order tidak ditemukan.'
            ];
        }

        $order_id = $master['ORDER_ID'];
        $old_status = strtoupper(trim($master['STATUS']));

        // ---------------------------------------------
        // VALIDATE MASTER STATUS
        // ---------------------------------------------

        if (!in_array($old_status, [
            'OPEN',
            'PENDING APPROVAL'
        ], TRUE)) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Order tidak dapat dibatalkan karena statusnya sudah ' . $old_status . '.'
            ];
        }

        // ---------------------------------------------
        // LOCK CUSTOMER ORDER
        // ---------------------------------------------

        $customer_query = $this->db->query(
            "
                SELECT *
                FROM {$this->customer_table}
                WHERE ORDER_ID = ?
                LIMIT 1
                FOR UPDATE
            ",
            [$order_id]
        );

        if (!$customer_query || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal membaca Customer Order.'
            ];
        }

        $customer = $customer_query->row_array();

        if (!$customer) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Customer Order tidak ditemukan.'
            ];
        }

        // ---------------------------------------------
        // LOCK SUPPLIER ORDER
        // ---------------------------------------------

        $supplier_query = $this->db->query(
            "
                SELECT *
                FROM {$this->supplier_table}
                WHERE ORDER_ID = ?
                LIMIT 1
                FOR UPDATE
            ",
            [$order_id]
        );

        if (!$supplier_query || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal membaca Supplier Order.'
            ];
        }

        $supplier = $supplier_query->row_array();

        if (!$supplier) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Supplier Order tidak ditemukan.'
            ];
        }

        // ---------------------------------------------
        // VALIDATE CUSTOMER PAYMENT
        // ---------------------------------------------

        $customer_paid = (float) ($customer['PAID'] ?? 0);
        $customer_payment_status = strtoupper(
            trim((string) ($customer['PAYMENT_STATUS'] ?? ''))
        );

        if (
            $customer_paid > 0 ||
            $customer_payment_status !== 'OPEN'
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Order tidak dapat dibatalkan karena Customer Order sudah memiliki pembayaran atau status pembayarannya bukan OPEN.'
            ];
        }

        // ---------------------------------------------
        // VALIDATE SUPPLIER PAYMENT
        // ---------------------------------------------

        $supplier_paid = (float) ($supplier['PAID'] ?? 0);
        $supplier_payment_status = strtoupper(
            trim((string) ($supplier['PAYMENT_STATUS'] ?? ''))
        );

        if (
            $supplier_paid > 0 ||
            $supplier_payment_status !== 'OPEN'
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Order tidak dapat dibatalkan karena Supplier Order sudah memiliki pembayaran atau status pembayarannya bukan OPEN.'
            ];
        }

        $now = date('Y-m-d H:i:s');

        // ---------------------------------------------
        // UPDATE MASTER ORDER
        // ---------------------------------------------

        $this->db
            ->where('ORDER_ID', $order_id)
            ->where('STATUS', $old_status)
            ->update($this->order_table, [
                'STATUS' => 'CANCELLED',
                'UPD_EMP' => $user_code,
                'UPD_DATE' => $now
            ]);

        if ($this->db->affected_rows() !== 1) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Master Order gagal dibatalkan atau sudah diproses oleh user lain.'
            ];
        }

        // ---------------------------------------------
        // CLOSE PENDING APPROVAL
        // ---------------------------------------------

        if ($old_status === 'PENDING APPROVAL') {
            $this->db
                ->where('ORDER_ID', $order_id)
                ->where('APPROVAL_STATUS', 'PENDING')
                ->update('pav_tr_order_approval', [
                    'APPROVAL_STATUS' => 'CANCELLED',
                    'APPROVAL_REMARK' => 'Order dibatalkan: ' . $remark
                ]);
        }

        // ---------------------------------------------
        // INSERT ORDER HISTORY
        // ---------------------------------------------

        $this->db->insert('pav_tr_order_history', [
            'ORDER_ID' => $order_id,
            'REFERENCE_TYPE' => 'MASTER_ORDER',
            'REFERENCE_ID' => $order_id,
            'ACTION_TYPE' => 'CANCELLED',
            'OLD_DATA' => json_encode([
                'MASTER_STATUS' => $old_status,
                'CUSTOMER_PAID' => $customer_paid,
                'CUSTOMER_PAYMENT_STATUS' => $customer_payment_status,
                'SUPPLIER_PAID' => $supplier_paid,
                'SUPPLIER_PAYMENT_STATUS' => $supplier_payment_status
            ]),
            'NEW_DATA' => json_encode([
                'MASTER_STATUS' => 'CANCELLED',
                'CANCELLED_BY' => $user_code,
                'CANCELLED_AT' => $now,
                'REMARK' => $remark
            ]),
            'REMARK' => $remark,
            'ACTION_BY' => $user_code,
            'ACTION_DATE' => $now
        ]);

        // ---------------------------------------------
        // CHECK TRANSACTION
        // ---------------------------------------------

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal membatalkan Order. Tidak ada perubahan yang disimpan.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'message' => 'Master Order berhasil dibatalkan.',
            'status' => 'CANCELLED'
        ];
    }

    private function update_master_financial($order_id, $customer, $supplier, $user_code)
    {
        $selling_price = (float) $customer['UNIT_PRICE'];
        $purchase_price = (float) $supplier['UNIT_PRICE'];

        $sales_total = (float) $customer['TOTAL'];
        $purchase_total = (float) $supplier['TOTAL'];

        $this->db
            ->where('ORDER_ID', $order_id)
            ->update($this->order_table, [
                'SELLING_PRICE'    => $selling_price,
                'PURCHASE_PRICE'   => $purchase_price,
                'DIFFERENCE_PRICE' => $selling_price - $purchase_price,

                'SALES_TOTAL'      => $sales_total,
                'PURCHASE_TOTAL'   => $purchase_total,
                'TOTAL_DIFFERENCE' => $sales_total - $purchase_total,

                'UPD_EMP'          => $user_code,
                'UPD_DATE'         => date('Y-m-d H:i:s')
            ]);

        return $this->db->trans_status() !== FALSE;
    }

    public function edit_customer_order($order_no, $input, $remark, $user_code)
    {
        $order_no = trim((string) $order_no);
        $remark   = trim((string) $remark);
        $user_code = trim((string) $user_code);

        if ($order_no === '' || $remark === '' || $user_code === '') {
            return [
                'ok' => FALSE,
                'message' => 'Order Number, remark, dan user wajib tersedia.'
            ];
        }

        $calculation = $this->calculate_order(
            $input['BASIS'] ?? '',
            $input['EKOR'] ?? 0,
            $input['KG'] ?? 0,
            $input['UNIT_PRICE'] ?? 0
        );

        if (!$calculation['ok']) {
            return $calculation;
        }

        $new_data = $calculation['data'];

        $this->db->trans_begin();

        // Lock Master Order
        $master_query = $this->db->query(
            "SELECT * FROM {$this->order_table}
            WHERE ORDER_NO = ?
            LIMIT 1 FOR UPDATE",
            [$order_no]
        );

        $master = $master_query ? $master_query->row_array() : NULL;

        if (!$master || $master['STATUS'] !== 'OPEN') {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Customer Order hanya dapat diedit jika Master Order berstatus OPEN.'
            ];
        }

        $order_id = $master['ORDER_ID'];

        // Lock Customer Order
        $customer_query = $this->db->query(
            "SELECT * FROM {$this->customer_table}
            WHERE ORDER_ID = ?
            LIMIT 1 FOR UPDATE",
            [$order_id]
        );

        $customer = $customer_query
            ? $customer_query->row_array()
            : NULL;

        // Lock Supplier Order
        $supplier_query = $this->db->query(
            "SELECT * FROM {$this->supplier_table}
            WHERE ORDER_ID = ?
            LIMIT 1 FOR UPDATE",
            [$order_id]
        );

        $supplier = $supplier_query
            ? $supplier_query->row_array()
            : NULL;

        if (!$customer || !$supplier) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Data Customer Order atau Supplier Order tidak ditemukan.'
            ];
        }

        // Validasi pembayaran Customer Order
        if (
            (float) $customer['PAID'] > 0 ||
            strtoupper($customer['PAYMENT_STATUS']) !== 'OPEN'
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Customer Order tidak dapat diedit karena sudah memiliki pembayaran.'
            ];
        }

        if (
            !in_array(
                strtoupper($customer['APPROVAL_STATUS']),
                ['NOT_REQUIRED', 'APPROVED'],
                TRUE
            )
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Customer Order belum memenuhi status approval untuk diedit.'
            ];
        }

        // Basis Customer dan Supplier harus tetap sama
        if ($new_data['BASIS'] !== strtoupper($supplier['BASIS'])) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Basis Customer Order harus sama dengan Supplier Order.'
            ];
        }

        // Pastikan tidak ada edit request yang masih pending
        $pending_request = $this->db
            ->where('ORDER_ID', $order_id)
            ->where('REFERENCE_TYPE', 'CUSTOMER_ORDER')
            ->where('APPROVAL_STATUS', 'PENDING')
            ->limit(1)
            ->get($this->edit_request_table)
            ->row_array();

        if ($pending_request) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Masih terdapat Customer Order edit request yang menunggu approval.'
            ];
        }

        // Periksa Credit Limit dengan mengeluarkan outstanding order ini
        // dari perhitungan outstanding sebelum membandingkan nilai baru.
        $credit = $this->validate_credit_limit(
            $master['CUST'],
            $new_data['TOTAL'],
            $customer['ORDER_CUSTOMER_ID']
        );

        if (!$credit['ok']) {
            $this->db->trans_rollback();
            return $credit;
        }

        // Credit balance sudah dihitung dengan mengecualikan
        // Customer Order yang sedang diedit.
        $available_for_edit = (float) $credit['credit_balance'];

        // Edit dapat langsung dilakukan hanya jika:
        // 1. Credit Limit aktif.
        // 2. Credit Limit tersedia.
        // 3. Total baru tidak melebihi Credit Balance.
        $approval_required = !(
            $credit['reason'] === 'CREDIT_BALANCE_AVAILABLE' &&
            $new_data['TOTAL'] <= $available_for_edit
        );

        $now = date('Y-m-d H:i:s');

        $old_data = [
            'BASIS'      => $customer['BASIS'],
            'EKOR'       => $customer['EKOR'],
            'KG'         => $customer['KG'],
            'AVG_BW'     => $customer['AVG_BW'],
            'UNIT_PRICE' => $customer['UNIT_PRICE'],
            'TOTAL'      => $customer['TOTAL']
        ];

        if ($approval_required) {
            // Simpan perubahan sebagai request, jangan mengubah data aktif.
            $this->db->insert($this->edit_request_table, [
                'ORDER_ID'       => $order_id,
                'REFERENCE_TYPE' => 'CUSTOMER_ORDER',
                'REFERENCE_ID'   => $customer['ORDER_CUSTOMER_ID'],
                'OLD_DATA'       => json_encode($old_data),
                'NEW_DATA'       => json_encode($new_data),
                'REMARK'         => $remark,
                'APPROVAL_STATUS'=> 'PENDING',
                'REQUESTED_BY'   => $user_code,
                'REQUESTED_AT'   => $now
            ]);

            $edit_request_id = $this->db->insert_id();

            if (!$edit_request_id) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Gagal membuat Customer Order edit request.'
                ];
            }

            $this->db->insert($this->approval_table, [
                'ORDER_ID'       => $order_id,
                'APPROVAL_TYPE'  => 'CUSTOMER_ORDER_EDIT',
                'REFERENCE_ID'   => $edit_request_id,
                'APPROVAL_STATUS'=> 'PENDING',
                'REQUESTED_BY'   => $user_code,
                'REQUESTED_AT'   => $now
            ]);

            $this->db->insert($this->history_table, [
                'ORDER_ID'       => $order_id,
                'REFERENCE_TYPE' => 'CUSTOMER_ORDER',
                'REFERENCE_ID'   => $customer['ORDER_CUSTOMER_ID'],
                'ACTION_TYPE'    => 'EDIT_REQUESTED',
                'OLD_DATA'       => json_encode($old_data),
                'NEW_DATA'       => json_encode($new_data),
                'REMARK'         => $remark,
                'ACTION_BY'      => $user_code,
                'ACTION_DATE'    => $now
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();

                return [
                    'ok' => FALSE,
                    'message' => 'Gagal menyimpan edit request.'
                ];
            }

            $this->db->trans_commit();

            return [
                'ok' => TRUE,
                'approval_required' => TRUE,
                'message' => 'Perubahan Customer Order berhasil diajukan dan menunggu approval.'
            ];
        }

        // Update langsung jika Credit Limit mencukupi.
        $update_data = array_merge($new_data, [
            'PAID'      => 0,
            'REMAIN'    => $new_data['TOTAL'],
            'UPD_EMP'   => $user_code,
            'UPD_DATE'  => $now
        ]);

        $this->db
            ->where('ORDER_CUSTOMER_ID', $customer['ORDER_CUSTOMER_ID'])
            ->where('ORDER_ID', $order_id)
            ->update($this->customer_table, $update_data);

        $updated_customer = array_merge($customer, $update_data);

        if (!$this->update_master_financial(
            $order_id,
            $updated_customer,
            $supplier,
            $user_code
        )) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal memperbarui nilai Master Order.'
            ];
        }

        $this->db->insert($this->history_table, [
            'ORDER_ID'       => $order_id,
            'REFERENCE_TYPE' => 'CUSTOMER_ORDER',
            'REFERENCE_ID'   => $customer['ORDER_CUSTOMER_ID'],
            'ACTION_TYPE'    => 'EDIT',
            'OLD_DATA'       => json_encode($old_data),
            'NEW_DATA'       => json_encode($new_data),
            'REMARK'         => $remark,
            'ACTION_BY'      => $user_code,
            'ACTION_DATE'    => $now
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan perubahan Customer Order.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'approval_required' => FALSE,
            'message' => 'Customer Order berhasil diperbarui.'
        ];
    }

    public function edit_supplier_order($order_no, $input, $remark, $user_code)
    {
        $order_no = trim((string) $order_no);
        $remark = trim((string) $remark);
        $user_code = trim((string) $user_code);

        if ($order_no === '' || $remark === '' || $user_code === '') {
            return [
                'ok' => FALSE,
                'message' => 'Order Number, remark, dan user wajib tersedia.'
            ];
        }

        $calculation = $this->calculate_order(
            $input['BASIS'] ?? '',
            $input['EKOR'] ?? 0,
            $input['KG'] ?? 0,
            $input['UNIT_PRICE'] ?? 0
        );

        if (!$calculation['ok']) {
            return $calculation;
        }

        $new_data = $calculation['data'];

        $this->db->trans_begin();

        // Lock Master Order
        $master_query = $this->db->query(
            "SELECT * FROM {$this->order_table}
            WHERE ORDER_NO = ?
            LIMIT 1 FOR UPDATE",
            [$order_no]
        );

        $master = $master_query
            ? $master_query->row_array()
            : NULL;

        if (!$master || $master['STATUS'] !== 'OPEN') {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Supplier Order hanya dapat diedit jika Master Order berstatus OPEN.'
            ];
        }

        $order_id = $master['ORDER_ID'];

        // Lock Customer Order
        $customer_query = $this->db->query(
            "SELECT * FROM {$this->customer_table}
            WHERE ORDER_ID = ?
            LIMIT 1 FOR UPDATE",
            [$order_id]
        );

        $customer = $customer_query
            ? $customer_query->row_array()
            : NULL;

        // Lock Supplier Order
        $supplier_query = $this->db->query(
            "SELECT * FROM {$this->supplier_table}
            WHERE ORDER_ID = ?
            LIMIT 1 FOR UPDATE",
            [$order_id]
        );

        $supplier = $supplier_query
            ? $supplier_query->row_array()
            : NULL;

        if (!$customer || !$supplier) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Customer Order atau Supplier Order tidak ditemukan.'
            ];
        }

        // Validasi pembayaran Supplier
        if (
            (float) $supplier['PAID'] > 0 ||
            strtoupper($supplier['PAYMENT_STATUS']) !== 'OPEN'
        ) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Supplier Order tidak dapat diedit karena sudah memiliki pembayaran.'
            ];
        }

        // Basis harus sama dengan Customer Order
        if ($new_data['BASIS'] !== strtoupper($customer['BASIS'])) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Basis Supplier Order harus sama dengan Customer Order.'
            ];
        }

        $old_data = [
            'BASIS'      => $supplier['BASIS'],
            'EKOR'       => $supplier['EKOR'],
            'KG'         => $supplier['KG'],
            'AVG_BW'     => $supplier['AVG_BW'],
            'UNIT_PRICE' => $supplier['UNIT_PRICE'],
            'TOTAL'      => $supplier['TOTAL']
        ];

        $now = date('Y-m-d H:i:s');

        // Update Supplier Order
        $update_data = array_merge($new_data, [
            'PAID'     => 0,
            'REMAIN'   => $new_data['TOTAL'],
            'UPD_EMP'  => $user_code,
            'UPD_DATE' => $now
        ]);

        $this->db
            ->where('ORDER_SUPPLIER_ID', $supplier['ORDER_SUPPLIER_ID'])
            ->where('ORDER_ID', $order_id)
            ->update($this->supplier_table, $update_data);

        $updated_supplier = array_merge($supplier, $update_data);

        // Update financial summary Master Order
        if (!$this->update_master_financial(
            $order_id,
            $customer,
            $updated_supplier,
            $user_code
        )) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal memperbarui financial summary Master Order.'
            ];
        }

        // Insert history
        $this->db->insert($this->history_table, [
            'ORDER_ID'       => $order_id,
            'REFERENCE_TYPE' => 'SUPPLIER_ORDER',
            'REFERENCE_ID'   => $supplier['ORDER_SUPPLIER_ID'],
            'ACTION_TYPE'    => 'EDIT',
            'OLD_DATA'       => json_encode($old_data),
            'NEW_DATA'       => json_encode($new_data),
            'REMARK'         => $remark,
            'ACTION_BY'      => $user_code,
            'ACTION_DATE'    => $now
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return [
                'ok' => FALSE,
                'message' => 'Gagal menyimpan perubahan Supplier Order.'
            ];
        }

        $this->db->trans_commit();

        return [
            'ok' => TRUE,
            'message' => 'Supplier Order berhasil diperbarui.'
        ];
    }

    public function get_sales_options()
    {
        return $this->db
            ->select('SALES, FULL_NAME')
            ->from('pav_cd_sales')
            ->where('STATUS', 'ACTIVE')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }

}
