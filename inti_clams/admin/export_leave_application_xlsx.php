<?php
require_once "../database/dbconnect.php";
require 'vendor1/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

// Check if the user is logged in
session_start();
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Prepare the query with placeholders
$query = "SELECT leave_application.leave_id, leave_application.student_id, leave_application.student_name, 
                 leave_application.state, subject.subject_id, leave_application.start_date, leave_application.end_date, 
                 leave_application.reason, leave_application.documents, leave_application.lecturer_approval, 
                 leave_application.lecturer_remarks, leave_application.ioav_approval, leave_application.ioav_remarks, 
                 leave_application.hop_approval, leave_application.hop_remarks
          FROM leave_application
          JOIN subject ON leave_application.subject_id = subject.subject_id
          WHERE leave_application.is_deleted = 0";

$result = $con->query($query);
$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "Error fetching data: " . $con->error;
}

// Count approvals and rejections
$totalRecords = count($data);
$approvedCount = 0;
$rejectedCount = 0;

foreach ($data as $row) {
    $lecturerApproved = $row['lecturer_approval'] === 'Approved';
    $hopApproved = $row['hop_approval'] === 'Approved';
    $ioavApproved = ($row['state'] === 'International') ? $row['ioav_approval'] === 'Approved' : true;

    if ($lecturerApproved && $hopApproved && $ioavApproved) {
        $approvedCount++;
    } else {
        $rejectedCount++;
    }
}

// Create the spreadsheet
$spreadsheet = new Spreadsheet();
$spreadsheet->setActiveSheetIndex(0);
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Leave Applications');

// Write headers to the first sheet
$headers = ['Leave ID', 'Student ID', 'Student Name', 'State', 'Subject Code', 'Start Date', 'End Date', 'Reason', 'Documents', 'Lecturer Approval', 'Lecturer Remarks', 'IOAV Approval', 'IOAV Remarks', 'HOP Approval', 'HOP Remarks'];
$sheet->fromArray($headers, NULL, 'A1');

// Write data to the first sheet
$rowIndex = 2;
foreach ($data as $row) {
    $sheet->fromArray(array_values($row), NULL, "A$rowIndex");
    $rowIndex++;
}

// Create a new sheet for the analysis
$analysisSheet = $spreadsheet->createSheet();
$spreadsheet->setActiveSheetIndex(1);
$analysisSheet->setTitle('Analysis');

// Add the text paragraph
$analysisSheet->setCellValue('A1', 'Leave Application Analysis');
$analysisSheet->setCellValue('A2', "Total Records: $totalRecords");
$analysisSheet->setCellValue('A3', "Approved: $approvedCount");
$analysisSheet->setCellValue('A4', "Rejected: $rejectedCount");

// Add the chart data
$analysisSheet->setCellValue('A6', 'Status');
$analysisSheet->setCellValue('B6', 'Count');
$analysisSheet->setCellValue('A7', 'Approved');
$analysisSheet->setCellValue('B7', $approvedCount);
$analysisSheet->setCellValue('A8', 'Rejected');
$analysisSheet->setCellValue('B8', $rejectedCount);

// Define the chart
$dataSeriesLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Analysis!$B$6', NULL, 1)];
$xAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Analysis!$A$7:$A$8', NULL, 2)];
$dataSeriesValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Analysis!$B$7:$B$8', NULL, 2)];

$series = new DataSeries(
    DataSeries::TYPE_PIECHART, 
    NULL, 
    range(0, count($dataSeriesValues) - 1), 
    $dataSeriesLabels, 
    $xAxisTickValues, 
    $dataSeriesValues
);

$plotArea = new PlotArea(NULL, [$series]);
$chart = new Chart(
    'Approval Status Chart', 
    new Title('Approval Status Chart'), 
    new Legend(Legend::POSITION_RIGHT, NULL, false), 
    $plotArea
);

$chart->setTopLeftPosition('A10');
$chart->setBottomRightPosition('H25');
$analysisSheet->addChart($chart);

// Save the Excel file
$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true);
$excelFileName = 'leave_application_analysis_' . date("Y-m-d") . '.xlsx';
$writer->save($excelFileName);

// Provide the Excel file for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . basename($excelFileName) . '"');
header('Cache-Control: max-age=0');
readfile($excelFileName);

// Exit the script
exit();
?>
