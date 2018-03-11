<?php
/**
 * Album sort page
 *
 * This displays a listing of all the photos so that they can be sorted
 */

elgg_gatekeeper();
elgg_group_gatekeeper();

// get the album entity
$album_guid = elgg_extract('guid', $vars);
$album = get_entity($album_guid);

// panic if we can't get it
if (!($album instanceof TidypicsAlbum)) {
	forward('', '404');
}

// container should always be set, but just in case
$owner = $album->getContainerEntity();
elgg_set_page_owner_guid($owner->getGUID());

$title = elgg_echo('tidypics:sort', [$album->getTitle()]);

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:albums'), 'photos/all');
if ($owner instanceof ElggGroup) {
	elgg_push_breadcrumb($owner->name, "photos/group/$owner->guid/all");
} else {
	elgg_push_breadcrumb($owner->name, "photos/owner/$owner->username");
}
elgg_push_breadcrumb($album->getTitle(), $album->getURL());
elgg_push_breadcrumb(elgg_echo('album:sort'));

if ($album->getSize()) {
	$content = elgg_view_form('photos/album/sort', [], ['album' => $album]);
} else {
	$content = elgg_echo('tidypics:sort:no_images');
}

$body = elgg_view_layout('content', [
	'filter' => false,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_al', ['page' => 'upload']),
]);

echo elgg_view_page($title, $body);
