<?php
include_once 'include_all.php';

date_default_timezone_set("UTC");
$lastUpdate = ORM::for_table("osdb_logs") -> order_by_desc('Timestamp') -> find_one();
$lastUpdateTime = strtotime($lastUpdate -> Timestamp);
$now = time();
$olderThan = $now - $lastUpdateTime > 60 * 60 * 24 * 2;
// $errorsToCalculateLeft = ORM::for_table("osdb_errors_to_calculate")->count() > 0 ;

if (!$olderThan) {

    // if ($lastUpdate -> Table != "osdb_ranking") {
        Update::update_ranking_table();
    // }
    redirect("index.php?page=refine_tables&choice=Calculate errors");
}
else {
    echo "<br>Nothing new! No update executed.<br>";
}
?>