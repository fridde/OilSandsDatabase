<?php

class Update {
    
    public static function update_ranking_table()
    {
       $rankingTable = ORM::for_table("osdb_ranking")->find_array();
       $compilationIds_1 = Helper::sql_select_columns($rankingTable, "Compilation_1");
       $compilationIds_2 = Helper::sql_select_columns($rankingTable, "Compilation_2");
       
       $compilationIds = array_unique(array_merge($compilationIds_1, $compilationIds_2));
          
       
       $tagArray = TimeSeriesArray::get_tags($compilationIds);
       $institutionArray = TimeSeriesArray::get_institutions($compilationIds);
       
       foreach($rankingTable as $rowKey => $row){
           
           $comp_1_tags = explode(",", $tagArray[$row["Compilation_1"]]);
           $comp_2_tags = explode(",", $tagArray[$row["Compilation_2"]]);
           $common_tags = array_intersect($comp_1_tags, $comp_2_tags);
           $rankingTable[$rowKey]["tags"] = implode(",", $common_tags);
           
           if($row["Mean_Differential"] < 0){
               $winner = $row["Compilation_1"];
           }
           else {
               $winner = $row["Compilation_2"];
           }
           $rankingTable[$rowKey]["winner"] = $winner;
           $rankingTable[$rowKey]["inst_1"] = $institutionArray[$row["Compilation_1"]];
           $rankingTable[$rowKey]["inst_2"] = $institutionArray[$row["Compilation_2"]];
           $rankingTable[$rowKey]["winner_inst"] = $institutionArray[$winner];
           
           
       }
       
       ORM::for_table("osdb_ranking") -> raw_execute("TRUNCATE TABLE osdb_ranking ;");
       Helper::sql_insert_array($rankingTable, "osdb_ranking");
    }
} 


?>