<?php

$possibleMainIdArray = Helper::sql_select_columns(ORM::for_table("osdb_ranking") -> distinct() -> select("Main_Id") -> find_array(), "Main_Id");

echo '<form action="index.php?page=refine_tables" method="post">
<p><input type="submit" name="choice" value="Recalculate Ranking"><input type="submit" name="choice" value="Recalculate errors">
</form>';
// echo '<form action="index.php?page=ranking">
// <p>Change the reported actual production the prognoses are compared to
// <select name="Main_Id">';
// foreach ($possibleMainIdArray as $mainId) {
// $mainIdName = ORM::for_table("osdb_compilations") -> find_one($mainId) -> Name;
// echo '<option value="' . $mainId . '">' . $mainIdName . '</option>';
// }
// echo '</select></form></p>';

foreach ($possibleMainIdArray as $mainId) {
    $mainIdName = ORM::for_table("osdb_compilations") -> find_one($mainId) -> Name;
    echo '<input type="checkbox" class="tCheck" value="' . $mainId .  '" checked>' . $mainIdName . '<br>';
}
echo '<div class="accordion"> ';
foreach ($possibleMainIdArray as $mainId) {
        
    $recentDay = 1000000;
    $mainIdName = ORM::for_table('osdb_compilations') -> find_one($mainId) -> Name;
    // echo '<div class="accordion" id="table_' . $mainId . '">' ;
    
    echo "<h1>Based on $mainIdName</h1>";
    echo '<div>';

    $dayArray = Helper::sql_select_columns(ORM::for_table('osdb_ranking') -> distinct() -> order_by_desc('Day') -> select("Day") -> find_array(), "Day");
    // echop($dayArray);
    foreach ($dayArray as $day) {
        $timeText = $day . " days";
        if ($day > 30) {
            $timeText = "above " . floor($day / 30) . " months ";
        }
        if ($day > 700) {
            $timeText = "above " . floor($day / 365) . " years ";
        }
        // echo $day - $recentDay . "<br>";
        $compilationIdArray = ORM::for_table('osdb_ranking') -> where("Main_Id", $mainId) -> where("Day", $day) -> find_array();
        if (count($compilationIdArray) > 1 && ($day < 100 || ($recentDay - $day) > 400)) {
            $recentDay = $day;
            $compilationIdArray = Helper::create_tournament_ranking($compilationIdArray);
            $linkText = '<a class="tinyLink" href="index.php?page=graphs&compilationId[]=' . $mainId;
            foreach ($compilationIdArray as $compilationId => $wins) {
                $linkText .= '&compilationId[]=' . $compilationId;
            }
            $linkText .= '">[Show in graph]</a>';

            echo '
    <table>
    <thead>
    <th>Category: ' .  $timeText . $linkText . '</th><th>Better than</th>
    </thead>
    <tbody>
    ';

            foreach ($compilationIdArray as $compilationId => $wins) {
                $compilationIdName = ORM::for_table('osdb_compilations') -> find_one($compilationId) -> Name;
                echo "<tr><td>$compilationIdName</td><td>$wins</td></tr>";
            }
            echo "
    </tbody>
    </table>";
        }
    }
    echo '</div>';
}
echo '<div>';
?>
