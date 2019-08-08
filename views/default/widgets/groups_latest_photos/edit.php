<?php
/**
 * Tidypics Plugin
 *
 * Groups page Latest Photos widget for Widget Manager plugin
 *
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$count = (int) $widget->tp_latest_photos_count;
if ($count < 1) {
	$count = 12;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:widget:num_latest'),
	'name' => 'params[tp_latest_photos_count]',
	'value' => $count,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);
