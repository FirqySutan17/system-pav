
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCustomerOverdue_model extends CI_Model
{
    private $order_table = 'pav_tr_order';
    private $customer_table = 'pav_tr_order_customer';
    private $master_customer_table = 'pav_cd_customer';
    private $credit_note_table = 'pav_tr_order_credit_note';

    // =============================================
    // CUSTOMER OPTIONS
    // =============================================

    public function get_customer_options()
    {
        return $this->db
            ->select('CUST, FULL_NAME')
            ->from($this->master_customer_table)
            ->where('CUST_KIND', 'CUSTOMER')
            ->where('STATUS', 'ACTIVE')
            ->order_by('FULL_NAME', 'ASC')
            ->get()
            ->result_array();
    }

    // =============================================
    // BASE OVERDUE SQL
    // =============================================

    private function get_base_overdue_sql()
    {
        return "
            SELECT
                o.ORDER_ID,
                o.ORDER_NO,
                o.ORDER_DATE,
                o.PLANT,
                o.CUST,

                c.FULL_NAME AS CUSTOMER_NAME,

                oc.ORDER_CUSTOMER_ID,
                oc.ORDER_CUSTOMER_NO,

                oc.BASIS,
                oc.EKOR,
                oc.KG,
                oc.UNIT_PRICE,

                oc.TOTAL AS ORDER_TOTAL,
                oc.PAID,
                oc.REMAIN AS OUTSTANDING,

                oc.PAYMENT_STATUS,
                oc.APPROVAL_STATUS,

                oc.TERMS_OF_PAYMENT,

                DATE_ADD(
                    o.ORDER_DATE,
                    INTERVAL oc.TERMS_OF_PAYMENT DAY
                ) AS DUE_DATE,

                DATEDIFF(
                    ?,
                    DATE_ADD(
                        o.ORDER_DATE,
                        INTERVAL oc.TERMS_OF_PAYMENT DAY
                    )
                ) AS OVERDUE_DAYS,

                COALESCE(cn.TOTAL_CN, 0) AS TOTAL_CN

            FROM pav_tr_order o

            INNER JOIN pav_tr_order_customer oc
                ON oc.ORDER_ID = o.ORDER_ID

            INNER JOIN pav_cd_customer c
                ON c.CUST = o.CUST

            LEFT JOIN (
                SELECT
                    ORDER_CUSTOMER_ID,
                    SUM(CN_CUSTOMER_TOTAL) AS TOTAL_CN
                FROM pav_tr_order_credit_note
                WHERE APPROVAL_STATUS = 'APPROVED'
                GROUP BY ORDER_CUSTOMER_ID
            ) cn
                ON cn.ORDER_CUSTOMER_ID = oc.ORDER_CUSTOMER_ID

            WHERE o.STATUS = 'OPEN'

              AND oc.APPROVAL_STATUS IN (
                  'NOT_REQUIRED',
                  'APPROVED'
              )

              AND oc.REMAIN > 0

              AND oc.TERMS_OF_PAYMENT IS NOT NULL
        ";
    }

    // =============================================
    // APPLY FILTERS
    // =============================================

    private function apply_overdue_filters(&$sql, &$params, $filters = [])
    {
        // CUSTOMER FILTER
        if (!empty($filters['customer'])) {

            $sql .= " AND a.CUST = ?";

            $params[] = $filters['customer'];
        }

        // SEARCH FILTER
        if (!empty($filters['search'])) {

            $search = '%' . trim($filters['search']) . '%';

            $sql .= "
                AND (
                    a.ORDER_NO LIKE ?
                    OR a.ORDER_CUSTOMER_NO LIKE ?
                    OR a.CUST LIKE ?
                    OR a.CUSTOMER_NAME LIKE ?
                )
            ";

            array_push(
                $params,
                $search,
                $search,
                $search,
                $search
            );
        }
    }

    // =============================================
    // GET REPORT OVERDUE
    // =============================================

    public function get_report_overdue($filters = [])
    {
        $as_of_date = !empty($filters['as_of_date'])
            ? $filters['as_of_date']
            : date('Y-m-d');

        $base_sql = $this->get_base_overdue_sql();

        $sql = "
            SELECT
                a.*
            FROM (
                {$base_sql}
            ) a
            WHERE a.OVERDUE_DAYS > 0
        ";

        $params = [$as_of_date];

        $this->apply_overdue_filters(
            $sql,
            $params,
            $filters
        );

        $sql .= "
            ORDER BY
                a.OVERDUE_DAYS DESC,
                a.CUSTOMER_NAME ASC,
                a.DUE_DATE ASC,
                a.ORDER_DATE ASC,
                a.ORDER_CUSTOMER_ID ASC
        ";

        return $this->db
            ->query($sql, $params)
            ->result_array();
    }

    // =============================================
    // GET REPORT SUMMARY
    // =============================================

    public function get_report_summary($filters = [])
    {
        $as_of_date = !empty($filters['as_of_date'])
            ? $filters['as_of_date']
            : date('Y-m-d');

        $base_sql = $this->get_base_overdue_sql();

        $sql = "
            SELECT

                COUNT(*) AS TOTAL_RECORDS,

                COUNT(DISTINCT a.CUST) AS TOTAL_CUSTOMERS,

                COALESCE(
                    SUM(a.OUTSTANDING),
                    0
                ) AS TOTAL_OUTSTANDING,

                COALESCE(
                    SUM(a.ORDER_TOTAL),
                    0
                ) AS TOTAL_ORDER,

                COALESCE(
                    SUM(a.PAID),
                    0
                ) AS TOTAL_PAID,

                COALESCE(
                    SUM(a.TOTAL_CN),
                    0
                ) AS TOTAL_CN,

                COALESCE(
                    MAX(a.OVERDUE_DAYS),
                    0
                ) AS MAX_OVERDUE_DAYS,

                COALESCE(
                    AVG(a.OVERDUE_DAYS),
                    0
                ) AS AVG_OVERDUE_DAYS,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.OVERDUE_DAYS BETWEEN 1 AND 30
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_1_30,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.OVERDUE_DAYS BETWEEN 31 AND 60
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_31_60,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.OVERDUE_DAYS BETWEEN 61 AND 90
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_61_90,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.OVERDUE_DAYS > 90
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_OVER_90,

                SUM(
                    CASE
                        WHEN a.OVERDUE_DAYS BETWEEN 1 AND 30
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_1_30,

                SUM(
                    CASE
                        WHEN a.OVERDUE_DAYS BETWEEN 31 AND 60
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_31_60,

                SUM(
                    CASE
                        WHEN a.OVERDUE_DAYS BETWEEN 61 AND 90
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_61_90,

                SUM(
                    CASE
                        WHEN a.OVERDUE_DAYS > 90
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_OVER_90

            FROM (
                {$base_sql}
            ) a

            WHERE a.OVERDUE_DAYS > 0
        ";

        $params = [$as_of_date];

        $this->apply_overdue_filters(
            $sql,
            $params,
            $filters
        );

        return $this->db
            ->query($sql, $params)
            ->row_array();
    }
}
