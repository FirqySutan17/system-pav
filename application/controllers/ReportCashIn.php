<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCashIn extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('ReportCashIn_model', 'report_cashin');
        $this->load->helper(['form', 'url']);
    }

    // =====================================================
    // GET FILTERS
    // =====================================================
    private function get_filters()
    {
        return [
            'date_from' => trim((string) $this->input->get('date_from', TRUE)),
            'date_to'   => trim((string) $this->input->get('date_to', TRUE)),
            'customer'  => trim((string) $this->input->get('customer', TRUE)),
            'method'    => trim((string) $this->input->get('method', TRUE)),
            'status'    => trim((string) $this->input->get('status', TRUE)),
            'search'    => trim((string) $this->input->get('search', TRUE))
        ];
    }

    // =====================================================
    // REPORT CASH IN
    // =====================================================
    public function index()
    {
        $filters = $this->get_filters();

        // Default periode: bulan berjalan
        if ($filters['date_from'] === '') {
            $filters['date_from'] = date('Y-m-01');
        }

        if ($filters['date_to'] === '') {
            $filters['date_to'] = date('Y-m-t');
        }

        $data = [
            'title'       => 'Report Cash In',
            'page_title'  => 'Report Cash In',
            'filters'     => $filters,
            'customers'   => $this->report_cashin->get_customer_options(),
            'cash_ins'    => $this->report_cashin->get_report_cash_in($filters),
            'summary'     => $this->report_cashin->get_report_summary($filters)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('admin/report_cash_in/list', $data);
        $this->load->view('templates/footer');
    }

    public function export_excel()
    {
        // =====================================
        // FILTER
        // =====================================
        $filters = $this->get_filters();

        // =====================================
        // VALIDASI TANGGAL
        // =====================================
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

        // =====================================
        // GET DATA
        // =====================================
        $cashIns = $this->report_cashin->get_report_cash_in($filters);

        if (empty($cashIns)) {
            show_error('Tidak ada data Cash In untuk diexport.', 404);
            return;
        }

        // =====================================
        // COMPOSER AUTOLOAD
        // =====================================
        $autoload = FCPATH . 'vendor/autoload.php';

        if (!file_exists($autoload)) {
            show_error(
                'PhpSpreadsheet tidak ditemukan. Periksa vendor/autoload.php.',
                500
            );
            return;
        }

        require_once $autoload;

        // =====================================
        // CREATE EXCEL
        // =====================================
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Style umum
        $titleStyle = [
            'font' => [
                'bold' => TRUE,
                'size' => 16,
                'color' => ['rgb' => '172554']
            ],
            'alignment' => [
                'horizontal' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];

        $headerStyle = [
            'font' => [
                'bold' => TRUE,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10
            ],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '344563']
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
                    'color' => ['rgb' => 'D9E2F3']
                ]
            ]
        ];

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9E2F3']
                ]
            ],
            'alignment' => [
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];

        /*
        |--------------------------------------------------------------------------
        | SHEET 1: CASH IN SUMMARY
        |--------------------------------------------------------------------------
        */

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Cash In Summary');

        // =====================================
        // TITLE
        // =====================================
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'REPORT CASH IN');

        $sheet->getStyle('A1:M1')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // =====================================
        // FILTER INFORMATION
        // =====================================
        $filterInfo = [
            'A2' => 'Date From',
            'B2' => $filters['date_from'] ?: 'All',

            'C2' => 'Date To',
            'D2' => $filters['date_to'] ?: 'All',

            'E2' => 'Customer',
            'F2' => $filters['customer'] ?: 'All',

            'G2' => 'Method',
            'H2' => $filters['method'] ?: 'All',

            'I2' => 'Status',
            'J2' => $filters['status'] ?: 'All',

            'K2' => 'Search',
            'L2' => $filters['search'] ?: 'All'
        ];

        foreach ($filterInfo as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A2:L2')->applyFromArray([
            'font' => ['size' => 10],
            'alignment' => [
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        foreach (['A2', 'C2', 'E2', 'G2', 'I2', 'K2'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(TRUE);
        }

        // =====================================
        // TABLE HEADER
        // =====================================
        $headers = [
            'No',
            'Cash In No',
            'Cash In Date',
            'Customer Code',
            'Customer Name',
            'Method',
            'Type',
            'Slip No',
            'Customer Orders',
            'Total Cash In',
            'Total Allocation',
            'Status',
            'Registered By'
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

        $sheet->getStyle('A4:M4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(35);

        // =====================================
        // TABLE DATA
        // =====================================
        $rowNumber = 5;
        $no = 1;

        $totalCashIn = 0;
        $totalAllocation = 0;

        foreach ($cashIns as $row) {

            // NO
            $sheet->setCellValue(
                'A' . $rowNumber,
                $no++
            );

            // CASH IN NO
            $sheet->setCellValueExplicit(
                'B' . $rowNumber,
                (string) ($row['CASH_IN_NO'] ?? ''),
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // CASH IN DATE
            $sheet->setCellValue(
                'C' . $rowNumber,
                !empty($row['CASH_IN_DATE'])
                    ? date('d/m/Y', strtotime($row['CASH_IN_DATE']))
                    : ''
            );

            // CUSTOMER
            $sheet->setCellValue(
                'D' . $rowNumber,
                $row['CUSTOMER'] ?? ''
            );

            $sheet->setCellValue(
                'E' . $rowNumber,
                $row['CUSTOMER_NAME'] ?? ''
            );

            // METHOD
            $sheet->setCellValue(
                'F' . $rowNumber,
                $row['CASH_IN_METHOD'] ?? ''
            );

            // TYPE
            $sheet->setCellValue(
                'G' . $rowNumber,
                $row['CASH_IN_TYPE'] ?? ''
            );

            // SLIP NO
            $sheet->setCellValue(
                'H' . $rowNumber,
                $row['SLIP_NO'] ?? ''
            );

            // CUSTOMER ORDERS
            $sheet->setCellValue(
                'I' . $rowNumber,
                $row['ORDER_LIST'] ?? ''
            );

            // TOTAL CASH IN
            $cashInAmount = (float) ($row['TOTAL_CASH_IN'] ?? 0);

            $sheet->setCellValue(
                'J' . $rowNumber,
                $cashInAmount
            );

            // TOTAL ALLOCATION
            $allocationAmount = (float) ($row['TOTAL_ALLOCATION'] ?? 0);

            $sheet->setCellValue(
                'K' . $rowNumber,
                $allocationAmount
            );

            // STATUS
            $sheet->setCellValue(
                'L' . $rowNumber,
                $row['STATUS'] ?? ''
            );

            // REGISTERED BY
            $sheet->setCellValue(
                'M' . $rowNumber,
                $row['REG_EMP'] ?? ''
            );

            $totalCashIn += $cashInAmount;
            $totalAllocation += $allocationAmount;

            $sheet->getRowDimension($rowNumber)->setRowHeight(24);

            $rowNumber++;
        }

        $lastRow = $rowNumber - 1;

        // =====================================
        // GRAND TOTAL
        // =====================================
        $grandTotalRow = $rowNumber;

        $sheet->mergeCells(
            'A' . $grandTotalRow . ':I' . $grandTotalRow
        );

        $sheet->setCellValue(
            'A' . $grandTotalRow,
            'GRAND TOTAL'
        );

        $sheet->setCellValue(
            'J' . $grandTotalRow,
            $totalCashIn
        );

        $sheet->setCellValue(
            'K' . $grandTotalRow,
            $totalAllocation
        );

        $sheet->getStyle(
            'A' . $grandTotalRow . ':M' . $grandTotalRow
        )->applyFromArray([
            'font' => [
                'bold' => TRUE,
                'color' => ['rgb' => '172554']
            ],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAF0F8']
            ],
            'borders' => [
                'top' => [
                    'borderStyle' =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '344563']
                ]
            ]
        ]);

        // =====================================
        // NUMBER FORMAT
        // =====================================
        $sheet->getStyle('J5:K' . $grandTotalRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        // =====================================
        // BORDERS
        // =====================================
        $sheet->getStyle('A4:M' . $grandTotalRow)
            ->applyFromArray($borderStyle);

        // =====================================
        // ALIGNMENT
        // =====================================
        $sheet->getStyle('J5:K' . $grandTotalRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            );

        $sheet->getStyle('A5:A' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('F5:G' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('L5:L' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('I5:I' . $lastRow)
            ->getAlignment()
            ->setWrapText(TRUE);

        // =====================================
        // AUTO WIDTH
        // =====================================
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(TRUE);
        }

        // Minimum widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(28);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(35);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(22);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(20);

        // =====================================
        // FREEZE HEADER
        // =====================================
        $sheet->freezePane('A5');

        // AutoFilter tidak menyertakan Grand Total
        $sheet->setAutoFilter('A4:M' . $lastRow);

        // =====================================
        // PAGE SETUP
        // =====================================
        $sheet->getPageSetup()->setOrientation(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
        );

        $sheet->getPageSetup()->setPaperSize(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3
        );

        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);

        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setBottom(0.5);
        $sheet->getPageMargins()->setLeft(0.3);
        $sheet->getPageMargins()->setRight(0.3);


        /*
        |--------------------------------------------------------------------------
        | SHEET 2: ALLOCATION DETAIL
        |--------------------------------------------------------------------------
        */

        $detailSheet = $spreadsheet->createSheet();
        $detailSheet->setTitle('Allocation Detail');

        // =====================================
        // TITLE
        // =====================================
        $detailSheet->mergeCells('A1:J1');
        $detailSheet->setCellValue(
            'A1',
            'CASH IN ALLOCATION DETAIL'
        );

        $detailSheet->getStyle('A1:J1')->applyFromArray($titleStyle);
        $detailSheet->getRowDimension(1)->setRowHeight(30);

        // =====================================
        // FILTER INFORMATION
        // =====================================
        $detailSheet->setCellValue('A2', 'Date From');
        $detailSheet->setCellValue('B2', $filters['date_from'] ?: 'All');

        $detailSheet->setCellValue('C2', 'Date To');
        $detailSheet->setCellValue('D2', $filters['date_to'] ?: 'All');

        $detailSheet->setCellValue('E2', 'Customer');
        $detailSheet->setCellValue('F2', $filters['customer'] ?: 'All');

        $detailSheet->setCellValue('G2', 'Method');
        $detailSheet->setCellValue('H2', $filters['method'] ?: 'All');

        // =====================================
        // TABLE HEADER
        // =====================================
        $detailHeaders = [
            'No',
            'Cash In No',
            'Cash In Date',
            'Customer',
            'Customer Order No',
            'Order No',
            'Order Date',
            'Order Total',
            'Allocation Amount',
            'Remain'
        ];

        $detailHeaderRow = 4;

        foreach ($detailHeaders as $index => $header) {

            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

            $detailSheet->setCellValue(
                $column . $detailHeaderRow,
                $header
            );
        }

        $detailSheet->getStyle('A4:J4')->applyFromArray($headerStyle);
        $detailSheet->getRowDimension(4)->setRowHeight(35);

        // =====================================
        // ALLOCATION DATA
        // =====================================
        $detailRow = 5;
        $detailNo = 1;

        foreach ($cashIns as $cashIn) {

            $allocations = $this->report_cashin->get_allocation_detail(
                $cashIn['CASH_IN_ID']
            );

            foreach ($allocations as $allocation) {

                $cashInDate = '';

                if (!empty($cashIn['CASH_IN_DATE'])) {
                    $cashInDate = date(
                        'd/m/Y',
                        strtotime($cashIn['CASH_IN_DATE'])
                    );
                }

                $orderDate = '';

                if (!empty($allocation['ORDER_DATE'])) {
                    $orderDate = date(
                        'd/m/Y',
                        strtotime($allocation['ORDER_DATE'])
                    );
                }

                $detailSheet->setCellValue('A' . $detailRow, $detailNo++);

                $detailSheet->setCellValueExplicit(
                    'B' . $detailRow,
                    (string) ($cashIn['CASH_IN_NO'] ?? ''),
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                );

                $detailSheet->setCellValue('C' . $detailRow, $cashInDate);

                $detailSheet->setCellValue(
                    'D' . $detailRow,
                    $cashIn['CUSTOMER'] ?? ''
                );

                $detailSheet->setCellValue(
                    'E' . $detailRow,
                    $allocation['ORDER_CUSTOMER_NO'] ?? ''
                );

                $detailSheet->setCellValue(
                    'F' . $detailRow,
                    $allocation['ORDER_NO'] ?? ''
                );

                $detailSheet->setCellValue('G' . $detailRow, $orderDate);

                $detailSheet->setCellValue(
                    'H' . $detailRow,
                    (float) ($allocation['ORDER_TOTAL'] ?? 0)
                );

                $detailSheet->setCellValue(
                    'I' . $detailRow,
                    (float) ($allocation['PAID_AMOUNT'] ?? 0)
                );

                $detailSheet->setCellValue(
                    'J' . $detailRow,
                    (float) ($allocation['ORDER_REMAIN'] ?? 0)
                );

                $detailSheet->getRowDimension($detailRow)->setRowHeight(24);

                $detailRow++;
            }
        }

        $detailLastRow = $detailRow - 1;

        // =====================================
        // ALLOCATION FORMATTING
        // =====================================
        if ($detailLastRow >= 5) {

            $detailSheet->getStyle('H5:J' . $detailLastRow)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

            $detailSheet->getStyle('A4:J' . $detailLastRow)
                ->applyFromArray($borderStyle);

            $detailSheet->getStyle('H5:J' . $detailLastRow)
                ->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                );

            $detailSheet->getStyle('A5:A' . $detailLastRow)
                ->getAlignment()
                ->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );

        } else {

            // Tetap tampilkan border header walaupun tidak ada allocation
            $detailSheet->getStyle('A4:J4')
                ->applyFromArray($borderStyle);
        }

        // =====================================
        // AUTO WIDTH
        // =====================================
        foreach (range('A', 'J') as $column) {
            $detailSheet->getColumnDimension($column)->setAutoSize(TRUE);
        }

        $detailSheet->getColumnDimension('A')->setWidth(8);
        $detailSheet->getColumnDimension('B')->setWidth(22);
        $detailSheet->getColumnDimension('C')->setWidth(15);
        $detailSheet->getColumnDimension('D')->setWidth(18);
        $detailSheet->getColumnDimension('E')->setWidth(25);
        $detailSheet->getColumnDimension('F')->setWidth(22);
        $detailSheet->getColumnDimension('G')->setWidth(15);
        $detailSheet->getColumnDimension('H')->setWidth(20);
        $detailSheet->getColumnDimension('I')->setWidth(22);
        $detailSheet->getColumnDimension('J')->setWidth(20);

        // =====================================
        // FREEZE HEADER & AUTOFILTER
        // =====================================
        $detailSheet->freezePane('A5');

        if ($detailLastRow >= 5) {
            $detailSheet->setAutoFilter('A4:J' . $detailLastRow);
        } else {
            $detailSheet->setAutoFilter('A4:J4');
        }

        // =====================================
        // PAGE SETUP
        // =====================================
        $detailSheet->getPageSetup()->setOrientation(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
        );

        $detailSheet->getPageSetup()->setPaperSize(
            \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3
        );

        $detailSheet->getPageSetup()->setFitToWidth(1);
        $detailSheet->getPageSetup()->setFitToHeight(0);

        $detailSheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);

        $detailSheet->getPageMargins()->setTop(0.5);
        $detailSheet->getPageMargins()->setBottom(0.5);
        $detailSheet->getPageMargins()->setLeft(0.3);
        $detailSheet->getPageMargins()->setRight(0.3);


        // =====================================
        // DOWNLOAD
        // =====================================
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Report_Cash_In_' . date('Ymd_His') . '.xlsx';

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
    // GET ALLOCATION DETAIL
    // =====================================================
    public function get_allocation($cash_in_id = 0)
    {
        $cash_in_id = (int) $cash_in_id;

        if ($cash_in_id <= 0) {
            show_404();
            return;
        }

        $details = $this->report_cashin->get_allocation_detail($cash_in_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'ok'   => TRUE,
                'data' => $details
            ]));
    }
}