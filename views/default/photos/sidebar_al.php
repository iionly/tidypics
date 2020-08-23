<?php
/**
 * Sidebar view
 */

$base = elgg_get_site_url() . 'photos/';

elgg_register_menu_item('page', [
	'name' => 'A10_tiypics_siteimages',
	'text' => elgg_echo('collection:object:image:all'),
	'href' => $base . 'siteimagesall',
	'section' => 'A'
]);
elgg_register_menu_item('page', [
	'name' => 'A20_tiypics_albums',
	'text' => elgg_echo('collection:object:album:all'),
	'href' => $base . 'all',
	'section' => 'A'
]);

$page = elgg_extract('page', $vars);
$show_extended_sidebar_menu = elgg_get_plugin_setting('extended_sidebar_menu', 'tidypics');
if ($show_extended_sidebar_menu && ($page != 'upload')) {
	echo elgg_view('photos/sidebar/extended_menu', $vars);
}
switch ($page) {
	case 'upload':
		if (elgg_get_plugin_setting('quota', 'tidypics')) {
			echo elgg_view('photos/sidebar/quota', $vars);
		}
		break;
	case 'all':
		echo elgg_view('page/elements/comments_block', [
			'subtypes' => TidypicsAlbum::SUBTYPE,
		]);
		echo elgg_view('page/elements/tagcloud_block', [
			'subtypes' => TidypicsAlbum::SUBTYPE,
		]);
		break;
	case 'owner':
		echo elgg_view('page/elements/comments_block', [
			'subtypes' => TidypicsAlbum::SUBTYPE,
			'owner_guid' => elgg_get_page_owner_guid(),
		]);
		echo elgg_view('page/elements/tagcloud_block', [
			'subtypes' => TidypicsAlbum::SUBTYPE,
			'owner_guid' => elgg_get_page_owner_guid(),
		]);
		break;
}
