<?php
/**
 * Tidypics Plugin
 *
 * Index page Latest Albums widget for Widget Manager plugin
 *
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_albums_count;
if ($limit < 1) {
	$limit = 6;
}

elgg_push_context('front');
$image_html = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
]);
elgg_pop_context();

echo $image_html;
