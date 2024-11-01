<?php
/**
 * View all albums on the site
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

elgg_register_title_button('add', 'object', TidypicsAlbum::SUBTYPE);

elgg_push_collection_breadcrumbs('object', TidypicsAlbum::SUBTYPE);

$title = elgg_echo('collection:object:album:all');

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);
 
$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'preload_owners' => true,
	'preload_containers' => true,
	'distinct' => false,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
	'no_results' => elgg_echo('tidypics:none'),
]);

$logged_in_user = elgg_get_logged_in_user_entity();
if (TidypicsTidypics::tidypics_can_add_new_photos(null, $logged_in_user)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_user->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

$body = elgg_view_layout('default', [
	'filter_value' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'all']),
]);

echo elgg_view_page($title, $body);
