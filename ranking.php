<?php

$possibleMainIdArray = Helper::sql_select_columns(ORM::for_table("osdb_ranking") -> distinct() -> select("Main_Id") -> find_array(), "Main_Id");

echo '<div id="tabs"> ';
$tabs = array();
foreach ($possibleMainIdArray as $mainId) {
        
    $recentDay = 1000000;
    $mainIdName = ORM::for_table('osdb_compilations') -> find_one($mainId) -> Name;
    $thisTab = array("Title"=>Helper::shorten_names($mainIdName), "Content" => "");
    $thisTab["Content"] .= '<h1> Based on ' . $mainIdName . '</h1><br>';
    
    $dayArray = Helper::sql_select_columns(ORM::for_table('osdb_ranking') -> distinct() -> order_by_desc('Day') -> select("Day") -> find_array(), "Day");
    foreach ($dayArray as $day) {
        $csvFileName = Helper::shorten_names($mainIdName);
        if ($day > 30 && $day <= 800) {
            $timeText = "~ " . floor($day / 30) . " months";
            $csvFileName .= " - " . floor($day / 30) . "m";
        }
        elseif ($day > 800) {
            $timeText = "~ " . floor($day / 365) . " years";
            $csvFileName .= " - " . floor($day / 365) . "y";
        } else {
            $timeText = $day . " days";
            $csvFileName .= $day . "d";
        }
       
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

           $thisTab["Content"] .=  '<table>
    <thead>
    <th>Category: ' .  $timeText . $graphLinkText . $csvLinkText . '</th><th>Wins</th>
    </thead>
    <tbody>
    ';

            foreach ($compilationIdArray as $compilationId => $wins) {
                $compilationIdName = ORM::for_table('osdb_compilations') -> find_one($compilationId) -> Name;
                $thisTab["Content"] .= "<tr><td>$compilationIdName</td><td>$wins</td></tr>";
            }
            $thisTab["Content"] .= "
    </tbody>
    </table>";
        }
    }
    $tabs[$mainId] = $thisTab;
}
echo '<ul>';
foreach($tabs as $mainId=>$tab){
    echo '<li><a href="#tabs_' . $mainId . '">' . $tab["Title"] . '</a></li>';
}
echo '<ul>';
foreach($tabs as $mainId=>$tab){
    echo '<div id="tabs_' . $mainId . '">' . $tab["Content"] . '</div>';
}
echo '</div>';
?>
