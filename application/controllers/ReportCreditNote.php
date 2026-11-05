<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportCreditNote extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(
            'ReportCreditNote_model',
            'report_credit_note'
        );

        $this->load->library(['session']);
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        // Default periode: awal bulan sampai akhir bulan berjalan
        $defaultDateFrom = date('Y-m-01');
        $defaultDateTo   = date('Y-m-t');

        // Ambil filter dari GET
        $dateFrom = $this->input->get('date_from', TRUE);
        $dateTo   = $this->input->get('date_to', TRUE);

        // Jika parameter tidak dikirim, gunakan default bulan berjalan
        if ($dateFrom === NULL) {
            $dateFrom = $defaultDateFrom;
        }

        if ($dateTo === NULL) {
            $dateTo = $defaultDateTo;
        }

        $filters = [
            'date_from'  => $dateFrom,
            'date_to'    => $dateTo,
            'order_type' => $this->input->get('order_type', TRUE) ?? '',
            'status'     => $this->input->get('status', TRUE) ?? '',
            'search'     => $this->input->get('search', TRUE) ?? ''
        ];

        // Ambil data report
        $data['credit_notes'] = $this->report_credit_note
            ->get_report_credit_note($filters);

        $data['filters'] = $filters;
        $data['title'] = 'Report Credit Note';

        // Load layout
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/report_credit_note/list', $data);
        $this->load->view('templates/footer', $data);
    }

    public function get_list()
    {
        if (strtoupper($this->input->method(TRUE)) !== 'POST') {
            return $this->json_response([
                'success' => FALSE,
                'message' => 'Method tidak diizinkan.',
                'data'    => []
            ]);
        }

        $filters = [
            'search'     => trim((string) $this->input->post('search', TRUE)),
            'date_from'  => trim((string) $this->input->post('date_from', TRUE)),
            'date_to'    => trim((string) $this->input->post('date_to', TRUE)),
            'order_type' => strtoupper(trim((string) $this->input->post('order_type', TRUE))),
            'status'     => strtoupper(trim((string) $this->input->post('status', TRUE)))
        ];

        $data = $this->report_credit_note->get_report_credit_note($filters);

        return $this->json_response([
            'success' => TRUE,
            'data'    => $data
        ]);
    }

    public function export_excel()
    {
        // =====================================
        // FILTER
        // =====================================
        $filters = [
            'date_from'  => $this->input->get('date_from', TRUE) !== NULL
                ? $this->input->get('date_from', TRUE)
                : date('Y-m-01'),

            'date_to'    => $this->input->get('date_to', TRUE) !== NULL
                ? $this->input->get('date_to', TRUE)
                : date('Y-m-t'),

            'order_type' => $this->input->get('order_type', TRUE) ?? '',
            'status'     => $this->input->get('status', TRUE) ?? '',
            'search'     => $this->input->get('search', TRUE) ?? ''
        ];

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
        $creditNotes = $this->report_credit_note
            ->get_report_credit_note($filters);

        if (empty($creditNotes)) {
            show_error('Tidak ada data Credit Note untuk diexport.', 404);
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
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Report Credit Note');

        // =====================================
        // TITLE
        // =====================================
        $sheet->mergeCells('A1:Q1');
        $sheet->setCellValue('A1', 'REPORT CREDIT NOTE');

        $sheet->getStyle('A1:Q1')->applyFromArray([
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
        ]);

        $sheet->getRowDimension(1)->setRowHeight(30);

        // =====================================
        // FILTER INFORMATION
        // =====================================
        $filterInfo = [
            'A2' => 'Date From',
            'B2' => $filters['date_from'] ?: 'All',

            'C2' => 'Date To',
            'D2' => $filters['date_to'] ?: 'All',

            'E2' => 'Order Type',
            'F2' => $filters['order_type'] ?: 'All',

            'G2' => 'Status',
            'H2' => $filters['status'] ?: 'All',

            'I2' => 'Search',
            'J2' => $filters['search'] ?: 'All'
        ];

        foreach ($filterInfo as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $sheet->getStyle('A2:J2')->applyFromArray([
            'font' => ['size' => 10],
            'alignment' => [
                'vertical' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        foreach (['A2', 'C2', 'E2', 'G2', 'I2'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(TRUE);
        }

        // =====================================
        // TABLE HEADER
        // =====================================
        $headers = [
            'CN No.',
            'CN Date',
            'Order Type',
            'Master Order No.',
            'Customer Order No.',
            'Supplier Order No.',
            'Customer',
            'Supplier',
            'CN Price',
            'CN Customer Total',
            'CN Supplier Total',
            'CN Total',
            'Approval Status',
            'Requested By',
            'Requested At',
            'Approved By',
            'Approved At'
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

        $sheet->getStyle('A4:Q4')->applyFromArray([
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
        ]);

        $sheet->getRowDimension(4)->setRowHeight(35);

        // =====================================
        // TABLE DATA
        // =====================================
        $rowNumber = 5;

        foreach ($creditNotes as $row) {

            $type = strtoupper($row['ORDER_TYPE'] ?? '');

            // CN NO
            $sheet->setCellValueExplicit(
                'A' . $rowNumber,
                (string) ($row['CN_NO'] ?? ''),
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );

            // CN DATE
            $sheet->setCellValue(
                'B' . $rowNumber,
                !empty($row['CN_DATE'])
                    ? date('d/m/Y', strtotime($row['CN_DATE']))
                    : ''
            );

            // ORDER TYPE
            $typeLabel = $type === 'CUSTOMER_SUPPLIER'
                ? 'CUSTOMER + SUPPLIER'
                : $type;

            $sheet->setCellValue('C' . $rowNumber, $typeLabel);

            // ORDER INFORMATION
            $sheet->setCellValue('D' . $rowNumber, $row['ORDER_NO'] ?? '');
            $sheet->setCellValue('E' . $rowNumber, $row['ORDER_CUSTOMER_NO'] ?? '');
            $sheet->setCellValue('F' . $rowNumber, $row['ORDER_SUPPLIER_NO'] ?? '');

            // CUSTOMER / SUPPLIER
            $sheet->setCellValue('G' . $rowNumber, $row['CUSTOMER_NAME'] ?? '');
            $sheet->setCellValue('H' . $rowNumber, $row['SUPPLIER_NAME'] ?? '');

            // CN PRICE
            $sheet->setCellValue(
                'I' . $rowNumber,
                isset($row['CN_PRICE']) && $row['CN_PRICE'] !== ''
                    ? (float) $row['CN_PRICE']
                    : NULL
            );

            // CN CUSTOMER TOTAL
            $sheet->setCellValue(
                'J' . $rowNumber,
                isset($row['CN_CUSTOMER_TOTAL']) && $row['CN_CUSTOMER_TOTAL'] !== ''
                    ? (float) $row['CN_CUSTOMER_TOTAL']
                    : NULL
            );

            // CN SUPPLIER TOTAL
            $sheet->setCellValue(
                'K' . $rowNumber,
                isset($row['CN_SUPPLIER_TOTAL']) && $row['CN_SUPPLIER_TOTAL'] !== ''
                    ? (float) $row['CN_SUPPLIER_TOTAL']
                    : NULL
            );

            // CN TOTAL
            $sheet->setCellValue(
                'L' . $rowNumber,
                isset($row['CN_TOTAL']) && $row['CN_TOTAL'] !== ''
                    ? (float) $row['CN_TOTAL']
                    : NULL
            );

            // APPROVAL STATUS
            $sheet->setCellValue(
                'M' . $rowNumber,
                $row['APPROVAL_STATUS'] ?? ''
            );

            // REQUESTED BY
            $sheet->setCellValue(
                'N' . $rowNumber,
                $row['REQUESTED_BY'] ?? ''
            );

            // REQUESTED AT
            $sheet->setCellValue(
                'O' . $rowNumber,
                !empty($row['REQUESTED_AT'])
                    ? date('d/m/Y H:i', strtotime($row['REQUESTED_AT']))
                    : ''
            );

            // APPROVED BY
            $sheet->setCellValue(
                'P' . $rowNumber,
                $row['APPROVED_BY'] ?? ''
            );

            // APPROVED AT
            $sheet->setCellValue(
                'Q' . $rowNumber,
                !empty($row['APPROVED_AT'])
                    ? date('d/m/Y H:i', strtotime($row['APPROVED_AT']))
                    : ''
            );

            $sheet->getRowDimension($rowNumber)->setRowHeight(24);

            $rowNumber++;
        }

        $lastRow = $rowNumber - 1;

        // =====================================
        // NUMBER FORMAT
        // =====================================
        $sheet->getStyle('I5:L' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        // =====================================
        // BORDERS
        // =====================================
        $sheet->getStyle('A4:Q' . $lastRow)->applyFromArray([
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
        ]);

        // =====================================
        // ALIGNMENT
        // =====================================
        $sheet->getStyle('I5:L' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            );

        $sheet->getStyle('A5:C' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle('M5:M' . $lastRow)
            ->getAlignment()
            ->setHorizontal(
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            );

        // =====================================
        // AUTO WIDTH
        // =====================================
        foreach (range('A', 'Q') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(TRUE);
        }

        // Minimum widths
        $sheet->getColumnDimension('A')->setWidth(22);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(23);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(24);
        $sheet->getColumnDimension('F')->setWidth(24);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);

        // =====================================
        // FREEZE HEADER
        // =====================================
        $sheet->freezePane('A5');

        // AutoFilter
        $sheet->setAutoFilter('A4:Q' . $lastRow);

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

        // =====================================
        // DOWNLOAD
        // =====================================
        $filename = 'Report_Credit_Note_' . date('Ymd_His') . '.xlsx';

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
   
    private function json_response($data)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}