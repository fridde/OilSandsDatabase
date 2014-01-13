<?php

if (isset($_REQUEST["password"]) && $_REQUEST["password"] == $ini_array["password"]) {
    $rightPassword = TRUE;
}
else {
    $rightPassword = FALSE;
}
$failedAttempt = FALSE;

switch ($_REQUEST["choice"]) {
    case 'Remove duplicates' :
        if ($rightPassword) {
            $deleted = Helper::sql_remove_duplicates($_REQUEST["Table"]);
            echo $deleted . " rows were deleted from " . $_REQUEST["Table"] . ".";
            break;
        }
        else {
            $failedAttempt = TRUE;
        }
    case 'Empty Table' :
        if ($rightPassword) {
            ORM::for_table($_REQUEST["Table"]) -> raw_execute("TRUNCATE TABLE " . $_REQUEST["Table"] . " ;");
            redirect("index.php?page=administration_form");
        }
        else {
            $failedAttempt = TRUE;
        }
        break;

    case 'Empty error table':
        if ($rightPassword) {
           ORM::for_table('osdb_errors_to_calculate') -> raw_execute("TRUNCATE TABLE osdb_errors_to_calculate ;");   
           $stepLength = $ini_array["maxSteps"];   
           Helper::establish_calculation_table($stepLength);     
           ORM::for_table('osdb_errors') -> raw_execute("TRUNCATE TABLE osdb_errors ;");
            redirect("index.php?page=administration_form");
        }
        else {
            $failedAttempt = TRUE;
        }
        break;
    
    case "Convert to barrels per day" :
        foreach ($_REQUEST["checked_source"] as $sourceId) {
            Helper::sql_to_barrels_per_day($sourceId, 'osdb_data');
        }
        redirect("index.php?page=refine_tables_form");

        break;

    case "Convert dates" :
        foreach ($_REQUEST["checked_source"] as $sourceId) {
            Helper::sql_convert_dates($sourceId, 'osdb_data');
        }
        redirect("index.php?page=refine_tables_form");
        break;

    case "Interpolate data" :
        foreach ($_REQUEST["checked_source"] as $sourceId) {
            Helper::interpolate_table($sourceId);
        }
        redirect("index.php?page=working_tables_form");
        break;

    case "Add synonyms" :
        if ($rightPassword) {
            foreach ($_REQUEST["synonym"] as $key => $synonym) {
                if (strlen($synonym) > 0) {
                    $newSynonym = ORM::for_table("osdb_synonyms") -> create();
                    $newSynonym -> Synonym = $synonym;
                    $newSynonym -> Replacement = $_REQUEST["replacement"][$key];
                    $newSynonym -> Type = $_REQUEST["synonym_type"];
                    $newSynonym -> save();
                }
            }
        }
        else {
            $failedAttempt = TRUE;
        }
        break;

    case "Combine" :
        if ($_REQUEST["method"] == "Calculate error statistics") {
            foreach ($_REQUEST["compilationId"] as $compilationId) {
                if (isset($_REQUEST["overwrite"])) {
                    ORM::for_table('osdb_errors') -> where_equal("Compilation_Id", $compilationId) -> delete_many();
                }
                $newTag = ORM::for_table('osdb_tags') -> create();
                $newTag -> Name = "analyzed";
                $newTag -> Compilation_Id = $compilationId;
                $newTag -> save();

            }
            $mainCompArray = Helper::sql_select_columns(ORM::for_table('osdb_tags') -> where("Name", "Basis") -> find_array(), "Compilation_Id");
            Helper::calculate_error_statistics($_REQUEST["compilationId"], $mainCompArray);
            redirect("index.php?page=ranking");
            break;
        }
        else {
            if (!(isset($_REQUEST["changeArray"]))) {
                $_REQUEST["changeArray"] = FALSE;
            }
            if (!isset($_REQUEST["onlyCommonDates"])) {$onlyCommonDates = FALSE;
            }
            else {$onlyCommonDates = TRUE;
            }
            Helper::combine_data($_REQUEST["compilationId"], $_REQUEST["method"], $_REQUEST["newName"], $_REQUEST["changeArray"], $onlyCommonDates);
            redirect("index.php?page=compilations");
            break;
        }

    case "AddTag" :
        if (!isset($_REQUEST["tags"])) {
            $_REQUEST["tags"] = array();
        }
        Helper::add_tags($_REQUEST["compilationId"], $_REQUEST["tags"], $_REQUEST["newTags"]);
        redirect("index.php?page=compilations");

        break;

    case "removeTag" :
        if ($rightPassword) {
            if (!isset($_REQUEST["tags"])) {
                $_REQUEST["tags"] = array();
            }

            Helper::remove_tags($_REQUEST["compilationId"], $_REQUEST["tags"]);
            redirect("index.php?page=compilations");
        }
        else {
            $failedAttempt = TRUE;
        }

        break;

    case "Calculate errors" :
            
            $start = microtime(TRUE);
            
            $minId = ORM::for_table("osdb_errors_to_calculate")->order_by_asc("id")->find_one()->id;
            $maxId = ORM::for_table("osdb_errors_to_calculate")->order_by_desc("id")->find_one()->id;
            
            for ($i=$minId; $i <= $maxId; $i++) {
                $rows = ORM::for_table("osdb_errors_to_calculate")->where("id", $i)->find_result_set();
                if($rows->count() > 0){
                    foreach($rows as $row){
                        Helper::calculate_errors($row->mainCompId, $row->compId, $row->startDate, $row->endDate);
                        $row->delete();   
                    }
                }
                if((microtime(TRUE) - $start) > $ini_array["maxCalculationTime"]){
                            echo ORM::for_table("osdb_errors_to_calculate")->count() . " left to calculate.<br><br>";
                        redirect("index.php?page=administration_form");
                    // break(2);
                }
            }
            
        
        break;
    
    case "Recalculate Ranking" :
        Helper::calculate_ranking();
        redirect("index.php?page=ranking");
        break;

    case "Edit Buttons" :
        if ($rightPassword) {
            $buttonArray = array_combine($_REQUEST["ButtonName"], $_REQUEST["ButtonDescription"]);
            foreach ($buttonArray as $buttonName => $buttonDescription) {
                if ($buttonDescription != "") {
                    $button = ORM::for_table("osdb_buttons") -> where("ButtonName", $buttonName) -> find_one();
                    $button -> Description = $buttonDescription;
                    $button -> save();
                }
            }
            redirect("index.php?page=edit_buttons");
        }
        else {
            $failedAttempt = TRUE;
        }

        break;

    case "Remove source" :
        if ($rightPassword) {
            if (isset($_REQUEST["archive"]) && $_REQUEST["archive"] == "archive") {
                $archive = TRUE;
            }
            else {
                $archive = FALSE;
            }
            Helper::remove_source_from_database($_REQUEST["Source"], $archive);
            redirect("index.php?page=administration_form");
        }
        else {
            $failedAttempt = TRUE;
        }

        break;

    case "Remove compilation" :
        if ($rightPassword) {

            Helper::remove_compilation_from_database($_REQUEST["Compilation"]);
            redirect("index.php?page=administration_form");

        }
        else {
            $failedAttempt = TRUE;
        }
        break;

    default :
        break;
}
// end of big switch

if ($failedAttempt) {
    echo "<h1> You tried to perform a critical action without a valid password. </h1>
     <p>Please contact the admin of this database to obtain one!</p>";
}
?>