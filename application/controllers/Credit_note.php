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
            'search'     => trim($this->input->post('search', TRUE)),
            'date_from'  => trim($this->input->post('date_from', TRUE)),
            'date_to'    => trim($this->input->post('date_to', TRUE)),
            'order_type' => trim($this->input->post('order_type', TRUE)),
            'status'     => trim($this->input->post('status', TRUE))
        ];

        return $this->json_response([
            'success' => TRUE,
            'data' => $this->credit_note->get_list($filters)
        ]);
    }

    public function get_order_options()
    {
        $type = strtoupper(trim($this->input->post('order_type', TRUE)));
        $search = trim($this->input->post('search', TRUE));

        if (!in_array($type, ['CUSTOMER', 'SUPPLIER'], TRUE)) {
            return $this->json_response(['success' => FALSE, 'message' => 'Tipe Order tidak valid.', 'data' => []]);
        }

        return $this->json_response([
            'success' => TRUE,
            'data' => $this->credit_note->get_order_options($type, $search)
        ]);
    }

    public function get_order_detail()
    {
        $type = strtoupper(trim($this->input->post('order_type', TRUE)));
        $detail_id = (int) $this->input->post('order_detail_id');

        if (!in_array($type, ['CUSTOMER', 'SUPPLIER'], TRUE) || $detail_id <= 0) {
            return $this->json_response(['success' => FALSE, 'message' => 'Parameter Order tidak valid.']);
        }

        $result = $this->credit_note->get_order_detail($type, $detail_id);
        if (!$result) {
            return $this->json_response(['success' => FALSE, 'message' => 'Order tidak ditemukan atau tidak tersedia.']);
        }

        return $this->json_response(['success' => TRUE, 'data' => $result]);
    }

    public function get_detail($id = 0)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return $this->json_response(['success' => FALSE, 'message' => 'ID Credit Note tidak valid.']);
        }

        $result = $this->credit_note->get_by_id($id);
        if (!$result) {
            return $this->json_response(['success' => FALSE, 'message' => 'Credit Note tidak ditemukan.']);
        }

        return $this->json_response(['success' => TRUE, 'data' => $result]);
    }

    public function save()
    {
        $id = (int) $this->input->post('credit_note_id');
        $data = [
            'ORDER_TYPE'      => $this->input->post('order_type', TRUE),
            'ORDER_DETAIL_ID' => $this->input->post('order_detail_id'),
            'CN_DATE'         => $this->input->post('cn_date', TRUE),
            'CN_PRICE'        => $this->input->post('cn_price'),
            'REMARK'          => $this->input->post('remark', TRUE)
        ];

        $user_code = $this->session->userdata('user_code');
        $result = $this->credit_note->save($data, $id ?: NULL, $user_code);

        return $this->json_response([
            'success' => !empty($result['ok']),
            'message' => $result['message'],
            'data' => $result
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREDIT NOTE APPROVAL
    |--------------------------------------------------------------------------
    */

    private function is_credit_note_approver()
    {
        $user_code = strtolower(trim((string) $this->session->userdata('user_code')));
        return in_array($user_code, ['pavamir', 'pavthomas'], TRUE);
    }

    public function approval()
    {

        $data['title'] = 'Approval Credit Note';
        $data['approvals'] = $this->credit_note->get_pending_approvals();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/credit_note/approval_list', $data);
        $this->load->view('templates/footer');
    }

    public function approval_detail($approval_id = 0)
    {

        $approval_id = (int) $approval_id;
        $data['title'] = 'Approval Credit Note';
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

        $result = $this->credit_note->process_approval(
            (int) $approval_id,
            $this->input->post('decision', TRUE),
            $this->input->post('remark', TRUE),
            $this->session->userdata('user_code')
        );

        $this->session->set_flashdata(
            !empty($result['ok']) ? 'credit_note_success' : 'credit_note_error',
            $result['message']
        );

        redirect('credit_note/approval_detail/' . (int) $approval_id);
    }

    private function json_response($data)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
