<?php

/**
 * Update view path of river entries for comments made on Tidypics images, albums and tidypics_batches (image uploads)
 *
 * This is a follow-up upgrade to be executed after the Elgg core upgrade from Elgg 1.8 to Elgg 1.9.
 * The Elgg core upgrade script changes comments from annotations to entities and updates the river entries accordingly.
 * This Tidypics-specific script then updates the views referred in river entries for comments made on Tidypics entities
 * to allow for using the Tidypics-specific river comment views (which add optionally a thumbnail image of the image/album
 * commented on and takes the specifics of commenting on tidypics_batches into account)
 */

// prevent timeout when script is running (thanks to Matt Beckett for suggesting)
set_time_limit(0);

// Ignore access to make sure all items get updated
$ia = elgg_set_ignore_access(true);

elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

// Make sure that entries for disabled entities also get upgraded
$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

$db_prefix = elgg_get_config('dbprefix');

$image_subtype_id = get_subtype_id('image');
$album_subtype_id = get_subtype_id('album');
$tidypics_batch_subtype_id = get_subtype_id('tidypics_batch');

/**
 * Upgrade comments added on Tidypics images
 */

// Get river entries for comments added to Tidypics images
$batch = new ElggBatch('elgg_get_river', array(
	'type' => 'object',
	'subtype' => 'comment',
	'action_type' => 'comment',
	'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid"),
	'wheres' => array("te.subtype = $image_subtype_id"),
	'limit' => false
));

// Collect the ids of the river items that need to be upgraded
$river_entry_ids = array();
foreach ($batch as $river_entry) {
	$river_entry_ids[] = $river_entry->id;
}

$river_entry_ids = implode(', ', $river_entry_ids);
$query = "UPDATE {$db_prefix}river
	SET view = 'river/object/comment/image'
	WHERE id IN ($river_entry_ids)";
update_data($query);

/**
 * Upgrade comments added on Tidypics albums
 */

// Get river entries for comments added to Tidypics albums
$batch = new ElggBatch('elgg_get_river', array(
	'type' => 'object',
	'subtype' => 'comment',
	'action_type' => 'comment',
	'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid"),
	'wheres' => array("te.subtype = $album_subtype_id"),
	'limit' => false
));

// Collect the ids of the river items that need to be upgraded
$river_entry_ids = array();
foreach ($batch as $river_entry) {
	$river_entry_ids[] = $river_entry->id;
}

$river_entry_ids = implode(', ', $river_entry_ids);
$query = "UPDATE {$db_prefix}river
	SET view = 'river/object/comment/album'
	WHERE id IN {$river_entry_ids}";
update_data($query);

/**
 * Upgrade comments added on Tidypics image batches
 */

// Get river entries for comments added to Tidypics batches
$batch = new ElggBatch('elgg_get_river', array(
	'type' => 'object',
	'subtype' => 'comment',
	'action_type' => 'comment',
	'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid"),
	//"JOIN {$db_prefix}entity_subtypes ts ON te.subtype = ts.id AND ts.subtype = 'tidypics_batch'"),
	'wheres' => array("te.subtype" => $tidypics_batch_subtype_id),
	'limit' => false
));

// TODO Is this doing the correct kind of upgrade?
foreach ($batch as $river_entry) {
	// fix target_guid and access_id for river entries that do not yet point to the album
	$target_entity = get_entity($river_entry->target_guid);
	$album = get_entity($target_entity->container_guid);
	$query = "
				UPDATE {$db_prefix}river
				SET view = 'river/object/comment/album',
						access_id = {$album->access_id},
						target_guid = {$album->guid}
				WHERE id = {$river_entry->id}
		";
	update_data($query);
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
