<?php
/**
 * Widget settings for newest albums
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$widget = elgg_extract('entity', $vars);

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
	'label' => elgg_echo('tidypics:widget:num_albums'),
	'max' => 25,
	'default' => 4,
]);
