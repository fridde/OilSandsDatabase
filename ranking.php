<?php

echo '<form action="index.php?page=refine_tables" method="post">
<p><input type="submit" name="choice" value="Recalculate Ranking">
</form>';

// $mainIdArray = ORM::for_table('osdb_errors')->distinct()->select("Main_Id")->find_array();
// echop($mainIdArray);
// foreach($mainIdArray as $mainId){
    // $mainId = $mainId["Main_Id"];
    // $mainIdName = ORM::for_table('osdb_compilations')->find_one($mainId)->Name;
    // // echo $mainIdName;
    // echo "<table><thead><th>$mainIdName</th></thead><tbody>";
    // $compilationIdArray = ORM::for_table('osdb_errors')->where("Main_Id", $mainId)->distinct()->select("Compilation_Id")->find_array();
    // foreach($compilationIdArray as $compilationId){
        // $compilationId = $compilationId["Compilation_Id"];
//         
    // }
    // echo "</tbody></table>";
//     
// }


?>

<!-- <table><thead><th>$mainIdName</th></thead></table> -->