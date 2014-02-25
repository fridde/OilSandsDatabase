<?php

if (isset($_REQUEST["discipline"])) {
    $discipline = $_REQUEST["discipline"];
}
else {
    $discipline = "";
}

$disciplines = array_unique(Helper::sql_select_columns(ORM::for_table("osdb_tags") -> where_like("Name", "@%") -> find_array(), "Name"));

echo '<p><form action="index.php?page=ranking" method="post">
<select name="discipline">';
echo '<option value="" selected >None</option>';
foreach ($disciplines as $disciplineValue) {
    echo '<option value="' . $disciplineValue . '">' . ltrim($disciplineValue, '@') . '</option>';
}
echo '</select>
<input type="submit" value="Change discipline">
</form></p>';

$possibleMainIdArray = Helper::sql_select_columns(ORM::for_table("osdb_ranking") -> distinct() -> select("Main_Id") -> find_array(), "Main_Id");

echo '<div id="tabs"> ';
$tabs = array();
foreach ($possibleMainIdArray as $mainId) {

    $mainIdName = ORM::for_table('osdb_compilations') -> find_one($mainId) -> Name;
    $thisTab = array(
        "Title" => Helper::shorten_names($mainIdName),
        "Content" => ""
    );
    $thisTab["Content"] .= '<h1> Based on ' . $mainIdName . '</h1>';
    if ($discipline != "") {
        $thisTab["Content"] .= '<h2>Subdiscipline: ' . ltrim($discipline, '@') . '</h2>';
    }

    $dayArray = Helper::sql_select_columns(ORM::for_table('osdb_ranking') -> distinct() -> order_by_desc('Day') -> select("Day") -> find_array(), "Day");

    $institutions = Helper::sql_select_columns(ORM::for_table("osdb_sources") -> select("Institution") -> distinct() -> find_array(), "Institution");
    $institution_win_freq = array();
    foreach ($institutions as $key => $value) {
        $institution_win_freq[$value] = array();
    }
    foreach ($dayArray as $day) {
        $csvFileName = Helper::shorten_names($mainIdName);
        if ($day > 30 && $day <= 800) {
            $timeText = "~ " . floor($day / 30) . " months";
            $csvFileName .= " - " . floor($day / 30) . "m";
        }
        elseif ($day > 800) {
            $timeText = "~ " . floor($day / 365) . " years";
            $csvFileName .= " - " . floor($day / 365) . "y";
        }
        else {
            $timeText = $day . " days";
            $csvFileName .= $day . "d";
        }

        $filteredRankingArray = ORM::for_table("osdb_ranking") -> where("Main_Id", $mainId) -> where("Day", $day) -> where_like("tags", "%" . $discipline . "%") -> find_array();

        if (count($filteredRankingArray) > 1) {
            $participating_compilations = array_merge(Helper::sql_select_columns($filteredRankingArray, "Compilation_1"), Helper::sql_select_columns($filteredRankingArray, "Compilation_2"));
            $participating_compilations_unique = array_unique($participating_compilations);
            $participationFreq = array_count_values($participating_compilations);
            $institutionArray = TimeSeriesArray::get_institutions($participating_compilations_unique);
            $winnerFreq = array_count_values(Helper::sql_select_columns($filteredRankingArray, "winner"));
            arsort($winnerFreq);
            // you can't win against yourself
            $possibleWins = count($participationFreq) - 1;
            foreach ($winnerFreq as $compId => $wins) {
                $winnerFreq[$compId] = $wins / $possibleWins;
                $institution_win_freq[$institutionArray[$compId]][] = $winnerFreq[$compId];
            }

            $comp_to_view = $winnerFreq;
            foreach ($participating_compilations_unique as $key => $id) {
                if (!isset($comp_to_view[$id])) {
                    $comp_to_view[$id] = 0;
                }
            }
            $graphLinkText = '<a class="tinyLink" target="_blank" href="index.php?page=graphs&compilationId[]=' . $mainId;
            $csvLinkText = '<a class="tinyLink" target="_blank" href="csv_creater.php?fileName=' . $csvFileName . '&compilationId[]=' . $mainId;
            foreach ($comp_to_view as $compilationId => $percentage) {
                $graphLinkText .= '&compilationId[]=' . $compilationId;
                $csvLinkText .= '&compilationId[]=' . $compilationId;
            }
            $graphLinkText .= '"> [Show in graph]</a>';
            $csvLinkText .= '"> [Download csv]</a>';

            $thisTab["Content"] .= '<table>
            <thead>
            <th>Category: ' . $timeText . $graphLinkText . $csvLinkText . '</th><th>Wins</th>
            </thead>
            <tbody>';

            foreach ($comp_to_view as $compilationId => $percentage) {
                $compilationIdName = ORM::for_table('osdb_compilations') -> find_one($compilationId) -> Name;
                $thisTab["Content"] .= "<tr><td>$compilationIdName</td><td>" . round($percentage * 100) . "% </td></tr>";
            }
            $thisTab["Content"] .= "
    </tbody>
    </table>";
        }
    }

    $inst_to_view = array();
    foreach ($institution_win_freq as $inst => $freq_array) {
        $length = count($freq_array);
        if ($length > 0) {
            $inst_to_view[$inst] = round((array_sum($freq_array) / count($freq_array)) * 100);
        }
        else {
            $inst_to_view[$inst] = FALSE;
        }
    }
    $inst_to_view = array_filter($inst_to_view, 'strlen');
    arsort($inst_to_view);

    $thisTab["Content"] .= '<h2>Total ranking within subcategory "' . ltrim($discipline, '@') . '" </h2>
    <table>
            <thead>
            <th>Institution</th><th>Average wins</th>
            </thead>
            <tbody>';
    foreach ($inst_to_view as $instName => $average) {
        $thisTab["Content"] .= '<tr><td>' . $instName . '</td><td>' . $average . '% </td></tr>';
    }
    $thisTab["Content"] .= "
    </tbody>
    </table>";
    // echop($inst_to_view);
    $tabs[$mainId] = $thisTab;
}
echo '<ul>';
foreach ($tabs as $mainId => $tab) {
    echo '<li><a href="#tabs_' . $mainId . '">' . $tab["Title"] . '</a></li>';
}
echo '<ul>';
foreach ($tabs as $mainId => $tab) {
    echo '<div id="tabs_' . $mainId . '">' . $tab["Content"] . '</div>';
}
echo '</div>';
?>