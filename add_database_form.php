<?php

$sourceList = ORM::for_table('osdb_Sources') -> order_by_asc('Institution') -> find_many();

//echo '<form action="action.php" method="post">' .
echo '<form action="index.php?page=add_database" method="post">
<p><input type="submit" name="action" value="Send data to database"></p>';

foreach ($sourceList as $source) {
    $sourceArray = Helper::csvstring_to_array($source -> SemiTidyData);
    $header = $sourceArray[0];

    echo '<table><tr>
    <td colspan="' . count($header) . '">';
    echo '<input type="checkbox" name="checked_source[]" value="' . $source -> id . '"';
    echo " checked ";
    echo "> Include</td></tr>";
    echo '<tr>
    <th>Source</th>
    <th colspan="' . count($header) . '">
    <a href="index.php?page=sources&source=' . $source -> id . '">' . $source -> SourceName . '</a></th>
    </tr>
    <tr>
    <td>Institution</td> <td colspan="' . (count($header) - 1) . '">' . $source -> Institution . '</td>
    </tr>
    <tr>
    <td>Date of Publication</td> <td colspan="' . (count($header) - 1) . '">' . $source -> PublicationDate . '</td>
    </tr><tr><td colspan="' . count($header) . '"><hr></td></tr>
    <tr>';
    foreach ($header as $col) {

        echo "<td>" . $col . "</td>";
    }
    echo "</tr>
    <tr>";
    foreach ($header as $col) {
        echo '<td><select name="' . $source -> id . '[]>';
        $possibleHeaders = ORM::for_table('osdb_Headers') -> select('Name') -> find_result_set();
        $possibleHeaders_array = Helper::result_column_to_array($possibleHeaders, "Name");
        $mostSimilar = Helper::find_most_similar($col, $possibleHeaders_array);
        foreach ($possibleHeaders as $header) {
            echo '<option value="' . $header -> Name . '" ';
            if ($header -> Name == $mostSimilar) {
                echo "selected";
            }
            echo '>' . $header -> Name . '</option>';
        }
        echo '</select></td>';
    }
    echo "</tr>
        </table><hr>";
}

echo "</form>";
?>