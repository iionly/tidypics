<?php
/**
 * This displays the photos that belong to an album
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

// get the album entity
$album_guid = elgg_extract('guid', $vars);
$album = get_entity($album_guid);
if (!($album instanceof TidypicsAlbum)) {
	forward('', '404');
}
$container = $album->getContainerEntity();
if (!$container) {
	forward('', '404');
}

elgg_set_page_owner_guid($album->getContainerGUID());
$owner = elgg_get_page_owner_entity();
elgg_group_gatekeeper();

$title = elgg_echo($album->getTitle());

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:albums'), 'photos/all');
if ($owner instanceof ElggGroup) {
	elgg_push_breadcrumb($owner->name, "photos/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
}
elgg_push_breadcrumb($album->getTitle());

$content = elgg_view_entity($album, ['full_view' => true]);

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

if ($album->canWriteToContainer(0, 'object', TidypicsImage::SUBTYPE)) {
	elgg_register_menu_item('title', [
			'name' => 'upload',
			'href' => 'photos/upload/' . $album->getGUID(),
			'text' => elgg_echo('images:upload'),
			'link_class' => 'elgg-button elgg-button-action',
	]);
}

// only show sort button if there are images
if ($album->canEdit() && $album->getSize() > 0) {
	elgg_register_menu_item('title', [
		'name' => 'sort',
		'href' => "photos/sort/" . $album->getGUID(),
		'text' => elgg_echo('album:sort'),
		'link_class' => 'elgg-button elgg-button-action',
		'priority' => 200,
	]);
}

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && ($album->getSize() > 0)) {
	elgg_require_js('tidypics/slideshow');
	$offset = (int) get_input('offset', 0);
	$limit = (int) get_input('limit', 16);
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => $album->getURL() . "?guid={$album->guid}",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => "<img src=\"" . elgg_get_simplecache_url("tidypics/slideshow.png") . "\" alt=\"".elgg_echo('album:slideshow')."\">",
		'title' => elgg_echo('album:slideshow'),
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
		'priority' => 300,
	]);
}

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $album->getTitle(),
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'album']),
]);

echo elgg_view_page($title, $body);
