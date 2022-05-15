<?php

/**
 * Most recently uploaded images - all images
 *
 */

elgg_require_js('tidypics/tidypics');

elgg_push_collection_breadcrumbs('object', TidypicsImage::SUBTYPE);

$title = elgg_echo('collection:object:image:all');

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

// grab the html to display the most recent images
$result = elgg_list_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'full_view' => false,
	'preload_owners' => true,
	'preload_containers' => true,
	'distinct' => false,
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
		'data-slideshowurl' => elgg_get_site_url() . "photos/siteimagesall",
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
	$content = elgg_echo('tidypics:siteimagesall:nosuccess');
}

$params = [
	'filter_id' => 'tidypics_siteimages_tabs',
	'filter_value' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('photos/sidebar_im', ['page' => 'all']),
];

if (!elgg_is_logged_in()) {
	$params['filter_value'] = '';
	$params['filter'] = '';
}

$body = elgg_view_layout('default', $params);

// Draw it
echo elgg_view_page($title, $body);
