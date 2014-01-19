<?php

/**
 * Update view path of river entries for comments made on Tidypics images, albums and tidypics_batches (image uploads)
 *
 * This is a follow-up upgrade to be executed AFTER the Elgg core upgrade from Elgg 1.8 to Elgg 1.9.
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

// TODO
// Situation 1 with Tidypics upgrade script 2014010101.php NOT EXECUTED:
//
// Case 1. There are river table entries that have a target_guid pointing to entities of subtype tiypics_batch
//			and the object_guid is a comment entity with a container_guid pointing to the same tidypics_batch entity.
//
// ---> these are due to uploads done with the official Tidypics and there's no duplicate of the comment (with container_guid pointing to album)
//
// To be done by looping through these river table entries
// ---> update river table entry to have album_guid as target_guid and access_id of album as access_id
// ---> update the comment entities (object_guid of river entry) to have album_guid as container_guid and access_id of album as access_id
//
// Case 2. Any remaining comment entities that have a container_guid pointing to a tidypics_batch should have been created with a newer release of Tidypics
//			and there should be duplicate comment entities for those
//
// ---> loop through comments that have container_guid pointing to entities of subtype tidypics_batch
// ---> delete these comments
//		??? can entities be deleted when looping through them using a batch???
//
//
// Situation 2 with Tidypics upgrade script 2014010101.php ALREADY EXECUTED:
//
// Problem: river table entries that originally had still the tidypics_batch in their target_guid have been updated and therefore "Case 1" from "Situation 1" can no longer get resolved. 
//
// At the time I wrote the 2014010101.php upgrade script updating the comment entities was ommited intentionally because these comments still showed on the activity page inline.
// But now the album / image comments are shown instead and if these comment entities are not updated accordingly they are no longer visible on the site.
// EVEN WORSE: as a consequence the action done for "Case 2" of "Situation 1" would result in comments getting deleted that have no duplicate!
//
// Possible solution:
// --->Loop through comment entities with container_guids pointing to tidypics_batch entirues instead.
//		For each comment entity found:
//		--> check if there is a river table entry with the guid of this comment as object_guid
//			--> if not: delete comment as it should exist a duplicate comment entity
//			--> if yes: update comment entity to have album_guid as container_guid and album's access_id as access_id
//						update the river table entry to have album guid as target, update access_id to match album's access_id and update view to 'river/object/comment/album'
//
// This solution does not take into account if the tidypics_batch has one or more images to not make it even more complicated.
// Otherwise, the comment entity and river table entity would have to be updated to have the image's guid as container_guid and target_guid respectively
// in case there's only a single image in the tidypics_batch.
//
//
// I don't know if Situation 2 should be considered. One could argue that Elgg 1.9 has not yet been officially released
// and also I released my versions of Tidypics always as "beta" releases so far. There might not many people have used the Elgg 1.9 version of Tidypics yet on a site updated from Elgg 1.8.
// Still, the deletion of comments that have no duplicate would be a rather nasty thing not to avoid.
//
// I'm also not sure if I really considered all cases correctly.

// Get river entries for comments added to Tidypics batches
$batch = new ElggBatch('elgg_get_river', array(
	'type' => 'object',
	'subtype' => 'comment',
	'action_type' => 'comment',
	'joins' => array("JOIN {$db_prefix}entities te ON te.guid = rv.target_guid"),
	'wheres' => array("te.subtype" => $tidypics_batch_subtype_id),
	'limit' => false
));

foreach ($batch as $river_entry) {
	$target_entity = get_entity($river_entry->target_guid);
	$album = get_entity($target_entity->container_guid);
	
	// fix container_guid and access_id of comment entity
	$object = get_entity($river_entry->object_guid);
	$object->container_guid = $album->guid;
	$object->access_id = $album->access_id;
	$object->save();

	// now fix target_guid and access_id for river entry
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
