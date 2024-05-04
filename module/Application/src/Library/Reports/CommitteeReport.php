<?php
namespace Application\Library\Reports;

use Application\Library\Pdf\PdfMcTable;
use Application\Library\Storage\Storage;
use Application\Library\Storage\Path;

class CommitteeReport extends PdfMcTable
{
    
    
    public function Header()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(15, 5, utf8_decode('Estado '), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(145, 5, utf8_decode(mb_strtoupper($this->report->data['frmNameState'])), 1, 0 , 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 5, utf8_decode('Sección Electoral'), 0, 0, 'C');
        $this->Ln(7);
        $this->Cell(40, 5, utf8_decode('Municipio/Delegación'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(120, 5, utf8_decode(mb_strtoupper($this->report->data['frmNameMunicipality'])), 1, 0 , 'L');
        $this->Cell(5, 5, utf8_decode(''), 0, 0, 'C');
        $this->Cell(30, 5, utf8_decode(mb_strtoupper($this->report->data['frmNameSection'])), 1, 0, 'C');
        $this->Ln(2);
        
    }

    public function Footer()
    {
       
    }

    public function generate($params)
    {

        $this->storage = $params['sm']->get(Storage::class);
        $this->report = $params['report'];
        $date = strtotime($this->report->data['frmRegistrationDateCommittee']. ' '.$this->report->data['frmRegistrationTimeCommittee']);
        $this->DefPageSize = $this->_getpagesize('letter');
        $this->lMargin = 10;
        $this->rMargin = 10;
        $this->SetLineWidth(0.2);
        $widthContent = $this->w - ($this->lMargin + $this->rMargin) + 7; // Ancho del espacio para imprimir contenido en la hoja
        $heightLine = 5; // Alto por defecto de cada línea
        $font = 'Arial'; // Fuente por defecto
        $sizeFont = 10; // Tamaño de fuente por defecto
        
        $this->AddPage('P');
        $this->SetFont($font, '', $sizeFont);
        if(@$this->report->data['frmCommitteeMembers'])
        {
            $number = 0;
            foreach ($this->report->data['frmCommitteeMembers'] as $idx => $d)
            {
                if($d['frmIdJobPosition'] != 8)
                {
                    $this->SetDrawColor(116, 27, 71);
                    $this->SetLineWidth(0.5);
                    $this->MultiCell(0, 5, '', 'B');
                    $this->Ln(2);
                    $this->SetLineWidth(0.2);
                    //Integrantes
                    $this->SetFont($font, 'B', 12 );
                    $this->SetTextColor(255, 255, 255);
                    $this->SetFillColor(0, 0, 0);
                    $this->Cell(15, 10, utf8_decode(++$number), 0, 0, 'C', true);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont($font, '', 8 );
                    $this->SetFillColor(255, 255, 255);
                    $this->SetX(30);
                    $this->SetDrawColor(0, 0, 0);
                    $this->Cell(57, 5, utf8_decode('NOMBRE'), 0, 0, 'L');
                    $this->Cell(57, 5, utf8_decode('PATERNO'), 0, 0, 'L');
                    $this->Cell(60, 5, utf8_decode('MATERNO'), 0, 0, 'L');
                    $this->Ln(4);
                    $this->SetX(30);
                    $this->SetFont($font, '', $sizeFont);
                    $this->Cell(57, 6, utf8_decode($d['frmNameCitizen']), 'LTB', 0, 'L', true);
                    $this->Cell(57, 6, utf8_decode($d['frmLastNameCitizen']), 'TB', 0, 'L', true);
                    $this->Cell(62, 6, utf8_decode($d['frmMaternalSurnameCitizen']), 'TRB', 0, 'L', true);
                    $this->Ln($heightLine +2 );
                    $this->SetFont($font, '', 8 );
                    $this->SetX(10);
                    $this->Cell(60, 5, utf8_decode('DOMICILIO'), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('DELEGACIÓN O MUNICIPIO'), 0, 0, 'L');
                    $this->Cell(35, 5, utf8_decode('ENTIDAD'), 0, 0, 'L');
                    $this->Cell(15, 5, utf8_decode('C.P.'), 0, 0, 'L');
                    $this->Ln(4);
                    $this->SetX(10);
                    $this->SetFont($font, '', $sizeFont);
                    $this->Cell(60, 6, utf8_decode($d['frmStreetCitizen'].', '.$d["frmOutdoorNumberCitizen"].', '.($d["frmInteriorNumberCitizen"].', ' ?: '').$d['frmNameNeighborhood']), 'LTB', 0, 'L', true);
                    $this->Cell(50, 6, utf8_decode($d['frmNameMunicipality']), 'TB', 0, 'L', true);
                    $this->Cell(35, 6, utf8_decode($d['frmNameState']), 'TB', 0, 'L', true);
                    $this->Cell(15, 6, utf8_decode($d['frmNameZipCode']), 'TRB', 0, 'L', true);
                    $this->Cell(1, 5, utf8_decode(' '), 0, 0, 'L');
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->Rect($x, $y, 35, 27);
                    if($d['frmSignatureCitizen']) {
                        $signature = $this->storage->getImageAsBase64(Path::citizen($d['frmIdCitizen'], $d['frmSignatureCitizen']));
                        $extension = @pathinfo($d['frmSignatureCitizen'], PATHINFO_EXTENSION);
                        if($signature && $extension) {
                            try {
                                @$this->Image($signature, $x, $y+5, 35, 0, $extension);
                            } catch (\Exception $e) {}
                        }
                    }
                    $this->Ln($heightLine + 2);
                    $this->SetX(10);
                    $this->Cell(105, 5, utf8_decode('CLAVE ELECTOR'), 0, 0, 'L');
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('TELEFONO'), 0, 0, 'L');
                    $this->Ln($heightLine);
                    $this->SetX(10);
                    $this->Cell(105, 5, utf8_decode($d['frmVoterIdCitizen']), 'B', 0, 'L', true);
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode($d['frmPhoneCitizen']), 'B', 0, 'L', true);
                    $this->Ln($heightLine );
                    $this->SetX(10);
                    $this->Cell(30, 5, utf8_decode('FECHA'), 0, 0, 'L');
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(70, 5, utf8_decode('CORREO ELECTRONICO'), 0, 0, 'L');
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('CELULAR'), 0, 0, 'L');
                    $this->Ln($heightLine);
                    $this->SetX(10);
                    $this->Cell(30, 5, utf8_decode($d['frmDateOfBirthCitizen']), 'B', 0, 'L', true);
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(70, 5, utf8_decode($d['frmEmailAddressCitizen']), 'B', 0, 'L', true);
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode($d['frmCellPhoneCitizen']), 'B', 0, 'L', true);
                    $this->Cell(1, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(35, 5, utf8_decode('Firma'), 0, 0, 'C');
                    $this->Ln(3);
                }
            }
            $this->Ln($heightLine * 2);
            $this->AddPage('P');
            $this->Ln($heightLine * 2);
            foreach ($this->report->data['frmCommitteeMembers'] as $idx => $d)
            {
                if($d['frmIdJobPosition'] == 8)
                {
                    $this->SetDrawColor(116, 27, 71);
                    $y = $this->GetY();
                    $this->SetLineWidth(0.5);
                    $this->Rect(9, $y, $widthContent + 1, 50);
                    $this->Ln(3);
                    $this->SetLineWidth(0.2);
                    $this->SetDrawColor(0, 0, 0);
                    //Integrantes
                    $this->SetFont($font, 'B', 12 );
                    $this->SetFillColor(0, 0, 0);
                    $this->SetTextColor(255, 255, 255);
                    $this->Cell(35, 10, utf8_decode($d['frmTitleJobPosition']), 0, 0, 'C', true);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont($font, '', 8 );
                    $this->SetFillColor(255, 255, 255);
                    $this->SetX(50);
                    $this->Cell(50, 5, utf8_decode('NOMBRE'), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('PATERNO'), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('MATERNO'), 0, 0, 'L');
                    $this->Ln($heightLine);
                    $this->SetX(50);
                    $this->SetFont($font, '', $sizeFont);
                    $this->Cell(50, 6, utf8_decode($d['frmNameCitizen']), 'LTB', 0, 'L', true);
                    $this->Cell(50, 6, utf8_decode($d['frmLastNameCitizen']), 'TB', 0, 'L', true);
                    $this->Cell(56, 6, utf8_decode($d['frmMaternalSurnameCitizen']), 'TRB', 0, 'L', true);
                    $this->Ln($heightLine +2 );
                    $this->SetFont($font, '', 8 );
                    $this->SetX(10);
                    $this->Cell(60, 5, utf8_decode('DOMICILIO'), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('DELEGACIÓN O MUNICIPIO'), 0, 0, 'L');
                    $this->Cell(35, 5, utf8_decode('ENTIDAD'), 0, 0, 'L');
                    $this->Cell(15, 5, utf8_decode('C.P.'), 0, 0, 'L');
                    $this->Ln(4);
                    $this->SetX(10);
                    $this->SetFont($font, '', $sizeFont);
                    $this->Cell(60, 6, utf8_decode($d['frmStreetCitizen'].', '.$d["frmOutdoorNumberCitizen"].', '.($d["frmInteriorNumberCitizen"].', ' ?: '').$d['frmNameNeighborhood']), 'LTB', 0, 'L', true);
                    $this->Cell(50, 6, utf8_decode($d['frmNameMunicipality']), 'TB', 0, 'L', true);
                    $this->Cell(35, 6, utf8_decode($d['frmNameState']), 'TB', 0, 'L', true);
                    $this->Cell(15, 6, utf8_decode($d['frmNameZipCode']), 'TRB', 0, 'L', true);
                    $this->Cell(1, 5, utf8_decode(' '), 0, 0, 'L');
                    $x = $this->GetX();
                    $y = $this->GetY();
                    $this->Rect($x, $y, 35, 27);
                    if($d['frmSignatureCitizen']) {
                        $signature = $this->storage->getImageAsBase64(Path::citizen($d['frmIdCitizen'], $d['frmSignatureCitizen']));
                        $extension = @pathinfo($d['frmSignatureCitizen'], PATHINFO_EXTENSION);
                        if($signature && $extension) {
                            try {
                                @$this->Image($signature, $x, $y+5, 35, 0, $extension);
                            } catch (\Exception $e) {}
                        }
                    }
                    $this->Ln($heightLine + 2);
                    $this->SetX(10);
                    $this->Cell(105, 5, utf8_decode('CLAVE ELECTOR'), 0, 0, 'L');
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('TELEFONO'), 0, 0, 'L');
                    $this->Ln($heightLine);
                    $this->SetX(10);
                    $this->Cell(105, 5, utf8_decode($d['frmVoterIdCitizen']), 'B', 0, 'L', true);
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode($d['frmPhoneCitizen']), 'B', 0, 'L', true);
                    $this->Ln($heightLine );
                    $this->SetX(10);
                    $this->Cell(30, 5, utf8_decode('FECHA'), 0, 0, 'L');
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(70, 5, utf8_decode('CORREO ELECTRONICO'), 0, 0, 'L');
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode('CELULAR'), 0, 0, 'L');
                    $this->Ln($heightLine);
                    $this->SetX(10);
                    $this->Cell(30, 5, utf8_decode($d['frmDateOfBirthCitizen']), 'B', 0, 'L', true);
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(70, 5, utf8_decode($d['frmEmailAddressCitizen']), 'B', 0, 'L', true);
                    $this->Cell(5, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(50, 5, utf8_decode($d['frmCellPhoneCitizen']), 'B', 0, 'L', true);
                    $this->Cell(1, 5, utf8_decode(' '), 0, 0, 'L');
                    $this->Cell(35, 5, utf8_decode('Firma'), 0, 0, 'C');
                    $this->Ln($heightLine * 2);
                }
            }
        }
        $this->Ln($heightLine * 2);
        // Impresión de descripción final
        $this->SetFont($font, 'B', 12);
        $this->Cell(0, 5, utf8_decode('ACTA DE INSTALACIÓN'), 0, 0, 'C');
        $this->Ln($heightLine);
        $this->Cell(0, 5, utf8_decode('DEL COMITÉ DE PROTAGONISTAS DEL CAMBIO VERDADERO (anexo 1)'), 0, 0, 'C');
        $this->Ln($heightLine * 2);
        $this->SetFont($font, '', $sizeFont);
        $this->Cell(40, 5, utf8_decode('Siendo las __________ '), 0, 0, 'L');
        $this->SetX(27);
        $this->Cell(21, 5, utf8_decode(date('H:i', $date)), 0, 0, 'C');
        $this->Cell(40, 5, utf8_decode('horas del día _______ '), 0, 0, 'L');
        $this->SetX($this->GetX() - 19 );
        $this->Cell(16, 5, utf8_decode(date('d', $date)), 0, 0, 'C');
        setlocale(LC_ALL, "es_ES");
        $this->Cell(30, 5, utf8_decode('de _______________'), 0, 0, 'L');
        $this->SetX($this->GetX() - 25 );
        $this->Cell(30, 5, mb_strtoupper(utf8_decode(strftime('%B', $date))), 0, 0, 'C');
        $this->Cell(30, 5, utf8_decode('del año ________ '), 0, 0, 'L');
        $this->SetX($this->GetX() - 17 );
        $this->Cell(16, 5, utf8_decode(date('Y')), 0, 0, 'C');
        $this->Cell(0, 5, utf8_decode('en el Municipio o Delegación de'), 0, 0, 'L');
        $this->Ln($heightLine);
        $this->Cell(70, 5, utf8_decode('_________________________________'), 0, 0, 'L');
        $this->SetX($this->GetX() - 70 );
        $this->Cell(70, 5, utf8_decode(mb_strtoupper($this->report->data['frmNameMunicipality'])), 0, 0, 'C');
        $this->Cell(85, 5, utf8_decode('en el Estado de ____________________________'), 0, 0, 'L');
        $this->SetX($this->GetX() - 60 );
        $this->Cell(60, 5, utf8_decode(mb_strtoupper($this->report->data['frmNameState'])), 0, 0, 'C');
        $this->Ln($heightLine * 2);
        $this->MultiCell($widthContent, $heightLine, utf8_decode('De conformidad a lo establecido en los artículos 5, 6, 14, 14 bis, 15, 16 y 17 del Estatuto de Morena, los ciudadanos que se integran de manera libre y voluntaria a este Comité, asumen la responsabilidad y compromiso de participar activamente en la lucha por la transformación democrática y justa de nuestro país, convenciendo y concientizando a otros para que acepten sumarse a este esfuerzo colectivo.
            
Aceptamos como propios los principios y valores, programas y normas contenidos en el Plan de acción, la Declaración de Principios y Estatuto de Morena.'), 0);
        $this->Ln($heightLine * 2);
        $this->SetFont($font, 'I', $sizeFont);
        $this->Cell($widthContent, 5, utf8_decode('Firman en el anexo 2'), 0, 0, 'R');
        
        
        return $this->Output('D', 'CommitteeReport.pdf');
    }
    
}