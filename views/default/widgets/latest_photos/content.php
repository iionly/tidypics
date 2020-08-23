<?php
/**
 * Display the latest photos uploaded by an individual
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->num_display ?: 8;

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'owner_guid' => $widget->owner_guid,
	'full_view' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
	'no_results' => elgg_echo('tidypics:widget:no_images'),
]);

$more_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:image:owner', [
		'guid' => $widget->owner_guid,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
