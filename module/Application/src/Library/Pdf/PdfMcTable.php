<?php
namespace Application\Library\Pdf;

use Complementos\Library\Exceptions\MyLogger;

class PdfMcTable extends \FPDF
{

    var $widths;

    var $aligns;

    var $rowHeight = 5;
    
    var $cellFonts;

    function SetWidths($w)
    {
        // Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        // Set the array of column alignments
        $this->aligns = $a;
    }
    
    function SetCellFonts($w)
    {
        $this->cellFonts = $w;
    }

    function SetRowHeight($height)
    {
        // Set the array of column alignments
        $this->rowHeight = $height;
    }

    //Se agregar factura para cambiar el color del borde dependiendo de la factura
    function Row($data, $alto, $borde, $banderaRelleno, $factura = null, $colores = null, $start = 15)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i ++) {
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        }
        $h = $alto * $nb;
        
        // Issue a page break first if needed
        $widths = $this->widths;
        $this->CheckPageBreak($h, $start);
        $this->widths = $widths;
        
        // Draw the cells of the row
        for ($i = 0; $i < count($data); $i ++) {
            
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            
            if(@$this->cellFonts) {
                $f = $this->cellFonts[$i];
                $this->SetFont($f[0], $f[1], $f[2]);
            }
            
            // Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            // Draw the border
            if ($borde == true) {
                //$this->SetDrawColor(3, 3, 3);
                switch ($factura){
                    case 1: 
                        $this->SetDrawColor($colores['r'], $colores['g'], $colores['b']);
                        break;
                    default:
                        $this->SetDrawColor(3, 3, 3);
                        break;
                }
                // $this -> SetDrawColor(255,255,255); //color del fondo
                // $this->SetLineWidth(0.2);
                // $this -> SetFillColor(220,220,220); //color del fondo
                if ($banderaRelleno == 0) {
                    // $this -> SetDrawColor(150,150,150);
                    $this->Rect($x, $y, $w, $h, 'D');
                } else {
                    $this->SetFillColor(180, 180, 180);
                    $this->Rect($x, $y, $w, $h, 'FD');
                }
            }
            
            
            // Print the text
            switch ($factura){
                case 1:
                    $this->MultiCell($w, 3, $data[$i], 0, $a);
                    break;
                default:
                    $this->MultiCell($w, $alto, $data[$i], 0, $a);
                    break;
            }
            
            // Put the position to the right of the cell
            //$this->SetXY($x + $w, $y);
            $this->SetXY($x + $w, $y);
            
        }
        // Go to the next line
        $this->Ln($h);
    }

    function bigRow($data, $alto, $borde, $banderaRelleno)
    {
        $texto = $data[0];
        while (strlen($texto) > 0) {
            $h = $alto * $this->NbLines($this->widths[0], $texto);
            $segments = array();
            $faltante = ($this->h - ($this->y + $this->bMargin));
            if ($h > $faltante) {
                $segments = $this->extractSegments($texto, $alto, $faltante);
                $this->Row(array(
                    $segments[0]
                ), $alto, $borde, $banderaRelleno);
                $texto = $segments[1];
                $this->AddPage($this->CurOrientation);
                $this->SetX(15);
            } else {
                $this->Row(array(
                    $texto
                ), $alto, $borde, $banderaRelleno);
                $texto = '';
            }
        }
    }

    function extractSegments($text, $rowHeight, $chunk)
    {
        $maxHeightSegment = $this->h - ($this->y + $this->bMargin);
        $cw = &$this->CurrentFont['cw'];
        $w = $this->widths[0];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        // $string = str_replace("\r",'',$text);
        $string = $text;
        $stringLength = strlen($string);
        if ($stringLength > 0 && $string[$stringLength - 1] == "\n") {
            $stringLength --;
        }
        $sep = - 1;
        $i = 0;
        $j = 0;
        $l = 0;
        $numberOfLines = 1;
        $segments = array();
        $segmented = false;
        while ($i < $stringLength) {
            $character = $string[$i];
            if ($character == "\n") {
                $i ++;
                $sep = - 1;
                $j = $i;
                $l = 0;
                $numberOfLines ++;
                if (($numberOfLines * $rowHeight) > $chunk) {
                    $segments[0] = substr($text, 0, $i - 2);
                    $segments[1] = substr($text, $i);
                    $segmented = true;
                    break;
                }
                continue;
            }
            if ($character == ' ') {
                $sep = $i;
            }
            $l += $cw[$character];
            if ($l > $wmax) {
                if ($sep == - 1) {
                    if ($i == $j) {
                        $i ++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = - 1;
                $j = $i;
                $l = 0;
                $numberOfLines ++;
                if (($numberOfLines * $rowHeight) > $chunk) {
                    $segments[0] = substr($text, 0, $i);
                    $segments[1] = substr($text, $i);
                    $segmented = true;
                    break;
                }
            } else {
                $i ++;
            }
        }
        if ($segmented = false) {
            $segments[0] = substr($text, 0);
            $segments[1] = '';
        }
        return $segments;
    }

    function RowSimple($data)
    {
        // Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i ++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $this->rowHeight * $nb;
        // Issue a page break first if needed
        $this->CheckPageBreak($h);
        // Draw the cells of the row
        for ($i = 0; $i < count($data); $i ++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            // Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            // Set font and color
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            // Draw the border
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(0.1);
            $this->Rect($x, $y, $w, $h, 'D');
            // Print the text
            $this->MultiCell($w, $this->rowHeight, $data[$i], 0, $a);
            // Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        // Go to the next line
        $this->Ln($h);
    }

    function HeaderSimple($data)
    {
        // Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i ++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = $this->rowHeight * $nb;
        // Issue a page break first if needed
        $this->CheckPageBreak($h);
        // Draw the cells of the row
        for ($i = 0; $i < count($data); $i ++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            // Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            // Set font and color
            $this->SetFont('Arial', 'B', 8);
            $this->SetTextColor(255, 255, 255);
            // Draw the border
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(0.1);
            $this->SetFillColor(180, 180, 180);
            $this->Rect($x, $y, $w, $h, 'D');
            // Print the text
            $this->MultiCell($w, $this->rowHeight, $data[$i], 1, $a, true);
            // Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        // Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h, $start = 15)
    {
        // If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
        $this->SetX($start);
    }

    function NbLines($w, $txt)
    {
        // Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $string = str_replace("\r", '', $txt);
        $stringLength = strlen($string);
        if ($stringLength > 0 && $string[$stringLength - 1] == "\n") {
            $stringLength --;
        }
        $sep = - 1;
        $i = 0;
        $j = 0;
        $l = 0;
        $numberOfLines = 1;
        while ($i < $stringLength) {
            $character = $string[$i];
            /* If a new line is found */
            if ($character == "\n") {
                $i ++;
                $sep = - 1;
                $j = $i;
                $l = 0;
                $numberOfLines ++;
                continue;
            }
            
            if ($character == ' ') {
                $sep = $i;
            }
            
            $l += $cw[$character];
            if ($l > $wmax) {
                if ($sep == - 1) {
                    if ($i == $j) {
                        $i ++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = - 1;
                $j = $i;
                $l = 0;
                $numberOfLines ++;
            } else {
                $i ++;
            }
        }
        return $numberOfLines;
    }
}
?>