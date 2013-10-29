<?php
$buttonArray = ORM::for_table('osdb_Buttons') -> distinct() -> order_by_asc('ButtonName') -> find_array();

echo '<form action="index.php?page=refine_tables" method="post">
<input hidden type="text" name="choice" value="Edit Buttons">
<input type="submit" value="Update buttons">';
foreach($buttonArray as $button){
    echo '<p><input hidden type="text" name="ButtonName[]" value="'. $button["ButtonName"] . '">' . $button["ButtonName"] . '<br>
    <textarea style="height:150px;" name="ButtonDescription[]">'. $button["Description"] . '</textarea></p>';
}
echo '</form>';

 ?>