<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCustomerAging_model extends CI_Model
{
    private $order_table    = 'pav_tr_order';
    private $customer_table = 'pav_tr_order_customer';
    private $master_customer_table = 'pav_cd_customer';

    // =====================================================
    // CUSTOMER OPTIONS
    // =====================================================

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

    // =====================================================
    // BASE AGING QUERY
    // One row per Customer Order
    // =====================================================

    private function get_base_aging_sql()
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
                COALESCE(cn.TOTAL_CN, 0) AS TOTAL_CN,

                CASE
                    WHEN oc.TERMS_OF_PAYMENT IS NOT NULL
                    THEN DATE_ADD(
                        o.ORDER_DATE,
                        INTERVAL oc.TERMS_OF_PAYMENT DAY
                    )
                    ELSE NULL
                END AS DUE_DATE,

                CASE
                    WHEN oc.TERMS_OF_PAYMENT IS NULL
                    THEN NULL

                    ELSE DATEDIFF(
                        ?,
                        DATE_ADD(
                            o.ORDER_DATE,
                            INTERVAL oc.TERMS_OF_PAYMENT DAY
                        )
                    )
                END AS AGING_DAYS,

                CASE
                    WHEN oc.TERMS_OF_PAYMENT IS NULL
                        THEN 'UNCLASSIFIED'

                    WHEN DATEDIFF(
                        ?,
                        DATE_ADD(
                            o.ORDER_DATE,
                            INTERVAL oc.TERMS_OF_PAYMENT DAY
                        )
                    ) <= 0
                        THEN 'CURRENT'

                    WHEN DATEDIFF(
                        ?,
                        DATE_ADD(
                            o.ORDER_DATE,
                            INTERVAL oc.TERMS_OF_PAYMENT DAY
                        )
                    ) BETWEEN 1 AND 30
                        THEN '1-30 DAYS'

                    WHEN DATEDIFF(
                        ?,
                        DATE_ADD(
                            o.ORDER_DATE,
                            INTERVAL oc.TERMS_OF_PAYMENT DAY
                        )
                    ) BETWEEN 31 AND 60
                        THEN '31-60 DAYS'

                    WHEN DATEDIFF(
                        ?,
                        DATE_ADD(
                            o.ORDER_DATE,
                            INTERVAL oc.TERMS_OF_PAYMENT DAY
                        )
                    ) BETWEEN 61 AND 90
                        THEN '61-90 DAYS'

                    ELSE 'OVER 90 DAYS'
                END AS AGING_BUCKET

            FROM {$this->order_table} o

            INNER JOIN {$this->customer_table} oc
                ON oc.ORDER_ID = o.ORDER_ID

            INNER JOIN {$this->master_customer_table} c
                ON c.CUST = o.CUST

            LEFT JOIN (
                SELECT
                    ORDER_CUSTOMER_ID,
                    SUM(CN_CUSTOMER_TOTAL) AS TOTAL_CN
                FROM pav_tr_order_credit_note
                WHERE APPROVAL_STATUS = 'APPROVED'
                GROUP BY ORDER_CUSTOMER_ID
            ) cn ON cn.ORDER_CUSTOMER_ID = oc.ORDER_CUSTOMER_ID

            WHERE o.STATUS = 'OPEN'

              AND oc.APPROVAL_STATUS IN (
                  'NOT_REQUIRED',
                  'APPROVED'
              )

              AND oc.REMAIN > 0
        ";
    }

    // =====================================================
    // APPLY AGING FILTERS
    // =====================================================

    private function apply_aging_filters(
        &$sql,
        &$params,
        $filters = []
    ) {
        // CUSTOMER FILTER
        if (!empty($filters['customer'])) {
            $sql .= "
                AND a.CUST = ?
            ";

            $params[] = $filters['customer'];
        }

        // AGING BUCKET FILTER
        if (!empty($filters['aging_bucket'])) {
            $sql .= "
                AND a.AGING_BUCKET = ?
            ";

            $params[] = $filters['aging_bucket'];
        }

        // SEARCH
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

            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
    }

    // =====================================================
    // REPORT CUSTOMER AGING
    // =====================================================

    public function get_report_aging($filters = [])
    {
        $as_of_date = !empty($filters['as_of_date'])
            ? $filters['as_of_date']
            : date('Y-m-d');

        $base_sql = $this->get_base_aging_sql();

        $sql = "
            SELECT a.*
            FROM (
                {$base_sql}
            ) a
            WHERE 1 = 1
        ";

        // Five date placeholders in base query
        $params = [
            $as_of_date,
            $as_of_date,
            $as_of_date,
            $as_of_date,
            $as_of_date
        ];

        $this->apply_aging_filters(
            $sql,
            $params,
            $filters
        );

        $sql .= "
            ORDER BY
                a.CUSTOMER_NAME ASC,
                a.DUE_DATE ASC,
                a.ORDER_DATE ASC,
                a.ORDER_CUSTOMER_ID ASC
        ";

        return $this->db
            ->query($sql, $params)
            ->result_array();
    }

    // =====================================================
    // REPORT SUMMARY
    // =====================================================

    public function get_report_summary($filters = [])
    {
        $as_of_date = !empty($filters['as_of_date'])
            ? $filters['as_of_date']
            : date('Y-m-d');

        $base_sql = $this->get_base_aging_sql();

        $sql = "
            SELECT

                COUNT(*) AS TOTAL_RECORDS,

                COUNT(DISTINCT a.CUST) AS TOTAL_CUSTOMERS,

                COALESCE(
                    SUM(a.OUTSTANDING),
                    0
                ) AS TOTAL_OUTSTANDING,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.AGING_BUCKET = 'CURRENT'
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_CURRENT,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.AGING_BUCKET = '1-30 DAYS'
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_1_30,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.AGING_BUCKET = '31-60 DAYS'
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_31_60,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.AGING_BUCKET = '61-90 DAYS'
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_61_90,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.AGING_BUCKET = 'OVER 90 DAYS'
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_OVER_90,

                COALESCE(
                    SUM(
                        CASE
                            WHEN a.AGING_BUCKET = 'UNCLASSIFIED'
                            THEN a.OUTSTANDING
                            ELSE 0
                        END
                    ),
                    0
                ) AS TOTAL_UNCLASSIFIED,

                SUM(
                    CASE
                        WHEN a.AGING_BUCKET = 'CURRENT'
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_CURRENT,

                SUM(
                    CASE
                        WHEN a.AGING_BUCKET = '1-30 DAYS'
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_1_30,

                SUM(
                    CASE
                        WHEN a.AGING_BUCKET = '31-60 DAYS'
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_31_60,

                SUM(
                    CASE
                        WHEN a.AGING_BUCKET = '61-90 DAYS'
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_61_90,

                SUM(
                    CASE
                        WHEN a.AGING_BUCKET = 'OVER 90 DAYS'
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_OVER_90,

                SUM(
                    CASE
                        WHEN a.AGING_BUCKET = 'UNCLASSIFIED'
                        THEN 1
                        ELSE 0
                    END
                ) AS COUNT_UNCLASSIFIED

            FROM (
                {$base_sql}
            ) a

            WHERE 1 = 1
        ";

        $params = [
            $as_of_date,
            $as_of_date,
            $as_of_date,
            $as_of_date,
            $as_of_date
        ];

        $this->apply_aging_filters(
            $sql,
            $params,
            $filters
        );

        return $this->db
            ->query($sql, $params)
            ->row_array();
    }
}