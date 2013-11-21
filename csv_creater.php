<?php

foreach (glob("include/*.php") as $filename) {
    include_once $filename;
}
$chosenCompilationIdArray = $_REQUEST["compilationId"];

if (!isset($_REQUEST["fileName"])) {
    $newFileName = FALSE;
} else {
    $newFileName = $_REQUEST["fileName"] . '.csv';
}
if(!isset($_REQUEST["accuracy"])){
    $accuracy = count($chosenCompilationIdArray);
} else {
    $accuracy = $_REQUEST["accuracy"];
}

$returnArray = array( array("Compilation", "Date", "Value"));
foreach ($chosenCompilationIdArray as $key => $compilationId) {
    $array = ORM::for_table("osdb_working") -> where("Compilation_Id", $compilationId) -> select_many("Date", "Value") -> order_by_asc('Date') -> find_array();
    $compilationName = Helper::shorten_names(ORM::for_table("osdb_compilations") -> find_one($compilationId) -> Name);
    if (!$newFileName && $compilationId == reset($chosenCompilationIdArray)) {
        $newFileName = $compilationName . '.csv';
    }
    $i = 0;
    foreach ($array as $rowKey => $row) {
        if ($i % $accuracy == 0) {
            $returnArray[] = array($compilationName, $row["Date"], $row["Value"]);
        }
        $i++;
    }
}
// echo $newFileName;
Helper::array_to_csv_download($returnArray, $newFileName);
?>