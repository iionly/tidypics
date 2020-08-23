<?php
/**
 * Tidypics Plugin
 *
 * Index page Latest Albums widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_albums_count ?: 6;

elgg_push_context('front');
echo elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('tidypics:widget:no_albums'),
	'distinct' => false,
]);
elgg_pop_context();
