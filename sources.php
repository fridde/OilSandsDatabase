<?php

if (empty($_GET["source"])) {
    echo '<h1>Sources</h1>';
    link_for("index.php?page=data_upload_form", "Add Source", "box");
    foreach (ORM::for_table('osdb_Sources')->
            select_many('id', 'SourceName', 'Institution', 'SourceUrl', 'PublicationDate', 'Product')
            ->order_by_asc('Institution')->find_result_set()->as_array() as $Source_set) {
        $Source = $Source_set->as_array();

        echo '<p><table class="tablesorter">';
        foreach ($Source as $key => $value) {
            if ($key === "id") {
                $Source_id = $value;
            }
            if ($key === "SourceName") {
                echo '<tr> <th>Source</th> <th>
                    <a href="index.php?page=sources&source=' .
                $Source_id .
                '">' . $value . '</a></th> </tr>';
            } elseif ($key != "id") {
                echo '<tr> <td>' . $key . '</td> <td>' . $value . '</td> </tr>';
            }
        }
        echo '</table></p>';
    };
} else {
    echo '<h1>Convert table</h1>';
    $table = ORM::for_table('osdb_Sources')->find_one($_GET["source"]);

    $SourceName = $table->SourceName;
    $Institution = $table->Institution;
    $SourceUrl = $table->SourceUrl;
    $PublicationDate = $table->PublicationDate;
    $Unit = $table->Unit;
    $RawData = $table->RawData;
    $SemiTidyData = $table->SemiTidyData;

    echo '<p><table class="tablesorter">
        <tr>
            <th>Source</th> 
            <th>' . $SourceName . '</th>
        </tr>
        <tr>
            <td>Institution</td> <td>' . $Institution . '</td>
        </tr>
            <td>Source Url</td> <td>' . $SourceUrl . '</td>
        </tr>
            <td>Date of Publication</td> <td>' . $PublicationDate . '</td>
        </tr> 
                </tr>
            <td>Units</td> <td>' . $Unit . '</td>
        </tr>';

    echo '</table></p>';

    $buttonNames = ORM::for_table('osdb_Buttons')->distinct()->
                    select('ButtonName')->find_result_set();
    $sourceId = $_GET["source"];
    echo '<form action="conversion.php" method="post" id="conversion">';
    echo 'First parameter<input type="text" name="par1"><br>
    Second parameter<input type="text" name="par2">';
    echo '<p><div class="buttons">';
    foreach ($buttonNames as $buttonName) {
        $buttonName = $buttonName->ButtonName;
        echo '<button type="submit" formaction="conversion.php?source_id=' .
        $sourceId . '&button=' . $buttonName . '">' . $buttonName . '</button>';
    }
    echo '</div></p>';
    echo '</form>';


    if (empty($_GET["as_table"])) {
        echo '<p><textarea disabled>' . $SemiTidyData . '</textarea></p>';
    } else {
        echo Helper::create_html_from_csv($SemiTidyData);
    }
}
?>

