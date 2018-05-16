<?php

/**
 * Most recently uploaded images - logged in user's images
 *
 */

elgg_gatekeeper();

$owner = elgg_get_logged_in_user_entity();

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('photos'), 'photos/siteimagesall');
elgg_push_breadcrumb($owner->name, "photos/siteimagesfriends/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

if ($friends = $owner->getFriends(['limit' => false])) {
	$friendguids = [];
	foreach ($friends as $friend) {
		$friendguids[] = $friend->getGUID();
	}
	$result = elgg_list_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'owner_guids' => $friendguids,
		'limit' => $limit,
		'offset' => $offset,
		'full_view' => false,
		'pagination' => true,
		'list_type' => 'gallery',
		'list_type_toggle' => false,
		'gallery_class' => 'tidypics-gallery',
	]);

	if (!empty($result)) {
		$content = $result;
	} else {
		$content = elgg_echo("tidypics:siteimagesfriends:nosuccess");
	}
} else {
	$content = elgg_echo("friends:none:you");
}

$title = elgg_echo('tidypics:siteimagesfriends');

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
		'data-slideshowurl' => elgg_get_site_url() . "photos/siteimagesfriends/{$owner->username}",
		'data-limit' => $limit,
		'data-offset' => $offset,
		'href' => 'ajax/view/photos/galleria',
		'text' => "<img src=\"" . elgg_get_simplecache_url("tidypics/slideshow.png") . "\" alt=\"".elgg_echo('album:slideshow')."\">",
		'title' => elgg_echo('album:slideshow'),
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

$body = elgg_view_layout('content', [
	'filter_override' => elgg_view('filter_override/siteimages', ['selected' => 'friends']),
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'friends']),
]);

// Draw it
echo elgg_view_page($title, $body);
