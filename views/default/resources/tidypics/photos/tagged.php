<?php
/**
 * Tidypics Tagged Listing
 *
 * List all photos tagged with a user
 */

elgg_gatekeeper();

// Get user guid (of logged in user, so everyone only gets the images their tagged in)
$guid = elgg_get_logged_in_user_guid();

$user = get_entity($guid);

if(!($user instanceof ElggUser)) {
	forward('', '404');
}

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb(elgg_echo('tidypics:usertagged'));

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$title = elgg_echo('tidypics:usertag', [$user->name]);
$result = elgg_list_entities_from_relationship([
	'relationship' => 'phototag',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => false,
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'tidypics-gallery',
	'no_results' => elgg_echo('tidypics:usertags_photos:nosuccess'),
]);

$logged_in_user = elgg_get_logged_in_user_entity();
if (tidypics_can_add_new_photos(null, $logged_in_user)) {
	elgg_register_menu_item('title', [
		'name' => 'addphotos',
		'href' => "ajax/view/photos/selectalbum/?owner_guid=" . $logged_in_user->getGUID(),
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
		'text' => "<img src=\"" . elgg_get_simplecache_url("tidypics/slideshow.png") . "\" alt=\"".elgg_echo('album:slideshow')."\">",
		'title' => elgg_echo('album:slideshow'),
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo('tidypics:usertags_photos:nosuccess');
}

$body = elgg_view_layout('content', [
	'filter_override' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'friends']),
]);

// Draw it
echo elgg_view_page($title, $body);
