
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportOrder extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('ReportOrder_model', 'report_order');
        $this->load->helper(['url', 'form']);
    }

    // =====================================================
    // INDEX
    // =====================================================

    public function index()
    {
        $filters = $this->get_filters();

        // Validasi tanggal
        if (
            !empty($filters['date_from']) &&
            !empty($filters['date_to']) &&
            $filters['date_from'] > $filters['date_to']
        ) {
            $this->session->set_flashdata(
                'report_error',
                'Tanggal From tidak boleh lebih besar dari tanggal To.'
            );

            redirect('reportorder');
            return;
        }

        $data = [
            'title' => 'Report Order',
            'page_title' => 'Report Order',

            'orders' => $this->report_order->get_report_order($filters),

            'customers' => $this->report_order->get_customer_options(),
            'suppliers' => $this->report_order->get_supplier_options(),

            'filters' => $filters
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/report_order/list', $data);
        $this->load->view('templates/footer');
    }

    // =====================================================
    // EXPORT EXCEL
    // =====================================================

    public function export_excel()
    {
        $filters = $this->get_filters();

        // =================================================
        // VALIDASI TANGGAL
        // =================================================

        if (
            !empty($filters['date_from']) &&
            !empty($filters['date_to']) &&
            $filters['date_from'] > $filters['date_to']
        ) {
            show_error(
                'Tanggal From tidak boleh lebih besar dari tanggal To.',
                400
            );
            return;
        }

        $orders = $this->report_order->get_report_order($filters);

        if (empty($orders)) {
            show_error('Tidak ada data untuk diexport.', 404);
            return;
        }

        // =================================================
        // COMPOSER AUTOLOAD
        // =================================================

        $autoload = FCPATH . 'vendor/autoload.php';

        if (!file_exists($autoload)) {
            show_error(
                'PhpSpreadsheet tidak ditemukan. Periksa vendor/autoload.php.',
                500
            );
            return;
        }

        require_once $autoload;

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Report Order');

        // =================================================
        // TITLE
        // =================================================

        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'REPORT ORDER');

        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => TRUE,
                'size' => 16,
                'color' => [
                    'rgb' => '172554'
                ]
            ],
            'alignment' => [
                'horizontal' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(30);

        // =================================================
        // FILTER INFORMATION
        // =================================================

        $filterInfo = [
            'A2' => 'Date From',
            'B2' => $filters['date_from'] ?: 'All',

            'C2' => 'Date To',
            'D2' => $filters['date_to'] ?: 'All',

            'E2' => 'Customer',
            'F2' => $filters['cust'] ?: 'All',

            'G2' => 'Supplier',
            'H2' => $filters['supplier'] ?: 'All',

            'I2' => 'Status',
            'J2' => $filters['status'] ?: 'All',

            'K2' => 'Search',
            'L2' => $filters['search'] ?: 'All'
        ];

        foreach ($filterInfo as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A2:L2')->applyFromArray([
            'font' => [
                'size' => 10
            ],
            'alignment' => [
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        foreach (['A2', 'C2', 'E2', 'G2', 'I2', 'K2'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(TRUE);
        }

        // =================================================
        // TABLE HEADER
        // =================================================

        $headers = [
            'No. Order',
            'Tanggal',
            'No. Sales/Supplier',
            'Customer/Supplier',
            'Basis',
            'Ekor',
            'BW',
            'Avg. BW',
            'Price',
            'Total',
            'Diff Price',
            'Total Diff Price',
            'Remain',
            'Payment Status'
        ];

        $headerRow = 4;

        foreach ($headers as $index => $header) {

            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

            $sheet->setCellValue(
                $column . $headerRow,
                $header
            );
        }

        $sheet->getStyle('A4:N4')->applyFromArray([
            'font' => [
                'bold' => TRUE,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
                'size' => 10
            ],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '344563'
                ]
            ],
            'alignment' => [
                'horizontal' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => TRUE
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D9E2F3'
                    ]
                ]
            ]
        ]);

        $sheet->getRowDimension(4)->setRowHeight(30);

        // =================================================
        // TABLE DATA
        // =================================================

        $rowNumber = 5;

        foreach ($orders as $index => $row) {

            $type = strtoupper($row['ORDER_TYPE'] ?? '');

            $nextRow = $orders[$index + 1] ?? NULL;

            // ---------------------------------------------
            // CHECK CUSTOMER - SUPPLIER PAIR
            // ---------------------------------------------

            $hasPair = (
                $type === 'CUSTOMER' &&
                $nextRow !== NULL &&
                strtoupper($nextRow['ORDER_TYPE'] ?? '') === 'SUPPLIER' &&
                (string) $nextRow['ORDER_ID'] === (string) $row['ORDER_ID']
            );

            // ---------------------------------------------
            // DIFF PRICE
            // Source: Supplier Order
            // ---------------------------------------------

            $diffPrice = $hasPair
                ? $nextRow['DIFF_PRICE']
                : NULL;

            $totalDiffPrice = $hasPair
                ? $nextRow['TOTAL_DIFF_PRICE']
                : NULL;

            // ---------------------------------------------
            // MASTER ORDER
            // Merge only on Customer row
            // ---------------------------------------------

            if ($type === 'CUSTOMER') {

                // No. Order
                $sheet->setCellValue(
                    'A' . $rowNumber,
                    $row['ORDER_NO']
                );

                // Tanggal
                $orderDate = !empty($row['ORDER_DATE'])
                    ? date('d/m/Y', strtotime($row['ORDER_DATE']))
                    : '-';

                $sheet->setCellValue(
                    'B' . $rowNumber,
                    $orderDate
                );

                // Diff Price
                if ($diffPrice !== NULL && $diffPrice !== '') {
                    $sheet->setCellValue(
                        'K' . $rowNumber,
                        (float) $diffPrice
                    );
                }

                // Total Diff Price
                if ($totalDiffPrice !== NULL && $totalDiffPrice !== '') {
                    $sheet->setCellValue(
                        'L' . $rowNumber,
                        (float) $totalDiffPrice
                    );
                }

                // Merge cells for Customer + Supplier
                if ($hasPair) {

                    $nextExcelRow = $rowNumber + 1;

                    $sheet->mergeCells(
                        'A' . $rowNumber . ':A' . $nextExcelRow
                    );

                    $sheet->mergeCells(
                        'B' . $rowNumber . ':B' . $nextExcelRow
                    );

                    $sheet->mergeCells(
                        'K' . $rowNumber . ':K' . $nextExcelRow
                    );

                    $sheet->mergeCells(
                        'L' . $rowNumber . ':L' . $nextExcelRow
                    );
                }
            }

            // ---------------------------------------------
            // TRANSACTION DATA
            // ---------------------------------------------

            $transactionValues = [
                'C' => $row['TRANSACTION_NO'],
                'D' => $row['PARTY_NAME'] ?: $row['PARTY_CODE'],
                'E' => $row['BASIS'],
                'F' => $row['EKOR'],
                'G' => $row['KG'],
                'H' => $row['AVG_BW'],
                'I' => $row['PRICE'],
                'J' => $row['TOTAL'],
                'M' => $row['REMAIN'],
                'N' => $row['PAYMENT_STATUS']
            ];

            foreach ($transactionValues as $column => $value) {

                $cell = $column . $rowNumber;

                // Numeric columns
                if (in_array($column, [
                    'F', 'G', 'H', 'I', 'J', 'M'
                ], TRUE)) {

                    if ($value !== NULL && $value !== '') {
                        $sheet->setCellValue(
                            $cell,
                            (float) $value
                        );
                    }

                } else {

                    $sheet->setCellValueExplicit(
                        $cell,
                        (string) ($value ?? ''),
                        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                    );
                }
            }

            $sheet->getRowDimension($rowNumber)->setRowHeight(24);

            $rowNumber++;
        }

        $lastRow = $rowNumber - 1;

        // =================================================
        // NUMBER FORMAT
        // Match List View
        // =================================================

        // Ekor
        $sheet->getStyle('F5:F' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // BW and Avg BW
        $sheet->getStyle('G5:H' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        // Price, Total, Diff Price, Total Diff Price, Remain
        $sheet->getStyle('I5:M' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // =================================================
        // BORDERS
        // =================================================

        $sheet->getStyle('A4:N' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'D9E2F3'
                    ]
                ]
            ],
            'alignment' => [
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        // =================================================
        // ALIGNMENT
        // =================================================

        $sheet->getStyle('F5:M' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            );

        $sheet->getStyle('A5:B' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('N5:N' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        // =================================================
        // AUTO WIDTH
        // =================================================

        foreach (range('A', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(TRUE);
        }

        // Set minimum widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(18);

        // =================================================
        // FREEZE HEADER
        // =================================================

        $sheet->freezePane('A5');

        // AutoFilter tidak digunakan karena beberapa kolom
        // menggunakan merged cells untuk pasangan transaksi.

        // =================================================
        // PAGE SETUP
        // =================================================

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

        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(4, 4);

        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setBottom(0.5);
        $sheet->getPageMargins()->setLeft(0.3);
        $sheet->getPageMargins()->setRight(0.3);

        // =================================================
        // DOWNLOAD
        // =================================================

        $filename = 'Report_Order_' . date('Ymd_His') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment;filename="' . $filename . '"'
        );

        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        exit;
    }

    // =====================================================
    // GET FILTERS
    // =====================================================

    private function get_filters()
    {
        return [
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
    }
}
