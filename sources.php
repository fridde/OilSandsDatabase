<?php

if (empty($_GET["source"])) {
    echo '<h1>Sources</h1>';
    link_for("index.php?page=source_upload_form", "Add Source", "box");
    $sources = ORM::for_table('osdb_Sources')->
    select_many('id', 'SourceName', 'Institution', 'SourceUrl', 'PublicationDate', 'Product')->where("Archived", 0)->
    order_by_asc('Institution')->find_array();
//     where_not_equal("Archived", 1)->
    foreach ($sources as $Source) {
        
        echo '<p><table class="tablesorter">';
        foreach ($Source as $key => $value) {
            switch ($key) {
                case 'id' :
                    $Source_id = $value;
                    break;

                case "SourceName" :
                    echo '<tr> <th>Source</th> <th>
                    <a href="index.php?page=sources&source=' . $Source_id . '">' . $value . '</a></th> </tr>';
                    break;

                case "SourceUrl" :
                    echo '<tr> <td>' . $key . '</td> <td>' . $value;
                    if ($value != "") {
                        echo '<a href="' . $value . '"> [link]</a>';
                    }
                    echo '</td> </tr>';
                    break;

                default :
                    echo '<tr> <td>' . $key . '</td> <td>' . $value . '</td> </tr>';
                    break;
            }

        }
        echo '</table></p>';
    };
} else {

    $buttonArray = ORM::for_table('osdb_Buttons') -> distinct() -> order_by_asc('ButtonName') -> find_array();
    echo '<ul id="buttonDescriptions" hidden>';
    foreach ($buttonArray as $button) {
        echo '<li class="buttonDescription" id="' . $button['ButtonName'] . '">' . htmlentities($button['Description']) . '</li>';
    }
    echo '</ul>';

    echo '<h1>Convert table</h1>';
    $table = ORM::for_table('osdb_Sources') -> find_one($_GET["source"]);

    $SourceName = $table -> SourceName;
    $Institution = $table -> Institution;
    $SourceUrl = $table -> SourceUrl;
    $PublicationDate = $table -> PublicationDate;
    $Unit = $table -> Unit;
    $RawData = $table -> RawData;
    $SemiTidyData = $table -> SemiTidyData;

    echo '<p><table class="tablesorter">
        <tr>
            <th>Source</th> 
            <th>' . $SourceName . '</th>
        </tr>
        <tr>
            <td>Institution</td> <td>' . $Institution . '</td>
        </tr>
            <td>Source Url</td> <td><a href="' . $SourceUrl . '">' . $SourceUrl . '</td>
        </tr>
            <td>Date of Publication</td> <td>' . $PublicationDate . '</td>
        </tr> 
                </tr>
            <td>Units</td> <td>' . $Unit . '</td>
        </tr>';

    echo '</table></p>';
    echo '<div>';
    $sourceId = $_GET["source"];
    echo '<form action="conversion.php" method="post" id="conversion">';
    echo 'First parameter<input type="text" name="par1"><br>
    Second parameter<input type="text" name="par2">';
    echo '<p><div class="buttons">';
    $i = 0;
    $rowlength = 80;
    foreach ($buttonArray as $button) {

        $buttonName = $button['ButtonName'];
        echo '<button class="buttonWithDescription" type="submit" formaction="conversion.php?source_id=' . $sourceId . '&button=' . $buttonName . '" value="' . $buttonName . '">' . $buttonName . '</button>';
        if ($i > $rowlength) {
            // $i = $i % $rowlength;
            $i = 0;
            echo '<br>';
        }
        $i += strlen($buttonName) + 5;
    }
    echo '</div></p>';
    echo '</form>';
    echo '<div id="buttonExplanation" hidden></div>';
    echo '</div>';

    echo '<div style="clear:both;">';
    if (empty($_GET["as_table"])) {
        echo '<p><textarea disabled>' . $SemiTidyData . '</textarea></p>';
    } else {
        echo Helper::create_html_from_csv($SemiTidyData);
    }
    echo '</div>';
}
?>

