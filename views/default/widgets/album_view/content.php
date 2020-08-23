<?php
/**
 * List albums in a widget
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$widget = elgg_extract('entity', $vars);

$limit = (int) $widget->num_display ?: 4;

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $widget->owner_guid,
	'limit' => $limit,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('tidypics:widget:no_albums'),
]);

$more_link = elgg_view('output/url', [
	'href' => elgg_generate_url('collection:object:album:owner', [
		'username' => $widget->getOwnerEntity()->username,
	]),
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
]);
echo "<div class=\"elgg-widget-more\">$more_link</div>";
