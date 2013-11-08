<?php
$headersToExclude = array("id", "Source_Id", "Compilation_Id");

$sourceIdList = ORM::for_table("osdb_data") -> distinct() -> select('Source_Id') -> find_array();

echo '<form action="index.php?page=refine_tables" method="post">
<input type="submit" name="choice" value="Interpolate data">
</p>';
echo '<input type="checkbox" id="chkSelectDeselectAll" onClick="SelectDeselect()">(De-)Select all';
$i = 0;
$lastInstitution = "";
foreach ($sourceIdList as $SourceRow) {
    $source = ORM::for_table('osdb_sources') -> find_one($SourceRow["Source_Id"])->as_array();
        $i++;
    if($source["Institution"] != $lastInstitution){
        echo '<hr>';
        echo '<h2>' . $source["Institution"] . '</h2>';
    }
    $lastInstitution = $source["Institution"];
    
    echo '<table class="';
    if ($i % 2 == 0) {
        echo 'odd';
    } else {
        echo 'even';
    }
    echo '">';
    

    echo '<tr>
    <td><input type="checkbox" name="checked_source[]" value="' . $source["id"] . '" ';
    echo 'checked';
    echo '></td>';
       echo '<td><a href="index.php?page=sources&source=' . $source["id"] . '">' . $source["SourceName"] . '</a></td>';
    echo '<td>' . $source["PublicationDate"] . '</td>';
    echo '</tr><tr><td colspan="3"><hr></td></tr> <tr>';

    $rowsBelongingToSource = ORM::for_table("osdb_data") -> where("Source_Id", $source["id"]) -> order_by_asc('Date') -> find_array();

    $numberRows = count($rowsBelongingToSource);

    $headersToShow = array();
    foreach ($rowsBelongingToSource as $row) {
        $headersToShow = array_merge($headersToShow, array_keys(array_filter($row)));
        $headersToShow = array_unique($headersToShow);
    }
    
    foreach ($headersToShow as $header) {
        if (!(in_array($header, $headersToExclude))) {
            echo '<td>' . $header . '</td>';
        }
    }
    echo '</tr>';
    foreach ($rowsBelongingToSource as $rowKey => $row) {
        if ($rowKey < 3 || $rowKey > count($rowsBelongingToSource) - 4) {
            echo '<tr>';
            foreach ($headersToShow as $header) {
                if (!(in_array($header, $headersToExclude))) {
                    echo '<td>' . $row[$header] . '</td>';
                }
            }
            echo '</tr>';
        } elseif ($rowKey == 4) {
            echo '<tr><td><center>...</center></td></tr>';
        }
    }
    echo '<tr><td><strong>Rows</strong></td><td><strong>' . $numberRows . '</strong></td></tr>';
    echo '</table>';
}

echo "</form>";
?>
