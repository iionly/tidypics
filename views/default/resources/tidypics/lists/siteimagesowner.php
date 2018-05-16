<?php

/**
 * Most recently uploaded images - logged in user's images
 *
 */

$owner_guid = elgg_extract('guid', $vars);
$owner = get_entity($owner_guid);
if (!($owner instanceof ElggUser)) {
	if (!$owner = elgg_get_logged_in_user_entity()) {
		// no one logged in and no user guid provided so error
		forward('', '404');
	} else {
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		$filter = elgg_view('filter_override/siteimages', ['selected' => 'mine']);
	}
} else if ($owner_guid == elgg_get_logged_in_user_guid()) {
	$filter = elgg_view('filter_override/siteimages', ['selected' => 'mine']);
} else {
	elgg_set_page_owner_guid($owner_guid);
	$filter = elgg_view('filter_override/siteimages', ['selected' => '']);
}

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb($owner->name);

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

// grab the html to display the most recent images
$result = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'owner_guid' => $owner->guid,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'list_type' => 'gallery',
	'gallery_class' => 'tidypics-gallery',
]);

$title = elgg_echo('tidypics:siteimagesowner', [$owner->name]);

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
		'data-slideshowurl' => elgg_get_site_url() . "photos/siteimagesowner",
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
	$content = elgg_echo('tidypics:siteimagesowner:nosuccess');
}
$body = elgg_view_layout('content', [
	'filter_override' => $filter,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'owner']),
]);

// Draw it
echo elgg_view_page($title, $body);
