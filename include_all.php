<?php

foreach (glob("include/*.php") as $filename) {
    include_once $filename;
}
include_once("include/html2fpdf/html2fpdf.php");
$onlineScripts = array("code.jquery.com/jquery-2.0.2.min");
$offlineScripts = array("jquery.dataTables" , "jquery.flot", "jquery-ui-1.10.3.min", "viz_v1",
"jquery.flot.time.min", "jquery.flot.selection.min","jquery.flot.stack", "base64", "canvas2image", "jquery.flot.saveAsImage",
  "preload", "readme");

foreach($onlineScripts as $scriptName){
    echo '<script src="http://' . $scriptName . '.js"></script>';
}
foreach($offlineScripts as $scriptName){
    echo '<script src="include/' . $scriptName . '.js"></script>';
}

?>
