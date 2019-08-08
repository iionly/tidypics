<?php
/**
 * Tidypics Plugin
 *
 * Groups page Latest Albums widget for Widget Manager plugin
 *
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$count = (int) $widget->tp_latest_albums_count;
if ($count < 1) {
	$count = 6;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:widget:num_albums'),
	'name' => 'params[tp_latest_albums_count]',
	'value' => $count,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);
