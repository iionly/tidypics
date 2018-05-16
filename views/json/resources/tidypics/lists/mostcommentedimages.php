<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$db_prefix = elgg_get_config('dbprefix');
$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'selects' => ["count( * ) AS views"],
	'joins' => [
		"JOIN {$db_prefix}entities ce ON ce.container_guid = e.guid",
		"JOIN {$db_prefix}entity_subtypes cs ON ce.subtype = cs.id AND cs.subtype = 'comment'",
	],
	'group_by' => 'e.guid',
	'order_by' => "views DESC",
]);

echo tidypics_slideshow_json_data($images);