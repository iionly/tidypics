<?php
/**
 * Tidypics Plugin
 *
 * Index page Latest Albums widget for Widget Manager plugin
 *
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'name' => 'tp_latest_albums_count',
	'label' => elgg_echo('tidypics:widget:num_albums'),
	'max' => 25,
	'default' => 6,
]);
