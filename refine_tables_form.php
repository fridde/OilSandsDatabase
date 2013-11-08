<?php

$sourceIdList = ORM::for_table('osdb_data') -> distinct() -> select('Source_Id') -> find_array();

echo '<form action="index.php?page=refine_tables" method="post">
<p><input type="submit" name="choice" value="Remove duplicates">
<input type="hidden" name="Table" value="osdb_data">
<input type="submit" name="choice" value="Convert to barrels per day">
<input type="submit" name="choice" value="Convert dates">
</p>';
echo '<input type="checkbox" id="chkSelectDeselectAll" onClick="SelectDeselect()">(De-)Select all';
$lastInstitution = "";
foreach ($sourceIdList as $SourceRow) {

    $source = ORM::for_table('osdb_sources') -> find_one($SourceRow["Source_Id"]) -> as_array();
    if ($source["Institution"] != $lastInstitution) {
        echo '<hr>';
        echo '<h2>' . $source["Institution"] . '</h2>';
    }
    $lastInstitution = $source["Institution"];

    echo "<table>";
    echo '<tr>
    <td><input type="checkbox" name="checked_source[]" value="' . $source["id"] . '" checked></td>';
    // echo '<td>' . $source["Institution"] . '</td>';
    echo '<td><a href="index.php?page=sources&source=' . $source["id"] . '">' . $source["SourceName"] . '</a></td>';
    echo '<td>' . $source["PublicationDate"] . '</td>';
    echo '</tr><tr><td colspan="4"><hr></td></tr> <tr>';

    $rowsBelongingToSource = ORM::for_table('osdb_data') -> where("Source_Id", $source["id"]) -> find_array();
    $numberRows = count($rowsBelongingToSource);

    $headersToShow = array();
    echo '<td><strong>Rows</strong></td>';
    foreach ($rowsBelongingToSource as $row) {
        $headersToShow = array_merge($headersToShow, array_keys(array_filter($row)));
        $headersToShow = array_unique($headersToShow);
    }
    $headersToExclude = array("id", "Source_Id");
    foreach ($headersToShow as $header) {
        if (!(in_array($header, $headersToExclude))) {
            echo '<td>' . $header . '</td>';
        }
    }

    echo '</tr><tr>';
    echo '<td><strong>' . $numberRows . '</strong></td>';
    foreach ($headersToShow as $header) {
        if (!(in_array($header, $headersToExclude))) {
            echo '<td>' . $rowsBelongingToSource[0][$header] . '</td>';
        }
    }

    echo '</tr>';
    echo '</table>';
}

echo "</form>";
?>