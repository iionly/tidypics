<?php

/**
 * Group images
 *
 */

$container_guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($container_guid);
elgg_group_gatekeeper();
$container = get_entity($container_guid);
if(!($container instanceof ElggGroup)) {
	forward('', '404');
}

$db_prefix = elgg_get_config('dbprefix');
$filter = '';

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb($container->name);

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

// grab the html to display the most recent images
$result = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'owner_guid' => null,
	'joins' => ["join {$db_prefix}entities u on e.container_guid = u.guid"],
	'wheres' => ["u.container_guid = {$container_guid}"],
	'order_by' => "e.time_created desc",
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'tidypics-gallery',
]);

$title = elgg_echo('tidypics:siteimagesgroup', [$container->name]);

if (tidypics_can_add_new_photos(null, $container)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $container_guid,
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && !empty($result)) {
	elgg_require_js('tidypics/slideshow');
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => elgg_get_site_url() . "photos/siteimagesgroup/$container_guid",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => "<img src=\"" . elgg_get_simplecache_url("tidypics/slideshow.png") . "\" alt=\"".elgg_echo('album:slideshow')."\">",
		'title' => elgg_echo('album:slideshow'),
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo('tidypics:siteimagesgroup:nosuccess');
}
$body = elgg_view_layout('content', [
	'filter_override' => $filter,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'owner']),
]);

// Draw it
echo elgg_view_page($title, $body);
