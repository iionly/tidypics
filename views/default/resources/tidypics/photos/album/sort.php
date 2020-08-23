<?php
/**
 * Album sort page
 *
 * This displays a listing of all the photos so that they can be sorted
 */

elgg_require_js('tidypics/tidypics');

$album_guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($album_guid, 'object', TidypicsAlbum::SUBTYPE);

// get the album entity
$album = get_entity($album_guid);

// container should always be set, but just in case
$owner = $album->getContainerEntity();
elgg_set_page_owner_guid($owner->getGUID());

$title = elgg_echo('sort:object:album', [$album->getTitle()]);

elgg_push_entity_breadcrumbs($album, false);

if ($album->getSize()) {
	$content = elgg_view_form('photos/album/sort', [], ['album' => $album]);
} else {
	$content = elgg_echo('tidypics:sort:no_images');
}

$body = elgg_view_layout('default', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
