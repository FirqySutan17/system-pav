
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCreditNote_model extends CI_Model
{
    private $table = 'pav_tr_order_credit_note';

    private $customer_order_table = 'pav_tr_order_customer';

    private $supplier_order_table = 'pav_tr_order_supplier';

    private $order_table = 'pav_tr_order';

    /*
    |--------------------------------------------------------------------------
    | GET REPORT CREDIT NOTE
    |--------------------------------------------------------------------------
    */

    public function get_report_credit_note($filters = [])
    {
        $this->db->select("
            cn.CREDIT_NOTE_ID,
            cn.CN_NO,
            cn.CN_DATE,

            cn.ORDER_CUSTOMER_ID,
            cn.ORDER_SUPPLIER_ID,

            CASE
                WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL
                    AND cn.ORDER_SUPPLIER_ID IS NOT NULL
                    THEN 'CUSTOMER_SUPPLIER'

                WHEN cn.ORDER_CUSTOMER_ID IS NOT NULL
                    THEN 'CUSTOMER'

                ELSE 'SUPPLIER'
            END AS ORDER_TYPE,

            o.ORDER_ID,
            o.ORDER_NO,

            oc.ORDER_CUSTOMER_NO,
            os.ORDER_SUPPLIER_NO,

            c.FULL_NAME AS CUSTOMER_NAME,
            s.FULL_NAME AS SUPPLIER_NAME,

            cn.CN_PRICE,
            cn.CN_CUSTOMER_TOTAL,
            cn.CN_SUPPLIER_TOTAL,
            cn.CN_TOTAL,

            cn.APPROVAL_STATUS,

            cn.REQUESTED_BY,
            cn.REQUESTED_AT,

            cn.APPROVED_BY,
            cn.APPROVED_AT

        ", FALSE);

        $this->db->from($this->table . ' cn');

        // Customer Order
        $this->db->join(
            $this->customer_order_table . ' oc',
            'oc.ORDER_CUSTOMER_ID = cn.ORDER_CUSTOMER_ID',
            'left'
        );

        // Supplier Order
        $this->db->join(
            $this->supplier_order_table . ' os',
            'os.ORDER_SUPPLIER_ID = cn.ORDER_SUPPLIER_ID',
            'left'
        );

        // Master Order
        $this->db->join(
            $this->order_table . ' o',
            'o.ORDER_ID = COALESCE(oc.ORDER_ID, os.ORDER_ID)',
            'left',
            FALSE
        );

        // Customer
        $this->db->join(
            'pav_cd_customer c',
            'c.CUST = o.CUST',
            'left'
        );

        // Supplier
        $this->db->join(
            'pav_cd_customer s',
            's.CUST = o.SUPPLIER',
            'left'
        );

        /*
        |--------------------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $this->db->group_start()
                ->like('cn.CN_NO', $search)
                ->or_like('o.ORDER_NO', $search)
                ->or_like('oc.ORDER_CUSTOMER_NO', $search)
                ->or_like('os.ORDER_SUPPLIER_NO', $search)
                ->or_like('c.FULL_NAME', $search)
                ->or_like('s.FULL_NAME', $search)
                ->or_like('cn.REQUESTED_BY', $search)
                ->or_like('cn.APPROVED_BY', $search)
                ->group_end();
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {
            $this->db->where(
                'cn.CN_DATE >=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {
            $this->db->where(
                'cn.CN_DATE <=',
                $filters['date_to']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER TYPE FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['order_type'])) {

            if ($filters['order_type'] === 'CUSTOMER') {

                $this->db
                    ->where(
                        'cn.ORDER_CUSTOMER_ID IS NOT NULL',
                        NULL,
                        FALSE
                    )
                    ->where(
                        'cn.ORDER_SUPPLIER_ID IS NULL',
                        NULL,
                        FALSE
                    );

            } elseif ($filters['order_type'] === 'SUPPLIER') {

                $this->db
                    ->where(
                        'cn.ORDER_SUPPLIER_ID IS NOT NULL',
                        NULL,
                        FALSE
                    )
                    ->where(
                        'cn.ORDER_CUSTOMER_ID IS NULL',
                        NULL,
                        FALSE
                    );

            } elseif ($filters['order_type'] === 'CUSTOMER_SUPPLIER') {

                $this->db
                    ->where(
                        'cn.ORDER_CUSTOMER_ID IS NOT NULL',
                        NULL,
                        FALSE
                    )
                    ->where(
                        'cn.ORDER_SUPPLIER_ID IS NOT NULL',
                        NULL,
                        FALSE
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | APPROVAL STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {
            $this->db->where(
                'cn.APPROVAL_STATUS',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        $this->db->order_by('cn.CN_DATE', 'DESC');
        $this->db->order_by('cn.CREDIT_NOTE_ID', 'DESC');

        return $this->db->get()->result_array();
    }
}
