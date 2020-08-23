<?php

$offset = (int) get_input('offset', 0);
$limit = (int) get_input('limit', 25);

$start = mktime(0, 0, 0, date("m"), 1, date("Y"));

$images = elgg_get_entities([
	'type' => 'object',
	'subtype' => TidypicsImage::SUBTYPE,
	'limit' => $limit,
	'offset' => $offset,
	'wheres' => function(\Elgg\Database\QueryBuilder $qb, $alias) use($start, $end) {
		$qb->groupBy("$alias.guid");
		$qb->innerJoin($alias, 'entities', 'ce', "ce.container_guid = e.guid");
		$qb->addSelect("count( * ) as views");
		$qb->orderBy('views', 'DESC');
		return $qb->merge([
			$qb->compare('ce.subtype', '=', 'comment', ELGG_VALUE_STRING),
			$qb->compare('ce.time_created', '>', $start, ELGG_VALUE_INTEGER),
		], 'AND');
	},
]);

echo tidypics_slideshow_json_data($images);
