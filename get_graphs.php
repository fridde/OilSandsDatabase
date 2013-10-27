<?php

//include_once "include_all.php";
include_once "include/idiorm.php";
include_once "include/idiorm_conf.php";

$chosenCompilationIdArray = $_REQUEST["compilationId"];
$shortNameArray = $_REQUEST["shortNameId"];
$plotType = $_REQUEST["plotType"];
$returnArray = array("plotType" => $plotType);
$dateOf1970 = date_create("1970-01-01");

$plotAccuracy = 1;
if (count($chosenCompilationIdArray) > 5) {
    $plotAccuracy = 7;
}
if (count($chosenCompilationIdArray) > 15) {
    $plotAccuracy = 30;
}

foreach ($chosenCompilationIdArray as $key => $compilationId) {

    $array = ORM::for_table("osdb_working") -> where("Compilation_Id", $compilationId) -> select_many("Date", "Value") -> order_by_asc('Date') -> find_array();

    $compilationName = $shortNameArray[$key];

    $currentArray = array();
    $i = 0;
    if (count($array) > 10000) {
        $plotAccuracy = 30;
    }
    foreach ($array as $rowKey => $row) {
        $i++;
        if ($i % $plotAccuracy == 0) {
            $today = date_create($row["Date"]);
            $interval = $dateOf1970->diff($today);

           $row["Date"] = $interval ->format('%a'); 
           $row["Date"] =$row["Date"] * 24 * 60 * 60 * 1000;
            $row["Value"] = floatval($row["Value"]);
            $currentArray[] = array_values($row);
        }
    }

    $returnArray[$compilationName] = $currentArray;

}
//echo print_r($returnArray) . "<br><br>";
header("Content-Type: application/json", true);
echo json_encode($returnArray);
?>