
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCustomerOverdue extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(
            'ReportCustomerOverdue_model',
            'report_overdue'
        );

        $this->load->helper([
            'url',
            'form'
        ]);
    }

    // =============================================
    // GET FILTERS
    // =============================================

    private function get_filters()
    {
        $as_of_date = $this->input->get('as_of_date', TRUE);

        return [
            'as_of_date' => $as_of_date !== NULL
                ? trim($as_of_date)
                : date('Y-m-d'),

            'customer' => trim(
                (string) $this->input->get('customer', TRUE)
            ),

            'search' => trim(
                (string) $this->input->get('search', TRUE)
            )
        ];
    }

    // =============================================
    // VALIDATE DATE
    // =============================================

    private function is_valid_date($date)
    {
        if (empty($date)) {
            return FALSE;
        }

        $d = DateTime::createFromFormat('Y-m-d', $date);

        return $d && $d->format('Y-m-d') === $date;
    }

    // =============================================
    // INDEX
    // =============================================

    public function index()
    {
        $filters = $this->get_filters();

        if (!$this->is_valid_date($filters['as_of_date'])) {
            show_error('As Of Date tidak valid.', 400);
            return;
        }

        // =============================================
        // GET DATA
        // =============================================

        $data = [];

        $data['title'] = 'Customer Overdue Report';
        $data['page_title'] = 'Customer Overdue Report';

        $data['filters'] = $filters;

        $data['customers'] =
            $this->report_overdue->get_customer_options();

        $data['overdue_data'] =
            $this->report_overdue->get_report_overdue($filters);

        $data['summary'] =
            $this->report_overdue->get_report_summary($filters);

        // =============================================
        // LOAD VIEW
        // =============================================

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);

        $this->load->view(
            'admin/report_customer_overdue/list',
            $data
        );

        $this->load->view('templates/footer', $data);
    }

    // =============================================
    // EXPORT EXCEL
    // =============================================

    public function export_excel()
    {
        $filters = $this->get_filters();

        if (!$this->is_valid_date($filters['as_of_date'])) {
            show_error('As Of Date tidak valid.', 400);
            return;
        }

        // Export akan kita implementasikan setelah View selesai.
        // Untuk sementara, arahkan ke halaman report.

        redirect(
            'reportcustomeroverdue?' . http_build_query($filters)
        );
    }
}
