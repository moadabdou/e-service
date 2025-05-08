<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

function exportHistoryToExcel(array $historicalData): void {
    // Check if the PhpSpreadsheet library is available
    if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
        // If not installed, tell the user
        echo json_encode(['error' => 'PhpSpreadsheet library is not installed. Run "composer require phpoffice/phpspreadsheet" to install it.']);
        exit;
    }

    // Create a new spreadsheet
    $spreadsheet = new Spreadsheet();
    
    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator('E-Service System')
        ->setLastModifiedBy('E-Service System')
        ->setTitle('Historique des affectations')
        ->setSubject('Historique des affectations de modules aux professeurs')
        ->setDescription('Export des affectations historiques par année académique')
        ->setKeywords('historique, affectations, modules, professeurs')
        ->setCategory('Education');
    
    // Create a worksheet for summary
    $summarySheet = $spreadsheet->getActiveSheet();
    $summarySheet->setTitle('Résumé');
    
    // Set the header style
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4361EE'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => '000000'],
            ],
        ],
    ];
    
    // Set the title style
    $titleStyle = [
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['rgb' => '4361EE'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];

    
    // Set the subtitle style
    $subtitleStyle = [
        'font' => [
            'bold' => true,
            'size' => 12,
            'color' => ['rgb' => '4361EE'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ];
    
    // Add title
    $summarySheet->setCellValue('A1', 'HISTORIQUE DES AFFECTATIONS');
    $summarySheet->mergeCells('A1:H1');
    $summarySheet->getStyle('A1')->applyFromArray($titleStyle);
    $summarySheet->getRowDimension(1)->setRowHeight(30);
    
    // Add subtitle with current date
    $summarySheet->setCellValue('A2', 'Export généré le ' . date('d/m/Y à H:i'));
    $summarySheet->mergeCells('A2:H2');
    $summarySheet->getStyle('A2')->applyFromArray($subtitleStyle);
    $summarySheet->getRowDimension(2)->setRowHeight(20);
    
    // Add empty row
    $summarySheet->getRowDimension(3)->setRowHeight(10);
    
    // Summary statistics
    $summarySheet->setCellValue('A4', 'STATISTIQUES GLOBALES');
    $summarySheet->mergeCells('A4:H4');
    $summarySheet->getStyle('A4')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 12,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'EEF2FF'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
    ]);
    $summarySheet->getRowDimension(4)->setRowHeight(25);
    
    // Calculate statistics
    $totalYears = count($historicalData);
    
    $uniqueProfessors = [];
    $totalModules = 0;
    $totalHours = 0;
    
    foreach ($historicalData as $yearData) {
        foreach ($yearData as $profId => $prof) {
            $uniqueProfessors[$profId] = true;
            $totalModules += count($prof['modules']);
            
            foreach ($prof['modules'] as $module) {
                $totalHours += $module['hours'] ?? 0;
            }
        }
    }
    
    $totalProfessors = count($uniqueProfessors);
    
    // Add statistics to summary sheet
    $summarySheet->setCellValue('B5', 'Nombre d\'années académiques:');
    $summarySheet->setCellValue('C5', $totalYears);
    $summarySheet->setCellValue('B6', 'Nombre total de professeurs:');
    $summarySheet->setCellValue('C6', $totalProfessors);
    $summarySheet->setCellValue('B7', 'Nombre total de modules:');
    $summarySheet->setCellValue('C7', $totalModules);
    $summarySheet->setCellValue('B8', 'Volume horaire total:');
    $summarySheet->setCellValue('C8', $totalHours . ' heures');
    
    // Style for stats
    $summarySheet->getStyle('B5:B8')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
    ]);
    
    $summarySheet->getStyle('C5:C8')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => '4361EE']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    ]);
    
    // Add summary table header at row 10
    $summarySheet->setCellValue('A10', 'Année Académique');
    $summarySheet->setCellValue('B10', 'Nombre de Professeurs');
    $summarySheet->setCellValue('C10', 'Nombre de Modules');
    $summarySheet->setCellValue('D10', 'Volume Horaire Total');
    
    $summarySheet->getStyle('A10:D10')->applyFromArray($headerStyle);
    $summarySheet->getRowDimension(10)->setRowHeight(20);
    
    // Add summary data
    $row = 11;
    foreach ($historicalData as $year => $professors) {
        $yearModules = 0;
        $yearHours = 0;
        
        foreach ($professors as $profId => $prof) {
            $yearModules += count($prof['modules']);
            
            foreach ($prof['modules'] as $module) {
                $yearHours += $module['hours'] ?? 0;
            }
        }
        
        $summarySheet->setCellValue('A' . $row, $year);
        $summarySheet->setCellValue('B' . $row, count($professors));
        $summarySheet->setCellValue('C' . $row, $yearModules);
        $summarySheet->setCellValue('D' . $row, $yearHours . ' heures');
        
        // Zebra striping for rows
        if ($row % 2 == 0) {
            $summarySheet->getStyle('A' . $row . ':D' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F2F2F2'],
                ],
            ]);
        }
        
        // Add border
        $summarySheet->getStyle('A' . $row . ':D' . $row)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
        
        $row++;
    }
    
    // Auto size columns for summary sheet
    foreach (range('A', 'D') as $col) {
        $summarySheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    // Create worksheets for each year
    foreach ($historicalData as $year => $professors) {
        // Create a new worksheet
        $yearSheet = $spreadsheet->createSheet();
        $yearSheet->setTitle($year);
        
        // Add title
        $yearSheet->setCellValue('A1', 'AFFECTATIONS ' . $year);
        $yearSheet->mergeCells('A1:K1');
        $yearSheet->getStyle('A1')->applyFromArray($titleStyle);
        $yearSheet->getRowDimension(1)->setRowHeight(30);
        
        // Add headers
        $yearSheet->setCellValue('A3', 'Professeur');
        $yearSheet->setCellValue('B3', 'Email');
        $yearSheet->setCellValue('C3', 'Filière');
        $yearSheet->setCellValue('D3', 'Module');
        $yearSheet->setCellValue('E3', 'Code Module');
        $yearSheet->setCellValue('F3', 'Semestre');
        $yearSheet->setCellValue('G3', 'Crédits');
        $yearSheet->setCellValue('H3', 'Cours (h)');
        $yearSheet->setCellValue('I3', 'TD (h)');
        $yearSheet->setCellValue('J3', 'TP (h)');
        $yearSheet->setCellValue('K3', 'Autre (h)');
        $yearSheet->setCellValue('L3', 'Total (h)');
        
        $yearSheet->getStyle('A3:L3')->applyFromArray($headerStyle);
        $yearSheet->getRowDimension(3)->setRowHeight(20);
        
        // Add data
        $row = 4;
        foreach ($professors as $profId => $professor) {
            $totalHours = 0;
            $moduleCount = count($professor['modules']);
            $firstRow = $row;
            
            foreach ($professor['modules'] as $moduleIndex => $module) {
                $totalHours += $module['hours'] ?? 0;
                
                
                $yearSheet->setCellValue('A' . $row, $professor['firstName'] . ' ' . $professor['lastName']);
                $yearSheet->setCellValue('B' . $row, $professor['email']);
                $yearSheet->setCellValue('C' . $row, $module['filiere'] ?? '');
                $yearSheet->setCellValue('D' . $row, $module['title'] ?? '');
                $yearSheet->setCellValue('E' . $row, $module['code_module'] ?? '');
                $yearSheet->setCellValue('F' . $row, $module['semester'] ?? '');
                $yearSheet->setCellValue('G' . $row, $module['credits'] ?? '');
                $yearSheet->setCellValue('H' . $row, $module['volume_cours'] ?? 0);
                $yearSheet->setCellValue('I' . $row, $module['volume_td'] ?? 0);
                $yearSheet->setCellValue('J' . $row, $module['volume_tp'] ?? 0);
                $yearSheet->setCellValue('K' . $row, $module['volume_autre'] ?? 0);
                $yearSheet->setCellValue('L' . $row, $module['hours'] ?? 0);
                
                // Add border
                $yearSheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                ]);
                
                $row++;
            }
            
            // If professor has multiple modules, merge cells for professor info
            if ($moduleCount > 1) {
                $yearSheet->mergeCells('A' . $firstRow . ':A' . ($row - 1));
                $yearSheet->mergeCells('B' . $firstRow . ':B' . ($row - 1));
                
                // Center the merged cells vertically
                $yearSheet->getStyle('A' . $firstRow . ':A' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $yearSheet->getStyle('B' . $firstRow . ':B' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }
            
            // Add professor separation with background
            $yearSheet->getStyle('A' . $firstRow . ':L' . ($row - 1))->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => ($firstRow % 4 < 2) ? 'FFFFFF' : 'F5F9FC'],
                ],
            ]);
        }
        
        // Auto size columns
        foreach (range('A', 'L') as $col) {
            $yearSheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set print area
        $yearSheet->getPageSetup()->setPrintArea('A1:L' . ($row - 1));
        $yearSheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $yearSheet->getPageSetup()->setFitToWidth(1);
        $yearSheet->getPageSetup()->setFitToHeight(0);
        
        // Set header and footer
        $yearSheet->getHeaderFooter()->setOddHeader('&C&B' . $year);
        $yearSheet->getHeaderFooter()->setOddFooter('&L&B' . $year . '&R&P / &N');
    }
    
    // Set summary sheet as active
    $spreadsheet->setActiveSheetIndex(0);
    
    // Set print area for summary sheet
    $lastRow = $summarySheet->getHighestRow();
    $summarySheet->getPageSetup()->setPrintArea('A1:D' . $lastRow);
    $summarySheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
    $summarySheet->getPageSetup()->setFitToWidth(1);
    $summarySheet->getPageSetup()->setFitToHeight(0);
    
    // Set header and footer for summary sheet
    $summarySheet->getHeaderFooter()->setOddHeader('&C&BHistorique des Affectations');
    $summarySheet->getHeaderFooter()->setOddFooter('&LExporté le ' . date('d/m/Y') . '&R&P / &N');
    
    // Create Excel writer and save to output
    $writer = new Xlsx($spreadsheet);
    
    // Set filename
    $filename = 'Historique_Affectations_' . date('Y-m-d_H-i-s') . '.xlsx';
    
    // Output to browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    // Save file
    $writer->save('php://output');
    exit;
}