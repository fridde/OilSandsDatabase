<?php
include_once "include_all.php";

if ($_REQUEST['update_only'] != "true") {

    $buttonName = $_POST['button_name'];
    $par1 = $_POST["par1"];
    $par2 = $_POST["par2"];
    $buttonContent = $_POST['button_content'];
    //echo "par2 is : " .$par2;
    // Strip all whitespace from buttonName
    $buttonName = preg_replace('/\s+/', '', $buttonName);

    $newFunction = "function " . $buttonName . '($text';
    if (!(empty($par1))) {
        $newFunction .= ', $' . $par1;
    } else {
        $newFunction .= ', $par1 = ""';
    }
    if (!(empty($par2))) {
        $newFunction .= ', $' . $par2;
    } else {
        $newFunction .= ', $par2 = ""';

    }
    $newFunction .= ') {' . $buttonContent . "}";
    $newFunction = preg_replace('/\s\s+/', ' ', $newFunction);
    $newFunction = str_replace('\"', '"', $newFunction);
    $newButton = ORM::for_table('osdb_Buttons') -> create();

    $newButton -> ButtonName = $buttonName;
    $newButton -> ButtonContent = $newFunction;
    $newButton -> save();

}
$buttonString = "<?php ";

foreach (ORM::for_table('osdb_Buttons')->find_result_set() as $button) {
    $buttonString .= $button -> ButtonContent . "\n\n";
};
$buttonString .= " ?>";

file_put_contents("include/buttons.php", $buttonString);

redirect("index.php?page=sources");
        ?>
