<?php

include_once "include_all.php";

$sourcesToAdd = $_REQUEST["checked_source"];

// intialize the bigArray
$allOldHeaders = array();
foreach ($sourcesToAdd as $key => $sourceId) {
    $allOldHeaders = array_merge($allOldHeaders, $_REQUEST[$sourceId]);
}
$allOldHeaders = array_unique($allOldHeaders);
$allOldHeaders = array_merge($allOldHeaders, array("Source_Id", "Unit"));
$bigArray = array_fill_keys($allOldHeaders, array());
//---------------------------------------

// fill the bigArray
foreach ($sourcesToAdd as $key => $sourceId) {
    $source = ORM::for_table('osdb_Sources') -> find_one($sourceId);
    $sourceArray = Helper::csvstring_to_array($source -> SemiTidyData);
    $sourceHeader = $sourceArray[0];

    foreach ($sourceHeader as $headerKey => $colFirst) {
        //echo $headerKey . " - " . $_REQUEST[$sourceId][$headerKey] . "<br>";
        $column = Helper::get_array_column($sourceArray, $headerKey, $_REQUEST[$sourceId][$headerKey]);
        $columnHeader = trim($column["header"]);
        //echo $columnHeader . "<br>";
        $columnValues = $column["values"];
        $bigArray[$columnHeader] = array_merge($bigArray[$columnHeader], $columnValues);
    }

    //make sure every row is attributed to its source
    $col_rows = Helper::count_col_row($bigArray);
    $bigArray["Source_Id"] = array_pad($bigArray["Source_Id"], $col_rows["col"], $sourceId);
    $Unit = ORM::for_table('osdb_Sources') -> find_one($sourceId) -> Unit;
    $bigArray["Unit"] = array_pad($bigArray["Unit"], $col_rows["col"], $Unit);

    $bigArray = Helper::fill_array($bigArray);
}

// insert the huge table into the MySQL-table

Helper::sql_add_columns('osdb_Data' , array_keys($bigArray));

// add every row of bigArray to to mysql-table
$col_rows = Helper::count_col_row($bigArray);

$rowKeys = range(0, $col_rows["col"] - 1);
$currentSourceld = $bigArray["Source_Id"][0];
foreach ($rowKeys as $k => $rowNumber) {
    $tableRow = ORM::for_table('osdb_Data') -> create();

    foreach ($bigArray as $header => $values) {
        $tableRow -> $header = $values[$rowNumber];
    }
   
    $tableRow -> save();

    if ($currentSourceld != $tableRow -> Source_Id) {
    echo "
    <p>
    The source <strong>" . ORM::for_table('osdb_Sources') -> find_one($currentSourceld) -> SourceName . " </strong> has been transferred to the database.
    </p> ";
    $currentSourceld = $tableRow -> Source_Id;
    }
}
?>