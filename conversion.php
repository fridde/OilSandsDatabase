<?php

include ("include_all.php");

$source_id = $_REQUEST["source_id"];
$table = ORM::for_table('osdb_Sources') -> find_one($source_id);
$text = $table -> SemiTidyData;

$button = $_REQUEST["button"];

if (empty($_REQUEST["par1"])) {
    $par1 = "";
} else {
    $par1 = $_REQUEST["par1"];
}
if (empty($_REQUEST["par2"])) {
    $par2 = "";
} else {
    $par2 = $_REQUEST["par2"];
}

$par1 = str_replace('\\\\', '\\', $par1);
$par2 = str_replace('\\\\', '\\', $par2);

if ($button === "ResetTable") {

    $table -> SemiTidyData = $table -> RawData;
    $table -> save();

    redirect("index.php?page=sources&source=" . $source_id);
} elseif ($button === "AsTable") {

    redirect("index.php?page=sources&as_table=true&source=" . $source_id);
} elseif ($button === "Undo") { 
            
        $table -> SemiTidyData = $table -> SemiTidyDataRecent;
        $table -> save();
        redirect("index.php?page=sources&source=" . $source_id);
} elseif ($button === "EditManually") {
    
    redirect("index.php?page=edit_source&source=" . $source_id);
    
} 

 else {
     
    $func = $button;
    $text = $func($text, $par1, $par2);
    
    
    $table -> SemiTidyDataRecent = $table -> SemiTidyData;
     $table -> SemiTidyData = $text;
     $table -> save();
     redirect("index.php?page=sources&source=" . $source_id);
     
}
?>
