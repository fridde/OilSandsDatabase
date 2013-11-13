<?php

if (isset($_REQUEST["tag"])) {
    $tagsToShow = array_unique($_REQUEST["tag"]);
} else {
    $tagsToShow = array();
}

$compilationList = ORM::for_table('osdb_compilations') ->order_by_asc("Name")-> find_array();
$compilationNames = Helper::sql_select_columns($compilationList, "Name");
$compilationShortNames = Helper::shorten_names($compilationNames);
$workingHeaders = Helper::sql_get_columnNames("osdb_working");
$ignoreArray = array("id", "Compilation_Id", "Source_Id", "Date", "Value");
$includeArray = array("Name", "Product", "Time_Accuracy");
$tags = ORM::for_table('osdb_tags') -> find_array();

$maxTags = array_count_values(array_filter(Helper::sql_select_columns($tags, "Compilation_Id")));
arsort($maxTags);
$maxTags = reset($maxTags);
$tagNames = array_keys(array_count_values(Helper::sql_select_columns($tags, "Name")));

echo '<form action="index.php?page=graphs" method="post">
<p>
<input type="button" value="Show graphs" onClick="this.form.action=\'index.php?page=graphs\'; this.form.submit()">
<input type="radio" name="plotType" value="lines" id="lines" checked>
<label for="lines">Lines</label>
<input type="radio" name="plotType" value="stacked" id="stacked">
<label for="stacked">Stacked</label>
</p>

<p>
<input type="button" value="Combine compilations" onClick="this.form.action=\'index.php?page=refine_tables&choice=Combine\';this.form.submit()">
<input type="radio" name="method" value="Add" id="Add" checked>
<label for="Add">Add</label>
<input type="radio" name="method" value="Subtract" class="showbutton" id="Subtract">
<label for="Subtract">Subtract</label>
<input type="radio" name="method" value="Concatenate" id="Concatenate">
<label for="Concatenate">Concatenate</label>
<input type="radio" id="showbutton" class="showbutton" name="method" value="Calculate error statistics">
<label for="showbutton">Calculate error statistics</label><br>
<input type="checkbox" id="onlyCommonDates" name="onlyCommonDates" value="TRUE">
<label for="onlyCommonDates">Only combine common dates</label>
<input type="checkbox" id="overwrite" name="overwrite" value="TRUE">
<label for="overwrite">Overwrite error statistics</label><br>
</p>
<table>
<tr><th colspan="2">Values for the new Compilation</th></tr>';
echo '<tr><td>Name</td><td><input type="text" name="newName" required placeholder="Enter new name"></td> ';
foreach ($workingHeaders as $header) {
    if (in_array($header, $ignoreArray) || !in_array($header, $includeArray)) {
        echo '<input name="changeArray[]" value="" hidden>';
    } else {
        echo '<tr><td>' . $header . '</td><td><input type="text" name="changeArray[]"></td></tr>';
    }
}
echo '</table>
<p>
<h2>Add tags</h2>
<input type="button" value="Add Tags" onClick="this.form.action=\'index.php?page=refine_tables&choice=AddTag\';this.form.submit()">';
echo '<input type="text" name="newTags" placeholder="Enter new tags here">';
echo '<table><tr>';
$i = 0;
foreach ($tagNames as $tag) {
    $i++;
    echo '<td><input type="checkbox" name="tags[]" value="' . $tag . '">';
    link_for('index.php?page=compilations&tag[]=' . $tag . "#sortable", $tag);
    echo '</td>';
    if ($i % 5 == 0) {
        echo '</tr><tr>';
    }
}
echo '</tr></table></p>';

echo '<p>';
link_for("index.php?page=compilations", "Show all compilations", "box");
echo '</p>';
// CAUTION: Change back unsortable to sortable when going to production!!!
echo '<table id="un-sortable">';
echo '<thead><tr><th></th><th><input type="checkbox" id="chkSelectDeselectAll" onClick="SelectDeselect()">Select All</th>
<th>Compilation</th><th>Label in graph</th>';
for ($i = 0; $i < $maxTags; $i++) {
    echo '<th>#</th>';
}

echo '<th></th></tr></thead>';
foreach ($compilationList as $key => $compilation) {
    $associatedTags = Helper::filter_for_value($tags, "Compilation_Id", $compilation["id"]);
    $associatedTags = Helper::sql_select_columns($associatedTags, "Name");
    $attributedSourceUrl = "index.php?page=sources&source=" . $compilation["Source_Id"];
    
    if (count($tagsToShow) == 0 || count(array_intersect($associatedTags, $tagsToShow)) == count($tagsToShow)) {
        $associatedTags = array_pad($associatedTags, $maxTags, " ");
        echo '<tr>';
        echo '<td><input type="radio" class="mainComp" name="mainComp" value="' . $compilation["id"] . '"></td>';
        echo '<td><input type="checkbox" name="compilationId[]" value="' . $compilation["id"] . '"></td>
    <td>' . $compilation["Name"] . '<a class="tinyLink" href="' . $attributedSourceUrl . '"> [Source]</a> (' . $compilation["TimePeriod"] . ')</td>
    <td><input type="text" name="compilationShortNames[]" value="' . $compilationShortNames[$key] . '"></td>';
        foreach ($associatedTags as $tag) {
            echo '<td>'; 
            link_for(curPageURL() . '&tag[]=' . $tag, $tag); 
        echo '</td>';
        }
        echo '<td><input name="allCompilationIds[]" value="' . $compilation["id"] . '" hidden></td></tr>';
    }
}
echo '</table>';
echo "</form>";
?>