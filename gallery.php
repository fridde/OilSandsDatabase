<?php

link_for("index.php?page=image_gallery", "Pre-drawn graphs", "box");

$galleryArray = ORM::for_table("osdb_gallery") -> find_array();

foreach ($galleryArray as $object) {
    echo '<h1>' . $object["Title"] . '</h1>';
    switch ($object["Type"]) {
        case 'tableau' :
            echo '<div class="tableauPlaceholder" style="width: 1000px; height: 621px;">
                    <object class="tableauViz" width="1000" height="621" style="display:none;">
                    <param name="host_url" value="http%3A%2F%2Fpublic.tableausoftware.com%2F" /> 
                    <param name="site_root" value="" />
                    <param name="name" value="' . trim($object["Link"]) . '" />
                    <param name="tabs" value="no" />
                    <param name="toolbar" value="yes" />
                    <param name="animate_transition" value="yes" /><param name="display_static_image" value="yes" />
                    <param name="display_spinner" value="yes" /><param name="display_overlay" value="yes" />
                    <param name="display_count" value="yes" /></object></div>';
            break;

        case 'plotly' :
            echo '<iframe id="igraph" src="https://plot.ly/' . trim($object["Link"]) . '/1200/800/" width="1200" height="800" seamless="seamless" scrolling="no"></iframe>';
            break;

    }

}
?>