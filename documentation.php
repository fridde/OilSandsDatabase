<h1>Documentation</h1>
    
    <?php
$file =file_get_contents("README.md");
 echo Markdown::defaultTransform($file);
 

?>
