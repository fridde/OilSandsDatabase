<?php

$filesToInclude = array(
    "helper_functions.php",
    "idiorm.php",
    "idiorm_conf.php",
);
foreach ($filesToInclude as $filename) {
    include_once "include/" . $filename;
}

$chosenCompilationIdArray = $_REQUEST["compilationId"];
$basisIdArray = Helper::sql_select_columns(ORM::for_table('osdb_tags') -> where('Name', 'Basis') -> find_array(), 'Compilation_Id');

if (!isset($_REQUEST["fileName"])) {
    $newFileName = FALSE;
}
else {
    $newFileName = $_REQUEST["fileName"] . '.csv';
}

$returnArray = array( array(
        "Compilation",
        "Date",
        "Value",
        "plotParameter",
        "Order"
    ));
$compilationCounter = 0;
foreach ($chosenCompilationIdArray as $key => $compilationId) {
    $compilationCounter++;
    $array = ORM::for_table("osdb_working") -> where("Compilation_Id", $compilationId) -> select_many("Date", "Value") -> order_by_asc('Date') -> find_array();
    $compilationName = Helper::shorten_names(ORM::for_table("osdb_compilations") -> find_one($compilationId) -> Name);
    /* the plot parameter is a parameter that can (but not must) be used to distinguish between different compilations.
     * For example, the R-script uses the parameter as the size of the plot, making every basis-tagged compilation double
     * the line size in comparison to other lines  */
    if (in_array($compilationId, $basisIdArray)) {
        $plotParameter = "2";
    }
    else {
        $plotParameter = "1";
    }
    if (!$newFileName && $compilationId == reset($chosenCompilationIdArray)) {
        $newFileName = $compilationName . '.csv';
    }
    $i = 0;
    if (!isset($_REQUEST["accuracy"])) {
        $accuracy = count($chosenCompilationIdArray) * ceil(count($array) / 10000);
    }
    else {
        $accuracy = $_REQUEST["accuracy"] * floor(count($array) / 500);
    }
    foreach ($array as $rowKey => $row) {
        if ($i % $accuracy == 0) {
            $returnArray[] = array(
                preg_replace("%[\r\n]%", "", $compilationName),
                $row["Date"],
                $row["Value"],
                $plotParameter,
                $compilationCounter
            );
        }
        $i++;
    }
}
if (!isset($newFileName)) {
    $newFileName = "export.csv";
}

Helper::array_to_csv_download($returnArray, $newFileName);
?>