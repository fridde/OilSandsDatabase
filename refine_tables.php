<?php

if (isset($_REQUEST["password"]) && $_REQUEST["password"] == $ini_array["password"]) {
    $rightPassword = TRUE;
}
else {
    $rightPassword = FALSE;
}
$failedAttempt = FALSE;

switch ($_REQUEST["choice"]) {
    /*
     /* ###################################################
     /* Remove duplicates
     /* ###################################################
     */
    case 'Remove duplicates' :
        if ($rightPassword) {
            $deleted = Helper::sql_remove_duplicates($_REQUEST["Table"]);
            echo $deleted . " rows were deleted from " . $_REQUEST["Table"] . ".";
            break;
        }
        else {
            $failedAttempt = TRUE;
        }
    /*
     /* ###################################################
     /* Empty Table
     /* ###################################################
     */
    case 'Empty Table' :
        if ($rightPassword) {
            ORM::for_table($_REQUEST["Table"]) -> raw_execute("TRUNCATE TABLE " . $_REQUEST["Table"] . " ;");
            redirect("index.php?page=administration_form");
        }
        else {
            $failedAttempt = TRUE;
        }
        break;

    /*
     /* ###################################################
     /* Convert to barrels per day
     /* ###################################################
     */
    case "Convert to barrels per day" :
        foreach ($_REQUEST["checked_source"] as $sourceId) {
            Helper::sql_to_barrels_per_day($sourceId, 'osdb_data');
        }
        redirect("index.php?page=refine_tables_form");

        break;
    /*
     /* ###################################################
     /* Convert dates
     /* ###################################################
     */
    case "Convert dates" :
        foreach ($_REQUEST["checked_source"] as $sourceId) {
            Helper::sql_convert_dates($sourceId, 'osdb_data');
        }
        redirect("index.php?page=refine_tables_form");
        break;
    /*
     /* ###################################################
     /* Interpolate data
     /* ###################################################
     */
    case "Interpolate data" :
        foreach ($_REQUEST["checked_source"] as $sourceId) {
            Helper::interpolate_table($sourceId);
        }
        // redirect("index.php?page=working_tables_form");
        break;
    /*
     /* ###################################################
     /* Add synonyms
     /* ###################################################
     */
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
    /*
     /* ###################################################
     /* Combine
     /* ###################################################
     */
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
    /*
     /* ###################################################
     /* AddTag
     /* ###################################################
     */
    case "AddTag" :
        if (!isset($_REQUEST["tags"])) {
            $_REQUEST["tags"] = array();
        }
        Helper::add_tags($_REQUEST["compilationId"], $_REQUEST["tags"], $_REQUEST["newTags"]);
        redirect("index.php?page=compilations");

        break;
    /*
     /* ###################################################
     /* removeTag
     /* ###################################################
     */
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
    /*
     /* ###################################################
     /* Empty error table
     /* ###################################################
     */
    case 'Empty error table' :
        if ($rightPassword) {
            ORM::for_table('osdb_errors_to_calculate') -> raw_execute("TRUNCATE TABLE osdb_errors_to_calculate ;");
            ORM::for_table("osdb_ranking") -> raw_execute("TRUNCATE TABLE osdb_ranking;");
            ORM::for_table('osdb_errors') -> raw_execute("TRUNCATE TABLE osdb_errors ;");

            $stepLength = $ini_array["maxSteps"];
            Helper::establish_calculation_table("errors", $stepLength);
            redirect("index.php?page=administration_form");
        }
        else {
            $failedAttempt = TRUE;
        }
        break;

    /* ###################################################
     /* Calculate errors
     /* ###################################################
     */
    case "Prepare statistics calculations" :
    case "Calculate statistics" :
    case "Calculate errors" :
        /* this calculation is rather calculation intensive, so it has to be ensured that memory or time of the
         * server is not overused. This is done by two mechanisms
         * 1. If the time maxCalculationTime defined in config.ini is exceeded, the calculation is abandoned
         * 2. The calculation is done piecewise. First a queue of error-calculations is established,
         * saved in the table osdb_errors_to_calculate. Then, errors are calculated. Then, a queue of
         * statistics-calculations is established. After that, this queue is processed step for step */

        $startTime = microtime(TRUE);

        $errorsLeft = ORM::for_table("osdb_errors_to_calculate") -> where("type", "errors") -> count() > 0;
        $noStatistics = ORM::for_table("osdb_errors_to_calculate") -> where("type", "statistics") -> count() == 0;
        $noRankings = ORM::for_table("osdb_ranking") -> count() == 0;

        /* any calculation of ranking values that is done BEFORE all errors are calculated would yield false results  */

        /* this calculation goes rather quick */
        if ($errorsLeft) {
            $minId = ORM::for_table("osdb_errors_to_calculate") -> where("type", "errors") -> order_by_asc("id") -> find_one() -> id;
            $maxId = ORM::for_table("osdb_errors_to_calculate") -> where("type", "errors") -> order_by_desc("id") -> find_one() -> id;

            for ($i = $minId; $i <= $maxId; $i++) {
                $rows = ORM::for_table("osdb_errors_to_calculate") -> where("id", $i) -> where("type", "errors") -> find_result_set();
                if ($rows -> count() > 0) {
                    /* actually, there'll be one result at maximum, but the grammar
                     *  of the result set demands a foreach-loop */
                    foreach ($rows as $row) {
                        Helper::calculate_errors($row -> mainCompId, $row -> compId1, $row -> startDate, $row -> endDate);
                        $row -> delete();
                    }
                }
                if ((microtime(TRUE) - $startTime) > $ini_array["maxCalculationTime"]) {
                    echo ORM::for_table("osdb_errors_to_calculate") -> count() . " left to calculate.<br><br>";
                    redirect("index.php?page=administration_form");
                }
            }
        }
        /* if all errors are calculated, ranking statistics can be calculated */
        else {
            if ($noStatistics && $noRankings) {
                Helper::establish_calculation_table("statistics");
                redirect("index.php?page=administration_form");
            }

            $timeLeft = TRUE;

            /*this process is horribly slow*/
            while (!$noStatistics && $timeLeft ) {
                /* check if time has run out */
                if ((microtime(TRUE) - $startTime) > $ini_array["maxCalculationTime"]) {
                    $timeLeft = FALSE;
                }
                $id = ORM::for_table("osdb_errors_to_calculate") -> where("type", "statistics") -> order_by_asc("Day") -> find_one();
                if (count($id) > 0) {
                    $id = $id["id"];
                    $rows = ORM::for_table("osdb_errors_to_calculate") -> where("id", $id) -> find_result_set();
                    /* actually, there'll be only one result, but the grammar
                     *  of the result set demands a foreach-loop */
                    foreach ($rows as $row) {
                        Helper::calculate_ranking($row -> mainCompId, $row -> compId1, $row -> compId2, $row -> Day);
                        $row -> delete();
                    }
                }

            }
            echo ORM::for_table("osdb_errors_to_calculate") -> count() . " left to calculate.<br><br>";
            redirect("index.php?page=administration_form");
        }

        break;

    /* ###################################################
     /* Edit Buttons
     /* ###################################################
     */
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
    /*
     /* ###################################################
     /* Remove source
     /* ###################################################
     */
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
    /*
     /* ###################################################
     /* Remove compilation
     /* ###################################################
     */
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