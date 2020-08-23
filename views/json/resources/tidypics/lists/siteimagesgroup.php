<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$group_guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($group_guid);
elgg_group_gatekeeper();
$group = get_entity($group_guid);

if($group instanceof ElggGroup) {
	$images = elgg_get_entities([
		'type' => 'object',
		'subtype' => TidypicsImage::SUBTYPE,
		'owner_guid' => null,
		'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) use($group_guid) {
			$qb->innerJoin($alias, 'entities', 'u', "u.guid = e.container_guid");
			$qb->orderBy('e.time_created', 'DESC');
			return $qb->compare('u.container_guid', '=', $group_guid, ELGG_VALUE_INTEGER);
		},
		'limit' => $limit,
		'offset' => $offset,
	]);
	echo tidypics_slideshow_json_data($images);
} else {
	echo json_encode([]);
}
