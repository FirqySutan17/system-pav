<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model
{
    private $table = 'pav_cd_customer';

    private $allowed_order = [
        'CUST'       => 'CUST',
        'FULL_NAME'  => 'FULL_NAME',
        'CUST_KIND'  => 'CUST_KIND',
        'CHIEF_NAME' => 'CHIEF_NAME',
        'STATUS'     => 'STATUS'
    ];

    private function apply_filter($search = '', $cust_kind = 'ALL')
    {
        if ($search !== '') {
            $this->db->group_start()
                ->like('CUST', $search)
                ->or_like('FULL_NAME', $search)
                ->or_like('SHORT_NAME', $search)
                ->or_like('CHIEF_NAME', $search)
                ->group_end();
        }

        $cust_kind = strtoupper(trim((string) $cust_kind));
        if (in_array($cust_kind, ['CUSTOMER', 'SUPPLIER'], true)) {
            $this->db->where('CUST_KIND', $cust_kind);
        }
    }

    public function get_data($limit, $offset, $search = '', $order = 'CUST', $dir = 'ASC', $cust_kind = 'ALL')
    {
        $limit = max(1, min(100, (int) $limit));
        $offset = max(0, (int) $offset);
        $order_key = strtoupper((string) $order);
        $order_col = isset($this->allowed_order[$order_key]) ? $this->allowed_order[$order_key] : 'CUST';
        $dir = strtoupper((string) $dir) === 'DESC' ? 'DESC' : 'ASC';

        $this->apply_filter(trim((string) $search), $cust_kind);
        $this->db->order_by($order_col, $dir);

        return $this->db->get($this->table, $limit, $offset)->result_array();
    }

    public function count_data($search = '', $cust_kind = 'ALL')
    {
        $this->apply_filter(trim((string) $search), $cust_kind);
        return $this->db->count_all_results($this->table);
    }

    public function get_by_pk($cust)
    {
        return $this->db->where('CUST', $cust)->get($this->table)->row_array();
    }

    public function generate_cust($cust_kind)
    {
        $prefix = $this->get_prefix($cust_kind);
        if ($prefix === FALSE) {
            return FALSE;
        }

        $row = $this->db->select('CUST')
            ->from($this->table)
            ->where('CUST_KIND', strtoupper(trim($cust_kind)))
            ->where('CUST REGEXP', '^' . $prefix . '[0-9]{6}$', NULL, FALSE)
            ->order_by('CUST', 'DESC')
            ->limit(1)
            ->get()->row();

        $last_number = $row ? (int) substr($row->CUST, 3) : 0;
        $next_number = $last_number + 1;
        if ($next_number > 999999) {
            return FALSE;
        }

        return $prefix . str_pad((string) $next_number, 6, '0', STR_PAD_LEFT);
    }

    private function get_prefix($cust_kind)
    {
        $cust_kind = strtoupper(trim((string) $cust_kind));
        if ($cust_kind === 'CUSTOMER') return 'CUS';
        if ($cust_kind === 'SUPPLIER') return 'SUP';
        return FALSE;
    }

    public function create_with_generated_code($data)
    {
        $kind = isset($data['CUST_KIND']) ? strtoupper(trim($data['CUST_KIND'])) : '';
        $prefix = $this->get_prefix($kind);

        if ($prefix === FALSE) {
            return ['status' => FALSE, 'message' => 'Jenis Customer/Supplier tidak valid.'];
        }

        $lock_name = 'customer_code_' . strtolower($prefix);
        $lock = $this->db->query('SELECT GET_LOCK(?, 10) AS lock_status', [$lock_name])->row();

        if (!$lock || (int) $lock->lock_status !== 1) {
            return ['status' => FALSE, 'message' => 'Penomoran sedang digunakan. Silakan coba kembali.'];
        }

        $result = ['status' => FALSE, 'message' => 'Data gagal disimpan.'];

        try {
            $this->db->trans_begin();
            $cust = $this->generate_cust($kind);

            if ($cust === FALSE) {
                $this->db->trans_rollback();
                $result['message'] = 'Kode gagal dibuat atau batas nomor tercapai.';
            } else {
                $data['CUST'] = $cust;
                $ok = $this->db->insert($this->table, $data);

                if ($ok && $this->db->trans_status() !== FALSE) {
                    $this->db->trans_commit();
                    $result = ['status' => TRUE, 'message' => 'Data berhasil disimpan.', 'cust' => $cust];
                } else {
                    $this->db->trans_rollback();
                    log_message('error', 'Create Customer/Supplier gagal: ' . $this->db->last_query());
                    $result['message'] = 'Data gagal disimpan. Periksa data dan coba kembali.';
                }
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Create Customer/Supplier exception: ' . $e->getMessage());
            $result['message'] = 'Terjadi kesalahan saat menyimpan data.';
        } finally {
            $this->db->query('SELECT RELEASE_LOCK(?)', [$lock_name]);
        }

        return $result;
    }

    public function update($cust, $data)
    {
        unset($data['CUST'], $data['CUST_KIND']);
        $this->db->where('CUST', $cust);
        return $this->db->update($this->table, $data);
    }

    /**
     * Mengambil Credit Limit dan history untuk halaman detail limit.
     */
    public function get_credit_limit($cust)
    {
        return $this->db->where('CUST', $cust)
            ->get('pav_tr_credit_limit')
            ->row_array();
    }

    public function get_credit_history($cust, $limit = 100)
    {
        return $this->db->where('CUST', $cust)
            ->order_by('CHANGED_AT', 'DESC')
            ->order_by('ID', 'DESC')
            ->limit(max(1, min(500, (int) $limit)))
            ->get('pav_tr_credit_history')
            ->result_array();
    }

    /**
     * Menyimpan Credit Limit utama langsung aktif dan menulis history
     * untuk setiap field yang berubah. Semua dalam satu transaksi.
     */
    public function save_credit_limit($cust, $data, $changed_by)
    {
        $existing_customer = $this->get_by_pk($cust);
        if (!$existing_customer || strtoupper((string) ($existing_customer['CUST_KIND'] ?? '')) !== 'CUSTOMER') {
            return ['status' => FALSE, 'message' => 'Credit Limit hanya tersedia untuk Customer yang valid.'];
        }

        $old = $this->get_credit_limit($cust);
        $now = date('Y-m-d H:i:s');

        // Kolom ini harus tersedia pada pav_tr_credit_limit.
        $fields = [
            'BASIC_CREDIT'           => ['label' => 'Basic Credit', 'numeric' => TRUE],
            'BASIC_CREDIT_FROM_DATE' => ['label' => 'Tanggal Berlaku Dari', 'numeric' => FALSE],
            'BASIC_CREDIT_TO_DATE'   => ['label' => 'Tanggal Berlaku Sampai', 'numeric' => FALSE],
            'CURRENCY'               => ['label' => 'Currency', 'numeric' => FALSE],
            'RISK_CLASS'             => ['label' => 'Risk Class', 'numeric' => FALSE],
            'TERMS_OF_PAYMENT'       => ['label' => 'Terms of Payment', 'numeric' => FALSE],
            'REMARK'                 => ['label' => 'Remark', 'numeric' => FALSE]
        ];

        $record = [
            'CUST'                   => $cust,
            'BASIC_CREDIT'           => $data['BASIC_CREDIT'],
            'BASIC_CREDIT_FROM_DATE' => $data['BASIC_CREDIT_FROM_DATE'],
            'BASIC_CREDIT_TO_DATE'   => $data['BASIC_CREDIT_TO_DATE'],
            'CURRENCY'               => $data['CURRENCY'],
            'RISK_CLASS'             => $data['RISK_CLASS'] !== '' ? $data['RISK_CLASS'] : NULL,
            'TERMS_OF_PAYMENT'       => $data['TERMS_OF_PAYMENT'],
            'STATUS'                 => 'ACTIVE',
            'REMARK'                 => $data['REMARK'],
            'UPDATED_BY'             => $changed_by,
            'UPDATED_AT'             => $now
        ];

        $this->db->trans_begin();

        try {
            if ($old) {
                $this->db->where('CUST', $cust)->update('pav_tr_credit_limit', $record);
            } else {
                $record['CREATED_BY'] = $changed_by;
                $record['CREATED_AT'] = $now;
                $this->db->insert('pav_tr_credit_limit', $record);
            }

            if ($this->db->trans_status() === FALSE) {
                $db_error = $this->db->error();
                throw new Exception('Gagal menyimpan data Credit Limit. DB: ' .
                    (isset($db_error['message']) ? $db_error['message'] : 'unknown database error'));
            }

            // Simpan snapshot NILAI TERBARU setelah record berhasil di-insert/update.
            // Snapshot ini dipakai sebagai satu baris riwayat per proses simpan.
            log_message('error', 'CREDIT LIMIT DEBUG: ' . json_encode([
                'CUST' => $cust,
                'DATA_BASIC_CREDIT' => $data['BASIC_CREDIT'],
                'DB_BASIC_CREDIT' => $this->get_credit_limit($cust)['BASIC_CREDIT'] ?? null,
                'TIME' => $now
            ]));

            $snapshot = [
                'BASIC_CREDIT' => $data['BASIC_CREDIT'],
                'BASIC_CREDIT_FROM_DATE' => $data['BASIC_CREDIT_FROM_DATE'],
                'BASIC_CREDIT_TO_DATE' => $data['BASIC_CREDIT_TO_DATE'],
                'REMARK' => $data['REMARK']
            ];

            $this->db->insert('pav_tr_credit_history', [
                'CUST'        => $cust,
                'ACTION_TYPE' => 'CREDIT_LIMIT_SNAPSHOT',
                'OLD_VALUE'   => $data['BASIC_CREDIT'],
                'NEW_VALUE'   => $data['BASIC_CREDIT'],
                'REMARK'      => json_encode($snapshot, JSON_UNESCAPED_UNICODE),
                'CHANGED_BY'  => $changed_by,
                'CHANGED_AT'  => $now
            ]);

            if ($this->db->trans_status() === FALSE) {
                $db_error = $this->db->error();
                throw new Exception('Gagal menyimpan snapshot riwayat Credit Limit. DB: ' .
                    (isset($db_error['message']) ? $db_error['message'] : 'unknown database error'));
            }

            // Tetap catat field yang berubah sebagai audit detail.
            foreach ($fields as $field => $meta) {
                $old_value = $old && array_key_exists($field, $old) ? $old[$field] : NULL;
                $new_value = array_key_exists($field, $data) ? $data[$field] : NULL;

                if ($this->credit_values_equal($old_value, $new_value, $meta['numeric'])) {
                    continue;
                }

                $history = [
                    'CUST'        => $cust,
                    'ACTION_TYPE' => $field,
                    'OLD_VALUE'   => $meta['numeric'] && $old_value !== NULL ? $old_value : NULL,
                    'NEW_VALUE'   => $meta['numeric'] && $new_value !== NULL ? $new_value : NULL,
                    'REMARK'      => $meta['numeric']
                        ? $meta['label']
                        : $meta['label'] . ' | Lama: ' . $this->history_value($old_value) . ' | Baru: ' . $this->history_value($new_value),
                    'CHANGED_BY'  => $changed_by,
                    'CHANGED_AT'  => $now
                ];

                $this->db->insert('pav_tr_credit_history', $history);

                if ($this->db->trans_status() === FALSE) {
                    $db_error = $this->db->error();
                    throw new Exception('Gagal menyimpan detail history Credit Limit. DB: ' .
                        (isset($db_error['message']) ? $db_error['message'] : 'unknown database error'));
                }
            }

            $this->db->trans_commit();
            return [
                'status' => TRUE,
                'message' => 'Credit Limit berhasil disimpan dan langsung berlaku.'
            ];
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message(
                'error',
                'Save Credit Limit failed for ' . $cust . ': ' . $e->getMessage() .
                ' | DB error: ' . json_encode($this->db->error()) .
                ' | SQL: ' . $this->db->last_query()
            );
            return [
                'status' => FALSE,
                'message' => 'Credit Limit gagal disimpan. Periksa log aplikasi untuk detail.'
            ];
        }
    }

    private function credit_values_equal($old, $new, $numeric = FALSE)
    {
        if ($old === NULL && ($new === NULL || $new === '')) return TRUE;
        if ($numeric) return (float) $old === (float) $new;
        return trim((string) $old) === trim((string) $new);
    }

    private function history_value($value)
    {
        return ($value === NULL || trim((string) $value) === '') ? '-' : (string) $value;
    }

    public function delete($cust)
    {
        return $this->db->where('CUST', $cust)->delete($this->table);
    }
}
