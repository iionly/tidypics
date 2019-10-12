<?php

/**
 * Update river entries for image uploads and tags
 *
 * The target_guid of existing river entries is set to the corresponding album's guid
 * for the river entries to show up in the activity lists of groups if the
 * album the images belong to or have been tagged is a group album
 *
 */

// prevent timeout when script is running
set_time_limit(0);

elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

// Make sure that entries for disabled entities also get upgraded
elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {

$db_prefix = elgg_get_config('dbprefix');


$batch = elgg_get_river([
	'type' => 'object',
	'subtype' => TidypicsAlbum::SUBTYPE,
	'action_type' => 'create',
	'limit' => false,
	'batch' => true,
]);
foreach ($batch as $river_entry) {
	$query = "
		UPDATE {$db_prefix}river
		SET target_guid = {$river_entry->object_guid}
		WHERE id = {$river_entry->id}
	";
	update_data($query);
}

$batch = elgg_get_river([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'action_type' => 'create',
	'limit' => false,
	'batch' => true,
]);
foreach ($batch as $river_entry) {
	$image = get_entity($river_entry->object_guid);
	$query = "
		UPDATE {$db_prefix}river
		SET target_guid = {$image->container_guid}
		WHERE id = {$river_entry->id}
	";
	update_data($query);
}

$batch = elgg_get_river([
	'type' => 'object',
	'subtype' => TidypicsBatch::SUBTYPE,
	'action_type' => 'create',
	'limit' => false,
	'batch' => true,
]);
foreach ($batch as $river_entry) {
	$tidypics_batch = get_entity($river_entry->object_guid);
	$query = "
		UPDATE {$db_prefix}river
		SET target_guid = {$tidypics_batch->container_guid}
		WHERE id = {$river_entry->id}
	";
	update_data($query);
}

$batch = elgg_get_river([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'action_type' => 'tag',
	'limit' => false,
	'batch' => true,
]);
foreach ($batch as $river_entry) {
	$tag_annotation = elgg_get_annotation_from_id($river_entry->annotation_id);
	$image = get_entity($tag_annotation->entity_guid);
	$query = "
		UPDATE {$db_prefix}river
		SET target_guid = {$image->container_guid}
		WHERE id = {$river_entry->id}
	";
	update_data($query);
}

$batch = elgg_get_river([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'action_type' => 'wordtag',
	'limit' => false,
	'batch' => true,
]);
foreach ($batch as $river_entry) {
	$image = get_entity($river_entry->object_guid);
	$query = "
		UPDATE {$db_prefix}river
		SET target_guid = {$image->container_guid}
		WHERE id = {$river_entry->id}
	";
	update_data($query);
}

});
