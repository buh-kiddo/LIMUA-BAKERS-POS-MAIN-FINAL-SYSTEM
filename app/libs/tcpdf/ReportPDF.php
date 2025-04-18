<?php
require_once 'tcpdf.php';

class ReportPDF extends TCPDF {
    public function Header() {
        // Logo
        $this->Image('assets/images/logo.png', 10, 10, 30);
        // Title
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, 'LIMUA BAKERS', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(20);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C');
    }
}
