<?php
include_once "include_all.php";
$sourceList = ORM::for_table('osdb_Sources') -> order_by_asc('Institution') -> find_many();



foreach ($sourceList as $source) {
    $sourceArray = Helper::csvstring_to_array($source->SemiTidyData);
    $header = $sourceArray[0];
    
   
    foreach ($header as $col) {
        echo $col . ", " ;
        
    }
    
}
?>
</form>