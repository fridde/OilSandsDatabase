<?php

date_default_timezone_set("UTC"); 
$filesToInclude = array("helper_functions.php", "idiorm.php", "idiorm_conf.php");
foreach ($filesToInclude as $filename) {
    include_once $filename;
}

$lastEdit = ORM::for_table("osdb_logs") -> order_by_desc("Timestamp") -> find_one() -> as_array();
$oneHourAgo = date_create_from_format("U", time() - 60 * 60);
$recentTimeStamp = date_create_from_format("Y-m-d\TH:i:s", $lastEdit["Timestamp"]);

if ($recentTimeStamp > $oneHourAgo) {
    ORM::for_table("osdb_errors") -> raw_execute("TRUNCATE TABLE osdb_errors;");

    $compilationIdArray = Helper::sql_select_columns(ORM::for_table('osdb_tags') -> distinct() -> where("Name", "analyzed") -> find_array(), "Compilation_Id");
    $mainCompIdArray = Helper::sql_select_columns(ORM::for_table('osdb_tags') -> distinct() -> where("Name", "Basis") -> find_array(), "Compilation_Id");
    Helper::calculate_error_statistics($compilationIdArray, $mainCompIdArray);

    Helper::calculate_ranking();
    
    echo "Recalculated!";
}
?>