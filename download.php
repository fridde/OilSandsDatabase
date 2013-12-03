<?php

$filesToInclude = array(
    "helper_functions.php",
);
foreach ($filesToInclude as $filename) {
    include_once "include/" . $filename;
}

if (!isset($_REQUEST["fileName"])) {
    echo "<h1>Your download could not be found!</h1>
    <p>Probably the download link is broken. Please contact the site admin.</p>";
    
}
else {
    $fileName = $_REQUEST["fileName"];
    if(isset($_REQUEST["as"])){
        $wantedFilename = $_REQUEST["as"];
    } 
    else {
        $wantedFilename = $_REQUEST["fileName"];
    }
    Helper::create_download("downloads/" . $_REQUEST["fileName"], $wantedFilename);
}




?>