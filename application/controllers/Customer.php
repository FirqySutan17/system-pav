
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Permission base_customer sengaja di-skip
        // sesuai keputusan tahap pengembangan.

        $this->load->model('Customer_model', 'm');
        $this->load->library('form_validation');
    }

    /**
     * HALAMAN DAFTAR
     */
    public function index()
    {
        $this->load->view('templates/header', [
            'title' => 'Customer & Supplier'
        ]);

        $this->load->view('templates/sidebar');
        $this->load->view('admin/customer/list');
        $this->load->view('templates/footer');
    }

    /**
     * DATA AJAX UNTUK DAFTAR
     */
    public function load_data()
    {
        $page = max(1, (int) $this->input->get('page'));

        $limit = max(
            1,
            min(100, (int) $this->input->get('limit'))
        );

        $search = trim((string) $this->input->get('search', TRUE));

        $kind = strtoupper(
            trim((string) $this->input->get('cust_kind', TRUE))
        );

        $order = strtoupper(
            (string) $this->input->get('order', TRUE)
        );

        $dir = strtoupper(
            (string) $this->input->get('dir', TRUE)
        );

        if (!in_array($kind, ['ALL', 'CUSTOMER', 'SUPPLIER'], TRUE)) {
            $kind = 'ALL';
        }

        if (!in_array($dir, ['ASC', 'DESC'], TRUE)) {
            $dir = 'ASC';
        }

        $offset = ($page - 1) * $limit;

        $rows = $this->m->get_data(
            $limit,
            $offset,
            $search,
            $order,
            $dir,
            $kind
        );

        $total = $this->m->count_data($search, $kind);
        $pages = (int) ceil($total / $limit);

        $pagination = '<nav><ul class="pagination justify-content-end">';

        for ($i = 1; $i <= $pages; $i++) {
            $active = ($i === $page) ? 'active' : '';

            $pagination .=
                '<li class="page-item ' . $active . '">' .
                    '<a class="page-link" href="javascript:loadPage(' .
                    $i . ')">' . $i . '</a>' .
                '</li>';
        }

        $pagination .= '</ul></nav>';

        return $this->json_response([
            'rows'       => $rows,
            'total'      => $total,
            'pagination' => $pagination,
            'page'       => $page,
            'pages'      => $pages
        ]);
    }

    /**
     * DETAIL AJAX UNTUK MODAL
     */
    public function detail()
    {
        $cust = trim((string) $this->input->get('cust', TRUE));

        if ($cust === '') {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Kode Customer/Supplier tidak valid.'
            ]);
        }

        $row = $this->m->get_by_pk($cust);

        if (!$row) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return $this->json_response([
            'status' => TRUE,
            'data'   => $row
        ]);
    }

    /**
     * PREVIEW KODE
     * Hanya untuk tampilan, bukan kode final.
     */
    public function preview_code()
    {
        $kind = strtoupper(
            trim((string) $this->input->get('cust_kind', TRUE))
        );

        if (!in_array($kind, ['CUSTOMER', 'SUPPLIER'], TRUE)) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Jenis registrasi tidak valid.'
            ]);
        }

        $cust = $this->m->generate_cust($kind);

        if (!$cust) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Gagal membuat preview kode.'
            ]);
        }

        return $this->json_response([
            'status' => TRUE,
            'cust'   => $cust,
            'label'  => 'Preview - kode final saat simpan'
        ]);
    }

    /**
     * CREATE
     * GET  : tampilkan halaman form.
     * POST : validasi dan simpan data.
     */
    public function create()
    {
        if ($this->input->method(TRUE) === 'POST') {
            return $this->store();
        }

        $data['title'] = 'Tambah Customer / Supplier';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/customer/create', $data);
        $this->load->view('templates/footer');
    }

    /**
     * PROSES SIMPAN CREATE
     */
    private function store()
    {
        $this->form_validation->set_rules(
            'cust_kind',
            'Jenis Registrasi',
            'required|in_list[CUSTOMER,SUPPLIER]'
        );

        $this->form_validation->set_rules(
            'full_name',
            'Nama Perusahaan',
            'required|trim|max_length[255]'
        );

        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|in_list[ACTIVE,INACTIVE]'
        );

        if ($this->form_validation->run() === FALSE) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => trim(strip_tags(validation_errors()))
            ]);
        }

        $kind = strtoupper(
            trim((string) $this->input->post('cust_kind', TRUE))
        );

        $reg_emp = $this->get_logged_in_user();

        if ($reg_emp === NULL) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Session pengguna tidak ditemukan. Silakan login ulang.'
            ]);
        }

        $data = [
            'CUST_KIND'    => $kind,
            'FULL_NAME'    => trim(
                (string) $this->input->post('full_name', TRUE)
            ),
            'SHORT_NAME'   => $this->nullable_post('short_name'),
            'CHIEF_NAME'   => $this->nullable_post('chief_name'),
            'TAX_NO'       => $this->nullable_post('tax_no'),
            'TELEPHONE_NO' => $this->nullable_post('telephone_no'),
            'MOBILE_PHONE' => $this->nullable_post('mobile_phone'),
            'ADDRESS2'     => $this->nullable_post('address2'),
            'PLANT'        => '0001',
            'STATUS'       => strtoupper(
                trim((string) $this->input->post('status', TRUE))
            ),
            'REG_EMP'      => $reg_emp,
            'REG_DATE'     => date('Y-m-d H:i:s')
        ];

        $result = $this->m->create_with_generated_code($data);

        return $this->json_response($result);
    }

    /**
     * EDIT
     * GET : tampilkan halaman Edit.
     * POST: proses update.
     */
    public function edit($cust = NULL)
    {
        if ($this->input->method(TRUE) === 'POST') {
            return $this->update();
        }

        if (empty($cust)) {
            show_404();
            return;
        }

        $row = $this->m->get_by_pk($cust);

        if (!$row) {
            $this->load->view('templates/header');
            $this->load->view('templates/sidebar');
            $this->load->view('admin/customer/edit', [
                'row' => NULL
            ]);
            $this->load->view('templates/footer');
            return;
        }

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/customer/edit', [
            'row' => $row
        ]);
        $this->load->view('templates/footer');
    }

    /**
     * PROSES UPDATE
     */
    private function update()
    {
        $orig_cust = trim(
            (string) $this->input->post('orig_cust', TRUE)
        );

        if ($orig_cust === '') {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Kode asli tidak ditemukan.'
            ]);
        }

        $existing = $this->m->get_by_pk($orig_cust);

        if (!$existing) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        $this->form_validation->set_rules(
            'full_name',
            'Nama Perusahaan',
            'required|trim|max_length[255]'
        );

        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|in_list[ACTIVE,INACTIVE]'
        );

        if ($this->form_validation->run() === FALSE) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => trim(strip_tags(validation_errors()))
            ]);
        }

        $mod_emp = $this->get_logged_in_user();

        if ($mod_emp === NULL) {
            return $this->json_response([
                'status'  => FALSE,
                'message' => 'Session pengguna tidak ditemukan. Silakan login ulang.'
            ]);
        }

        $data = [
            'FULL_NAME'    => trim(
                (string) $this->input->post('full_name', TRUE)
            ),
            'SHORT_NAME'   => $this->nullable_post('short_name'),
            'CHIEF_NAME'   => $this->nullable_post('chief_name'),
            'TAX_NO'       => $this->nullable_post('tax_no'),
            'TELEPHONE_NO' => $this->nullable_post('telephone_no'),
            'MOBILE_PHONE' => $this->nullable_post('mobile_phone'),
            'ADDRESS2'     => $this->nullable_post('address2'),
            'STATUS'       => strtoupper(
                trim((string) $this->input->post('status', TRUE))
            ),
            'MOD_EMP'      => $mod_emp,
            'MOD_DATE'     => date('Y-m-d H:i:s')
        ];

        $ok = $this->m->update($orig_cust, $data);

        return $this->json_response([
            'status'  => (bool) $ok,
            'message' => $ok
                ? 'Data berhasil diperbarui.'
                : 'Data gagal diperbarui.'
        ]);
    }


    /**
     * CREDIT LIMIT CUSTOMER
     * GET  : tampilkan form dan history.
     * POST : simpan limit utama langsung aktif, tanpa approval.
     */
    public function credit_limit($cust = NULL)
    {
        $cust = trim((string) $cust);

        if ($cust === '') {
            if ($this->input->method(TRUE) === 'POST') {
                return $this->json_response([
                    'status' => FALSE,
                    'message' => 'Kode Customer tidak valid.'
                ], 400);
            }
            show_404();
            return;
        }

        $row = $this->m->get_by_pk($cust);

        if (!$row || strtoupper((string) ($row['CUST_KIND'] ?? '')) !== 'CUSTOMER') {
            if ($this->input->method(TRUE) === 'POST') {
                return $this->json_response([
                    'status' => FALSE,
                    'message' => 'Credit Limit hanya tersedia untuk Customer yang valid.'
                ], 404);
            }
            show_404();
            return;
        }

        if ($this->input->method(TRUE) === 'POST') {
            return $this->save_credit_limit($cust);
        }

        $data = [
            'title'   => 'Credit Limit - ' . ($row['FULL_NAME'] ?? $cust),
            'row'     => $row,
            'credit'  => $this->m->get_credit_limit($cust),
            'history' => $this->m->get_credit_history($cust)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/customer/credit_limit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Validasi dan simpan Credit Limit utama.
     */
    private function save_credit_limit($cust)
    {
        $this->form_validation->set_rules(
            'basic_credit',
            'Batas Kredit',
            'required|trim|numeric|greater_than_equal_to[0]'
        );
        $this->form_validation->set_rules('currency', 'Mata Uang', 'required|in_list[IDR,USD,EUR]');
        $this->form_validation->set_rules('risk_class', 'Risk Class', 'trim|in_list[A,B,C,D,E]');
        $this->form_validation->set_rules('terms_of_payment', 'Terms of Payment', 'trim|integer|greater_than_equal_to[1]|less_than_equal_to[90]');

        if ($this->form_validation->run() === FALSE) {
            return $this->json_response([
                'status' => FALSE,
                'message' => trim(strip_tags(validation_errors()))
            ], 422);
        }

        $changed_by = $this->get_logged_in_user();
        if ($changed_by === NULL) {
            return $this->json_response([
                'status' => FALSE,
                'message' => 'Session pengguna tidak ditemukan. Silakan login ulang.'
            ], 401);
        }

        $date_from = trim((string) $this->input->post('basic_credit_from_date', TRUE));
        $date_to = trim((string) $this->input->post('basic_credit_to_date', TRUE));

        foreach (['Tanggal mulai' => $date_from, 'Tanggal akhir' => $date_to] as $label => $date) {
            if ($date !== '' && (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || date('Y-m-d', strtotime($date)) !== $date)) {
                return $this->json_response([
                    'status' => FALSE,
                    'message' => $label . ' berlaku tidak valid.'
                ], 422);
            }
        }

        if ($date_from !== '' && $date_to !== '' && $date_to < $date_from) {
            return $this->json_response([
                'status' => FALSE,
                'message' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.'
            ], 422);
        }

        $terms = $this->nullable_post('terms_of_payment');

        $data = [
            'BASIC_CREDIT'          => (float) $this->input->post('basic_credit'),
            'BASIC_CREDIT_FROM_DATE'=> $date_from !== '' ? $date_from : NULL,
            'BASIC_CREDIT_TO_DATE'  => $date_to !== '' ? $date_to : NULL,
            'CURRENCY'              => strtoupper(trim((string) $this->input->post('currency', TRUE))),
            'RISK_CLASS'            => strtoupper(trim((string) $this->input->post('risk_class', TRUE))),
            'TERMS_OF_PAYMENT'      => $terms !== NULL ? (string) (int) $terms : NULL,
            'REMARK'                => $this->nullable_post('remark')
        ];

        $result = $this->m->save_credit_limit($cust, $data, $changed_by);
        return $this->json_response($result, !empty($result['status']) ? 200 : 500);
    }

    /**
     * IDENTITAS USER DARI SESSION
     */
    private function get_logged_in_user()
    {
        $keys = [
            'USER_ID',
            'user_id',
            'USERNAME',
            'username',
            'NIK',
            'nik',
            'employee_id'
        ];

        foreach ($keys as $key) {
            $value = $this->session->userdata($key);

            if (is_scalar($value) && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return NULL;
    }

    /**
     * POST OPTIONAL
     */
    private function nullable_post($key)
    {
        $value = trim((string) $this->input->post($key, TRUE));

        return $value === '' ? NULL : $value;
    }

    /**
     * JSON RESPONSE
     */
    private function json_response($data, $http_code = 200)
    {
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
