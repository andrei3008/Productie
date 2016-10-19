<?php

require_once('classes/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'S.C. AMPERA GAMES S.R.L.');
$pdf->Cell(40,10,'Str. George Enescu, Nr. 82 Craiova, Dolj');
$pdf->Cell();
$pdf->Output();