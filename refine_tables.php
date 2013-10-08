<?php

switch ($_REQUEST["choice"]) {
    case 'Remove duplicates' :
        $deleted = Helper::sql_remove_duplicates($_REQUEST["Table"]);
        echo $deleted . " rows were deleted from " . $_REQUEST["Table"] . ".";
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
         redirect("index.php?page=working_tables_form&table_type=working");
        break;

    case "Add synonyms" :
        foreach ($_REQUEST["synonym"] as $key => $synonym) {
            if (strlen($synonym) > 0) {
                $newSynonym = ORM::for_table("osdb_synonyms") -> create();
                $newSynonym -> Synonym = $synonym;
                $newSynonym -> Replacement = $_REQUEST["replacement"][$key];
                $newSynonym -> Type = $_REQUEST["synonym_type"];
                $newSynonym -> save();
            }
        }
        break;
    case "Combine" :
        //echo print_r($_REQUEST["compilationId"]);
        Helper::combine_data($_REQUEST["compilationId"], $_REQUEST["method"], $_REQUEST["newName"], $_REQUEST["changeArray"]);
        //redirect("index.php?page=compilations");
        break;

    case "AddTag" :
        if (!isset($_REQUEST["tags"])) {
            $_REQUEST["tags"] = array();
        }
        
        Helper::add_tags($_REQUEST["compilationId"], $_REQUEST["tags"], $_REQUEST["newTags"]);
        redirect("index.php?page=compilations");
        break;

    default :
        break;
}
?>