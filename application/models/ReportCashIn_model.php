<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCashIn_model extends CI_Model
{
    private $cash_in_table = 'pav_tr_cash_in';
    private $detail_table  = 'pav_tr_cash_in_detail';

    // =====================================================
    // CUSTOMER OPTIONS
    // =====================================================
    public function get_customer_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from('pav_cd_customer')
            ->where('CUST_KIND', 'CUSTOMER')
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
                'ci.CASH_IN_DATE >=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {
            $this->db->where(
                'ci.CASH_IN_DATE <=',
                $filters['date_to']
            );
        }

        if (!empty($filters['customer'])) {
            $this->db->where(
                'ci.CUSTOMER',
                $filters['customer']
            );
        }

        if (!empty($filters['method'])) {
            $this->db->where(
                'ci.CASH_IN_METHOD',
                $filters['method']
            );
        }

        if (!empty($filters['status'])) {
            $this->db->where(
                'ci.STATUS',
                $filters['status']
            );
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $this->db->group_start()
                ->like('ci.CASH_IN_NO', $search)
                ->or_like('ci.SLIP_NO', $search)
                ->or_like('ci.CUSTOMER', $search)
                ->or_like('c.FULL_NAME', $search)
                ->or_where(
                    "EXISTS (
                        SELECT 1
                        FROM pav_tr_cash_in_detail cid_search
                        INNER JOIN pav_tr_order_customer oc_search
                            ON oc_search.ORDER_CUSTOMER_ID = cid_search.ORDER_CUSTOMER_ID
                        INNER JOIN pav_tr_order o_search
                            ON o_search.ORDER_ID = oc_search.ORDER_ID
                        WHERE cid_search.CASH_IN_ID = ci.CASH_IN_ID
                          AND (
                              oc_search.ORDER_CUSTOMER_NO LIKE " .
                              $this->db->escape('%' . $search . '%') . "
                              OR o_search.ORDER_NO LIKE " .
                              $this->db->escape('%' . $search . '%') . "
                          )
                    )",
                    NULL,
                    FALSE
                )
                ->group_end();
        }
    }

    // =====================================================
    // REPORT CASH IN LIST
    // One row per Cash In header
    // =====================================================
    public function get_report_cash_in($filters = [])
    {
        $this->db
            ->select("
                ci.CASH_IN_ID,
                ci.CASH_IN_NO,
                ci.CASH_IN_DATE,
                ci.CUSTOMER,
                c.FULL_NAME AS CUSTOMER_NAME,
                ci.CASH_IN_METHOD,
                ci.CASH_IN_TYPE,
                ci.SLIP_NO,
                ci.TOTAL_CASH_IN,
                ci.STATUS,
                ci.REMARK,
                ci.REG_EMP,
                ci.REG_DATE,
                ci.UPD_EMP,
                ci.UPD_DATE,
                COALESCE(alloc.TOTAL_ALLOCATION, 0) AS TOTAL_ALLOCATION,
                COALESCE(alloc.ALLOCATION_COUNT, 0) AS ALLOCATION_COUNT,
                alloc.ORDER_LIST
            ", FALSE)
            ->from($this->cash_in_table . ' ci')
            ->join(
                'pav_cd_customer c',
                'c.CUST = ci.CUSTOMER',
                'left'
            )
            ->join(
                "(
                    SELECT
                        cid.CASH_IN_ID,
                        SUM(cid.PAID_AMOUNT) AS TOTAL_ALLOCATION,
                        COUNT(cid.CASH_IN_ID) AS ALLOCATION_COUNT,
                        GROUP_CONCAT(
                            DISTINCT oc.ORDER_CUSTOMER_NO
                            ORDER BY oc.ORDER_CUSTOMER_NO
                            SEPARATOR ', '
                        ) AS ORDER_LIST
                    FROM {$this->detail_table} cid
                    LEFT JOIN pav_tr_order_customer oc
                        ON oc.ORDER_CUSTOMER_ID = cid.ORDER_CUSTOMER_ID
                    GROUP BY cid.CASH_IN_ID
                ) alloc",
                'alloc.CASH_IN_ID = ci.CASH_IN_ID',
                'left',
                FALSE
            );

        $this->apply_report_filters($filters);

        return $this->db
            ->order_by('ci.CASH_IN_DATE', 'DESC')
            ->order_by('ci.CASH_IN_ID', 'DESC')
            ->get()
            ->result_array();
    }

    // =====================================================
    // REPORT SUMMARY
    // =====================================================
    public function get_report_summary($filters = [])
    {
        $this->db
            ->select("
                COUNT(DISTINCT ci.CASH_IN_ID) AS TOTAL_RECORDS,

                COALESCE(SUM(
                    CASE
                        WHEN ci.STATUS = 'POSTED'
                        THEN ci.TOTAL_CASH_IN
                        ELSE 0
                    END
                ), 0) AS TOTAL_POSTED,

                COALESCE(SUM(
                    CASE
                        WHEN ci.STATUS = 'VOID'
                        THEN ci.TOTAL_CASH_IN
                        ELSE 0
                    END
                ), 0) AS TOTAL_VOID,

                COALESCE(SUM(
                    CASE
                        WHEN ci.STATUS = 'POSTED'
                        THEN COALESCE(alloc.TOTAL_ALLOCATION, 0)
                        ELSE 0
                    END
                ), 0) AS TOTAL_ALLOCATION
            ", FALSE)
            ->from($this->cash_in_table . ' ci')
            ->join(
                'pav_cd_customer c',
                'c.CUST = ci.CUSTOMER',
                'left'
            )
            ->join(
                "(
                    SELECT
                        CASH_IN_ID,
                        SUM(PAID_AMOUNT) AS TOTAL_ALLOCATION
                    FROM {$this->detail_table}
                    GROUP BY CASH_IN_ID
                ) alloc",
                'alloc.CASH_IN_ID = ci.CASH_IN_ID',
                'left',
                FALSE
            );

        $this->apply_report_filters($filters);

        return $this->db->get()->row_array();
    }

    // =====================================================
    // CASH IN ALLOCATION DETAIL
    // =====================================================
    public function get_allocation_detail($cash_in_id)
    {
        return $this->db
            ->select("
                cid.CASH_IN_ID,
                cid.ORDER_CUSTOMER_ID,
                cid.PAID_AMOUNT,
                oc.ORDER_CUSTOMER_NO,
                oc.ORDER_ID,
                oc.TOTAL AS ORDER_TOTAL,
                oc.REMAIN AS ORDER_REMAIN,
                o.ORDER_NO,
                o.ORDER_DATE
            ", FALSE)
            ->from($this->detail_table . ' cid')
            ->join(
                'pav_tr_order_customer oc',
                'oc.ORDER_CUSTOMER_ID = cid.ORDER_CUSTOMER_ID',
                'left'
            )
            ->join(
                'pav_tr_order o',
                'o.ORDER_ID = oc.ORDER_ID',
                'left'
            )
            ->where('cid.CASH_IN_ID', (int) $cash_in_id)
            ->order_by('o.ORDER_DATE', 'ASC')
            ->order_by('oc.ORDER_CUSTOMER_ID', 'ASC')
            ->get()
            ->result_array();
    }
}