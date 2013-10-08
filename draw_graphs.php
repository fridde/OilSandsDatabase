<?php

include("include_all.php");

$pdf=new HTML2FPDF();
$pdf->AddPage();
$pdf->WriteHTML($_REQUEST["content"]);
$pdf->SetDisplayMode('fullpage');
$pdf->Output("temp/sample.pdf", 'F');

 ?>