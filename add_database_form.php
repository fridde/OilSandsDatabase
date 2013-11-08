<?php

$sourceList = ORM::for_table('osdb_Sources') -> where("Archived", 0) -> order_by_asc('Institution') -> find_array();
echo '<form action="index.php?page=add_database" method="post">
<p><input type="submit" name="action" value="Send data to database"></p>';
echo '<input type="checkbox" id="chkSelectDeselectAll" onClick="SelectDeselect()">(De-)Select all';
$lastInstitution = "";
foreach ($sourceList as $source) {
    if ($source["Institution"] != $lastInstitution) {
        echo '<hr>';
        echo '<h2>' . $source["Institution"] . '</h2>';
    }
    $lastInstitution = $source["Institution"];
    $sourceArray = Helper::csvstring_to_array($source["SemiTidyData"]);
    $header = $sourceArray[0];

    echo '<table><tr>
    <td colspan="' . count($header) . '">';
    echo '<input type="checkbox" name="checked_source[]" value="' . $source["id"] . '"';
    echo " checked ";
    echo "> Include</td></tr>";
    echo '<tr>
    <th>Source</th>
    <th colspan="' . count($header) . '">
    <a href="index.php?page=sources&source=' . $source["id"] . '">' . $source["SourceName"] . '</a></th>
    </tr>
    <tr>
    <td>Date of Publication</td> <td colspan="' . (count($header) - 1) . '">' . $source["PublicationDate"] . '</td>
    </tr><tr><td colspan="' . count($header) . '"><hr></td></tr>
    <tr>';
    foreach ($header as $col) {

        echo "<td>" . $col . "</td>";
    }
    echo "</tr>
    <tr>";
    $possibleHeaders = Helper::sql_get_columnNames('osdb_data');
    foreach ($header as $col) {
        echo '<td><select name="' . $source["id"] . '[]>';
        $mostSimilar = Helper::find_most_similar($col, $possibleHeaders);
        foreach ($possibleHeaders as $header) {
            echo '<option value="' . $header . '" ';
            if ($header == $mostSimilar) {
                echo "selected";
            }
            echo '>' . $header . '</option>';
        }
        echo '</select></td>';
    }
    echo "</tr>
        </table>";
}

echo "</form>";
?>