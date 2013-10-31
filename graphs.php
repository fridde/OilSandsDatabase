<?php
if (isset($_REQUEST["compilationId"])) {
    echo '<ul id="compilation" hidden >';
    foreach ($_REQUEST["compilationId"] as $compilation) {
        echo '<li>' . $compilation . '</li>';
    }
    echo '</ul>';
}
if (isset($_REQUEST["compilationShortNames"])) {
    $compilationShortNames = array_combine($_REQUEST["allCompilationIds"], $_REQUEST["compilationShortNames"]);

    echo '<ul id="shortName" hidden>';
    foreach ($compilationShortNames as $compilationId => $shortName) {
        if (in_array($compilationId, $_REQUEST["compilationId"])) {
            echo '<li>' . $shortName . '</li>';
        }
    }
    echo '</ul>';
}
echo '<p hidden id="plotType">' .  $_REQUEST["plotType"] . '</p>';

?>


<div id="container">
	<div id="graph" class="column"></div>

		<div id="labeler" class="column"></div>
		<div id="choices" class="column">Show:</div>
	
</div>


<div id="overview" style="width:300px;height:70px"></div>

