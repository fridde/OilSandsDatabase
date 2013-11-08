<?php

include_once "include_all.php";

$sourcesToAdd = $_REQUEST["checked_source"];

// intialize the bigArray
$allOldHeaders = array();

foreach ($sourcesToAdd as $key => $sourceId) {
    $allOldHeaders = array_merge($allOldHeaders, $_REQUEST[$sourceId]);
}
$allOldHeaders = array_unique($allOldHeaders);
$bigArray = array_fill_keys($allOldHeaders, array());

//---------------------------------------

// fill the bigArray
foreach ($sourcesToAdd as $key => $sourceId) {
    $source = ORM::for_table('osdb_Sources') -> find_one($sourceId);
    $sourceArray = Helper::csvstring_to_array($source -> SemiTidyData);
    $sourceHeader = $sourceArray[0];

    foreach ($sourceHeader as $headerKey => $colFirst) {

        $column = Helper::get_array_column($sourceArray, $headerKey, $_REQUEST[$sourceId][$headerKey]);
        $columnHeader = trim($column["header"]);
        $columnValues = $column["values"];
        $bigArray[$columnHeader] = array_merge($bigArray[$columnHeader], $columnValues);
    }
   
    /* Now it's time to turn the array around so that it's prepared for the SQL insertion */

    $queryArray = array();
    $headers = array_keys($bigArray);
    
    for ($i = 0; $i < count(reset($bigArray)); $i++) {
        foreach ($headers as $header) {
            if(isset($bigArray[$header][$i])){
                $queryArray[$i][$header] = $bigArray[$header][$i];
            }
            else{
                    $queryArray[$i][$header] = NULL;
            }
            
        }
    }
    $standardUnit = ORM::for_table('osdb_Sources') -> find_one($sourceId) -> Unit;
    foreach ($queryArray as $rowKey => $row) {
        if (!isset($row["Unit"]) || trim($row["Unit"]) == "") {
            $queryArray[$rowKey]["Unit"] = $standardUnit;
        }
        $queryArray[$rowKey]["Source_Id"] = $sourceId;
    }
  
   Helper::sql_insert_array($queryArray, "osdb_data");
    echo '<p>The source <strong>' . ORM::for_table('osdb_sources') -> find_one($sourceId) -> SourceName . '</strong> has been transferred to the database.</p>';
}
?>