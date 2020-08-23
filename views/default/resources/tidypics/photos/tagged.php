<?php
/**
 * Tidypics Tagged Listing
 *
 * List all photos tagged with a user
 */

elgg_require_js('tidypics/tidypics');

// Get user guid (of logged in user, so everyone only gets the images their tagged in)
$guid = elgg_get_logged_in_user_guid();

$user = get_entity($guid);

if(!($user instanceof ElggUser)) {
	throw new \Elgg\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', TidypicsImage::SUBTYPE, $owner);

$title = elgg_echo('collection:object:image:usertagged');

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$result = elgg_list_entities([
	'relationship' => 'phototag',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => false,
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'preload_owners' => true,
	'preload_containers' => true,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
]);

if (tidypics_can_add_new_photos(null, $user)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $user->getGUID(),
		'text' => elgg_echo("photos:addphotos"),
		'link_class' => 'elgg-button elgg-button-action tidypics-selectalbum-lightbox',
	]);
}

// only show slideshow link if slideshow is enabled in plugin settings and there are images
if (elgg_get_plugin_setting('slideshow', 'tidypics') && !empty($result)) {
	elgg_require_js('tidypics/slideshow');
	elgg_register_menu_item('title', [
		'name' => 'slideshow',
		'id' => 'slideshow',
		'data-slideshowurl' => elgg_get_site_url() . "photos/tagged?guid={$user->guid}",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => '<i class="far fa-images"></i>',
		'title' => elgg_echo('album:slideshow'),
		'item_class' => 'tidypics-slideshow-button',
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo('tidypics:usertags_photos:nosuccess');
}

$body = elgg_view_layout('default', [
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'friends']),
]);

// Draw it
echo elgg_view_page($title, $body);
