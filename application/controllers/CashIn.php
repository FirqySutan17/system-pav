<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CashIn extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CashIn_model', 'cashin');
        $this->load->helper(['form', 'url']);
    }

    private function json_response($status, $data)
    {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // =====================================================
    // CASH IN LIST
    // =====================================================
    public function index()
    {
        $filters = [
            'customer'  => trim((string) $this->input->get('customer', TRUE)),
            'date_from' => trim((string) $this->input->get('date_from', TRUE)),
            'date_to'   => trim((string) $this->input->get('date_to', TRUE)),
            'method'    => trim((string) $this->input->get('method', TRUE)),
            'status'    => trim((string) $this->input->get('status', TRUE)),
            'search'    => trim((string) $this->input->get('search', TRUE))
        ];

        $data = [
            'title'      => 'Cash In Customer',
            'page_title' => 'Cash In Customer',
            'filters'    => $filters,
            'customers'  => $this->cashin->get_customer_options(),
            'cash_ins'   => $this->cashin->get_cash_in_list($filters)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/cash_in/list', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // CREATE CASH IN
    // =====================================================
    public function create()
    {
        $data = [
            'title'      => 'Create Cash In',
            'page_title' => 'Create Cash In',
            'customers'  => $this->cashin->get_customer_options()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/cash_in/add', $data);
        $this->load->view('templates/footer');
    }

    // GET: cashin/customer_orders/{customer}
    public function customer_orders($customer = '')
    {
        if ($this->input->method(TRUE) !== 'GET') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $customer = trim((string) $customer);
        if ($customer === '') {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Customer wajib dipilih.'
            ]);
        }

        return $this->json_response(200, [
            'ok' => TRUE,
            'data' => $this->cashin->get_customer_orders($customer)
        ]);
    }

    // =====================================================
    // SAVE CASH IN
    // =====================================================
    public function save()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $data = [
            'CASH_IN_DATE'   => $this->input->post('CASH_IN_DATE', TRUE),
            'CUSTOMER'       => $this->input->post('CUSTOMER', TRUE),
            'CASH_IN_METHOD' => $this->input->post('CASH_IN_METHOD', TRUE),
            'CASH_IN_TYPE'   => $this->input->post('CASH_IN_TYPE', TRUE),
            'SLIP_NO'        => $this->input->post('SLIP_NO', TRUE),
            'TOTAL_CASH_IN'  => $this->input->post('TOTAL_CASH_IN', TRUE),
            'REMARK'         => $this->input->post('REMARK', TRUE)
        ];

        $allocations = $this->decode_allocations();
        if ($allocations === FALSE) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Format allocation tidak valid.'
            ]);
        }

        if (!is_numeric($data['TOTAL_CASH_IN'])) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Total Cash In tidak valid.'
            ]);
        }

        $user_code = $this->session->userdata('username');
        if (empty($user_code)) {
            return $this->json_response(401, [
                'ok' => FALSE,
                'message' => 'Session user tidak ditemukan.'
            ]);
        }

        $result = $this->cashin->save_cash_in($data, $allocations, $user_code);
        return $this->json_response($result['ok'] ? 200 : 400, $result);
    }

    // =====================================================
    // CASH IN DETAIL
    // =====================================================
    public function detail($cash_in_id = 0)
    {
        $cash_in_id = (int) $cash_in_id;
        if ($cash_in_id <= 0) {
            show_404();
            return;
        }

        $cash_in = $this->cashin->get_cash_in_detail($cash_in_id);
        if (!$cash_in) {
            show_404();
            return;
        }

        $data = [
            'title'      => 'Cash In Detail',
            'page_title' => 'Cash In Detail',
            'cash_in'    => $cash_in
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/cash_in/detail', $data);
        $this->load->view('templates/footer', $data);
    }

    // =====================================================
    // EDIT CASH IN PAGE
    // =====================================================
    public function edit($cash_in_id = 0)
    {
        $cash_in_id = (int) $cash_in_id;
        if ($cash_in_id <= 0) {
            show_404();
            return;
        }

        $cash_in = $this->cashin->get_cash_in_detail($cash_in_id);
        if (!$cash_in) {
            show_404();
            return;
        }

        if ($cash_in['STATUS'] !== 'POSTED') {
            $this->session->set_flashdata('error', 'Cash In yang sudah VOID tidak dapat diedit.');
            redirect('cashin');
            return;
        }

        if ($this->cashin->has_later_posted_cash_in($cash_in_id)) {
            $this->session->set_flashdata(
                'error',
                'Cash In tidak dapat diedit karena terdapat Cash In POSTED yang lebih baru pada Customer Order terkait.'
            );
            redirect('cashin');
            return;
        }

        $data = [
            'title'           => 'Edit Cash In',
            'page_title'      => 'Edit Cash In',
            'cash_in'         => $cash_in,
            'customer_orders' => $this->cashin->get_edit_customer_orders($cash_in_id),
            'customers'       => $this->cashin->get_customer_options()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/cash_in/edit', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // UPDATE CASH IN
    // =====================================================
    public function update($cash_in_id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $cash_in_id = (int) $cash_in_id;
        if ($cash_in_id <= 0) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Cash In ID tidak valid.'
            ]);
        }

        $data = [
            'CASH_IN_DATE'   => $this->input->post('CASH_IN_DATE', TRUE),
            'CUSTOMER'       => $this->input->post('CUSTOMER', TRUE),
            'CASH_IN_METHOD' => $this->input->post('CASH_IN_METHOD', TRUE),
            'CASH_IN_TYPE'   => $this->input->post('CASH_IN_TYPE', TRUE),
            'SLIP_NO'        => $this->input->post('SLIP_NO', TRUE),
            'TOTAL_CASH_IN'  => $this->input->post('TOTAL_CASH_IN', TRUE),
            'REMARK'         => $this->input->post('REMARK', TRUE)
        ];

        $allocations = $this->decode_allocations();
        if ($allocations === FALSE) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Format allocation tidak valid.'
            ]);
        }

        if (!is_numeric($data['TOTAL_CASH_IN'])) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Total Cash In tidak valid.'
            ]);
        }

        $user_code = $this->session->userdata('username');
        if (empty($user_code)) {
            return $this->json_response(401, [
                'ok' => FALSE,
                'message' => 'Session user tidak ditemukan.'
            ]);
        }

        $result = $this->cashin->edit_cash_in($cash_in_id, $data, $allocations, $user_code);
        return $this->json_response($result['ok'] ? 200 : 400, $result);
    }

    // =====================================================
    // VOID CASH IN
    // =====================================================
    public function void($cash_in_id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $cash_in_id = (int) $cash_in_id;
        $user_code = $this->session->userdata('username');

        if ($cash_in_id <= 0) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Cash In ID tidak valid.'
            ]);
        }
        if (empty($user_code)) {
            return $this->json_response(401, [
                'ok' => FALSE,
                'message' => 'Session user tidak ditemukan.'
            ]);
        }

        $result = $this->cashin->void_cash_in($cash_in_id, $user_code);
        return $this->json_response($result['ok'] ? 200 : 400, $result);
    }

    // =====================================================
    // DECODE ALLOCATION JSON
    // =====================================================
    private function decode_allocations()
    {
        $allocation_json = $this->input->post('allocations', FALSE);
        if (empty($allocation_json)) {
            return [];
        }

        $allocations = json_decode($allocation_json, TRUE);
        return is_array($allocations) ? $allocations : FALSE;
    }
}
