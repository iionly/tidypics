<?php
/**
 * Widget settings for latest photos
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$count = (int) $widget->num_display;
if ($count < 1) {
	$count = 8;
}

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('tidypics:widget:num_latest'),
	'name' => 'params[num_display]',
	'value' => $count,
	'min' => 1,
	'max' => 25,
	'step' => 1,
]);
