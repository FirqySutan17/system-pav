
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Payment_model', 'payment');
        $this->load->helper(['form', 'url']);
    }

    // =====================================================
    // JSON RESPONSE
    // =====================================================

    private function json_response($status, $data)
    {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // =====================================================
    // PAYMENT LIST
    // =====================================================

    public function index()
    {
        $filters = [
            'supplier'   => trim((string) $this->input->get('supplier', TRUE)),
            'date_from'  => trim((string) $this->input->get('date_from', TRUE)),
            'date_to'    => trim((string) $this->input->get('date_to', TRUE)),
            'method'     => trim((string) $this->input->get('method', TRUE)),
            'status'     => trim((string) $this->input->get('status', TRUE)),
            'search'     => trim((string) $this->input->get('search', TRUE))
        ];

        $data = [
            'title'     => 'Payment Supplier',
            'page_title'=> 'Payment Supplier',
            'filters'   => $filters,
            'suppliers' => $this->payment->get_supplier_options(),
            'payments'  => $this->payment->get_payment_list($filters)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/payment/list', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // CREATE PAYMENT
    // =====================================================

    public function create()
    {
        $data = [
            'title' => 'Create Payment',
            'page_title' => 'Create Payment',
            'suppliers' => $this->payment->get_supplier_options()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/payment/add', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // GET SUPPLIER ORDERS
    // GET: payment/supplier_orders/{supplier}
    // =====================================================

    public function supplier_orders($supplier = '')
    {
        if ($this->input->method(TRUE) !== 'GET') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $supplier = trim((string) $supplier);

        if ($supplier === '') {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Supplier wajib dipilih.'
            ]);
        }

        $orders = $this->payment->get_supplier_orders($supplier);

        return $this->json_response(200, [
            'ok' => TRUE,
            'data' => $orders
        ]);
    }

    // =====================================================
    // SAVE PAYMENT
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
            'PAYMENT_DATE' => $this->input->post('PAYMENT_DATE', TRUE),
            'SUPPLIER' => $this->input->post('SUPPLIER', TRUE),
            'PAYMENT_METHOD' => $this->input->post('PAYMENT_METHOD', TRUE),
            'PAYMENT_TYPE' => $this->input->post('PAYMENT_TYPE', TRUE),
            'SLIP_NO' => $this->input->post('SLIP_NO', TRUE),
            'TOTAL_PAYMENT' => $this->input->post('TOTAL_PAYMENT', TRUE),
            'REMARK' => $this->input->post('REMARK', TRUE)
        ];

        // Allocation dikirim sebagai JSON string dari JavaScript.
        $allocation_json = $this->input->post('allocations', FALSE);
        $allocations = [];

        if (!empty($allocation_json)) {
            $allocations = json_decode($allocation_json, TRUE);

            if (!is_array($allocations)) {
                return $this->json_response(400, [
                    'ok' => FALSE,
                    'message' => 'Format allocation tidak valid.'
                ]);
            }
        }

        // Normalisasi angka.
        // Frontend sebaiknya mengirim TOTAL_PAYMENT dalam format angka
        // tanpa pemisah ribuan, contoh: 1500000.00.
        if (!is_numeric($data['TOTAL_PAYMENT'])) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Total Payment tidak valid.'
            ]);
        }

        $user_code = $this->session->userdata('username');

        if (empty($user_code)) {
            return $this->json_response(401, [
                'ok' => FALSE,
                'message' => 'Session user tidak ditemukan.'
            ]);
        }

        $result = $this->payment->save_payment(
            $data,
            $allocations,
            $user_code
        );

        if (!$result['ok']) {
            return $this->json_response(400, $result);
        }

        return $this->json_response(200, $result);
    }

    // =====================================================
    // PAYMENT DETAIL
    // =====================================================

    public function detail($payment_id)
    {
        $payment = $this->payment->get_payment_detail($payment_id);

        if (!$payment) {
            show_404();
            return;
        }

        $data['title'] = 'Payment Detail';
        $data['page_title'] = 'Payment Detail';
        $data['payment'] = $payment;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/payment/detail', $data);
        $this->load->view('templates/footer', $data);
    }

    // =====================================================
    // EDIT PAYMENT PAGE
    // =====================================================

    public function edit($payment_id = 0)
    {
        $payment_id = (int) $payment_id;

        if ($payment_id <= 0) {
            show_404();
            return;
        }

        $payment = $this->payment->get_payment_detail($payment_id);

        if (!$payment) {
            show_404();
            return;
        }

        if ($payment['STATUS'] !== 'POSTED') {
            $this->session->set_flashdata(
                'error',
                'Payment yang sudah VOID tidak dapat diedit.'
            );

            redirect('payment');
            return;
        }

        if ($this->payment->has_later_posted_payment($payment_id)) {
            $this->session->set_flashdata(
                'error',
                'Payment tidak dapat diedit karena terdapat Payment POSTED yang lebih baru pada Supplier Order terkait.'
            );

            redirect('payment');
            return;
        }

        $data['title'] = 'Edit Payment';
        $data['payment'] = $payment;
        

        $data['supplier_orders'] =
            $this->payment->get_edit_supplier_orders($payment_id);

        $data['suppliers'] = $this->payment->get_supplier_options();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/payment/edit', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // UPDATE PAYMENT
    // =====================================================

    public function update($payment_id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $payment_id = (int) $payment_id;

        if ($payment_id <= 0) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Payment ID tidak valid.'
            ]);
        }

        $data = [
            'PAYMENT_DATE' => $this->input->post('PAYMENT_DATE', TRUE),
            'SUPPLIER' => $this->input->post('SUPPLIER', TRUE),
            'PAYMENT_METHOD' => $this->input->post('PAYMENT_METHOD', TRUE),
            'PAYMENT_TYPE' => $this->input->post('PAYMENT_TYPE', TRUE),
            'SLIP_NO' => $this->input->post('SLIP_NO', TRUE),
            'TOTAL_PAYMENT' => $this->input->post('TOTAL_PAYMENT', TRUE),
            'REMARK' => $this->input->post('REMARK', TRUE)
        ];

        $allocation_json = $this->input->post('allocations', FALSE);
        $allocations = [];

        if (!empty($allocation_json)) {
            $allocations = json_decode($allocation_json, TRUE);

            if (!is_array($allocations)) {
                return $this->json_response(400, [
                    'ok' => FALSE,
                    'message' => 'Format allocation tidak valid.'
                ]);
            }
        }

        if (!is_numeric($data['TOTAL_PAYMENT'])) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Total Payment tidak valid.'
            ]);
        }

        $user_code = $this->session->userdata('username');

        if (empty($user_code)) {
            return $this->json_response(401, [
                'ok' => FALSE,
                'message' => 'Session user tidak ditemukan.'
            ]);
        }

        $result = $this->payment->edit_payment(
            $payment_id,
            $data,
            $allocations,
            $user_code
        );

        if (!$result['ok']) {
            return $this->json_response(400, $result);
        }

        return $this->json_response(200, $result);
    }

    // =====================================================
    // VOID PAYMENT
    // =====================================================

    public function void($payment_id = 0)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            return $this->json_response(405, [
                'ok' => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $payment_id = (int) $payment_id;
        $user_code = $this->session->userdata('username');

        if ($payment_id <= 0) {
            return $this->json_response(400, [
                'ok' => FALSE,
                'message' => 'Payment ID tidak valid.'
            ]);
        }

        if (empty($user_code)) {
            return $this->json_response(401, [
                'ok' => FALSE,
                'message' => 'Session user tidak ditemukan.'
            ]);
        }

        $result = $this->payment->void_payment(
            $payment_id,
            $user_code
        );

        if (!$result['ok']) {
            return $this->json_response(400, $result);
        }

        return $this->json_response(200, $result);
    }

}
