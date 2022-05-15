<?php

/**
 * Most recently uploaded images - logged in user's images
 *
 */

elgg_require_js('tidypics/tidypics');

$owner = elgg_get_page_owner_entity();

if (!$owner) {
	$guid = elgg_extract('guid', $vars);
	$owner = get_user($guid);
}

if (!$owner) {
	$username = elgg_extract('username', $vars);
	$owner = get_user_by_username($username);
}

if (!$owner) {
	$owner = elgg_get_logged_in_user_entity();
}

if (!($owner instanceof ElggUser)) {
	throw new \Elgg\Exceptions\Http\EntityNotFoundException();
}

elgg_push_collection_breadcrumbs('object', TidypicsImage::SUBTYPE, $owner, true);

$title = elgg_echo('collection:friends', [elgg_echo('collection:object:image')]);

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$result = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'relationship' => 'friend',
	'relationship_guid' => $owner->guid,
	'relationship_join_on' => 'owner_guid',
	'distinct' => false,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'pagination' => true,
	'list_type' => 'gallery',
	'list_type_toggle' => false,
	'gallery_class' => 'tidypics-gallery',
]);

$logged_in_user = elgg_get_logged_in_user_entity();
if (TidypicsTidypics::tidypics_can_add_new_photos(null, $logged_in_user)) {
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
		'text' => '<i class="far fa-images"></i>',
		'title' => elgg_echo('album:slideshow'),
		'item_class' => 'tidypics-slideshow-button',
		'link_class' => 'elgg-button elgg-button-action tidypics-slideshow-lightbox',
	]);
}

if (!empty($result)) {
	$content = $result;
} else {
	$content = elgg_echo("tidypics:siteimagesfriends:nosuccess");
}

$body = elgg_view_layout('default', [
	'filter_id' => 'tidypics_siteimages_tabs',
	'filter_value' => 'friends',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'friends']),
]);

// Draw it
echo elgg_view_page($title, $body);
