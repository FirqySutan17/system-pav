<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportPayment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('ReportPayment_model', 'report_payment');
        $this->load->helper(['form', 'url']);
    }

    // =====================================================
    // PAYMENT REPORT
    // =====================================================

    public function index()
    {
        $filters = $this->get_filters();

        if (
            !empty($filters['date_from']) &&
            !empty($filters['date_to']) &&
            $filters['date_from'] > $filters['date_to']
        ) {
            show_error('Tanggal From tidak boleh lebih besar dari tanggal To.', 400);
            return;
        }

        $data = [
            'title'      => 'Report Payment',
            'page_title' => 'Report Payment',
            'filters'    => $filters,
            'suppliers'  => $this->report_payment->get_supplier_options(),
            'payments'   => $this->report_payment->get_report_payment($filters),
            'summary'    => $this->report_payment->get_report_summary($filters)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/report_payment/list', $data);
        $this->load->view('templates/footer', $data);
    }

    // =====================================================
    // EXPORT EXCEL
    // =====================================================

    public function export_excel()
    {
        $filters = $this->get_filters();

        // Validasi tanggal
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

        // Ambil data Payment
        $payments = $this->report_payment->get_report_payment($filters);

        if (empty($payments)) {
            show_error('Tidak ada data Payment untuk diexport.', 404);
            return;
        }

        // Composer autoload
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

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0']
                ]
            ]
        ];

        $titleStyle = [
            'font' => [
                'bold' => TRUE,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '243247']
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
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '40516F']
            ],
            'alignment' => [
                'horizontal' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];

        // =====================================================
        // SHEET 1: PAYMENT SUMMARY
        // =====================================================

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Payment Summary');

        $headers = [
            'No',
            'Payment No',
            'Payment Date',
            'Supplier Code',
            'Supplier Name',
            'Method',
            'Type',
            'Slip No',
            'Supplier Orders',
            'Total Payment',
            'Status',
            'Registered By',
            'Registered Date'
        ];

        $lastColumn = 'M';

        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'REPORT PAYMENT');
        $sheet->getStyle('A1:M1')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Filter information
        $filterInfo = [
            'A3' => 'Date From',
            'B3' => $filters['date_from'] ?: 'All',
            'C3' => 'Date To',
            'D3' => $filters['date_to'] ?: 'All',
            'E3' => 'Supplier',
            'F3' => $filters['supplier'] ?: 'All Suppliers',
            'G3' => 'Method',
            'H3' => $filters['method'] ?: 'All Methods',

            'A4' => 'Type',
            'B4' => $filters['type'] ?: 'All Types',
            'C4' => 'Status',
            'D4' => $filters['status'] ?: 'All Status',
            'E4' => 'Search',
            'F4' => $filters['search'] ?: '-'
        ];

        foreach ($filterInfo as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        foreach (['A3', 'C3', 'E3', 'G3', 'A4', 'C4', 'E4'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(TRUE);
        }

        // Table header
        $headerRow = 6;
        $column = 'A';

        foreach ($headers as $header) {
            $sheet->setCellValue($column . $headerRow, $header);
            $column++;
        }

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")
            ->applyFromArray($headerStyle);

        // Data
        $rowNumber = 7;
        $no = 1;

        $totalPosted = 0;
        $totalVoid = 0;

        foreach ($payments as $payment) {

            $paymentDate = !empty($payment['PAYMENT_DATE'])
                ? date('d/m/Y', strtotime($payment['PAYMENT_DATE']))
                : '-';

            $registeredDate = !empty($payment['REG_DATE'])
                ? date('d/m/Y H:i:s', strtotime($payment['REG_DATE']))
                : '-';

            $sheet->setCellValue('A' . $rowNumber, $no++);
            $sheet->setCellValue('B' . $rowNumber, $payment['PAYMENT_NO']);
            $sheet->setCellValue('C' . $rowNumber, $paymentDate);
            $sheet->setCellValue('D' . $rowNumber, $payment['SUPPLIER']);
            $sheet->setCellValue('E' . $rowNumber, $payment['SUPPLIER_NAME'] ?: '-');
            $sheet->setCellValue('F' . $rowNumber, $payment['PAYMENT_METHOD']);
            $sheet->setCellValue('G' . $rowNumber, $payment['PAYMENT_TYPE']);
            $sheet->setCellValue('H' . $rowNumber, $payment['SLIP_NO'] ?: '-');
            $sheet->setCellValue('I' . $rowNumber, $payment['ORDER_LIST'] ?: '-');
            $sheet->setCellValue('J' . $rowNumber, (float) $payment['TOTAL_PAYMENT']);
            $sheet->setCellValue('K' . $rowNumber, $payment['STATUS']);
            $sheet->setCellValue('L' . $rowNumber, $payment['REG_EMP'] ?: '-');
            $sheet->setCellValue('M' . $rowNumber, $registeredDate);

            if ($payment['STATUS'] === 'POSTED') {
                $totalPosted += (float) $payment['TOTAL_PAYMENT'];
            } elseif ($payment['STATUS'] === 'VOID') {
                $totalVoid += (float) $payment['TOTAL_PAYMENT'];
            }

            $rowNumber++;
        }

        $lastDataRow = $rowNumber - 1;

        // Grand total
        $sheet->setCellValue('A' . $rowNumber, 'TOTAL PAYMENT POSTED');
        $sheet->mergeCells('A' . $rowNumber . ':I' . $rowNumber);
        $sheet->setCellValue('J' . $rowNumber, $totalPosted);

        $sheet->getStyle("A{$rowNumber}:M{$rowNumber}")->applyFromArray([
            'font' => ['bold' => TRUE],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAF0F8']
            ],
            'borders' => [
                'top' => [
                    'borderStyle' =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '40516F']
                ]
            ]
        ]);

        $rowNumber++;

        $sheet->setCellValue('A' . $rowNumber, 'TOTAL PAYMENT VOID');
        $sheet->mergeCells('A' . $rowNumber . ':I' . $rowNumber);
        $sheet->setCellValue('J' . $rowNumber, $totalVoid);

        $sheet->getStyle("A{$rowNumber}:M{$rowNumber}")->applyFromArray([
            'font' => ['bold' => TRUE],
            'fill' => [
                'fillType' =>
                    \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF0F0']
            ]
        ]);

        $sheet->getStyle("A6:M{$rowNumber}")->applyFromArray($borderStyle);

        $sheet->getStyle("J7:J{$rowNumber}")
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        $sheet->getStyle("J7:J{$rowNumber}")
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            );

        $widths = [
            'A' => 7,
            'B' => 22,
            'C' => 15,
            'D' => 17,
            'E' => 30,
            'F' => 15,
            'G' => 13,
            'H' => 20,
            'I' => 35,
            'J' => 20,
            'K' => 14,
            'L' => 20,
            'M' => 22
        ];

        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->getStyle("I7:I{$lastDataRow}")
            ->getAlignment()->setWrapText(TRUE);

        $sheet->freezePane('A7');
        $sheet->setAutoFilter("A6:M{$lastDataRow}");

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
        $sheet->getPageSetup()->setFitToPage(TRUE);


        // =====================================================
        // SHEET 2: ALLOCATION DETAIL
        // =====================================================

        $detailSheet = $spreadsheet->createSheet();
        $detailSheet->setTitle('Allocation Detail');

        $detailHeaders = [
            'No',
            'Payment No',
            'Payment Date',
            'Supplier Code',
            'Supplier Name',
            'Supplier Order No',
            'Master Order No',
            'Order Date',
            'Order Total',
            'Allocation Amount',
            'Current Paid',
            'Current Remain',
            'Payment Status'
        ];

        $detailSheet->mergeCells('A1:M1');
        $detailSheet->setCellValue('A1', 'PAYMENT ALLOCATION DETAIL');
        $detailSheet->getStyle('A1:M1')->applyFromArray($titleStyle);
        $detailSheet->getRowDimension(1)->setRowHeight(30);

        $detailSheet->setCellValue('A2', 'Date From');
        $detailSheet->setCellValue('B2', $filters['date_from'] ?: 'All');
        $detailSheet->setCellValue('C2', 'Date To');
        $detailSheet->setCellValue('D2', $filters['date_to'] ?: 'All');

        foreach (['A2', 'C2'] as $cell) {
            $detailSheet->getStyle($cell)->getFont()->setBold(TRUE);
        }

        $detailHeaderRow = 4;
        $column = 'A';

        foreach ($detailHeaders as $header) {
            $detailSheet->setCellValue($column . $detailHeaderRow, $header);
            $column++;
        }

        $detailSheet->getStyle('A4:M4')->applyFromArray($headerStyle);

        $detailRow = 5;
        $detailNo = 1;

        foreach ($payments as $payment) {

            $allocations = $this->report_payment->get_allocation_detail(
                $payment['PAYMENT_ID']
            );

            foreach ($allocations as $allocation) {

                $paymentDate = !empty($payment['PAYMENT_DATE'])
                    ? date('d/m/Y', strtotime($payment['PAYMENT_DATE']))
                    : '-';

                $orderDate = !empty($allocation['ORDER_DATE'])
                    ? date('d/m/Y', strtotime($allocation['ORDER_DATE']))
                    : '-';

                $detailSheet->setCellValue('A' . $detailRow, $detailNo++);
                $detailSheet->setCellValue('B' . $detailRow, $payment['PAYMENT_NO']);
                $detailSheet->setCellValue('C' . $detailRow, $paymentDate);
                $detailSheet->setCellValue('D' . $detailRow, $payment['SUPPLIER']);
                $detailSheet->setCellValue('E' . $detailRow, $payment['SUPPLIER_NAME'] ?: '-');

                $detailSheet->setCellValue(
                    'F' . $detailRow,
                    $allocation['ORDER_SUPPLIER_NO'] ?: '-'
                );

                $detailSheet->setCellValue(
                    'G' . $detailRow,
                    $allocation['ORDER_NO'] ?: '-'
                );

                $detailSheet->setCellValue('H' . $detailRow, $orderDate);
                $detailSheet->setCellValue('I' . $detailRow, (float) $allocation['ORDER_TOTAL']);
                $detailSheet->setCellValue('J' . $detailRow, (float) $allocation['PAID_AMOUNT']);
                $detailSheet->setCellValue('K' . $detailRow, (float) $allocation['CURRENT_PAID']);
                $detailSheet->setCellValue('L' . $detailRow, (float) $allocation['CURRENT_REMAIN']);
                $detailSheet->setCellValue('M' . $detailRow, $allocation['PAYMENT_STATUS'] ?: '-');

                $detailRow++;
            }
        }

        $detailLastRow = max(4, $detailRow - 1);

        if ($detailLastRow >= 5) {
            $detailSheet->getStyle("I5:L{$detailLastRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

            $detailSheet->getStyle("A4:M{$detailLastRow}")
                ->applyFromArray($borderStyle);
        }

        $detailWidths = [
            'A' => 7,
            'B' => 22,
            'C' => 15,
            'D' => 17,
            'E' => 30,
            'F' => 24,
            'G' => 22,
            'H' => 15,
            'I' => 20,
            'J' => 22,
            'K' => 20,
            'L' => 20,
            'M' => 18
        ];

        foreach ($detailWidths as $col => $width) {
            $detailSheet->getColumnDimension($col)->setWidth($width);
        }

        $detailSheet->freezePane('A5');
        $detailSheet->setAutoFilter("A4:M{$detailLastRow}");

        $detailSheet->getPageSetup()
            ->setOrientation(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
            );

        $detailSheet->getPageSetup()
            ->setPaperSize(
                \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
            );

        $detailSheet->getPageSetup()->setFitToWidth(1);
        $detailSheet->getPageSetup()->setFitToHeight(0);
        $detailSheet->getPageSetup()->setFitToPage(TRUE);


        // =====================================================
        // DOWNLOAD
        // =====================================================

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Report_Payment_' . date('Ymd_His') . '.xlsx';

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();

        exit;
    }

    // =====================================================
    // REPORT FILTERS
    // =====================================================

    private function get_filters()
    {
        return [
            'supplier' => trim((string) (
                $this->input->get('supplier', TRUE) !== NULL
                    ? $this->input->get('supplier', TRUE)
                    : ''
            )),

            'date_from' => trim((string) (
                $this->input->get('date_from', TRUE) !== NULL
                    ? $this->input->get('date_from', TRUE)
                    : date('Y-m-01')
            )),

            'date_to' => trim((string) (
                $this->input->get('date_to', TRUE) !== NULL
                    ? $this->input->get('date_to', TRUE)
                    : date('Y-m-t')
            )),

            'method' => strtoupper(trim((string) (
                $this->input->get('method', TRUE) ?? ''
            ))),

            'type' => strtoupper(trim((string) (
                $this->input->get('type', TRUE) ?? ''
            ))),

            'status' => strtoupper(trim((string) (
                $this->input->get('status', TRUE) ?? ''
            ))),

            'search' => trim((string) (
                $this->input->get('search', TRUE) ?? ''
            ))
        ];
    }

    // =====================================================
    // GET ALLOCATION DETAIL
    // GET: reportpayment/get-allocation/{payment_id}
    // =====================================================

    public function get_allocation($payment_id = 0)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            return $this->json_response(405, [
                'ok'      => FALSE,
                'message' => 'Method not allowed.'
            ]);
        }

        $payment_id = (int) $payment_id;

        if ($payment_id <= 0) {
            return $this->json_response(400, [
                'ok'      => FALSE,
                'message' => 'Payment ID tidak valid.'
            ]);
        }

        $details = $this->report_payment->get_allocation_detail($payment_id);

        return $this->json_response(200, [
            'ok'   => TRUE,
            'data' => $details
        ]);
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
}