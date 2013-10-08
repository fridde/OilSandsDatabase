<?php

//include_once "include_all.php";
include_once "include/idiorm.php";
include_once "include/idiorm_conf.php";

$chosenCompilationIdArray = $_REQUEST["compilationId"];
$shortNameArray = $_REQUEST["shortNameId"];
$plotType = $_REQUEST["plotType"];
$returnArray = array("plotType"=>$plotType);

foreach ($chosenCompilationIdArray as $key=>$compilationId) {
    $array = ORM::for_table("osdb_working") -> where("Compilation_Id", $compilationId) 
    -> select_many("Date", "Value") ->order_by_asc('Date')-> find_array();
  
   $compilationName = $shortNameArray[$key];
  
    $currentArray = array();
    foreach ($array as $rowKey => $row) {
        
        $row["Date"] = strtotime($row["Date"]) * 1000;
        
        $row["Value"] = floatval($row["Value"]);
        $currentArray[] = array_values($row);

    }
    
    $returnArray[$compilationName] = $currentArray;
}
//echo print_r($returnArray) . "<br><br>";
header("Content-Type: application/json", true);
echo json_encode($returnArray);
?>