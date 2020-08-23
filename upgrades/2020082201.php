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

elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () {

	// Get river entries for comments added to Tidypics images
	$batch = elgg_get_river([
		'type' => 'object',
		'subtype' => 'comment',
		'action_type' => 'comment',
		'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) {
			$qb->innerJoin($alias, 'entities', 'im', "im.guid = rv.target_guid");
			return $qb->compare('im.subtype', '=', TidypicsImage::SUBTYPE, ELGG_VALUE_STRING);
		},
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

		$qb = \Elgg\Database\Update::table('river');
		$qb->set("view", $qb->param("river/object/comment/image", ELGG_VALUE_STRING))->where($qb->compare("id", "IN", ($river_entry_ids)));
		elgg()->db->updateData($qb);
	}
});

elgg_flush_caches();
