<?php
/**
 * Convert river entries for tags to be tagger-tagee-annotation from
 * image-tagee
 */

// prevent timeout when script is running
set_time_limit(0);

elgg_register_plugin_hook_handler('permissions_check', 'all', 'elgg_override_permissions');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'elgg_override_permissions');

// Make sure that entries for disabled entities also get upgraded
elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {

$db_prefix = elgg_get_config('dbprefix');

$batch = elgg_get_river([
	'view' => 'river/object/image/tag',
	'limit' => false,
	'batch' => true,
]);

foreach ($batch as $river_entry) {
	$batch_annotations = elgg_get_annotations([
		'guid' => $river_entry->subject_guid,
		'annotation_name' => 'phototag',
		'limit' => false,
		'batch' => true,
	]);
	foreach ($batch_annotations as $annotation) {
		$tag = unserialize($annotation->value);
		if ($tag->type === 'user') {
			if ($tag->value == $river_entry->object_guid) {
				$query = "
					UPDATE {$db_prefix}river
					SET subject_guid = {$annotation->owner_guid}, annotation_id = {$annotation->id}
					WHERE id = {$river_entry->id}
				";
				update_data($query);
			}
		}
	}
}

});
