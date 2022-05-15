<?php
/**
 * This displays the photos that belong to an album
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_require_js('tidypics/tidypics');

$album_guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($album_guid, 'object', TidypicsAlbum::SUBTYPE);

// get the album entity
$album = get_entity($album_guid);

elgg_set_page_owner_guid($album->getContainerGUID());
$owner = elgg_get_page_owner_entity();

$title = $album->getTitle();

elgg_push_entity_breadcrumbs($album, false);

$content = elgg_view_entity($album, ['full_view' => true]);

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

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && ($album->getSize() > 0)) {
	elgg_require_js('tidypics/slideshow');
	$offset = (int) get_input('offset', 0);
	$limit = (int) get_input('limit', 25);
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => $album->getURL() . "?guid={$album->guid}",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => '<i class="far fa-images"></i>',
		'title' => elgg_echo('album:slideshow'),
		'item_class' => 'tidypics-slideshow-button',
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
		'priority' => 300,
	]);
}

$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'entity' => $album,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'album']),
]);

echo elgg_view_page($title, $body);
