<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportPayment_model extends CI_Model
{
    private $payment_table = 'pav_tr_payment';
    private $detail_table  = 'pav_tr_payment_detail';
    private $order_table   = 'pav_tr_order';
    private $supplier_order_table = 'pav_tr_order_supplier';
    private $customer_table = 'pav_cd_customer';

    // =====================================================
    // SUPPLIER OPTIONS
    // =====================================================

    public function get_supplier_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from($this->customer_table)
            ->where('CUST_KIND', 'SUPPLIER')
            ->where('STATUS', 'ACTIVE')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }

    // =====================================================
    // APPLY REPORT FILTERS
    // =====================================================

    private function apply_report_filters($filters = [])
    {
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
            $this->db->where(
                'p.SUPPLIER',
                $filters['supplier']
            );
        }

        if (!empty($filters['method'])) {
            $this->db->where(
                'p.PAYMENT_METHOD',
                $filters['method']
            );
        }

        if (!empty($filters['type'])) {
            $this->db->where(
                'p.PAYMENT_TYPE',
                $filters['type']
            );
        }

        if (!empty($filters['status'])) {
            $this->db->where(
                'p.STATUS',
                $filters['status']
            );
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $this->db->group_start()
                ->like('p.PAYMENT_NO', $search)
                ->or_like('p.SLIP_NO', $search)
                ->or_like('p.SUPPLIER', $search)
                ->or_like('c.FULL_NAME', $search)
                ->or_where(
                    "EXISTS (
                        SELECT 1
                        FROM {$this->detail_table} pd_search
                        INNER JOIN {$this->supplier_order_table} os_search
                            ON os_search.ORDER_SUPPLIER_ID =
                               pd_search.ORDER_SUPPLIER_ID
                        WHERE pd_search.PAYMENT_ID = p.PAYMENT_ID
                        AND os_search.ORDER_SUPPLIER_NO LIKE " .
                        $this->db->escape('%' . $search . '%') . "
                    )",
                    NULL,
                    FALSE
                )
                ->group_end();
        }
    }

    // =====================================================
    // PAYMENT REPORT LIST
    // =====================================================

    public function get_report_payment($filters = [])
    {
        $this->db
            ->select("
                p.PAYMENT_ID,
                p.PAYMENT_NO,
                p.PAYMENT_DATE,
                p.SUPPLIER,
                c.FULL_NAME AS SUPPLIER_NAME,
                p.PAYMENT_METHOD,
                p.PAYMENT_TYPE,
                p.SLIP_NO,
                p.TOTAL_PAYMENT,
                p.REMARK,
                p.STATUS,
                p.REG_EMP,
                p.REG_DATE,
                p.UPD_EMP,
                p.UPD_DATE,
                COALESCE(orders.ORDER_LIST, '') AS ORDER_LIST
            ", FALSE)
            ->from($this->payment_table . ' p')
            ->join(
                $this->customer_table . ' c',
                'c.CUST = p.SUPPLIER',
                'left'
            )
            ->join("
                (
                    SELECT
                        pd.PAYMENT_ID,
                        GROUP_CONCAT(
                            DISTINCT os.ORDER_SUPPLIER_NO
                            ORDER BY os.ORDER_SUPPLIER_NO
                            SEPARATOR ', '
                        ) AS ORDER_LIST
                    FROM {$this->detail_table} pd
                    INNER JOIN {$this->supplier_order_table} os
                        ON os.ORDER_SUPPLIER_ID = pd.ORDER_SUPPLIER_ID
                    GROUP BY pd.PAYMENT_ID
                ) orders
            ", 'orders.PAYMENT_ID = p.PAYMENT_ID', 'left', FALSE);

        $this->apply_report_filters($filters);

        return $this->db
            ->order_by('p.PAYMENT_DATE', 'DESC')
            ->order_by('p.PAYMENT_ID', 'DESC')
            ->get()
            ->result_array();
    }

    // =====================================================
    // PAYMENT SUMMARY
    // =====================================================

    public function get_report_summary($filters = [])
    {
        $this->db
            ->select("
                COUNT(p.PAYMENT_ID) AS TOTAL_TRANSACTIONS,

                SUM(
                    CASE
                        WHEN p.STATUS = 'POSTED' THEN 1
                        ELSE 0
                    END
                ) AS TOTAL_POSTED,

                SUM(
                    CASE
                        WHEN p.STATUS = 'VOID' THEN 1
                        ELSE 0
                    END
                ) AS TOTAL_VOID,

                SUM(
                    CASE
                        WHEN p.STATUS = 'POSTED'
                        THEN p.TOTAL_PAYMENT
                        ELSE 0
                    END
                ) AS TOTAL_PAYMENT_POSTED
            ", FALSE)
            ->from($this->payment_table . ' p')
            ->join(
                $this->customer_table . ' c',
                'c.CUST = p.SUPPLIER',
                'left'
            );

        $this->apply_report_filters($filters);

        $summary = $this->db
            ->get()
            ->row_array();

        return [
            'TOTAL_TRANSACTIONS' => (int) ($summary['TOTAL_TRANSACTIONS'] ?? 0),
            'TOTAL_POSTED'       => (int) ($summary['TOTAL_POSTED'] ?? 0),
            'TOTAL_VOID'         => (int) ($summary['TOTAL_VOID'] ?? 0),
            'TOTAL_PAYMENT_POSTED' => (float) ($summary['TOTAL_PAYMENT_POSTED'] ?? 0)
        ];
    }

    // =====================================================
    // PAYMENT ALLOCATION DETAIL
    // =====================================================

    public function get_allocation_detail($payment_id)
    {
        $payment_id = (int) $payment_id;

        if ($payment_id <= 0) {
            return [];
        }

        return $this->db
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
            ->where('pd.PAYMENT_ID', $payment_id)
            ->order_by('o.ORDER_DATE', 'ASC')
            ->order_by('os.ORDER_SUPPLIER_ID', 'ASC')
            ->get()
            ->result_array();
    }
}