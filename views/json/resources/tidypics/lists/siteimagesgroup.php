<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 16);

$container_guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($container_guid);
elgg_group_gatekeeper();
$container = get_entity($container_guid);

if($container instanceof ElggGroup) {
	$db_prefix = elgg_get_config('dbprefix');
	$images = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'owner_guid' => null,
		'joins' => ["join {$db_prefix}entities u on e.container_guid = u.guid"],
		'wheres' => ["u.container_guid = {$container_guid}"],
		'order_by' => "e.time_created desc",
		'limit' => $limit,
		'offset' => $offset,
	]);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
