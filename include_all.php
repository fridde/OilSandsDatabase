<?php

foreach (glob("include/*.php") as $filename) {
    include $filename;
}

$onlineScripts = array("code.jquery.com/jquery-2.0.2.min");
$offlineScripts = array("jquery.dataTables" , "jquery.flot", 
"jquery.flot.time.min", "jquery.flot.selection.min","jquery.flot.stack", "base64", "canvas2image", "jquery.flot.saveAsImage",
  "preload");

foreach($onlineScripts as $scriptName){
    echo '<script src="http://' . $scriptName . '.js"></script>';
}
foreach($offlineScripts as $scriptName){
    echo '<script src="include/' . $scriptName . '.js"></script>';
}

?>
