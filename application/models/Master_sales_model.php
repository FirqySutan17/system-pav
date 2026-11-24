
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_sales_model extends CI_Model
{
    private $table = 'pav_cd_sales';

    private $allowed_order = [
        'SALES',
        'FULL_NAME',
        'PHONE',
        'EMAIL',
        'STATUS',
        'REG_DATE'
    ];

    /**
     * Apply search filter
     */
    private function apply_filter($search = '')
    {
        if ($search !== '') {
            $this->db->group_start()
                ->like('SALES', $search)
                ->or_like('FULL_NAME', $search)
                ->or_like('PHONE', $search)
                ->or_like('EMAIL', $search)
                ->or_like('STATUS', $search)
            ->group_end();
        }
    }

    /**
     * Get paginated sales data
     */
    public function get_data(
        $limit = 10,
        $offset = 0,
        $search = '',
        $order = 'SALES',
        $dir = 'ASC'
    ) {
        $limit  = max(1, min((int) $limit, 100));
        $offset = max(0, (int) $offset);

        $order = in_array($order, $this->allowed_order, true)
            ? $order
            : 'SALES';

        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';

        $this->apply_filter($search);

        return $this->db
            ->select('SALES, FULL_NAME, PHONE, EMAIL, STATUS, REG_DATE')
            ->from($this->table)
            ->order_by($order, $dir)
            ->limit($limit, $offset)
            ->get()
            ->result();
    }

    /**
     * Count filtered sales
     */
    public function count_data($search = '')
    {
        $this->apply_filter($search);

        return $this->db
            ->from($this->table)
            ->count_all_results();
    }

    /**
     * Get sales by primary key
     */
    public function get_by_pk($sales)
    {
        return $this->db
            ->select("
                s.*,
                u1.name AS REG_EMP_NAME,
                u2.name AS MOD_EMP_NAME
            ", false)
            ->from($this->table . ' s')
            ->join('users u1', 'u1.id = s.REG_EMP', 'left')
            ->join('users u2', 'u2.id = s.MOD_EMP', 'left')
            ->where('s.SALES', $sales)
            ->get()
            ->row();
    }

    /**
     * Generate next Sales Code
     * Format: SLS000001
     *
     * This method is used for preview only.
     */
    public function generate_sales()
    {
        $row = $this->db
            ->select("
                MAX(CAST(SUBSTRING(SALES, 4) AS UNSIGNED)) AS LAST_NUMBER
            ", false)
            ->where("SALES REGEXP", '^SLS[0-9]{6}$')
            ->get($this->table)
            ->row();

        $next = ((int) ($row->LAST_NUMBER ?? 0)) + 1;

        return 'SLS' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create Sales with generated code
     *
     * Final code is generated inside the lock
     * to prevent duplicate codes during concurrent inserts.
     */
    public function create_with_generated_code($data)
    {
        $lock_name = 'pav_sales_code_generation';

        $lock = $this->db->query(
            "SELECT GET_LOCK(?, 10) AS lock_status",
            [$lock_name]
        )->row();

        if (!$lock || (int) $lock->lock_status !== 1) {
            return [
                'status'  => false,
                'message' => 'Unable to generate Sales Code. Please try again.'
            ];
        }

        try {
            $this->db->trans_begin();

            $sales_code = $this->generate_sales();

            $data['SALES'] = $sales_code;

            $insert = $this->db->insert($this->table, $data);

            if (!$insert || $this->db->trans_status() === false) {
                $this->db->trans_rollback();

                return [
                    'status'  => false,
                    'message' => 'Failed to save Sales data.'
                ];
            }

            $this->db->trans_commit();

            return [
                'status'  => true,
                'message' => 'Sales created successfully.',
                'sales'   => $sales_code
            ];

        } catch (Exception $e) {
            $this->db->trans_rollback();

            return [
                'status'  => false,
                'message' => 'An error occurred while saving Sales.'
            ];

        } finally {
            $this->db->query(
                "SELECT RELEASE_LOCK(?)",
                [$lock_name]
            );
        }
    }

    /**
     * Update Sales
     *
     * Sales Code is immutable.
     */
    public function update($sales, $data)
    {
        unset($data['SALES']);

        return $this->db
            ->where('SALES', $sales)
            ->update($this->table, $data);
    }
}
