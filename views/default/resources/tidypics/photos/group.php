<?php
/**
 * Show all the albums that belong to a user or group
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$group_guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($group_guid, 'group');

elgg_group_tool_gatekeeper('photos', $group_guid);

$group = get_entity($group_guid);

elgg_register_title_button(null, 'add', 'object', TidypicsAlbum::SUBTYPE);

elgg_push_collection_breadcrumbs('object', TidypicsAlbum::SUBTYPE, $group);

$title = elgg_echo('collection:object:album:group');

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => (int) $group->guid,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'preload_containers' => false,
	'distinct' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
	'no_results' => elgg_echo('tidypics:none'),
]);

if (tidypics_can_add_new_photos(null, $group)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . ((int) $group->guid),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

$params = [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'owner']),
];

$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
