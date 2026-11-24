
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');

        $this->load->model('Order_model', 'order');
        $this->load->library('form_validation');
    }

    // =====================================================
    // INDEX
    // =====================================================

    public function index()
    {
        $filters = [
            'date_from' => trim(
                (string) $this->input->get('date_from', TRUE)
            ),

            'date_to' => trim(
                (string) $this->input->get('date_to', TRUE)
            ),

            'cust' => trim(
                (string) $this->input->get('cust', TRUE)
            ),

            'supplier' => trim(
                (string) $this->input->get('supplier', TRUE)
            ),

            'status' => trim(
                (string) $this->input->get('status', TRUE)
            ),

            'search' => trim(
                (string) $this->input->get('search', TRUE)
            )
        ];

        $data = [
            'title' => 'Order Management',
            'page_title' => 'Order Management',

            'orders' => $this->order->get_order_list($filters),

            'customers' => $this->order->get_customer_options(),
            'suppliers' => $this->order->get_supplier_options(),

            'filters' => $filters
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/order/list', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // CREATE
    // =====================================================

    public function create()
    {
        $data = [
            'title' => 'Create Master Order',
            'page_title' => 'Create Master Order',

            'customers' => $this->order->get_customer_options(),
            'suppliers' => $this->order->get_supplier_options(),
            'sales_list' => $this->order->get_sales_options()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/order/form', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // CUSTOMER CREDIT INFO - AJAX
    // =====================================================

    public function customer_credit_info($cust = NULL)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            return $this->json_response([
                'ok' => FALSE,
                'message' => 'Invalid request method.'
            ], 405);
        }

        $cust = trim(rawurldecode((string) $cust));

        if ($cust === '') {
            return $this->json_response([
                'ok' => FALSE,
                'message' => 'Customer wajib dipilih.'
            ], 400);
        }

        $result = $this->order->get_customer_credit_summary($cust);

        if (!$result['ok']) {
            return $this->json_response($result, 404);
        }

        return $this->json_response($result);
    }

    // =====================================================
    // SAVE
    // =====================================================

    public function save()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Invalid request method.', 405);
            return;
        }

        // ---------------------------------------------
        // MASTER ORDER
        // ---------------------------------------------

        $order_date = trim(
            (string) $this->input->post('ORDER_DATE', TRUE)
        );

        $cust = trim(
            (string) $this->input->post('CUST', TRUE)
        );

        $supplier_code = trim(
            (string) $this->input->post('SUPPLIER', TRUE)
        );

        $driver = strtoupper(trim(
            (string) $this->input->post('DRIVER', TRUE)
        ));

        $vehicle_no = strtoupper(trim(
            (string) $this->input->post('VEHICLE_NO', TRUE)
        ));

        $sales_code = trim(
            (string) $this->input->post('SALES', TRUE)
        );

        // ---------------------------------------------
        // CUSTOMER ORDER
        // ---------------------------------------------

        $customer = [
            'BASIS' => strtoupper(trim(
                (string) $this->input->post('CUSTOMER_BASIS', TRUE)
            )),

            'EKOR' => $this->input->post('CUSTOMER_EKOR', TRUE),

            'KG' => $this->input->post('CUSTOMER_KG', TRUE),

            'UNIT_PRICE' => $this->input->post('CUSTOMER_UNIT_PRICE', TRUE)
        ];

        // ---------------------------------------------
        // SUPPLIER ORDER
        // ---------------------------------------------

        $supplier = [
            'BASIS' => strtoupper(trim(
                (string) $this->input->post('SUPPLIER_BASIS', TRUE)
            )),

            'EKOR' => $this->input->post('SUPPLIER_EKOR', TRUE),

            'KG' => $this->input->post('SUPPLIER_KG', TRUE),

            'UNIT_PRICE' => $this->input->post('SUPPLIER_UNIT_PRICE', TRUE)
        ];

        // ---------------------------------------------
        // BASIC VALIDATION
        // ---------------------------------------------

        $this->form_validation->set_data([
            'ORDER_DATE' => $order_date,
            'CUST' => $cust,
            'SUPPLIER' => $supplier_code,
            'SALES' => $sales_code
        ]);

        $this->form_validation->set_rules(
            'ORDER_DATE',
            'Order Date',
            'required|callback_valid_date'
        );

        $this->form_validation->set_rules(
            'CUST',
            'Customer',
            'required'
        );

        $this->form_validation->set_rules(
            'SUPPLIER',
            'Supplier',
            'required'
        );

        $this->form_validation->set_rules(
            'SALES',
            'Sales',
            'required'
        );

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata(
                'order_error',
                validation_errors()
            );

            redirect('order/create');
            return;
        }

        // ---------------------------------------------
        // MASTER DATA
        // ---------------------------------------------

        $header = [
            'ORDER_DATE' => $order_date,
            'CUST' => $cust,
            'SUPPLIER' => $supplier_code,
            'SALES' => $sales_code,
            'DRIVER' => $driver,
            'VEHICLE_NO' => $vehicle_no,
            'PLANT' => '0001'
        ];

        // ---------------------------------------------
        // USER SESSION
        // ---------------------------------------------

        $user_code = $this->session->userdata('username');

        if (!$user_code) {
            show_error('User session tidak ditemukan.', 401);
            return;
        }

        // ---------------------------------------------
        // SAVE TRANSACTION
        // ---------------------------------------------

        $result = $this->order->save_order(
            $header,
            $customer,
            $supplier,
            $user_code
        );

        if (!$result['ok']) {
            $this->session->set_flashdata(
                'order_error',
                $result['message']
            );

            redirect('order/create');
            return;
        }

        $this->session->set_flashdata(
            'order_success',
            'Master Order berhasil disimpan. Nomor: ' .
            $result['order_no']
        );

        redirect(
            'order/detail/' . rawurlencode($result['order_no'])
        );
    }

    // =====================================================
    // ORDER DETAIL
    // =====================================================

    public function detail($order_no = NULL)
    {
        if (!$order_no) {
            show_404();
            return;
        }

        $order = $this->order->get_order_detail_by_no($order_no);

        if (!$order) {
            show_404();
            return;
        }

        $data = [
            'title' => 'Order Detail',
            'page_title' => 'Order Detail',
            'order' => $order
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/order/detail', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // EDIT CUSTOMER ORDER - POST
    // =====================================================

    public function edit_customer_action($order_no = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Invalid request method.', 405);
            return;
        }

        $order_no = trim((string) $order_no);
        $user_code = $this->session->userdata('username');

        if (!$user_code) {
            show_error('User session tidak ditemukan.', 401);
            return;
        }

        if ($order_no === '') {
            show_404();
            return;
        }

        $remark = trim(
            (string) $this->input->post('remark', TRUE)
        );

        if ($remark === '') {
            $this->session->set_flashdata(
                'order_error',
                'Alasan perubahan Customer Order wajib diisi.'
            );

            redirect('order/detail/' . rawurlencode($order_no));
            return;
        }

        $input = [
            'BASIS' => strtoupper(trim(
                (string) $this->input->post('BASIS', TRUE)
            )),

            'EKOR' => $this->input->post('EKOR', TRUE),

            'KG' => $this->input->post('KG', TRUE),

            'UNIT_PRICE' => $this->input->post('UNIT_PRICE', TRUE)
        ];

        $result = $this->order->edit_customer_order(
            $order_no,
            $input,
            $remark,
            $user_code
        );

        if (!$result['ok']) {
            $this->session->set_flashdata(
                'order_error',
                $result['message']
            );
        } else {
            $this->session->set_flashdata(
                'order_success',
                $result['message']
            );
        }

        redirect('order/detail/' . rawurlencode($order_no));
    }

    // =====================================================
    // EDIT SUPPLIER ORDER - POST
    // =====================================================

    public function edit_supplier_action($order_no = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Invalid request method.', 405);
            return;
        }

        $order_no = trim((string) $order_no);
        $user_code = $this->session->userdata('username');

        if (!$user_code) {
            show_error('User session tidak ditemukan.', 401);
            return;
        }

        if ($order_no === '') {
            show_404();
            return;
        }

        $remark = trim(
            (string) $this->input->post('remark', TRUE)
        );

        if ($remark === '') {
            $this->session->set_flashdata(
                'order_error',
                'Alasan perubahan Supplier Order wajib diisi.'
            );

            redirect('order/detail/' . rawurlencode($order_no));
            return;
        }

        $input = [
            'BASIS' => strtoupper(trim(
                (string) $this->input->post('BASIS', TRUE)
            )),

            'EKOR' => $this->input->post('EKOR', TRUE),

            'KG' => $this->input->post('KG', TRUE),

            'UNIT_PRICE' => $this->input->post('UNIT_PRICE', TRUE)
        ];

        $result = $this->order->edit_supplier_order(
            $order_no,
            $input,
            $remark,
            $user_code
        );

        if (!$result['ok']) {
            $this->session->set_flashdata(
                'order_error',
                $result['message']
            );
        } else {
            $this->session->set_flashdata(
                'order_success',
                $result['message']
            );
        }

        redirect('order/detail/' . rawurlencode($order_no));
    }

    // =====================================================
    // APPROVAL ACCESS
    // =====================================================

    private function is_order_approver()
    {
        $user_code = strtolower(trim(
            (string) $this->session->userdata('username')
        ));

        return in_array($user_code, [
            'pavamir',
            'pavthomas'
        ], TRUE);
    }


    // =====================================================
    // APPROVAL LIST
    // =====================================================

    public function approval_index()
    {
        if (!$this->is_order_approver()) {
            show_error('Anda tidak memiliki akses Approval Order.', 403);
            return;
        }

        $data = [
            'title' => 'Approval Order',
            'page_title' => 'Approval Order',
            'approvals' => $this->order->get_pending_order_approvals()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/order/approval_list', $data);
        $this->load->view('templates/footer');
    }


    // =====================================================
    // APPROVAL DETAIL
    // =====================================================

    public function approval_detail($approval_id = NULL)
    {
        if (!$this->is_order_approver()) {
            show_error('Anda tidak memiliki akses Approval Order.', 403);
            return;
        }

        $order = $this->order->get_order_approval_detail($approval_id);

        if (!$order) {
            $this->session->set_flashdata(
                'order_error',
                'Data approval tidak ditemukan atau sudah diproses.'
            );

            redirect('order/approval');
            return;
        }

        $data = [
            'title' => 'Approval Order Detail',
            'page_title' => 'Approval Order Detail',
            'order' => $order
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/order/approval_detail', $data);
        $this->load->view('templates/footer');
    }

    public function approval_action($approval_id = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Invalid request method.', 405);
            return;
        }

        if (!$this->is_order_approver()) {
            show_error('Anda tidak memiliki akses Approval Order.', 403);
            return;
        }

        $approval_id = (int) $approval_id;

        $decision = strtoupper(trim(
            (string) $this->input->post('decision', TRUE)
        ));

        $remark = trim(
            (string) $this->input->post('remark', TRUE)
        );

        $user_code = $this->session->userdata('username');

        if (!$approval_id || !$user_code || $remark === '') {
            $this->session->set_flashdata(
                'order_error',
                'Approval ID, user, dan remark wajib tersedia.'
            );

            redirect('order/approval');
            return;
        }

        $result = $this->order->process_order_approval(
            $approval_id,
            $decision,
            $remark,
            $user_code
        );

        if (!$result['ok']) {
            $this->session->set_flashdata(
                'order_error',
                $result['message']
            );
        } else {
            $this->session->set_flashdata(
                'order_success',
                $result['message']
            );
        }

        redirect('order/approval');
    }

    // =====================================================
    // VALIDATE DATE
    // =====================================================

    public function valid_date($value)
    {
        $dt = DateTime::createFromFormat('!Y-m-d', $value);

        if (!$dt || $dt->format('Y-m-d') !== $value) {
            $this->form_validation->set_message(
                'valid_date',
                'Tanggal Order tidak valid.'
            );

            return FALSE;
        }

        return TRUE;
    }


    // =====================================================
    // CANCEL ORDER
    // =====================================================

    public function cancel_action($order_no = NULL)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Invalid request method.', 405);
            return;
        }

        $order_no = trim((string) $order_no);
        $remark = trim(
            (string) $this->input->post('remark', TRUE)
        );

        $user_code = $this->session->userdata('username');

        if (!$user_code) {
            show_error('User session tidak ditemukan.', 401);
            return;
        }

        if ($order_no === '') {
            show_404();
            return;
        }

        if ($remark === '') {
            $this->session->set_flashdata(
                'order_error',
                'Alasan pembatalan wajib diisi.'
            );

            redirect('order/detail/' . rawurlencode($order_no));
            return;
        }

        $result = $this->order->cancel_order(
            $order_no,
            $remark,
            $user_code
        );

        if (!$result['ok']) {
            $this->session->set_flashdata(
                'order_error',
                $result['message']
            );
        } else {
            $this->session->set_flashdata(
                'order_success',
                $result['message']
            );
        }

        redirect('order/detail/' . rawurlencode($order_no));
    }

    // =====================================================
    // JSON RESPONSE
    // =====================================================

    private function json_response($data, $http_code = 200)
    {
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
