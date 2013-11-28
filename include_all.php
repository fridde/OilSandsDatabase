<?php

$filesToInclude = array(
    "buttons.php",
    "helper_functions.php",
    "idiorm.php",
    "idiorm_conf.php",
    "Markdown.php",
    "simple_html_dom.php"
);
foreach ($filesToInclude as $filename) {
    include_once "include/" . $filename;
}

$onlineScripts = array("code.jquery.com/jquery-2.0.2.min");
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
    echo '<script src="http://' . $scriptName . '.js"></script>';
}
foreach ($offlineScripts as $scriptName) {
    echo '<script src="include/' . $scriptName . '.js"></script>';
}
?>
