<?php

if (isset($_REQUEST["discipline"])) {
    $discipline = $_REQUEST["discipline"];
}
else {
    $discipline = "";
}

$disciplines = array_unique(Helper::sql_select_columns(ORM::for_table("osdb_tags") -> where_like("Name", "%@%") -> find_array(), "Name"));
$disciplines = array_merge(array(""), $disciplines);

echo '<p><form action="index.php?page=ranking" method="post">
<select name="discipline">';
foreach ($disciplines as $disciplineValue) {
    echo '<option value="' . $disciplineValue . '" ';
    if ($disciplineValue == $discipline) {
        echo ' selected ';
    }
    echo '>';
    if ($disciplineValue == "") {
        echo "All";
    }
    else {
        echo ltrim($disciplineValue, '@');
    }
    echo '</option>';

}
echo '</select>
<input type="submit" value="Change discipline">
</form></p>';
/* Get the id's of all compilations containing reported values, tagged with "main" */
$possibleMainIdArray = Helper::sql_select_columns(ORM::for_table("osdb_ranking") -> distinct() -> select("Main_Id") -> find_array(), "Main_Id");

/* this array contains every time category. like 29 days, 2 months or 7 years. the time span is expressed in days */
$dayArray = Helper::sql_select_columns(ORM::for_table('osdb_ranking') -> distinct() -> order_by_desc('Day') -> select("Day") -> find_array(), "Day");

echo '<div id="tabs"> ';
/* we'll create a new tab for each main-compilation (the one with the reported values) and fill that tab with it's
 * associated content, $thisTab["Content"]. The actual display of the content happens later on */
$tabs = array();

$institution_translator_table = array_unique(Helper::sql_select_columns(ORM::for_table("osdb_tags") -> where("Name", "analyzed") -> find_array(), "Compilation_Id"));
$institution_translator_table = TimeSeriesArray::get_institutions($institution_translator_table);

foreach ($possibleMainIdArray as $mainId) {
    /* this is the array that contains all the values of "osdb_ranking" that match the mainId, have the right day,
     * and are tagged with the tagged given by the post-parameter "discipline" */
    $filteredRankingTable = ORM::for_table("osdb_ranking") -> where("Main_Id", $mainId) -> where_like("tags", "%" . $discipline . "%") -> find_array();
    $temporaryFilteredRankingTable = $filteredRankingTable;
    $filteredRankingTable = array();
    foreach ($temporaryFilteredRankingTable as $rowKey => $row) {
        $currentTags = explode(",", $row["tags"]);
        if (in_array($discipline, $currentTags) || $discipline == "") {
            $filteredRankingTable[$rowKey] = $row;
        }
    }

    $participatingInstitutions = array_merge(Helper::sql_select_columns($filteredRankingTable, "inst_1"), Helper::sql_select_columns($filteredRankingTable, "inst_2"));
    $participatingInstitutions = array_unique($participatingInstitutions);

    /* this array will be filled with scores of the institutions */
    $institution_scores = array();

    $mainIdName = ORM::for_table('osdb_compilations') -> find_one($mainId) -> Name;
    $thisTab = array(
        "Title" => Helper::shorten_names($mainIdName),
        "Content" => ""
    );
    $thisTab["Content"] .= '<h1> Based on ' . $mainIdName . '</h1>';
    if ($discipline != "") {
        $thisTab["Content"] .= '<h2>Subdiscipline: ' . ltrim($discipline, '@') . '</h2>';
    }

    foreach ($dayArray as $day) {

        $filteredRankingArray = Helper::filter_for_value($filteredRankingTable, "Day", $day);
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

        if (count($filteredRankingArray) > 1) {
            /* this algorithm calculates the probability of a certain prognosis to win against other prognoses within the same category and discipline */

            $participating_compilations = array_merge(Helper::sql_select_columns($filteredRankingArray, "Compilation_1"), Helper::sql_select_columns($filteredRankingArray, "Compilation_2"));
            $participating_compilations_unique = array_unique($participating_compilations);
            $participationFreq = array_count_values($participating_compilations);

            /* the institution array contains all compilations as keys and their corresponding institute as value */

            $winnerFreq = array_count_values(Helper::sql_select_columns($filteredRankingArray, "winner"));
            arsort($winnerFreq);
            // you can't win against yourself
            $possibleWins = count($participationFreq) - 1;
            foreach ($winnerFreq as $compId => $wins) {
                $winnerFreq[$compId] = $wins / $possibleWins;

                $currentInstitution = $institution_translator_table[$compId];
                if (isset($institution_scores[$currentInstitution])) {
                    $institution_scores[$currentInstitution][] = $winnerFreq[$compId];
                }
                else {
                    $institution_scores[$currentInstitution] = array($winnerFreq[$compId]);
                }

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

    foreach ($participatingInstitutions as $key => $institution) {
        if (isset($institution_scores[$institution])) {
            $freq_array = $institution_scores[$institution];
            $inst_to_view[$institution] = round((array_sum($freq_array) / count($freq_array)) * 100);
        }
        else {
            $inst_to_view[$institution] = 0;
        }
    }
    arsort($inst_to_view);

    $thisTab["Content"] .= '<h2>Total ranking';
    if ($discipline != "") {
        $thisTab["Content"] .= ' within subcategory "' . ltrim($discipline, '@') . '"';
    }
    $thisTab["Content"] .= ' </h2>
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