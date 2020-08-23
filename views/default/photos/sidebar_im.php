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
if ($show_extended_sidebar_menu && ($page != 'upload') && ($page != 'tp_view')) {
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
			'subtypes' => TidypicsImage::SUBTYPE,
		]);
		echo elgg_view('page/elements/tagcloud_block', [
			'subtypes' => TidypicsImage::SUBTYPE,
		]);
		break;
	case 'owner':
		echo elgg_view('page/elements/comments_block', [
			'subtypes' => TidypicsImage::SUBTYPE,
			'owner_guid' => elgg_get_page_owner_guid(),
		]);
		echo elgg_view('page/elements/tagcloud_block', [
			'subtypes' => TidypicsImage::SUBTYPE,
			'owner_guid' => elgg_get_page_owner_guid(),
		]);
		break;
	case 'tp_view':
		$image = elgg_extract('image', $vars);
		if ($image) {
			if (elgg_get_plugin_setting('exif', 'tidypics')) {
				echo elgg_view('photos/sidebar/exif', $vars);
			}

			// list of tagged members in an image (code from Tagged people plugin by Kevin Jardine)
			if (elgg_get_plugin_setting('tagging', 'tidypics')) {
				$body = elgg_list_entities([
					'relationship' => 'phototag',
					'relationship_guid' => $image->guid,
					'inverse_relationship' => true,
					'type' => 'user',
					'limit' => 15,
					'list_type' => 'gallery',
					'gallery_class' => 'elgg-gallery-users',
					'pagination' => false,
				]);
				if ($body) {
					$title = elgg_echo('tidypics_tagged_members');
					echo elgg_view_module('aside', $title, $body);
				}
			}
		}
		break;
}
