<?php
/**
 * List albums in a widget
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

/* @var $widget ElggWidget */
$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->num_display;
if ($limit < 1) {
	$limit = 4;
}

$owner = elgg_get_page_owner_entity();

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $owner->guid,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
]);

if (empty($content)) {
	echo elgg_echo('tidypics:widget:no_albums');
	return;
}

echo $content;

$more_link = elgg_view('output/url', [
	'href' => "/photos/owner/" . $owner->username,
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);
echo elgg_format_element('div', ['class' => 'elgg-widget-more'], $more_link);
