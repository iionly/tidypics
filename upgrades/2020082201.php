<?php

/**
 * Update view path of river entries for comments made on Tidypics images
 *
 * Due to an error in the name given for the TidypicsImage class within the tidypics_comments_handler plugin hook handler
 * ("Tidypics" instead of "TidypicsImage") the view column in the river table contained the wrong view path
 * (generic river/object/comment/create instead of river/object/comment/image). Therefore, displaying the preview image
 * did not work even if the corresponding Tidypics plugin setting was enabled
 * 
 * This update scripts updates the content of the view column for the river entries created for comments on images
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

$image_subtype_id = get_subtype_id('object', TidypicsImage::SUBTYPE);

// Get river entries for comments added to Tidypics images
$batch = elgg_get_river([
	'type' => 'object',
	'subtype' => 'comment',
	'action_type' => 'comment',
	'joins' => ["JOIN {$db_prefix}entities im ON im.guid = rv.target_guid"],
	'wheres' => ["im.subtype = $image_subtype_id"],
	'limit' => false,
	'batch' => true,
]);

// now collect the ids of the river items that need to be upgraded
$river_entry_ids = [];
foreach ($batch as $river_entry) {
	$river_entry_ids[] = $river_entry->id;
}

// and finally update the rows in the river table if there are any rows to update
if ($river_entry_ids) {
	$river_entry_ids = implode(', ', $river_entry_ids);
	$query = "UPDATE {$db_prefix}river
		SET view = 'river/object/comment/image'
		WHERE id IN ($river_entry_ids)";
	update_data($query);
}

elgg_set_ignore_access($ia);
access_show_hidden_entities($access_status);
