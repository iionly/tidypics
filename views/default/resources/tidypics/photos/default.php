<?php

$site_menu_links_to = elgg_get_plugin_setting('site_menu_link', 'tidypics');
if ($site_menu_links_to == 'albums') {
	echo elgg_view_resource('tidypics/photos/all', $vars);
} else {
	echo elgg_view_resource('tidypics/lists/siteimagesall', $vars);
}
