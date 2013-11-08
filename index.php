<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link type="text/css" rel="stylesheet" href="stylesheet.css"/>

		<title>Oil Sand Database</title>
	</head>
	<body>
		<?php
        include_once "include_all.php";
		?>

		<div id="header">
			<a href="index.php"> <h1>Oil Sands Database</h1> </a>
		</div>

		<div id="navbar">
				<?php
                $linkArray = array("Home" => "home", "Documentation" => "documentation", "Sources" => "sources", "Send sources to database" => "add_database_form", "Refine data" => "refine_tables_form", "Create Compilations" => "working_tables_form&table_type=data", "Compilations" => "compilations", "Ranking"=>"ranking", "Administration" => "administration_form");
                foreach ($linkArray as $label => $url) {
                    link_for("index.php?page=" . $url, $label, "box");
                }
				?>


		</div>

		<div id="main">
			<?php
            if (isset($_GET["page"])) {
                include $_GET["page"] . ".php";
            } else {
                include "home.php";
            }
			?>
		</div>

		<div id="footer"></div>
	</body>
</html>
