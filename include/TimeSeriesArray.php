<?php

class TimeSeriesArray {

    function __construct($array) {
        $this -> content = $array;
    }

    public function getContent() {
        return $this -> content;
    }

    public function filter_for_tag($tag) {
        $tagTable = ORM::for_table("osdb_tags") -> where("Name", $tag) -> find_array();
        $returnArray = array();

        foreach ($tagTable as $key => $row) {
            if (in_array($row["Compilation_Id"], $this -> content)) {
                $returnArray[] = $row["Compilation_Id"];
            }
        }
        return $returnArray;
    }

    public static function get_tags($idArray) {

        $tagTable = ORM::for_table("osdb_tags") -> find_array();
        $returnArray = array();

        foreach ($idArray as $idKey => $id) {
            $currentTags = array();
            foreach ($tagTable as $rowKey => $row) {
                if ($row["Compilation_Id"] == $id) {
                    $currentTags[] = $row["Name"];
                }
            }
            $returnArray[$id] = implode(",", $currentTags);
        }
        return $returnArray;
    }

    public function filter_for_institution($institution, $type = "id") {
        /* will filter a list and only return those matching with right institution.
         * If type is "id", the id of the institution is given
         * if type is "name", the actual name of the institution is given */
        if (count($this -> content) == 0) {
            return $this -> content;
        }

        //$compilationsTable

    }

    public static function get_institutions($array = array()) {

        if (count($array) === 0) { $array = $this -> content;
        }

        $sourceTable = ORM::for_table("osdb_sources") -> find_array();
        $sourceIdArray = new TimeSeriesArray($array);
        $sourceIdArray = $sourceIdArray -> get_sources();

        $returnArray = array();
        foreach ($sourceIdArray as $idKey => $sourceId) {
            foreach ($sourceTable as $key => $row) {
                if ($row["id"] == $sourceId) {
                    $returnArray[] = $row["Institution"];
                }
            }
        }
        $returnArray = array_combine($array, $returnArray);
        return $returnArray;
    }

    public function get_sources() {

        $compilationsTable = ORM::for_table("osdb_compilations") -> find_array();
        $idArray = $this -> content;

        $returnArray = array();

        foreach ($idArray as $idKey => $id) {
            foreach ($compilationsTable as $key => $row) {
                if ($row["id"] == $id) {
                    $returnArray[] = $row["Source_Id"];
                }
            }
        }
        return $returnArray;
    }

    /* ###################################################
     /* create_tournament_ranking
     /* ###################################################
     */
    public static function create_tournament_ranking($discipline) {

        $compilationIdArray = $this -> content;

        $returnArray = array();

        foreach ($compilationIdArray as $row) {
            $c1 = $row["Compilation_1"];
            $c2 = $row["Compilation_2"];

            $testThis = new TimeSeriesArray( array(
                $c1,
                $c2
            ));
            if (count($testThis -> filter_for_tag($discipline) == 2)) {

                if (!isset($returnArray[$c1])) {
                    $returnArray[$c1] = 0;
                }
                if (!isset($returnArray[$c2])) {
                    $returnArray[$c2] = 0;
                }
                if ($row["Mean_Differential"] < 0) {
                    $returnArray[$c1] = $returnArray[$c1] + 1;
                }
                else {
                    $returnArray[$c2] = $returnArray[$c2] + 1;
                }
            }
        }
        arsort($returnArray);
        return $returnArray;
    }

}
?>