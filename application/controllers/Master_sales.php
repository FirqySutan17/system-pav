
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_sales extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Master_sales_model', 'm');
        $this->load->library('form_validation');
    }

    /**
     * Master Sales List
     */
    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master_sales/list');
        $this->load->view('templates/footer');
    }

    /**
     * AJAX: Load Sales Data
     */
    public function load_data()
    {
        $page = max(1, (int) $this->input->get('page'));
        $limit = (int) $this->input->get('limit');

        if ($limit < 1) {
            $limit = 10;
        }

        $limit = min($limit, 100);

        $search = trim((string) $this->input->get('search', true));
        $order  = $this->input->get('order', true) ?: 'SALES';
        $dir    = $this->input->get('dir', true) ?: 'ASC';

        $offset = ($page - 1) * $limit;

        $rows = $this->m->get_data(
            $limit,
            $offset,
            $search,
            $order,
            $dir
        );

        $total = $this->m->count_data($search);
        $pages = max(1, (int) ceil($total / $limit));

        $this->json_response([
            'status' => true,
            'rows' => $rows,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => $pages
        ]);
    }

    /**
     * AJAX: Sales Detail
     */
    public function detail()
    {
        $sales = $this->input->get('sales');

        log_message('error', '[MASTER SALES] GET: ' . json_encode($_GET));
        log_message('error', '[MASTER SALES] SALES: ' . var_export($sales, true));

        if (empty($sales)) {
            return $this->json_response([
                'status' => false,
                'message' => 'Sales Code is required.',
                'debug_get' => $_GET
            ], 400);
        }

        $row = $this->m->get_by_pk($sales);

        if (!$row) {
            return $this->json_response([
                'status' => false,
                'message' => 'Sales data not found.'
            ], 404);
        }

        return $this->json_response([
            'status' => true,
            'data' => $row
        ]);
    }
    
    /**
     * AJAX: Preview Sales Code
     *
     * Preview only. Final code is generated during save.
     */
    public function preview_code()
    {
        $code = $this->m->generate_sales();

        if (!$code) {
            return $this->json_response([
                'status'  => false,
                'message' => 'Failed to generate Sales Code preview.'
            ], 500);
        }

        return $this->json_response([
            'status' => true,
            'sales'  => $code
        ]);
    }


    /**
     * Create Sales Page
     */
    public function create()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master_sales/create');
        $this->load->view('templates/footer');
    }

    /**
     * Store Sales
     */
    public function store()
    {
        $this->form_validation->set_rules(
            'FULL_NAME',
            'Full Name',
            'trim|required|max_length[255]'
        );

        $this->form_validation->set_rules(
            'PHONE',
            'Phone',
            'trim|max_length[50]'
        );

        $this->form_validation->set_rules(
            'EMAIL',
            'Email',
            'trim|max_length[150]|valid_email'
        );

        $this->form_validation->set_rules(
            'STATUS',
            'Status',
            'required|in_list[ACTIVE,INACTIVE]'
        );

        if ($this->form_validation->run() === false) {
            return $this->json_response([
                'status' => false,
                'message' => strip_tags(validation_errors())
            ], 422);
        }

        $user = $this->get_logged_in_user();

        $data = [
            'FULL_NAME' => trim($this->input->post('FULL_NAME', true)),
            'PHONE' => $this->nullable_post('PHONE'),
            'EMAIL' => $this->nullable_post('EMAIL'),
            'STATUS' => $this->input->post('STATUS', true) ?: 'ACTIVE',
            'REG_EMP' => $user,
            'REG_DATE' => date('Y-m-d H:i:s')
        ];

        $result = $this->m->create_with_generated_code($data);

        if (!$result['status']) {
            return $this->json_response([
                'status' => false,
                'message' => $result['message']
            ], 500);
        }

        $this->json_response([
            'status' => true,
            'message' => $result['message'],
            'sales' => $result['sales']
        ]);
    }

    /**
     * Edit Sales Page
     */
    public function edit($sales = null)
    {
        $sales = trim((string) $sales);

        if ($sales === '') {
            show_404();
            return;
        }

        $data['sales'] = $this->m->get_by_pk($sales);

        if (!$data['sales']) {
            show_404();
            return;
        }

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('admin/master_sales/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Update Sales
     */
    public function update($sales = null)
    {
        $sales = trim((string) $sales);

        if ($sales === '') {
            return $this->json_response([
                'status' => false,
                'message' => 'Sales Code is required.'
            ], 400);
        }

        $existing = $this->m->get_by_pk($sales);

        if (!$existing) {
            return $this->json_response([
                'status' => false,
                'message' => 'Sales data not found.'
            ], 404);
        }

        $this->form_validation->set_rules(
            'FULL_NAME',
            'Full Name',
            'trim|required|max_length[255]'
        );

        $this->form_validation->set_rules(
            'PHONE',
            'Phone',
            'trim|max_length[50]'
        );

        $this->form_validation->set_rules(
            'EMAIL',
            'Email',
            'trim|max_length[150]|valid_email'
        );

        $this->form_validation->set_rules(
            'STATUS',
            'Status',
            'required|in_list[ACTIVE,INACTIVE]'
        );

        if ($this->form_validation->run() === false) {
            return $this->json_response([
                'status' => false,
                'message' => strip_tags(validation_errors())
            ], 422);
        }

        $data = [
            'FULL_NAME' => trim($this->input->post('FULL_NAME', true)),
            'PHONE' => $this->nullable_post('PHONE'),
            'EMAIL' => $this->nullable_post('EMAIL'),
            'STATUS' => $this->input->post('STATUS', true),
            'MOD_EMP' => $this->get_logged_in_user(),
            'MOD_DATE' => date('Y-m-d H:i:s')
        ];

        $updated = $this->m->update($sales, $data);

        if (!$updated) {
            return $this->json_response([
                'status' => false,
                'message' => 'Failed to update Sales data.'
            ], 500);
        }

        $this->json_response([
            'status' => true,
            'message' => 'Sales updated successfully.',
            'sales' => $sales
        ]);
    }

    /**
     * Get logged-in user from session
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

            if (!empty($value)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Get nullable POST value
     */
    private function nullable_post($field)
    {
        $value = trim((string) $this->input->post($field, true));

        return $value !== '' ? $value : null;
    }

    /**
     * JSON Response
     */
    private function json_response($data, $http_code = 200)
    {
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
