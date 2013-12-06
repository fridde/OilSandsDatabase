<?php
include_once "include/idiorm.php";
include_once "include/idiorm_conf.php";
include_once "include/helper_functions.php";

if (!isset($_REQUEST["year"])) {
    $year = "2012";
}

$mapCompilationIdArray = Helper::sql_select_columns(ORM::for_table("osdb_tags") -> where("Name", "map") -> distinct() -> find_array(), "Compilation_Id");

$returnArray = array();
$continue = TRUE;
foreach ($mapCompilationIdArray as $compilationId) {

    $project = ORM::for_table("osdb_working") -> where("Compilation_Id", $compilationId) -> where_like('Date', '%' . $year . '%') -> find_array();
    $values = Helper::sql_select_columns($project, "Value");
    $value = array_sum($values) / count($values);

    $projectName = ORM::for_table("osdb_compilations") -> find_one($compilationId) -> Name;
    echop($projectName);
    echop($matchingProject = Helper::find_matching_project($projectName));

}
?>