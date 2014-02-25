<?php
$ini_array = parse_ini_file("config.ini");
$filesToInclude = array(
    "buttons.php",
    "helper_functions.php",
    "idiorm.php",
    "idiorm_conf.php",
    "Markdown.php",
    "simple_html_dom.php",
    "TimeSeriesArray.php",
    "Update.php"
);
foreach ($filesToInclude as $filename) {
    include_once "include/" . $filename;
}

$onlineScripts = array(
    "code.jquery.com/jquery-2.0.2.min.js",
    "maps.googleapis.com/maps/api/js?key=AIzaSyDieFUAMECA5JDJijt-mH7-H0gvW_z8Qt4&sensor=false"
);
$offlineScripts = array(
    "jquery.dataTables",
    "jquery.flot",
    "jquery-ui-1.10.3.min",
    "viz_v1",
    "jquery.flot.time.min",
    "jquery.flot.selection.min",
    "jquery.flot.stack",
    "base64",
    "preload",
    "readme"
);

foreach ($onlineScripts as $scriptName) {
    echo '<script src="http://' . $scriptName . '"></script>';
}
foreach ($offlineScripts as $scriptName) {
    echo '<script src="include/' . $scriptName . '.js"></script>';
}
?>
