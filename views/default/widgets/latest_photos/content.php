<?php
/**
 * Display the latest photos uploaded by an individual
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->num_display;
if ($limit < 1) {
	$limit = 8;
}
$owner = elgg_get_page_owner_entity();

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'owner_guid' => $owner->guid,
	'full_view' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'pagination' => false,
	'gallery_class' => 'tidypics-gallery-widget',
]);

if (empty($content)) {
	echo elgg_echo('tidypics:widget:no_images');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => "/photos/siteimagesowner/" . $owner->guid,
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);
echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);
