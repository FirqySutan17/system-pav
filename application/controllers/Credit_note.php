<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_note extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Credit_note_model', 'credit_note');
        $this->load->library(['session']);
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $data['title'] = 'Credit Note';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/credit_note/list', $data);
        $this->load->view('templates/footer');
    }

    public function get_list()
    {
        $filters = [
            'search'     => trim((string) $this->input->post('search', TRUE)),
            'date_from'  => trim((string) $this->input->post('date_from', TRUE)),
            'date_to'    => trim((string) $this->input->post('date_to', TRUE)),
            'order_type' => strtoupper(trim((string) $this->input->post('order_type', TRUE))),
            'status'     => strtoupper(trim((string) $this->input->post('status', TRUE)))
        ];

        return $this->json_response([
            'success' => TRUE,
            'data'    => $this->credit_note->get_list($filters)
        ]);
    }

    /**
     * Order options are loaded separately for Customer and Supplier.
     * For CUSTOMER_SUPPLIER, the form calls this endpoint twice:
     * once with CUSTOMER and once with SUPPLIER.
     */
    public function get_order_options()
    {
        $type   = strtoupper(trim((string) $this->input->post('order_type', TRUE)));
        $search = trim((string) $this->input->post('search', TRUE));

        if (!in_array($type, ['CUSTOMER', 'SUPPLIER'], TRUE)) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Tipe Order tidak valid.',
                'data'    => []
            ]);
        }

        return $this->json_response([
            'success' => TRUE,
            'data'    => $this->credit_note->get_order_options($type, $search)
        ]);
    }

    /**
     * Retrieve one selected Customer or Supplier Order detail.
     */
    public function get_order_detail()
    {
        $type      = strtoupper(trim((string) $this->input->post('order_type', TRUE)));
        $detail_id = (int) $this->input->post('order_detail_id');

        if (!in_array($type, ['CUSTOMER', 'SUPPLIER'], TRUE) || $detail_id <= 0) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Parameter Order tidak valid.'
            ]);
        }

        $result = $this->credit_note->get_order_detail($type, $detail_id);

        if (!$result) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Order tidak ditemukan atau tidak tersedia.'
            ]);
        }

        return $this->json_response([
            'success' => TRUE,
            'data'    => $result
        ]);
    }

    public function get_detail($id = 0)
    {
        $id = (int) $id;

        if ($id <= 0) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'ID Credit Note tidak valid.'
            ]);
        }

        $result = $this->credit_note->get_by_id($id);

        if (!$result) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Credit Note tidak ditemukan.'
            ]);
        }

        return $this->json_response([
            'success' => TRUE,
            'data'    => $result
        ]);
    }

    /**
     * Create / update Credit Note.
     *
     * Supported order_type:
     * - CUSTOMER
     * - SUPPLIER
     * - CUSTOMER_SUPPLIER
     *
     * For backward compatibility, CUSTOMER and SUPPLIER can still submit
     * order_detail_id. The combined type must submit both detail IDs.
     */
    public function save()
    {
        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Method tidak diizinkan.'
            ]);
        }

        $id         = (int) $this->input->post('credit_note_id');
        $order_type = strtoupper(trim((string) $this->input->post('order_type', TRUE)));

        $customer_order_id = (int) $this->input->post('order_customer_id');
        $supplier_order_id = (int) $this->input->post('order_supplier_id');

        // Backward compatibility for the previous single-order form.
        $legacy_detail_id = (int) $this->input->post('order_detail_id');

        if ($order_type === 'CUSTOMER' && $customer_order_id <= 0) {
            $customer_order_id = $legacy_detail_id;
        } elseif ($order_type === 'SUPPLIER' && $supplier_order_id <= 0) {
            $supplier_order_id = $legacy_detail_id;
        }

        if (!in_array($order_type, ['CUSTOMER', 'SUPPLIER', 'CUSTOMER_SUPPLIER'], TRUE)) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Tipe Credit Note tidak valid.'
            ]);
        }

        if ($order_type === 'CUSTOMER' && $customer_order_id <= 0) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Customer Order wajib dipilih.'
            ]);
        }

        if ($order_type === 'SUPPLIER' && $supplier_order_id <= 0) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Supplier Order wajib dipilih.'
            ]);
        }

        if (
            $order_type === 'CUSTOMER_SUPPLIER' &&
            ($customer_order_id <= 0 || $supplier_order_id <= 0)
        ) {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Customer Order dan Supplier Order wajib dipilih untuk tipe gabungan.'
            ]);
        }

        $data = [
            'ORDER_TYPE'        => $order_type,
            'ORDER_CUSTOMER_ID' => $customer_order_id > 0 ? $customer_order_id : NULL,
            'ORDER_SUPPLIER_ID' => $supplier_order_id > 0 ? $supplier_order_id : NULL,
            'CN_DATE'           => $this->input->post('cn_date', TRUE),
            'CN_PRICE'          => $this->input->post('cn_price'),
            'REMARK'            => $this->input->post('remark', TRUE)
        ];

        $user_code = $this->session->userdata('user_code');

        $result = $this->credit_note->save(
            $data,
            $id ?: NULL,
            $user_code
        );

        return $this->json_response([
            'success' => !empty($result['ok']),
            'message' => isset($result['message']) ? $result['message'] : 'Proses Credit Note selesai.',
            'data'    => $result
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREDIT NOTE APPROVAL
    |--------------------------------------------------------------------------
    */

    public function approval()
    {
        $data['title']     = 'Approval Credit Note';
        $data['approvals'] = $this->credit_note->get_pending_approvals();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/credit_note/approval_list', $data);
        $this->load->view('templates/footer');
    }

    public function approval_detail($approval_id = 0)
    {
        $approval_id = (int) $approval_id;

        if ($approval_id <= 0) {
            show_404();
            return;
        }

        $data['title']         = 'Approval Credit Note';
        $data['approval_data'] = $this->credit_note->get_approval_detail($approval_id);

        if (!$data['approval_data']) {
            show_404();
            return;
        }

        $data['current_user'] = $this->session->userdata('user_code');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/credit_note/approval_detail', $data);
        $this->load->view('templates/footer');
    }

    public function approval_action($approval_id = 0)
    {
        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            show_error('Method tidak diizinkan.', 405);
            return;
        }

        $approval_id = (int) $approval_id;

        if ($approval_id <= 0) {
            show_error('ID Approval tidak valid.', 400);
            return;
        }

        $result = $this->credit_note->process_approval(
            $approval_id,
            $this->input->post('decision', TRUE),
            $this->input->post('remark', TRUE),
            $this->session->userdata('user_code')
        );

        $this->session->set_flashdata(
            !empty($result['ok']) ? 'credit_note_success' : 'credit_note_error',
            isset($result['message']) ? $result['message'] : 'Proses approval gagal.'
        );

        redirect('credit_note/approval_detail/' . $approval_id);
    }

    private function json_response($data)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
