<?php
/**
 * List all the albums of someone's friends
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_gatekeeper();

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:albums'), 'photos/all');
elgg_push_breadcrumb($owner->name, "photos/friends/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo('album:friends');

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

if ($friends = $owner->getFriends(['limit' => false])) {
	$friendguids = [];
	foreach ($friends as $friend) {
		$friendguids[] = $friend->getGUID();
	}
	$content = elgg_list_entities([
		'type' => 'object',
		'subtype' => TidypicsAlbum::SUBTYPE,
		'owner_guids' => $friendguids,
		'limit' => $limit,
		'offset' => $offset,
		'full_view' => false,
		'pagination' => true,
		'list_type' => 'gallery',
		'list_type_toggle' => false,
		'gallery_class' => 'tidypics-gallery',
		'no_results' => elgg_echo('tidypics:none'),
	]);
} else {
	$content = elgg_echo("friends:none:you");
}

$logged_in_user = elgg_get_logged_in_user_entity();
if (tidypics_can_add_new_photos(null, $logged_in_user)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_user->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

elgg_register_title_button();

$body = elgg_view_layout('content', [
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'friends']),
]);

echo elgg_view_page($title, $body);
