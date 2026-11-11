
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

        // =============================================
        // GET DATA
        // =============================================

        $overdue_data = $this->report_overdue
            ->get_report_overdue($filters);

        $summary = $this->report_overdue
            ->get_report_summary($filters);

        $customers = $this->report_overdue
            ->get_customer_options();

        $customer_name = 'All Customers';

        if (!empty($filters['customer'])) {
            foreach ($customers as $customer) {
                if ($customer['CUST'] === $filters['customer']) {
                    $customer_name = $customer['FULL_NAME'];
                    break;
                }
            }
        }

        // =============================================
        // LOAD PHP SPREADSHEET
        // =============================================

        require_once FCPATH . 'vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Customer Overdue');

        // =============================================
        // TITLE
        // =============================================

        $sheet->setCellValue('A1', 'CUSTOMER OVERDUE REPORT');

        $sheet->mergeCells('A1:N1');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        $sheet->setCellValue('A2', 'As Of Date: ' . date('d-m-Y', strtotime($filters['as_of_date'])));
        $sheet->setCellValue('A3', 'Customer: ' . $customer_name);
        $sheet->setCellValue('A4', 'Generated At: ' . date('d-m-Y H:i:s'));

        // =============================================
        // SUMMARY
        // =============================================

        $sheet->setCellValue('A6', 'SUMMARY');

        $sheet->mergeCells('A6:B6');

        $sheet->getStyle('A6:B6')->getFont()->setBold(true);

        $summary_rows = [
            ['Total Overdue', $summary['TOTAL_OUTSTANDING'] ?? 0],
            ['Total Transactions', $summary['TOTAL_RECORDS'] ?? 0],
            ['Total Customers', $summary['TOTAL_CUSTOMERS'] ?? 0],
            ['Total Order', $summary['TOTAL_ORDER'] ?? 0],
            ['Total Paid', $summary['TOTAL_PAID'] ?? 0],
            ['Total Credit Note', $summary['TOTAL_CN'] ?? 0],
            ['Maximum Overdue Days', $summary['MAX_OVERDUE_DAYS'] ?? 0],
            ['Average Overdue Days', $summary['AVG_OVERDUE_DAYS'] ?? 0],
            ['Outstanding 1-30 Days', $summary['TOTAL_1_30'] ?? 0],
            ['Outstanding 31-60 Days', $summary['TOTAL_31_60'] ?? 0],
            ['Outstanding 61-90 Days', $summary['TOTAL_61_90'] ?? 0],
            ['Outstanding Over 90 Days', $summary['TOTAL_OVER_90'] ?? 0],
        ];

        $row = 7;

        foreach ($summary_rows as $summary_row) {
            $sheet->setCellValue('A' . $row, $summary_row[0]);
            $sheet->setCellValue('B' . $row, $summary_row[1]);

            $row++;
        }

        // =============================================
        // DETAIL TABLE
        // =============================================

        $header_row = 21;

        $headers = [
            'No',
            'Customer Code',
            'Customer Name',
            'Master Order',
            'Customer Order',
            'Order Date',
            'TOP',
            'Due Date',
            'Overdue Days',
            'Overdue Bucket',
            'Order Total',
            'Paid',
            'Total CN',
            'Outstanding',
            'Payment Status'
        ];

        // Detail table menggunakan kolom A sampai O
        $header_row = 21;

        foreach ($headers as $index => $header) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);

            $sheet->setCellValue($column . $header_row, $header);
        }

        $last_column = 'O';

        $sheet->getStyle("A{$header_row}:{$last_column}{$header_row}")
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '1769AA'
                    ]
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ]);

        $row = $header_row + 1;

        $no = 1;

        $total_order = 0;
        $total_paid = 0;
        $total_cn = 0;
        $total_outstanding = 0;

        foreach ($overdue_data as $item) {

            $order_total = (float) ($item['ORDER_TOTAL'] ?? 0);
            $paid = (float) ($item['PAID'] ?? 0);
            $cn = (float) ($item['TOTAL_CN'] ?? 0);
            $outstanding = (float) ($item['OUTSTANDING'] ?? 0);

            $days = (int) $item['OVERDUE_DAYS'];

            if ($days <= 30) {
                $bucket = '1-30 DAYS';
            } elseif ($days <= 60) {
                $bucket = '31-60 DAYS';
            } elseif ($days <= 90) {
                $bucket = '61-90 DAYS';
            } else {
                $bucket = 'OVER 90 DAYS';
            }

            $values = [
                $no++,
                $item['CUST'],
                $item['CUSTOMER_NAME'],
                $item['ORDER_NO'],
                $item['ORDER_CUSTOMER_NO'],
                !empty($item['ORDER_DATE']) ? date('d-m-Y', strtotime($item['ORDER_DATE'])) : '',
                $item['TERMS_OF_PAYMENT'] !== NULL ? (int) $item['TERMS_OF_PAYMENT'] . ' Days' : '',
                !empty($item['DUE_DATE']) ? date('d-m-Y', strtotime($item['DUE_DATE'])) : '',
                $days,
                $bucket,
                $order_total,
                $paid,
                $cn,
                $outstanding,
                $item['PAYMENT_STATUS']
            ];

            foreach ($values as $index => $value) {
                $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);

                $sheet->setCellValue($column . $row, $value);
            }

            $total_order += $order_total;
            $total_paid += $paid;
            $total_cn += $cn;
            $total_outstanding += $outstanding;

            $row++;
        }

        // =============================================
        // GRAND TOTAL
        // =============================================

        if (!empty($overdue_data)) {

            $sheet->mergeCells("A{$row}:J{$row}");

            $sheet->setCellValue("A{$row}", 'GRAND TOTAL');

            $sheet->setCellValue("K{$row}", $total_order);
            $sheet->setCellValue("L{$row}", $total_paid);
            $sheet->setCellValue("M{$row}", $total_cn);
            $sheet->setCellValue("N{$row}", $total_outstanding);

            $sheet->getStyle("A{$row}:O{$row}")->applyFromArray([
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E9ECEF'
                    ]
                ]
            ]);
        }

        // =============================================
        // NUMBER FORMAT
        // =============================================

        $last_data_row = max($header_row + 1, $row);

        foreach (['B', 'C', 'D', 'E'] as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach (['A', 'F', 'G', 'H', 'I', 'J', 'O'] as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach (['K', 'L', 'M', 'N'] as $column) {
            $sheet->getColumnDimension($column)->setWidth(20);

            $sheet->getStyle("{$column}7:{$column}{$last_data_row}")
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
        }

        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(22);

        // =============================================
        // TABLE BORDER
        // =============================================

        $sheet->getStyle("A{$header_row}:O" . max($header_row, $row))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );

        // =============================================
        // FREEZE PANE & AUTOFILTER
        // =============================================

        $sheet->freezePane('A22');

        $sheet->setAutoFilter("A{$header_row}:O{$header_row}");

        // =============================================
        // PAGE SETUP
        // =============================================

        $sheet->getPageSetup()
            ->setOrientation(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
            );

        $sheet->getPageSetup()
            ->setPaperSize(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
            );

        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $sheet->getPageSetup()->setFitToPage(true);

        // =============================================
        // DOWNLOAD
        // =============================================

        $filename = 'Customer_Overdue_' . date('Ymd_His') . '.xlsx';

        // Bersihkan output buffer agar file tidak corrupt
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();

        exit;
    }

}
