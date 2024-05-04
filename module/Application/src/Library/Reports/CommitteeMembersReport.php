<?php
namespace Application\Library\Reports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CommitteeMembersReport
{
    
    /**
     * Generates binary for Excel printing.
     * @param array $params
     * @return string
     */
    public function generate($params)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Sheet configuration.
        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
        
        // Footer configuration.
        $sheet->getHeaderFooter()->setOddFooter('&L'.date('d/m/Y H:i').'&RPágina &P');
        $sheet->getHeaderFooter()->setEvenFooter('&L'.date('d/m/Y H:i').'&RPágina &P');
        
        // General styles.
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
        
        // Row dimensions.
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $row = 1;
        
        // Columns dimensions.
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(23);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(28);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(23);
        $sheet->getColumnDimension('I')->setWidth(23);
        $sheet->getColumnDimension('J')->setWidth(23);
        $sheet->getColumnDimension('K')->setWidth(18);
        $sheet->getColumnDimension('L')->setWidth(28);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(13);
        $sheet->getColumnDimension('O')->setWidth(40);
        
        // Title.
        $sheet->setCellValue('A'.$row, 'Reporte de integrantes por comité');
        $sheet->getStyle('A'.$row)->getFont()->setBold(true);
        $sheet->getStyle('A'.$row)->getFont()->setSize(20);
        $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('A'.$row.':O'.$row);
        
        // Filters
        
        $row += 3;
        $rowInitiFilters = $row;
        $sheet->setCellValue('G'.$row, 'FILTROS');
        $sheet->getStyle('G'.$row.':J'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$row.':J'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G'.$row.':J'.$row)->getFill()->getStartColor()->setARGB('FF999999');
        $sheet->mergeCells('G'.$row.':J'.$row);
        
        $row += 1;
        $sheet->setCellValue('G'.$row, 'Tipo de reporte:');
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
        $sheet->mergeCells('G'.$row.':H'.$row);
        $sheet->setCellValue('I'.$row, @$params['filters']['frmNameReportType']);
        $sheet->mergeCells('I'.$row.':J'.$row);
        
        $row += 1;
        $sheet->setCellValue('G'.$row, 'Proceso electoral:');
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
        $sheet->mergeCells('G'.$row.':H'.$row);
        $sheet->setCellValue('I'.$row, @trim($params['filters']['frmNameElectoralProcess']));
        $sheet->mergeCells('I'.$row.':J'.$row);
        
        $row += 1;
        $sheet->setCellValue('G'.$row, 'Estado:');
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
        $sheet->mergeCells('G'.$row.':H'.$row);
        $sheet->setCellValue('I'.$row, @$params['filters']['frmNameState']);
        $sheet->mergeCells('I'.$row.':J'.$row);
        
        if($params['filters']['frmIdReportType'] == 2) {
            $row += 1;
            $sheet->setCellValue('G'.$row, 'Usuario:');
            $sheet->getStyle('G'.$row.':H'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle('G'.$row.':H'.$row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
            $sheet->mergeCells('G'.$row.':H'.$row);
            $sheet->setCellValue('I'.$row, @$params['filters']['frmNameUser']);
            $sheet->mergeCells('I'.$row.':J'.$row);
        }
        
        $row += 1;
        $sheet->setCellValue('G'.$row, 'Asignación distrital:');
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
        $sheet->mergeCells('G'.$row.':H'.$row);
        $sheet->setCellValue('I'.$row, @$params['filters']['frmNameSectionalAssignment']);
        $sheet->mergeCells('I'.$row.':J'.$row);
        
        $row += 1;
        sort($params['filters']['frmLocalDistricts'], SORT_NUMERIC);
        sort($params['filters']['frmFederalDistricts'], SORT_NUMERIC);
        $sheet->setCellValue('G'.$row, 'Distritos seleccionados:');
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G'.$row.':H'.$row)->getFill()->getStartColor()->setARGB('FFDDDDDD');
        $sheet->mergeCells('G'.$row.':H'.$row);
        $sheet->setCellValue('I'.$row, @$params['filters']['frmIdSectionalAssignment'] == 2 ? implode(', ', $params['filters']['frmLocalDistricts']) : implode(', ', $params['filters']['frmFederalDistricts']));
        $sheet->mergeCells('I'.$row.':J'.$row);
        
        $sheet->getStyle('G'.$rowInitiFilters.':J'.$row)->applyFromArray([
            'alignment' => [
                'wrapText' => true,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);
        
        // Table styles.
        $pStyles = [
            'alignment' => [
                'wrapText' => true,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
        
        // Table header.
        $row += 3;
        $sheet->setCellValue('A'.$row, '#');
        $sheet->setCellValue('B'.$row, 'NOMBRE COMPLETO');
        $sheet->setCellValue('C'.$row, 'CLAVE DE ELECTOR');
        $sheet->setCellValue('D'.$row, 'SECCIÓN');
        $sheet->setCellValue('E'.$row, 'TELÉFONO');
        $sheet->setCellValue('F'.$row, 'CORREO');
        $sheet->setCellValue('G'.$row, $params['filters']['frmIdSectionalAssignment'] == 2 ? 'DIST. LOCAL' : 'DIST. FEDERAL');
        $sheet->setCellValue('H'.$row, 'ESTADO');
        $sheet->setCellValue('I'.$row, 'MUNICIPIO');
        $sheet->setCellValue('J'.$row, 'CIUDAD');
        $sheet->setCellValue('K'.$row, 'CÓDIGO POSTAL');
        $sheet->setCellValue('L'.$row, 'COLONIA');
        $sheet->setCellValue('M'.$row, 'COMITÉ COMPLETO');
        $sheet->setCellValue('N'.$row, 'COMITÉ');
        $sheet->setCellValue('O'.$row, 'REGISTRADO POR');
        $sheet->getStyle('A'.$row.':O'.$row)->applyFromArray($pStyles);
        $sheet->getStyle('A'.$row.':O'.$row)->getFont()->setBold(true);
        $sheet->getStyle('A'.$row.':O'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A'.$row.':O'.$row)->getFill()->getStartColor()->setARGB('FFCCCCCC');
        
        // Data table is printed.
        $row++;
        $i=$row;
        $fill = true;
        if($params['members']){
            $idx = 0;
            foreach($params['members'] as $r) {
                $sheet->setCellValue('A'.$row, ++$idx);
                $sheet->setCellValue('B'.$row, implode(' ', [$r['frmNameCitizen'], $r['frmLastNameCitizen'], $r['frmMaternalSurnameCitizen']]));
                $sheet->setCellValueExplicit('C'.$row, $r['frmVoterIdCitizen'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D'.$row, $r['frmSectionNameCitizen'], DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E'.$row, $r['frmCellPhoneCitizen'] ? : $r['frmPhoneCitizen'], DataType::TYPE_STRING);
                $sheet->setCellValue('F'.$row, $r['frmEmailAddressCitizen']);
                $sheet->setCellValueExplicit('G'.$row, $params['filters']['frmIdSectionalAssignment'] == 2 ? $r['frmLocalDistrictSection'] : $r['frmFederalDistrictSection'], DataType::TYPE_STRING);
                $sheet->setCellValue('H'.$row, $r['frmNameState']);
                $sheet->setCellValue('I'.$row, $r['frmNameMunicipality']);
                $sheet->setCellValue('J'.$row, $r['frmNameCity'] ? : $r['frmNameMunicipality']);
                $sheet->setCellValue('K'.$row, $r['frmNameZipCode']);
                $sheet->setCellValue('L'.$row, $r['frmNameNeighborhood']);
                $sheet->setCellValue('M'.$row, $r['frmCompleteCommittee'] == 't' ? 'SÍ' : 'NO');
                $sheet->setCellValue('N'.$row, str_pad($r['frmNameSection'], 4, '0', STR_PAD_LEFT));
                $sheet->setCellValue('O'.$row, implode(' ', [$r['frmUserNameCitizen'], $r['frmUserLastNameCitizen'], $r['frmUserMaternalSurnameCitizen']]));
                if($fill) {
                    $sheet->getStyle('A'.$row.':O'.$row)->getFill()->setFillType(Fill::FILL_SOLID);
                    $sheet->getStyle('A'.$row.':O'.$row)->getFill()->getStartColor()->setARGB('FFEDEDED');
                }
                $fill = !$fill;
                $row++;
            }
        } else {
            $sheet->setCellValue('A'.$row, 'No se encontraron datos');
            $sheet->mergeCells('A'.$row.':O'.$row);
        }
        $sheet->getStyle('A'.$i.':O'.($row-1))->applyFromArray($pStyles);
        
        // Excel is generated.
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
    
}