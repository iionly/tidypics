<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) {
		$qb->innerJoin($alias, 'entities', 'ce', "ce.container_guid = e.guid");
		$qb->orderBy('ce.time_created', 'DESC');
		return $qb->compare('ce.subtype', '=', 'comment', ELGG_VALUE_STRING);
	},
]);

echo tidypics_slideshow_json_data($images);
