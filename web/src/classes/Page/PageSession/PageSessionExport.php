<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class PageSessionExport
{
    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function styleHeader($columnIndex, $rowIndex, &$sheet) {

        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'rotation' => 90,
                'startColor' => array(
                    'argb' => 'FFffff99',
                ),
                'endColor' => array(
                    'argb' => 'FFffff99',
                ),
            ),
        );

        $sheet->getStyleByColumnAndRow($columnIndex, $rowIndex)->applyFromArray($styleArray);
    }

    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param $value
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function setHeader($columnIndex, $rowIndex, $value, &$sheet) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
        self::styleHeader($columnIndex, $rowIndex, $sheet);
    }

    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param $value
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function setDataCell($columnIndex, $rowIndex, $value, &$sheet) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
        self::styleDataCell($columnIndex, $rowIndex, $sheet);
    }

    /**
     * @param int $columnIndex
     * @param int $rowIndex
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private static function styleDataCell($columnIndex, $rowIndex, &$sheet) {

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ),
            ),
        );

        $sheet->getStyleByColumnAndRow($columnIndex, $rowIndex)->applyFromArray($styleArray);
    }

    /**
     * Display text responses in sheet
     * @param int $sessionQuestionID
     * @param array $config
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param mysqli $mysqli
     */
    private static function text($sessionQuestionID, &$sheet, $config, $mysqli) {
        
        $responses = DatabaseResponse::loadResponses($sessionQuestionID, $mysqli);

        $i = 7;
        foreach($responses as $response) {
            self::setDataCell(1, $i, $response->getUsername(), $sheet);
            self::setDataCell(2, $i, date($config["datetime"]["datetime"]["long"], $response->getTime()), $sheet);
            self::setDataCell(3, $i, $response->getResponse(), $sheet);
            self::setDataCell(4, $i, "N/A", $sheet);
            self::setDataCell(5, $i, 0, $sheet);
            $i++;
        }
    }


    /**
     * @param Question $question
     * @param PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $config
     * @param mysqli $mysqli
     */
    private static function sheet($question, &$sheet, $config, $mysqli) {

        $sessionQuestionID = $question->getSessionQuestionID();

        // Add question details headings
        self::setHeader(1, 1, "Question", $sheet);
        self::setHeader(1, 2, "Type", $sheet);
        self::setHeader(1, 3, "Answer", $sheet);
        self::setHeader(1, 4, "Date/Time", $sheet);

        // Add question details values
        self::setDataCell(2, 1, $question->getQuestion(), $sheet);
        self::setDataCell(2, 2, $question->getTypeDisplay(), $sheet);
        self::setDataCell(2, 3, "", $sheet);
        self::setDataCell(2, 4, date($config["datetime"]["datetime"]["long"], $question->getCreated()), $sheet);

        // Add headings
        self::setHeader(1, 6, "Username", $sheet);
        self::setHeader(2, 6, "Date/Time", $sheet);
        self::setHeader(3, 6, "Response", $sheet);
        self::setHeader(4, 6, "Correct?", $sheet);
        self::setHeader(5, 6, "Points", $sheet);

        // Auto resize all columns
        for($i = 1; $i <= 5; $i++)
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);

        self::text($sessionQuestionID, $sheet, $config, $mysqli);
    }

    public static function export() {
        $config = Flight::get("config");

        // Connect to database
        $databaseConnect = Flight::get("databaseConnect");
        $mysqli = $databaseConnect();

        $spreadsheet = new Spreadsheet();

        $questions = DatabaseSessionQuestion::loadSessionQuestions(1, $mysqli);

        $first = true;

        // For each question
        foreach ($questions["questions"] as $question) {
            /** @var $question Question */

            // If this is the first sheet
            if($first) {
                $first = false;
                $sheet = $spreadsheet->getActiveSheet();
            }

            else {
                $sheet = $spreadsheet->createSheet();
            }

            // Create a new sheet
            $sheet->setTitle("Q" . strval($question->getQuestionID()));

            self::sheet($question, $sheet, $config, $mysqli);
        }

        //$sessionQuestionID = 3;

        // Load the question
        //$question = DatabaseSessionQuestion::loadQuestion($sessionQuestionID, $mysqli);


        // Create a new temp file
        $tempFile = tempnam("/tmp", "");

        // Save the spreadsheet to a temporary file
        $writer = new Xls($spreadsheet);
        $writer->save($tempFile);

        // Output the spreadsheet file for download
        header("Content-Disposition: attachment; filename=\"report.xls\"");
        header("Content-Type: application/force-download");
        header("Content-Length: " . filesize($tempFile));
        header("Connection: close");
        readfile($tempFile);

        // Remove temp file
        unlink($tempFile);
    }
}