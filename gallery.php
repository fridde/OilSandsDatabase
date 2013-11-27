<?php

$tableauArray = ORM::for_table("osdb_gallery") -> where("Type", "tableau") -> find_array();

foreach ($tableauArray as $tableau) {

    echo '<div class="tableauPlaceholder" style="width: 1000px; height: 621px;">
<object class="tableauViz" width="1000" height="621" style="display:none;">
<param name="host_url" value="http%3A%2F%2Fpublic.tableausoftware.com%2F" /> 
<param name="site_root" value="" />
<param name="name" value="' . trim($tableau["Link"]) . '" />
<param name="tabs" value="no" />
<param name="toolbar" value="yes" />
<param name="animate_transition" value="yes" /><param name="display_static_image" value="yes" />
<param name="display_spinner" value="yes" /><param name="display_overlay" value="yes" />
<param name="display_count" value="yes" /></object></div>';
}

// <param name="static_image" value="http:&#47;&#47;public.tableausoftware.com&#47;static&#47;images&#47;Pr&#47;ProductionTarSands2012&#47;Sheet1&#47;1.png" / >
// ProductionTarSands2012/Sheet1
?>