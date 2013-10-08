<?php

$pdf=new HTML2FPDF();
$pdf->AddPage();
$pdf->SetDisplayMode('fullpage');
$pdf->WriteHTML('<p>This is your first PDF File</p>');
$pdf->SetDisplayMode('fullpage');
$pdf->Output("sample.pdf", 'D');

?>
