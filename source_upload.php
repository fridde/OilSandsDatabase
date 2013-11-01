<?php

include ("include_all.php");

// the update routine
if ($_REQUEST["password"] == "mikael") {
    $source_id = $_REQUEST["source_id"];
    $Source = ORM::for_table('osdb_Sources') -> find_one($source_id);

    if ($_REQUEST["delete"] == "DELETE") {
        $Source -> delete();
    } else {
 
        $SourceArray = $Source -> as_array();

        foreach ($SourceArray as $key => $value) {
            if (!(empty($_REQUEST[$key]))) {
                $Source -> set($key, $_REQUEST[$key]);
            }
        }
        // defining a new ShortName for later reference
        $ShortName = $_REQUEST["Institution"] . " " . array_shift(explode("-", $_REQUEST["PublicationDate"]));
        $availableShortNames = ORM::for_table('osdb_Sources') -> select("ShortName") -> find_array();
        if (in_array($ShortName, $availableShortNames)) {
            $ShortName = $SourceName;
        }
        $Source ->ShortName = $ShortName;
        
        $Source -> save();
        redirect("index.php?page=sources&source=" . $source_id);
    }

} else {
    echo "<h1>Wrong password!</h1>";
    sleep(5);
}

// if values are not updated, but posted for the first time

//defining values from $_POST
$SourceName = ucwords(strtolower($_POST["Source"]));
if (empty($_POST["SourceUrl"])) {
    $SourceUrl = "";
} else {
    $SourceUrl = $_POST["SourceUrl"];
}

$Institution = $_POST["Institution"];

if ($_POST["Institution"] == "none" and !(empty($_POST["NewInstitution"]))) {
    $Institution = $_POST["NewInstitution"];
}

$PublicationDate = $_POST["PublicationDate"] . "-01";

if (empty($_POST["Reported"])) {
    $Reported = "0";
} else {
    $Reported = "1";
}

if (empty($_POST["Prognosis"])) {
    $Prognosis = "0";
} else {
    $Prognosis = "1";
}

$TimeAccuracy = $_POST["TimeAccuracy"];
if ($TimeAccuracy == 0) {
    $TimeAccuracy = "";
}

if (!(empty($_POST["NewProduct"]))) {
    $Product = $_POST["NewProduct"];
} else {
    $Product = $_POST["Product"];
}
echo $Product . "<br>";
if (!(empty($_POST["NewUnit"]))) {
    $Unit = $_POST["NewUnit"];
} else {
    $Unit = $_POST["Unit"];
}

$RawData = $_POST["RawData"];
$SemiTidyData = $RawData;

$ShortName = $Institution . " " . array_shift(explode("-", $PublicationDate));
$availableShortNames = ORM::for_table('osdb_Sources') -> select("ShortName") -> find_array();
if (in_array($ShortName, $availableShortNames)) {
    $ShortName = $SourceName;
}

$newSource = ORM::for_table('osdb_Sources') -> create();

$newSource -> SourceName = $SourceName;
$newSource -> ShortName = $ShortName;
$newSource -> SourceUrl = $SourceUrl;
$newSource -> Institution = $Institution;
$newSource -> PublicationDate = $PublicationDate;
$newSource -> Prognosis = $Prognosis;
$newSource -> Reported = $Reported;
$newSource -> TimeAccuracy = $TimeAccuracy;
$newSource -> Product = $Product;
$newSource -> Unit = $Unit;
$newSource -> RawData = $RawData;
$newSource -> SemiTidyData = $SemiTidyData;
$newSource -> Description = $_REQUEST["Description"];


$newSource -> save();
redirect("index.php?page=sources&source=" . $source_id);
?>
