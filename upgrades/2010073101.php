<?php
/**
 * Populate image lists for current photo albums
 */

// prevent timeout when script is running
set_time_limit(0);

elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

// Make sure that entries for disabled entities also get upgraded
elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {

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

});
