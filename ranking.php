<?php

$possibleMainIdArray = Helper::sql_select_columns(ORM::for_table("osdb_ranking") -> distinct() -> select("Main_Id") -> find_array(), "Main_Id");

// echo '<div class="boxed">
// <h3>Show</h3>';
// foreach ($possibleMainIdArray as $mainId) {
    // $mainIdName = ORM::for_table("osdb_compilations") -> find_one($mainId) -> Name;
    // echo '<input type="checkbox" class="tCheck" value="' . $mainId .  '" checked>' . $mainIdName . '<br>';
// }
// echo '</div>';
echo '<div class="accordion"> ';
foreach ($possibleMainIdArray as $mainId) {
        
    $recentDay = 1000000;
    $mainIdName = ORM::for_table('osdb_compilations') -> find_one($mainId) -> Name;
    
    echo '<h1 class="table_' . $mainId . '"> > Based on ' . $mainIdName . '</h1>';
    echo '<div class="table_' . $mainId . '">';
    
    $dayArray = Helper::sql_select_columns(ORM::for_table('osdb_ranking') -> distinct() -> order_by_desc('Day') -> select("Day") -> find_array(), "Day");
    // echop($dayArray);
    foreach ($dayArray as $day) {
        $csvFileName = Helper::shorten_names($mainIdName);
        if ($day > 30 && $day <= 700) {
            $timeText = "above " . floor($day / 30) . " months";
            $csvFileName .= " - " . floor($day / 30) . "m";
        }
        elseif ($day > 700) {
            $timeText = "above " . floor($day / 365) . " years";
            $csvFileName .= " - " . floor($day / 365) . "y";
        } else {
            $timeText = $day . " days";
            $csvFileName .= $day . "d";
        }
        // echo $day - $recentDay . "<br>";
        $compilationIdArray = ORM::for_table('osdb_ranking') -> where("Main_Id", $mainId) -> where("Day", $day) -> find_array();
        if (count($compilationIdArray) > 1 && ($day < 100 || ($recentDay - $day) > 400)) {
            $recentDay = $day;
            $compilationIdArray = Helper::create_tournament_ranking($compilationIdArray);
            $graphLinkText = '<a class="tinyLink" target="_blank" href="index.php?page=graphs&compilationId[]=' . $mainId;
            $csvLinkText = '<a class="tinyLink" target="_blank" href="csv_creater.php?fileName=' . $csvFileName  . '&compilationId[]=' . $mainId;
            foreach ($compilationIdArray as $compilationId => $wins) {
                $graphLinkText .= '&compilationId[]=' . $compilationId;
                $csvLinkText .= '&compilationId[]=' . $compilationId;
            }
            $graphLinkText .= '"> [Show in graph]</a>';
            $csvLinkText .= '"> [Download csv]</a>';

            echo '
    <table>
    <thead>
    <th>Category: ' .  $timeText . $graphLinkText . $csvLinkText . '</th><th>Better than</th>
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
