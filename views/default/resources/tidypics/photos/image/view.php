<?php
/**
 * View an image
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$photo_guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($photo_guid, 'object', TidypicsImage::SUBTYPE);

$photo = get_entity($photo_guid);

$album = $photo->getContainerEntity();
if (!($album instanceof TidypicsAlbum)) {
	return;
}

$album_container = $album->getContainerEntity();
if (!$album_container) {
	return;
}

// set page owner based on owner of photo album
elgg_set_page_owner_guid($album->getContainerGUID());
$owner = elgg_get_page_owner_entity();

$title = $photo->getTitle();

elgg_push_entity_breadcrumbs($photo, false);
 
$photo->addView();

if (elgg_get_plugin_setting('tagging', 'tidypics')) {
	elgg_require_css('tidypics/css/jquery-imgareaselect');
	elgg_require_js('tidypics/tagging');
}

if (!$owner instanceof ElggGroup) {
	$owner = elgg_get_logged_in_user_entity();
}

if (TidypicsTidypics::tidypics_can_add_new_photos(null, $owner)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $owner->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

$content = elgg_view_entity($photo, ['full_view' => true]);

$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'entity' => $photo,
	'sidebar' => elgg_view('photos/sidebar_im', [
		'page' => 'tp_view',
		'image' => $photo,
	]),
]);

echo elgg_view_page($title, $body);
