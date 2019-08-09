<?php

use inquid\pdf\FPDF;
use app\models\Reporte;

class PDF extends FPDF {

    function Header() {
        
        $this->EncabezadoDetalles();
        
        
    }

    function EncabezadoDetalles() {
        
        $this->Ln(5);
    }

    function Body($pdf,$model) {
        
        $pdf->SetXY(4, 10);
        $pdf->SetFont('Arial', '', 11);         
        $pdf->MultiCell(202,4.6,strip_tags(utf8_decode($model->mensaje)),0,'J');
        //$pdf->MultiCell(146, 4, utf8_decode(valorEnLetras($model->totalpagar)),0,'J');
    }

    function Footer() {

        $this->SetFont('Arial', '', 8);        
        $this->Text(170, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');
    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Times', '', 10);
$pdf->Output("Reporte$model->id_reporte.pdf", 'D');

exit;
