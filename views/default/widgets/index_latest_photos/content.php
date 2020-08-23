<?php
/**
 * Tidypics Plugin
 *
 * Index page Latest Photos widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->tp_latest_photos_count ?: 12;

$prev_context = elgg_get_context();
elgg_set_context('front');
echo elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'full_view' => false,
	'list_type_toggle' => false,
	'list_type' => 'gallery',
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
	'no_results' => elgg_echo('tidypics:widget:no_images'),
	'distinct' => false,
]);
elgg_set_context($prev_context);
