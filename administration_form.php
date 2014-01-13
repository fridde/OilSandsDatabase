<?php

link_for("index.php?page=button_form", "Add Button", "box");
link_for("index.php?page=edit_buttons", "Edit Buttons", "box");
echo '<form action="index.php?page=refine_tables" method="post">
<p><input type="text" name="password"> <b>Password for certain actions</b></p>
<p>
<select name="Table">';
$Tables = ORM::for_table("information_schema.tables") -> where("TABLE_SCHEMA", $ini_array["mysql_db"]) -> find_array();

foreach ($Tables as $Table) {
    echo '<option value="' . $Table["TABLE_NAME"] . '">' . $Table["TABLE_NAME"] . '</option>';
}
echo '</select>
<input type="submit" name="choice" value="Remove duplicates"><input type="submit" name="choice" value="Empty Table">
</p>';
echo '<p>
<select name="Source">';
$sources = ORM::for_table("osdb_sources")->order_by_asc("Institution")->find_array();
foreach($sources as $source){
    $name = $source["Institution"]. " - " . $source["SourceName"] . " - " . reset(explode("-", $source["PublicationDate"]));
    echo '<option value="' . $source["id"] . '">' . $name . '</option>'; 
}
echo '</select><br>
<input type="submit" name="choice" value="Remove source"> 
<input type="checkbox" name="archive" value="archive"> Archive source </p>' ; 

echo '<p>
<select name="Compilation">';
$compilations = ORM::for_table("osdb_compilations")->order_by_asc("Name")->find_array();
foreach($compilations as $compilation){
    echo '<option value="' . $compilation["id"] . '">' . $compilation["Name"] . '</option>'; 
}
echo '</select><br>
<input type="submit" name="choice" value="Remove compilation"></p>';

echo '<p><input type="submit" name="choice" value="Recalculate Ranking">';


$toCalculate = ORM::for_table('osdb_errors_to_calculate') -> count();

if($toCalculate > 0){
    echo '<input type="submit" name="choice" value="Calculate errors" class="redButton">';
} else {
    echo '<input type="submit" name="choice" value="Empty error table">';
}
echo " " . $toCalculate . " more to calculate";


?>
<p><h2>Add synonyms or abbreviations</h2></p>
<p><input type="submit" name="choice" value="Add synonyms">
<input type="radio" name="synonym_type" value="Synonym" id="synonym" checked>
<label for="synonym">Synonym</label>
<input type="radio" name="synonym_type" value="Abbreviation" id="abbreviation">
<label for="abbreviation">Abbreviation</label>
</p>
<table class="short_table">
    <tr><th>Synonym</th><th>Replacement</th></tr>
<?php
for ($i=0; $i < 15 ; $i++) { 
	echo '<tr id="synonyms"><td><input type="text" name="synonym[]"></td>
	<td><input type="text" name="replacement[]"></td></tr>';
}
 ?>    
</table>
</form>
