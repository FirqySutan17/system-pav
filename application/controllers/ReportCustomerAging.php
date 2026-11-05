<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCustomerAging extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(
            'ReportCustomerAging_model',
            'report_aging'
        );
    }

    // =====================================================
    // GET FILTERS
    // =====================================================

    private function get_filters()
    {
        $as_of_date = $this->input->get('as_of_date', TRUE);

        return [
            'as_of_date' => $as_of_date !== NULL
                ? trim((string) $as_of_date)
                : date('Y-m-d'),

            'customer' => trim(
                (string) ($this->input->get('customer', TRUE) ?? '')
            ),

            'aging_bucket' => strtoupper(trim(
                (string) ($this->input->get('aging_bucket', TRUE) ?? '')
            )),

            'search' => trim(
                (string) ($this->input->get('search', TRUE) ?? '')
            )
        ];
    }

    // =====================================================
    // VALIDATE DATE
    // =====================================================

    private function is_valid_date($date)
    {
        if ($date === '') {
            return FALSE;
        }

        $dt = DateTime::createFromFormat('!Y-m-d', $date);

        return $dt && $dt->format('Y-m-d') === $date;
    }

    // =====================================================
    // INDEX
    // =====================================================

    public function index()
    {
        $filters = $this->get_filters();

        if (!$this->is_valid_date($filters['as_of_date'])) {
            show_error('As Of Date tidak valid.', 400);
            return;
        }

        $allowed_buckets = [
            '',
            'CURRENT',
            '1-30 DAYS',
            '31-60 DAYS',
            '61-90 DAYS',
            'OVER 90 DAYS',
            'UNCLASSIFIED'
        ];

        if (!in_array($filters['aging_bucket'], $allowed_buckets, TRUE)) {
            show_error('Aging Bucket tidak valid.', 400);
            return;
        }

        $data = [
            'title' => 'Customer Aging Report',
            'page_title' => 'Customer Aging Report',

            'filters' => $filters,

            'customers' => $this->report_aging->get_customer_options(),

            'aging_data' => $this->report_aging->get_report_aging(
                $filters
            ),

            'summary' => $this->report_aging->get_report_summary(
                $filters
            )
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/report_customer_aging/list', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // EXPORT EXCEL
    // =====================================================
    
    public function export_excel()
    {
        $filters = $this->get_filters();

        // =============================================
        // VALIDATION
        // =============================================

        if (!$this->is_valid_date($filters['as_of_date'])) {
            show_error('As Of Date tidak valid.', 400);
            return;
        }

        $allowed_buckets = [
            '',
            'CURRENT',
            '1-30 DAYS',
            '31-60 DAYS',
            '61-90 DAYS',
            'OVER 90 DAYS',
            'UNCLASSIFIED'
        ];

        if (!in_array($filters['aging_bucket'], $allowed_buckets, TRUE)) {
            show_error('Aging Bucket tidak valid.', 400);
            return;
        }

        // =============================================
        // GET DATA
        // =============================================

        $aging_data = $this->report_aging->get_report_aging($filters);

        $summary = $this->report_aging->get_report_summary($filters);

        // =============================================
        // LOAD PHPSPREADSHEET
        // =============================================

        $autoload = FCPATH . 'vendor/autoload.php';

        if (!file_exists($autoload)) {
            show_error(
                'PhpSpreadsheet tidak ditemukan. Periksa vendor/autoload.php.',
                500
            );
            return;
        }

        require_once $autoload;

        // =============================================
        // CREATE WORKBOOK
        // =============================================

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Customer Aging');

        // =============================================
        // TITLE
        // =============================================

        $sheet->setCellValue('A1', 'CUSTOMER AGING REPORT');
        $sheet->mergeCells('A1:O1');

        $sheet->setCellValue(
            'A2',
            'As Of Date: ' . $filters['as_of_date']
        );
        $sheet->mergeCells('A2:O2');

        $sheet->setCellValue(
            'A3',
            'Generated At: ' . date('d-m-Y H:i:s')
        );
        $sheet->mergeCells('A3:O3');

        // =============================================
        // FILTER INFORMATION
        // =============================================

        $customer_label = 'All Customers';

        if (!empty($filters['customer'])) {

            foreach (
                $this->report_aging->get_customer_options()
                as $customer
            ) {
                if ($customer['CUST'] === $filters['customer']) {

                    $customer_label =
                        $customer['CUST'] . ' - ' .
                        $customer['FULL_NAME'];

                    break;
                }
            }
        }

        $sheet->setCellValue(
            'A4',
            'Customer: ' . $customer_label
        );
        $sheet->mergeCells('A4:O4');

        $sheet->setCellValue(
            'A5',
            'Aging Bucket: ' .
            (
                $filters['aging_bucket'] !== ''
                    ? $filters['aging_bucket']
                    : 'All Buckets'
            )
        );
        $sheet->mergeCells('A5:O5');

        // =============================================
        // SUMMARY
        // =============================================

        $sheet->setCellValue('A7', 'AGING SUMMARY');
        $sheet->mergeCells('A7:C7');

        $summary_rows = [
            [
                'Total Transactions',
                (int) ($summary['TOTAL_RECORDS'] ?? 0),
                ''
            ],
            [
                'Total Customers',
                (int) ($summary['TOTAL_CUSTOMERS'] ?? 0),
                ''
            ],
            [
                'Total Outstanding',
                (float) ($summary['TOTAL_OUTSTANDING'] ?? 0),
                ''
            ],
            [
                'Current',
                (float) ($summary['TOTAL_CURRENT'] ?? 0),
                (int) ($summary['COUNT_CURRENT'] ?? 0)
            ],
            [
                '1-30 Days',
                (float) ($summary['TOTAL_1_30'] ?? 0),
                (int) ($summary['COUNT_1_30'] ?? 0)
            ],
            [
                '31-60 Days',
                (float) ($summary['TOTAL_31_60'] ?? 0),
                (int) ($summary['COUNT_31_60'] ?? 0)
            ],
            [
                '61-90 Days',
                (float) ($summary['TOTAL_61_90'] ?? 0),
                (int) ($summary['COUNT_61_90'] ?? 0)
            ],
            [
                'Over 90 Days',
                (float) ($summary['TOTAL_OVER_90'] ?? 0),
                (int) ($summary['COUNT_OVER_90'] ?? 0)
            ],
            [
                'Unclassified',
                (float) ($summary['TOTAL_UNCLASSIFIED'] ?? 0),
                (int) ($summary['COUNT_UNCLASSIFIED'] ?? 0)
            ]
        ];

        $sheet->setCellValue('A8', 'Category');
        $sheet->setCellValue('B8', 'Outstanding');
        $sheet->setCellValue('C8', 'Transactions');

        $row = 9;

        foreach ($summary_rows as $summary_row) {

            $sheet->setCellValue('A' . $row, $summary_row[0]);
            $sheet->setCellValue('B' . $row, $summary_row[1]);
            $sheet->setCellValue('C' . $row, $summary_row[2]);

            $row++;
        }

        // =============================================
        // DETAIL HEADER
        // =============================================

        $detail_start = 21;

        $sheet->setCellValue(
            'A' . $detail_start,
            'CUSTOMER AGING DETAIL'
        );

        $sheet->mergeCells(
            'A' . $detail_start . ':O' . $detail_start
        );

        $headers = [
            'No',
            'Customer Code',
            'Customer Name',
            'Master Order No',
            'Customer Order No',
            'Order Date',
            'TOP (Days)',
            'Due Date',
            'Aging Days',
            'Aging Bucket',
            'Order Total',
            'Paid',
            'Total CN',
            'Outstanding',
            'Payment Status'
        ];

        $header_row = $detail_start + 1;

        foreach ($headers as $index => $header) {

            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

            $sheet->setCellValue(
                $column . $header_row,
                $header
            );
        }

        // =============================================
        // DETAIL DATA
        // =============================================

        $row = $header_row + 1;
        $no = 1;

        $total_order = 0;
        $total_paid = 0;
        $total_cn = 0;
        $total_outstanding = 0;

        foreach ($aging_data as $item) {

            $order_total = (float) ($item['ORDER_TOTAL'] ?? 0);
            $paid = (float) ($item['PAID'] ?? 0);
            $total_cn_item = (float) ($item['TOTAL_CN'] ?? 0);
            $outstanding = (float) ($item['OUTSTANDING'] ?? 0);

            $total_order += $order_total;
            $total_paid += $paid;
            $total_cn += $total_cn_item;
            $total_outstanding += $outstanding;

            $values = [
                $no++,
                $item['CUST'],
                $item['CUSTOMER_NAME'],
                $item['ORDER_NO'],
                $item['ORDER_CUSTOMER_NO'],
                $item['ORDER_DATE'],
                $item['TERMS_OF_PAYMENT'],
                $item['DUE_DATE'],
                $item['AGING_DAYS'],
                $item['AGING_BUCKET'],
                $order_total,
                $paid,
                $total_cn_item,
                $outstanding,
                $item['PAYMENT_STATUS']
            ];

            foreach ($values as $index => $value) {

                $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $index + 1
                );

                // Master Order dan Customer Order dipaksa sebagai TEXT
                // agar nomor tidak berubah menjadi angka/scientific notation.
                if ($column === 'D' || $column === 'E') {

                    $sheet->setCellValueExplicit(
                        $column . $row,
                        (string) $value,
                        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                    );

                } else {

                    $sheet->setCellValue(
                        $column . $row,
                        $value
                    );
                }
            }

            $row++;
        }

        // =============================================
        // GRAND TOTAL
        // =============================================

        $data_last_row = $row - 1;

        if (!empty($aging_data)) {

            $total_row = $row;

            $sheet->setCellValue(
                'A' . $total_row,
                'GRAND TOTAL'
            );

            $sheet->mergeCells(
                'A' . $total_row . ':J' . $total_row
            );

            $sheet->setCellValue('K' . $total_row, $total_order);
            $sheet->setCellValue('L' . $total_row, $total_paid);
            $sheet->setCellValue('M' . $total_row, $total_cn);
            $sheet->setCellValue('N' . $total_row, $total_outstanding);

            $row++;
        }

        $last_row = max($row - 1, $header_row);

        // =============================================
        // STYLING - MAIN TITLE
        // =============================================

        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => TRUE,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '17365D']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // =============================================
        // STYLING - SUMMARY
        // =============================================

        $sheet->getStyle('A7:C7')->applyFromArray([
            'font' => [
                'bold' => TRUE,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ]
        ]);

        $sheet->getStyle('A8:C8')->applyFromArray([
            'font' => [
                'bold' => TRUE
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9EAF7']
            ]
        ]);

        $sheet->getStyle('B9:B17')
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        $sheet->getStyle('A8:C17')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );

        // =============================================
        // STYLING - DETAIL TITLE
        // =============================================

        $sheet->getStyle(
            'A' . $detail_start . ':O' . $detail_start
        )->applyFromArray([
            'font' => [
                'bold' => TRUE,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '17365D']
            ]
        ]);

        // =============================================
        // STYLING - DETAIL HEADER
        // =============================================

        $sheet->getStyle(
            'A' . $header_row . ':O' . $header_row
        )->applyFromArray([
            'font' => [
                'bold' => TRUE
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9EAF7']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => TRUE
            ]
        ]);

        // =============================================
        // NUMBER FORMAT - DETAIL
        // =============================================

        if (!empty($aging_data)) {

            // Order Total, Paid, Total CN, Outstanding
            $sheet->getStyle(
                'K' . ($header_row + 1) . ':N' . $data_last_row
            )->getNumberFormat()->setFormatCode('#,##0.00');

            // GRAND TOTAL
            $sheet->getStyle(
                'K' . $total_row . ':N' . $total_row
            )->getNumberFormat()->setFormatCode('#,##0.00');

            // GRAND TOTAL STYLE
            $sheet->getStyle(
                'A' . $total_row . ':O' . $total_row
            )->applyFromArray([
                'font' => [
                    'bold' => TRUE
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E9EFF7']
                ]
            ]);
        }

        // =============================================
        // BORDERS - DETAIL
        // =============================================

        $sheet->getStyle(
            'A' . $header_row . ':O' . $last_row
        )->getBorders()->getAllBorders()->setBorderStyle(
            \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
        );

        // =============================================
        // COLUMN WIDTHS
        // =============================================

        $widths = [
            'A' => 8,
            'B' => 16,
            'C' => 28,
            'D' => 30,
            'E' => 30,
            'F' => 14,
            'G' => 12,
            'H' => 14,
            'I' => 12,
            'J' => 18,
            'K' => 18,
            'L' => 18,
            'M' => 18,
            'N' => 18,
            'O' => 18
        ];

        foreach ($widths as $column => $width) {

            $sheet->getColumnDimension($column)
                ->setWidth($width);
        }

        $sheet->getRowDimension($header_row)->setRowHeight(35);

        // =============================================
        // ALIGNMENT
        // =============================================

        if (!empty($aging_data)) {

            // Nomor Master Order dan Customer Order
            $sheet->getStyle(
                'D' . ($header_row + 1) . ':E' . $data_last_row
            )->getAlignment()->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            );

            // Kolom nominal
            $sheet->getStyle(
                'K' . ($header_row + 1) . ':N' . $total_row
            )->getAlignment()->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            );
        }

        // =============================================
        // FREEZE / FILTER
        // =============================================

        $sheet->freezePane('A' . ($header_row + 1));

        if (!empty($aging_data)) {

            // Filter hanya sampai data transaksi,
            // tidak termasuk baris Grand Total.
            $sheet->setAutoFilter(
                'A' . $header_row . ':O' . $data_last_row
            );
        }

        // =============================================
        // PAGE SETUP
        // =============================================

        $sheet->getPageSetup()
            ->setOrientation(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
            )
            ->setPaperSize(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
            )
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getPageSetup()->setFitToPage(TRUE);

        // =============================================
        // OUTPUT
        // =============================================

        $filename = 'Customer_Aging_' .
            date('Ymd_His') .
            '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header(
            'Content-Disposition: attachment;filename="' . $filename . '"'
        );

        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        exit;
    }

}