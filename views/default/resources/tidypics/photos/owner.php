<?php
/**
 * Show all the albums that belong to a user or group
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_group_gatekeeper();

$owner = elgg_get_page_owner_entity();

if (!$owner) {
	forward('', '404');
}

$title = elgg_echo('album:user', [$owner->name]);

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:albums'), 'photos/all');
elgg_push_breadcrumb($owner->name);

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'container_guid' => $owner->getGUID(),
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
	'no_results' => elgg_echo('tidypics:none'),
]);

if (!$owner instanceof ElggGroup) {
	$owner = elgg_get_logged_in_user_entity();
}

if (tidypics_can_add_new_photos(null, $owner)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $owner->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

elgg_register_title_button(null, 'add', 'object', TidypicsAlbum::SUBTYPE);

$params = [
	'filter_context' => 'mine',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'owner']),
];

// don't show filter if out of filter context
if ($owner instanceof ElggGroup) {
	$params['filter'] = false;
}

if (elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
	$params['filter_context'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
