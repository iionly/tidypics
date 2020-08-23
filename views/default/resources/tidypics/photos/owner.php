<?php
/**
 * Show all the albums that belong to a user or group
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$username = elgg_extract('username', $vars);

$owner = get_user_by_username($username);
if (!$owner) {
	$owner = elgg_get_logged_in_user_entity();
}
if (!$owner) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_register_title_button(null, 'add', 'object', TidypicsAlbum::SUBTYPE);

elgg_push_collection_breadcrumbs('object', TidypicsAlbum::SUBTYPE, $owner);

if ($owner->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('collection:object:album');
} else {
	$title = elgg_echo('collection:object:album:owner', [$owner->getDisplayName()]);
}

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $owner->getGUID(),
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'preload_owners' => false,
	'distinct' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
	'no_results' => elgg_echo('tidypics:none'),
]);

$owner = elgg_get_logged_in_user_entity();
if (tidypics_can_add_new_photos(null, $owner)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $owner->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

$params = [
	'filter_value' => 'mine',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'owner']),
];

if (elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
	$params['filter_value'] = '';
	$params['filter'] = '';
}

$body = elgg_view_layout('default', $params);

echo elgg_view_page($title, $body);
