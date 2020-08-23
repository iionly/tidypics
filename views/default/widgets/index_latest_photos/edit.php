<?php
/**
 * Tidypics Plugin
 *
 * Index page Latest Photos widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'name' => 'tp_latest_photos_count',
	'label' => elgg_echo('tidypics:widget:num_latest'),
	'max' => 25,
	'default' => 12,
]);
