<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PO_model', 'po');
    }

    public function index()
    {
        $data = [
            'title'     => 'Purchase Order',
            'suppliers' => $this->po->get_supplier_options()
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/po/list', $data);
        $this->load->view('templates/footer');
    }

    public function load_data()
    {
        $page = max(1, (int) $this->input->get('page'));
        $limit = max(1, min(100, (int) $this->input->get('limit')));
        $search = trim((string) $this->input->get('search', TRUE));
        $supplier = trim((string) $this->input->get('supplier', TRUE));
        $date_from = trim((string) $this->input->get('date_from', TRUE));
        $date_to = trim((string) $this->input->get('date_to', TRUE));
        $order = strtoupper(trim((string) $this->input->get('order', TRUE)));
        $dir = strtoupper(trim((string) $this->input->get('dir', TRUE)));

        $allowed_order = ['PO_NO', 'PO_DATE', 'SUPPLIER_NAME', 'TOTAL_EKOR', 'TOTAL_BW', 'TOTAL_AMOUNT', 'STATUS'];
        if (!in_array($order, $allowed_order, TRUE)) $order = 'PO_DATE';
        if (!in_array($dir, ['ASC', 'DESC'], TRUE)) $dir = 'DESC';
        if ($date_from !== '' && !$this->valid_date($date_from)) $date_from = '';
        if ($date_to !== '' && !$this->valid_date($date_to)) $date_to = '';

        $filters = [
            'search' => $search,
            'supplier' => $supplier,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];

        $total = $this->po->count_data($filters);
        $pages = (int) ceil($total / $limit);
        if ($pages > 0 && $page > $pages) $page = $pages;
        $offset = ($page - 1) * $limit;
        $rows = $this->po->get_data($limit, $offset, $filters, $order, $dir);

        $pagination = '<nav aria-label="PO pagination"><ul class="pagination justify-content-end">';
        for ($i = 1; $i <= $pages; $i++) {
            $active = $i === $page ? 'active' : '';
            $pagination .= '<li class="page-item ' . $active . '"><a class="page-link" href="javascript:loadPage(' . $i . ')">' . $i . '</a></li>';
        }
        $pagination .= '</ul></nav>';

        return $this->json_response([
            'rows' => $rows,
            'total' => $total,
            'pagination' => $pagination,
            'page' => $page,
            'pages' => $pages
        ]);
    }

    public function detail($po_no = NULL)
    {
        if (!$po_no) show_404();
        $row = $this->po->get_by_pk($po_no);
        if (!$row) show_404();
        $data = ['title'=>'Detail Purchase Order', 'header'=>$row, 'details'=>$row['DETAILS']];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/po/detail', $data);
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $this->render_form('Add Purchase Order', 'po/create', [], []);

    }

    public function edit($po_no = NULL)
    {
        if (!$po_no) show_404();
        show_error('Form Edit Purchase Order akan dibuat pada tahap berikutnya.', 501, 'Module PO dalam pengembangan');
    }


    private function render_form($title, $action, $header, $details)
    {
        $data = ['title'=>$title, 'page_title'=>$title, 'form_action'=>$action,
            'header'=>$header, 'details'=>$details, 'suppliers'=>$this->po->get_supplier_options()];
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/po/form', $data);
        $this->load->view('templates/footer');
    }

    private function submit_form($po_no = NULL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('PO_DATE', 'Tanggal PO', 'required|callback_valid_date');
        $this->form_validation->set_rules('SUPPLIER', 'Supplier', 'required');
        $this->form_validation->set_rules('TRUCK_NO', 'No. Truck', 'required|callback_valid_truck');
        $this->form_validation->set_rules('DRIVER_NAME', 'Nama Driver', 'required|trim|max_length[100]');
        if (!$this->form_validation->run()) return;
        $supplier = $this->po->get_supplier_by_code($this->input->post('SUPPLIER', TRUE));
        if (!$supplier) { $this->session->set_flashdata('po_error', 'Supplier tidak valid.'); redirect($po_no ? 'po/edit/'.$po_no : 'po/create'); return; }

        $basis = $this->input->post('CALCULATION_BASIS');
        $descs = $this->input->post('DESCRIPTION');
        $qtys = $this->input->post('QTY_EKOR');
        $bws = $this->input->post('TOTAL_BW');
        $prices = $this->input->post('PRICE');
        $remarks = $this->input->post('DETAIL_REMARK');
        $details = [];
        if (!is_array($basis) || count($basis) < 1) {
            $this->session->set_flashdata('po_error', 'Minimal satu baris detail wajib diisi.');
            redirect($po_no ? 'po/edit/'.$po_no : 'po/create'); return;
        }
        foreach ($basis as $i => $b) {
            $q = isset($qtys[$i]) ? (float)$qtys[$i] : 0;
            $bw = isset($bws[$i]) ? (float)$bws[$i] : 0;
            $price = isset($prices[$i]) ? (float)$prices[$i] : -1;
            $desc = trim(strip_tags($descs[$i] ?? ''));
            if (!in_array($b, ['EKOR','BW'], TRUE) || $q < 1 || floor($q) != $q || $bw <= 0 || $price < 0 || $desc === '') {
                $this->session->set_flashdata('po_error', 'Detail tidak valid. Ekor harus bilangan bulat, BW lebih dari 0, dan harga tidak boleh negatif.');
                redirect($po_no ? 'po/edit/'.$po_no : 'po/create'); return;
            }
            $details[] = ['CALCULATION_BASIS'=>$b,'DESCRIPTION'=>$desc,'QTY_EKOR'=>$q,'TOTAL_BW'=>$bw,'PRICE'=>$price,'REMARK'=>trim(strip_tags($remarks[$i] ?? ''))];
        }
        $header = ['PO_DATE'=>$this->input->post('PO_DATE', TRUE),'PLANT'=>'0001',
            'SUPPLIER'=>$supplier['CUST'],'TRUCK_NO'=>strtoupper(preg_replace('/\s+/', ' ', trim($this->input->post('TRUCK_NO', TRUE)))),
            'DRIVER_NAME'=>trim($this->input->post('DRIVER_NAME', TRUE)),
            'REMARK'=>trim($this->input->post('REMARK', TRUE))];
        $result = $this->po->save_po($header, $details, $po_no);
        if (!$result['ok']) { $this->session->set_flashdata('po_error', $result['message']); redirect($po_no ? 'po/edit/'.$po_no : 'po/create'); return; }
        redirect('po/detail/'.rawurlencode($result['po_no']));
    }

    public function valid_truck($value)
    {
        $value = strtoupper(preg_replace('/\s+/', ' ', trim($value)));
        if (!preg_match('/^[A-Z]{1,2} [0-9]{1,4} [A-Z]{1,3}$/', $value)) {
            $this->form_validation->set_message('valid_truck', 'Format No. Truck harus seperti B 9123 XYZ.');
            return FALSE;
        }
        return TRUE;
    }

    public function valid_date($value)
    {
        $dt = DateTime::createFromFormat('!Y-m-d', $value);
        if (!$dt || $dt->format('Y-m-d') !== $value) {
            $this->form_validation->set_message('valid_date', 'Tanggal PO tidak valid.');
            return FALSE;
        }
        return TRUE;
    }

    private function valid_date_old($date)
    {
        $dt = DateTime::createFromFormat('!Y-m-d', $date);
        return $dt && $dt->format('Y-m-d') === $date;
    }

    private function json_response($data, $http_code = 200)
    {
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
