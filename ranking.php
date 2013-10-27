<?php

echo '<form action="index.php?page=refine_tables" method="post">
<p><input type="submit" name="choice" value="Recalculate Ranking">
</form>';
$_REQUEST["Main_Id"] = "318";
$mainId = $_REQUEST["Main_Id"];
$mainIdName = ORM::for_table('osdb_compilations') -> find_one($mainId) -> Name;
echo "<h1>Based on $mainIdName</h1>";

$dayArray = ORM::for_table('osdb_ranking') -> distinct() ->order_by_desc('Day')-> select("Day") -> find_array();
 // echop($dayArray);
foreach ($dayArray as $day) {
    $day = $day["Day"];
    $timeText = $day . " days";
    if($day > 30) {
        $timeText = "above " . floor($day/30) . " months";
    }
    if($day > 700){
        $timeText = "above " . floor($day/365) . " years";
    }
    
    
    
    
    echo "
    <table>
    <thead>
    <th>Category:  $timeText</th><th>Better than</th>
    </thead>
    <tbody>
    ";
    $compilationIdArray = ORM::for_table('osdb_ranking') -> where("Main_Id", $mainId)->where("Day", $day) -> find_array();
    $compilationIdArray = Helper::create_tournament_ranking($compilationIdArray);
        foreach ($compilationIdArray as $compilationId=>$wins) {
                $compilationIdName = ORM::for_table('osdb_compilations') -> find_one($compilationId) -> Name;
            echo "<tr><td>$compilationIdName</td><td>$wins</td></tr>";
    }
    echo "
    </tbody>
    </table>";

}
?>
