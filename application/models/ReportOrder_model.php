
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportOrder_model extends CI_Model
{
    private $order_table = 'pav_tr_order';
    private $customer_order_table = 'pav_tr_order_customer';
    private $supplier_order_table = 'pav_tr_order_supplier';
    private $customer_master_table = 'pav_cd_customer';

    /**
     * GET COMBINED REPORT ORDER
     *
     * Satu Customer Order = satu baris.
     * Satu Supplier Order = satu baris.
     */
    public function get_report_order($filters = [])
    {
        $where = [];
        $params = [];

        // DATE FROM
        if (!empty($filters['date_from'])) {
            $where[] = 'o.ORDER_DATE >= ?';
            $params[] = $filters['date_from'];
        }

        // DATE TO
        if (!empty($filters['date_to'])) {
            $where[] = 'o.ORDER_DATE <= ?';
            $params[] = $filters['date_to'];
        }

        // CUSTOMER
        if (!empty($filters['cust'])) {
            $where[] = 'o.CUST = ?';
            $params[] = $filters['cust'];
        }

        // SUPPLIER
        if (!empty($filters['supplier'])) {
            $where[] = 'o.SUPPLIER = ?';
            $params[] = $filters['supplier'];
        }

        // MASTER ORDER STATUS
        if (!empty($filters['status'])) {
            $where[] = 'o.STATUS = ?';
            $params[] = $filters['status'];
        }

        $master_where = '';

        if (!empty($where)) {
            $master_where = ' AND ' . implode(' AND ', $where);
        }

        // SEARCH CUSTOMER ORDER
        $customer_search = '';
        $customer_params = $params;

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $customer_search = "
                AND (
                    o.ORDER_NO LIKE ?
                    OR oc.ORDER_CUSTOMER_NO LIKE ?
                    OR o.CUST LIKE ?
                    OR c.FULL_NAME LIKE ?
                    OR o.SUPPLIER LIKE ?
                    OR s.FULL_NAME LIKE ?
                )
            ";

            for ($i = 0; $i < 6; $i++) {
                $customer_params[] = '%' . $search . '%';
            }
        }

        // SEARCH SUPPLIER ORDER
        $supplier_search = '';
        $supplier_params = $params;

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $supplier_search = "
                AND (
                    o.ORDER_NO LIKE ?
                    OR os.ORDER_SUPPLIER_NO LIKE ?
                    OR o.CUST LIKE ?
                    OR c.FULL_NAME LIKE ?
                    OR o.SUPPLIER LIKE ?
                    OR s.FULL_NAME LIKE ?
                )
            ";

            for ($i = 0; $i < 6; $i++) {
                $supplier_params[] = '%' . $search . '%';
            }
        }

        $sql = "
            SELECT *
            FROM (
                /* =====================================
                   CUSTOMER ORDER
                ===================================== */

                SELECT
                    o.ORDER_ID,
                    o.ORDER_NO,
                    o.ORDER_DATE,

                    oc.ORDER_CUSTOMER_ID AS DETAIL_ID,
                    oc.ORDER_CUSTOMER_NO AS TRANSACTION_NO,

                    'CUSTOMER' AS ORDER_TYPE,

                    o.CUST AS PARTY_CODE,
                    c.FULL_NAME AS PARTY_NAME,

                    oc.BASIS,
                    oc.EKOR,
                    oc.KG,
                    oc.AVG_BW,
                    oc.UNIT_PRICE AS PRICE,
                    oc.TOTAL,

                    NULL AS DIFF_PRICE,
                    NULL AS TOTAL_DIFF_PRICE,

                    oc.REMAIN,
                    oc.PAYMENT_STATUS,

                    o.STATUS AS ORDER_STATUS,

                    1 AS SORT_ORDER

                FROM {$this->order_table} o

                INNER JOIN {$this->customer_order_table} oc
                    ON oc.ORDER_ID = o.ORDER_ID

                LEFT JOIN {$this->customer_master_table} c
                    ON c.CUST = o.CUST

                LEFT JOIN {$this->customer_master_table} s
                    ON s.CUST = o.SUPPLIER

                WHERE 1 = 1
                {$master_where}
                {$customer_search}

                UNION ALL

                /* =====================================
                   SUPPLIER ORDER
                ===================================== */

                SELECT
                    o.ORDER_ID,
                    o.ORDER_NO,
                    o.ORDER_DATE,

                    os.ORDER_SUPPLIER_ID AS DETAIL_ID,
                    os.ORDER_SUPPLIER_NO AS TRANSACTION_NO,

                    'SUPPLIER' AS ORDER_TYPE,

                    o.SUPPLIER AS PARTY_CODE,
                    s.FULL_NAME AS PARTY_NAME,

                    os.BASIS,
                    os.EKOR,
                    os.KG,
                    os.AVG_BW,
                    os.UNIT_PRICE AS PRICE,
                    os.TOTAL,

                    o.DIFFERENCE_PRICE AS DIFF_PRICE,
                    o.TOTAL_DIFFERENCE AS TOTAL_DIFF_PRICE,

                    os.REMAIN,
                    os.PAYMENT_STATUS,

                    o.STATUS AS ORDER_STATUS,

                    2 AS SORT_ORDER

                FROM {$this->order_table} o

                INNER JOIN {$this->supplier_order_table} os
                    ON os.ORDER_ID = o.ORDER_ID

                LEFT JOIN {$this->customer_master_table} c
                    ON c.CUST = o.CUST

                LEFT JOIN {$this->customer_master_table} s
                    ON s.CUST = o.SUPPLIER

                WHERE 1 = 1
                {$master_where}
                {$supplier_search}

            ) report_order

            ORDER BY
                ORDER_DATE DESC,
                ORDER_ID DESC,
                SORT_ORDER ASC
        ";

        $query_params = array_merge(
            $customer_params,
            $supplier_params
        );

        return $this->db
            ->query($sql, $query_params)
            ->result_array();
    }

    /**
     * CUSTOMER OPTIONS
     */
    public function get_customer_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from($this->customer_master_table)
            ->where('CUST_KIND', 'CUSTOMER')
            ->where('STATUS', 'ACTIVE')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * SUPPLIER OPTIONS
     */
    public function get_supplier_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from($this->customer_master_table)
            ->where('CUST_KIND', 'SUPPLIER')
            ->where('STATUS', 'ACTIVE')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }
}
