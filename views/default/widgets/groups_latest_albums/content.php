<?php
/**
 * Tidypics Plugin
 *
 * Groups page Latest Albums widget for Widget Manager plugin
 *
 */

// get widget settings
/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_albums_count;
if ($limit < 1) {
	$limit = 6;
}

$group = elgg_get_page_owner_entity();
$group_guid = $group->getGUID();

$prev_context = elgg_get_context();
elgg_set_context('groups');
$image_html = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $group_guid,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
]);
elgg_set_context($prev_context);

if ($group->canWriteToContainer(0, 'object', TidypicsAlbum::SUBTYPE)) {
	$image_html .= elgg_view('output/url', [
		'href' => "photos/add/" . $group_guid,
		'text' => elgg_echo('photos:add'),
		'is_trusted' => true,
	]);
}

echo $image_html;
