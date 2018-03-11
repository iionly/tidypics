<?php
/**
 * Populate image lists for current photo albums
 */

// prevent timeout when script is running
set_time_limit(0);

// Ignore access to make sure all items get updated
$ia = elgg_set_ignore_access(true);

elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

// Make sure that entries for disabled entities also get upgraded
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$batch = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'limit' => false,
	'batch' => true,
]);

foreach ($batch as $album) {
	$batch_images = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'container_guid' => $album->guid,
		'limit' => false,
		'batch' => true,
	]);
	$image_list = [];
	foreach ($batch_images as $image) {
		$image_list[] = $image->guid;
	}
	$album->prependImageList($image_list);
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
