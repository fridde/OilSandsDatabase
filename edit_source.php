<?php

$source_id = $_REQUEST["source"];
$table = ORM::for_table('osdb_sources') -> select_many("SourceName", "Institution", "SourceUrl", 
"PublicationDate", "Prognosis", "Prognosis", "Reported", "TimeAccuracy", "Unit", "SemiTidyData", "Archived", "Description") 
-> find_one($source_id)->as_array();


echo '<form action="source_upload.php" method="post"> 
            <p><table>';
        foreach ($table as $key => $value) {
               if($key != "SemiTidyData") {
           echo    
           '<tr>
                    <td>' . $key . '</td><td><input type="text" name="' . $key . '" value= "' . $value . '"></td>
           </tr>';
               } 
        }
       echo '</table></p>
       <p>Password to change files<input type="text" name="password"></p>
        <p><input type="submit" value="Update source"></p>
       <p><textarea name="SemiTidyData" placeholder="Enter edited data here">' . $table["SemiTidyData"] . '</textarea></p>
       <p><textarea disabled>' . $table["SemiTidyData"] . '</textarea></p>
       <input hidden type="text" name="source_id" value="' . $source_id . '>
       </form>';
        
?>